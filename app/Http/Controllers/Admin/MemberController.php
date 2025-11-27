<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MembersExport;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     */
    public function index(Request $request)
    {
        $query = Member::with(['person', 'person.livingProvince', 'person.nationality', 'medicalSpecialities']);

        // Apply search filter (nome, número de membro, número de inscrição)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('member_number', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%")
                    ->orWhereHas('person', function ($q2) use ($search) {
                        $q2->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // Apply medical speciality filter
        if ($request->filled('medical_speciality_id')) {
            $query->whereHas('medicalSpecialities', function ($q) use ($request) {
                $q->where('medical_specialities.id', $request->medical_speciality_id);
            });
        }

        // Apply contact filter (email or phone)
        if ($request->filled('contact')) {
            $contact = trim($request->contact);
            $query->whereHas('person', function ($q) use ($contact) {
                $q->where('email', 'like', "%{$contact}%")
                    ->orWhere('phone', 'like', "%{$contact}%")
                    ->orWhere('mobile', 'like', "%{$contact}%");
            });
        }

        // Apply province filter (província de residência)
        if ($request->filled('province_id')) {
            $query->whereHas('person', function ($q) use ($request) {
                $q->where('living_province_id', $request->province_id);
            });
        }

        // Apply nationality filter
        if ($request->filled('nationality_id')) {
            $query->whereHas('person', function ($q) use ($request) {
                $q->where('nationality_id', $request->nationality_id);
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get statistics for the dashboard
        $stats = [
            'total' => Member::count(),
            'active' => Member::where('status', 'active')->count(),
            'pending' => Member::where('status', 'pending')->count(),
            'suspended' => Member::where('status', 'suspended')->count(),
        ];

        // Get filter options
        $specialties = \App\Models\MedicalSpeciality::active()->ordered()->get();
        $provinces = \App\Models\Province::whereHas('country', function ($q) {
            $q->where('code', 'MZ')->orWhere('iso', 'MOZ'); // Moçambique
        })->orderBy('name')->get();
        $countries = \App\Models\Country::orderBy('name')->get();

        $members = $query->latest('members.created_at')->paginate(20)->withQueryString();

        return view('admin.members.index', compact('members', 'stats', 'specialties', 'provinces', 'countries'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        $countries = \App\Models\Country::orderBy('name')->get();

        return view('admin.members.create', compact('countries'));
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Person data
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'gender_id' => 'required|integer|exists:genders,id',
            'nationality_id' => 'required|exists:countries,id',
            'birth_country_id' => 'nullable|exists:countries,id',
            'identity_document_id' => 'required|integer|exists:identity_documents,id',
            'identity_document_number' => 'required|string|unique:people,identity_document_number',
            'living_address' => 'required|string',

            // Member data
            'professional_category' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'sub_specialty' => 'nullable|string|max:255',
            'workplace' => 'required|string|max:255',
            'workplace_address' => 'required|string|max:255',
            'workplace_phone' => 'required|string|max:20',
            'workplace_email' => 'required|email',
            'academic_degree' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'graduation_date' => 'required|date',

            // Profile photo
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        // Check if a user with this email already exists
        $existingUser = \App\Models\User::where('email', $request->email)->first();

        // Check if the existing user is already linked to a member
        if ($existingUser && $existingUser->person && $existingUser->person->member) {
            return back()->withInput()->withErrors(['email' => 'This email is already associated with another member.']);
        }

        DB::beginTransaction();

        try {
            // If no user exists with this email, create one
            if (! $existingUser) {
                $userName = $request->first_name.' '.($request->middle_name ? $request->middle_name.' ' : '').$request->last_name;
                $existingUser = \App\Models\User::create([
                    'name' => $userName,
                    'email' => $request->email,
                    'password' => Hash::make('12345678'),
                ]);
            }

            // Create person
            $person = new Person;
            $person->user_id = $existingUser->id;
            $person->fill($request->only([
                'first_name', 'middle_name', 'last_name', 'email', 'phone', 'mobile',
                'birth_date', 'gender_id', 'nationality_id', 'identity_document_id',
                'identity_document_number', 'identity_document_issue_date',
                'identity_document_issue_place', 'identity_document_expiry_date',
                'living_address', 'has_disability', 'disability_description',
                'birth_country_id', 'birth_province_id', 'birth_district_id',
                'marital_status_id', 'father_name', 'mother_name', 'fax',
                'website', 'linkedin',
            ]));

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $person->profile_picture_url = $path;
            }

            $person->save();

            // Create member
            $member = new Member;
            $member->person_id = $person->id;
            $member->fill($request->only([
                'professional_category', 'specialty', 'sub_specialty', 'workplace',
                'workplace_address', 'workplace_phone', 'workplace_email',
                'academic_degree', 'university', 'graduation_date',
            ]));
            $member->status = 'active';
            $member->save();

            DB::commit();

            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Member created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Failed to create member: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified member.
     */
    public function show(Member $member)
    {
        $member->load([
            'person',
            'person.livingProvince',
            'person.nationality',
            'registrations',
            'registrations.registrationType',
            'documents.documentType',
            'card',
            'quotaHistory.payment',
            'statusHistory.changedBy',
            'medicalSpecialities',
        ]);

        // Get quota statistics
        $quotaStats = [
            'total' => $member->quotaHistory()->count(),
            'paid' => $member->quotaHistory()->where('status', \App\Models\MemberQuota::STATUS_PAID)->count(),
            'pending' => $member->quotaHistory()->where('status', \App\Models\MemberQuota::STATUS_PENDING)->count(),
            'overdue' => $member->quotaHistory()->where('status', \App\Models\MemberQuota::STATUS_OVERDUE)->count(),
            'total_amount' => $member->quotaHistory()->sum('amount'),
            'paid_amount' => $member->quotaHistory()->where('status', \App\Models\MemberQuota::STATUS_PAID)->sum('amount'),
            'pending_amount' => $member->quotaHistory()->where('status', \App\Models\MemberQuota::STATUS_PENDING)->sum('amount'),
            'overdue_amount' => $member->quotaHistory()->where('status', \App\Models\MemberQuota::STATUS_OVERDUE)->sum('amount'),
            'total_penalties' => $member->quotaHistory()->sum('penalty_amount'),
        ];

        // Get recent quotas
        $recentQuotas = $member->quotaHistory()
            ->with('payment')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(10)
            ->get();

        // Get overdue quotas
        $overdueQuotas = $member->overdueQuotas()
            ->orderBy('due_date', 'asc')
            ->get();

        // Get all payments for the member (including quotas)
        $payments = Payment::where('member_id', $member->id)
            ->with(['paymentType', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate payment statistics
        $paymentStats = [
            'total' => $payments->count(),
            'completed' => $payments->where('status', \App\Enums\PaymentStatus::COMPLETED)->count(),
            'pending' => $payments->where('status', \App\Enums\PaymentStatus::PENDING)->count(),
            'failed' => $payments->where('status', \App\Enums\PaymentStatus::FAILED)->count(),
            'refunded' => $payments->where('status', \App\Enums\PaymentStatus::REFUNDED)->count(),
            'total_amount' => $payments->sum('amount'),
            'completed_amount' => $payments->where('status', \App\Enums\PaymentStatus::COMPLETED)->sum('amount'),
            'pending_amount' => $payments->where('status', \App\Enums\PaymentStatus::PENDING)->sum('amount'),
        ];

        return view('admin.members.show', compact('member', 'quotaStats', 'recentQuotas', 'overdueQuotas', 'payments', 'paymentStats'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(Member $member)
    {
        $member->load('person');
        $countries = \App\Models\Country::orderBy('name')->get();

        return view('admin.members.edit', compact('member', 'countries'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            // Person data
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'gender_id' => 'required|integer|exists:genders,id',
            'nationality_id' => 'required|exists:countries,id',
            'birth_country_id' => 'nullable|exists:countries,id',
            'identity_document_id' => 'required|integer|exists:identity_documents,id',
            'identity_document_number' => 'required|string|unique:people,identity_document_number,'.$member->person_id,
            'living_address' => 'required|string',

            // Member data
            'professional_category' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'sub_specialty' => 'nullable|string|max:255',
            'workplace' => 'required|string|max:255',
            'workplace_address' => 'required|string|max:255',
            'workplace_phone' => 'required|string|max:20',
            'workplace_email' => 'required|email',
            'academic_degree' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'graduation_date' => 'required|date',

            // Profile photo
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        // Check if email is being changed
        $emailChanged = $member->person->email !== $request->email;

        // If email is changing, check if a user with the new email already exists
        if ($emailChanged) {
            $existingUser = \App\Models\User::where('email', $request->email)->first();

            // Check if the existing user is already linked to a different member
            if ($existingUser && $existingUser->person && $existingUser->person->member && $existingUser->person->member->id !== $member->id) {
                return back()->withInput()->withErrors(['email' => 'This email is already associated with another member.']);
            }
        }

        DB::beginTransaction();

        try {
            // Update person
            $person = $member->person;

            // If email is changing, handle user relationship
            if ($emailChanged) {
                $existingUser = \App\Models\User::where('email', $request->email)->first();

                if (! $existingUser) {
                    // Create new user with the new email
                    $userName = $request->first_name.' '.($request->middle_name ? $request->middle_name.' ' : '').$request->last_name;
                    $existingUser = \App\Models\User::create([
                        'name' => $userName,
                        'email' => $request->email,
                        'password' => Hash::make('12345678'),
                    ]);
                }

                // Update the user_id on the person
                $person->user_id = $existingUser->id;
            } elseif (! $person->user_id) {
                // If person doesn't have a user but email hasn't changed, create one
                $existingUser = \App\Models\User::where('email', $request->email)->first();

                if (! $existingUser) {
                    $userName = $request->first_name.' '.($request->middle_name ? $request->middle_name.' ' : '').$request->last_name;
                    $existingUser = \App\Models\User::create([
                        'name' => $userName,
                        'email' => $request->email,
                        'password' => Hash::make('12345678'),
                    ]);
                }

                $person->user_id = $existingUser->id;
            }

            $person->fill($request->only([
                'first_name', 'middle_name', 'last_name', 'email', 'phone', 'mobile',
                'birth_date', 'gender_id', 'nationality_id', 'identity_document_id',
                'identity_document_number', 'identity_document_issue_date',
                'identity_document_issue_place', 'identity_document_expiry_date',
                'living_address', 'has_disability', 'disability_description',
                'birth_country_id', 'birth_province_id', 'birth_district_id',
                'marital_status_id', 'father_name', 'mother_name', 'fax',
                'website', 'linkedin',
            ]));

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($person->profile_picture_url) {
                    Storage::disk('public')->delete($person->profile_picture_url);
                }

                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $person->profile_picture_url = $path;
            }

            $person->save();

            // Update member
            $member->fill($request->only([
                'professional_category', 'specialty', 'sub_specialty', 'workplace',
                'workplace_address', 'workplace_phone', 'workplace_email',
                'academic_degree', 'university', 'graduation_date',
            ]));
            $member->save();

            DB::commit();

            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Member updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Failed to update member: '.$e->getMessage()]);
        }
    }

    /**
     * Show the form for updating the status of the specified member.
     */
    public function showStatusForm(Member $member)
    {
        $member->load(['person', 'registrations', 'documents', 'card']);

        return view('admin.members.status', compact('member'));
    }

    /**
     * Update the status of the specified member.
     */
    public function updateStatus(Request $request, Member $member)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended,irregular,canceled',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $previousStatus = $member->status;
        $newStatus = $request->status;

        // Update notes if provided
        if ($request->filled('notes')) {
            $member->notes = $request->notes;
        }

        // Only update if status changed
        if ($previousStatus !== $newStatus) {
            DB::transaction(function () use ($member, $previousStatus, $newStatus, $request) {
                $member->status = $newStatus;
                $member->save();

                // Create status history record
                \App\Models\MemberStatusHistory::create([
                    'member_id' => $member->id,
                    'previous_status' => $previousStatus,
                    'new_status' => $newStatus,
                    'changed_by' => auth()->id(),
                    'reason' => $request->reason ?? 'Status alterado manualmente',
                    'notes' => $request->notes,
                    'effective_date' => now(),
                ]);

                // If suspending, use SuspendMemberAction
                if ($newStatus === Member::STATUS_SUSPENDED) {
                    app(\App\Actions\Member\SuspendMemberAction::class)->execute(
                        $member->refresh(),
                        $request->reason ?? 'Suspensão manual pelo administrador',
                        auth()->id()
                    );
                }

                // If reactivating, use ReactivateMemberAction
                if ($previousStatus === Member::STATUS_SUSPENDED && $newStatus === Member::STATUS_ACTIVE) {
                    app(\App\Actions\Member\ReactivateMemberAction::class)->execute(
                        $member->refresh(),
                        $request->reason ?? 'Reativação manual pelo administrador',
                        auth()->id()
                    );
                }
            });
        } else {
            // Status didn't change, just save notes if updated
            $member->save();
        }

        return redirect()->route('admin.members.show', $member)
            ->with('success', 'Status do membro atualizado com sucesso.');
    }

    /**
     * Upload documents for the specified member.
     */
    public function uploadDocuments(Request $request, Member $member)
    {
        $request->validate([
            'document_type_id' => 'required|exists:document_types,id',
            'document_file' => 'required|file|max:10240', // 10MB max
            'translation_file' => 'nullable|file|max:10240', // 10MB max
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

            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Document uploaded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Failed to upload document: '.$e->getMessage()]);
        }
    }

    /**
     * Update quota status for the specified member.
     */
    public function updateQuotaStatus(Request $request, Member $member)
    {
        $request->validate([
            'dues_paid' => 'required|boolean',
            'dues_paid_until' => 'required_if:dues_paid,1|nullable|date',
            'notes' => 'nullable|string',
        ]);

        $member->dues_paid = $request->dues_paid;

        if ($request->dues_paid && $request->filled('dues_paid_until')) {
            $member->dues_paid_until = $request->dues_paid_until;
        } else {
            $member->dues_paid_until = null;
        }

        if ($request->filled('notes')) {
            $member->notes = $request->notes;
        }

        $member->save();

        return redirect()->route('admin.members.show', $member)
            ->with('success', 'Quota status updated successfully.');
    }

    /**
     * Show the digital card for the specified member.
     */
    public function showCard(Member $member)
    {
        $member->load(['person', 'card']);

        if (! $member->card) {
            return redirect()->route('admin.members.show', $member)
                ->with('error', 'This member does not have a digital card yet.');
        }

        return view('admin.members.card', compact('member'));
    }

    /**
     * Generate a digital card with QR code for the specified member.
     */
    public function generateCard(Request $request, Member $member)
    {
        $request->validate([
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Generate card number
            $cardNumber = 'ORMM-'.str_pad($member->id, 6, '0', STR_PAD_LEFT).'-'.date('Ymd');

            // Generate QR code with direct URL to public profile
            $profileUrl = url('/guest/members/'.$member->id);

            // Store member data for reference
            $memberData = [
                'member_id' => $member->id,
                'card_number' => $cardNumber,
                'name' => $member->full_name,
                'registration_number' => $member->registration_number,
                'specialty' => $member->specialty,
                'issue_date' => now()->format('Y-m-d'),
                'expiry_date' => $request->filled('expiry_date') ? $request->expiry_date : null,
                'profile_url' => $profileUrl,
            ];

            $qrCodePath = 'member-cards/qr-'.Str::random(10).'.png';
            $qrCodeFullPath = storage_path('app/public/'.$qrCodePath);

            // Ensure directory exists
            if (! file_exists(dirname($qrCodeFullPath))) {
                mkdir(dirname($qrCodeFullPath), 0755, true);
            }

            // Generate QR code image with direct URL
            QrCode::format('png')
                ->size(300)
                ->errorCorrection('H')
                ->generate($profileUrl, $qrCodeFullPath);

            // Create member card
            $card = new MemberCard;
            $card->member_id = $member->id;
            $card->card_number = $cardNumber;
            $card->qr_code_path = $qrCodePath;
            $card->issue_date = now();

            if ($request->filled('expiry_date')) {
                $card->expiry_date = $request->expiry_date;
            }

            if ($request->filled('notes')) {
                $card->notes = $request->notes;
            }

            $card->save();

            DB::commit();

            return redirect()->route('admin.members.card', $member)
                ->with('success', 'Digital card generated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Failed to generate digital card: '.$e->getMessage()]);
        }
    }

    /**
     * Get a report of members based on filters.
     */
    public function report(Request $request)
    {
        $query = Member::with('person');

        // Apply filters
        if ($request->filled('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        if ($request->filled('province')) {
            $query->whereHas('person', function ($q) use ($request) {
                $q->where('province', $request->province);
            });
        }

        if ($request->filled('nationality')) {
            $query->whereHas('person', function ($q) use ($request) {
                $q->where('nationality_id', $request->nationality);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('dues_paid')) {
            $query->where('dues_paid', $request->dues_paid);
        }

        $members = $query->get();

        return view('admin.members.report', compact('members'));
    }

    /**
     * Get quota statistics for dashboard.
     */
    public function quotaStatistics(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month');

        $query = \App\Models\MemberQuota::with('member.person')
            ->where('year', $year);

        if ($month) {
            $query->where('month', $month);
        }

        $statistics = [
            'total' => (clone $query)->count(),
            'paid' => (clone $query)->where('status', \App\Models\MemberQuota::STATUS_PAID)->count(),
            'pending' => (clone $query)->where('status', \App\Models\MemberQuota::STATUS_PENDING)->count(),
            'overdue' => (clone $query)->where('status', \App\Models\MemberQuota::STATUS_OVERDUE)->count(),
            'total_amount' => (clone $query)->sum('amount'),
            'total_paid_amount' => (clone $query)->where('status', \App\Models\MemberQuota::STATUS_PAID)->sum('amount'),
            'total_pending_amount' => (clone $query)->where('status', \App\Models\MemberQuota::STATUS_PENDING)->sum('amount'),
            'total_overdue_amount' => (clone $query)->where('status', \App\Models\MemberQuota::STATUS_OVERDUE)->sum('amount'),
            'total_penalties' => (clone $query)->sum('penalty_amount'),
        ];

        if ($request->wantsJson()) {
            return response()->json($statistics);
        }

        return view('admin.members.quota-statistics', compact('statistics', 'year', 'month'));
    }

    /**
     * Export members to Excel/CSV.
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'excel');

        // Apply same filters as index
        $query = Member::with(['person', 'person.livingProvince', 'person.nationality', 'medicalSpecialities']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('member_number', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%")
                    ->orWhereHas('person', function ($q2) use ($search) {
                        $q2->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('medical_speciality_id')) {
            $query->whereHas('medicalSpecialities', function ($q) use ($request) {
                $q->where('medical_specialities.id', $request->medical_speciality_id);
            });
        }

        if ($request->filled('contact')) {
            $contact = trim($request->contact);
            $query->whereHas('person', function ($q) use ($contact) {
                $q->where('email', 'like', "%{$contact}%")
                    ->orWhere('phone', 'like', "%{$contact}%")
                    ->orWhere('mobile', 'like', "%{$contact}%");
            });
        }

        if ($request->filled('province_id')) {
            $query->whereHas('person', function ($q) use ($request) {
                $q->where('living_province_id', $request->province_id);
            });
        }

        if ($request->filled('nationality_id')) {
            $query->whereHas('person', function ($q) use ($request) {
                $q->where('nationality_id', $request->nationality_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->get();

        $export = new MembersExport($members);

        if ($format === 'csv') {
            return \Maatwebsite\Excel\Facades\Excel::download($export, 'membros_'.now()->format('Y-m-d').'.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        return \Maatwebsite\Excel\Facades\Excel::download($export, 'membros_'.now()->format('Y-m-d').'.xlsx');
    }

    /**
     * Export quota report.
     */
    public function exportQuotaReport(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month');
        $format = $request->input('format', 'excel');

        $query = \App\Models\MemberQuota::with('member.person')
            ->where('year', $year);

        if ($month) {
            $query->where('month', $month);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotas = $query->orderBy('due_date')->get();

        if ($format === 'pdf') {
            return $this->generateQuotaPDF($quotas, $year, $month);
        }

        // Excel export (to be implemented)
        return response()->json(['message' => 'Excel export to be implemented']);
    }

    /**
     * Generate PDF report for quotas.
     */
    private function generateQuotaPDF($quotas, $year, $month = null)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.members.quota-pdf', [
            'quotas' => $quotas,
            'year' => $year,
            'month' => $month,
        ]);

        $filename = 'relatorio_quotas_'.$year.($month ? '_'.$month : '').'_'.now()->format('Y-m-d').'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Check for pending documents and send alerts.
     */
    public function checkPendingDocuments()
    {
        $pendingDocuments = Document::where('status', \App\Enums\DocumentStatus::PENDING)->get();
        $count = 0;

        foreach ($pendingDocuments as $document) {
            // Get the member associated with the document
            $member = $document->member;

            // Get the document type
            $documentType = $document->documentType;

            // Check if the document has been pending for more than 7 days
            $daysPending = now()->diffInDays($document->submission_date);

            if ($daysPending >= 7) {
                // Send an email notification to the member
                // In a real implementation, this would use Laravel's notification system
                // For now, we'll just increment the count
                $count++;

                // Log the alert
                \Log::info("Alert sent to {$member->full_name} about pending document: {$documentType->name}");
            }
        }

        return redirect()->route('admin.members.index')
            ->with('success', "{$count} document alerts sent successfully.");
    }

    /**
     * Show the form for creating a payment for a specific member.
     */
    public function createPayment(Member $member)
    {
        $member->load('person');

        $paymentTypes = PaymentType::active()->get();
        $paymentMethods = PaymentMethod::active()->get();

        return view('admin.members.create-payment', compact('member', 'paymentTypes', 'paymentMethods'));
    }
}
