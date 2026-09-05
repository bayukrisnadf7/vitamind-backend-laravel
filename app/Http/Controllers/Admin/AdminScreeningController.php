<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Screening;
use App\Services\ScreeningService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminScreeningController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ScreeningService $screeningService
    ) {}

    /**
     * Display a listing of all screenings.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $risk = $request->input('risk');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Screening::with('user.detail')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('skrining_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhereHas('detail', function ($dQuery) use ($search) {
                                $dQuery->where('nik', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($risk) {
            $query->where('result', $risk);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $screenings = $query->paginate(15)->withQueryString();

        $totalScreenings = Screening::count();
        $totalLow = Screening::where('result', 'Risiko Rendah')->count();
        $totalMedium = Screening::where('result', 'Risiko Sedang')->count();
        $totalHigh = Screening::where('result', 'Risiko Tinggi')->count();

        return view('admin.screenings.index', compact(
            'screenings',
            'search',
            'risk',
            'dateFrom',
            'dateTo',
            'totalScreenings',
            'totalLow',
            'totalMedium',
            'totalHigh'
        ));
    }

    /**
     * Display the specified screening details with questionnaire breakdown.
     */
    public function show(string $id): View
    {
        $screening = Screening::with('user.detail')
            ->where('skrining_id', $id)
            ->firstOrFail();

        // Load question details from JSON
        $jsonPath = resource_path('data/screening_questions.json');
        if (! file_exists($jsonPath)) {
            $jsonPath = base_path('../Vitamind/assets/data/screening_questions.json');
        }

        $questionsMap = [];
        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);
            $questions = $jsonData['questions'] ?? [];

            foreach ($questions as $q) {
                $idKey = (string) $q['id'];
                $numKey = preg_replace('/[^0-9]/', '', $idKey);
                $questionsMap[$idKey] = $q;
                if ($numKey !== '') {
                    $questionsMap[$numKey] = $q;
                }
            }
        }

        $answersList = $screening->answers ?? [];
        $detailedAnswers = [];
        $totalScoreObtained = 0.0;
        $totalMaxScore = 0.0;

        foreach ($answersList as $ans) {
            $qId = (string) ($ans['question_id'] ?? '');
            $numId = preg_replace('/[^0-9]/', '', $qId);
            $userAns = (string) ($ans['answer'] ?? '');

            $qObj = $questionsMap[$qId] ?? ($questionsMap[$numId] ?? null);

            $questionText = $qObj['question'] ?? 'Pertanyaan ID: '.$qId;
            $category = $qObj['category'] ?? 'Umum';
            $maxOptScore = 0.0;
            $optScore = 0.0;

            if ($qObj && isset($qObj['options'])) {
                foreach ($qObj['options'] as $opt) {
                    $s = (float) ($opt['score'] ?? 0);
                    if ($s > $maxOptScore) {
                        $maxOptScore = $s;
                    }
                    if (trim((string) $opt['value']) === trim($userAns)) {
                        $optScore = $s;
                    }
                }
            }

            $totalScoreObtained += $optScore;
            $totalMaxScore += $maxOptScore;

            $detailedAnswers[] = [
                'question_id' => $qId,
                'category' => $category,
                'question' => $questionText,
                'user_answer' => $userAns,
                'score' => $optScore,
                'max_score' => $maxOptScore,
            ];
        }

        $percentage = $totalMaxScore > 0 ? round(($totalScoreObtained / $totalMaxScore) * 100, 2) : 0;

        return view('admin.screenings.show', compact(
            'screening',
            'detailedAnswers',
            'totalScoreObtained',
            'totalMaxScore',
            'percentage'
        ));
    }
}
