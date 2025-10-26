<?php

namespace App\Livewire\Guest;

use App\Models\Feedback;
use App\Models\FeedbackEntry;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Volt\Compilers\Mount;

#[Layout('components.layouts.guest')]
class FeedbackList extends Component
{
    public $selectedProduct = 'all';
    public $selectedRating = 'all';
    public $productsData;

    public function mount()
    {
        $this->productsData = Product::where('status', true)->get();
    }

    public function render()
    {
        // Fetch products with average rating
        $products = Product::withAvg('feedbackEntries as average_rating', 'rating')
            ->when($this->selectedProduct !== 'all', fn($q) => $q->where('id', $this->selectedProduct))
            ->withCount('feedbackEntries as ratings_count')->get();
        // dd($products);
        // Get approved feedback entries with filters
        // $feedbackEntries = FeedbackEntry::with('product')
        //     ->when($this->selectedProduct !== 'all', fn($q) => $q->where('product_id', $this->selectedProduct))
        //     ->when($this->selectedRating !== 'all', fn($q) => $q->where('rating', $this->selectedRating))
        //     ->where('status', 'Approved')
        //     ->latest()
        //     ->get();

        return view('livewire.guest.feedback-list', compact('products'));
    }

}
