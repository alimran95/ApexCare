<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinics = Clinic::withCount('doctors')
            ->paginate(10);
            
        return view('admin.clinics.index', compact('clinics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clinics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
        ]);

        Clinic::create($validated);

        return redirect()->route('clinics.index')
            ->with('success', 'Clinic created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Clinic $clinic)
    {
        $clinic->load(['doctors.user']);
        
        return view('admin.clinics.show', compact('clinic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clinic $clinic)
    {
        return view('admin.clinics.edit', compact('clinic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
        ]);

        $clinic->update($validated);

        return redirect()->route('clinics.index')
            ->with('success', 'Clinic updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic)
    {
        // Check if clinic has associated doctors
        if ($clinic->doctors()->count() > 0) {
            return redirect()->route('clinics.index')
                ->with('error', 'Cannot delete clinic that has associated doctors.');
        }
        
        $clinic->delete();
        
        return redirect()->route('clinics.index')
            ->with('success', 'Clinic deleted successfully.');
    }
}
