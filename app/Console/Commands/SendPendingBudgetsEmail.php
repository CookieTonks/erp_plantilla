<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Mail;

use Illuminate\Console\Command;
use App\Mail\PendingBudgetsMail;
use App\Models\Budget;


class SendPendingBudgetsEmail extends Command
{
    protected $signature = 'budgets:pending-send-email';
    protected $description = 'Envía un correo con la lista de presupuestos pendientes';

    public function handle()
    {
        $budgets = Budget::where('estado', 'PENDIENTE')->get();

        if ($budgets->isEmpty()) {
            $this->info('No hay presupuestos pendientes para enviar.');
            return;
        }

        $adminEmail = 'miriamdominguez.e@gmail.com';
        Mail::to($adminEmail)->send(new PendingBudgetsMail($budgets));
        $this->info("Correo enviado al admin: $adminEmail");

        $budgetsByUser = $budgets->groupBy('user.email');

        foreach ($budgetsByUser as $email => $userBudgets) {
            if ($email) {
                Mail::to($email)->send(new PendingBudgetsMail($userBudgets));
                $this->info("Correo enviado a vendedor: $email");
            } else {
                $this->info("Presupuesto sin usuario asignado.");
            }
        }

        $this->info('Correos enviados con éxito.');
    }
}
