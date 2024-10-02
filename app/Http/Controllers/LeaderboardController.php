<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all'); // Default to all time

        $query = User::select('users.id', 'users.name', DB::raw('SUM(activities.points) as total_points'))
            ->join('activities', 'users.id', '=', 'activities.user_id')
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_points', 'desc');

        if ($filter == 'day') {
            $query->whereDate('activities.activity_time', today());
        } elseif ($filter == 'month') {
            $query->whereMonth('activities.activity_time', now()->month)
                  ->whereYear('activities.activity_time', now()->year);
        } elseif ($filter == 'year') {
            $query->whereYear('activities.activity_time', now()->year);
        }

        $leaderboard = $query->get();

        return view('leaderboard.index', compact('leaderboard', 'filter'));
    }

    public function search(Request $request)
    {
        $userId = $request->input('user_id');

        $user = User::select('users.id', 'users.name', DB::raw('SUM(activities.points) as total_points'))
            ->join('activities', 'users.id', '=', 'activities.user_id')
            ->where('users.id', $userId)
            ->groupBy('users.id', 'users.name')
            ->first();

        if (!$user) {
            return redirect()->route('leaderboard.index')->with('error', 'User not found');
        }

        $leaderboard = collect([$user])->merge(
            User::select('users.id', 'users.name', DB::raw('SUM(activities.points) as total_points'))
                ->join('activities', 'users.id', '=', 'activities.user_id')
                ->where('users.id', '!=', $userId)
                ->groupBy('users.id', 'users.name')
                ->orderBy('total_points', 'desc')
                ->get()
        );

        return view('leaderboard.index', compact('leaderboard'))->with('searched', true);
    }

    public function recalculate()
    {
        return redirect()->route('leaderboard.index');
    }
}
