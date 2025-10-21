@extends('dashboard.layout')

@section('title', 'Manage Patients')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Patients</h2>
        <a href="{{ route('patients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Patient
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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
                            <th>Patient Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Blood Group</th>
                            <th>Appointments</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr>
                                <td>{{ $patient->patient_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial bg-success rounded-circle">
                                                {{ substr($patient->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $patient->user->name }}</h6>
                                            <small class="text-muted">
                                                {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'DOB not set' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $patient->user->email }}</td>
                                <td>{{ $patient->user->phone ?? 'Not provided' }}</td>
                                <td>
                                    @if($patient->blood_group)
                                        <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $patient->appointments->count() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $patient->user->is_active ? 'success' : 'secondary' }}">
                                        {{ $patient->user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('patients.show', $patient->patient_id) }}" 
                                           class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('patients.edit', $patient->patient_id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('patients.destroy', $patient->patient_id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to deactivate this patient?');">
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
                                        <i class="fas fa-user-injured fa-3x mb-3"></i>
                                        <p>No patients found.</p>
                                        <a href="{{ route('patients.create') }}" class="btn btn-primary">
                                            Add First Patient
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($patients->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection