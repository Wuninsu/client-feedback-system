<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Sent Logs</h5>
            <div class="d-flex gap-2 flex-wrap">
                <input type="text" wire:model.debounce.500ms="search" class="form-control form-control-sm"
                    placeholder="Search..." />
                <button wire:click="retryAllFailed" class="btn btn-sm btn-warning">
                    <i class="bi bi-arrow-repeat"></i> Retry Failed
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Channel</th>
                            <th>To</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ strtoupper($log->channel) }}</td>
                                <td>{{ $log->to }}</td>
                                <td>
                                    <span class="badge {{ $log->status === 'sent' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td>{{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i') : '—' }}</td>
                                <td>
                                    @if($log->status === 'failed')
                                        <button wire:click="retry({{ $log->id }})" class="btn btn-sm btn-outline-warning">
                                            Retry
                                        </button>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No sent logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

</div>