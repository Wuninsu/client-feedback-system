<?php

namespace App\Livewire\Forms;

use App\Mail\FeedbackRequestMail;
use App\Mail\SendRequest;
use App\Models\Feedback;
use App\Models\GuestUser;
use App\Models\Product;
use App\Models\SentLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class SendFeedbackRequest extends Component
{
    public $client_name, $client_contact;
    // In SendFeedbackRequest.php
    public $product_ids = []; // instead of $product_id

    public function send()
    {
        $this->validate([
            'client_contact' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
        ]);

        $contact = $this->client_contact;
        $channel = filter_var($contact, FILTER_VALIDATE_EMAIL) ? 'email' : 'sms';
        $user = User::where('email', $contact)->orWhere('phone', $contact)->first();
        $guest = GuestUser::where('email', $contact)->orWhere('phone', $contact)->first();

        DB::beginTransaction();

        try {
            if (!$user && !$guest) {
                $guest = GuestUser::create([
                    'name' => $this->client_name,
                    'email' => filter_var($contact, FILTER_VALIDATE_EMAIL) ? $contact : null,
                    'phone' => !filter_var($contact, FILTER_VALIDATE_EMAIL) ? $contact : null,
                ]);
            }

            $token = Str::uuid()->toString();
            $link = route('feedback.form', $token);
            $productNames = Product::whereIn('id', $this->product_ids)->pluck('name')->toArray();

            $message = "Hello {$this->client_name}, we value your opinion! Please give your feedback on " . implode(', ', $productNames) . ": {$link}";

            $feedback = Feedback::create([
                'user_id' => $user?->id,
                'guest_user_id' => $guest?->id,
                'feedback_category_id' => 1,
                'token' => $token,
                'client_name' => $this->client_name,
                'client_contact' => $this->client_contact,
            ]);

            $feedback->products()->attach($this->product_ids);

            // Attempt to send
            try {
                if ($channel === 'email') {
                    Mail::to($contact)->send(new SendRequest(
                        clientName: $this->client_name,
                        messageContent: "Hello {$this->client_name}, we value your opinion! Please give your feedback on " . implode(', ', $productNames) . ": {$link}",
                        feedbackLink: $link
                    ));
                } else {
                    $message = "Hi {$this->client_name}, we value your opinion! Please click this link to share your feedback: {$link}";
                    $data = [
                        'phone' => $contact,
                        'message' => $message,
                    ];
                    sendSMS($data);
                }

                $status = 'sent';
                $feedback->status = 'sent';
                $feedback->save();
            } catch (\Exception $e) {
                Log::error("Send failed: " . $e->getMessage());
                $status = 'failed';
            }

            SentLog::create([
                'feedback_id' => $feedback->id,
                'user_id' => auth('web')->id(),
                'channel' => $channel,
                'to' => $contact,
                'message' => $message,
                'status' => $status,
                'sent_at' => now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to send feedback request: " . $e->getMessage());

            $this->addError('send_error', 'Failed to send feedback request. Please try again.');
            return;
        }

        sweetalert()->success('Feedback request sent successfully');
        $this->reset(['client_name', 'client_contact', 'product_ids']);
        $this->dispatch('feedback-sent');
        $this->dispatch('close-modal');
    }

    public function updatedClientContact($value)
    {
        $value = trim($value);

        // Try to find in users table first
        $user = User::where('email', $value)->orWhere('phone', $value)->first();

        if ($user) {
            $this->client_name = $user->name;
            return;
        }

        // If not found, try guest users
        $guest = GuestUser::where('email', $value)->orWhere('phone', $value)->first();

        if ($guest) {
            $this->client_name = $guest->name;
            return;
        }

        // If not found in both, clear name
        $this->client_name = '';
    }
    public function render()
    {
        return view('livewire.forms.send-feedback-request', [
            'products' => Product::all(),
        ]);
    }
}
