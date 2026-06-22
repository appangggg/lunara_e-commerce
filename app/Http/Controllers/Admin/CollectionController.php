<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function create()
    {
        return view('admin.collections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('collections', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        Collection::create($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Collection Created.');
    }

    public function edit(Collection $collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('collections', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        $collection->update($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Collection Updated.');
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Collection Deleted.');
    }
}
