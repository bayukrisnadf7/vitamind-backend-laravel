<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Screening;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard with summary statistics.
     */
    public function index(): View
    {
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalScreenings = Screening::count();

        $newUsersThisMonth = User::where('role', '!=', 'admin')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        $screeningsThisMonth = Screening::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        $riskBreakdown = [
            'rendah' => Screening::where('result', 'Risiko Rendah')->count(),
            'sedang' => Screening::where('result', 'Risiko Sedang')->count(),
            'tinggi' => Screening::where('result', 'Risiko Tinggi')->count(),
        ];

        $riskOther = $totalScreenings - array_sum($riskBreakdown);
        if ($riskOther > 0) {
            $riskBreakdown['lainnya'] = $riskOther;
        }

        // Calculate percentages
        $riskPercentages = [];
        foreach ($riskBreakdown as $key => $count) {
            $riskPercentages[$key] = $totalScreenings > 0 ? round(($count / $totalScreenings) * 100, 1) : 0;
        }

        // Recent screenings with user relation
        $recentScreenings = Screening::with('user.detail')
            ->latest()
            ->take(6)
            ->get();

        // Recent users
        $recentUsers = User::where('role', '!=', 'admin')
            ->with('detail')
            ->withCount('screenings')
            ->latest()
            ->take(5)
            ->get();

        // Monthly trends for the last 6 months
        $monthlyLabels = [];
        $monthlyScreenings = [];
        $monthlyUsers = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->translatedFormat('M Y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $monthlyLabels[] = $monthKey;
            $monthlyScreenings[] = Screening::whereBetween('created_at', [$start, $end])->count();
            $monthlyUsers[] = User::where('role', '!=', 'admin')->whereBetween('created_at', [$start, $end])->count();
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalScreenings',
            'newUsersThisMonth',
            'screeningsThisMonth',
            'riskBreakdown',
            'riskPercentages',
            'recentScreenings',
            'recentUsers',
            'monthlyLabels',
            'monthlyScreenings',
            'monthlyUsers'
        ));
    }
}
