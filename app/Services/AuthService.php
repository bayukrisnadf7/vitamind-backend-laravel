<?php

namespace App\Services;

use App\Mail\ForgotPasswordOtpMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    public function register(array $data): User
    {
        return User::create($data);
    }

    public function login(array $credentials): ?array
    {
        if (! auth()->attempt($credentials)) {
            return null;
        }

        /** @var User $user */
        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function getGoogleRedirectUrl(): string
    {
        return Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
    }

    public function handleGoogleCallback(): array
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengambil data pengguna dari Google.', 401);
        }

        $email = $googleUser->getEmail();
        if (empty($email)) {
            throw new \Exception('Akun Google tidak memiliki alamat email.', 400);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?? explode('@', $email)[0],
                'email' => $email,
                'password' => Hash::make(Str::random(16)),
                'email_verified_at' => Carbon::now(),
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function sendForgotPasswordOtp(string $email): void
    {
        PasswordResetOtp::where('email', $email)->delete();

        $otp = (string) random_int(100000, 999999);

        PasswordResetOtp::create([
            'email' => $email,
            'otp' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::to($email)->send(new ForgotPasswordOtpMail($otp));
    }

    public function verifyOtp(string $email, string $otp): void
    {
        $otpData = PasswordResetOtp::where('email', $email)->first();

        if (! $otpData) {
            throw new \Exception('Kode OTP tidak ditemukan.', 404);
        }

        if ($otpData->expires_at->isPast()) {
            throw new \Exception('Kode OTP telah kedaluwarsa.', 400);
        }

        if (! Hash::check($otp, $otpData->otp)) {
            throw new \Exception('Kode OTP tidak valid.', 400);
        }
    }

    public function resetPassword(string $email, string $otp, string $password): void
    {
        $this->verifyOtp($email, $otp);

        User::where('email', $email)->update([
            'password' => Hash::make($password),
        ]);

        PasswordResetOtp::where('email', $email)->delete();
    }

    public function resetPasswordByEmail(string $email, string $password): void
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw new \Exception('Email tidak terdaftar.', 404);
        }

        $user->update([
            'password' => Hash::make($password),
        ]);

        PasswordResetOtp::where('email', $email)->delete();
    }
}
