@extends('dashboard.layout')

@section('title', 'Doctor Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Doctor Dashboard</h2>
        <div>
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Appointment
            </a>
        </div>
    </div>
    
    <!-- Welcome Card -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Welcome, Dr. {{ auth()->user()->name }}</h4>
                    <p class="mb-0">Here's what's happening with your practice today.</p>
                </div>
                <i class="fas fa-user-md fa-4x opacity-25"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Today's Appointments</h6>
                            <h2 class="mb-0">{{ $appointments->where('appointment_time', '>=', now()->startOfDay())
                                ->where('appointment_time', '<=', now()->endOfDay())->count() }}</h2>
                        </div>
                        <i class="fas fa-calendar-day fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Upcoming Appointments</h6>
                            <h2 class="mb-0">{{ $appointments->count() }}</h2>
                        </div>
                        <i class="fas fa-calendar-week fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pending Approvals</h6>
                            <h2 class="mb-0">{{ $appointments->where('status', 'pending')->count() }}</h2>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Upcoming Appointments</h5>
            <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0">
            @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Date & Time</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <span class="avatar-initial bg-primary rounded-circle">
                                                    {{ substr($appointment->patient->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $appointment->patient->user->name }}</h6>
                                                <small class="text-muted">{{ $appointment->patient->blood_group ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $appointment->appointment_time->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $appointment->appointment_time->format('h:i A') }}</small>
                                    </td>
                                    <td>{{ $appointment->appointment_type }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $appointment->status === 'booked' ? 'warning' : 
                                            ($appointment->status === 'completed' ? 'success' : 'danger') 
                                        }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('appointments.show', $appointment->appointment_id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($appointment->status === 'booked')
                                            <form action="{{ route('appointments.update', $appointment->appointment_id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to mark this appointment as completed?')">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                        data-bs-toggle="tooltip" 
                                                        title="Mark as Completed">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-calendar-times fa-4x text-muted"></i>
                    </div>
                    <h5>No upcoming appointments</h5>
                    <p class="text-muted">You don't have any upcoming appointments scheduled.</p>
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Appointment
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('doctor.schedule') }}" class="btn btn-outline-primary text-start">
                            <i class="fas fa-clock me-2"></i> Manage Schedule
                        </a>
                        <a href="{{ route('patients.index') }}" class="btn btn-outline-success text-start">
                            <i class="fas fa-users me-2"></i> View Patients
                        </a>
                        <a href="{{ route('appointments.create') }}" class="btn btn-outline-info text-start">
                            <i class="fas fa-prescription me-2"></i> Create Appointment
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Patients</h5>
                    <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @php
                        $recentPatients = $appointments->unique('patient_id')
                            ->map(function($appt) {
                                return $appt->patient;
                            })
                            ->take(4);
                    @endphp
                    
                    @if($recentPatients->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentPatients as $patient)
                                <a href="{{ route('patients.show', $patient->patient_id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial bg-primary rounded-circle">
                                                {{ substr($patient->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $patient->user->name }}</h6>
                                            <small class="text-muted">Last visit: 
                                                {{ $patient->appointments->sortByDesc('appointment_time')->first()?->appointment_time?->diffForHumans() ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-injured fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No recent patients</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
