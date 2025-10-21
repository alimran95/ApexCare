<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
<!-- ApexCare Header -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <div class="d-flex align-items-center flex-wrap">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="d-flex align-items-center me-4 mb-2 mb-md-0 text-decoration-none">
                <div class="position-relative me-3">
                    <!-- Circular logo with shield and cross -->
                    <svg width="60" height="60" viewBox="0 0 60 60" class="d-block">
                        <!-- Outer circle -->
                        <circle cx="30" cy="30" r="28" fill="none" stroke="#2d5a27" stroke-width="3"/>
                        <!-- Shield shape -->
                        <path d="M30 8 L45 18 L45 35 L30 50 L15 35 L15 18 Z" fill="none" stroke="#2d5a27" stroke-width="2.5"/>
                        <!-- Medical cross -->
                        <rect x="27" y="20" width="6" height="20" fill="#2d5a27"/>
                        <rect x="20" y="27" width="20" height="6" fill="#2d5a27"/>
                    </svg>
                </div>
                <!-- APEXCARE text -->
                <div class="text-uppercase fw-bold d-none d-md-block" style="color: #2d5a27; font-size: 0.9rem; letter-spacing: 1px;">APEXCARE</div>
            </a>
            
            <!-- Main ApexCare branding -->
            <div class="flex-grow-1 mb-2 mb-md-0">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <!-- <h1 class="mb-0 fw-bold" style="color: #1a365d; font-size: clamp(1.8rem, 4vw, 2.5rem);">ApexCare</h1>
                </a>
                <p class="mb-0 text-muted small d-none d-md-block">Your Health, Our Priority</p> -->
            </div>
            
            <!-- User info and logout -->
            <div class="d-flex align-items-center">
                <div class="me-3 text-end d-none d-sm-block">
                    <div class="fw-semibold">{{ $user->name }}</div>
                    <div class="small text-muted">Patient</div>
                </div>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="mb-4">
        <h3 class="mb-2">Dashboard</h3>
        <p class="text-muted mb-0">Welcome back, {{ $user->name }}! Here's your health overview.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
                    <span>Patient Information</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="event.preventDefault(); toggleEditMode(); return false;" id="editBtn">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                </div>
                <div class="card-body">
                    <!-- View Mode -->
                    <div id="viewMode">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width:56px;height:56px;">
                                <span class="h4 mb-0">{{ substr($user->name,0,1) }}</span>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <div class="text-muted small">Patient ID: {{ $patient->patient_id ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="row small gy-2">
                            <div class="col-6"><span class="text-muted">Email:</span> {{ $user->email }}</div>
                            <div class="col-6"><span class="text-muted">Phone:</span> {{ $user->phone ?? '—' }}</div>
                            <div class="col-6"><span class="text-muted">Blood Group:</span> {{ $patient->blood_group ?? '—' }}</div>
                            <div class="col-6"><span class="text-muted">Gender:</span> {{ $patient->gender ?? '—' }}</div>
                            <div class="col-12"><span class="text-muted">Address:</span> {{ $patient->address ?? '—' }}</div>
                        </div>
                    </div>

                    <!-- Edit Mode -->
                    <div id="editMode" style="display: none;" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                        <form id="patientUpdateForm" method="POST" action="{{ route('patient.update') }}" onsubmit="return false;" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                <label for="name" class="form-label small fw-semibold">Full Name</label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ $user->name }}" required onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                            </div>
                            
                            <div class="mb-3" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                <label for="email" class="form-label small fw-semibold">Email</label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ $user->email }}" required onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                            </div>
                            
                            <div class="mb-3" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                <label for="phone" class="form-label small fw-semibold">Phone</label>
                                <input type="tel" class="form-control form-control-sm" id="phone" name="phone" value="{{ $user->phone ?? '' }}" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                            </div>
                            
                            <div class="row" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                <div class="col-6 mb-3" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                    <label for="blood_group" class="form-label small fw-semibold">Blood Group</label>
                                    <select class="form-select form-select-sm" id="blood_group" name="blood_group" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" {{ ($patient->blood_group ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ ($patient->blood_group ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ ($patient->blood_group ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ ($patient->blood_group ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ ($patient->blood_group ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ ($patient->blood_group ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ ($patient->blood_group ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ ($patient->blood_group ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-3" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                    <label for="gender" class="form-label small fw-semibold">Gender</label>
                                    <select class="form-select form-select-sm" id="gender" name="gender" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ ($patient->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ ($patient->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ ($patient->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                <label for="address" class="form-label small fw-semibold">Address</label>
                                <textarea class="form-control form-control-sm" id="address" name="address" rows="2" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">{{ $patient->address ?? '' }}</textarea>
                            </div>
                            
                            <div class="d-flex gap-2" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                <button type="button" class="btn btn-sm btn-primary" onclick="event.preventDefault(); savePatientInfo(); return false;">
                                    <i class="bi bi-check"></i> Save Changes
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="event.preventDefault(); cancelEdit(); return false;">
                                    <i class="bi bi-x"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
                    <span>Upcoming Appointments</span>
                    <a href="#" class="btn btn-sm btn-primary">Book Appointment</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingAppointments as $appt)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('M d, Y h:i A') }}</td>
                                        <td>{{ $appt->doctor?->user?->name }}</td>
                                        <td><span class="badge bg-info">{{ ucfirst($appt->status) }}</span></td>
                                        <td class="text-end"><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">No upcoming appointments</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light fw-semibold">Recent Visits</div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($pastAppointments as $appt)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <div class="fw-semibold">{{ $appt->doctor?->user?->name }}</div>
                                                <div class="small text-muted">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('M d, Y') }}</div>
                                            </div>
                                            <a href="#" class="btn btn-sm btn-outline-secondary">Details</a>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">No visit history</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light fw-semibold">Recent Payments</div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($payments as $pay)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>#{{ $pay->payment_id }} • {{ ucfirst($pay->status) }}</span>
                                        <span class="fw-semibold">৳{{ number_format($pay->amount,2) }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">No payment history</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light fw-semibold">Health Monitoring / Vitals</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6"><div class="text-muted small">Weight</div><div class="fw-semibold">{{ $vitals['weight'] ?? '—' }}</div></div>
                        <div class="col-6"><div class="text-muted small">Height</div><div class="fw-semibold">{{ $vitals['height'] ?? '—' }}</div></div>
                        <div class="col-6"><div class="text-muted small">BMI</div><div class="fw-semibold">{{ $vitals['bmi'] ?? '—' }}</div></div>
                        <div class="col-6"><div class="text-muted small">BP</div><div class="fw-semibold">{{ $vitals['bp'] ?? '—' }}</div></div>
                        <div class="col-6"><div class="text-muted small">Heart Rate</div><div class="fw-semibold">{{ $vitals['hr'] ?? '—' }}</div></div>
                        <div class="col-6"><div class="text-muted small">Sugar</div><div class="fw-semibold">{{ $vitals['sugar'] ?? '—' }}</div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light fw-semibold">Notifications & Alerts</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li>• Appointment reminders will appear here</li>
                        <li>• Medicine reminders</li>
                        <li>• Test report notifications</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ url('/') }}" class="btn btn-secondary">Back to Home</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Simple global functions that work immediately
