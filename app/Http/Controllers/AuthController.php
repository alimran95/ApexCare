<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function showLogin()
	{
		return view('auth.login');
	}

	public function showRegister()
	{
		return view('auth.register');
	}

	public function login(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
		]);

		if (Auth::attempt($credentials, $request->boolean('remember'))) {
			$request->session()->regenerate();
			
			// Redirect based on role
			$user = Auth::user();
			if ($user->isSuperAdmin()) {
				return redirect()->intended('/admin');
			} elseif ($user->isDoctor()) {
				return redirect()->intended('/doctor');
			} elseif ($user->isPatient()) {
				return redirect()->intended('/patient');
			}
			
			return redirect()->intended('/');
		}

		return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
	}

	public function register(Request $request)
	{
		$data = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'max:255', 'unique:users,email'],
			'password' => ['required', 'min:6', 'confirmed'],
		]);

		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
			'role' => 'patient',
		]);

		Auth::login($user);
		return redirect('/patient');
	}

	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect('/');
	}
}
