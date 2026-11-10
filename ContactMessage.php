<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $body,
        public string $portfolioName,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [new Address($this->senderEmail, $this->senderName)],
            subject: 'Nouveau message via votre portfolio — '.$this->senderName,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact',
            with: [
                'senderName' => $this->senderName,
                'senderEmail' => $this->senderEmail,
                'body' => $this->body,
                'portfolioName' => $this->portfolioName,
            ],
        );
    }

    /**
     * On attache l'image pour l'embarquer dans le mail
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(public_path('images/nexora-logo.png'))
                ->as('nexora-logo.png')
                ->withMime('image/png'),
        ];
    }
}