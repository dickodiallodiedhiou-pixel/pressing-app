<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $pdfData;

    public function __construct(Ticket $ticket, $pdfData)
    {
        $this->ticket = $ticket;
        $this->pdfData = $pdfData;
    }

    public function build()
    {
        return $this->subject('Votre commande est prête ! - Pressing LIC')
                    ->view('emails.order_ready')
                    ->attachData($this->pdfData, 'recu_pressing_'.$this->ticket->id.'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Ready Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
