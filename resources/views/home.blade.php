<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ApexCare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; color: #212529; }
        .hero { background:#f8f9fa; color:#212529; padding:60px 0; border-bottom: 1px solid #dee2e6; }
        .search-bar { background:#fff; border:1px solid #dee2e6; border-radius:10px; padding:12px; box-shadow:0 2px 8px rgba(0,0,0,.04); }
        .services .card { border:1px solid #dee2e6; background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.04); }
        .navbar { 
            background-color: #b22222 !important; 
            border-bottom: 1px solid #9b1c1c;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand { font-weight:700; color: #ffffff !important; }
        .nav-link { color: rgba(255,255,255,0.9) !important; }
        .nav-link:hover, .nav-link.active { color: #ffffff !important; font-weight: 500; }
        .footer { background:#b22222; color:rgba(255,255,255,0.9); padding:30px 0; }
        .footer h6 { color:#fff; margin-bottom:1.25rem; }
        .btn-primary { background-color: #b22222; border-color: #9b1c1c; }
        .btn-primary:hover { background-color: #9b1c1c; border-color: #8B0000; }
        .btn-outline-primary { color: #b22222; border-color: #b22222; }
        .btn-outline-primary:hover { background-color: #b22222; border-color: #b22222; }
        .footer a { color: rgba(255,255,255,0.9); text-decoration: none; }
        .footer a:hover { color: #fff; text-decoration: underline; }
        #searchResults {
            position: absolute;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            display: none;
        }
        .search-result-item {
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #f1f1f1;
            cursor: pointer;
        }
        .search-result-item:hover {
            background-color: #f8f9fa;
        }
        .search-loading {
            padding: 1rem;
            text-align: center;
            color: #6c757d;
        }
        .avatar-lg {
            width: 60px;
            height: 60px;
        }
        .avatar-initial {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }
        .clinic-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .carousel-item {
            min-height: 400px;
        }
        .carousel-control-prev, .carousel-control-next {
            width: 5%;
        }
        .carousel-indicators {
            bottom: -50px;
        }
        .carousel-indicators button {
            background-color: #b22222;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_apexcare.png') }}" alt="ApexCare" class="me-2" style="height:32px;">
            <span>ApexCare</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}">Home</a></li> -->
                <li class="nav-item"><a class="nav-link" href="#find-doctor" >Find Doctor</a></li>
                <li class="nav-item"><a class="nav-link" href="#find-clinics">Find Clinics</a></li>
                <li class="nav-item"><a class="nav-link" href="#health-tips">Health Tips</a></li>
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
                    <a href="{{ route('auth.register.show') }}" class="btn btn-sm btn-primary">Register</a>
                @endauth
            </div>
        </div>
    </div>
    </nav>

<!-- <section class="hero">
    <div class="container">
        <div class="text-center">
            <h2 class="fw-bold">Your Health, Our Priority</h2>
            <p class="mb-4">Find the best doctors & clinics near you.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <form id="searchForm">
                    @csrf
                    <div class="search-bar">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-2">
                                <select name="type" id="searchType" class="form-select">
                                    <option value="doctor">Doctor</option>
                                    <option value="hospital">Clinic</option>
                                </select>
                            </div>
                            <div class="col-md-4 position-relative">
                                <div class="input-group">
                                    <span class="input-group-text bi bi-search"></span>
                                    <input type="text" name="query" id="searchQuery" class="form-control" placeholder="Search doctors, hospitals..." autocomplete="off">
                                </div>
                                <div id="searchResults"></div>
                            </div>
                            <div class="col-md-2">
                                <select name="specialty" id="searchSpecialty" class="form-select">
                                    <option value="">All Specialties</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="city" id="searchCity" class="form-select">
                                    <option value="">All Cities</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}">{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary" id="searchButton">
                                    <span class="spinner-border spinner-border-sm d-none" id="searchSpinner" role="status" aria-hidden="true"></span>
                                    <span id="searchButtonText">Search</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section> -->

<section class="py-5 bg-white">
    <div class="container">
        <div id="carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            
            <div class="carousel-inner">
                <!-- Slide 1: Exceptional Care -->
                <div class="carousel-item active">
                    <div class="row align-items-center p-4">
                        <div class="col-md-6">
                            <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 class="img-fluid rounded-3" 
                                 alt="Healthcare professionals providing exceptional care">
                        </div>
                        <div class="col-md-6">
                            <div class="p-4">
                                <h3 class="fw-bold mb-3 text-primary">Exceptional Care, Every Patient</h3>
                                <p class="mb-3 text-muted">We provide top-quality healthcare services with a team of dedicated professionals committed to your health and wellness. Our state-of-the-art facilities ensure you receive the best medical care.</p>
                                <a class="btn btn-primary" href="#find-doctor">Find a Doctor</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 2: Advanced Technology -->
                <div class="carousel-item">
                    <div class="row align-items-center p-4">
                        <div class="col-md-6">
                            <img src="../resources/images/advancedmedtech.jpg"
                                 class="img-fluid rounded-3" 
                                 alt="Modern medical equipment and technology">
                        </div>
                        <div class="col-md-6">
                            <div class="p-4">
                                <h3 class="fw-bold mb-3 text-primary">Advanced Medical Technology</h3>
                                <p class="mb-3 text-muted">Our clinics are equipped with cutting-edge medical technology and diagnostic equipment to provide accurate diagnoses and effective treatments for all your healthcare needs.</p>
                                <a class="btn btn-primary" href="#find-clinics">Find Clinics</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 3: Trusted Specialists -->
                <div class="carousel-item">
                    <div class="row align-items-center p-4">
                        <div class="col-md-6">
                            <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 class="img-fluid rounded-3" 
                                 alt="Experienced doctors and medical specialists">
                        </div>
                        <div class="col-md-6">
                            <div class="p-4">
                                <h3 class="fw-bold mb-3 text-primary">Trusted Medical Specialists</h3>
                                <p class="mb-3 text-muted">Connect with board-certified doctors and specialists across various medical fields. Our verified healthcare professionals are committed to providing personalized care tailored to your needs.</p>
                                <a class="btn btn-primary" href="#find-doctor">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 4: 24/7 Emergency Care -->
                <div class="carousel-item">
                    <div class="row align-items-center p-4">
                        <div class="col-md-6">
                            <img src="https://images.unsplash.com/photo-1551190822-a9333d879b1f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 class="img-fluid rounded-3" 
                                 alt="Emergency medical services and ambulance">
                        </div>
                        <div class="col-md-6">
                            <div class="p-4">
                                <h3 class="fw-bold mb-3 text-primary">24/7 Emergency Services</h3>
                                <p class="mb-3 text-muted">Round-the-clock emergency medical services with rapid response ambulance and critical care units. We're here when you need us most, ensuring immediate medical attention.</p>
                                <a class="btn btn-primary" href="#health-tips">Health Tips</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<!-- Find Doctor Section -->
<section class="py-5 bg-white" id="find-doctor">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary">Find the Right Doctor</h2>
            <p class="lead text-muted">Connect with qualified healthcare professionals near you</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('find.doctor') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search by Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control" 
                                       placeholder="Doctor name..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="specialty" class="form-label">Specialty</label>
                            <select name="specialty" id="specialty" class="form-select">
                                <option value="">All Specialties</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->specialty_id ?? $specialty->id }}" 
                                        {{ request('specialty') == ($specialty->specialty_id ?? $specialty->id) ? 'selected' : '' }}>
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="city" class="form-label">City</label>
                            <select name="city" id="city" class="form-select">
                                <option value="">All Cities</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Doctors Grid -->
        <div class="row">
            @if($doctors->count() > 0)
                @foreach($doctors as $doctor)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-lg me-3">
                                        <div class="avatar-initial bg-primary rounded-circle fs-3">
                                            {{ substr($doctor->user->name ?? $doctor->name ?? 'D', 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1">Dr. {{ $doctor->user->name ?? $doctor->name ?? 'Unknown' }}</h5>
                                        <div class="text-muted small">
                                            @if($doctor->specialties && $doctor->specialties->count() > 0)
                                                {{ $doctor->specialties->pluck('name')->join(', ') }}
                                            @else
                                                General Medicine
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                @if($doctor->bio)
                                    <p class="card-text text-muted small">{{ Str::limit($doctor->bio, 100) }}</p>
                                @endif
                                
                                <div class="mb-3">
                                    @if($doctor->city)
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $doctor->city }}
                                        </small>
                                    @endif
                                    @if($doctor->license_number)
                                        <br><small class="text-muted">
                                            <i class="fas fa-certificate me-1"></i>License: {{ $doctor->license_number }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('doctors.show', $doctor->doctor_id ?? $doctor->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Profile
                                    </a>
                                    @auth
                                        <a href="{{ route('appointments.create', ['doctor_id' => $doctor->doctor_id ?? $doctor->id]) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-calendar-plus me-1"></i>Book Appointment
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-sign-in-alt me-1"></i>Login to Book
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-user-md fa-4x text-muted mb-3"></i>
                        <h4>No doctors found</h4>
                        <p class="text-muted">Try adjusting your search criteria to find more results.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('find.doctor') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>View All Doctors
            </a>
        </div>
    </div>
</section>

<!-- Find Clinics Section -->
<section class="py-5 bg-light" id="find-clinics">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary">Find Medical Clinics</h2>
            <p class="lead text-muted">Discover trusted healthcare facilities in your area</p>
        </div>

        <!-- Clinics Grid -->
        <div class="row">
            @if($clinics->count() > 0)
                @foreach($clinics as $clinic)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="clinic-icon me-3">
                                        <i class="fas fa-hospital fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1">{{ $clinic->name }}</h5>
                                        <div class="text-muted small">Medical Facility</div>
                                    </div>
                                </div>
                                
                                @if($clinic->address)
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $clinic->address }}
                                        </small>
                                    </div>
                                @endif
                                
                                @if($clinic->contact_phone)
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>{{ $clinic->contact_phone }}
                                        </small>
                                    </div>
                                @endif
                                
                                <!-- Services -->
                                <div class="mb-3">
                                    <div class="small text-muted">
                                        <strong>Services:</strong>
                                        <span class="badge bg-light text-dark me-1">General Medicine</span>
                                        <span class="badge bg-light text-dark me-1">Emergency Care</span>
                                        <span class="badge bg-light text-dark">Diagnostics</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('clinics.show', $clinic->clinic_id ?? $clinic->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                    @auth
                                        <a href="{{ route('appointments.create', ['clinic_id' => $clinic->clinic_id ?? $clinic->id]) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-calendar-plus me-1"></i>Book Appointment
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-sign-in-alt me-1"></i>Login to Book
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-hospital fa-4x text-muted mb-3"></i>
                        <h4>No clinics found</h4>
                        <p class="text-muted">Try adjusting your search criteria to find more results.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('find.clinics') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>View All Clinics
            </a>
        </div>

        <!-- Clinic Benefits Section -->
        <div class="row mt-5">
            <div class="col-md-8 mx-auto text-center">
                <h3 class="fw-bold mb-3">Why Choose Our Partner Clinics?</h3>
                <div class="row g-4 mt-3">
                    <div class="col-md-4">
                        <div class="text-primary mb-2">
                            <i class="fas fa-award fa-2x"></i>
                        </div>
                        <h6>Certified Facilities</h6>
                        <p class="small text-muted">All partner clinics meet strict quality and safety standards.</p>
                    </div>
                    <div class="col-md-4">
                        <div class="text-primary mb-2">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h6>Convenient Hours</h6>
                        <p class="small text-muted">Extended hours and emergency services available.</p>
                    </div>
                    <div class="col-md-4">
                        <div class="text-primary mb-2">
                            <i class="fas fa-stethoscope fa-2x"></i>
                        </div>
                        <h6>Expert Care</h6>
                        <p class="small text-muted">Experienced medical professionals and modern equipment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Health Tips Section -->
<section class="py-5 bg-white" id="health-tips">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary">Health Tips & Wellness</h2>
            <p class="lead text-muted">Expert advice for a healthier, happier life</p>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="btn-group" role="group" aria-label="Category filter">
                            <input type="radio" class="btn-check" name="category" id="all" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="all">All Tips</label>

                            <input type="radio" class="btn-check" name="category" id="general" autocomplete="off">
                            <label class="btn btn-outline-primary" for="general">General Health</label>

                            <input type="radio" class="btn-check" name="category" id="fitness" autocomplete="off">
                            <label class="btn btn-outline-primary" for="fitness">Fitness</label>

                            <input type="radio" class="btn-check" name="category" id="nutrition" autocomplete="off">
                            <label class="btn btn-outline-primary" for="nutrition">Nutrition</label>

                            <input type="radio" class="btn-check" name="category" id="mental" autocomplete="off">
                            <label class="btn btn-outline-primary" for="mental">Mental Health</label>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchTips" class="form-control" placeholder="Search tips...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Health Tips Grid -->
        <div class="row" id="tipsContainer">
            @foreach(array_slice($healthTips, 0, 6) as $tip)
                <div class="col-lg-4 col-md-6 mb-4 tip-card" data-category="{{ strtolower(str_replace(' ', '', $tip['category'])) }}">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $tip['image'] }}" class="card-img-top" alt="{{ $tip['title'] }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary">{{ $tip['category'] }}</span>
                                <small class="text-muted">{{ $tip['created_at']->diffForHumans() }}</small>
                            </div>
                            <h5 class="card-title">{{ $tip['title'] }}</h5>
                            <p class="card-text text-muted">{{ $tip['content'] }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-heart text-danger me-1"></i>Helpful tip
                                </small>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm me-1" onclick="shareTip('{{ $tip['title'] }}')">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-sm" onclick="likeTip(this)">
                                        <i class="fas fa-thumbs-up"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('health.tips') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>View All Health Tips
            </a>
        </div>

        <!-- Newsletter Subscription -->
        <div class="bg-primary text-white rounded-3 p-4 mt-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="fw-bold mb-2">Stay Updated with Health Tips</h4>
                    <p class="mb-0">Subscribe to our newsletter and get weekly health tips delivered to your inbox.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0">
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Enter your email" required>
                        <button type="submit" class="btn btn-light">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Health Facts -->
        <div class="mt-5">
            <h3 class="text-center mb-4">Quick Health Facts</h3>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center p-4 bg-light rounded-3">
                        <div class="display-4 text-primary mb-2">🚶‍♂️</div>
                        <h6>Daily Steps</h6>
                        <p class="small text-muted mb-0">Aim for 10,000 steps per day for optimal health</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-4 bg-light rounded-3">
                        <div class="display-4 text-primary mb-2">💧</div>
                        <h6>Water Intake</h6>
                        <p class="small text-muted mb-0">Drink 8-10 glasses of water daily</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-4 bg-light rounded-3">
                        <div class="display-4 text-primary mb-2">😴</div>
                        <h6>Sleep Hours</h6>
                        <p class="small text-muted mb-0">7-9 hours of quality sleep each night</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-4 bg-light rounded-3">
                        <div class="display-4 text-primary mb-2">🥗</div>
                        <h6>Fruits & Veggies</h6>
                        <p class="small text-muted mb-0">5 servings of fruits and vegetables daily</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services py-5">
    <div class="container">
        <h4 class="text-center mb-4">Our Services</h4>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card p-4 text-center h-100">
                    <div class="fs-1 mb-2">👨‍⚕️</div>
                    <h6 class="fw-semibold">Find a Doctor</h6>
                    <p class="mb-0">Easily search and connect with top-rated medical professionals.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 text-center h-100">
                    <div class="fs-1 mb-2">💬</div>
                    <h6 class="fw-semibold">Health Consultancy</h6>
                    <p class="mb-0">Get expert advice and consultations for your health concerns.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 text-center h-100">
                    <div class="fs-1 mb-2">🚑</div>
                    <h6 class="fw-semibold">Emergency Care</h6>
                    <p class="mb-0">Access to 24/7 ambulance and emergency medical services.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <h6>ApexCare</h6>
                <p class="mb-0">Providing the best healthcare solutions for you and your family.</p>
            </div>
            <div class="col-md-3">
                <h6>Quick Links</h6>
                <ul class="list-unstyled small mb-0">
                    <li><a class="link-light text-decoration-none" href="#">Home</a></li>
                    <li><a class="link-light text-decoration-none" href="#find-doctor">Find Doctor</a></li>
                    <li><a class="link-light text-decoration-none" href="#find-clinics">Find Clinics</a></li>
                    <li><a class="link-light text-decoration-none" href="#health-tips">Health Tips</a></li>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const searchQuery = document.getElementById('searchQuery');
        const searchResults = document.getElementById('searchResults');
        const searchButton = document.getElementById('searchButton');
        const searchButtonText = document.getElementById('searchButtonText');
        const searchSpinner = document.getElementById('searchSpinner');
        let searchTimeout;

        // Handle search form submission
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });

        // Handle search input with debounce
        searchQuery.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 500);
        });

        // Handle search when filters change
        document.getElementById('searchType').addEventListener('change', performSearch);
        document.getElementById('searchSpecialty').addEventListener('change', performSearch);
        document.getElementById('searchCity').addEventListener('change', performSearch);

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchResults.contains(e.target) && e.target !== searchQuery) {
                searchResults.style.display = 'none';
            }
        });

        // Function to perform the search
        function performSearch() {
            const query = searchQuery.value.trim();
            const type = document.getElementById('searchType').value;
            const specialty = document.getElementById('searchSpecialty').value;
            const city = document.getElementById('searchCity').value;

            // Don't search if query is too short and no filters are applied
            if (query.length < 2 && !specialty && !city) {
                searchResults.style.display = 'none';
                return;
            }

            // Show loading state
            searchButton.disabled = true;
            searchSpinner.classList.remove('d-none');
            searchButtonText.textContent = 'Searching...';
            searchResults.innerHTML = '<div class="search-loading">Searching...</div>';
            searchResults.style.display = 'block';

            // Prepare form data
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('type', type);
            formData.append('query', query);
            if (specialty) formData.append('specialty', specialty);
            if (city) formData.append('city', city);

            // Send AJAX request
            fetch('{{ route("search") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data);
            })
            .catch(error => {
                console.error('Error:', error);
                searchResults.innerHTML = '<div class="search-loading">Error loading results. Please try again.</div>';
            })
            .finally(() => {
                // Reset button state
                searchButton.disabled = false;
                searchSpinner.classList.add('d-none');
                searchButtonText.textContent = 'Search';
            });
        }

        // Function to display search results
        function displaySearchResults(data) {
            const results = data.results || [];
            const type = data.type || 'doctor';
            
            if (results.length === 0) {
                searchResults.innerHTML = '<div class="search-loading">No results found</div>';
                return;
            }

            let html = '';
            results.forEach(item => {
                if (type === 'doctor') {
                    html += `
                        <div class="search-result-item" data-id="${item.id}">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <img src="${item.profile_photo_url || 'https://via.placeholder.com/40'}" 
                                         alt="${item.name}" 
                                         class="rounded-circle" 
                                         width="40" 
                                         height="40">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Dr. ${item.name}</div>
                                    <small class="text-muted">${item.specialties?.[0]?.name || 'General Physician'}</small>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="search-result-item" data-id="${item.id}">
                            <div class="fw-semibold">${item.name}</div>
                            <small class="text-muted">${item.address || ''}</small>
                        </div>
                    `;
                }
            });

            searchResults.innerHTML = html;

            // Add click handlers to result items
            document.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    if (type === 'doctor') {
                        window.location.href = `/doctors/${id}`;
                    } else {
                        window.location.href = `/hospitals/${id}`;
                    }
                });
            });
        }
    });

    // Health Tips functionality
    // Category filtering
    const categoryButtons = document.querySelectorAll('input[name="category"]');
    const tipCards = document.querySelectorAll('.tip-card');
    
    categoryButtons.forEach(button => {
        button.addEventListener('change', function() {
            const selectedCategory = this.id;
            
            tipCards.forEach(card => {
                if (selectedCategory === 'all' || card.dataset.category.includes(selectedCategory)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Search functionality for health tips
    const searchInput = document.getElementById('searchTips');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            tipCards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const content = card.querySelector('.card-text').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
});

// Health tips utility functions
function shareTip(title) {
    if (navigator.share) {
        navigator.share({
            title: 'Health Tip: ' + title,
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }
}

function likeTip(button) {
    const icon = button.querySelector('i');
    if (icon.classList.contains('fas')) {
        icon.classList.remove('fas');
        icon.classList.add('far');
        button.classList.remove('btn-outline-success');
        button.classList.add('btn-outline-secondary');
    } else {
        icon.classList.remove('far');
        icon.classList.add('fas');
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-outline-success');
    }
}
</script>
</body>
</html>


