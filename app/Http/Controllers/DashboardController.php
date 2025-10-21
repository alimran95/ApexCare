<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isDoctor()) {
            return $this->doctorDashboard();
        } elseif ($user->isPatient()) {
            return $this->patientDashboard();
        }

        // Default redirect if no role matches
        return redirect()->route('home');
    }

    /**
     * Show the doctor dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function doctorDashboard()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        // If user doesn't have a doctor profile yet, create one or show empty dashboard
        if (!$doctor) {
            // Create a basic doctor profile
            $doctor = \App\Models\Doctor::create([
                'user_id' => $user->id,
                'license_number' => null,
                'bio' => null,
                'clinic_id' => null,
            ]);
        }
        
        $appointments = $doctor->appointments()
            ->with('patient.user')
            ->whereDate('appointment_time', '>=', now())
            ->orderBy('appointment_time')
            ->take(5)
            ->get();
            
        return view('dashboard.doctor', compact('appointments'));
    }

    /**
     * Show the patient dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function patientDashboard()
    {
        $patient = Auth::user()->patient;
        
        // If user doesn't have a patient profile yet, create one or show empty dashboard
        if (!$patient) {
            // Create a basic patient profile
            $patient = \App\Models\Patient::create([
                'user_id' => Auth::user()->id,
                'dob' => null,
                'gender' => null,
                'blood_group' => null,
                'address' => null,
                'medical_history' => null,
            ]);
        }
        
        $appointments = $patient->appointments()
            ->with('doctor.user')
            ->whereDate('appointment_time', '>=', now())
            ->orderBy('appointment_time')
            ->take(5)
            ->get();
            
        return view('dashboard.patient', compact('appointments', 'patient'));
    }
}
