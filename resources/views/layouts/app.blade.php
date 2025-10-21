<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ApexCare - Healthcare Excellence')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        footer {
            background-color: #2c3e50;
            color: white;
            margin-top: auto;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-heartbeat me-2"></i>ApexCare
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('find.doctor') ? 'active' : '' }}" href="{{ route('find.doctor') }}">Find Doctor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('find.clinics') ? 'active' : '' }}" href="{{ route('find.clinics') }}">Find Clinics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('health.tips') ? 'active' : '' }}" href="{{ route('health.tips') }}">Health Tips</a>
                    </li>
                </ul>
                
                <div class="navbar-nav">
                    @auth
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                @if(Auth::user()->isSuperAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Super Admin</a></li>
                                @elseif(Auth::user()->isDoctor())
                                    <li><a class="dropdown-item" href="{{ route('doctor.dashboard') }}">Doctor Dashboard</a></li>
                                @elseif(Auth::user()->isPatient())
                                    <li><a class="dropdown-item" href="{{ route('patient.dashboard') }}">Patient Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('patient.appointments') }}">My Appointments</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('auth.logout') }}" class="px-3">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('auth.login.show') }}" class="btn btn-sm btn-primary me-2">Login</a>
                        <a href="{{ route('auth.register.show') }}" class="btn btn-sm btn-outline-primary">Register</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-4 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <h6>ApexCare</h6>
                    <p class="mb-0 small">Providing the best healthcare solutions for you and your family.</p>
                </div>
                <div class="col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled small mb-0">
                        <li><a class="link-light text-decoration-none" href="{{ route('find.doctor') }}">Find Doctor</a></li>
                        <li><a class="link-light text-decoration-none" href="{{ route('find.clinics') }}">Find Clinics</a></li>
                        <li><a class="link-light text-decoration-none" href="{{ route('health.tips') }}">Health Tips</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Support</h6>
                    <ul class="list-unstyled small mb-0">
                        <li>FAQ</li>
                        <li>Help Center</li>
                        <li>Privacy Policy</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Contact Us</h6>
                    <ul class="list-unstyled small mb-0">
                        <li>House 21, Road 1, Block A, Dhaka 1219</li>
                        <li>+880 19 234 5678</li>
                        <li>contact@apexcare.com.bd</li>
                    </ul>
                </div>
            </div>
            <div class="text-center small mt-4">© {{ date('Y') }} ApexCare. All rights reserved.</div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>