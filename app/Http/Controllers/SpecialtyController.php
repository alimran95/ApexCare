<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specialties = Specialty::withCount('doctors')
            ->paginate(10);
            
        return view('admin.specialties.index', compact('specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.specialties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name',
            'description' => 'nullable|string',
        ]);

        Specialty::create($validated);

        return redirect()->route('specialties.index')
            ->with('success', 'Specialty created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialty $specialty)
    {
        $specialty->load(['doctors.user']);
        
        return view('admin.specialties.show', compact('specialty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialty $specialty)
    {
        return view('admin.specialties.edit', compact('specialty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name,' . $specialty->specialty_id . ',specialty_id',
            'description' => 'nullable|string',
        ]);

        $specialty->update($validated);

        return redirect()->route('specialties.index')
            ->with('success', 'Specialty updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialty $specialty)
    {
        // Check if specialty has associated doctors
        if ($specialty->doctors()->count() > 0) {
            return redirect()->route('specialties.index')
                ->with('error', 'Cannot delete specialty that has associated doctors.');
        }
        
        $specialty->delete();
        
        return redirect()->route('specialties.index')
            ->with('success', 'Specialty deleted successfully.');
    }
}
