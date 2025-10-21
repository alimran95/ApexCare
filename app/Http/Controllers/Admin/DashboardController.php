<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;

class DashboardController extends Controller
{
	public function index()
	{
		$stats = [
			'users' => User::count(),
			'doctors' => Doctor::count(),
			'patients' => Patient::count(),
			'appointments_today' => Appointment::whereDate('appointment_time', now()->toDateString())->count(),
		];

		// Get recently registered doctors (users with doctor role)
		$recentDoctors = User::where('role', 'doctor')
			->with(['doctor.specialties', 'doctor.clinic'])
			->whereHas('doctor') // Only include users who have doctor profiles
			->latest()
			->take(5)
			->get();

		return view('admin.dashboard', compact('stats', 'recentDoctors'));
	}
}
