<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->except(['edit', 'update']);
    }

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Only allow admins to edit other users, or users to edit their own profile
        if (auth()->user()->isAdmin() || auth()->id() === $user->id) {
            return view('users.edit', compact('user'));
        }
        
        abort(403, 'Unauthorized action.');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Only allow admins to update other users, or users to update their own profile
        if (!auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Only allow admins to change roles
        if (auth()->user()->isAdmin()) {
            $validated['role'] = $request->validate([
                'role' => ['required', 'string', Rule::in(['admin', 'doctor', 'patient'])]
            ])['role'];
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request, User $user)
    {
        // Only allow users to update their own password unless admin
        if (!auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Toggle user status (active/inactive).
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        return back()->with('success', 'User status updated successfully.');
    }
    
    /**
     * Show the form for editing the authenticated user's profile.
     */
    public function profile()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }
    
    /**
     * Update the authenticated user's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);
        
        // Update basic info
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $user->phone,
        ]);
        
        // Update password if provided
        if (!empty($validated['new_password'])) {
            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);
        }
        
        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
