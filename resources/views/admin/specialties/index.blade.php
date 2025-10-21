@extends('dashboard.layout')

@section('title', 'Manage Specialties')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Specialties</h2>
        <a href="{{ route('specialties.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Specialty
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
                            <th>Description</th>
                            <th>Doctors Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specialties as $specialty)
                            <tr>
                                <td>{{ $specialty->specialty_id }}</td>
                                <td>
                                    <h6 class="mb-0">{{ $specialty->name }}</h6>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($specialty->description, 50) ?: 'No description' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $specialty->doctors_count }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('specialties.show', $specialty->specialty_id) }}" 
                                           class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('specialties.edit', $specialty->specialty_id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('specialties.destroy', $specialty->specialty_id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this specialty?');">
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
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-star fa-3x mb-3"></i>
                                        <p>No specialties found.</p>
                                        <a href="{{ route('specialties.create') }}" class="btn btn-primary">
                                            Add First Specialty
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($specialties->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $specialties->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection