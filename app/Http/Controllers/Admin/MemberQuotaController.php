<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberQuota;
use App\Services\Member\MemberQuotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberQuotaController extends Controller
{
    /**
     * Display a listing of quotas for a member.
     */
    public function index(Member $member, Request $request)
    {
        $query = $member->quotaHistory()->with('payment')->orderBy('year', 'desc')->orderBy('month', 'desc');

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotas = $query->paginate(20)->withQueryString();

        return view('admin.members.quotas.index', compact('member', 'quotas'));
    }

    /**
     * Show the form for creating a new quota.
     */
    public function create(Member $member)
    {
        return view('admin.members.quotas.create', compact('member'));
    }

    /**
     * Store a newly created quota.
     */
    public function store(Request $request, Member $member, MemberQuotaService $quotaService)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:'.(now()->year + 1),
            'month' => 'required|integer|min:1|max:12',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $quota = $quotaService->generateQuota(
                $member,
                $validated['year'],
                $validated['month'],
                $validated['amount']
            );

            $quota->update([
                'due_date' => $validated['due_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Quota criada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Erro ao criar quota: '.$e->getMessage()]);
        }
    }

    /**
     * Show the form for editing a quota.
     */
    public function edit(Member $member, MemberQuota $quota)
    {
        if ($quota->member_id !== $member->id) {
            abort(403);
        }

        return view('admin.members.quotas.edit', compact('member', 'quota'));
    }

    /**
     * Update a quota.
     */
    public function update(Request $request, Member $member, MemberQuota $quota)
    {
        if ($quota->member_id !== $member->id) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue,waived',
            'penalty_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $quota->update($validated);

        return redirect()->route('admin.members.show', $member)
            ->with('success', 'Quota atualizada com sucesso.');
    }

    /**
     * Mark a quota as paid.
     */
    public function markAsPaid(Request $request, Member $member, MemberQuota $quota, MemberQuotaService $quotaService)
    {
        if ($quota->member_id !== $member->id) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_id' => 'nullable|exists:payments,id',
            'payment_date' => 'nullable|date',
        ]);

        $quotaService->markQuotaAsPaid($quota, $validated['payment_id'] ?? null);

        if ($request->filled('payment_date')) {
            $quota->update(['payment_date' => $validated['payment_date']]);
        }

        return redirect()->route('admin.members.show', $member)
            ->with('success', 'Quota marcada como paga.');
    }

    /**
     * Delete a quota.
     */
    public function destroy(Member $member, MemberQuota $quota)
    {
        if ($quota->member_id !== $member->id) {
            abort(403);
        }

        if ($quota->status === MemberQuota::STATUS_PAID) {
            return back()->withErrors(['error' => 'Não é possível excluir uma quota já paga.']);
        }

        $quota->delete();

        return redirect()->route('admin.members.show', $member)
            ->with('success', 'Quota excluída com sucesso.');
    }
}
