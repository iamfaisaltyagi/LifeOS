<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Application Settings</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">App Tagline</label>
                        <input type="text" name="app_tagline" value="{{ $settings['app_tagline'] ?? 'Plan your day. Build better habits. Improve your life.' }}" class="w-full border rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="hidden" name="allow_registration" value="0">
                            <input type="checkbox" name="allow_registration" value="1" @checked(($settings['allow_registration'] ?? '1') === '1')>
                            Allow public registration
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Notice</label>
                        <textarea name="maintenance_notice" class="w-full border rounded-md px-3 py-2" rows="3">{{ $settings['maintenance_notice'] ?? '' }}</textarea>
                    </div>

                    <button class="px-4 py-2 rounded-md bg-indigo-600 text-white">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
