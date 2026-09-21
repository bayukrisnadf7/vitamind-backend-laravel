<?php

namespace App\Http\Controllers;

use App\Services\ScreeningService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ScreeningService $screeningService
    ) {}

    /**
     * Get list of screening history for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $screenings = $request->user()
            ->screenings()
            ->latest()
            ->get();

        return response()->json([
            'screenings' => $screenings,
        ]);
    }

    /**
     * Submit a new screening.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required',
            'answers.*.answer' => 'required',
            'result' => 'nullable|string',
        ]);

        $calculated = $this->screeningService->calculateResult($validated['answers']);
        $finalResult = ! empty($validated['result']) ? $validated['result'] : $calculated['result'];

        $screening = $this->screeningService->createScreening(
            $request->user(),
            $validated['answers'],
            $finalResult
        );

        return response()->json([
            'message' => 'Skrining berhasil disimpan.',
            'screening' => $screening,
            'calculation' => [
                'score_obtained' => $calculated['score_obtained'],
                'max_score' => $calculated['max_score'],
                'percentage' => $calculated['percentage'],
                'result' => $calculated['result'],
            ],
        ], 200);
    }

    /**
     * Get a specific screening details.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $screening = $request->user()
            ->screenings()
            ->where('skrining_id', $id)
            ->first();

        if (! $screening) {
            return response()->json([
                'message' => 'Data skrining tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'screening' => $screening,
        ]);
    }
}
