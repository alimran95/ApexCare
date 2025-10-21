@extends('layouts.app')

@section('title', 'Find Clinics - ApexCare')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">Find Medical Clinics</h1>
        <p class="lead text-muted">Discover trusted healthcare facilities in your area</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('find.clinics') }}">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="search" class="form-label">Search by Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Clinic name..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-3 d-flex align-items-end">
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
                            
                            <!-- Services (you can expand this based on your clinic model) -->
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
                                <a href="{{ route('clinics.show', $clinic->clinic_id) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                                @auth
                                    <a href="{{ route('appointments.create', ['clinic_id' => $clinic->clinic_id]) }}" 
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
                    <a href="{{ route('find.clinics') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>Clear Filters
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($clinics->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $clinics->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Additional Information Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row">
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
</div>

<style>
.clinic-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection