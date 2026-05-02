<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
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
            'title' => $event->event_title,
            'description' => $event->event_description,
        ]);
    }

    public function register(Request $request)
    {
        // 1. Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|digits:10',
            'institution' => 'required|string',
            'event_id' => 'required|exists:events,id',
        ]);

        try {
            // 2. Insert into the database
            DB::table('event_registrations')->insert([
                'event_id'          => $request->event_id, // Correctly mapped event_id
                'name'              => $request->name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'institution'       => $request->institution, // Included missing field
                'attendance_status' => 'registered',
                'created_at'        => now(), // Good practice to include both
                'updated_at'        => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Application submitted successfully.',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed. Please try again.',
                'debug' => $e->getMessage() // Optional: remove in production
            ], 500);
        }
    }
}