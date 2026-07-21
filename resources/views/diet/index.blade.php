<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Diet Checklist</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('diet-items.index') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Date</label>
                        <input type="date" name="date" value="{{ $date }}" class="border rounded-md px-3 py-2">
                    </div>
                    <button class="bg-gray-900 text-white rounded-md px-4 py-2">Load</button>
                </form>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
                    <div class="border rounded-md p-3">
                        <p class="text-sm text-gray-500">Completion</p>
                        <p class="text-2xl font-semibold">{{ $completionPercentage }}%</p>
                    </div>
                    <div class="border rounded-md p-3">
                        <p class="text-sm text-gray-500">Total Calories</p>
                        <p class="text-2xl font-semibold">{{ $totalCalories }}</p>
                    </div>
                    <div class="border rounded-md p-3">
                        <p class="text-sm text-gray-500">Total Protein</p>
                        <p class="text-2xl font-semibold">{{ number_format($totalProtein, 1) }} g</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Add Diet Item</h3>
                <form method="POST" action="{{ route('diet-items.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input name="meal_name" placeholder="Meal name (Breakfast)" class="border rounded-md px-3 py-2" required>
                    <input name="food_name" placeholder="Food name" class="border rounded-md px-3 py-2" required>
                    <input name="quantity" placeholder="Quantity" class="border rounded-md px-3 py-2">
                    <input type="time" name="scheduled_time" class="border rounded-md px-3 py-2">
                    <button class="bg-emerald-600 text-white rounded-md px-4 py-2">Add</button>
                    <input type="number" min="0" name="calories" placeholder="Calories" class="border rounded-md px-3 py-2">
                    <input type="number" step="0.01" min="0" name="protein" placeholder="Protein" class="border rounded-md px-3 py-2">
                    <input type="number" step="0.01" min="0" name="carbohydrates" placeholder="Carbs" class="border rounded-md px-3 py-2">
                    <input type="number" step="0.01" min="0" name="fat" placeholder="Fat" class="border rounded-md px-3 py-2">
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Meal</th>
                        <th class="py-2">Food</th>
                        <th class="py-2">Nutrition</th>
                        <th class="py-2">Time</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($items as $item)
                        <tr class="border-b align-top">
                            <td class="py-3">{{ $item->meal_name }}</td>
                            <td class="py-3">
                                <p>{{ $item->food_name }}</p>
                                <p class="text-xs text-gray-500">{{ $item->quantity }}</p>
                            </td>
                            <td class="py-3 text-xs text-gray-600">Cal: {{ $item->calories }} | P: {{ $item->protein }} | C: {{ $item->carbohydrates }} | F: {{ $item->fat }}</td>
                            <td class="py-3">{{ $item->scheduled_time ? \Illuminate\Support\Str::substr($item->scheduled_time, 0, 5) : '-' }}</td>
                            <td class="py-3">{{ $item->is_completed ? 'Completed' : 'Pending' }}</td>
                            <td class="py-3">
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('diet-items.toggle', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="px-2 py-1 rounded-md text-xs {{ $item->is_completed ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">{{ $item->is_completed ? 'Undo' : 'Complete' }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('diet-items.destroy', $item) }}" onsubmit="return confirm('Delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2 py-1 rounded-md text-xs bg-red-100 text-red-700">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-gray-500">No diet items for this date.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $items->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