let originalValues = {};

// Prevent all unwanted redirects in edit mode
document.addEventListener('click', function(e) {
    const editMode = document.getElementById('editMode');
    if (editMode && editMode.style.display !== 'none') {
        // If we're in edit mode, prevent any clicks from causing redirects
        if (e.target.closest('#editMode')) {
            console.log('Click prevented in edit mode:', e.target);
            e.stopPropagation();
            e.preventDefault();
            return false;
        }
    }
});

document.addEventListener('keydown', function(e) {
    const editMode = document.getElementById('editMode');
    if (editMode && editMode.style.display !== 'none') {
        // If we're in edit mode, prevent any keydown events from causing redirects
        if (e.target.closest('#editMode')) {
            console.log('Keydown prevented in edit mode:', e.target);
            e.stopPropagation();
            return false;
        }
    }
});

// Additional protection - prevent any form submission that might cause redirects
document.addEventListener('submit', function(e) {
    const editMode = document.getElementById('editMode');
    if (editMode && editMode.style.display !== 'none') {
        if (e.target.closest('#editMode')) {
            console.log('Form submission prevented in edit mode:', e.target);
            e.preventDefault();
            return false;
        }
    }
});

function toggleEditMode() {
    console.log('Toggle edit mode called');
    
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    const editBtn = document.getElementById('editBtn');
    
    if (!viewMode || !editMode || !editBtn) {
        console.error('Elements not found');
        return;
    }
    
    if (viewMode.style.display === 'none') {
        // Switch to view mode
        viewMode.style.display = 'block';
        editMode.style.display = 'none';
        editBtn.innerHTML = '<i class="bi bi-pencil"></i> Edit';
    } else {
        // Switch to edit mode
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
        editBtn.innerHTML = '<i class="bi bi-eye"></i> View';
        
        // Store original values
        originalValues = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            blood_group: document.getElementById('blood_group').value,
            gender: document.getElementById('gender').value,
            address: document.getElementById('address').value
        };
    }
}

