<div>
    <div class="container my-5">
        <div class="card p-3 mb-4">
            <h2 class="text-center mb-4">Product Feedback & Ratings</h2>

            <!-- Filters -->
            <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                <select wire:model.change="selectedProduct" class="form-select w-auto">
                    <option value="all">All Products</option>
                    @foreach($productsData as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Products Summary -->

        <div class="row">
            @forelse($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="{{ asset($product->image ?? NO_IMAGE) }}" alt="img" class="card-img-top"
                        style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="mb-1">
                            Average: ⭐ <strong>{{ number_format($product->average_rating, 1) ?? 'No Rating' }}</strong>
                            / 5

                            <span class="text-muted small">
                                ({{ $product->ratings_count }} {{ Str::plural('review', $product->ratings_count) }})
                            </span>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('guest.product.feedback', $product->uuid) }}"
                            class="btn btn-outline-primary btn-sm">
                            View Feedbacks
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <p>No products found.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>