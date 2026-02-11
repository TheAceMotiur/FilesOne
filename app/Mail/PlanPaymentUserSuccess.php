<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlanPaymentUserSuccess extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $this->plan = $mailData['plan'];
        $this->period = $mailData['period'];
        $this->gateway = $mailData['gateway'];
        $this->payment = $mailData['payment'];
        $this->start = $mailData['start'];
        $this->end = $mailData['end'];

        $this->title = 'You paid for a plan';
        $this->content = emailContent('plan_payment_user_success');
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
            markdown: 'email_templates.plan_payment_user_success',
            with: [
                'plan' => $this->plan,
                'period' => $this->period,
                'gateway' => $this->gateway,
                'payment' => $this->payment,
                'start' => $this->start,
                'end' => $this->end,
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
