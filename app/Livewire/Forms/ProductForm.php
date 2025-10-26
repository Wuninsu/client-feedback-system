<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use App\Models\ProductCategory;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductForm extends Component
{
    use WithFileUploads;

    public $productId = null;
    public $product_category_id, $name, $description, $sku, $status = 'active', $price, $image;
    public $existingImage = null;

    public function mount(Product $product)
    {
        if ($product->id) {
            $this->productId = $product->id;
            $this->product_category_id = $product->product_category_id;
            $this->name = $product->name;
            $this->description = $product->description;
            $this->sku = $product->sku;
            $this->status = $product->status == 1 ? 'active' : 'inactive';
            $this->price = $product->price;
            $this->existingImage = $product->image;
        }
    }

    public function save()
    {
        $this->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $this->productId,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'price' => 'required|numeric|min:0',
            'image' => $this->productId ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $path = $this->existingImage;
        if ($this->image) {
            if ($this->existingImage && Storage::disk('public')->exists($this->existingImage)) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $path = uploadFile($this->image, 'products');
        }


        $product = Product::updateOrCreate(
            ['id' => $this->productId],
            [
                'product_category_id' => $this->product_category_id,
                'name' => $this->name,
                'description' => $this->description,
                'sku' => $this->sku,
                'status' => $this->status == 'active' ? true : false,
                'price' => $this->price,
                'image' => $path,
            ]
        );

        if ($this->productId) {
            logActivity('product_update', $product);
        } else {
            logActivity('product_create', $product);
        }

        sweetalert()->success('Product saved successfully.');
        return redirect()->route('products.index');
    }


    public function updated($propertyName)
    {
        if (!$this->productId &&  $propertyName === 'name' && $this->name && $this->product_category_id) {
            $this->sku = $this->generateSKU();
        }
    }

    private function generateSKU()
    {
        // Fetch category and supplier names from their respective models
        $category = ProductCategory::find($this->product_category_id);
        // Get first 3 letters of each attribute
        $namePart = strtoupper(substr($this->name, 0, 3));
        $categoryPart = $category ? strtoupper(substr($category->name, 0, 3)) : 'XXX';

        // Generate a random 4-digit number to ensure uniqueness
        $randomNumber = rand(1000, 9999);

        // Combine parts to form SKU
        return "{$namePart}-{$categoryPart}-{$randomNumber}";
    }

    #[Title('Manage Product')]
    public function render()
    {
        return view('livewire.forms.product-form', [
            'categories' => ProductCategory::orderBy('name')->get(),
        ]);
    }
}
