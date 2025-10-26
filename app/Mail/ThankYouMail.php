<?php

namespace App\Mail;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $messageContent;
    public $subject;

    public function __construct(string $name, string $message, string $subject = 'Thank You for Your Feedback')
    {
        $this->messageContent = $message;
        $this->name = $name;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->markdown('emails.feedback-thankyou')
            ->with([
                'message' => $this->messageContent,
                'name' => $this->name,
            ]);
    }


    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: $this->subject,
    //     );
    // }

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
