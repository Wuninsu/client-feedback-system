<div>
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-md-4 col-sm-12">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control form-control-sm"
                        placeholder="Search by name...">
                </div>

                <div class="col-md-4 col-sm-6 d-flex gap-2">
                    <select class="form-select form-select-sm w-auto" wire:model="type">
                        <option value="all">All</option>
                        <option value="sms">SMS</option>
                        <option value="email">Email</option>
                    </select>

                    <a href="{{ route('templates.create') }}" class="btn btn-dark btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Template
                    </a>
                </div>
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Subject</th>
                            <th>Body</th>
                            <th>Status</th>
                            <th style="width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($templates as $template)
                            <tr>
                                <td class="fw-medium">{{ $template->name }}</td>
                                <td><span class="badge bg-secondary text-uppercase">{{ $template->type }}</span></td>
                                <td>{{ $template->subject ?? '—' }}</td>
                                <td>{{ Str::limit(strip_tags($template->body), 60) }}</td>
                                <td>
                                    @if($template->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('templates.edit', $template->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <button wire:click="confirmDelete('{{ $template->id }}')"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No message templates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div>
                    @isset($templates)
                        {{ $templates->links() }}
                    @endisset
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('confirm', (event) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this template.",
                icon: 'warning',
                confirmButtonColor: "#d33",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('delete', {
                        id: event.id
                    })
                }
            });

        });
    </script>
    @endscript
</div>