<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlanPaymentAdminSuccess extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $this->name = $mailData['name'];
        $this->email = $mailData['email'];
        $this->ip = $mailData['ip'];
        $this->plan = $mailData['plan'];
        $this->period = $mailData['period'];
        $this->gateway = $mailData['gateway'];
        $this->revenue = $mailData['revenue'];

        $this->title = 'A plan was purchased';
        $this->content = emailContent('plan_payment_admin_success');
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
            markdown: 'email_templates.plan_payment_admin_success',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'ip' => $this->ip,
                'plan' => $this->plan,
                'period' => $this->period,
                'gateway' => $this->gateway,
                'revenue' => $this->revenue,
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