function cancelEdit() {
    console.log('Cancel edit called');
    
    // Restore original values
    document.getElementById('name').value = originalValues.name;
    document.getElementById('email').value = originalValues.email;
    document.getElementById('phone').value = originalValues.phone;
    document.getElementById('blood_group').value = originalValues.blood_group;
    document.getElementById('gender').value = originalValues.gender;
    document.getElementById('address').value = originalValues.address;
    
    // Switch back to view mode
    toggleEditMode();
}

function savePatientInfo() {
    console.log('Save patient info called');
    
    const form = document.getElementById('patientUpdateForm');
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                           document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response received:', data);
        if (data.success) {
            // Show success message
            showAlert('success', 'Patient information updated successfully!');
            
            // Update the view mode with new values
            updateViewMode(formData);
            
            // Switch back to view mode
            toggleEditMode();
            
            // Reload page after a short delay to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message || 'Failed to update patient information.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred while updating patient information.');
    });
}

// Form submission is now handled by the savePatientInfo() function

function updateViewMode(formData) {
    // Update the view mode display with new values
    const nameElement = document.querySelector('#viewMode .fw-semibold');
    if (nameElement) {
        nameElement.textContent = formData.get('name');
    }
    
    // Update other fields in view mode
    const viewModeDiv = document.getElementById('viewMode');
    const emailSpan = viewModeDiv.querySelector('.row .col-6:nth-child(1)');
    const phoneSpan = viewModeDiv.querySelector('.row .col-6:nth-child(2)');
    const bloodGroupSpan = viewModeDiv.querySelector('.row .col-6:nth-child(3)');
    const genderSpan = viewModeDiv.querySelector('.row .col-6:nth-child(4)');
    const addressSpan = viewModeDiv.querySelector('.row .col-12');
    
    if (emailSpan) emailSpan.innerHTML = `<span class="text-muted">Email:</span> ${formData.get('email')}`;
    if (phoneSpan) phoneSpan.innerHTML = `<span class="text-muted">Phone:</span> ${formData.get('phone') || '—'}`;
    if (bloodGroupSpan) bloodGroupSpan.innerHTML = `<span class="text-muted">Blood Group:</span> ${formData.get('blood_group') || '—'}`;
    if (genderSpan) genderSpan.innerHTML = `<span class="text-muted">Gender:</span> ${formData.get('gender') || '—'}`;
    if (addressSpan) addressSpan.innerHTML = `<span class="text-muted">Address:</span> ${formData.get('address') || '—'}`;
}

function showAlert(type, message) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
</body>
</html>


