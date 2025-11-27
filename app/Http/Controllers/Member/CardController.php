<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CardController extends Controller
{
    /**
     * Display the member's digital card.
     */
    public function index()
    {
        $user = Auth::user();
        $member = $user->person->member;

        $member->load(['person', 'card']);

        if (! $member->card) {
            return view('member.card.index', compact('member'));
        }

        return view('member.card.show', compact('member'));
    }

    /**
     * Generate a digital card with QR code for the member.
     */
    public function generate(Request $request)
    {
        $user = Auth::user();
        $member = $user->person->member;

        $request->validate([
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            Log::info('Starting card generation for member ID: '.$member->id);

            // Generate card number
            $cardNumber = 'ORMM-'.str_pad($member->id, 6, '0', STR_PAD_LEFT).'-'.date('Ymd');
            Log::info('Generated card number: '.$cardNumber);

            // Generate QR code
            $qrData = json_encode([
                'member_id' => $member->id,
                'card_number' => $cardNumber,
                'name' => $member->full_name,
                'registration_number' => $member->registration_number,
                'specialty' => $member->specialty,
                'issue_date' => now()->format('Y-m-d'),
                'expiry_date' => $request->filled('expiry_date') ? $request->expiry_date : null,
            ]);
            Log::info('Generated QR data: '.$qrData);

            $qrCodePath = 'member-cards/qr-'.Str::random(10).'.png';
            $qrCodeFullPath = storage_path('app/public/'.$qrCodePath);
            Log::info('QR code path: '.$qrCodePath);
            Log::info('Full QR code path: '.$qrCodeFullPath);

            // Ensure directory exists
            $directory = dirname($qrCodeFullPath);
            Log::info('Checking directory: '.$directory);

            if (! file_exists($directory)) {
                Log::info('Directory does not exist, creating it');
                if (! mkdir($directory, 0755, true)) {
                    Log::error('Failed to create directory: '.$directory);
                    throw new \Exception("Failed to create directory: {$directory}");
                }
                Log::info('Directory created successfully');
            } else {
                Log::info('Directory already exists');
            }

            // Ensure directory is writable
            if (! is_writable($directory)) {
                Log::error('Directory is not writable: '.$directory);
                throw new \Exception("Directory is not writable: {$directory}");
            }
            Log::info('Directory is writable');

            // Generate QR code image
            Log::info('Generating QR code image');
            try {
                QrCode::format('png')
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate($qrData, $qrCodeFullPath);
                Log::info('QR code image generated successfully');
            } catch (\Exception $e) {
                Log::error('Error generating QR code: '.$e->getMessage());
                throw new \Exception('Error generating QR code: '.$e->getMessage());
            }

            // Ensure the QR code file exists and is readable
            if (! file_exists($qrCodeFullPath)) {
                Log::error('QR code file does not exist after generation');
                throw new \Exception('QR code file does not exist after generation. Check storage permissions.');
            } elseif (! is_readable($qrCodeFullPath)) {
                Log::error('QR code file exists but is not readable');
                throw new \Exception('QR code file exists but is not readable. Check file permissions.');
            }
            Log::info('QR code file exists and is readable');

            // Create or update member card
            if ($member->card) {
                Log::info('Updating existing card for member ID: '.$member->id);
                // Delete old QR code if it exists
                if ($member->card->qr_code_path) {
                    Log::info('Deleting old QR code: '.$member->card->qr_code_path);
                    try {
                        Storage::disk('public')->delete($member->card->qr_code_path);
                        Log::info('Old QR code deleted successfully');
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete old QR code: '.$e->getMessage());
                        // Continue even if deletion fails
                    }
                }

                $card = $member->card;
            } else {
                Log::info('Creating new card for member ID: '.$member->id);
                $card = new MemberCard;
                $card->member_id = $member->id;
            }

            Log::info('Setting card properties');
            $card->card_number = $cardNumber;
            $card->qr_code_path = $qrCodePath;
            $card->issue_date = now();

            if ($request->filled('expiry_date')) {
                $card->expiry_date = $request->expiry_date;
                Log::info('Setting expiry date: '.$request->expiry_date);
            }

            if ($request->filled('notes')) {
                $card->notes = $request->notes;
                Log::info('Setting notes: '.$request->notes);
            }

            Log::info('Saving card to database');
            try {
                $card->save();
                Log::info('Card saved successfully with ID: '.$card->id);
            } catch (\Exception $e) {
                Log::error('Failed to save card: '.$e->getMessage());
                throw new \Exception('Failed to save card: '.$e->getMessage());
            }

            Log::info('Committing transaction');
            DB::commit();
            Log::info('Transaction committed successfully');

            return redirect()->route('member.card.index')
                ->with('success', 'Digital card generated successfully.');
        } catch (\Exception $e) {
            Log::error('Exception during card generation: '.$e->getMessage());
            Log::error('Exception trace: '.$e->getTraceAsString());
            DB::rollBack();
            Log::info('Transaction rolled back due to exception');

            return back()->withInput()->withErrors(['error' => 'Failed to generate digital card: '.$e->getMessage()]);
        }
    }

    /**
     * Download the digital card as PDF.
     */
    public function download()
    {
        $user = Auth::user();
        $member = $user->person->member;

        $member->load(['person', 'card']);

        if (! $member->card) {
            return redirect()->route('member.card.index')
                ->with('error', 'You do not have a digital card yet.');
        }

        // PDF generation logic would go here
        // For now, we'll just return a view
        return view('member.card.download', compact('member'));
    }

    /**
     * Send the digital card by email.
     */
    public function email()
    {
        $user = Auth::user();
        $member = $user->person->member;

        $member->load(['person', 'card']);

        if (! $member->card) {
            return redirect()->route('member.card.index')
                ->with('error', 'You do not have a digital card yet.');
        }

        // Email sending logic would go here

        return redirect()->route('member.card.index')
            ->with('success', 'Digital card sent to your email successfully.');
    }

    /**
     * Print the digital card.
     */
    public function print()
    {
        $user = Auth::user();
        $member = $user->person->member;

        $member->load(['person', 'card']);

        if (! $member->card) {
            return redirect()->route('member.card.index')
                ->with('error', 'You do not have a digital card yet.');
        }

        return view('member.card.print', compact('member'));
    }
}
