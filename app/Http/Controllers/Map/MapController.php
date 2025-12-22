<?php

namespace App\Http\Controllers\Map;

use App\Models\Spot;
use Inertia\Inertia;
use App\Models\Specie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function show()
    {
        $spots = auth()->user()->spots()->with('species')->get();
        $species = Specie::select('id', 'name')->get();
        return Inertia::render('map/Map', ['spots' => $spots, 'species' => $species]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|decimal:7',
            'longitude' => 'required|decimal:7',
            'name' => 'required|string|min:3|max:100',
            'license' => 'string|min:3|max:100|nullable',
            'url' => 'nullable|url',
            'species' => 'nullable|array',
            'species.*' => 'exists:species,id',
            'environement' => 'nullable|array',
            'environement.*' => 'string',
            'equipments' => 'nullable|array',
            'rules' => 'nullable|array',
            'rules.*' => 'string',
            'is_public' => 'required|boolean',
        ]);

        $spot = Spot::create([
            'user_id' => auth()->user()->id,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'name' => $validated['name'],
            'license' => $validated['license'],
            'url' => $validated['url'],
            'environement' => $validated['environement'],
            'equipments' => $validated['equipments'],
            'rules' => $validated['rules'],
            'is_public' => $validated['is_public'],
        ]);

        if (!empty($validated['species'])) {
            $spot->species()->sync($validated['species']);
        }

        return redirect()->route('map');
    }

    public function destroy(Spot $spot)
    {
        $spot->delete();

        return redirect()->route('map');
    }

    public function restore($id)
    {
        $spot = Spot::withTrashed()->findOrFail($id);

        if ($spot->trashed()) {
            $spot->restore();
        }

        return redirect()->route('map');
    }

    public function update(Request $request, Spot $spot)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required|string|min:3|max:100',
            'license' => 'string|min:3|max:100|nullable',
            'url' => 'nullable|url',
            'species' => 'nullable|array',
            'species.*' => 'exists:species,id',
            'environement' => 'nullable|array',
            'environement.*' => 'string',
            'equipments' => 'nullable|array',
            'rules' => 'nullable|array',
            'rules.*' => 'string',
            'is_public' => 'required|boolean',
        ]);

        $spot->update([
            'user_id' => auth()->user()->id,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'name' => $validated['name'],
            'license' => $validated['license'],
            'url' => $validated['url'],
            'environement' => $validated['environement'],
            'equipments' => $validated['equipments'],
            'rules' => $validated['rules'],
            'is_public' => $validated['is_public'],
        ]);

        if (!empty($validated['species'])) {
            $spot->species()->sync($validated['species']);
        } else {
            $spot->species()->detach();
        }

        return redirect()->route('map');
    }

    public function updatePosition(Request $request, Spot $spot)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $spot->update($validated);
    }
}
