<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $this->name = $mailData['name'];
        $this->email = $mailData['email'];
        $this->emailSubject = $mailData['subject'];
        $this->emailMessage = $mailData['message'];
        $this->ip = $mailData['ip'];

        $this->title = 'Contact Form';
        $this->content = emailContent('contact_email');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: emailSetting('email_noreply'),
            subject: $this->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'email_templates.contact',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->emailSubject,
                'message' => $this->emailMessage,
                'ip' => $this->ip,
                'title' => $this->title,
                'content' => $this->content,
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
