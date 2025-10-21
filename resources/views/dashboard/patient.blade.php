@extends('dashboard.layout')

@section('title', 'My Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Welcome back, {{ auth()->user()->name }}</h2>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Upcoming Appointments</h6>
                            <h2 class="mb-0">{{ $appointments->count() }}</h2>
                        </div>
                        <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Doctors</h6>
                            <h2 class="mb-0">{{ $appointments->unique('doctor_id')->count() }}</h2>
                        </div>
                        <i class="fas fa-user-md fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Prescriptions</h6>
                            <h2 class="mb-0">{{ auth()->user()->patient?->prescriptions?->count() ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-prescription fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Quick Actions</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('appointments.create') }}" class="btn btn-outline-primary w-100 h-100 py-3">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                                    <span>Book Appointment</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('doctors.index') }}" class="btn btn-outline-success w-100 h-100 py-3">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-user-md fa-2x mb-2"></i>
                                    <span>Find a Doctor</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('patient.appointments') }}" class="btn btn-outline-info w-100 h-100 py-3">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-file-medical fa-2x mb-2"></i>
                                    <span>My Appointments</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('patient.prescriptions') }}" class="btn btn-outline-warning w-100 h-100 py-3">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-prescription fa-2x mb-2"></i>
                                    <span>Prescriptions</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Appointments</h5>
                    <a href="{{ route('patient.appointments') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Doctor</th>
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
                                                            {{ substr($appointment->doctor->user->name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Dr. {{ $appointment->doctor->user->name }}</h6>
                                                        <small class="text-muted">{{ $appointment->doctor->specialties->first()?->name ?? 'General' }}</small>
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
                                                    <button class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#cancelAppointmentModal"
                                                            data-appointment-id="{{ $appointment->appointment_id }}"
                                                            title="Cancel Appointment">
                                                        <i class="fas fa-times"></i>
                                                    </button>
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
                                <i class="fas fa-plus me-2"></i>Book an Appointment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Health Summary -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Health Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Blood Group</h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-danger me-2">{{ auth()->user()->patient?->blood_group ?? 'Not specified' }}</span>
                            <small class="text-muted">
                                <a href="{{ route('profile.edit') }}">Update</a>
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Last Checkup</h6>
                        <p class="mb-1">
                            @php
                                $patient = auth()->user()->patient;
                                $lastAppointment = $patient?->appointments
                                    ?->where('status', 'completed')
                                    ?->sortByDesc('appointment_time')
                                    ?->first();
                            @endphp
                            
                            @if($lastAppointment)
                                {{ $lastAppointment->appointment_time->format('M d, Y') }} with 
                                <strong>Dr. {{ $lastAppointment->doctor->user->name }}</strong>
                            @else
                                No previous checkups
                            @endif
                        </p>
                        <small class="text-muted">
                            <a href="{{ route('patient.appointments') }}">View History</a>
                        </small>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Current Medications</h6>
                        @php
                            $patient = auth()->user()->patient;
                            $currentMeds = $patient?->prescriptions
                                ?->where('is_active', true)
                                ?->pluck('medication')
                                ?->unique() ?? collect();
                        @endphp
                        
                        @if($currentMeds->count() > 0)
                            <ul class="list-unstyled mb-1">
                                @foreach($currentMeds as $med)
                                    <li>{{ $med }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-1">No current medications</p>
                        @endif
                        <small class="text-muted">
                            <a href="{{ route('patient.prescriptions') }}">View All</a>
                        </small>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="#" class="btn btn-danger" onclick="alert('Emergency services: Call 911 or your local emergency number')">
                            <i class="fas fa-ambulance me-2"></i>Emergency Help
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Health Tips -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Health Tips</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-utensils text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Eat a Balanced Diet</h6>
                            <p class="small text-muted mb-0">Include a variety of fruits, vegetables, and whole grains in your diet.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-walking text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Stay Active</h6>
                            <p class="small text-muted mb-0">Aim for at least 30 minutes of moderate exercise daily.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-bed text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Get Enough Sleep</h6>
                            <p class="small text-muted mb-0">7-9 hours of quality sleep is essential for good health.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div class="modal fade" id="cancelAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="cancelAppointmentForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this appointment?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Yes, Cancel Appointment</button>
                </div>
            </form>
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
        
        // Handle cancel appointment modal
        var cancelAppointmentModal = document.getElementById('cancelAppointmentModal');
        if (cancelAppointmentModal) {
            cancelAppointmentModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var appointmentId = button.getAttribute('data-appointment-id');
                var form = document.getElementById('cancelAppointmentForm');
                form.action = '/appointments/' + appointmentId;
            });
        }
    });
</script>
@endpush
@endsection
