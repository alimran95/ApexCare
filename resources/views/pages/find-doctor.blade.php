@extends('layouts.app')

@section('title', 'Find Doctor - ApexCare')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">Find the Right Doctor</h1>
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
                                <option value="{{ $specialty->specialty_id }}" 
                                    {{ request('specialty') == $specialty->specialty_id ? 'selected' : '' }}>
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

    <!-- Results Section -->
    <div class="row">
        @if($doctors->count() > 0)
            @foreach($doctors as $doctor)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-lg me-3">
                                    <div class="avatar-initial bg-primary rounded-circle fs-3">
                                        {{ substr($doctor->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Dr. {{ $doctor->user->name }}</h5>
                                    <div class="text-muted small">
                                        @if($doctor->specialties->count() > 0)
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
                                <a href="{{ route('doctors.show', $doctor->doctor_id) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Profile
                                </a>
                                @auth
                                    @if(Auth::user()->isPatient())
                                        <a href="{{ route('appointments.create', ['doctor_id' => $doctor->doctor_id]) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-calendar-plus me-1"></i>Book Appointment
                                        </a>
                                    @elseif(Auth::user()->isAdmin())
                                        <a href="{{ route('doctors.edit', $doctor->doctor_id) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit me-1"></i>Manage Doctor
                                        </a>
                                    @endif
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
                    <a href="{{ route('find.doctor') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>Clear Filters
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($doctors->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $doctors->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<style>
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
</style>
@endsection