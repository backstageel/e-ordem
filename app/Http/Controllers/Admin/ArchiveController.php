<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Exam;
use App\Models\Member;
use App\Models\Payment;
use Modules\Registration\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    /**
     * Display the archive dashboard.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $type = $request->input('type', 'all');
        $status = $request->input('status', 'all');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        $archivedItems = collect();

        // Get archived members
        if ($type === 'all' || $type === 'members') {
            $membersQuery = Member::onlyTrashed()->with('user');

            if ($date_from && $date_to) {
                $membersQuery->whereBetween('deleted_at', [$date_from, $date_to]);
            }

            $archivedMembers = $membersQuery->get()->map(function ($member) {
                return [
                    'id' => $member->id,
                    'type' => 'member',
                    'name' => $member->name,
                    'email' => $member->email,
                    'deleted_at' => $member->deleted_at,
                    'original_data' => $member,
                ];
            });

            $archivedItems = $archivedItems->merge($archivedMembers);
        }

        // Get archived registrations
        if ($type === 'all' || $type === 'registrations') {
            $registrationsQuery = Registration::onlyTrashed()->with('member');

            if ($date_from && $date_to) {
                $registrationsQuery->whereBetween('deleted_at', [$date_from, $date_to]);
            }

            $archivedRegistrations = $registrationsQuery->get()->map(function ($registration) {
                return [
                    'id' => $registration->id,
                    'type' => 'registration',
                    'name' => $registration->member ? $registration->member->name : 'N/A',
                    'email' => $registration->member ? $registration->member->email : 'N/A',
                    'deleted_at' => $registration->deleted_at,
                    'original_data' => $registration,
                ];
            });

            $archivedItems = $archivedItems->merge($archivedRegistrations);
        }

        // Get archived documents
        if ($type === 'all' || $type === 'documents') {
            $documentsQuery = Document::onlyTrashed()->with('member');

            if ($date_from && $date_to) {
                $documentsQuery->whereBetween('deleted_at', [$date_from, $date_to]);
            }

            $archivedDocuments = $documentsQuery->get()->map(function ($document) {
                return [
                    'id' => $document->id,
                    'type' => 'document',
                    'name' => $document->name,
                    'email' => $document->member ? $document->member->email : 'N/A',
                    'deleted_at' => $document->deleted_at,
                    'original_data' => $document,
                ];
            });

            $archivedItems = $archivedItems->merge($archivedDocuments);
        }

        // Get archived exams
        if ($type === 'all' || $type === 'exams') {
            $examsQuery = Exam::onlyTrashed();

            if ($date_from && $date_to) {
                $examsQuery->whereBetween('deleted_at', [$date_from, $date_to]);
            }

            $archivedExams = $examsQuery->get()->map(function ($exam) {
                return [
                    'id' => $exam->id,
                    'type' => 'exam',
                    'name' => $exam->title,
                    'email' => 'N/A',
                    'deleted_at' => $exam->deleted_at,
                    'original_data' => $exam,
                ];
            });

            $archivedItems = $archivedItems->merge($archivedExams);
        }

        // Get archived payments
        if ($type === 'all' || $type === 'payments') {
            $paymentsQuery = Payment::onlyTrashed()->with('member');

            if ($date_from && $date_to) {
                $paymentsQuery->whereBetween('deleted_at', [$date_from, $date_to]);
            }

            $archivedPayments = $paymentsQuery->get()->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'type' => 'payment',
                    'name' => $payment->member ? $payment->member->name : 'N/A',
                    'email' => $payment->member ? $payment->member->email : 'N/A',
                    'deleted_at' => $payment->deleted_at,
                    'original_data' => $payment,
                ];
            });

            $archivedItems = $archivedItems->merge($archivedPayments);
        }

        // Sort by deleted_at
        $archivedItems = $archivedItems->sortByDesc('deleted_at');

        // Get statistics
        $stats = [
            'total_archived' => $archivedItems->count(),
            'members_archived' => Member::onlyTrashed()->count(),
            'registrations_archived' => Registration::onlyTrashed()->count(),
            'documents_archived' => Document::onlyTrashed()->count(),
            'exams_archived' => Exam::onlyTrashed()->count(),
            'payments_archived' => Payment::onlyTrashed()->count(),
        ];

        // Log the view action

        return view('admin.archive.index', compact('archivedItems', 'stats'));
    }

    /**
     * Restore an archived item.
     */
    public function restore(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:member,registration,document,exam,payment'],
            'id' => ['required', 'integer'],
        ]);

        $type = $request->type;
        $id = $request->id;

        $model = null;
        switch ($type) {
            case 'member':
                $model = Member::onlyTrashed()->findOrFail($id);
                break;
            case 'registration':
                $model = Registration::onlyTrashed()->findOrFail($id);
                break;
            case 'document':
                $model = Document::onlyTrashed()->findOrFail($id);
                break;
            case 'exam':
                $model = Exam::onlyTrashed()->findOrFail($id);
                break;
            case 'payment':
                $model = Payment::onlyTrashed()->findOrFail($id);
                break;
        }

        if ($model) {
            $model->restore();

            // Log the action

            return response()->json([
                'success' => true,
                'message' => ucfirst($type).' restaurado com sucesso.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item não encontrado.',
        ], 404);
    }

    /**
     * Permanently delete an archived item.
     */
    public function forceDelete(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:member,registration,document,exam,payment'],
            'id' => ['required', 'integer'],
        ]);

        $type = $request->type;
        $id = $request->id;

        $model = null;
        switch ($type) {
            case 'member':
                $model = Member::onlyTrashed()->findOrFail($id);
                break;
            case 'registration':
                $model = Registration::onlyTrashed()->findOrFail($id);
                break;
            case 'document':
                $model = Document::onlyTrashed()->findOrFail($id);
                // Delete associated files
                if ($model->file_path && Storage::exists($model->file_path)) {
                    Storage::delete($model->file_path);
                }
                break;
            case 'exam':
                $model = Exam::onlyTrashed()->findOrFail($id);
                break;
            case 'payment':
                $model = Payment::onlyTrashed()->findOrFail($id);
                break;
        }

        if ($model) {
            $model->forceDelete();

            // Log the action

            return response()->json([
                'success' => true,
                'message' => ucfirst($type).' excluído permanentemente.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item não encontrado.',
        ], 404);
    }

    /**
     * View archived item details.
     */
    public function show(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:member,registration,document,exam,payment'],
            'id' => ['required', 'integer'],
        ]);

        $type = $request->type;
        $id = $request->id;

        $model = null;
        switch ($type) {
            case 'member':
                $model = Member::onlyTrashed()->with('user')->findOrFail($id);
                break;
            case 'registration':
                $model = Registration::onlyTrashed()->with('member')->findOrFail($id);
                break;
            case 'document':
                $model = Document::onlyTrashed()->with('member')->findOrFail($id);
                break;
            case 'exam':
                $model = Exam::onlyTrashed()->findOrFail($id);
                break;
            case 'payment':
                $model = Payment::onlyTrashed()->with('member')->findOrFail($id);
                break;
        }

        if (! $model) {
            abort(404);
        }

        // Log the view action

        return view('admin.archive.show', compact('model', 'type'));
    }

    /**
     * Export archive data.
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'all');
        $format = $request->input('format', 'csv');

        // This would implement export functionality
        // For now, return a simple response
        return response()->json([
            'message' => 'Exportação será implementada em breve.',
            'type' => $type,
            'format' => $format,
        ]);
    }

    /**
     * Get archive statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_archived' => Member::onlyTrashed()->count() +
                              Registration::onlyTrashed()->count() +
                              Document::onlyTrashed()->count() +
                              Exam::onlyTrashed()->count() +
                              Payment::onlyTrashed()->count(),
            'members_archived' => Member::onlyTrashed()->count(),
            'registrations_archived' => Registration::onlyTrashed()->count(),
            'documents_archived' => Document::onlyTrashed()->count(),
            'exams_archived' => Exam::onlyTrashed()->count(),
            'payments_archived' => Payment::onlyTrashed()->count(),
            'archived_this_month' => Member::onlyTrashed()->whereMonth('deleted_at', now()->month)->count() +
                                   Registration::onlyTrashed()->whereMonth('deleted_at', now()->month)->count() +
                                   Document::onlyTrashed()->whereMonth('deleted_at', now()->month)->count() +
                                   Exam::onlyTrashed()->whereMonth('deleted_at', now()->month)->count() +
                                   Payment::onlyTrashed()->whereMonth('deleted_at', now()->month)->count(),
        ];

        // Log the view action

        return view('admin.archive.statistics', compact('stats'));
    }
}
