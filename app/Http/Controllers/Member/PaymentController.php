<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $member = Auth::user()->person->member;

        // Get member payments
        $payments = Payment::where('member_id', $member->id)
            ->with(['paymentType', 'paymentMethod'])
            ->latest()
            ->paginate(10);

        // Calculate payment statistics
        $stats = [
            'total_paid' => Payment::where('member_id', $member->id)
                ->where('status', 'completed')
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            'pending_amount' => Payment::where('member_id', $member->id)
                ->where('status', 'pending')
                ->sum('amount'),
            'overdue_amount' => Payment::where('member_id', $member->id)
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->sum('amount'),
            'next_payment' => Payment::where('member_id', $member->id)
                ->where('status', 'pending')
                ->where('due_date', '>=', now())
                ->orderBy('due_date')
                ->first(),
        ];

        return view('member.payments.index', compact('payments', 'stats'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        $member = Auth::user()->person->member;

        // Get pending payments
        $pendingPayments = Payment::where('member_id', $member->id)
            ->where('status', 'pending')
            ->with(['paymentType'])
            ->get();

        // Get payment types and methods
        $paymentTypes = PaymentType::active()->get();
        $paymentMethods = PaymentMethod::active()->get();

        return view('member.payments.create', compact('pendingPayments', 'paymentTypes', 'paymentMethods'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $member = Auth::user()->person->member;

        $validated = $request->validate([
            'payment_type' => 'required|string|in:pending,new',
            'selected_payment' => 'required_if:payment_type,pending|nullable|string',
            'payment_type_id' => 'required_if:payment_type,new|nullable|exists:payment_types,id',
            'amount' => 'required_if:payment_type,new|nullable|numeric|min:0',
            'description' => 'required_if:payment_type,new|nullable|string|max:255',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Handle pending payment
        if ($validated['payment_type'] === 'pending') {
            $payment = Payment::where('member_id', $member->id)
                ->where('reference_number', $validated['selected_payment'])
                ->firstOrFail();

            // Update payment with method and status
            $payment->update([
                'payment_method_id' => $validated['payment_method_id'],
                'status' => 'completed',
                'payment_date' => now(),
            ]);

            // Handle receipt upload
            if ($request->hasFile('receipt')) {
                $path = $request->file('receipt')->store('receipts', 'public');
                $payment->update(['receipt_path' => $path]);
            }
        }
        // Handle new payment
        else {
            // Create new payment
            $payment = Payment::create([
                'person_id' => $member->person_id,
                'member_id' => $member->id,
                'payment_type_id' => $validated['payment_type_id'],
                'payment_method_id' => $validated['payment_method_id'],
                'reference_number' => 'PAY-'.now()->format('Ymd').'-'.Str::random(5),
                'amount' => $validated['amount'],
                'payment_date' => now(),
                'status' => 'completed',
                'notes' => $validated['description'],
                'payable_type' => 'App\\Models\\Member',
                'payable_id' => $member->id,
                'recorded_by' => Auth::id(),
            ]);

            // Handle receipt upload
            if ($request->hasFile('receipt')) {
                $path = $request->file('receipt')->store('receipts', 'public');
                $payment->update(['receipt_path' => $path]);
            }
        }

        // Generate receipt
        $this->generateReceipt($payment);

        return redirect()->route('member.payments.receipts')
            ->with('success', 'Pagamento processado com sucesso.');
    }

    /**
     * Display the payment receipts.
     */
    public function receipts()
    {
        $member = Auth::user()->person->member;

        // Get completed payments with receipts
        $payments = Payment::where('member_id', $member->id)
            ->where('status', 'completed')
            ->with(['paymentType', 'paymentMethod'])
            ->latest()
            ->paginate(12);

        return view('member.payments.receipts', compact('payments'));
    }

    /**
     * Download a payment receipt.
     */
    public function downloadReceipt($id)
    {
        $member = Auth::user()->person->member;

        $payment = Payment::where('member_id', $member->id)
            ->findOrFail($id);

        if (! $payment->receipt_path) {
            // Generate receipt if not exists
            $path = $this->generateReceipt($payment);
        } else {
            $path = $payment->receipt_path;
        }

        return Storage::disk('public')->download($path, 'comprovante-'.$payment->reference_number.'.pdf');
    }

    /**
     * Send receipt by email.
     */
    public function emailReceipt($id)
    {
        $member = Auth::user()->person->member;

        $payment = Payment::where('member_id', $member->id)
            ->findOrFail($id);

        if (! $payment->receipt_path) {
            // Generate receipt if not exists
            $this->generateReceipt($payment);
        }

        // Send email with receipt
        try {
            \Mail::to($member->person->email)
                ->send(new \App\Mail\PaymentReceipt($payment));

            return redirect()->route('member.payments.receipts')
                ->with('success', 'Comprovante enviado por email com sucesso.');
        } catch (\Exception $e) {
            \Log::error('Error sending payment receipt email: '.$e->getMessage());

            return redirect()->route('member.payments.receipts')
                ->with('error', 'Erro ao enviar o comprovante por email: '.$e->getMessage());
        }
    }

    /**
     * Generate a receipt for the payment.
     */
    private function generateReceipt(Payment $payment)
    {
        // Generate PDF receipt
        $pdf = PDF::loadView('member.payments.receipt', compact('payment'));

        // Save PDF to storage
        $filename = 'receipt-'.$payment->reference_number.'.pdf';
        $path = 'receipts/'.$filename;
        Storage::disk('public')->put($path, $pdf->output());

        // Update payment with receipt path
        $payment->update(['receipt_path' => $path]);

        return $path;
    }
}
