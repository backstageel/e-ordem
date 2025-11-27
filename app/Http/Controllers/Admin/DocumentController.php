<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DocumentsExport;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     */
    public function index(Request $request)
    {
        $query = Document::with(['person', 'person.member', 'documentType', 'validatedBy', 'registration']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', \App\Enums\DocumentStatus::from($request->status));
        }

        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('person', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('expiry_date_from')) {
            $query->where('expiry_date', '>=', $request->expiry_date_from);
        }

        if ($request->filled('expiry_date_to')) {
            $query->where('expiry_date', '<=', $request->expiry_date_to);
        }

        if ($request->filled('submission_date_from')) {
            $query->where('submission_date', '>=', $request->submission_date_from);
        }

        if ($request->filled('submission_date_to')) {
            $query->where('submission_date', '<=', $request->submission_date_to);
        }

        if ($request->filled('has_expired')) {
            $query->where('expiry_date', '<', now());
        }

        if ($request->filled('needs_translation')) {
            $query->needsTranslation();
        }

        $documents = $query->latest()->paginate(10);

        // Get options for selects from backend
        $documentTypes = DocumentType::active()->get();
        $statusOptions = \App\Enums\DocumentStatus::options();

        return view('documents.index', compact('documents', 'documentTypes', 'statusOptions'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(Request $request)
    {
        $selectedPerson = null;
        if ($request->filled('person_id')) {
            $selectedPerson = \App\Models\Person::findOrFail($request->person_id);
        }

        $documentTypes = DocumentType::active()->get();
        $persons = \App\Models\Person::orderBy('first_name')->orderBy('last_name')->limit(500)->get();

        return view('documents.create', compact('documentTypes', 'selectedPerson', 'persons'));
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'person_id' => 'required|exists:people,id',
            'document_type_id' => 'required|exists:document_types,id',
            'document_file' => [
                'required',
                'file',
                'max:10240', // 10MB max
                'mimes:pdf,doc,docx,jpg,jpeg,png', // Allowed file formats
            ],
            'translation_file' => [
                'nullable',
                'file',
                'max:10240', // 10MB max
                'mimes:pdf,doc,docx,jpg,jpeg,png', // Allowed file formats
            ],
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $documentType = DocumentType::findOrFail($request->document_type_id);
        $person = \App\Models\Person::findOrFail($request->person_id);

        DB::beginTransaction();

        try {
            $document = new Document;
            $document->person_id = $person->id;

            // Set member_id if person has a member
            if ($person->member) {
                $document->member_id = $person->member->id;
            }

            $document->document_type_id = $request->document_type_id;

            if ($request->filled('registration_id')) {
                $document->registration_id = $request->registration_id;
            }

            // Handle document file upload with compression
            $file = $request->file('document_file');
            $storageService = app(\App\Services\Documents\DocumentStorageService::class);
            $stored = $storageService->store($file, 'public', 'member-documents');

            $document->file_path = $stored['path'];
            $document->original_filename = $stored['original_filename'];
            $document->mime_type = $stored['mime_type'];
            $document->file_size = $stored['size'];
            if (isset($stored['hash'])) {
                $document->file_hash = $stored['hash'];
            }
            $document->status = \App\Enums\DocumentStatus::PENDING;
            $document->submission_date = now();

            if ($request->filled('expiry_date')) {
                $document->expiry_date = $request->expiry_date;
            }

            if ($request->filled('notes')) {
                $document->notes = $request->notes;
            }

            // Handle translation file upload if required with compression
            if ($documentType->requires_translation && $request->hasFile('translation_file')) {
                $translationFile = $request->file('translation_file');
                $storageService = app(\App\Services\Documents\DocumentStorageService::class);
                $translationStored = $storageService->store($translationFile, 'public', 'member-documents/translations');

                $document->has_translation = true;
                $document->translation_file_path = $translationStored['path'];
            }

            $document->save();

            DB::commit();

            return redirect()->route('admin.documents.show', $document)
                ->with('success', 'Document uploaded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Failed to upload document: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        $document->load(['member', 'documentType', 'validatedBy']);

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document)
    {
        $document->load(['member', 'documentType']);
        $documentTypes = DocumentType::active()->get();

        return view('documents.edit', compact('document', 'documentTypes'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'document_type_id' => 'required|exists:document_types,id',
            'document_file' => [
                'nullable',
                'file',
                'max:10240', // 10MB max
                'mimes:pdf,doc,docx,jpg,jpeg,png', // Allowed file formats
            ],
            'translation_file' => [
                'nullable',
                'file',
                'max:10240', // 10MB max
                'mimes:pdf,doc,docx,jpg,jpeg,png', // Allowed file formats
            ],
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $documentType = DocumentType::findOrFail($request->document_type_id);

        DB::beginTransaction();

        try {
            $document->document_type_id = $request->document_type_id;

            // Handle document file upload if a new file is provided
            if ($request->hasFile('document_file')) {
                // Delete old file
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }

                $file = $request->file('document_file');
                $storageService = app(\App\Services\Documents\DocumentStorageService::class);
                $stored = $storageService->store($file, 'public', 'member-documents');

                $document->file_path = $stored['path'];
                $document->original_filename = $stored['original_filename'];
                $document->mime_type = $stored['mime_type'];
                $document->file_size = $stored['size'];
                if (isset($stored['hash'])) {
                    $document->file_hash = $stored['hash'];
                }
                $document->status = \App\Enums\DocumentStatus::PENDING; // Reset status if file is changed
                $document->submission_date = now();
                $document->validation_date = null;
                $document->validated_by = null;
            }

            if ($request->filled('expiry_date')) {
                $document->expiry_date = $request->expiry_date;
            } else {
                $document->expiry_date = null;
            }

            if ($request->filled('notes')) {
                $document->notes = $request->notes;
            }

            // Handle translation file upload if required and a new file is provided
            if ($documentType->requires_translation && $request->hasFile('translation_file')) {
                // Delete old translation file
                if ($document->translation_file_path && Storage::disk('public')->exists($document->translation_file_path)) {
                    Storage::disk('public')->delete($document->translation_file_path);
                }

                $translationFile = $request->file('translation_file');
                $storageService = app(\App\Services\Documents\DocumentStorageService::class);
                $translationStored = $storageService->store($translationFile, 'public', 'member-documents/translations');

                $document->has_translation = true;
                $document->translation_file_path = $translationStored['path'];
            }

            $document->save();

            DB::commit();

            return redirect()->route('admin.documents.show', $document)
                ->with('success', 'Document updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Failed to update document: '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Document $document)
    {
        try {
            // Delete the document files
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            if ($document->translation_file_path && Storage::disk('public')->exists($document->translation_file_path)) {
                Storage::disk('public')->delete($document->translation_file_path);
            }

            $document->delete();

            return redirect()->route('admin.documents.index')
                ->with('success', 'Document deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete document: '.$e->getMessage()]);
        }
    }

    /**
     * Show the form for validating a document.
     */
    public function showValidationForm(Document $document)
    {
        $document->load(['person', 'member', 'documentType', 'documentType']);

        // Generate signed URL for document viewing (valid for 60 minutes)
        $fileUrl = URL::signedRoute(
            'admin.documents.serve',
            ['document' => $document->id, 'type' => 'main'],
            now()->addMinutes(60)
        );

        // Check if it's a PDF for proper rendering
        $isPdf = $document->mime_type === 'application/pdf';

        return view('documents.validate', compact('document', 'fileUrl', 'isPdf'));
    }

    /**
     * Validate the specified document.
     */
    public function validateDocument(Request $request, Document $document)
    {
        $request->validate([
            'status' => 'required|in:under_review,requires_correction,validated,expired,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        try {
            $document->status = \App\Enums\DocumentStatus::from($request->status);
            $document->validation_date = now();
            $document->validated_by = Auth::id();

            if ($document->status === \App\Enums\DocumentStatus::REJECTED && $request->filled('rejection_reason')) {
                $document->rejection_reason = $request->rejection_reason;
            }

            if ($request->filled('notes')) {
                $document->notes = $request->notes;
            }

            $document->save();

            // If document requires correction, use the action
            if ($document->status === \App\Enums\DocumentStatus::REQUIRES_CORRECTION) {
                $correctionAction = app(\App\Documents\RequestDocumentCorrectionAction::class);
                $correctionAction->execute(
                    $document,
                    Auth::user(),
                    $request->input('notes', 'Documento requer correção.'),
                    $request->input('rejection_reason')
                );
            }

            // Check and update registration pendencies
            if ($document->registration) {
                $checkPendencies = app(\App\Documents\CheckDocumentPendenciesAction::class);
                $checkPendencies->execute($document->registration);
            }

            $statusMessage = match ($request->status) {
                'validated' => 'Documento validado com sucesso.',
                'rejected' => 'Documento rejeitado.',
                'requires_correction' => 'Correção solicitada com sucesso.',
                default => 'Documento atualizado com sucesso.',
            };

            return redirect()->route('admin.documents.show', $document)
                ->with('success', $statusMessage);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao validar documento: '.$e->getMessage()]);
        }
    }

    /**
     * Show the checklist for a member.
     */
    public function showChecklist(Member $member)
    {
        $member->load('person.documents.documentType');
        $requiredDocumentTypes = DocumentType::required()->active()->get();

        $checklist = [];
        foreach ($requiredDocumentTypes as $type) {
            $document = $member->person->documents->where('document_type_id', $type->id)->first();
            $checklist[] = [
                'type' => $type,
                'document' => $document,
                'status' => $document ? $document->status : 'missing',
                'is_expired' => $document && $document->isExpired(),
                'needs_translation' => $type->requires_translation && (! $document || ! $document->has_translation),
            ];
        }

        return view('documents.checklist', compact('member', 'checklist'));
    }

    /**
     * Check for documents that need attention.
     */
    public function checkDocumentsStatus()
    {
        // Documents pending for more than 7 days
        $longPendingDocuments = Document::where('status', \App\Enums\DocumentStatus::PENDING)
            ->where('submission_date', '<=', now()->subDays(7))
            ->with(['member', 'documentType'])
            ->get();

        // Documents that will expire in the next 30 days
        $expiringDocuments = Document::where('status', \App\Enums\DocumentStatus::VALIDATED)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays(30))
            ->with(['member', 'documentType'])
            ->get();

        // Documents that have already expired (by status or by date)
        $expiredDocuments = Document::expired()
            ->with(['member', 'documentType'])
            ->get();

        // Documents that need translation
        $needsTranslationDocuments = Document::needsTranslation()
            ->with(['member', 'documentType'])
            ->get();

        return view('documents.status', compact(
            'longPendingDocuments',
            'expiringDocuments',
            'expiredDocuments',
            'needsTranslationDocuments'
        ));
    }

    /**
     * Export documents to Excel.
     */
    public function exportXlsx(Request $request)
    {
        $query = Document::with([
            'person',
            'registration',
            'member',
            'documentType',
            'validatedBy',
        ]);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', \App\Enums\DocumentStatus::from($request->status));
        }

        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('person', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('expiry_date_from')) {
            $query->where('expiry_date', '>=', $request->expiry_date_from);
        }

        if ($request->filled('expiry_date_to')) {
            $query->where('expiry_date', '<=', $request->expiry_date_to);
        }

        if ($request->filled('submission_date_from')) {
            $query->where('submission_date', '>=', $request->submission_date_from);
        }

        if ($request->filled('submission_date_to')) {
            $query->where('submission_date', '<=', $request->submission_date_to);
        }

        if ($request->filled('has_expired')) {
            $query->where('expiry_date', '<', now());
        }

        if ($request->filled('needs_translation')) {
            $query->needsTranslation();
        }

        $documents = $query->latest()->get();

        $export = new DocumentsExport($documents);

        return Excel::download($export, 'documentos_'.now()->format('Y-m-d_H-i-s').'.xlsx');
    }

    /**
     * Export documents to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Document::with([
            'person',
            'registration',
            'member',
            'documentType',
            'validatedBy',
        ]);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', \App\Enums\DocumentStatus::from($request->status));
        }

        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('person', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('expiry_date_from')) {
            $query->where('expiry_date', '>=', $request->expiry_date_from);
        }

        if ($request->filled('expiry_date_to')) {
            $query->where('expiry_date', '<=', $request->expiry_date_to);
        }

        if ($request->filled('submission_date_from')) {
            $query->where('submission_date', '>=', $request->submission_date_from);
        }

        if ($request->filled('submission_date_to')) {
            $query->where('submission_date', '<=', $request->submission_date_to);
        }

        if ($request->filled('has_expired')) {
            $query->where('expiry_date', '<', now());
        }

        if ($request->filled('needs_translation')) {
            $query->needsTranslation();
        }

        $documents = $query->latest()->get();

        $pdf = Pdf::loadView('admin.documents.pdf-export', [
            'documents' => $documents,
            'filters' => $request->all(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('documentos_'.now()->format('Y-m-d_H-i-s').'.pdf');
    }

    /**
     * Search persons for select2 dropdown.
     */
    public function searchPersons(Request $request)
    {
        $query = $request->input('q', '');

        if (empty($query)) {
            return response()->json([]);
        }

        $persons = \App\Models\Person::where(function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%");
        })
            ->limit(20)
            ->get();

        // Return in Select2 format
        $results = $persons->map(function ($person) {
            return [
                'id' => $person->id,
                'full_name' => $person->full_name ?? ($person->first_name.' '.$person->last_name),
                'email' => $person->email ?? '',
            ];
        })->values();

        return response()->json($results->toArray());
    }

    /**
     * View document in browser with PDF viewer.
     */
    public function view(Document $document)
    {
        // Try public disk first, then local
        $disk = 'public';
        if (! Storage::disk('public')->exists($document->file_path)) {
            $disk = 'local';
            if (! Storage::disk('local')->exists($document->file_path)) {
                abort(404, 'Documento não encontrado');
            }
        }

        // Generate signed URL (valid for 60 minutes)
        $url = URL::signedRoute(
            'admin.documents.serve',
            ['document' => $document->id, 'type' => 'main'],
            now()->addMinutes(60)
        );

        // Check if it's a PDF
        $isPdf = $document->mime_type === 'application/pdf';

        return view('documents.view', [
            'document' => $document,
            'fileUrl' => $url,
            'isPdf' => $isPdf,
        ]);
    }

    /**
     * Serve document file (for viewing/downloading).
     */
    public function serve(Document $document, string $type = 'main')
    {
        $filePath = $type === 'translation' ? $document->translation_file_path : $document->file_path;
        $originalFilename = $type === 'translation'
            ? 'traducao_'.$document->original_filename
            : $document->original_filename;

        if (! $filePath) {
            abort(404, 'Ficheiro não encontrado');
        }

        // Try public disk first, then local
        $disk = 'public';
        if (! Storage::disk('public')->exists($filePath)) {
            $disk = 'local';
            if (! Storage::disk('local')->exists($filePath)) {
                abort(404, 'Ficheiro não encontrado');
            }
        }

        $storageDisk = Storage::disk($disk);
        $fullPath = $storageDisk->path($filePath);

        if (! file_exists($fullPath)) {
            abort(404, 'Ficheiro não encontrado');
        }

        // Detect MIME type if needed
        $mimeType = $document->mime_type;
        if (! $mimeType || $type === 'translation') {
            $mimeType = Storage::disk($disk)->mimeType($filePath) ?? 'application/octet-stream';
        }

        // Serve file with appropriate headers (inline for viewing)
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.$originalFilename.'"',
        ]);
    }

    /**
     * Download document.
     */
    public function download(Document $document)
    {
        // Try public disk first, then local
        $disk = 'public';
        if (! Storage::disk('public')->exists($document->file_path)) {
            $disk = 'local';
            if (! Storage::disk('local')->exists($document->file_path)) {
                abort(404, 'Documento não encontrado');
            }
        }

        return Storage::disk($disk)->download(
            $document->file_path,
            $document->original_filename
        );
    }

    /**
     * Download translation file.
     */
    public function downloadTranslation(Document $document)
    {
        if (! $document->translation_file_path) {
            abort(404, 'Ficheiro de tradução não encontrado');
        }

        // Try public disk first, then local
        $disk = 'public';
        if (! Storage::disk('public')->exists($document->translation_file_path)) {
            $disk = 'local';
            if (! Storage::disk('local')->exists($document->translation_file_path)) {
                abort(404, 'Ficheiro de tradução não encontrado');
            }
        }

        return Storage::disk($disk)->download(
            $document->translation_file_path,
            'traducao_'.$document->original_filename
        );
    }
}
