<?php

namespace App\Livewire\Main;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;
    public $category = 'all';
    public $search = '';

    public function confirmDelete($uuid)
    {
        $this->dispatch('confirm', uuid: $uuid);
    }

    #[On('delete')]
    public function handleDelete($uuid)
    {
        $product = Product::where('uuid', $uuid)->first();
        if ($product) {
            if ($product->image) {
                // Normalize the path (strip /storage/ if present)
                $relativePath = str_replace('/storage/', '', $product->image);

                // 1. Delete from 'public' disk (storage/app/public)
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }

                // 2. Delete from public directory directly (public/storage/...)
                $publicPath = public_path('storage/' . $relativePath);
                if (file_exists($publicPath)) {
                    @unlink($publicPath); // Suppress error if not found
                }
            }


            logActivity('product_delete', $product);
            $product->delete();
            sweetalert()->success('Product deleted successfully.');
        } else {
            sweetalert()->error('Product not found.');
        }
    }

    #[Title('Products')]
    public function render()
    {
        $categories = ProductCategory::orderBy('name')->get();

        $products = Product::query()
            ->when($this->category !== 'all', fn($q) => $q->where('product_category_id', $this->category))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->with('category') // eager load for display
            ->latest()
            ->paginate(paginationLimit());

        return view('livewire.main.product-index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
