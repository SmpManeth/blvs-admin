<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::with('user')->latest()->paginate(10);
        return view('pages.trips.index', compact('trips'));
    }

    public function create()
    {
        $users = \App\Models\User::pluck('name', 'id');
        return view('pages.trips.create', compact('users'));
    }

   

    public function store(Request $request)
    {


        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'location' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            $trip = Trip::create($validated);

         


            return redirect()->route('trips.index')->with('success', 'Trip created with documents.');
        } catch (\Throwable $th) {
            dd($th);
        }
    }


    public function show(Trip $trip)
    {
        return view('pages.trips.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        $users = User::pluck('name', 'id');
        $trip->load('user', 'documents');

        return view('pages.trips.edit', compact('trip', 'users'));
    }

    public function update(Request $request, Trip $trip)
    {

        $trip->load('user');
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
        ]);

        $trip->update($request->all());


        return redirect()->route('trips.index')->with('success', 'Trip updated successfully.');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return redirect()->route('trips.index')->with('success', 'Trip deleted successfully.');
    }
}
