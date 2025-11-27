<?php

namespace App\Services\Documents;

use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentValidationService
{
    /**
     * Maximum file size in bytes (10MB).
     */
    protected int $maxFileSize = 10 * 1024 * 1024;

    /**
     * Allowed MIME types.
     */
    protected array $allowedMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/jpg',
        'image/png',
    ];

    /**
     * Allowed file extensions.
     */
    protected array $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

    /**
     * Validate a document file.
     *
     * @return array{valid: bool, errors: array, warnings: array, metadata: array}
     */
    public function validateFile(UploadedFile $file, ?DocumentType $documentType = null): array
    {
        $errors = [];
        $warnings = [];
        $metadata = [];

        // 1. Validate file format
        $formatValidation = $this->validateFormat($file);
        if (! $formatValidation['valid']) {
            $errors = array_merge($errors, $formatValidation['errors']);
        }
        $metadata['format'] = $formatValidation['metadata'] ?? [];

        // 2. Validate file size
        $sizeValidation = $this->validateSize($file);
        if (! $sizeValidation['valid']) {
            $errors = array_merge($errors, $sizeValidation['errors']);
        }
        $metadata['size'] = $sizeValidation['metadata'] ?? [];

        // 3. Validate file signature (magic bytes)
        $signatureValidation = $this->validateSignature($file);
        if (! $signatureValidation['valid']) {
            $warnings = array_merge($warnings, $signatureValidation['warnings']);
        }
        $metadata['signature'] = $signatureValidation['metadata'] ?? [];

        // 4. Validate document-specific requirements
        if ($documentType) {
            $documentValidation = $this->validateDocumentTypeRequirements($file, $documentType);
            if (! $documentValidation['valid']) {
                $errors = array_merge($errors, $documentValidation['errors']);
            }
            $metadata['document_type'] = $documentValidation['metadata'] ?? [];
        }

        // 5. Check for duplicates
        $duplicateCheck = $this->checkDuplicates($file);
        if ($duplicateCheck['duplicate']) {
            $warnings[] = 'Documento com conteúdo idêntico já existe no sistema.';
            $metadata['duplicate'] = $duplicateCheck['metadata'] ?? [];
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'metadata' => $metadata,
        ];
    }

    /**
     * Validate an existing document.
     *
     * @return array{valid: bool, errors: array, warnings: array, metadata: array}
     */
    public function validateDocument(Document $document): array
    {
        $errors = [];
        $warnings = [];
        $metadata = [];

        // 1. Check if file exists
        if (! Storage::disk('public')->exists($document->file_path)) {
            $errors[] = 'Arquivo do documento não encontrado no sistema de armazenamento.';

            return [
                'valid' => false,
                'errors' => $errors,
                'warnings' => $warnings,
                'metadata' => $metadata,
            ];
        }

        // 2. Validate expiry date
        $expiryValidation = $this->validateExpiryDate($document);
        if (! $expiryValidation['valid']) {
            $errors = array_merge($errors, $expiryValidation['errors']);
        }
        $metadata['expiry'] = $expiryValidation['metadata'] ?? [];

        // 3. Validate file integrity (hash check)
        $integrityValidation = $this->validateFileIntegrity($document);
        if (! $integrityValidation['valid']) {
            $errors = array_merge($errors, $integrityValidation['errors']);
        }
        $metadata['integrity'] = $integrityValidation['metadata'] ?? [];

        // 4. Check for required translation
        if ($document->documentType && $document->documentType->requires_translation && ! $document->has_translation) {
            $errors[] = 'Documento requer tradução juramentada, mas nenhuma foi fornecida.';
        }

        // 5. Check duplicate documents
        $duplicateCheck = $this->checkDocumentDuplicates($document);
        if ($duplicateCheck['duplicate']) {
            $warnings[] = 'Documento com conteúdo idêntico já existe no sistema.';
            $metadata['duplicate'] = $duplicateCheck['metadata'] ?? [];
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'metadata' => $metadata,
        ];
    }

    /**
     * Validate file format (MIME type and extension).
     */
    protected function validateFormat(UploadedFile $file): array
    {
        $errors = [];
        $metadata = [];

        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        $metadata['mime_type'] = $mimeType;
        $metadata['extension'] = $extension;

        if (! in_array($mimeType, $this->allowedMimeTypes)) {
            $errors[] = "Tipo MIME '{$mimeType}' não é permitido. Tipos permitidos: ".implode(', ', $this->allowedMimeTypes);
        }

        if (! in_array($extension, $this->allowedExtensions)) {
            $errors[] = "Extensão '{$extension}' não é permitida. Extensões permitidas: ".implode(', ', $this->allowedExtensions);
        }

        // Additional check: verify extension matches MIME type
        $expectedExtensions = [
            'application/pdf' => ['pdf'],
            'application/msword' => ['doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
        ];

        if (isset($expectedExtensions[$mimeType]) && ! in_array($extension, $expectedExtensions[$mimeType])) {
            $warnings[] = "Extensão '{$extension}' não corresponde ao tipo MIME '{$mimeType}'.";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'metadata' => $metadata,
        ];
    }

    /**
     * Validate file size.
     */
    protected function validateSize(UploadedFile $file): array
    {
        $errors = [];
        $metadata = [];

        $fileSize = $file->getSize();
        $metadata['size_bytes'] = $fileSize;
        $metadata['size_mb'] = round($fileSize / (1024 * 1024), 2);
        $metadata['max_size_bytes'] = $this->maxFileSize;
        $metadata['max_size_mb'] = round($this->maxFileSize / (1024 * 1024), 2);

        if ($fileSize > $this->maxFileSize) {
            $errors[] = "Tamanho do arquivo ({$metadata['size_mb']} MB) excede o limite permitido ({$metadata['max_size_mb']} MB).";
        }

        if ($fileSize === 0) {
            $errors[] = 'Arquivo está vazio (0 bytes).';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'metadata' => $metadata,
        ];
    }

    /**
     * Validate file signature (magic bytes).
     */
    protected function validateSignature(UploadedFile $file): array
    {
        $warnings = [];
        $metadata = [];

        try {
            $fileHandle = fopen($file->getRealPath(), 'rb');
            if (! $fileHandle) {
                return [
                    'valid' => false,
                    'warnings' => ['Não foi possível ler o arquivo para verificar a assinatura.'],
                    'metadata' => [],
                ];
            }

            $signature = fread($fileHandle, 12);
            fclose($fileHandle);

            $metadata['signature_hex'] = bin2hex($signature);
            $metadata['signature_detected'] = $this->detectFileTypeBySignature($signature);

            $mimeType = $file->getMimeType();
            $expectedSignature = $this->getExpectedSignature($mimeType);

            if ($expectedSignature && strpos(bin2hex($signature), $expectedSignature) !== 0) {
                $warnings[] = 'Assinatura do arquivo não corresponde ao tipo MIME declarado. O arquivo pode estar corrompido ou ter extensão incorreta.';
            }
        } catch (\Exception $e) {
            Log::warning('Erro ao validar assinatura do arquivo: '.$e->getMessage());
            $warnings[] = 'Não foi possível validar a assinatura do arquivo.';
        }

        return [
            'valid' => true,
            'warnings' => $warnings,
            'metadata' => $metadata,
        ];
    }

    /**
     * Validate document type specific requirements.
     */
    protected function validateDocumentTypeRequirements(UploadedFile $file, DocumentType $documentType): array
    {
        $errors = [];
        $metadata = [];

        // Check if document type requires specific format
        // This can be extended based on document type configuration
        if ($documentType->requires_translation) {
            $metadata['requires_translation'] = true;
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'metadata' => $metadata,
        ];
    }

    /**
     * Check for duplicate documents by hash.
     */
    protected function checkDuplicates(UploadedFile $file): array
    {
        $metadata = [];
        $storageService = app(DocumentStorageService::class);
        $hash = $storageService->calculateHash($file);
        $metadata['hash'] = $hash;

        // Check if a document with the same hash exists
        $existingDocument = Document::where('file_hash', $hash)
            ->where('id', '!=', null) // Exclude current document if updating
            ->first();

        if ($existingDocument) {
            $metadata['duplicate_document_id'] = $existingDocument->id;
            $metadata['duplicate_document_path'] = $existingDocument->file_path;

            return [
                'duplicate' => true,
                'metadata' => $metadata,
            ];
        }

        return [
            'duplicate' => false,
            'metadata' => $metadata,
        ];
    }

    /**
     * Check for duplicate documents for an existing document.
     */
    protected function checkDocumentDuplicates(Document $document): array
    {
        $metadata = [];

        if (! $document->file_hash) {
            return [
                'duplicate' => false,
                'metadata' => $metadata,
            ];
        }

        $existingDocument = Document::where('file_hash', $document->file_hash)
            ->where('id', '!=', $document->id)
            ->first();

        if ($existingDocument) {
            $metadata['duplicate_document_id'] = $existingDocument->id;
            $metadata['duplicate_document_path'] = $existingDocument->file_path;
            $metadata['duplicate_person_id'] = $existingDocument->person_id;

            return [
                'duplicate' => true,
                'metadata' => $metadata,
            ];
        }

        return [
            'duplicate' => false,
            'metadata' => $metadata,
        ];
    }

    /**
     * Validate document expiry date.
     */
    protected function validateExpiryDate(Document $document): array
    {
        $errors = [];
        $metadata = [];

        if (! $document->expiry_date) {
            $metadata['has_expiry'] = false;

            return [
                'valid' => true,
                'errors' => $errors,
                'metadata' => $metadata,
            ];
        }

        $metadata['has_expiry'] = true;
        $metadata['expiry_date'] = $document->expiry_date->format('Y-m-d');
        $metadata['is_expired'] = $document->expiry_date->isPast();

        if ($document->expiry_date->isPast()) {
            $errors[] = "Documento expirado em {$document->expiry_date->format('d/m/Y')}.";
        } elseif ($document->expiry_date->isBefore(now()->addDays(30))) {
            $metadata['expires_soon'] = true;
            $metadata['days_until_expiry'] = now()->diffInDays($document->expiry_date);
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'metadata' => $metadata,
        ];
    }

    /**
     * Validate file integrity by comparing stored hash with calculated hash.
     */
    protected function validateFileIntegrity(Document $document): array
    {
        $errors = [];
        $metadata = [];

        if (! $document->file_hash) {
            return [
                'valid' => true,
                'errors' => $errors,
                'metadata' => ['hash_not_set' => true],
            ];
        }

        try {
            $storageService = app(DocumentStorageService::class);
            $calculatedHash = $storageService->calculateHashFromPath($document->file_path, 'public');

            $metadata['stored_hash'] = $document->file_hash;
            $metadata['calculated_hash'] = $calculatedHash;
            $metadata['integrity_check'] = $calculatedHash === $document->file_hash;

            if ($calculatedHash !== $document->file_hash) {
                $errors[] = 'Integridade do arquivo comprometida: hash calculado não corresponde ao hash armazenado.';
            }
        } catch (\Exception $e) {
            Log::warning('Erro ao validar integridade do arquivo: '.$e->getMessage());
            $errors[] = 'Não foi possível validar a integridade do arquivo.';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'metadata' => $metadata,
        ];
    }

    /**
     * Detect file type by signature (magic bytes).
     */
    protected function detectFileTypeBySignature(string $signature): ?string
    {
        $signatureHex = bin2hex($signature);

        $signatures = [
            '25504446' => 'PDF', // %PDF
            '504b0304' => 'ZIP/DOCX', // PK..
            'd0cf11e0' => 'DOC', // Microsoft Office
            'ffd8ff' => 'JPEG',
            '89504e47' => 'PNG', // PNG
        ];

        foreach ($signatures as $sig => $type) {
            if (str_starts_with($signatureHex, $sig)) {
                return $type;
            }
        }

        return null;
    }

    /**
     * Get expected signature for a MIME type.
     */
    protected function getExpectedSignature(string $mimeType): ?string
    {
        return match ($mimeType) {
            'application/pdf' => '25504446', // %PDF
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '504b0304', // ZIP/DOCX
            'application/msword' => 'd0cf11e0', // DOC
            'image/jpeg', 'image/jpg' => 'ffd8ff',
            'image/png' => '89504e47',
            default => null,
        };
    }
}
