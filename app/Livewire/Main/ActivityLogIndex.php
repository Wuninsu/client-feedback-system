<?php

namespace App\Livewire\Main;

use App\Models\ActivityLog;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    #[Title('Activity Logs')]
    public function render()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->when($this->search, function ($query) {
                $query->where('action', 'like', '%' . $this->search . '%')
                    ->orWhere('model_type', 'like', '%' . $this->search . '%')
                    ->orWhereHas(
                        'user',
                        fn($q) =>
                        $q->where('name', 'like', '%' . $this->search . '%')
                    );
            })
            ->paginate(paginationLimit());

        return view('livewire.main.activity-log-index', [
            'logs' => $logs,
        ]);
    }
}
