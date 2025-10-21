<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user', 'clinic'])
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);
            
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $doctor_id = null)
    {
        $user = auth()->user();
        
        // Get the doctor if doctor_id is provided
        $selectedDoctor = $doctor_id ? Doctor::with(['user', 'specialties', 'clinic'])->findOrFail($doctor_id) : null;
        
        if ($user->isAdmin()) {
            // Admin can create appointments for any patient
            $doctors = Doctor::with('user')->get();
            $patients = Patient::with('user')->get();
            $clinics = Clinic::all();
            
            return view('admin.appointments.create', compact('doctors', 'patients', 'clinics', 'selectedDoctor'));
        } elseif ($user->isPatient()) {
            // Patient can only create appointments for themselves
            $patient = $user->patient;
            
            // Create patient profile if it doesn't exist
            if (!$patient) {
                $patient = Patient::create([
                    'user_id' => $user->id,
                    'dob' => null,
                    'gender' => null,
                    'blood_group' => null,
                    'address' => null,
                    'medical_history' => null,
                ]);
            }
            
            $doctors = Doctor::with(['user', 'specialties', 'clinic'])->get();
            
            return view('appointments.create', compact('doctors', 'patient', 'selectedDoctor'));
        }
        
        abort(403, 'Unauthorized to create appointments.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin can create appointments for any patient
            $validated = $request->validate([
                'patient_id' => 'required|exists:patients,patient_id',
                'doctor_id' => 'required|exists:doctors,doctor_id',
                'clinic_id' => 'nullable|exists:clinics,clinic_id',
                'appointment_time' => 'required|date|after:now',
                'appointment_type' => 'required|in:consultation,follow_up,emergency',
                'notes' => 'nullable|string',
                'status' => 'required|in:booked,completed,cancelled',
            ]);
        } elseif ($user->isPatient()) {
            // Patient can only create appointments for themselves
            $validated = $request->validate([
                'doctor_id' => 'required|exists:doctors,doctor_id',
                'clinic_id' => 'nullable|exists:clinics,clinic_id',
                'appointment_time' => 'required|date|after:now',
                'appointment_type' => 'required|in:consultation,follow_up,emergency',
                'notes' => 'nullable|string',
            ]);
            
            // Set patient_id to current user's patient profile
            $patient = $user->patient;
            if (!$patient) {
                $patient = Patient::create([
                    'user_id' => $user->id,
                    'dob' => null,
                    'gender' => null,
                    'blood_group' => null,
                    'address' => null,
                    'medical_history' => null,
                ]);
            }
            
            $validated['patient_id'] = $patient->patient_id;
            $validated['status'] = 'booked'; // Patient appointments start as booked
        } else {
            abort(403, 'Unauthorized to create appointments.');
        }

        Appointment::create($validated);
        
        if ($user->isAdmin()) {
            return redirect()->route('appointments.index')
                ->with('success', 'Appointment created successfully.');
        } else {
            return redirect()->route('patient.appointments')
                ->with('success', 'Appointment booked successfully! Your appointment has been confirmed.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.user', 'clinic']);
        
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.user']);
        $doctors = Doctor::with('user')->get();
        $patients = Patient::with('user')->get();
        $clinics = Clinic::all();
        
        return view('admin.appointments.edit', compact('appointment', 'doctors', 'patients', 'clinics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'doctor_id' => 'required|exists:doctors,doctor_id',
            'clinic_id' => 'nullable|exists:clinics,clinic_id',
            'appointment_time' => 'required|date',
            'appointment_type' => 'required|in:consultation,follow_up,emergency',
            'notes' => 'nullable|string',
            'status' => 'required|in:booked,completed,cancelled',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }
    
    /**
     * Display a listing of the authenticated patient's appointments.
     */
    public function patientAppointments()
    {
        $appointments = auth()->user()->patient->appointments()
            ->with(['doctor', 'clinic'])
            ->latest()
            ->paginate(10);
            
        return view('appointments.patient-index', compact('appointments'));
    }
    
    /**
     * Display a listing of the authenticated patient's prescriptions.
     */
    public function patientPrescriptions()
    {
        $prescriptions = auth()->user()->patient->prescriptions()
            ->with(['appointment', 'doctor'])
            ->latest()
            ->paginate(10);
            
        return view('prescriptions.index', compact('prescriptions'));
    }
}
