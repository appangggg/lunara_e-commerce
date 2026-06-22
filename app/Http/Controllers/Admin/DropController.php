<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drop;
use Illuminate\Http\Request;

class DropController extends Controller
{
    public function create()
    {
        return view('admin.drops.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'date_label' => 'required|string|max:255',
            'price_label' => 'nullable|string|max:255',
            'status' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('drops', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        Drop::create($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Drop Scheduled.');
    }

    public function edit(Drop $drop)
    {
        return view('admin.drops.edit', compact('drop'));
    }

    public function update(Request $request, Drop $drop)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'date_label' => 'required|string|max:255',
            'price_label' => 'nullable|string|max:255',
            'status' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('drops', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        $drop->update($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Drop Updated.');
    }

    public function destroy(Drop $drop)
    {
        $drop->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Drop Cancelled.');
    }
}
