<?php

namespace App\Services;

use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MfaService
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA;
    }

    /**
     * Generate a new secret key for TOTP.
     *
     * @return string
     */
    public function generateSecretKey()
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate a QR code for the given email and secret key.
     *
     * @param  string  $email
     * @param  string  $secretKey
     * @return string
     */
    public function generateQrCode($email, $secretKey)
    {
        $appName = config('app.name');
        $qrCodeUrl = $this->google2fa->getQRCodeUrl($appName, $email, $secretKey);

        return QrCode::size(200)->generate($qrCodeUrl);
    }

    /**
     * Verify a TOTP code against a secret key.
     *
     * @param  string  $secretKey
     * @param  string  $code
     * @return bool
     */
    public function verifyCode($secretKey, $code)
    {
        return $this->google2fa->verifyKey($secretKey, $code);
    }

    /**
     * Generate recovery codes.
     *
     * @param  int  $count  Number of recovery codes to generate
     * @param  int  $length  Length of each recovery code
     * @return array
     */
    public function generateRecoveryCodes($count = 8, $length = 10)
    {
        $recoveryCodes = [];

        for ($i = 0; $i < $count; $i++) {
            $recoveryCodes[] = Str::random($length);
        }

        return $recoveryCodes;
    }
}
