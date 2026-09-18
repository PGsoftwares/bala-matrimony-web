<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrackProfile extends Mailable
{
    use Queueable, SerializesModels;

    protected User $user;
    protected $viewedCount;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $viewedCount)
    {
        $this->user = $user;
        $this->viewedCount = $viewedCount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Broker Alert - No of Profiles viewed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.mail.broker_profile',
            with: [
                'user' => $this->user,
                'viewedCount' => $this->viewedCount,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
