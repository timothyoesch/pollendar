<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\PollenForecast;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('App/Entry/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'date' => 'required|date',
            "symptomSeverity" => 'required|min:1|max:5',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'string',
            "medicationTaken" => 'boolean',
            "medicationInformation" => 'nullable|string',
            'notes' => 'nullable|string',
            "pollenForecastLocation" => "required|array",
            "pollenForecastLocation.latitude" => "required|numeric|min:-90|max:90",
            "pollenForecastLocation.longitude" => "required|numeric|min:-180|max:180"
        ]);

        $entry = Entry::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $validated['date']
            ],
            [
                'symptoms' => $validated['symptoms'],
                'notes' => $validated['notes'],
                'symptoms_severity' => $validated['symptomSeverity'],
                'medication_taken' => $validated['medicationTaken'],
                'medication_information' => $validated['medicationInformation'] ?? null,
            ]
        );

        // Check if pollen forecast for lat and long exists, if not create it
        $forecast = PollenForecast::getForLatAndLong(
            $validated['date'],
            $validated['pollenForecastLocation']['latitude'],
            $validated['pollenForecastLocation']['longitude']
        );

        $entry->pollenForecast()->associate($forecast);
        $entry->save();
        return redirect()->route('app.dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Entry $entry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entry $entry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entry $entry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entry $entry)
    {
        //
    }
}
