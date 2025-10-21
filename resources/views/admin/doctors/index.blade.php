@extends('dashboard.layout')

@section('title', 'Manage Doctors')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Doctors</h2>
        <a href="{{ route('doctors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Doctor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
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
                        @forelse($doctors as $doctor)
                            <tr>
                                <td>{{ $doctor->doctor_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial bg-primary rounded-circle">
                                                {{ substr($doctor->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $doctor->user->name }}</h6>
                                            <small class="text-muted">{{ $doctor->user->phone }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $doctor->user->email }}</td>
                                <td>{{ $doctor->license_number }}</td>
                                <td>
                                    @foreach($doctor->specialties as $specialty)
                                        <span class="badge bg-info me-1">{{ $specialty->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $doctor->clinic->name ?? 'Not assigned' }}</td>
                                <td>
                                    <span class="badge bg-{{ $doctor->user->is_active ? 'success' : 'secondary' }}">
                                        {{ $doctor->user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('doctors.show', $doctor->doctor_id) }}" 
                                           class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('doctors.edit', $doctor->doctor_id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('doctors.destroy', $doctor->doctor_id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to deactivate this doctor?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Deactivate">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-user-md fa-3x mb-3"></i>
                                        <p>No doctors found.</p>
                                        <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                                            Add First Doctor
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($doctors->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $doctors->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection