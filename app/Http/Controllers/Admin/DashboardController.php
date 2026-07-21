<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DietItem;
use App\Models\Habit;
use App\Models\Task;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_suspended', false)->count();
        $suspendedUsers = User::where('is_suspended', true)->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'suspendedUsers' => $suspendedUsers,
            'totalTasks' => Task::count(),
            'totalDietItems' => DietItem::count(),
            'totalWorkouts' => Workout::count(),
            'totalHabits' => Habit::count(),
            'latestUsers' => User::latest()->limit(8)->get(),
        ]);
    }
}
