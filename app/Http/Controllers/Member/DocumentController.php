<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the member's documents.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $member = $user->person->member;
        $person = $user->person;

        $query = $person->documents()
            ->with(['documentType']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('documentType', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('original_filename', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->paginate(10);
        $documents->appends($request->all());

        return view('member.documents.index', compact('documents', 'member'));
    }

    /**
     * Display a listing of the member's pending documents.
     */
    public function pending()
    {
        $user = Auth::user();
        $person = $user->person;

        if (! $person) {
            abort(404, 'Person not found');
        }

        $member = $person->member;

        // Get required document types
        $requiredDocumentTypes = DocumentType::required()->active()->get();

        // Get person's documents
        $personDocuments = $person->documents()->with('documentType')->get();

        // Prepare the pending documents list
        $pendingDocuments = [];
        foreach ($requiredDocumentTypes as $type) {
            $document = $personDocuments->where('document_type_id', $type->id)->first();
            $pendingDocuments[] = [
                'type' => $type,
                'document' => $document,
                'status' => $document ? $document->status : 'missing',
                'is_expired' => $document && $document->isExpired(),
                'needs_translation' => $type->requires_translation && (! $document || ! $document->has_translation),
            ];
        }

        return view('member.documents.pending', compact('pendingDocuments', 'member'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        $user = Auth::user();
        $member = $user->person->member;
        $documentTypes = DocumentType::active()->get();

        return view('member.documents.create', compact('documentTypes', 'member'));
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $member = $user->person->member;

        $request->validate([
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

        DB::beginTransaction();

        try {
            $document = new Document;
            $document->person_id = $member->person_id;
            $document->member_id = $member->id;
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

            return redirect()->route('member.documents.index')
                ->with('success', 'Documento enviado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Falha ao enviar documento: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        $user = Auth::user();
        $member = $user->person->member;
        $person = $user->person;

        // Ensure the document belongs to the authenticated person
        if ($document->person_id !== $person->id) {
            abort(403, 'Unauthorized action.');
        }

        $document->load(['documentType']);

        return view('member.documents.show', compact('document'));
    }

    /**
     * Download the document file.
     */
    public function download(Document $document)
    {
        $user = Auth::user();
        $member = $user->person->member;
        $person = $user->person;

        // Ensure the document belongs to the authenticated person
        if ($document->person_id !== $person->id) {
            abort(403, 'Unauthorized action.');
        }

        if (! Storage::disk('public')->exists($document->file_path)) {
            return back()->withErrors(['error' => 'Arquivo não encontrado.']);
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_filename
        );
    }

    /**
     * Download the translation file.
     */
    public function downloadTranslation(Document $document)
    {
        $user = Auth::user();
        $member = $user->person->member;
        $person = $user->person;

        // Ensure the document belongs to the authenticated person
        if ($document->person_id !== $person->id) {
            abort(403, 'Unauthorized action.');
        }

        if (! $document->has_translation || ! Storage::disk('public')->exists($document->translation_file_path)) {
            return back()->withErrors(['error' => 'Arquivo de tradução não encontrado.']);
        }

        return Storage::disk('public')->download(
            $document->translation_file_path,
            'translation_'.$document->original_filename
        );
    }
}
