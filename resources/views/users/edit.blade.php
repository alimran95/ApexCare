@extends('dashboard.layout')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit User Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            @if(auth()->user()->isAdmin())
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-select @error('role') is-invalid @enderror" 
                                                id="role" name="role" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="doctor" {{ old('role', $user->role) === 'doctor' ? 'selected' : '' }}>Doctor</option>
                                            <option value="patient" {{ old('role', $user->role) === 'patient' ? 'selected' : '' }}>Patient</option>
                                        </select>
                                        @if(auth()->id() === $user->id)
                                            <small class="text-muted">You cannot change your own role.</small>
                                            <input type="hidden" name="role" value="{{ $user->role }}">
                                        @endif
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Users
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Change Password -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.password.update', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        @if(auth()->user()->isAdmin() && auth()->id() !== $user->id)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-1"></i>
                                You are changing the password for {{ $user->name }}.
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-key me-1"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Account Status -->
            @if(auth()->user()->isAdmin() && auth()->id() !== $user->id)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Account Status</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            Status: 
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                        
                        <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} w-100">
                                <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} me-1"></i>
                                {{ $user->is_active ? 'Deactivate Account' : 'Activate Account' }}
                            </button>
                        </form>
                        
                        @if(auth()->id() !== $user->id)
                            <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash-alt me-1"></i> Delete Account
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-label {
        font-weight: 500;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    .btn i {
        margin-right: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Disable form submission on Enter key
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && !e.target.classList.contains('allow-enter')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
@endsection
