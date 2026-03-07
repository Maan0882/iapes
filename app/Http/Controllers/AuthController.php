<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\InterviewManagement\Application;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

class AuthController extends Controller
{
    public function submitApplication(Request $request)
    {
        // 1. Define validation (This creates the $validated variable)
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email',
            'phone'                 => 'required',
            'college'               => 'required',
            'degree'                => 'required',
            'year'                  => 'required',
            'cgpa'                  => 'required',
            'domain'                => 'required',
            'duration'              => 'required',
            'duration_unit'         => 'required',
            'skills'                => 'required',
            'resume_path'           => 'nullable|file|mimes:pdf|max:2048', // 2MB Max
        ]);

        $resumePath = null;
        if ($request->hasFile('resume_path')) {
            $file = $request->file('resume_path');
            // 2. Now $validated['name'] will work perfectly
            $fileName = Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $resumePath = $file->storeAs('resumes', $fileName, 'public');
        }

        // 2. Exact code logic for generating the application code
        $date = Carbon::now()->format('dmy');
        
        $lastApplication = Application::orderBy('id', 'desc')->first();

        if ($lastApplication) {
            $lastNumber = (int) substr($lastApplication->application_code, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $sequence = str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        $applicationCode = "APP/{$date}/{$sequence}";
        $verificationToken = Str::random(64);

        // 3. Attach the generated values to the form data array
        $formData['application_code'] = $applicationCode;
        $formData['verification_token'] = $verificationToken;
        Application::updateOrCreate(
            ['email' => $request->email], 
            [
                'application_code'   => $applicationCode,      // <-- ADD THIS HERE
                'verification_token' => $verificationToken,
                'name'               => $request->name,
                'phone'              => $request->phone,
                'college'            => $request->college,
                'degree'             => $request->degree,
                'year'               => $request->year,
                'cgpa'               => $request->cgpa,
                'domain'             => $request->domain,
                'duration'           => $request->duration,
                'duration_unit'      => $request->duration_unit,
                'skills'             => $request->skills,
                'resume_path'        => $resumePath,
            ]
        );

         return response()->json([
            'status' => 'success',
            'message' => 'Application submitted successfully. We will contact you if selected.',
            'application_code' => $applicationCode
        ], 201);

    }

}


