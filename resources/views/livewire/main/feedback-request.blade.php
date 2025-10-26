<div>

    @if (session('success'))
    <div class="alert alert-success my-3">{{ session('success') }}</div>
    @endif
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
                <div class="col-md-4">
                    <button class="btn btn-dark" wire:click="openBulkModal">
                        <i class="bi bi-send me-1"></i> Send Bulk Request
                    </button>
                    <button class="btn btn-primary" wire:click="openModal">
                        <i class="bi bi-plus-circle"></i> New Request
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Token</th>
                        <th>Status</th>
                        <th>Products</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($feedbacks as $feedback)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $feedback->token }}</code></td>
                        <td>
                            <span class="badge bg-{{ 
                                $feedback->status == 'sent' ? 'success' :
                                ($feedback->status == 'reviewed' ? 'info' :
                                ($feedback->status == 'rejected' ? 'danger' :
                                ($feedback->status == 'resolved' ? 'primary' : 'secondary')))
                            }}">
                                {{ ucfirst($feedback->status) }}
                            </span>
                        </td>
                        <td>
                            @foreach ($feedback->products as $product)
                            <span class="badge bg-info text-dark">{{ $product->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"
                                wire:click="sendSingleRequest({{ $feedback->id }})" @disabled($feedback->status !=
                                'pending') wire:loading.attr="disabled">
                                <span wire:loading wire:target="sendSingleRequest({{ $feedback->id }})">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                                Send
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No feedback requests yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $feedbacks->links() }}
            </div>
        </div>
    </div>

    <div class="row">
        <div wire:ignore.self class="modal fade" id="bulkRequestForm" tabindex="-1"
            aria-labelledby="bulkRequestFormLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-3 shadow">
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold" id="bulkRequestFormLabel">Send Bulk Requests</h6>
                        <button type="button" class="btn-close" wire:click="$dispatch('close-bulk-modal')"></button>
                    </div>
                    <form wire:submit="sendBulkRequests">
                        <div class="modal-body">
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="fa-solid fa-circle-info fa-lg me-2"></i>
                                <div>
                                    <strong>Note:</strong> Only <span class="fw-semibold text-primary">pending feedback
                                        requests</span> within the selected date range will be sent.
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Start Date</label>
                                    <input type="date" wire:model.live="startDate" id="startDate" class="form-control @error('startDate') is-invalid border-danger 
                                    @enderror">
                                    @error('startDate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong> </span>
                                    @enderror

                                </div>

                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <input type="date" wire:model.live="endDate" id="endDate" class="form-control @error('endDate') is-invalid border-danger 
                                    @enderror">
                                    @error('endDate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong> </span>
                                    @enderror
                                </div>
                            </div>
                            ({{ $pendingCount }})
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" wire:click="$dispatch('close-bulk-modal')">Cancel</button>
                            <button class="btn btn-primary" type="submit" wire:loading.attr="disabled"
                                @disabled($pendingCount==0)>
                                <span wire:loading.remove>{{ __('Send Requests') }}</span>
                                <span wire:loading wire:target="sendBulkRequests">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    {{__('Sending please wait...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Request Form Modal -->
        <div wire:ignore.self class="modal fade" id="feedbackForm" tabindex="-1" aria-labelledby="feedbackFormLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-3 shadow">
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold" id="feedbackFormLabel">New Feedback Request</h6>
                        <button type="button" class="btn-close" wire:click="$dispatch('close-modal')"></button>
                    </div>
                    <form wire:submit="createFeedback">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Contact (Phone/Email)</label>
                                <input type="text"
                                    class="form-control @error('client_contact') is-invalid border-danger @enderror"
                                    wire:model.lazy="client_contact" placeholder="Enter client email or phone">
                                @error('client_contact') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label>Client Name</label>
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control @error('client_name') is-invalid border-danger @enderror"
                                        wire:model.defer="client_name" placeholder="Enter or auto-filled name">
                                    <button type="button" class="btn btn-outline-secondary" wire:click="verifyClient">
                                        Verify Client
                                    </button>
                                </div>
                                @error('client_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            @if($client_id)
                            <small class="text-success fw-bold"> <i class="fa fa-check-circle"></i> Client
                                verified</small>
                            @else
                            <small class="text-muted">Client not verified yet</small>
                            @endif
                            <div class="mb-3">
                                <label>Products</label>
                                <div wire:ignore class="">
                                    <select class="form-select" id="selectedProducts" wire:model="selectedProducts"
                                        multiple>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedProducts')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong> </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input @error('send_now') is-invalid border-danger @enderror"
                                        type="checkbox" id="sendNow" wire:model.live="send_now">
                                    <label class="form-check-label" for="sendNow">Send Now</label>
                                    @error('send_now') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- @if($errors->any())
                            <div class="alert alert-danger mt-3">
                                <strong>There were errors with your submission. Please check the fields:</strong>
                                <ul class="mt-2">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif --}}
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" wire:click="$dispatch('close-modal')">Cancel</button>
                            <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ __('Create Request') }}</span>
                                <span wire:loading wire:target="createFeedback">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    {{__('Creating please wait...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @script
    <script>
        $wire.on('show-modal', (event) => {
                $('#feedbackForm').modal('show');
            });
            $wire.on('close-modal', (event) => {
                $('#selectedProducts').val(null).trigger('change');
                $('#feedbackForm').modal('hide');
            });

            $wire.on('show-bulk-modal', (event) => {
            $('#bulkRequestForm').modal('show');
            });
            $wire.on('close-bulk-modal', (event) => {
            $('#bulkRequestForm').modal('hide');
            });
    </script>
    @endscript

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const selectedProducts = $('#selectedProducts');

            // Initialize Select2
            selectedProducts.select2();

            // Trigger Livewire event on change
            selectedProducts.on('change', function(e) {
                @this.set('selectedProducts', $(this).val());
            });

            // Reinitialize Select2 after Livewire updates
            Livewire.hook('message.processed', (message, component) => {
                selectedProducts.select2();
            });
        });
    </script>

</div>