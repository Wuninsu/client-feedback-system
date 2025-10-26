<?php

namespace App\Livewire\Guest;

use App\Mail\ThankYouMail;
use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\FeedbackEntry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class FeedbackForm extends Component
{
    public string $token;
    public array $products = [];
    public array $feedbackCategories = [];
    public int $currentStep = 0;
    public array $feedbackEntries = [];

    public Feedback $feedback;

    public function mount(string $token)
    {
        $this->token = $token;

        $this->feedback = Feedback::where('token', $token)->firstOrFail();
        $this->products = $this->feedback->products()->get()->all();
        $this->feedbackCategories = FeedbackCategory::all()->toArray();

        foreach ($this->products as $product) {
            $this->feedbackEntries[$product->id] = [
                'rating' => null,
                'comment' => '',
                'feedback_category' => null,
            ];
        }
    }

    public function submitCurrent()
    {
        $product = $this->products[$this->currentStep];
        $entry = $this->feedbackEntries[$product->id];

        // dd($entry);
        $this->validate([
            "feedbackEntries.{$product->id}.rating" => 'required|integer|min:1|max:5',
            "feedbackEntries.{$product->id}.feedback_category" => 'required|exists:feedback_categories,name',
            "feedbackEntries.{$product->id}.comment" => 'nullable|string|max:1000',
        ], [
            "feedbackEntries.{$product->id}.rating" => 'Rating is required',
            "feedbackEntries.{$product->id}.feedback_category" => 'Feedback category is required'
        ]);

        FeedbackEntry::updateOrCreate(
            [
                'feedback_id' => $this->feedback->id,
                'product_id' => $product->id,
            ],
            [
                'rating' => $entry['rating'],
                'comment' => $entry['comment'],
                'feedback_category' => $entry['feedback_category'],
                'status' => 'pending',
            ]
        );

        // Move to next or finish
        if ($this->currentStep < count($this->products) - 1) {
            $this->currentStep++;
        } else {
            try {
                $this->feedback->update(['status' => 'reviewed']);

                $message = "Thank you for your valuable feedback. Your insights help us improve our services.";

                $user = $this->feedback->user;

                if (!empty($user->email)) {
                    Mail::to($user->email)->send(new ThankYouMail(
                        $user->name ?? 'Client',
                        $message,
                    ));
                } else {
                    $phone = $user->phone;
                    $data = [
                        'phone' => $phone,
                        'message' => $message,
                    ];
                    sendSms($data);
                }
            } catch (\Exception $e) {
                // Log the error or handle it as needed
                Log::error('Feedback response error: ' . $e->getMessage());
                session()->flash('error', $e->getMessage());
            }

            session()->flash('success', 'Thanks for submitting your feedback!');
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 0) {
            $this->currentStep--;
        }
    }

    public function skipCurrent()
    {
        if ($this->currentStep < count($this->products) - 1) {
            $this->currentStep++;
        } else {
            session()->flash('success', 'Thanks for completing your feedback!');
        }
    }


    // public function submitFeedback()
    // {
    //     foreach ($this->feedbackEntries as $productId => $entry) {
    //         // Store each feedback entry
    //         \App\Models\FeedbackItem::create([
    //             'feedback_id' => $this->feedback->id,
    //             'product_id' => $productId,
    //             'rating' => $entry['rating'],
    //             'comment' => $entry['comment'],
    //         ]);
    //     }

    //     // You can also optionally store name/email if you want
    //     $this->reset(['feedbackEntries', 'client_name', 'client_email']);
    //     $this->dispatch('feedback-submitted');
    // }
    public function render()
    {
        return view('livewire.guest.feedback-form');
    }
}
