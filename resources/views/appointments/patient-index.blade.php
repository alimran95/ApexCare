@extends('layouts.app')

@section('title', 'My Appointments - ApexCare')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-calendar-alt me-2"></i>My Appointments</h2>
                <a href="{{ route('find.doctor') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Book New Appointment
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($appointments && $appointments->count() > 0)
                <div class="row">
                    @foreach($appointments as $appointment)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user-md me-2"></i>
                                        Dr. {{ $appointment->doctor->user->name }}
                                    </h6>
                                    <span class="badge bg-{{ 
                                        $appointment->status === 'confirmed' ? 'success' : 
                                        ($appointment->status === 'pending' ? 'warning' : 
                                        ($appointment->status === 'completed' ? 'info' : 'danger')) 
                                    }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Date & Time:</strong><br>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('M d, Y') }}<br>
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Type:</strong> {{ ucfirst($appointment->appointment_type) }}
                                    </div>
                                    
                                    @if($appointment->doctor->specialties->count() > 0)
                                        <div class="mb-2">
                                            <strong>Specialty:</strong> {{ $appointment->doctor->specialties->pluck('name')->join(', ') }}
                                        </div>
                                    @endif
                                    
                                    @if($appointment->clinic)
                                        <div class="mb-2">
                                            <strong>Clinic:</strong><br>
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $appointment->clinic->name }}
                                        </div>
                                    @endif
                                    
                                    @if($appointment->notes)
                                        <div class="mb-2">
                                            <strong>Notes:</strong><br>
                                            <small class="text-muted">{{ $appointment->notes }}</small>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">
                                        Booked on {{ $appointment->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($appointments->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $appointments->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h4>No Appointments Yet</h4>
                    <p class="text-muted">You haven't booked any appointments yet. Find a doctor and book your first appointment!</p>
                    <a href="{{ route('find.doctor') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Find Doctor
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection