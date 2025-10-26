<div>
    <div class="row g-3">
        <!-- List -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Product Categories</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($categories as $cat)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $cat->name }}</div>
                                <span class="badge {{ $cat->is_active == 1 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $cat->is_active == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="btn-group">
                                <button wire:click="edit({{ $cat->id }})" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button wire:click="confirmDelete({{ $cat->id }})" class="btn btn-sm btn-outline-danger">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">No categories</li>
                    @endforelse
                </ul>

            </div>
        </div>

        <!-- Form -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">
                        {{ $categoryId ? 'Edit Category' : 'Add New Category' }}
                    </h5>
                    @if($categoryId)
                        <button wire:click="resetForm" class="btn btn-sm btn-secondary">Cancel</button>
                    @endif
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="save" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" wire:model.defer="name" class="form-control">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select wire:model="is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea wire:model.defer="description" class="form-control" rows="4"></textarea>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary">{{ $categoryId ? 'Update' : 'Create' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @script
    <script>
        $wire.on('confirm', (event) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this product category.",
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