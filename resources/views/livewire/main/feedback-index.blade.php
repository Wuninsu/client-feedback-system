<div>

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-md-6">
                    <select class="form-select form-select-sm" wire:model.change="status">
                        <option value="">All status</option>
                        <option value="pending">Pending</option>
                        <option value="sent">Sent</option>
                        <option value="received">Received</option>
                    </select>
                </div>
                {{-- <div class="col-md-4">
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                        <i class="bi bi-send me-1"></i> Send Request
                    </button>
                </div> --}}
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Client Name</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->feedbacks as $feed)
                        <tr>
                            <td class="fw-semibold">{{ $feed->user ? $feed->user->name : $feed->guestUser->name }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $feed->status == 'sent'
                                            ? 'success'
                                            : ($feed->status == 'reviewed'
                                                ? 'info'
                                                : ($feed->status == 'rejected'
                                                    ? 'danger'
                                                    : ($feed->status == 'resolved'
                                                        ? 'primary'
                                                        : 'secondary'))) }}">
                                    {{ ucfirst($feed->status) }}
                                </span>
                            </td>
                            <td>{{ $feed->created_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <a href="{{ route('feedbacks.response.detail', ['token' => $feed->token]) }}"
                                    class="btn btn-sm btn-outline-primary shadow-sm px-3 py-2"><i class="fa fa-eye"></i>
                                    View</a>
                                <button wire:click="confirmDelete('{{ $feed->id }}')"
                                    class="btn btn-sm btn-outline-danger shadow-sm px-3 py-2"><i
                                        class="fa fa-trash"></i>
                                    Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No feedback found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @isset($this->feedbacks)
                {{ $this->feedbacks->links() }}
                @endisset
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('confirm', (event) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to delete this product.",
                    icon: 'warning',
                    confirmButtonColor: "#d33",
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch('delete', {
                            uuid: event.uuid
                        })
                    }
                });

            });
    </script>
    @endscript
</div>