@extends('dashboard.layout')

@section('title', 'Manage Reviews')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Reviews & Ratings</h2>
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
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>{{ $review->review_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial bg-success rounded-circle">
                                                {{ substr($review->patient->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $review->patient->user->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial bg-primary rounded-circle">
                                                {{ substr($review->doctor->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $review->doctor->user->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ms-2">({{ $review->rating }}/5)</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($review->review_text, 60) }}</span>
                                </td>
                                <td>{{ $review->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-info" title="View Full Review" 
                                                data-bs-toggle="modal" data-bs-target="#reviewModal-{{ $review->review_id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form action="{{ route('reviews.destroy', $review->review_id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this review?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Review Modal -->
                            <div class="modal fade" id="reviewModal-{{ $review->review_id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Review Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <strong>Patient:</strong> {{ $review->patient->user->name }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Doctor:</strong> {{ $review->doctor->user->name }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Rating:</strong>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                                ({{ $review->rating }}/5)
                                            </div>
                                            <div class="mb-3">
                                                <strong>Review:</strong>
                                                <p class="mt-2">{{ $review->review_text }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Date:</strong> {{ $review->created_at->format('M d, Y h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-star fa-3x mb-3"></i>
                                        <p>No reviews found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reviews->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection