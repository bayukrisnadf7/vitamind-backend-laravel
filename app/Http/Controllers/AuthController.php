<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected AuthService $authService
    ) {}

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->authService->register($validated);

        return response()->json(
            [
                'message' => 'Pengguna berhasil dibuat',
                'user' => $user,
            ],
            200,
        );
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($validated);

        if (! $result) {
            return response()->json(
                [
                    'message' => 'Email atau kata sandi salah',
                ],
                401,
            );
        }

        return response()->json(
            [
                'message' => 'Berhasil masuk',
                'user' => $result['user'],
                'token' => $result['token'],
            ],
            200,
        );
    }

    public function redirectToGoogle()
    {
        return response()->json([
            'url' => $this->authService->getGoogleRedirectUrl(),
        ]);
    }

    public function handleGoogleCallback()
    {
        try {
            $result = $this->authService->handleGoogleCallback();

            return response()->json([
                'message' => 'Berhasil masuk dengan Google',
                'user' => $result['user'],
                'token' => $result['token'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json(
            [
                'message' => 'Berhasil keluar',
            ],
            200,
        );
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $this->authService->sendForgotPasswordOtp($request->email);

        return response()->json([
            'message' => 'Kode OTP telah berhasil dikirim ke email Anda.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        try {
            $this->authService->verifyOtp($request->email, $request->otp);

            return response()->json([
                'message' => 'Kode OTP valid.',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                $e->getCode() ?: 400,
            );
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $this->authService->resetPasswordByEmail(
                $request->email,
                $request->password
            );

            return response()->json([
                'message' => 'Kata sandi Anda telah berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                $e->getCode() ?: 400,
            );
        }
    }

    public function resetPasswordOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $this->authService->resetPassword(
                $request->email,
                $request->otp,
                $request->password
            );

            return response()->json([
                'message' => 'Kata sandi Anda telah berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                $e->getCode() ?: 400,
            );
        }
    }
}
