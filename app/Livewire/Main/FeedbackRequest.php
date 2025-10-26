<?php

namespace App\Livewire\Main;

use App\Mail\FeedbackRequestMail;
use App\Mail\SendRequest;
use App\Mail\ThankYouMail;
use App\Models\Feedback;
use App\Models\GuestUser;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\SentLog;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class FeedbackRequest extends Component
{
    use WithPagination;

    public $status = '';
    public $selectedProducts = [];
    public $userId;
    public $client_name, $client_contact;
    public $client_id = null;
    // For new feedback creation
    public $feedbackId = null;
    public $send_now = false;


    public $startDate;
    public $endDate;
    public $pendingCount = 0;

    protected $rules = [
        'selectedProducts' => 'required|array|min:1',
        'userId' => 'required|integer|exists:users,id',
    ];

    /**
     * Computed property: only feedbacks that exist
     */
    public function getFeedbacksProperty()
    {
        return Feedback::with('products')
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(10);
    }

    /**
     * Open modal to create feedback request
     */
    public function openModal()
    {
        $this->reset(['selectedProducts', 'client_name', 'client_contact', 'client_id', 'send_now']);
        $this->dispatch('show-modal');
    }

    public function openBulkModal()
    {
        $this->reset(['startDate', 'endDate']);
        $this->dispatch('show-bulk-modal');
    }

    public function createFeedback()
    {
        $this->validate([
            'client_contact' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'exists:products,id',
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
            $productNames = Product::whereIn('id', $this->selectedProducts)->pluck('name')->toArray();

            $feedback = Feedback::create([
                'user_id' => $user?->id,
                'guest_user_id' => $guest?->id,
                'feedback_category_id' => 1,
                'token' => $token,
                'client_name' => $this->client_name,
                'client_contact' => $this->client_contact,
            ]);

            $feedback->products()->attach($this->selectedProducts);

            // check if user to send request now
            if ($this->send_now) {
                // Attempt to send
                try {
                    if ($channel === 'email') {
                        // Mail::to($contact)->send(new FeedbackRequestMail($feedback, implode(', ', $productNames), $this->client_name));
                        Mail::to($contact)->send(new SendRequest(
                            clientName: $this->client_name,
                            messageContent: "We value your opinion! Please give your feedback on <strong>" . implode(', ', $productNames) . "</strong>",
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

                    SentLog::create([
                        'feedback_id' => $feedback->id,
                        'user_id' => auth('web')->id(),
                        'channel' => $channel,
                        'to' => $contact,
                        'message' => $message,
                        'status' => $status,
                        'sent_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error("Send failed: " . $e->getMessage());
                    $status = 'failed';
                }

            } else {
                if ($channel == 'email') {
                    $message = "We value your opinion! Please give your feedback on <strong>" . implode(', ', $productNames) . "</strong>";
                    Mail::to($contact)->send(new ThankYouMail(
                        $this->client_name,
                        "Thank you for doing business with us. We truly appreciate your trust and support, and we look forward to serving you again.",
                        'Thank You'
                    ));

                } else {
                    $message = "Hi {$this->client_name}, we value your opinion! Please click this link to share your feedback: {$link}";
                    $data = [
                        'phone' => $contact,
                        'message' => "Thank you for for choosing NI Ventures. We truly appreciate your trust and support, and we look forward to serving you again.",
                    ];
                    sendSms($data);
                }
                SentLog::create([
                    'feedback_id' => $feedback->id,
                    'user_id' => auth('web')->id(),
                    'channel' => $channel,
                    'to' => $contact,
                    'message' => $message,
                    'status' => 'pending',
                    'sent_at' => now(),
                ]);
            }
            $this->reset(['selectedProducts', 'client_name', 'client_contact', 'client_id', 'send_now']);
            sweetalert()->success('Feedback request created successfully');
            $this->dispatch('close-modal');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to send feedback request: " . $e->getMessage());

            $this->addError('send_error', 'Failed to send feedback request. Please try again.');
            return;
        }
    }


    /**
     * Send feedback request
     */
    public function sendSingleRequest($id)
    {
        $feedback = Feedback::with('log')->findOrFail($id);
        // retrieve the user info either from users or guest
        $feedLog = $feedback->log;
        if (!$feedLog) {
            flash('Can not send, feedback was not logged', 'error');
            return;
        }
        if (!$feedLog->channel == null && $feedLog->channel == 'email') {
            Mail::to($feedLog->to)->send(new FeedbackRequestMail($feedback, $feedLog->message));
        } else {
            $data = [
                'phone' => $feedLog->to,
                'message' => $feedLog->message,
            ];
            sendSMS($data);
        }
        $feedback->update(['status' => 'sent']);
        $feedLog->update(['status' => 'sent']);
        flash('Feedback request sent successfully!', 'success');
        session()->flash('success', 'Feedback request sent successfully!');
    }


    public function updated($property)
    {
        if (in_array($property, ['startDate', 'endDate'])) {
            $this->validate([
                'startDate' => 'required|date',
                'endDate' => 'date|after_or_equal:startDate',
            ]);
            $this->calculatePendingCount();
        }
    }

    protected function calculatePendingCount()
    {
        if (!$this->startDate || !$this->endDate) {
            $this->pendingCount = 0;
            return;
        }
        $this->pendingCount = Feedback::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'pending')
            ->count();
    }

    public function sendBulkRequests()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $feedbacks = Feedback::with('log')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'pending')
            ->get();

        if ($feedbacks->isEmpty()) {
            session()->flash('error', 'No pending feedback requests found in this date range.');
            return;
        }

        foreach ($feedbacks as $feedback) {
            $feedLog = $feedback->log;

            if (!$feedLog) {
                continue; // Skip if no log found
            }

            if ($feedLog->channel === 'email') {
                try {
                    Mail::to($feedLog->to)->send(new FeedbackRequestMail($feedback, $feedLog->message));
                } catch (\Exception $e) {
                    // You can log this or continue silently
                    Log::error('Email send failed: ' . $e->getMessage());
                    continue;
                }
            } else {
                try {
                    sendSMS([
                        'phone' => $feedLog->to,
                        'message' => $feedLog->message,
                    ]);
                } catch (\Exception $e) {
                    Log::error('SMS send failed: ' . $e->getMessage());
                    continue;
                }
            }

            $feedback->update(['status' => 'sent']);
            $feedLog->update(['status' => 'sent']);
        }
        $this->calculatePendingCount();
        $this->reset(['startDate', 'endDate']);
        $this->dispatch('close-bulk-modal');
        session()->flash('success', 'All pending feedback requests within this date range have been sent successfully.');
    }


    /**
     * When contact changes, try to auto-fill client name
     */
    public function updatedClientContact($value)
    {
        $value = trim($value);
        $this->reset(['client_name', 'client_id']);

        if (empty($value))
            return;

        $user = User::where('email', $value)
            ->orWhere('phone', $value)
            ->first();

        if ($user) {
            $this->client_name = $user->name;
            $this->client_id = $user->id;
            return;
        }

        $guest = GuestUser::where('email', $value)
            ->orWhere('phone', $value)
            ->first();

        if ($guest) {
            $this->client_name = $guest->name;
            $this->client_id = $guest->id;
            return;
        }

        // No record found, wait for user to manually fill name
        $this->client_name = '';
    }

    /**
     * Explicitly verify or create client when user clicks button
     */
    public function verifyClient()
    {
        $this->validate([
            'client_contact' => 'required|string|min:5',
            'client_name' => 'required|string|min:3',
        ]);

        $contact = trim($this->client_contact);

        // If already found, do nothing
        if ($this->client_id) {
            flash('Existing client verified successfully.', 'info');
            return;
        }

        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);

        try {
            $user = User::create([
                'username' => generateUsername($this->client_name),
                'name' => $this->client_name,
                'email' => $isEmail ? $contact : null,
                'phone' => !$isEmail ? $contact : null,
                'status' => true,
                'is_active' => true,
                'password' => Hash::make('test1234'),
            ]);

            if (Role::where('name', 'client')->exists()) {
                $user->assignRole('client');
            }

            $this->client_id = $user->id;

            flash('New client created successfully.', 'success', );
        } catch (\Exception $e) {
            $this->addError('client_contact', 'Error creating client. Try again.');
        }
    }

    #[Title('Feedback Request')]
    public function render()
    {
        return view('livewire.main.feedback-request', [
            'feedbacks' => $this->feedbacks,
            'products' => Product::where('status', true)->get(),
        ]);
    }
}
