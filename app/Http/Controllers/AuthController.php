<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\InterviewManagement\Application;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

class AuthController extends Controller
{
    public function sendVerification(Request $request)
    {
       $email = $request->email;

        // 1. Fetch the LATEST record for this email to check current state
        $latestEntry = DB::table('applications')
            ->where('email', $email)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latestEntry) {
            // If they already applied and are NOT shortlisted, block them
            if ($latestEntry->status === 'applied') {
                return response()->json([
                    'message' => 'Application already submitted. Code: ' . $latestEntry->application_code
                ], 400);
            }

            // If they are 'verified' and ready to fill the form, let them through
            if ($latestEntry->status === 'verified') {
                return response()->json([
                    'status' => 'already_verified',
                    'message' => 'Email already verified! You can proceed.'
                ], 200);
            }

            // 2. Logic for 'shortlisted': If the status is shortlisted, 
            // we skip the update logic and fall through to the INSERT section below.
            if ($latestEntry->status === 'shortlisted') {
                return $this->createNewApplicationRecord($email);
            }
            
            // 3. Logic for 'pending': Just resend the token for the current record
            if ($latestEntry->status === 'pending') {
                return $this->resendLink($latestEntry);
            }
        }

        // 4. Fallback: Create a new record for completely new emails
        return $this->createNewApplicationRecord($email);
    }

    /**
     * Creates a brand new database row for the application
     */
    private function createNewApplicationRecord($email)
    {
        $token = Str::random(32);

        // 1. Insert the new record to avoid 'application_code' unique key collisions
        DB::table('applications')->insert([
            'email' => $email,
            'verification_token' => $token,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $this->sendVerificationEmail($email, $token);
    }

    private function resendLink($user)
    {
        DB::table('applications')
        ->where('id', $user->id)
        ->update([
            'created_at' => now(), // Critical for your 10-min check
            'updated_at' => now()
        ]);
        // 2. Reuse the email helper for consistent branding
        return $this->sendVerificationEmail($user->email, $user->verification_token);
    }

    /**
     * Centered helper to handle the Blade-based email sending
     */
    private function sendVerificationEmail($email, $token)
    {
        $link = config('app.url') . "/api/verify-email?token=" . $token . "&email=" . urlencode($email);
        
        // Data passed to 'emails.application-verification'
        $data = ['link' => $link];

        Mail::send('emails.application-verification', $data, function ($message) use ($email) {
            $message->to($email)
                    ->subject('Verify your Internship Application - TechStrota');
        });

        return response()->json(['message' => 'Verification link sent successfully']);
    }

    

    public function verifyEmail(Request $request)
    {
     //   dd($request->all());
        $token = $request->token;

        // Always target the latest 'pending' record for this email/token combo
        $user = DB::table('applications')
        ->where('email', $request->email)
        ->where('verification_token', $request->token)
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid or already used token'], 400);
        }

        // ⛔ Check 10 min expiry using updated_at
        // ✅ FIX: Check expiry against created_at instead of updated_at
    if (Carbon::parse($user->created_at)->addMinutes(10)->isPast()) {
        return response()->json(['message' => 'Token expired'], 400);
    }

      // ✅ VERIFY and NULL the token immediately
        DB::table('applications')
            ->where('id', $user->id)
            ->update([
                'status' => 'verified',
                'verification_token' => $token, // Token becomes null after use
                'updated_at' => now()
            ]);
    
         return redirect(config('app.frontend_url') . "/?token=" . $token . "&email=" . urlencode($request->email));
    }



   public function submitApplication(Request $request)
    {
        // 1. Find the specific record that was just verified
        // We target the LATEST 'verified' record for this email
        $user = DB::table('applications')
            ->where('email', $request->email)
            ->where('status', 'verified')
            ->orderBy('created_at', 'desc')
            ->first();

        // Verification check
        if (!$user || ($request->filled('token') && $user->verification_token !== $request->token)) {
            return response()->json(['message' => 'Unauthorized or not verified'], 403);
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email',
            'phone'         => 'required',
            'college'       => 'required',
            'degree'        => 'required',
            'year'          => 'required',
            'cgpa'          => 'required',
            'domain'        => 'required',
            'duration'      => 'required',
            'duration_unit' => 'required',
            'skills'        => 'required',
            'resume_path'   => 'nullable|file|mimes:pdf|max:2048', 
        ]);

        $resumePath = null;
        if ($request->hasFile('resume_path')) {
            $file = $request->file('resume_path');
            $fileName = Str::slug($validated['name']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $resumePath = $file->storeAs('resumes', $fileName, 'public');
        }

        // 2. GENERATE NEW APPLICATION CODE
        $date = Carbon::now()->format('dmy');
        $prefix = "APP/{$date}/";

        $lastToday = Application::where('application_code', 'like', $prefix . '%')
            ->orderBy('application_code', 'desc')
            ->first();

        $newNumber = $lastToday ? ((int) substr($lastToday->application_code, -3)) + 1 : 1;
        $applicationCode = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // 3. CRITICAL CHANGE: Update specifically by ID, not updateOrCreate by Email
        // This allows multiple rows for the same email (old shortlisted + new applied)
        DB::table('applications')
            ->where('id', $user->id) 
            ->update([
                'application_code'   => $applicationCode,
                'status'             => 'applied',
                'verification_token' => null, // Clear the used verification token
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
                'updated_at'         => now(),
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Application submitted successfully.',
            'appCode' => $applicationCode
        ], 201);
    }
}


