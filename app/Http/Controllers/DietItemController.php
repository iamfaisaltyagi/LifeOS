<?php

namespace App\Http\Controllers;

use App\Models\DietItem;
use App\Models\DietPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DietItemController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        $items = DietItem::where('user_id', $request->user()->id)
            ->whereDate('date', $date)
            ->orderBy('meal_name')
            ->orderBy('scheduled_time')
            ->paginate(12)
            ->withQueryString();

        $summary = DietItem::where('user_id', $request->user()->id)
            ->whereDate('date', $date)
            ->selectRaw('COUNT(*) as total_items')
            ->selectRaw('SUM(CASE WHEN is_completed = 1 THEN 1 ELSE 0 END) as completed_items')
            ->selectRaw('SUM(calories) as total_calories')
            ->selectRaw('SUM(protein) as total_protein')
            ->first();

        $totalItems = (int) ($summary->total_items ?? 0);
        $completedItems = (int) ($summary->completed_items ?? 0);
        $completionPercentage = $totalItems > 0 ? (int) round(($completedItems / $totalItems) * 100) : 0;

        return view('diet.index', [
            'items' => $items,
            'date' => $date,
            'completionPercentage' => $completionPercentage,
            'totalCalories' => (int) ($summary->total_calories ?? 0),
            'totalProtein' => (float) ($summary->total_protein ?? 0),
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('diet-items.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'meal_name' => ['required', 'string', 'max:100'],
            'food_name' => ['required', 'string', 'max:255'],
            'quantity' => ['nullable', 'string', 'max:100'],
            'calories' => ['nullable', 'integer', 'min:0'],
            'protein' => ['nullable', 'numeric', 'min:0'],
            'carbohydrates' => ['nullable', 'numeric', 'min:0'],
            'fat' => ['nullable', 'numeric', 'min:0'],
            'scheduled_time' => ['nullable', 'date_format:H:i'],
            'date' => ['required', 'date'],
        ]);

        $plan = DietPlan::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'plan_date' => $validated['date'],
            ],
            [
                'title' => 'Daily Diet Plan',
            ]
        );

        $request->user()->dietItems()->create(array_merge($validated, [
            'diet_plan_id' => $plan->id,
            'calories' => $validated['calories'] ?? 0,
            'protein' => $validated['protein'] ?? 0,
            'carbohydrates' => $validated['carbohydrates'] ?? 0,
            'fat' => $validated['fat'] ?? 0,
        ]));

        return redirect()->route('diet-items.index', ['date' => $validated['date']])->with('status', 'Diet item added.');
    }

    public function show(string $id): RedirectResponse
    {
        return redirect()->route('diet-items.index');
    }

    public function edit(string $id): RedirectResponse
    {
        return redirect()->route('diet-items.index');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $item = $request->user()->dietItems()->findOrFail($id);

        $validated = $request->validate([
            'meal_name' => ['required', 'string', 'max:100'],
            'food_name' => ['required', 'string', 'max:255'],
            'quantity' => ['nullable', 'string', 'max:100'],
            'calories' => ['nullable', 'integer', 'min:0'],
            'protein' => ['nullable', 'numeric', 'min:0'],
            'carbohydrates' => ['nullable', 'numeric', 'min:0'],
            'fat' => ['nullable', 'numeric', 'min:0'],
            'scheduled_time' => ['nullable', 'date_format:H:i'],
            'date' => ['required', 'date'],
        ]);

        $plan = DietPlan::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'plan_date' => $validated['date'],
            ],
            [
                'title' => 'Daily Diet Plan',
            ]
        );

        $item->update(array_merge($validated, [
            'diet_plan_id' => $plan->id,
        ]));

        return redirect()->route('diet-items.index', ['date' => $validated['date']])->with('status', 'Diet item updated.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $item = $request->user()->dietItems()->findOrFail($id);
        $date = optional($item->date)->toDateString();
        $item->delete();

        return redirect()->route('diet-items.index', ['date' => $date])->with('status', 'Diet item deleted.');
    }

    public function toggle(Request $request, string $id): RedirectResponse
    {
        $item = $request->user()->dietItems()->findOrFail($id);

        $item->update([
            'is_completed' => !$item->is_completed,
            'completed_at' => $item->is_completed ? null : now(),
        ]);

        return redirect()->route('diet-items.index', ['date' => optional($item->date)->toDateString()])->with('status', 'Diet item status updated.');
    }
}
