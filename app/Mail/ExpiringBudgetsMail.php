<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExpiringBudgetsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $budgets;


    public function __construct($budgets)
    {
        $this->budgets = $budgets;
    }

    public function build()
    {
        return $this->subject('Orden de compras próximas a vencer')
            ->markdown('emails.expiring_budgets');
    }
}
