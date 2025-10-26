<?php

namespace App\Livewire\Main;

use Livewire\Component;
use App\Models\Feedback;
use Carbon\Carbon;

class ReportsIndex extends Component
{

    public $startDate;
    public $endDate;
    public $status = 'all';

    public $feedbacks = [];
    public $summary = [];

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->loadReport();
    }

    public function updated($field)
    {
        if (in_array($field, ['startDate', 'endDate', 'status'])) {
            $this->validate([
                'startDate' => 'required|date',
                'endDate' => 'date|after_or_equal:startDate',
            ]);
            $this->loadReport();
        }
    }

    public function loadReport()
    {
        $query = Feedback::query()
            ->whereBetween('created_at', [$this->startDate, Carbon::parse($this->endDate)->endOfDay()]);

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $this->feedbacks = $query->latest()->get();

        $this->summary = [
            'total' => Feedback::count(),
            'pending' => Feedback::where('status', 'pending')->count(),
            'sent' => Feedback::where('status', 'sent')->count(),
            'reviewed' => Feedback::where('status', 'reviewed')->count(),
            'resolved' => Feedback::where('status', 'resolved')->count(),
        ];
    }
    public function render()
    {
        return view('livewire.main.reports-index');
    }
}
