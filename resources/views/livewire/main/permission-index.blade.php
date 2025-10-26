<div class="">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Search by name...">
                </div>
                <div class="col-md-6">
                    <div class="float-end">
                        <a class="btn btn-dark" href="{{ route('permissions.index') }}"><i
                                class="bi bi-arrow-clockwise"></i></a>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div
                    class="@can('assign permission') col-md-8 @elsecannot('assign permission') col-md-12 @endcan order-2 order-md-1">

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
                                @isset($permissions)
                                    @forelse ($permissions as $permission)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $permission->name }}</td>
                                            @can('assign permission')
                                                <td class="text-end">
                                                    <button type="button" wire:click="editPermission({{ $permission->id }})"
                                                        class="btn btn-primary btn-sm"><i
                                                            class="bi bi-pencil-square"></i></button>
                                                    <button type="button" wire:click="confirmDelete({{ $permission->id }})"
                                                        class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>

                                                </td>
                                            @endcan
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
                            @isset($permissions)
                                {{ $permissions->links() }}
                            @endisset
                        </div>

                    </div>
                </div>
                @can('assign permission')
                    <div class="col-md-4 order-1 order-md-2">
                        <div class="my-3">
                            <form wire:submit.prevent="{{ $isEdit == true ? 'updatePermission()' : 'savePermission()' }}">
                                <div class="form-group mb-2">
                                    <label for="name">Permission Name</label>
                                    <input type="text" wire:model.live="name"
                                        class="form-control @error('name') border-danger is-invalid @enderror">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mt-2">
                                    @can('create permission')
                                        <button type="submit"
                                            class="btn btn-primary mb-0 w-100">{{ $isEdit ? 'Edit ' : 'Add ' }}Permission</button>
                                    @elsecannot('create permission')
                                        <button type="submit" class="btn btn-primary mb-0 w-100" disabled>
                                            You do not have permission
                                        </button>
                                    @endcan

                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>


    @script
        <script>
            $wire.on('confirm', (event) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to delete this permission. This action cannot be undone.",
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
