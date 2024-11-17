<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AffiliateUpdatePositive extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

     private $storeName;
     private $promoterName;

    public function __construct($storeName, $promoterName)
    {
        //
        $this->storeName = $storeName;
        $this->promoterName = $promoterName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Affiliate Update',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.affiliate-update-positive',
            with:[
                'storeName' => $this->storeName,
                'promoterName' => $this->promoterName
            ]
        );
    }

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
