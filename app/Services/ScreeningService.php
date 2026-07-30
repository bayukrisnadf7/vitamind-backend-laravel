<?php

namespace App\Services;

use App\Models\Screening;
use App\Models\User;

class ScreeningService
{
    /**
     * Create a new screening record.
     */
    public function createScreening(User $user, array $answers, ?string $result = null): Screening
    {
        $calculated = $this->calculateResult($answers);
        $finalResult = $result ?: $calculated['result'];

        return Screening::create([
            'user_id' => $user->user_id,
            'answers' => $answers,
            'result' => $finalResult,
        ]);
    }

    /**
     * Calculate screening score, percentage, and risk level.
     *
     * Persentase = (Total Skor Diperoleh / Total Skor Maksimal) * 100%
     * IF Skor <= 33.3 THEN Risiko Rendah
     * IF 33.3 < Skor <= 66.6 THEN Risiko Sedang
     * IF Skor > 66.6 THEN Risiko Tinggi
     */
    public function calculateResult(array $answers): array
    {
        $jsonPath = resource_path('data/screening_questions.json');
        if (! file_exists($jsonPath)) {
            $jsonPath = base_path('../Vitamind/assets/data/screening_questions.json');
        }

        $totalScoreObtained = 0.0;
        $totalMaxScore = 0.0;

        if (file_exists($jsonPath)) {
            $jsonContent = file_get_contents($jsonPath);
            $data = json_decode($jsonContent, true);
            $questions = $data['questions'] ?? [];

            $questionsMap = [];
            foreach ($questions as $q) {
                $idKey = (string) $q['id'];
                $numKey = preg_replace('/[^0-9]/', '', $idKey);
                $questionsMap[$idKey] = $q;
                if ($numKey !== '') {
                    $questionsMap[$numKey] = $q;
                }
            }

            foreach ($answers as $ans) {
                $qId = (string) ($ans['question_id'] ?? '');
                $numId = preg_replace('/[^0-9]/', '', $qId);
                $userAns = (string) ($ans['answer'] ?? '');

                $qObj = $questionsMap[$qId] ?? ($questionsMap[$numId] ?? null);
                if ($qObj && isset($qObj['options'])) {
                    $maxOptScore = 0.0;
                    $optScore = 0.0;

                    foreach ($qObj['options'] as $opt) {
                        $s = (float) ($opt['score'] ?? 0);
                        if ($s > $maxOptScore) {
                            $maxOptScore = $s;
                        }
                        if (trim((string) $opt['value']) === trim($userAns)) {
                            $optScore = $s;
                        }
                    }

                    $totalScoreObtained += $optScore;
                    $totalMaxScore += $maxOptScore;
                }
            }
        }

        $percentage = $totalMaxScore > 0 ? ($totalScoreObtained / $totalMaxScore) * 100 : 0.0;

        if ($percentage <= 33.3) {
            $resultCategory = 'Risiko Rendah';
        } elseif ($percentage <= 66.6) {
            $resultCategory = 'Risiko Sedang';
        } else {
            $resultCategory = 'Risiko Tinggi';
        }

        return [
            'score_obtained' => $totalScoreObtained,
            'max_score' => $totalMaxScore,
            'percentage' => round($percentage, 2),
            'result' => $resultCategory,
        ];
    }
}
