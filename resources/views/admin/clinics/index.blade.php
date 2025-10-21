@extends('dashboard.layout')

@section('title', 'Manage Clinics')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Clinics</h2>
        <a href="{{ route('clinics.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Clinic
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
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Doctors Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clinics as $clinic)
                            <tr>
                                <td>{{ $clinic->clinic_id }}</td>
                                <td>
                                    <h6 class="mb-0">{{ $clinic->name }}</h6>
                                    <small class="text-muted">{{ Str::limit($clinic->description, 30) }}</small>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($clinic->address, 50) }}</span>
                                </td>
                                <td>
                                    @if($clinic->phone)
                                        <div><i class="fas fa-phone me-1"></i>{{ $clinic->phone }}</div>
                                    @endif
                                    @if($clinic->email)
                                        <div><i class="fas fa-envelope me-1"></i>{{ $clinic->email }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $clinic->doctors_count }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('clinics.show', $clinic->clinic_id) }}" 
                                           class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clinics.edit', $clinic->clinic_id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('clinics.destroy', $clinic->clinic_id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this clinic?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-hospital fa-3x mb-3"></i>
                                        <p>No clinics found.</p>
                                        <a href="{{ route('clinics.create') }}" class="btn btn-primary">
                                            Add First Clinic
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($clinics->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $clinics->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection