<?php

namespace App\Livewire;

use App\Models\Feedback;
use App\Models\FeedbackEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    public $chartData = [];
    public $topProducts = [];
    public $recentFeedbacks = [];
    public function mount()
    {
        $this->loadChartData();
        $this->loadTopProducts();
        $this->loadRecentFeedbacks();
    }

    public function loadRecentFeedbacks()
    {
        $this->recentFeedbacks = Feedback::with('log')
            ->latest()
            ->take(5)
            ->get();
    }
    public function loadChartData()
    {
        $now = Carbon::now();
        $weekStart = $now->copy()->startOfWeek();
        $monthStart = $now->copy()->startOfMonth();

        $statuses = ['pending', 'approved', 'rejected'];

        $this->chartData = [
            'labels' => ['Pending', 'Approved', 'Rejected'],
            'all' => collect($statuses)->map(fn($s) => FeedbackEntry::where('status', $s)->count())->toArray(),
            'week' => collect($statuses)->map(fn($s) => FeedbackEntry::where('status', $s)
                ->whereBetween('created_at', [$weekStart, $now])->count())->toArray(),
            'month' => collect($statuses)->map(fn($s) => FeedbackEntry::where('status', $s)
                ->whereBetween('created_at', [$monthStart, $now])->count())->toArray(),
        ];
    }

    public function loadTopProducts()
    {
        $this->topProducts = FeedbackEntry::select(
            'product_id',
            DB::raw('AVG(rating) as avg_rating'),
            DB::raw('COUNT(id) as total_reviews')
        )
            ->groupBy('product_id')
            ->orderByDesc('avg_rating')
            ->orderByDesc('total_reviews')
            ->with('product')
            ->take(5)
            ->get();
    }


    #[On('refresh-top-products')]
    public function refreshProducts()
    {
        $this->loadTopProducts();
    }
    #[On('refresh-feedback-chart')]
    public function refreshChart()
    {
        $this->loadChartData();
        $this->dispatch('chart-updated', chartData: $this->chartData);
    }

    #[Title('Dashboard')]
    public function render()
    {
        return view('livewire.dashboard');
    }
}
