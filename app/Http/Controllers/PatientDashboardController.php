<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = Patient::with(['appointments.doctor.user'])
            ->where('user_id', $user->id)
            ->first();

        $upcomingAppointments = collect();
        $pastAppointments = collect();
        $payments = collect();

        if ($patient) {
            $upcomingAppointments = Appointment::with(['doctor.user'])
                ->where('patient_id', $patient->patient_id)
                ->where('appointment_time', '>=', now())
                ->orderBy('appointment_time')
                ->limit(5)
                ->get();

            $pastAppointments = Appointment::with(['doctor.user'])
                ->where('patient_id', $patient->patient_id)
                ->where('appointment_time', '<', now())
                ->orderByDesc('appointment_time')
                ->limit(5)
                ->get();

            $payments = Payment::whereIn('appointment_id', Appointment::where('patient_id', $patient->patient_id)->pluck('appointment_id'))
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        }

        $vitals = [
            'weight' => null,
            'height' => null,
            'bmi' => null,
            'bp' => null,
            'hr' => null,
            'sugar' => null,
        ];

        return view('patient.dashboard', compact('user', 'patient', 'upcomingAppointments', 'pastAppointments', 'payments', 'vitals'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Update user information
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Get or create patient record
            $patient = Patient::where('user_id', $user->id)->first();
            
            if ($patient) {
                // Update existing patient record
                $patient->update([
                    'blood_group' => $request->blood_group,
                    'gender' => $request->gender,
                    'address' => $request->address,
                ]);
            } else {
                // Create new patient record if it doesn't exist
                Patient::create([
                    'user_id' => $user->id,
                    'blood_group' => $request->blood_group,
                    'gender' => $request->gender,
                    'address' => $request->address,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Patient information updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update patient information. Please try again.'
            ], 500);
        }
    }
}
