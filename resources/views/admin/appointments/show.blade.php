@extends('dashboard.layout')

@section('title', 'Appointment Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Appointment #{{ $appointment->appointment_id }}</h2>
        <div>
            <a href="{{ route('appointments.edit', $appointment->appointment_id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Appointments
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Appointment Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Appointment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Appointment ID:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $appointment->appointment_id }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Date & Time:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $appointment->appointment_time->format('l, F j, Y \a\t g:i A') }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Type:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-secondary">{{ ucfirst($appointment->appointment_type) }}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ 
                                $appointment->status === 'booked' ? 'warning' : 
                                ($appointment->status === 'completed' ? 'success' : 'danger') 
                            }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($appointment->notes)
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Notes:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $appointment->notes }}
                        </div>
                    </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Created:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $appointment->created_at->format('M j, Y \a\t g:i A') }}
                        </div>
                    </div>
                    
                    @if($appointment->updated_at != $appointment->created_at)
                    <div class="row">
                        <div class="col-sm-3">
                            <strong>Last Updated:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $appointment->updated_at->format('M j, Y \a\t g:i A') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Patient Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3">
                            <span class="avatar-initial bg-success rounded-circle">
                                {{ substr($appointment->patient->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $appointment->patient->user->name }}</h6>
                            <small class="text-muted">{{ $appointment->patient->user->email }}</small>
                        </div>
                    </div>
                    
                    @if($appointment->patient->dob)
                    <div class="row mb-2">
                        <div class="col-4">
                            <strong>Age:</strong>
                        </div>
                        <div class="col-8">
                            {{ \Carbon\Carbon::parse($appointment->patient->dob)->age }} years
                        </div>
                    </div>
                    @endif
                    
                    @if($appointment->patient->gender)
                    <div class="row mb-2">
                        <div class="col-4">
                            <strong>Gender:</strong>
                        </div>
                        <div class="col-8">
                            {{ ucfirst($appointment->patient->gender) }}
                        </div>
                    </div>
                    @endif
                    
                    @if($appointment->patient->blood_group)
                    <div class="row mb-2">
                        <div class="col-4">
                            <strong>Blood Group:</strong>
                        </div>
                        <div class="col-8">
                            {{ $appointment->patient->blood_group }}
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('patients.show', $appointment->patient->patient_id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-user me-1"></i>View Full Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Doctor Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Doctor Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3">
                            <span class="avatar-initial bg-primary rounded-circle">
                                {{ substr($appointment->doctor->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Dr. {{ $appointment->doctor->user->name }}</h6>
                            <small class="text-muted">{{ $appointment->doctor->license_number }}</small>
                        </div>
                    </div>
                    
                    @if($appointment->doctor->specialties->count() > 0)
                    <div class="row mb-2">
                        <div class="col-4">
                            <strong>Specialties:</strong>
                        </div>
                        <div class="col-8">
                            @foreach($appointment->doctor->specialties as $specialty)
                                <span class="badge bg-light text-dark me-1">{{ $specialty->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('doctors.show', $appointment->doctor->doctor_id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-user-md me-1"></i>View Full Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Clinic Information -->
            @if($appointment->clinic)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Clinic Information</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-2">{{ $appointment->clinic->name }}</h6>
                    
                    @if($appointment->clinic->address)
                    <div class="row mb-2">
                        <div class="col-4">
                            <strong>Address:</strong>
                        </div>
                        <div class="col-8">
                            {{ $appointment->clinic->address }}
                        </div>
                    </div>
                    @endif
                    
                    @if($appointment->clinic->contact_phone)
                    <div class="row mb-2">
                        <div class="col-4">
                            <strong>Phone:</strong>
                        </div>
                        <div class="col-8">
                            {{ $appointment->clinic->contact_phone }}
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('clinics.show', $appointment->clinic->clinic_id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-hospital me-1"></i>View Clinic Details
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 16px;
}
</style>
@endsection