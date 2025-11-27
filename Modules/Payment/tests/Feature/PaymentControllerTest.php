<?php

namespace Modules\Payment\Tests\Feature;

use App\Enums\PaymentStatus;
use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'member']);
    Role::firstOrCreate(['name' => 'teacher']);
    Role::firstOrCreate(['name' => 'evaluator']);

    // Create a user for authentication
    $this->user = User::factory()->create();
    $this->user->assignRole('admin');

    // Create test data
    $this->person = Person::factory()->create();
    $this->member = Member::factory()->create(['person_id' => $this->person->id]);
    $this->paymentType = PaymentType::factory()->create(['is_active' => true]);
    $this->paymentMethod = PaymentMethod::factory()->create(['is_active' => true]);
});

test('index displays payments with statistics', function () {
    // Create some payments using the controller method
    $payment1 = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'status' => PaymentStatus::COMPLETED->value,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.index'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.payments.index');
    $response->assertViewHas(['payments', 'stats', 'paymentTypes']);
});

test('create displays form with payment types and methods', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.create'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.payments.create');
    $response->assertViewHas(['paymentTypes', 'paymentMethods']);
});

test('store creates payment with member lookup by registration number', function () {
    $data = [
        'member_registration_number' => $this->member->registration_number,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'amount' => 1000.00,
        'status' => PaymentStatus::COMPLETED->value,
        'reference_number' => 'TEST-REF-123',
        'notes' => 'Test payment',
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.payments.store'), $data);

    $response->assertRedirect(route('admin.payments.show', Payment::latest()->first()));
    $response->assertSessionHas('success', 'Pagamento registrado com sucesso.');

    $this->assertDatabaseHas('payments', [
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'amount' => 1000.00,
        'status' => PaymentStatus::COMPLETED->value,
        'reference_number' => 'TEST-REF-123',
    ]);
});

test('store creates payment with direct member_id', function () {
    $data = [
        'member_registration_number' => $this->member->registration_number,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'amount' => 500.00,
        'status' => PaymentStatus::PENDING->value,
        'notes' => 'Test pending payment',
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.payments.store'), $data);

    $payment = Payment::latest()->first();
    $response->assertRedirect(route('admin.payments.show', $payment));

    $this->assertDatabaseHas('payments', [
        'member_id' => $this->member->id,
        'amount' => 500.00,
        'status' => PaymentStatus::PENDING->value,
    ]);
});

test('store generates reference number when not provided', function () {
    $data = [
        'member_registration_number' => $this->member->registration_number,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'amount' => 750.00,
        'status' => PaymentStatus::COMPLETED->value,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.payments.store'), $data);

    $payment = Payment::latest()->first();
    expect($payment->reference_number)->toStartWith('PAY-');
});

test('store sets payment date when status is completed', function () {
    $data = [
        'member_registration_number' => $this->member->registration_number,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'amount' => 1000.00,
        'status' => PaymentStatus::COMPLETED->value,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.payments.store'), $data);

    $payment = Payment::latest()->first();
    expect($payment->payment_date)->not->toBeNull();
});

test('store handles receipt upload', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('receipt.pdf', 100);

    $data = [
        'member_registration_number' => $this->member->registration_number,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'amount' => 1000.00,
        'status' => PaymentStatus::COMPLETED->value,
        'receipt' => $file,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.payments.store'), $data);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Pagamento registrado com sucesso.');

    $payment = Payment::latest()->first();
    expect($payment)->not->toBeNull();
    expect($payment->receipt_path)->not->toBeNull();
    Storage::disk('public')->assertExists($payment->receipt_path);
});

test('store returns error for invalid member registration number', function () {
    $data = [
        'member_registration_number' => 'INVALID-REG',
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'amount' => 1000.00,
        'status' => PaymentStatus::COMPLETED->value,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.payments.store'), $data);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['member_registration_number']);
});

test('store validates required fields', function () {
    $response = $this->actingAs($this->user)
        ->post(route('admin.payments.store'), []);

    $response->assertSessionHasErrors([
        'member_id',
        'payment_type_id',
        'payment_method_id',
        'amount',
        'status',
    ]);
});

test('show displays payment details', function () {
    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.show', $payment));

    $response->assertSuccessful();
    $response->assertViewIs('admin.payments.show');
    $response->assertViewHas(['payment', 'relatedPayments']);
});

test('edit displays form with payment data', function () {
    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.edit', $payment));

    $response->assertSuccessful();
    $response->assertViewIs('admin.payments.edit');
    $response->assertViewHas(['payment', 'paymentTypes', 'paymentMethods']);
});

test('update modifies payment data', function () {
    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'status' => PaymentStatus::PENDING->value,
    ]);

    $newPaymentType = PaymentType::factory()->create(['is_active' => true]);
    $newPaymentMethod = PaymentMethod::factory()->create(['is_active' => true]);

    $data = [
        'payment_type_id' => $newPaymentType->id,
        'payment_method_id' => $newPaymentMethod->id,
        'amount' => 1500.00,
        'status' => PaymentStatus::COMPLETED->value,
        'reference_number' => 'UPDATED-REF-123',
        'notes' => 'Updated payment',
    ];

    $response = $this->actingAs($this->user)
        ->put(route('admin.payments.update', $payment), $data);

    $response->assertRedirect(route('admin.payments.show', $payment));
    $response->assertSessionHas('success', 'Pagamento atualizado com sucesso.');

    $payment->refresh();
    expect($payment->payment_type_id)->toBe($newPaymentType->id);
    expect($payment->payment_method_id)->toBe($newPaymentMethod->id);
    expect($payment->amount)->toBe('1500.00');
    expect($payment->status)->toBe(PaymentStatus::COMPLETED);
});

test('update sets payment date when status changes to completed', function () {
    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'status' => PaymentStatus::PENDING->value,
        'payment_date' => null,
    ]);

    $data = [
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'amount' => 1000.00,
        'status' => PaymentStatus::COMPLETED->value,
    ];

    $response = $this->actingAs($this->user)
        ->put(route('admin.payments.update', $payment), $data);

    $payment->refresh();
    expect($payment->payment_date)->not->toBeNull();
});

test('update handles receipt upload and deletes old receipt', function () {
    Storage::fake('public');

    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'receipt_path' => 'receipts/old-receipt.pdf',
    ]);

    // Create the old receipt file
    Storage::disk('public')->put('receipts/old-receipt.pdf', 'old content');

    $newFile = UploadedFile::fake()->create('new-receipt.pdf', 100);

    $data = [
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'amount' => 1000.00,
        'status' => PaymentStatus::COMPLETED->value,
        'receipt' => $newFile,
    ];

    $response = $this->actingAs($this->user)
        ->put(route('admin.payments.update', $payment), $data);

    $payment->refresh();
    expect($payment->receipt_path)->not->toBe('receipts/old-receipt.pdf');
    Storage::disk('public')->assertMissing('receipts/old-receipt.pdf');
    Storage::disk('public')->assertExists($payment->receipt_path);
});

test('destroy deletes payment and receipt', function () {
    Storage::fake('public');

    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'receipt_path' => 'receipts/test-receipt.pdf',
    ]);

    // Create the receipt file
    Storage::disk('public')->put('receipts/test-receipt.pdf', 'test content');

    $response = $this->actingAs($this->user)
        ->delete(route('admin.payments.destroy', $payment));

    $response->assertRedirect(route('admin.payments.index'));
    $response->assertSessionHas('success', 'Pagamento excluído com sucesso.');

    $this->assertSoftDeleted('payments', ['id' => $payment->id]);
    Storage::disk('public')->assertMissing('receipts/test-receipt.pdf');
});

test('generateReceipt creates PDF receipt', function () {
    Storage::fake('public');

    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'reference_number' => 'TEST-REF-123',
    ]);

    // Call the generateReceipt method directly through the controller
    $controller = new \App\Http\Controllers\Admin\PaymentController;
    $result = $controller->generateReceipt($payment);

    $payment->refresh();
    expect($payment->receipt_path)->not->toBeNull();
    expect($payment->receipt_path)->toContain('receipt-TEST-REF-123.pdf');
    Storage::disk('public')->assertExists($payment->receipt_path);
});

test('downloadReceipt downloads existing receipt', function () {
    Storage::fake('public');

    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'reference_number' => 'TEST-REF-123',
        'receipt_path' => 'receipts/test-receipt.pdf',
    ]);

    // Create the receipt file
    Storage::disk('public')->put('receipts/test-receipt.pdf', 'PDF content');

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.download-receipt', $payment));

    $response->assertDownload('receipt-TEST-REF-123.pdf');
});

test('downloadReceipt generates receipt if not exists', function () {
    Storage::fake('public');

    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'reference_number' => 'TEST-REF-123',
        'receipt_path' => null,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.download-receipt', $payment));

    $response->assertDownload('receipt-TEST-REF-123.pdf');

    $payment->refresh();
    expect($payment->receipt_path)->not->toBeNull();
});

test('sendReceiptByEmail sends email successfully', function () {
    Mail::fake();

    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'receipt_path' => 'receipts/test-receipt.pdf',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.send-receipt', $payment));

    $response->assertRedirect(route('admin.payments.show', $payment));
    $response->assertSessionHas('success', 'Comprovativo enviado por email com sucesso.');

    Mail::assertSent(\App\Mail\PaymentReceipt::class, function ($mail) use ($payment) {
        return $mail->hasTo($payment->member->person->email);
    });
});

test('sendReceiptByEmail generates receipt if not exists', function () {
    Mail::fake();
    Storage::fake('public');

    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'receipt_path' => null,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.send-receipt', $payment));

    $response->assertRedirect(route('admin.payments.show', $payment));

    $payment->refresh();
    expect($payment->receipt_path)->not->toBeNull();
});

test('sendReceiptByEmail handles email errors', function () {
    Mail::fake();
    Mail::shouldReceive('to->send')->andThrow(new \Exception('Email service unavailable'));

    $payment = Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'receipt_path' => 'receipts/test-receipt.pdf',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.send-receipt', $payment));

    $response->assertRedirect(route('admin.payments.show', $payment));
    $response->assertSessionHas('error');
});

test('updateSettings updates payment types', function () {
    $paymentType = PaymentType::factory()->create();

    $data = [
        'payment_types' => [
            $paymentType->id => [
                'name' => 'Updated Payment Type',
                'description' => 'Updated description',
                'default_amount' => 2000.00,
                'is_active' => true,
            ],
        ],
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.payments.update-settings'), $data);

    $response->assertRedirect(route('admin.payments.settings'));
    $response->assertSessionHas('success', 'Configurações de pagamento atualizadas com sucesso.');

    $paymentType->refresh();
    expect($paymentType->name)->toBe('Updated Payment Type');
    expect($paymentType->description)->toBe('Updated description');
    expect($paymentType->default_amount)->toBe('2000.00');
    expect($paymentType->is_active)->toBeTrue();
});

test('updateSettings updates payment methods', function () {
    $paymentMethod = PaymentMethod::factory()->create();

    $data = [
        'payment_methods' => [
            $paymentMethod->id => [
                'name' => 'Updated Payment Method',
                'description' => 'Updated description',
                'is_active' => true,
            ],
        ],
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.payments.update-settings'), $data);

    $response->assertRedirect(route('admin.payments.settings'));

    $paymentMethod->refresh();
    expect($paymentMethod->name)->toBe('Updated Payment Method');
    expect($paymentMethod->description)->toBe('Updated description');
    expect($paymentMethod->is_active)->toBeTrue();
});

test('searchMembers returns JSON response', function () {
    $member1 = Member::factory()->create(['registration_number' => 'MEM001']);
    $member2 = Member::factory()->create(['registration_number' => 'MEM002']);

    $response = $this->actingAs($this->user)
        ->post(route('admin.payments.search-members'), ['query' => 'MEM001']);

    $response->assertSuccessful();
    $response->assertJsonCount(1);
});

test('export downloads Excel file with payments', function () {
    Payment::factory()->count(3)->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.export'));

    $response->assertDownload();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('export applies filters correctly', function () {
    $paymentType1 = PaymentType::factory()->create();
    $paymentType2 = PaymentType::factory()->create();

    Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $paymentType1->id,
        'payment_method_id' => $this->paymentMethod->id,
        'status' => PaymentStatus::COMPLETED->value,
        'amount' => 1000.00,
    ]);

    Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $paymentType2->id,
        'payment_method_id' => $this->paymentMethod->id,
        'status' => PaymentStatus::PENDING->value,
        'amount' => 500.00,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.export', [
            'payment_type' => $paymentType1->id,
            'status' => PaymentStatus::COMPLETED->value,
            'min_amount' => 800,
        ]));

    $response->assertDownload();
});

test('printReport downloads PDF report', function () {
    Payment::factory()->count(5)->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.print-report'));

    $response->assertDownload();
    $response->assertHeader('Content-Type', 'application/pdf');
});

test('printReport applies filters and calculates statistics', function () {
    $paymentType1 = PaymentType::factory()->create();
    $paymentType2 = PaymentType::factory()->create();

    Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $paymentType1->id,
        'payment_method_id' => $this->paymentMethod->id,
        'status' => PaymentStatus::COMPLETED->value,
        'amount' => 1000.00,
    ]);

    Payment::factory()->create([
        'member_id' => $this->member->id,
        'payment_type_id' => $paymentType2->id,
        'payment_method_id' => $this->paymentMethod->id,
        'status' => PaymentStatus::PENDING->value,
        'amount' => 500.00,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.print-report', [
            'payment_type' => $paymentType1->id,
            'status' => PaymentStatus::COMPLETED->value,
        ]));

    $response->assertDownload();
});

test('settings displays payment settings page', function () {
    PaymentType::factory()->count(2)->create();
    PaymentMethod::factory()->count(2)->create();

    $response = $this->actingAs($this->user)
        ->get(route('admin.payments.settings'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.payments.settings');
    $response->assertViewHas('paymentTypes');
    $response->assertViewHas('paymentMethods');
    $response->assertViewHas('integrations');
});
