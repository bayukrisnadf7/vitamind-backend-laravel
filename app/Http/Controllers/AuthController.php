<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordOtpMail;
use App\Models\PasswordResetOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($validated);

        return response()->json(
            [
                'message' => 'User created successfully',
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

        if (!auth()->attempt($validated)) {
            return response()->json(
                [
                    'message' => 'Invalid credentials',
                ],
                401,
            );
        }

        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(
            [
                'message' => 'User logged in successfully',
                'user' => $user,
                'token' => $token,
            ],
            200,
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            [
                'message' => 'User logged out successfully',
            ],
            200,
        );
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        PasswordResetOtp::where('email', $request->email)->delete();

        $otp = (string) random_int(100000, 999999);

        PasswordResetOtp::create([
            'email' => $request->email,
            'otp' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::to($request->email)->send(new ForgotPasswordOtpMail($otp));

        return response()->json([
            'message' => 'The OTP was successfully sent to your email.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $otpData = PasswordResetOtp::where('email', $request->email)->first();

        if (!$otpData) {
            return response()->json(
                [
                    'message' => 'OTP not found.',
                ],
                404,
            );
        }

        if ($otpData->expires_at->isPast()) {
            return response()->json(
                [
                    'message' => 'OTP has expired.',
                ],
                400,
            );
        }

        if (!Hash::check($request->otp, $otpData->otp)) {
            return response()->json(
                [
                    'message' => 'OTP is invalid.',
                ],
                400,
            );
        }

        return response()->json([
            'message' => 'The OTP is valid.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ]);

        $otpData = PasswordResetOtp::where('email', $request->email)->first();

        if (!$otpData) {
            return response()->json(
                [
                    'message' => 'OTP not found.',
                ],
                404,
            );
        }

        if ($otpData->expires_at->isPast()) {
            return response()->json(
                [
                    'message' => 'OTP has expired.',
                ],
                400,
            );
        }

        if (!Hash::check($request->otp, $otpData->otp)) {
            return response()->json(
                [
                    'message' => 'OTP is invalid.',
                ],
                400,
            );
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        $otpData->delete();

        return response()->json([
            'message' => 'Your password has been reset successfully.',
        ]);
    }
}
