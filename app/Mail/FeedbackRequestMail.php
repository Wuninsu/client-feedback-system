<?php

namespace App\Mail;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeedbackRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $feedback;
    public $messageContent;

    public function __construct(Feedback $feedback, $messageContent)
    {
        $this->feedback = $feedback;
        $this->messageContent = $messageContent;
    }

    public function build()
    {
        return $this->subject('We value your feedback')
            ->markdown('emails.feedback-request')
            ->with([
                'messageContent' => $this->messageContent,
                'feedbackLink' => route('feedback.form', $this->feedback->token),
            ]);
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Feedback Request Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         markdown: 'emails.feedback-request',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
