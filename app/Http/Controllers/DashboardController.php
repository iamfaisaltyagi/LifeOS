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

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today();

        $tasksTotal = Task::where('user_id', $user->id)->whereDate('created_at', '<=', $today)->count();
        $tasksCompleted = Task::where('user_id', $user->id)->where('status', 'completed')->count();
        $todayTasksTotal = Task::where('user_id', $user->id)->whereDate('due_date', $today)->count();
        $todayTasksCompleted = Task::where('user_id', $user->id)->whereDate('due_date', $today)->where('status', 'completed')->count();

        $todayDietTotal = DietItem::where('user_id', $user->id)->whereDate('date', $today)->count();
        $todayDietCompleted = DietItem::where('user_id', $user->id)->whereDate('date', $today)->where('is_completed', true)->count();

        $todayWorkoutTotal = Workout::where('user_id', $user->id)->whereDate('date', $today)->count();
        $todayWorkoutCompleted = Workout::where('user_id', $user->id)->whereDate('date', $today)->where('is_completed', true)->count();

        $todayHabitsTotal = Habit::where('user_id', $user->id)->where('is_active', true)->count();
        $todayHabitsCompleted = HabitCompletion::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->where('is_completed', true)
            ->count();

        $taskProgress = $todayTasksTotal > 0 ? (int) round(($todayTasksCompleted / $todayTasksTotal) * 100) : 0;
        $dietProgress = $todayDietTotal > 0 ? (int) round(($todayDietCompleted / $todayDietTotal) * 100) : 0;
        $workoutProgress = $todayWorkoutTotal > 0 ? (int) round(($todayWorkoutCompleted / $todayWorkoutTotal) * 100) : 0;
        $habitProgress = $todayHabitsTotal > 0 ? (int) round(($todayHabitsCompleted / $todayHabitsTotal) * 100) : 0;
        $overallProgress = (int) round(collect([$taskProgress, $dietProgress, $workoutProgress, $habitProgress])->avg());

        $pendingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('due_date')
            ->limit(6)
            ->get();

        $upcomingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereDate('due_date', '>=', $today)
            ->orderBy('due_date')
            ->limit(6)
            ->get();

        $todayTasks = Task::where('user_id', $user->id)
            ->whereDate('due_date', $today)
            ->orderBy('status')
            ->orderBy('priority')
            ->orderBy('title')
            ->get();

        $todayDietItems = DietItem::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->orderBy('meal_name')
            ->orderBy('scheduled_time')
            ->get();

        $todayWorkouts = Workout::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->orderBy('exercise_name')
            ->get();

        $todayHabits = Habit::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $todayHabitCompletionMap = HabitCompletion::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->pluck('is_completed', 'habit_id');

        return view('dashboard', [
            'overallProgress' => $overallProgress,
            'taskProgress' => $taskProgress,
            'dietProgress' => $dietProgress,
            'workoutProgress' => $workoutProgress,
            'habitProgress' => $habitProgress,
            'todayDate' => $today->toDateString(),
            'todayTasks' => $todayTasks,
            'todayDietItems' => $todayDietItems,
            'todayWorkouts' => $todayWorkouts,
            'todayHabits' => $todayHabits,
            'todayHabitCompletionMap' => $todayHabitCompletionMap,
            'pendingTasks' => $pendingTasks,
            'upcomingTasks' => $upcomingTasks,
            'totals' => [
                'tasks' => [$tasksCompleted, $tasksTotal],
                'todayTasks' => [$todayTasksCompleted, $todayTasksTotal],
                'diet' => [$todayDietCompleted, $todayDietTotal],
                'workout' => [$todayWorkoutCompleted, $todayWorkoutTotal],
                'habit' => [$todayHabitsCompleted, $todayHabitsTotal],
            ],
        ]);
    }
}
