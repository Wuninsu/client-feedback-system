<div>

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-md-4 col-sm-12">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control form-control-sm"
                        placeholder="Search products...">
                </div>

                <div class="col-md-4 col-sm-6 d-flex gap-2">
                    <select class="form-select form-select-sm w-auto" wire:model.change="category">
                        <option value="all">All Categories</option>
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <a href="{{ route('products.create') }}" class="btn btn-dark btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Product
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif

            {{-- <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                <img src="{{ asset($product->image ?? NO_IMAGE) }}" alt="img" class="img-thumbnail"
                                    style="height: 40px;">
                                @else
                                <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td>{{ $product->sku ?? '—' }}</td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td>₵{{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="badge {{ $product->status === 1 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($product->status == 1 ? 'Active' : 'Inactive') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('products.edit', $product->uuid) }}"
                                    class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i> Edit</a>
                                <button wire:click="confirmDelete('{{ $product->uuid }}')"
                                    class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No products found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @isset($products)
                {{ $products->links() }}
                @endisset
            </div> --}}
            <div class="row">
                @forelse($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ asset($product->image ?? NO_IMAGE) }}" alt="img" class="card-img-top"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">
                                <strong>SKU:</strong> {{ $product->sku ?? '—' }}<br>
                                <strong>Category:</strong> {{ $product->category->name ?? '—' }}<br>
                                <strong>Price:</strong> ₵{{ number_format($product->price, 2) }}<br>
                                <strong>Status:</strong>
                                <span class="badge {{ $product->status === 1 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($product->status == 1 ? 'Active' : 'Inactive') }}
                                </span>
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('products.edit', $product->uuid) }}"
                                class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i> Edit</a>
                            <button wire:click="confirmDelete('{{ $product->uuid }}')"
                                class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i> Delete</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4">
                    <p>No products found.</p>
                </div>
                @endforelse
            </div>

            @isset($products)
            {{ $products->links() }}
            @endisset
        </div>
    </div>

    @script
    <script>
        $wire.on('confirm', (event) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this product.",
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