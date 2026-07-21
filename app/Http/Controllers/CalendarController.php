<?php

namespace App\Http\Controllers;

use App\Models\DietItem;
use App\Models\Habit;
use App\Models\HabitCompletion;
use App\Models\Task;
use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $date = Carbon::parse($request->get('date', Carbon::today()->toDateString()))->toDateString();

        $tasks = Task::where('user_id', $user->id)
            ->whereDate('due_date', $date)
            ->orderBy('due_date')
            ->get();

        $dietItems = DietItem::where('user_id', $user->id)
            ->whereDate('date', $date)
            ->orderBy('meal_name')
            ->orderBy('scheduled_time')
            ->get();

        $workouts = Workout::where('user_id', $user->id)
            ->whereDate('date', $date)
            ->orderBy('exercise_name')
            ->get();

        $habits = Habit::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $completionMap = HabitCompletion::where('user_id', $user->id)
            ->whereDate('date', $date)
            ->pluck('is_completed', 'habit_id');

        return view('calendar.index', [
            'date' => $date,
            'tasks' => $tasks,
            'dietItems' => $dietItems,
            'workouts' => $workouts,
            'habits' => $habits,
            'completionMap' => $completionMap,
        ]);
    }
}
