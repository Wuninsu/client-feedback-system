<div>
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-md-4 col-sm-12">
                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control"
                        placeholder="Search by name">
                </div>

                <div class="col-md-4 col-sm-6 d-flex gap-2">

                    <select wire:model.change="role" class="form-select  form-select-sm w-auto">
                        <option value="">All Roles</option>
                        @foreach ($roles as $r)
                        <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                        @endforeach
                    </select>

                    <a href="{{ route('users.create') }}" class="btn btn-dark btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add User
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td class="pe-0">{{ $users->firstItem() + $loop->index }}</td>
                            <td class="ps-0">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset($user->avatar ?? NO_IMAGE) }}" alt="" class="ms-2 img-thumbnail"
                                        width="80" />
                                    <div class="ms-2">
                                        {{ $user->name }}
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->getRoleNames() as $role)
                                @php
                                $badgeClass = match ($role) {
                                'admin' => 'bg-success',
                                'client' => 'bg-primary',
                                'dev' => 'bg-danger',
                                default => 'bg-secondary',
                                };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($role) }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                @can('assign permission')
                                <a href="{{ route('users.manage-user-permission', ['user' => $user->uuid]) }}"
                                    class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                    aria-label="Permission" data-bs-original-title="Permissions">
                                    <i class="fa fa-key"></i>
                                </a>
                                @endcan
                                <a href="{{route('users.edit', ['user' => $user->uuid])}}"
                                    class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <button type="button" @if ($user->hasRole('dev') || $user->hasRole('admin'))
                                    disabled @endif
                                    wire:click="confirmDelete('{{ $user->uuid }}')" class="btn btn-sm btn-danger">
                                    <span wire:ignore><i class="fa fa-trash" class="icon-xs"></i></span>
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div>
                    @isset($users)
                    {{ $users->links() }}
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
                text: "You are about to delete this user. This action cannot be undone.",
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