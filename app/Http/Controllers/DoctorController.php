<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with(['user', 'clinic', 'specialties'])
            ->paginate(10);
            
        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Display the find doctor page with search and filters
     */
    public function findDoctor(Request $request)
    {
        $query = Doctor::with(['user', 'clinic', 'specialties', 'schedules']);

        // Search by doctor name
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by specialty
        if ($request->filled('specialty')) {
            $query->whereHas('specialties', function ($q) use ($request) {
                $q->where('specialty_id', $request->get('specialty'));
            });
        }

        // Filter by clinic
        if ($request->filled('clinic')) {
            $query->where('clinic_id', $request->get('clinic'));
        }

        // Filter by availability (day of week)
        if ($request->filled('day')) {
            $query->whereHas('schedules', function ($q) use ($request) {
                $q->where('day_of_week', $request->get('day'));
            });
        }

        $doctors = $query->paginate(12);
        $specialties = \App\Models\Specialty::all();
        $clinics = \App\Models\Clinic::all();

        return view('find-doctor', compact('doctors', 'specialties', 'clinics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clinics = Clinic::all();
        $specialties = Specialty::all();
        
        return view('admin.doctors.create', compact('clinics', 'specialties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'license_number' => 'required|string|unique:doctors,license_number',
            'bio' => 'nullable|string',
            'clinic_id' => 'nullable|exists:clinics,clinic_id',
            'specialties' => 'required|array',
            'specialties.*' => 'exists:specialties,specialty_id',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => 'doctor',
            'is_active' => true,
        ]);

        // Create doctor profile
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'license_number' => $validated['license_number'],
            'bio' => $validated['bio'] ?? null,
            'clinic_id' => $validated['clinic_id'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // Attach specialties
        $doctor->specialties()->attach($validated['specialties']);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'clinic', 'specialties', 'schedules', 'appointments.patient.user']);
        
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $doctor->load(['user', 'specialties']);
        $clinics = Clinic::all();
        $specialties = Specialty::all();
        
        return view('admin.doctors.edit', compact('doctor', 'clinics', 'specialties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($doctor->user_id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'license_number' => ['required', 'string', Rule::unique('doctors', 'license_number')->ignore($doctor->doctor_id, 'doctor_id')],
            'bio' => 'nullable|string',
            'clinic_id' => 'nullable|exists:clinics,clinic_id',
            'specialties' => 'required|array',
            'specialties.*' => 'exists:specialties,specialty_id',
            'is_active' => 'boolean',
        ]);

        // Update user account
        $doctor->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Update doctor profile
        $doctor->update([
            'license_number' => $validated['license_number'],
            'bio' => $validated['bio'] ?? null,
            'clinic_id' => $validated['clinic_id'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // Sync specialties
        $doctor->specialties()->sync($validated['specialties']);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        // Soft delete or deactivate the user instead of hard delete
        $doctor->user->update(['is_active' => false]);
        
        return redirect()->route('doctors.index')
            ->with('success', 'Doctor deactivated successfully.');
    }
}
