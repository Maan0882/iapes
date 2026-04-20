<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    //
    public function getLatestEvent()
    {
        // Fetch the most recent active event
        $event = Event::latest()->first();

        if (!$event) {
            return response()->json([
                'message' => 'No events found',
            ], 404);
        }

        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|digits:10',
            'institution' => 'required|string',
            'event_id' => 'required|exists:events,id',
            'source' => 'required|string',
        ]);

        // Logic to save the registration...
        // EventRegistration::create($validated);

        return response()->json(['message' => 'Registration successful!'], 201);
    }
}
