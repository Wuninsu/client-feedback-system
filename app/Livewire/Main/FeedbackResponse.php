<?php

namespace App\Livewire\Main;

use App\Models\Feedback;
use App\Models\FeedbackEntry;
use Livewire\Component;

class FeedbackResponse extends Component
{
    public $data;
    public $message;

    public function mount($token)
    {
        $this->data = Feedback::with(['products', 'entries.product', 'responses.responder'])
            ->where('token', $token)
            ->firstOrFail();
    }

    public function addResponse()
    {
        $this->validate([
            'message' => 'required|string|min:3'
        ]);

        $this->data->responses()->create([
            'feedback_id' => $this->data->id,
            'user_id' => auth('web')->id(),
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->data->refresh();

        flash('Response added successfully!', 'success');
    }


    public function updateStatus($entryId, $status)
    {
        $entry = $this->data->entries()->find($entryId);

        if ($entry) {
            $entry->update(['status' => $status]);
            // Refresh entries after update
            $this->data->refresh();
            flash('Status updated', 'success');
        }
    }

    public function render()
    {
        return view('livewire.main.feedback-response');
    }
}
