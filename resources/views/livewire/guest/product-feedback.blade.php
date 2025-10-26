<div>
    <div class="container py-5">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm mb-4 rounded-3 p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1 text-primary">
                        {{ $product->name }} <small class="text-muted fs-6">Customer Feedback</small>
                    </h3>
                    <p class="text-muted mb-0">
                        <i class="fa-solid fa-comments me-1"></i>
                        {{ $product->feedbackEntries->count() }}
                        {{ Str::plural('review', $product->feedbackEntries->count()) }}
                    </p>
                </div>

                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mt-3 mt-md-0">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Products
                </a>
            </div>
        </div>

        <!-- Feedback Entries -->
        @forelse ($product->feedbackEntries as $entry)
        @php $customer = $entry->feedback?->customer; @endphp

        <div class="card mb-3 border-0 shadow-sm rounded-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <!-- Customer Info -->
                    <div>
                        <h6 class="fw-semibold mb-1 text-dark">
                            <i class="fa-solid fa-user-circle text-primary me-1"></i>
                            {{ $customer?->name ?? 'Anonymous Customer' }}
                        </h6>
                        <p class="text-muted small mb-1">
                            <i class="fa-solid fa-tag me-1"></i>
                            {{ $entry->feedback_category ?? 'General' }} •
                            <i class="fa-regular fa-clock me-1"></i>
                            {{ $entry->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Star Rating -->
                    <div class="text-warning fs-6">
                        @for ($i = 1; $i <= 5; $i++) @if ($i <=$entry->rating)
                            <i class="fa-solid fa-star"></i>
                            @else
                            <i class="fa-regular fa-star"></i>
                            @endif
                            @endfor
                            <span class="text-muted small">({{ $entry->rating }}/5)</span>
                    </div>
                </div>

                <!-- Comment -->
                <p class="mt-3 mb-0 text-secondary" style="line-height: 1.6;">
                    <i class="fa-solid fa-quote-left text-muted me-2"></i>
                    {{ $entry->comment ? $entry->comment : 'No comment provided.' }}
                </p>
            </div>
        </div>
        @empty
        <div class="alert alert-light border text-center py-5">
            <i class="fa-solid fa-comment-slash fs-3 text-muted mb-3 d-block"></i>
            <p class="mb-0 text-muted">No feedback entries yet for this product.</p>
        </div>
        @endforelse
    </div>
</div>