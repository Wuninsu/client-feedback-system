<?php

namespace App\Livewire\Main;

use App\Models\SentLog;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class SentLogIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function retry($id)
    {
        $log = SentLog::find($id);

        if ($log && $log->status === 'failed') {
            // Dispatch job or service to re-send here
            // For demo:
            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
            session()->flash('success', "Message resent to {$log->to}");
        }
    }

    public function retryAllFailed()
    {
        $logs = SentLog::where('status', 'failed')->get();

        foreach ($logs as $log) {
            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }

        session()->flash('success', "{$logs->count()} failed message(s) resent.");
    }

    #[Title('Sent Logs')]
    public function render()
    {
        $logs = SentLog::latest()
            ->when(
                $this->search,
                fn($q) =>
                $q->where('to', 'like', "%{$this->search}%")
                    ->orWhere('channel', 'like', "%{$this->search}%")
                    ->orWhere('status', 'like', "%{$this->search}%")
            )
            ->paginate(paginationLimit());

        return view('livewire.main.sent-log-index', compact('logs'));
    }
}
