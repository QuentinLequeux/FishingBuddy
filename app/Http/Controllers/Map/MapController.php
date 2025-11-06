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
            'name' => 'required|string|max:100',
            'license' => 'string|max:100|nullable',
            'url' => 'nullable|url',
            'species' => 'nullable|array',
            'species.*' => 'exists:species,id',
            'environement' => 'nullable|array',
            'equipments' => 'nullable|array',
            'rules' => 'nullable|array',
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
}
