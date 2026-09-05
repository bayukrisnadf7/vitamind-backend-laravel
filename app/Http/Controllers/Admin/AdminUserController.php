<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $gender = $request->input('gender');

        $query = User::where('role', '!=', 'admin')
            ->with('detail')
            ->withCount('screenings')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('detail', function ($detailQuery) use ($search) {
                        $detailQuery->where('nik', 'like', "%{$search}%")
                            ->orWhere('no_telepon', 'like', "%{$search}%");
                    });
            });
        }

        if ($gender) {
            $query->whereHas('detail', function ($detailQuery) use ($gender) {
                $detailQuery->where('jenis_kelamin', $gender);
            });
        }

        $users = $query->paginate(15)->withQueryString();

        $totalUsers = User::where('role', '!=', 'admin')->count();
        $usersWithDetails = User::where('role', '!=', 'admin')->has('detail')->count();
        $usersWithScreening = User::where('role', '!=', 'admin')->has('screenings')->count();

        return view('admin.users.index', compact(
            'users',
            'search',
            'gender',
            'totalUsers',
            'usersWithDetails',
            'usersWithScreening'
        ));
    }

    /**
     * Display the specified user details and screening history.
     */
    public function show(string $id): View
    {
        $user = User::where('user_id', $id)
            ->with(['detail', 'screenings' => function ($query) {
                $query->latest();
            }])
            ->firstOrFail();

        return view('admin.users.show', compact('user'));
    }
}
