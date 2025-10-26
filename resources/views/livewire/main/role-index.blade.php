<div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Search by name...">
                </div>
                <div class="col-md-6">
                    <div class="float-end">
                        <a class="btn btn-dark" href="{{ route('roles.index') }}"><i
                                class="bi bi-arrow-clockwise"></i></a>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div
                    class="@canany(['create role', 'update role']) col-md-8 @else col-12 @endcanany order-2 order-md-1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    @can('assign permission')
                                        <th class="text-end">Action</th>
                                    @endcan

                                </tr>
                            </thead>
                            <tbody>
                                @isset($roles)
                                    @forelse ($roles as $role)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $role->name }}</td>
                                            @canany(['create role', 'update role'])
                                                <td class="text-end">
                                                    @can('assign permission')
                                                        <a class="btn btn-success btn-sm"
                                                            href="{{ route('roles.manage_permissions', ['role' => $role->id]) }}">
                                                            <i class="align-middle" data-feather="shield"></i> Assign Permissions
                                                        </a>
                                                    @endcan

                                                    @can('update role')
                                                        <button type="button" wire:click="editRole({{ $role->id }})"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                    @endcan

                                                    @can('delete role')
                                                        <button type="button" wire:click="confirmDelete({{ $role->id }})"
                                                            class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endcan

                                                </td>
                                            @endcanany
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <span class="text-danger">No records found</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                @endisset
                            </tbody>
                        </table>
                        <div class="">
                            @isset($roles)
                                {{ $roles->links() }}
                            @endisset
                        </div>

                    </div>
                </div>
                @canany(['create roles', 'update role'])
                    <div class="col-md-4 order-1 order-md-2">
                        <div class="my-3">
                            <form wire:submit.prevent="{{ $isEdit == true ? 'updateRole()' : 'saveRole()' }}">
                                <div class="form-group mb-2">
                                    <label for="name">Role Name</label>
                                    <input type="text" wire:model.live="name"
                                        class="form-control @error('name') border-danger is-invalid @enderror">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mt-2">
                                    @canany(['create role', 'update role'])
                                        <button type="submit" class="btn btn-primary mb-0 w-100">
                                            {{ $isEdit ? 'Edit ' : 'Add ' }}Role
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-primary mb-0 w-100" disabled>
                                            You do not have permission
                                        </button>
                                    @endcanany

                                </div>
                            </form>
                        </div>
                    </div>
                @endcanany
            </div>
        </div>
    </div>

    @script
        <script>
            $wire.on('confirm', (event) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to delete this role. This action cannot be undone.",
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

