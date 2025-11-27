<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Display a listing of the card requests.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all card requests
        $cards = Card::with('member')->latest()->paginate(10);

        // Count cards by status
        $pendingCount = Card::where('status', 'pending')->count();
        $inProductionCount = Card::where('status', 'in_production')->count();
        $completedCount = Card::where('status', 'completed')->count();
        $totalCount = Card::count();

        return view('admin.cards.index', compact(
            'cards',
            'pendingCount',
            'inProductionCount',
            'completedCount',
            'totalCount'
        ));
    }

    /**
     * Show the form for creating a new card request.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get all members for the dropdown
        $members = Member::all();

        return view('admin.cards.create', compact('members'));
    }

    /**
     * Store a newly created card request in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'card_type' => 'required|in:professional_card,digital_wallet',
            'issue_reason' => 'required|string',
            'urgency' => 'required|in:normal,urgent,express',
            'delivery_method' => 'required|in:pickup,mail,courier',
            'delivery_address' => 'nullable|string',
            'observations' => 'nullable|string',
        ]);

        // Add additional data
        $validated['status'] = 'pending';
        $validated['requested_by'] = Auth::id();
        $validated['requested_at'] = now();
        $validated['expected_delivery_date'] = $this->calculateExpectedDeliveryDate($request->urgency);

        // Create the card request
        $card = Card::create($validated);

        return redirect()->route('admin.cards.index')
            ->with('success', 'Card request created successfully.');
    }

    /**
     * Display the specified card request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::with('member', 'requestedBy')->findOrFail($id);

        return view('admin.cards.show', compact('card'));
    }

    /**
     * Update the status of a card request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'status' => 'required|in:pending,in_production,completed,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Update the card status
        $card = Card::findOrFail($id);
        $card->status = $validated['status'];

        // Add status change to history
        $history = $card->status_history ?? [];
        $history[] = [
            'status' => $validated['status'],
            'changed_by' => Auth::id(),
            'changed_at' => now()->toDateTimeString(),
            'notes' => $validated['notes'] ?? null,
        ];
        $card->status_history = $history;

        // If status is delivered, set delivery date
        if ($validated['status'] === 'delivered') {
            $card->delivered_at = now();
        }

        $card->save();

        return redirect()->route('admin.cards.show', $card->id)
            ->with('success', 'Card status updated successfully.');
    }

    /**
     * Display the history of card requests.
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        // Get all completed or delivered cards
        $cards = Card::with('member')
            ->whereIn('status', ['completed', 'delivered'])
            ->latest()
            ->paginate(10);

        // Get statistics
        $totalIssued = Card::whereIn('status', ['completed', 'delivered'])->count();
        $thisMonthIssued = Card::whereIn('status', ['completed', 'delivered'])
            ->whereMonth('created_at', now()->month)
            ->count();
        $renewals = Card::where('issue_reason', 'renewal')->count();
        $successRate = $this->calculateSuccessRate();

        return view('admin.cards.history', compact(
            'cards',
            'totalIssued',
            'thisMonthIssued',
            'renewals',
            'successRate'
        ));
    }

    /**
     * Calculate the expected delivery date based on urgency.
     *
     * @param  string  $urgency
     * @return \Carbon\Carbon
     */
    private function calculateExpectedDeliveryDate($urgency)
    {
        $now = now();

        switch ($urgency) {
            case 'express':
                return $now->addDay();
            case 'urgent':
                return $now->addWeekdays(3);
            case 'normal':
            default:
                return $now->addWeekdays(7);
        }
    }

    /**
     * Calculate the success rate of card requests.
     *
     * @return float
     */
    private function calculateSuccessRate()
    {
        $total = Card::count();

        if ($total === 0) {
            return 100; // No cards, so 100% success rate
        }

        $successful = Card::whereIn('status', ['completed', 'delivered'])->count();

        return round(($successful / $total) * 100, 2);
    }
}
