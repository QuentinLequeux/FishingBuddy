<?php

namespace App\Http\Controllers\Map;

use App\Models\Spot;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function show()
    {
        $spots = auth()->user()->spots()->with('species')->get();
        return Inertia::render('map/Map', ['spots' => $spots]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|decimal:7',
            'longitude' => 'required|decimal:7',
            'name' => 'required|string|max:255',
            'license' => 'string|max:255',
            'url' => 'nullable|url',
            'environement' => 'nullable|array',
            'equipments' => 'nullable|array',
            'rules' => 'nullable|array',
            'is_public' => 'required|boolean',
        ]);

        //$spot = Spot::create([
        Spot::create([
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

        //$spot->species()->attach($validated['species']);

        return redirect()->route('map');
    }

    public function destroy(Spot $spot)
    {
        $spot->delete();

        return redirect()->route('map');
    }
}

// TODO : Update
