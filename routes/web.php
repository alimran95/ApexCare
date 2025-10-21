<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/search', [HomeController::class, 'search'])->name('search');

// Public Dynamic Pages
Route::get('/find-doctor', [HomeController::class, 'findDoctor'])->name('find.doctor');
Route::get('/find-clinics', [HomeController::class, 'findClinics'])->name('find.clinics');
Route::get('/health-tips', [HomeController::class, 'healthTips'])->name('health.tips');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/auth/login', [AuthController::class, 'showLogin'])->name('auth.login.show'); // For backward compatibility
    Route::post('/login', [AuthController::class, 'login']);
    
    // Registration routes
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/auth/register', [AuthController::class, 'showRegister'])->name('auth.register.show'); // For backward compatibility
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout Routes
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout'); // For backward compatibility

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::patch('/{user}/password', [UserController::class, 'updatePassword'])->name('password.update');
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Resource Routes - with role restrictions
    Route::group(['middleware' => ['role:admin']], function () {
        Route::resources([
            'doctors' => DoctorController::class,
            'patients' => PatientController::class,
            'specialties' => SpecialtyController::class,
            'clinics' => ClinicController::class,
            'doctor-schedules' => DoctorScheduleController::class,
            'reviews' => ReviewController::class,
            'payments' => PaymentController::class,
        ]);
    });
    
    // Appointments - accessible by admin and patients
    Route::resource('appointments', AppointmentController::class);
    
    // Public doctor views (for patients to book appointments)
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
    Route::get('/appointments/create/{doctor_id?}', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // Doctor Only Routes
    Route::middleware('role:doctor')->group(function () {
        Route::get('/doctor', [DashboardController::class, 'doctorDashboard'])->name('doctor.dashboard');
        
        Route::get('/doctor/schedule', [DoctorScheduleController::class, 'index'])->name('doctor.schedule');
    });

    // Patient Only Routes
    Route::middleware('role:patient')->group(function () {
        Route::get('/patient', [DashboardController::class, 'patientDashboard'])->name('patient.dashboard');
        
        Route::get('/patient/appointments', [AppointmentController::class, 'patientAppointments'])->name('patient.appointments');
        Route::get('/patient/prescriptions', [AppointmentController::class, 'patientPrescriptions'])->name('patient.prescriptions');
    });
    
    // Profile Routes
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});
