<?php

namespace App\Livewire\Guest;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class ProductFeedback extends Component
{
    public $product;

    public function mount($product)
    {

        // $this->product = Product::with('feedbackEntries.feedback.customer')->where('uuid', $product)->firstOrFail();
        $this->product = Product::with([
            'feedbackEntries' => function ($query) {
                $query->where('status', 'approved')
                    ->with(['feedback.customer'])
                    ->latest();
            },
        ])->where('uuid', $product)
            ->firstOrFail();
    }
    public function render()
    {
        // dd($this->product);
        return view('livewire.guest.product-feedback');
    }
}
