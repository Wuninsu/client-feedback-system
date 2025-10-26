<div>
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Feedback Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Feedback Details</h4>
                        <span class="badge bg-{{ $data->status == 'sent'
                                                                ? 'success'
                                                                : ($data->status == 'reviewed'
                                                                    ? 'info'
                                                                    : ($data->status == 'rejected'
                                                                        ? 'danger'
                                                                        : ($data->status == 'resolved'
                                                                            ? 'primary'
                                                                            : 'secondary'))) }}">
                            {{ ucfirst($data->status) }}
                        </span>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                <span class="text-capitalize">{{ $data->status ?? 'N/A' }}</span>
                            </p>
                            <p><strong>Submitted To:</strong>
                                {{ $data->user?->name ?? 'Guest User' }}
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Token:</strong> {{ $data->token }}</p>
                            <p><strong>Submitted On:</strong> {{ $data->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Associated Products -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-bold">Associated Product(s)</div>
                <div class="card-body">
                    @forelse ($data->products as $product)
                    <div class="d-flex align-items-center mb-3 border-bottom pb-2">
                        <img src="{{ asset( $product->image ?? NO_IMAGE) }}" alt="{{ $product->name }}"
                            class="rounded me-3" style="width: 70px; height: 70px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0">{{ $product->name }}</h6>
                            <small class="text-muted">{{ $product->category->name ?? 'Uncategorized'
                                }}</small>
                            <p class="mb-0 text-muted small">{{ Str::limit($product->description, 80) }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No products linked to this feedback.</p>
                    @endforelse
                </div>
            </div>

            <!-- Feedback Entries -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-bold">Feedback Entries</div>
                <div class="card-body">
                    @forelse ($data->entries as $entry)
                    <div class="mb-4">

                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">
                                {{ $entry->product?->name ?? 'General Feedback' }}
                            </h6>
                            <div class="">
                                <div class="d-flex align-items-center gap-3 mt-3">
                                    <label class="fw-semibold text-secondary small mb-0">Status:</label>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status_{{ $entry->id }}"
                                            id="pending_{{ $entry->id }}" value="pending"
                                            wire:click="updateStatus({{ $entry->id }}, 'pending')" {{ $entry->status ===
                                        'pending' ?
                                        'checked' : '' }}
                                        >
                                        <label class="form-check-label text-muted small"
                                            for="pending_{{ $entry->id }}">Pending</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status_{{ $entry->id }}"
                                            id="approved_{{ $entry->id }}" value="approved"
                                            wire:click="updateStatus({{ $entry->id }}, 'approved')" {{ $entry->status
                                        === 'approved' ?
                                        'checked' : '' }}
                                        >
                                        <label class="form-check-label text-success small"
                                            for="approved_{{ $entry->id }}">Approved</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status_{{ $entry->id }}"
                                            id="rejected_{{ $entry->id }}" value="rejected"
                                            wire:click="updateStatus({{ $entry->id }}, 'rejected')" {{ $entry->status
                                        === 'rejected' ?
                                        'checked' : '' }}
                                        >
                                        <label class="form-check-label text-danger small"
                                            for="rejected_{{ $entry->id }}">Rejected</label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @for ($i = 1; $i <= 5; $i++) <i
                                    class="bi {{ $i <= $entry->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}">
                                    </i>
                                    @endfor
                            </div>
                        </div>
                        <p class="text-muted small mb-1">Category: {{ ucfirst($entry->feedback_category) }}</p>
                        <p class="mb-2">{{ $entry->comment }}</p>
                        <hr>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No feedback entries found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Responses Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-bold">Responses</div>
                <div class="card-body">
                    @forelse ($data->responses as $response)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $response->responder?->name ?? 'Admin Response' }}</strong>
                            <small class="text-muted">{{ $response->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-2">{{ $response->message }}</p>
                        <hr>
                    </div>
                    @empty
                    <p class="text-muted">No responses yet.</p>
                    @endforelse

                    <!-- Response Form -->
                    <form wire:submit.prevent="addResponse">
                        <div class="mb-3">
                            <textarea wire:model="message" class="form-control" rows="3"
                                placeholder="Write a response..."></textarea>
                            @error('message') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Send Response
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>