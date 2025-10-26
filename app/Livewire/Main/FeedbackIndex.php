<?php

namespace App\Livewire\Main;

use App\Models\Feedback;
use App\Models\FeedbackEntry;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class FeedbackIndex extends Component
{
    use WithPagination;
    // Modal variables
    public $feedbackId = null;
    public $status = '';

    // For delete
    public $deleteId = null;
    /**
     * Computed property to get filtered feedback
     */
    public function getFeedbacksProperty()
    {
        return Feedback::query()
            ->whereHas('entries') // Only include feedbacks with at least one entry
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(paginationLimit());
    }


    public function confirmDelete($uuid)
    {
        $this->dispatch('confirm', uuid: $uuid);
    }

    #[On('delete')]
    public function handleDelete($uuid)
    {
        $feedback = Feedback::find($uuid);
        if ($feedback) {
            logActivity('feedback_delete', $feedback);
            $feedback->delete();
            sweetalert()->success('Feedback deleted successfully.');
        } else {
            sweetalert()->error('Feedback not found.');
        }
    }


    #[Title('Feedbacks')]
    public function render()
    {
        return view('livewire.main.feedback-index');
    }
}
