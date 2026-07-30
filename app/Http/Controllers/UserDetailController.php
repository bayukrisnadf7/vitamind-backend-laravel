<?php

namespace App\Http\Controllers;

use App\Services\UserDetailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserDetailController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected UserDetailService $userDetailService
    ) {}

    /**
     * Get detail of the authenticated user.
     */
    public function show(Request $request): JsonResponse
    {
        $detail = $this->userDetailService->getUserDetail($request->user());

        return response()->json([
            'message' => 'Detail pengguna berhasil diambil',
            'user_detail' => $detail,
        ], 200);
    }

    /**
     * Create or update detail of the authenticated user.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nik' => 'nullable|string|digits:16',
            'jenis_kelamin' => 'nullable|string|max:20',
            'gender' => 'nullable|string|max:20',
            'tgl_lahir' => 'nullable|date',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        $detail = $this->userDetailService->updateOrCreateUserDetail(
            $request->user(),
            $validated
        );

        return response()->json([
            'message' => 'Detail pengguna berhasil diperbarui',
            'user_detail' => $detail,
        ], 200);
    }
}
