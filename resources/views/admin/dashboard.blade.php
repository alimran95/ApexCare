<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - ApexCare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .sidebar { 
            background: #2c3e50; 
            min-height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 250px; 
            z-index: 1000;
        }
        .sidebar .nav-link { 
            color: #ecf0f1; 
            padding: 12px 20px; 
            border-radius: 0; 
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            background: #34495e; 
            color: #fff; 
        }
        .sidebar .nav-link i { 
            width: 20px; 
            margin-right: 10px; 
        }
        .main-content { 
            margin-left: 250px; 
            padding: 20px; 
        }
        .card { 
            border: none; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            transition: transform 0.2s;
        }
        .card:hover { 
            transform: translateY(-2px); 
        }
        .stat-card { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
        }
        .stat-card-2 { 
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); 
            color: white; 
        }
        .stat-card-3 { 
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); 
            color: white; 
        }
        .stat-card-4 { 
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); 
            color: white; 
        }
        .navbar-brand { 
            font-weight: 700; 
            font-size: 1.5rem; 
            color: #fff !important; 
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-brand:hover {
            color: #fff !important;
            text-decoration: none;
        }
        .logo-image {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        .recent-activity { 
            max-height: 400px; 
            overflow-y: auto; 
        }
        .activity-item { 
            padding: 10px 0; 
            border-bottom: 1px solid #eee; 
        }
        .activity-item:last-child { 
            border-bottom: none; 
        }
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
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .sidebar { 
                transform: translateX(-100%); 
                transition: transform 0.3s; 
            }
            .sidebar.show { 
                transform: translateX(0); 
            }
            .main-content { 
                margin-left: 0; 
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="p-3">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo_apexcare.png') }}" alt="ApexCare Logo" class="logo-image">
                ApexCare
            </a>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('doctors.index') }}">
                    <i class="bi bi-person-badge"></i> Doctors
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('patients.index') }}">
                    <i class="bi bi-person"></i> Patients
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('appointments.index') }}">
                    <i class="bi bi-calendar-event"></i> Appointments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('specialties.index') }}">
                    <i class="bi bi-star"></i> Specialties
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('doctor-schedules.index') }}">
                    <i class="bi bi-clock"></i> Doctor Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('clinics.index') }}">
                    <i class="bi bi-geo-alt"></i> Clinics / Locations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reviews.index') }}">
                    <i class="bi bi-chat-dots"></i> Reviews / Ratings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('payments.index') }}">
                    <i class="bi bi-credit-card"></i> Payments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-bell"></i> Notifications
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-shield-check text-primary"></i> Admin Dashboard</h2>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, {{ Auth::user()->name }}!</span>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Admin Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stat-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Total Users</h6>
                            <h2 class="mb-0">{{ $stats['users'] }}</h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card-2 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Total Doctors</h6>
                            <h2 class="mb-0">{{ $stats['doctors'] }}</h2>
                        </div>
                        <i class="bi bi-person-badge fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card-3 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Total Patients</h6>
                            <h2 class="mb-0">{{ $stats['patients'] }}</h2>
                        </div>
                        <i class="bi bi-person fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card-4 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Appointments Today</h6>
                            <h2 class="mb-0">{{ $stats['appointments_today'] }}</h2>
                        </div>
                        <i class="bi bi-calendar-event fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Responsibilities Section -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-shield-check"></i> Admin Responsibilities</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <i class="bi bi-person-check fs-2 text-success me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Doctor Registration Approval</h6>
                                        <p class="text-muted mb-0">Approve or reject new doctor registrations</p>
                                        <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-outline-success mt-2">Manage</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <i class="bi bi-people fs-2 text-info me-3"></i>
                                    <div>
                                        <h6 class="mb-1">User Management</h6>
                                        <p class="text-muted mb-0">View, edit, and deactivate user accounts</p>
                                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-info mt-2">Manage</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <i class="bi bi-calendar-check fs-2 text-warning me-3"></i>
                                    <div>
                                        <h6 class="mb-1">All Appointments</h6>
                                        <p class="text-muted mb-0">View and manage all system appointments</p>
                                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-warning mt-2">View All</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <i class="bi bi-bell fs-2 text-danger me-3"></i>
                                    <div>
                                        <h6 class="mb-1">System Notifications</h6>
                                        <p class="text-muted mb-0">Send system-wide notifications to users</p>
                                        <button class="btn btn-sm btn-outline-danger mt-2" onclick="alert('Notification system coming soon!')">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manage Doctors Section -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Manage Doctors</h5>
                        <a href="{{ route('doctors.create') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-plus"></i> Add New Doctor
                        </a>
                    </div>
                    <div class="card-body">
                        @if($recentDoctors->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Doctor Name</th>
                                            <th>Email</th>
                                            <th>License Number</th>
                                            <th>Specialties</th>
                                            <th>Clinic</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentDoctors as $user)
                                            @if($user->doctor)
                                                <tr>
                                                    <td>{{ $user->doctor->doctor_id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar me-3">
                                                                <span class="avatar-initial bg-primary rounded-circle">
                                                                    {{ substr($user->name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                                <small class="text-muted">{{ $user->phone ?? 'No phone' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->doctor->license_number ?? 'Not provided' }}</td>
                                                    <td>
                                                        @if($user->doctor->specialties && $user->doctor->specialties->count() > 0)
                                                            @foreach($user->doctor->specialties as $specialty)
                                                                <span class="badge bg-info me-1">{{ $specialty->name }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted small">No specialties</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->doctor->clinic->name ?? 'Not assigned' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('doctors.show', $user->doctor->doctor_id) }}" 
                                                               class="btn btn-sm btn-outline-info" title="View">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="{{ route('doctors.edit', $user->doctor->doctor_id) }}" 
                                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            @if(!$user->is_active)
                                                                <button class="btn btn-sm btn-outline-danger" title="Deactivated">
                                                                    <i class="bi bi-ban"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('doctors.index') }}" class="btn btn-primary">
                                    <i class="bi bi-eye"></i> View All Doctors
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-person-badge fs-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">No Recent Doctor Registrations</h5>
                                <p class="text-muted">New doctor registrations will appear here.</p>
                                <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus"></i> Add New Doctor
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Activity</h5>
                    </div>
                    <div class="card-body recent-activity">
                        <div class="activity-item">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-plus text-success me-3"></i>
                                <div>
                                    <strong>New doctor registered:</strong> Dr. Sarah
                                    <small class="text-muted d-block">2 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-plus text-info me-3"></i>
                                <div>
                                    <strong>New appointment booked:</strong>Dr. Dipak
                                    <small class="text-muted d-block">4 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-star text-warning me-3"></i>
                                <div>
                                    <strong>New review submitted:</strong> 5-star rating for Dr. Aditya
                                    <small class="text-muted d-block">6 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-credit-card text-primary me-3"></i>
                                <div>
                                    <strong>Payment processed:</strong> tk 1500 for appointment #1234
                                    <small class="text-muted d-block">8 hours ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Quick Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Pending Approvals</span>
                                <span class="badge bg-warning">5</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Active Appointments</span>
                                <span class="badge bg-success">23</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>System Alerts</span>
                                <span class="badge bg-danger">2</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Revenue Today</span>
                                <span class="text-success fw-bold">tk 2,450</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


