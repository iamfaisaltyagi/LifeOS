<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = AppSetting::whereIn('key', [
            'app_tagline',
            'allow_registration',
            'maintenance_notice',
        ])->pluck('value', 'key');

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_tagline' => ['nullable', 'string', 'max:255'],
            'allow_registration' => ['nullable', 'boolean'],
            'maintenance_notice' => ['nullable', 'string', 'max:500'],
        ]);

        foreach (['app_tagline', 'allow_registration', 'maintenance_notice'] as $key) {
            AppSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $key === 'allow_registration'
                    ? ((bool) ($validated['allow_registration'] ?? false) ? '1' : '0')
                    : (string) ($validated[$key] ?? '')]
            );
        }

        return redirect()->route('admin.settings.index')->with('status', 'Settings updated.');
    }
}
