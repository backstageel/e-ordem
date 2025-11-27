<?php

namespace Modules\Registration\Http\Controllers\Admin;

use App\Enums\RegistrationCategory;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Person;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the registrations.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? (int) $perPage : 10;

        $registrations = QueryBuilder::for(Registration::class)
            ->with(['person', 'registrationType'])
            ->allowedFilters([
                AllowedFilter::callback('search', function ($query, $value) {
                    $value = trim((string) $value);
                    $query->where(function ($q) use ($value) {
                        $q->where('registration_number', 'like', "%{$value}%")
                            ->orWhereHas('person', function ($p) use ($value) {
                                $p->where('first_name', 'like', "%{$value}%")
                                    ->orWhere('middle_name', 'like', "%{$value}%")
                                    ->orWhere('last_name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%");
                            });
                    });
                }),
                AllowedFilter::exact('status'),
                AllowedFilter::callback('type', function ($query, $value) {
                    $query->whereHas('registrationType', function ($t) use ($value) {
                        $t->where('category', $value);
                    });
                }),
                AllowedFilter::callback('date_from', function ($query, $value) {
                    if ($value) {
                        $query->whereDate('submission_date', '>=', $value);
                    }
                }),
                AllowedFilter::callback('date_to', function ($query, $value) {
                    if ($value) {
                        $query->whereDate('submission_date', '<=', $value);
                    }
                }),
            ])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $totalCount = Registration::count();
        $approvedCount = Registration::where('status', 'approved')->count();
        $rejectedCount = Registration::where('status', 'rejected')->count();
        $pendingCount = Registration::whereNotIn('status', ['approved', 'rejected'])->count();

        $statusOptions = collect(RegistrationStatus::cases())
            ->map(fn ($c) => ['value' => $c->value, 'label' => $c->label()]);

        $typeOptions = collect(RegistrationCategory::cases())
            ->map(fn ($c) => ['value' => $c->value, 'label' => $c->label()]);

        return view('registration::admin.registrations.index', compact(
            'registrations',
            'totalCount',
            'approvedCount',
            'rejectedCount',
            'pendingCount',
            'statusOptions',
            'typeOptions'
        ));
    }

    /**
     * Append an entry to registration workflow history (JSON array in DB).
     */
    private function addHistory(Registration $registration, string $action, array $meta = []): void
    {
        $history = [];
        if (! empty($registration->workflow_history)) {
            try {
                $history = json_decode($registration->workflow_history, true) ?: [];
            } catch (\Throwable) {
                $history = [];
            }
        }
        $history[] = [
            'at' => now()->toDateTimeString(),
            'by' => auth()->id(),
            'action' => $action,
            'meta' => $meta,
        ];
        $registration->workflow_history = json_encode($history);
        $registration->save();
    }

    /**
     * Export registrations applying current filters.
     */
    public function export(Request $request)
    {
        $rows = QueryBuilder::for(Registration::class)
            ->with(['person', 'registrationType', 'payments', 'person.documents'])
            ->allowedFilters([
                AllowedFilter::callback('search', function ($query, $value) {
                    $value = trim((string) $value);
                    $query->where(function ($q) use ($value) {
                        $q->where('registration_number', 'like', "%{$value}%")
                            ->orWhereHas('person', function ($p) use ($value) {
                                $p->where('first_name', 'like', "%{$value}%")
                                    ->orWhere('middle_name', 'like', "%{$value}%")
                                    ->orWhere('last_name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%");
                            });
                    });
                }),
                AllowedFilter::exact('status'),
                AllowedFilter::callback('type', function ($query, $value) {
                    $query->whereHas('registrationType', function ($t) use ($value) {
                        $t->where('category', $value);
                    });
                }),
                AllowedFilter::callback('date_from', function ($query, $value) {
                    if ($value) {
                        $query->whereDate('submission_date', '>=', $value);
                    }
                }),
                AllowedFilter::callback('date_to', function ($query, $value) {
                    if ($value) {
                        $query->whereDate('submission_date', '<=', $value);
                    }
                }),
            ])
            ->latest()
            ->get();

        $export = new \App\Exports\RegistrationsExport($rows);

        return \Maatwebsite\Excel\Facades\Excel::download($export, 'inscricoes.xlsx');
    }

    /**
     * Export registration as PDF (formatted form).
     */
    public function exportPdf(Registration $registration)
    {
        $registration->load(['person', 'registrationType', 'person.documents.documentType', 'payments']);
        $pdf = Pdf::loadView('registration::admin.registrations.pdf', [
            'registration' => $registration,
        ])->setPaper('a4');

        return $pdf->download('inscricao-'.$registration->registration_number.'.pdf');
    }

    // Pending page removed by request; approvals happen via detalhe da inscrição

    /**
     * Approve a single document.
     */
    public function approveDocument(Registration $registration, Document $document)
    {
        abort_unless($document->registration_id === $registration->id, 404);
        $document->status = \App\Enums\DocumentStatus::VALIDATED;
        $document->validation_date = now();
        $document->validated_by = auth()->id();
        $document->rejection_reason = null;
        if (! $document->person_id && $registration->person_id) {
            $document->person_id = $registration->person_id;
        }
        $document->save();

        // If all documents are validated, mark registration flag
        $allValidated = $registration->documents()->where('status', '!=', \App\Enums\DocumentStatus::VALIDATED)->count() === 0;
        if ($allValidated) {
            $registration->documents_validated = true;
            $registration->save();
        }

        $this->addHistory($registration, 'document_approved', [
            'document_id' => $document->id,
            'document_type' => optional($document->documentType)->name,
        ]);

        // Notify
        try {
            $url = route('admin.registrations.show', $registration);
            $note = new \App\Notifications\SimpleRegistrationNotification(
                'Documento aprovado',
                'O documento "'.(optional($document->documentType)->name ?? 'Documento').'" foi aprovado.',
                $url,
                'Ver inscrição'
            );
            if ($registration->person?->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $registration->person->email)->notify($note);
            }
            foreach (\App\Models\User::role('super-admin')->get() as $admin) {
                $admin->notify($note);
            }
        } catch (\Throwable $e) {
        }

        return back()->with('status', 'Documento aprovado.');
    }

    /**
     * Reject a single document.
     */
    public function rejectDocument(Registration $registration, Document $document, Request $request)
    {
        abort_unless($document->registration_id === $registration->id, 404);
        $reason = (string) $request->string('reason');
        $document->status = \App\Enums\DocumentStatus::REJECTED;
        $document->rejection_reason = $reason ?: null;
        $document->validation_date = null;
        $document->validated_by = null;
        $document->save();

        $registration->documents_validated = false;
        $registration->save();

        $this->addHistory($registration, 'document_rejected', [
            'document_id' => $document->id,
            'document_type' => optional($document->documentType)->name,
            'reason' => $reason,
        ]);

        try {
            $url = route('admin.registrations.show', $registration);
            $note = new \App\Notifications\SimpleRegistrationNotification(
                'Documento rejeitado',
                'O documento "'.(optional($document->documentType)->name ?? 'Documento').'" foi rejeitado.'.($reason ? ' Motivo: '.$reason : ''),
                $url,
                'Ver inscrição'
            );
            if ($registration->person?->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $registration->person->email)->notify($note);
            }
            foreach (\App\Models\User::role('super-admin')->get() as $admin) {
                $admin->notify($note);
            }
        } catch (\Throwable $e) {
        }

        return back()->with('status', 'Documento rejeitado.');
    }

    /**
     * Approve all documents for a registration.
     */
    public function approveAllDocuments(Registration $registration)
    {
        $registration->load('documents');
        foreach ($registration->documents as $doc) {
            $doc->status = \App\Enums\DocumentStatus::VALIDATED;
            $doc->validation_date = now();
            $doc->validated_by = auth()->id();
            $doc->rejection_reason = null;
            if (! $doc->person_id && $registration->person_id) {
                $doc->person_id = $registration->person_id;
            }
            $doc->save();
        }
        $registration->documents_validated = true;
        $registration->save();
        $this->addHistory($registration, 'documents_approved_all');
        try {
            $url = route('admin.registrations.show', $registration);
            $note = new \App\Notifications\SimpleRegistrationNotification('Documentos aprovados', 'Todos os documentos da sua inscrição foram aprovados.', $url, 'Ver inscrição');
            if ($registration->person?->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $registration->person->email)->notify($note);
            }
            foreach (\App\Models\User::role('super-admin')->get() as $admin) {
                $admin->notify($note);
            }
        } catch (\Throwable $e) {
        }

        return back()->with('status', 'Todos os documentos foram aprovados.');
    }

    /**
     * Reject all documents for a registration.
     */
    public function rejectAllDocuments(Registration $registration, Request $request)
    {
        $reason = (string) $request->string('reason');
        $registration->load('documents');
        foreach ($registration->documents as $doc) {
            $doc->status = \App\Enums\DocumentStatus::REJECTED;
            $doc->validation_date = null;
            $doc->validated_by = null;
            $doc->rejection_reason = $reason ?: null;
            if (! $doc->person_id && $registration->person_id) {
                $doc->person_id = $registration->person_id;
            }
            $doc->save();
        }
        $registration->documents_validated = false;
        $registration->save();
        $this->addHistory($registration, 'documents_rejected_all', ['reason' => $reason]);
        try {
            $url = route('admin.registrations.show', $registration);
            $note = new \App\Notifications\SimpleRegistrationNotification('Documentos rejeitados', 'Os documentos da sua inscrição foram rejeitados.'.($reason ? ' Motivo: '.$reason : ''), $url, 'Ver inscrição');
            if ($registration->person?->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $registration->person->email)->notify($note);
            }
            foreach (\App\Models\User::role('super-admin')->get() as $admin) {
                $admin->notify($note);
            }
        } catch (\Throwable $e) {
        }

        return back()->with('status', 'Todos os documentos foram rejeitados.');
    }

    /**
     * Validate payment via modal form.
     */
    public function validatePayment(Registration $registration, Request $request)
    {
        $data = $request->validate([
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'proof' => ['nullable', 'file', 'max:10240'],
        ]);

        $payment = $registration->payments()->firstOrNew([]);
        if (! $payment->exists) {
            $payment->amount = $data['amount'] ?? ($registration->registrationType->fee ?? 0);
            $payment->person_id = $registration->person_id;
            // resolve payment_type_id from registration type's payment_type_code
            if (! empty($registration->registrationType->payment_type_code)) {
                $type = \App\Models\PaymentType::firstOrCreate([
                    'code' => $registration->registrationType->payment_type_code,
                ], [
                    'name' => str_replace('_', ' ', $registration->registrationType->payment_type_code),
                    'description' => null,
                    'default_amount' => $registration->registrationType->fee ?? 0,
                    'is_active' => true,
                ]);
                $payment->payment_type_id = $type->id;
            }
        }
        $payment->payable_type = \Modules\Registration\Models\Registration::class;
        $payment->payable_id = $registration->id;
        $payment->reference_number = $data['reference_number'];
        if (! empty($data['payment_method'])) {
            $method = \App\Models\PaymentMethod::firstOrCreate(['name' => $data['payment_method']]);
            $payment->payment_method_id = $method->id;
        }
        $payment->payment_date = $data['payment_date'];
        $payment->status = \App\Enums\PaymentStatus::COMPLETED;
        $payment->save();

        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('public/registrations/'.$registration->id, 'local');
            // Optionally store as a document record
            $docType = DocumentType::where('code', 'payment_proof')->first();
            if ($docType) {
                Document::updateOrCreate([
                    'registration_id' => $registration->id,
                    'document_type_id' => $docType->id,
                ], [
                    'person_id' => $registration->person_id,
                    'file_path' => str_replace('public/', '', $path),
                    'original_filename' => $request->file('proof')->getClientOriginalName(),
                    'mime_type' => $request->file('proof')->getMimeType(),
                    'file_size' => $request->file('proof')->getSize(),
                    'status' => \App\Enums\DocumentStatus::VALIDATED,
                    'submission_date' => now(),
                    'validation_date' => now(),
                    'validated_by' => auth()->id(),
                ]);
            }
        }

        $registration->is_paid = true;
        $registration->payment_date = $data['payment_date'];
        $registration->payment_amount = $payment->amount;
        $registration->save();

        $this->addHistory($registration, 'payment_validated', [
            'reference' => $payment->reference_number,
            'date' => $registration->payment_date?->toDateString(),
        ]);

        try {
            $url = route('admin.registrations.show', $registration);
            $note = new \App\Notifications\SimpleRegistrationNotification('Pagamento validado', 'O seu pagamento foi validado. Referência: '.$payment->reference_number, $url, 'Ver inscrição');
            if ($registration->person?->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $registration->person->email)->notify($note);
            }
            foreach (\App\Models\User::role('super-admin')->get() as $admin) {
                $admin->notify($note);
            }
        } catch (\Throwable $e) {
        }

        return back()->with('status', 'Pagamento validado com sucesso.');
    }

    // approve() and reject() already implemented below with full workflow; duplicates removed

    /**
     * Validate a registration (pre-approval). Requires payment validated.
     */
    public function validateRegistration(Request $request, Registration $registration)
    {
        if ($registration->status !== \App\Enums\RegistrationStatus::SUBMITTED && $registration->status !== \App\Enums\RegistrationStatus::UNDER_REVIEW && $registration->status !== \App\Enums\RegistrationStatus::DOCUMENTS_PENDING && $registration->status !== \App\Enums\RegistrationStatus::PAYMENT_PENDING) {
            return back()->withErrors(['error' => 'Este registo não pode ser validado neste estado.']);
        }
        if (! $registration->is_paid) {
            return back()->withErrors(['error' => 'Pagamento ainda não validado. Valide o pagamento antes de validar a inscrição.']);
        }

        $registration->update(['status' => \App\Enums\RegistrationStatus::VALIDATED->value]);
        $this->addHistory($registration->fresh(), 'registration_validated');

        try {
            $url = route('admin.registrations.show', $registration);
            $note = new \App\Notifications\SimpleRegistrationNotification('Inscrição validada', 'A sua inscrição foi validada. Pode prosseguir para exame.', $url, 'Ver inscrição');
            if ($registration->person?->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $registration->person->email)->notify($note);
            }
            foreach (\App\Models\User::role('super-admin')->get() as $admin) {
                $admin->notify($note);
            }
        } catch (\Throwable $e) {
        }

        return back()->with('success', 'Inscrição validada.');
    }

    /**
     * Remove the specified registration from storage.
     */
    public function destroy(Registration $registration)
    {
        try {
            $registration->delete();

            return redirect()->route('admin.registrations.index')
                ->with('success', 'Inscrição apagada com sucesso.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao apagar inscrição: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified registration.
     */
    public function show(Registration $registration)
    {
        $registration->load([
            'person.currentAcademicQualification',
            'person.currentWorkExperience',
            'person.documents.documentType',
            'registrationType',
            'documents.documentType',
            'approvedBy',
        ]);

        return view('registration::admin.registrations.show', compact('registration'));
    }

    /**
     * Approve the specified registration.
     */
    public function approve(Request $request, Registration $registration)
    {
        if ($registration->status !== \App\Enums\RegistrationStatus::SUBMITTED && $registration->status !== \App\Enums\RegistrationStatus::VALIDATED) {
            return back()->withErrors(['error' => 'Only submitted registrations can be approved.']);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Calculate expiry date based on registration type validity period
            $validityPeriodDays = $registration->registrationType->validity_period_days;
            $expiryDate = $validityPeriodDays ? now()->addDays($validityPeriodDays) : null;

            // Check if person exists
            if (! $registration->person_id) {
                throw new \Exception('Registration must be associated with a person to be approved.');
            }

            // Ensure person has a user; create if needed
            $person = $registration->person;
            if ($person && ! $person->user_id) {
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $person->email],
                    ['name' => trim($person->first_name.' '.$person->last_name), 'password' => bcrypt(str()->random(12))]
                );
                $person->user_id = $user->id;
                $person->save();
            } else {
                $user = $person?->user;
            }

            // Create member if not exists
            $existingMember = Member::where('person_id', $registration->person_id)->first();
            if (! $existingMember) {
                $member = Member::create([
                    'person_id' => $registration->person_id,
                    'registration_number' => $registration->registration_number,
                    'professional_category' => $registration->professional_category,
                    'specialty' => $registration->specialty,
                    'sub_specialty' => $registration->sub_specialty,
                    'workplace' => $registration->workplace,
                    'workplace_address' => $registration->workplace_address,
                    'workplace_phone' => $registration->workplace_phone,
                    'workplace_email' => $registration->workplace_email,
                    'academic_degree' => $registration->academic_degree,
                    'university' => $registration->university,
                    'graduation_date' => $registration->graduation_date,
                    'registration_date' => now(),
                    'status' => 'active',
                ]);
            } else {
                $member = $existingMember;
            }

            // Ensure payment is validated; if not, create/mark as paid now
            if (! $registration->is_paid) {
                $payment = $registration->payments()->firstOrNew([]);
                if (! $payment->exists) {
                    $payment->amount = $registration->registrationType->fee ?? 0;
                    $payment->person_id = $registration->person_id;
                    if (! empty($registration->registrationType->payment_type_code)) {
                        $type = \App\Models\PaymentType::firstOrCreate([
                            'code' => $registration->registrationType->payment_type_code,
                        ], [
                            'name' => str_replace('_', ' ', $registration->registrationType->payment_type_code),
                            'description' => null,
                            'default_amount' => $registration->registrationType->fee ?? 0,
                            'is_active' => true,
                        ]);
                        $payment->payment_type_id = $type->id;
                    }
                }
                $payment->payable_type = \Modules\Registration\Models\Registration::class;
                $payment->payable_id = $registration->id;
                $payment->reference_number = $payment->reference_number ?: $registration->registration_number;
                $payment->status = \App\Enums\PaymentStatus::COMPLETED;
                $payment->payment_date = now();
                $payment->save();

                $registration->is_paid = true;
                $registration->payment_date = $payment->payment_date;
                $registration->payment_amount = $payment->amount;
                $registration->save();
            }

            // Update the registration status
            $registration->update([
                'status' => 'approved',
                'approval_date' => now(),
                // expiry_date removed from registrations table
                'notes' => $validated['notes'] ?? $registration->notes,
                'approved_by' => auth()->id(),
                'documents_validated' => true,
                'person_id' => $registration->person_id,
            ]);

            // Update all pending documents to validated and ensure person_id is set
            $registration->documents()->where('status', \App\Enums\DocumentStatus::PENDING)->update([
                'status' => \App\Enums\DocumentStatus::VALIDATED,
                'validation_date' => now(),
                'validated_by' => auth()->id(),
                'person_id' => $registration->person_id,
            ]);

            // Link documents to member and ensure person_id is set
            if ($member) {
                $registration->documents()->update([
                    'member_id' => $member->id,
                    'person_id' => $registration->person_id,
                ]);
            }

            // Link any payments to the member
            if ($registration->is_paid && $member) {
                // Find payments related to this registration
                $payments = Payment::where('payable_type', \Modules\Registration\Models\Registration::class)
                    ->where('payable_id', $registration->id)
                    ->get();

                foreach ($payments as $payment) {
                    $payment->member_id = $member->id;
                    $payment->save();
                }
            }

            // Notify candidate and super-admins
            try {
                $url = route('admin.registrations.show', $registration);
                $note = new \App\Notifications\SimpleRegistrationNotification(
                    'Inscrição aprovada',
                    'A sua inscrição '.$registration->registration_number.' foi aprovada.',
                    $url,
                    'Ver detalhes'
                );
                if ($person?->email) {
                    \Illuminate\Support\Facades\Notification::route('mail', $person->email)->notify($note);
                }
                foreach (\App\Models\User::role('super-admin')->get() as $admin) {
                    $admin->notify($note);
                }
            } catch (\Throwable $e) {
                // swallow notification failures
            }

            DB::commit();

            return redirect()->route('admin.registrations.show', $registration)
                ->with('success', 'Registration approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to approve registration: '.$e->getMessage()]);
        }
    }

    /**
     * Reject the specified registration.
     */
    public function reject(Request $request, Registration $registration)
    {
        if ($registration->status !== \App\Enums\RegistrationStatus::SUBMITTED) {
            return back()->withErrors(['error' => 'Only submitted registrations can be rejected.']);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $this->addHistory($registration->fresh(), 'registration_rejected', ['reason' => $validated['rejection_reason']]);

        return redirect()->route('admin.registrations.show', $registration)
            ->with('success', 'Registration rejected successfully.');
    }

    /**
     * Generate a unique registration number.
     */
    public function generateRegistrationNumber(RegistrationType $registrationType): string
    {
        $prefix = substr(strtoupper(str_replace(' ', '', $registrationType->name)), 0, 3);
        $year = date('Y');
        $random = strtoupper(Str::random(4));

        return "{$prefix}-{$year}-{$random}";
    }
}
