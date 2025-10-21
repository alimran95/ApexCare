<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - ApexCare</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white" style="width: 250px; min-height: 100vh;">
            <div class="p-3">
                <h4 class="text-center mb-4">ApexCare</h4>
                <hr>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    
                    @if(auth()->user()->isAdmin())
                    <!-- Admin Navigation -->
                    <li class="nav-item mt-3">
                        <span class="nav-link text-uppercase small fw-bold text-muted">Admin</span>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link text-white {{ request()->routeIs('users.*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-users-cog me-2"></i> User Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('doctors.index') }}" class="nav-link text-white {{ request()->routeIs('doctors.*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-user-md me-2"></i> Manage Doctors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('patients.index') }}" class="nav-link text-white {{ request()->routeIs('patients.*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-user-injured me-2"></i> Manage Patients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('appointments.index') }}" class="nav-link text-white {{ request()->routeIs('appointments.*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-calendar-check me-2"></i> All Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('specialties.index') }}" class="nav-link text-white {{ request()->routeIs('specialties.*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-star me-2"></i> Specialties
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('clinics.index') }}" class="nav-link text-white {{ request()->routeIs('clinics.*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-hospital me-2"></i> Clinics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reviews.index') }}" class="nav-link text-white {{ request()->routeIs('reviews.*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-star-half-alt me-2"></i> Reviews
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('payments.index') }}" class="nav-link text-white {{ request()->routeIs('payments.*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-credit-card me-2"></i> Payments
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->isDoctor())
                        <li class="nav-item">
                            <a href="{{ route('appointments.index') }}" class="nav-link text-white {{ request()->routeIs('appointments.*') ? 'active bg-primary rounded' : '' }}">
                                <i class="fas fa-calendar-check me-2"></i> Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('doctor.schedule') }}" class="nav-link text-white {{ request()->routeIs('doctor.schedule') ? 'active bg-primary rounded' : '' }}">
                                <i class="fas fa-clock me-2"></i> My Schedule
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->isPatient())
                        <li class="nav-item">
                            <a href="{{ route('patient.appointments') }}" class="nav-link text-white {{ request()->routeIs('patient.appointments') ? 'active bg-primary rounded' : '' }}">
                                <i class="fas fa-calendar-check me-2"></i> My Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('patient.prescriptions') }}" class="nav-link text-white {{ request()->routeIs('patient.prescriptions') ? 'active bg-primary rounded' : '' }}">
                                <i class="fas fa-prescription me-2"></i> Prescriptions
                            </a>
                        </li>
                    @endif
                    <li class="nav-item mt-4">
                        <a href="{{ route('profile.edit') }}" class="nav-link text-white">
                            <i class="fas fa-user-cog me-2"></i> Profile Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit" class="nav-link text-white bg-transparent border-0 w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
