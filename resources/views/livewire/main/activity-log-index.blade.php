<div>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Activity Logs</h5>
            <input wire:model.debounce.500ms="search" type="text" class="form-control form-control-sm w-auto"
                placeholder="Search by user/action/model...">
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Model ID</th>
                            <th>IP</th>
                            <th>User Agent</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>{{ $log->action }}</td>
                                <td>{{ class_basename($log->model_type) ?? '-' }}</td>
                                <td>{{ $log->model_id ?? '-' }}</td>
                                <td>{{ $log->ip }}</td>
                                <td class="text-truncate" style="max-width: 200px;">{{ $log->user_agent }}</td>
                                <td>{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No activity logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $logs->links() }}
        </div>
    </div>

</div>