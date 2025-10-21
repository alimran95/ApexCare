@extends('layouts.app')

@section('title', 'Book Appointment - ApexCare')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Book an Appointment
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf

                        <!-- Doctor Selection -->
                        <div class="mb-4">
                            <label for="doctor_id" class="form-label">Select Doctor *</label>
                            @if($selectedDoctor)
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <span class="avatar-initial bg-primary rounded-circle">
                                                    {{ substr($selectedDoctor->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Dr. {{ $selectedDoctor->user->name }}</h6>
                                                <div class="text-muted small">
                                                    @if($selectedDoctor->specialties->count() > 0)
                                                        {{ $selectedDoctor->specialties->pluck('name')->join(', ') }}
                                                    @else
                                                        General Medicine
                                                    @endif
                                                </div>
                                                @if($selectedDoctor->clinic)
                                                    <small class="text-muted">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $selectedDoctor->clinic->name }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="doctor_id" value="{{ $selectedDoctor->doctor_id }}">
                                <input type="hidden" name="clinic_id" value="{{ $selectedDoctor->clinic_id }}">
                            @else
                                <select class="form-select @error('doctor_id') is-invalid @enderror" 
                                        id="doctor_id" name="doctor_id" required>
                                    <option value="">Choose a doctor...</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->doctor_id }}" 
                                                {{ old('doctor_id') == $doctor->doctor_id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->user->name }}
                                            @if($doctor->specialties->count() > 0)
                                                - {{ $doctor->specialties->pluck('name')->join(', ') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <!-- Appointment Date and Time -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_time" class="form-label">Appointment Date & Time *</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('appointment_time') is-invalid @enderror" 
                                           id="appointment_time" name="appointment_time" 
                                           value="{{ old('appointment_time') }}" 
                                           min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" required>
                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_type" class="form-label">Appointment Type *</label>
                                    <select class="form-select @error('appointment_type') is-invalid @enderror" 
                                            id="appointment_type" name="appointment_type" required>
                                        <option value="">Select type...</option>
                                        <option value="consultation" {{ old('appointment_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                        <option value="follow_up" {{ old('appointment_type') == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                                        <option value="emergency" {{ old('appointment_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                    </select>
                                    @error('appointment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4" 
                                      placeholder="Please describe your symptoms or reason for the appointment...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Patient Information Notice -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Booking as:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('find.doctor') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Find Doctor
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Book Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 50px;
    height: 50px;
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
    font-size: 18px;
}
</style>
@endsection