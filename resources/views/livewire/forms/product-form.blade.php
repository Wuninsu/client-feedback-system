<div>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $productId ? 'Edit Product' : 'Add New Product' }}</h5>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form wire:submit.prevent="save" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label mb-0">Category</label>
                    <select wire:model.change="product_category_id"
                        class="form-select @error('product_category_id') border-danger is-invalid @enderror">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('product_category_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-0">Status</label>
                    <select wire:model="status" class="form-select @error('status') border-danger is-invalid @enderror">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status') <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-0">Name</label>
                    <input type="text" wire:model.blur="name"
                        class="form-control @error('name') border-danger is-invalid @enderror">
                    @error('name') <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-0">SKU</label>
                    <input type="text" wire:model.defer="sku"
                        class="form-control @error('sku') border-danger is-invalid @enderror">
                    @error('sku') <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label mb-0">Description</label>
                    <textarea wire:model.defer="description"
                        class="form-control @error('description') border-danger is-invalid @enderror"
                        rows="3"></textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-0">Price (GHS)</label>
                    <input type="number" wire:model.defer="price"
                        class="form-control @error('price') border-danger is-invalid @enderror" step="0.01">
                    @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-0">Product Image</label>
                    <input type="file" wire:model="image" class="form-control">
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <span wire:loading wire:target="image" class="text-success">uploading...</span>
                    <div class="mt-1">
                        <img class="img-thumbnail shadow-sm" width="100"
                            src="{{ asset($image ? $image->temporaryUrl() : $existingImage ?? NO_IMAGE) }}" alt=""
                            srcset="">
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-end" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ __($productId ? 'Update Product' : 'Create Product') }}</span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            {{__($productId ? 'Updating' : 'Creating') }} Product
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>