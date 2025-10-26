<div>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">Feedback Reports</h4>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <label>Start Date</label>
                <input type="date" wire:model.live="startDate" class="form-control">
            </div>

            <div class="col-md-3">
                <label>End Date</label>
                <input type="date" wire:model.live="endDate" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="all">All</option>
                    <option value="pending">Pending</option>
                    <option value="sent">Sent</option>
                    <option value="reviewed">Reviewed</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>
        </div>

        <div class="row text-center mb-4">
            @foreach ($summary as $key => $count)
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="text-capitalize">{{ $key }}</h5>
                        <h4 class="fw-bold">{{ $count }}</h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Report Details</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Channel</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($feedbacks as $feedback)
                        <tr>
                            <td>{{ $feedback->client_name ?? 'N/A' }}</td>
                            <td><span
                                    class="badge bg-{{ $feedback->status == 'pending' ? 'warning' : ($feedback->status == 'resolved' ? 'success' : 'info') }}">{{
                                    ucfirst($feedback->status) }}</span></td>
                            <td>{{ $feedback->log->channel ?? 'N/A' }}</td>
                            <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No records found for the selected
                                period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>