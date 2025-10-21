@extends('dashboard.layout')

@section('title', 'Edit Appointment')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Appointment #{{ $appointment->appointment_id }}</h2>
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Appointments
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Appointment Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('appointments.update', $appointment->appointment_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Patient Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">Patient *</label>
                                    <select class="form-select @error('patient_id') is-invalid @enderror" 
                                            id="patient_id" name="patient_id" required>
                                        <option value="">Select Patient...</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->patient_id }}" 
                                                    {{ (old('patient_id') ?? $appointment->patient_id) == $patient->patient_id ? 'selected' : '' }}>
                                                {{ $patient->user->name }} ({{ $patient->user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Doctor Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="doctor_id" class="form-label">Doctor *</label>
                                    <select class="form-select @error('doctor_id') is-invalid @enderror" 
                                            id="doctor_id" name="doctor_id" required>
                                        <option value="">Select Doctor...</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->doctor_id }}" 
                                                    {{ (old('doctor_id') ?? $appointment->doctor_id) == $doctor->doctor_id ? 'selected' : '' }}>
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
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Clinic Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="clinic_id" class="form-label">Clinic</label>
                                    <select class="form-select @error('clinic_id') is-invalid @enderror" 
                                            id="clinic_id" name="clinic_id">
                                        <option value="">Select Clinic...</option>
                                        @foreach($clinics as $clinic)
                                            <option value="{{ $clinic->clinic_id }}" 
                                                    {{ (old('clinic_id') ?? $appointment->clinic_id) == $clinic->clinic_id ? 'selected' : '' }}>
                                                {{ $clinic->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('clinic_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Appointment Type -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_type" class="form-label">Appointment Type *</label>
                                    <select class="form-select @error('appointment_type') is-invalid @enderror" 
                                            id="appointment_type" name="appointment_type" required>
                                        <option value="">Select Type...</option>
                                        <option value="consultation" {{ (old('appointment_type') ?? $appointment->appointment_type) == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                        <option value="follow_up" {{ (old('appointment_type') ?? $appointment->appointment_type) == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                                        <option value="emergency" {{ (old('appointment_type') ?? $appointment->appointment_type) == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                    </select>
                                    @error('appointment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Appointment Date and Time -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_time" class="form-label">Appointment Date & Time *</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('appointment_time') is-invalid @enderror" 
                                           id="appointment_time" name="appointment_time" 
                                           value="{{ old('appointment_time') ?? $appointment->appointment_time->format('Y-m-d\TH:i') }}" 
                                           required>
                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status...</option>
                                        <option value="booked" {{ (old('status') ?? $appointment->status) == 'booked' ? 'selected' : '' }}>Booked</option>
                                        <option value="completed" {{ (old('status') ?? $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ (old('status') ?? $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
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
                                      placeholder="Enter any additional notes for this appointment...">{{ old('notes') ?? $appointment->notes }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection