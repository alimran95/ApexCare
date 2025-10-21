@extends('dashboard.layout')

@section('title', 'Manage Payments')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Payments</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Appointment</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial bg-success rounded-circle">
                                                {{ substr($payment->appointment->patient->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $payment->appointment->patient->user->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial bg-primary rounded-circle">
                                                {{ substr($payment->appointment->doctor->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $payment->appointment->doctor->user->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">#{{ $payment->appointment_id }}</span><br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($payment->appointment->appointment_time)->format('M d, Y') }}
                                    </small>
                                </td>
                                <td>
                                    <strong class="text-success">${{ number_format($payment->amount, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $payment->payment_status === 'paid' ? 'success' : 
                                        ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-info" title="View Details" 
                                                data-bs-toggle="modal" data-bs-target="#paymentModal-{{ $payment->payment_id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($payment->payment_status === 'pending')
                                            <button class="btn btn-sm btn-outline-success" title="Mark as Paid">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Payment Modal -->
                            <div class="modal fade" id="paymentModal-{{ $payment->payment_id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Payment Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <strong>Payment ID:</strong> {{ $payment->payment_id }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Patient:</strong> {{ $payment->appointment->patient->user->name }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Doctor:</strong> {{ $payment->appointment->doctor->user->name }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Appointment Date:</strong> 
                                                {{ \Carbon\Carbon::parse($payment->appointment->appointment_time)->format('M d, Y h:i A') }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Amount:</strong> 
                                                <span class="text-success">${{ number_format($payment->amount, 2) }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Payment Method:</strong> {{ $payment->payment_method ?? 'Not specified' }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Status:</strong>
                                                <span class="badge bg-{{ $payment->payment_status === 'paid' ? 'success' : 
                                                    ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($payment->payment_status) }}
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Payment Date:</strong> {{ $payment->created_at->format('M d, Y h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-credit-card fa-3x mb-3"></i>
                                        <p>No payments found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection