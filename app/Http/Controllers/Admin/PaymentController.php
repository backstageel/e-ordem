<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentIntegration;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['member', 'paymentType', 'paymentMethod'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total_month' => Payment::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_amount' => Payment::where('status', 'pending')
                ->sum('amount'),
            'payments_today' => Payment::whereDate('created_at', today())
                ->count(),
            'average_payment' => Payment::where('status', 'completed')
                ->avg('amount') ?? 0,
        ];

        $paymentTypes = PaymentType::withCount('payments')
            ->with(['payments' => function ($query) {
                $query->where('status', 'paid');
            }])
            ->get()
            ->map(function ($type) {
                $type->total_amount = $type->payments->sum('amount');

                return $type;
            });

        return view('admin.payments.index', compact('payments', 'stats', 'paymentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paymentTypes = PaymentType::active()->get();
        $paymentMethods = PaymentMethod::active()->get();

        return view('admin.payments.create', compact('paymentTypes', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Look up member by registration number
        if ($request->has('member_registration_number')) {
            $member = Member::where('registration_number', $request->member_registration_number)->first();

            if ($member) {
                $request->merge(['member_id' => $member->id]);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['member_registration_number' => 'Membro com este número de registo não encontrado.']);
            }
        }

        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'payment_type_id' => 'required|exists:payment_types,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'status' => ['required', 'in:'.implode(',', \App\Enums\PaymentStatus::values())],
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Get member instance
        $member = Member::findOrFail($validated['member_id']);
        $validated['person_id'] = $member->person_id;

        // Set payable fields for polymorphic relationship
        $validated['payable_type'] = 'App\\Models\\Member';
        $validated['payable_id'] = $member->id;

        // Generate reference number if not provided
        if (empty($validated['reference_number'])) {
            $validated['reference_number'] = 'PAY-'.now()->format('Ymd').'-'.Str::random(5);
        }

        // Set payment date to now if not provided and status is completed
        if (empty($validated['payment_date']) && $validated['status'] === \App\Enums\PaymentStatus::COMPLETED->value) {
            $validated['payment_date'] = now();
        }

        // Set recorded_by to current user
        $validated['recorded_by'] = auth()->id();

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt_path'] = $path;
        }

        // Remove the receipt file from validated data as it's not a database column
        unset($validated['receipt']);

        $payment = Payment::create($validated);

        // Generate receipt if payment is completed
        if ($payment->status === \App\Enums\PaymentStatus::COMPLETED->value) {
            $this->generateReceipt($payment);
        }

        // Redirect back to member page if coming from member-specific payment route
        if ($request->has('from_member_page')) {
            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Pagamento registrado com sucesso.');
        }

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Pagamento registrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::with(['member', 'paymentType', 'paymentMethod', 'recordedBy'])
            ->findOrFail($id);

        // Get related payments for the same member
        $relatedPayments = Payment::where('member_id', $payment->member_id)
            ->where('id', '!=', $payment->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.payments.show', compact('payment', 'relatedPayments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        $paymentTypes = PaymentType::active()->get();
        $paymentMethods = PaymentMethod::active()->get();

        return view('admin.payments.edit', compact('payment', 'paymentTypes', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'status' => ['required', 'in:'.implode(',', \App\Enums\PaymentStatus::values())],
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Set payment date to now if status changed to completed
        if ($payment->status !== \App\Enums\PaymentStatus::COMPLETED->value && $validated['status'] === \App\Enums\PaymentStatus::COMPLETED->value && empty($validated['payment_date'])) {
            $validated['payment_date'] = now();
        }

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($payment->receipt_path) {
                Storage::disk('public')->delete($payment->receipt_path);
            }

            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt_path'] = $path;
        }

        // Remove the receipt file from validated data as it's not a database column
        unset($validated['receipt']);

        $payment->update($validated);

        // Generate receipt if payment is now paid
        if ($payment->status === \App\Enums\PaymentStatus::COMPLETED->value && ! $payment->receipt_path) {
            $this->generateReceipt($payment);
        }

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Pagamento atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);

        // Delete receipt if exists
        if ($payment->receipt_path) {
            Storage::disk('public')->delete($payment->receipt_path);
        }

        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pagamento excluído com sucesso.');
    }

    /**
     * Generate a receipt for the payment.
     */
    public function generateReceipt(Payment $payment)
    {
        // Generate PDF receipt
        $pdf = PDF::loadView('admin.payments.receipt', compact('payment'));

        // Save PDF to storage
        $filename = 'receipt-'.$payment->reference_number.'.pdf';
        $path = 'receipts/'.$filename;
        Storage::disk('public')->put($path, $pdf->output());

        // Update payment with receipt path
        $payment->update(['receipt_path' => $path]);

        return $path;
    }

    /**
     * Download receipt for the payment.
     */
    public function downloadReceipt(string $id)
    {
        $payment = Payment::findOrFail($id);

        if (! $payment->receipt_path || ! Storage::disk('public')->exists($payment->receipt_path)) {
            // Generate receipt if not exists
            $path = $this->generateReceipt($payment);
            $payment->refresh();
        } else {
            $path = $payment->receipt_path;
        }

        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'Receipt not found');
        }

        return Storage::disk('public')->download($path, 'receipt-'.$payment->reference_number.'.pdf');
    }

    /**
     * Send receipt by email.
     */
    public function sendReceiptByEmail(string $id)
    {
        $payment = Payment::with('member.person')->findOrFail($id);

        if (! $payment->receipt_path) {
            // Generate receipt if not exists
            $this->generateReceipt($payment);
        }

        // Send email with receipt
        try {
            \Mail::to($payment->member->person->email)
                ->send(new \App\Mail\PaymentReceipt($payment));

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Comprovativo enviado por email com sucesso.');
        } catch (\Exception $e) {
            \Log::error('Error sending payment receipt email: '.$e->getMessage());

            return redirect()->route('admin.payments.show', $payment)
                ->with('error', 'Erro ao enviar o comprovativo por email: '.$e->getMessage());
        }
    }

    /**
     * Show payment settings.
     */
    public function settings()
    {
        $paymentTypes = PaymentType::all();
        $paymentMethods = PaymentMethod::all();

        // Check if payment_integrations table exists
        $integrations = collect();
        try {
            if (\Schema::hasTable('payment_integrations')) {
                $integrations = PaymentIntegration::all();
            }
        } catch (\Exception $e) {
            // Table doesn't exist or other error, use empty collection
            $integrations = collect();
        }

        return view('admin.payments.settings', compact('paymentTypes', 'paymentMethods', 'integrations'));
    }

    /**
     * Update payment settings.
     */
    public function updateSettings(Request $request)
    {
        // Update payment types
        if ($request->has('payment_types')) {
            foreach ($request->payment_types as $id => $data) {
                PaymentType::findOrFail($id)->update([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'default_amount' => $data['default_amount'],
                    'is_active' => isset($data['is_active']),
                ]);
            }
        }

        // Update payment methods
        if ($request->has('payment_methods')) {
            foreach ($request->payment_methods as $id => $data) {
                PaymentMethod::findOrFail($id)->update([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'is_active' => isset($data['is_active']),
                ]);
            }
        }

        // Update integrations
        if ($request->has('integrations')) {
            foreach ($request->integrations as $id => $data) {
                $integration = PaymentIntegration::findOrFail($id);

                $config = $integration->config;
                foreach ($data['config'] as $key => $value) {
                    $config[$key] = $value;
                }

                $integration->update([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'config' => $config,
                    'environment' => $data['environment'],
                    'is_active' => isset($data['is_active']),
                ]);
            }
        }

        return redirect()->route('admin.payments.settings')
            ->with('success', 'Configurações de pagamento atualizadas com sucesso.');
    }

    /**
     * Search for members.
     */
    public function searchMembers(Request $request)
    {
        $query = $request->input('query');

        $members = Member::with('person')
            ->whereHas('person', function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->orWhere('registration_number', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($members);
    }

    /**
     * Export payments to Excel.
     */
    public function export(Request $request)
    {
        // Apply filters from request
        $query = Payment::with(['member.person', 'paymentType', 'paymentMethod']);

        // Apply filters
        if ($request->filled('payment_type')) {
            $query->where('payment_type_id', $request->payment_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method_id', $request->payment_method);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // Get payments
        $payments = $query->latest()->get();

        // Create Excel file
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Membro');
        $sheet->setCellValue('C1', 'Número de Membro');
        $sheet->setCellValue('D1', 'Tipo de Taxa');
        $sheet->setCellValue('E1', 'Valor');
        $sheet->setCellValue('F1', 'Data');
        $sheet->setCellValue('G1', 'Método');
        $sheet->setCellValue('H1', 'Status');
        $sheet->setCellValue('I1', 'Referência');

        // Fill data
        $row = 2;
        foreach ($payments as $payment) {
            $sheet->setCellValue('A'.$row, $payment->id);
            $sheet->setCellValue('B'.$row, $payment->member->person->full_name ?? 'N/A');
            $sheet->setCellValue('C'.$row, $payment->member->registration_number ?? 'N/A');
            $sheet->setCellValue('D'.$row, $payment->paymentType->name ?? 'N/A');
            $sheet->setCellValue('E'.$row, $payment->amount);
            $sheet->setCellValue('F'.$row, $payment->payment_date ? $payment->payment_date->format('d/m/Y H:i') : 'N/A');
            $sheet->setCellValue('G'.$row, $payment->paymentMethod->name ?? 'N/A');
            $sheet->setCellValue('H'.$row, $payment->status instanceof \App\Enums\PaymentStatus ? $payment->status->label() : $payment->status);
            $sheet->setCellValue('I'.$row, $payment->reference_number);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'pagamentos_'.date('Y-m-d_H-i-s').'.xlsx';
        $path = storage_path('app/public/exports/'.$filename);

        // Ensure directory exists
        if (! file_exists(storage_path('app/public/exports'))) {
            mkdir(storage_path('app/public/exports'), 0755, true);
        }

        $writer->save($path);

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Print payment report.
     */
    public function printReport(Request $request)
    {
        // Apply filters from request
        $query = Payment::with(['member', 'paymentType', 'paymentMethod']);

        // Apply filters (same as export method)
        if ($request->filled('payment_type')) {
            $query->where('payment_type_id', $request->payment_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method_id', $request->payment_method);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // Get payments
        $payments = $query->latest()->get();

        // Calculate statistics
        $stats = [
            'total' => $payments->sum('amount'),
            'count' => $payments->count(),
            'paid' => $payments->where('status', \App\Enums\PaymentStatus::COMPLETED->value)->sum('amount'),
            'pending' => $payments->where('status', \App\Enums\PaymentStatus::PENDING->value)->sum('amount'),
            'by_type' => $payments->groupBy('payment_type_id')->map(function ($items, $typeId) {
                $type = PaymentType::find($typeId);

                return [
                    'name' => $type ? $type->name : 'Desconhecido',
                    'count' => $items->count(),
                    'total' => $items->sum('amount'),
                ];
            }),
            'by_method' => $payments->groupBy('payment_method_id')->map(function ($items, $methodId) {
                $method = PaymentMethod::find($methodId);

                return [
                    'name' => $method ? $method->name : 'Desconhecido',
                    'count' => $items->count(),
                    'total' => $items->sum('amount'),
                ];
            }),
        ];

        // Generate PDF
        $pdf = PDF::loadView('admin.payments.report', compact('payments', 'stats', 'request'));

        // Return PDF for download
        return $pdf->download('relatorio_pagamentos_'.date('Y-m-d_H-i-s').'.pdf');
    }
}
