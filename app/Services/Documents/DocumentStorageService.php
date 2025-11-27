<?php

namespace App\Services\Documents;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;

class DocumentStorageService
{
    /**
     * Maximum width for image compression (in pixels).
     */
    protected int $maxImageWidth = 1920;

    /**
     * Maximum height for image compression (in pixels).
     */
    protected int $maxImageHeight = 1920;

    /**
     * Image compression quality (0-100).
     */
    protected int $imageQuality = 85;

    /**
     * PDF compression quality (only for images, PDFs are not compressed).
     */
    protected int $pdfQuality = 75;

    /**
     * Get the ImageManager instance.
     */
    protected function getImageManager(): ?ImageManager
    {
        // Try Imagick first, fallback to GD
        if (extension_loaded('imagick')) {
            try {
                return new ImageManager(new ImagickDriver);
            } catch (\Exception $e) {
                // Imagick failed, try GD
            }
        }

        if (extension_loaded('gd')) {
            try {
                return new ImageManager(new Driver);
            } catch (\Exception $e) {
                // GD failed
            }
        }

        return null;
    }

    /**
     * Check if file is an image that can be compressed.
     */
    protected function isCompressibleImage(UploadedFile $file): bool
    {
        $mimeType = $file->getMimeType();

        return in_array($mimeType, [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
        ]);
    }

    /**
     * Compress image file.
     */
    protected function compressImage(UploadedFile $file): string
    {
        $manager = $this->getImageManager();
        if (! $manager) {
            // No image library available, return original
            return file_get_contents($file->getRealPath());
        }

        try {
            $image = $manager->read($file->getRealPath());

            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // Calculate new dimensions maintaining aspect ratio
            if ($originalWidth > $this->maxImageWidth || $originalHeight > $this->maxImageHeight) {
                $ratio = min(
                    $this->maxImageWidth / $originalWidth,
                    $this->maxImageHeight / $originalHeight
                );
                $newWidth = (int) ($originalWidth * $ratio);
                $newHeight = (int) ($originalHeight * $ratio);
                $image->scale($newWidth, $newHeight);
            }

            // Get file extension to determine format
            $extension = strtolower($file->getClientOriginalExtension());

            // Determine output format
            $outputFormat = match ($extension) {
                'gif' => 'png', // Convert GIF to PNG for better compression
                'jpg', 'jpeg' => 'jpeg',
                'png' => 'png',
                'webp' => 'webp',
                default => 'jpeg',
            };

            // Encode image with compression based on format
            if ($outputFormat === 'jpeg') {
                $encoded = $image->encode(new \Intervention\Image\Encoders\JpegEncoder(quality: $this->imageQuality));
            } elseif ($outputFormat === 'png') {
                // PNG encoding (no quality parameter, just format)
                $encoded = $image->encode(new \Intervention\Image\Encoders\PngEncoder());
            } elseif ($outputFormat === 'webp') {
                $encoded = $image->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: $this->imageQuality));
            } else {
                // Default to JPEG
                $encoded = $image->encode(new \Intervention\Image\Encoders\JpegEncoder(quality: $this->imageQuality));
            }

            return $encoded->toString();
        } catch (\Exception $e) {
            // If compression fails, return original file content
            return file_get_contents($file->getRealPath());
        }
    }

    /**
     * Store document with automatic compression and calculate SHA-256 hash.
     */
    public function store(UploadedFile $file, string $disk = 'public', ?string $path = null): array
    {
        $originalSize = $file->getSize();
        $compressed = false;

        // Compress images automatically
        if ($this->isCompressibleImage($file)) {
            try {
                $compressedContent = $this->compressImage($file);
                $compressedSize = strlen($compressedContent);

                // Only use compressed version if it's smaller
                if ($compressedSize < $originalSize) {
                    $path = $path ?? 'documents';
                    $filename = time().'_'.uniqid().'.'.strtolower($file->getClientOriginalExtension());
                    $fullPath = $path.'/'.$filename;

                    Storage::disk($disk)->put($fullPath, $compressedContent);

                    $hash = hash('sha256', $compressedContent);
                    $compressed = true;

                    return [
                        'path' => $fullPath,
                        'hash' => $hash,
                        'size' => $compressedSize,
                        'mime_type' => $file->getMimeType(),
                        'original_filename' => $file->getClientOriginalName(),
                        'original_size' => $originalSize,
                        'compressed' => true,
                    ];
                }
            } catch (\Exception $e) {
                // If compression fails, fall through to normal storage
            }
        }

        // Store file normally (not an image or compression failed/not beneficial)
        $hash = $this->calculateHash($file);
        $storedPath = $file->store($path ?? 'documents', $disk);

        return [
            'path' => $storedPath,
            'hash' => $hash,
            'size' => $originalSize,
            'mime_type' => $file->getMimeType(),
            'original_filename' => $file->getClientOriginalName(),
            'compressed' => false,
        ];
    }

    /**
     * Store file content and calculate SHA-256 hash.
     */
    public function storeContent(string $content, string $disk, string $path): array
    {
        $hash = hash('sha256', $content);
        Storage::disk($disk)->put($path, $content);

        return [
            'path' => $path,
            'hash' => $hash,
            'size' => strlen($content),
        ];
    }

    /**
     * Calculate SHA-256 hash of uploaded file.
     */
    public function calculateHash(UploadedFile $file): string
    {
        return hash_file('sha256', $file->getRealPath());
    }

    /**
     * Calculate SHA-256 hash of stored file.
     */
    public function calculateHashFromPath(string $path, string $disk = 'public'): ?string
    {
        if (! Storage::disk($disk)->exists($path)) {
            return null;
        }

        $content = Storage::disk($disk)->get($path);

        return hash('sha256', $content);
    }

    /**
     * Verify file integrity by comparing stored hash with calculated hash.
     */
    public function verifyIntegrity(string $path, string $storedHash, string $disk = 'public'): bool
    {
        $calculatedHash = $this->calculateHashFromPath($path, $disk);

        return $calculatedHash && $calculatedHash === $storedHash;
    }

    /**
     * Get file URL with optional signed temporary URL.
     */
    public function getUrl(string $path, string $disk = 'public', bool $temporary = false, int $expirationMinutes = 60): string
    {
        if ($temporary && $disk === 'public') {
            // For private files, generate signed URL
            return Storage::disk($disk)->temporaryUrl($path, now()->addMinutes($expirationMinutes));
        }

        return Storage::disk($disk)->url($path);
    }

    /**
     * Delete document file.
     */
    public function delete(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }
}
