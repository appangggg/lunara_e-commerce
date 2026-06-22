<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'manifesto_title' => 'required|string|max:255',
            'manifesto_desc' => 'required|string',
            'hero_badge' => 'required|string|max:50',
            'hero_title' => 'required|string|max:255',
            'hero_desc' => 'required|string',
            'hero_bg_image' => 'required|url',
        ]);

        Setting::updateOrCreate(['key' => 'manifesto_title'], ['value' => $request->manifesto_title]);
        Setting::updateOrCreate(['key' => 'manifesto_desc'], ['value' => $request->manifesto_desc]);
        Setting::updateOrCreate(['key' => 'hero_badge'], ['value' => $request->hero_badge]);
        Setting::updateOrCreate(['key' => 'hero_title'], ['value' => $request->hero_title]);
        Setting::updateOrCreate(['key' => 'hero_desc'], ['value' => $request->hero_desc]);
        Setting::updateOrCreate(['key' => 'hero_bg_image'], ['value' => $request->hero_bg_image]);

        return redirect()->route('admin.dashboard')->with('success', 'Site Variables updated.');
    }
}
