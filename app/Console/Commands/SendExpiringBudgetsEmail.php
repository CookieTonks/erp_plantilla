<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\ExpiringBudgetsMail;
use App\Models\Budget;
use Illuminate\Support\Facades\Mail;

class SendExpiringBudgetsEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget:send-expiring-budgets-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $today = now();
        $nextWeek = now()->addDays(7);

        // Filtrar presupuestos que vencen en los próximos 7 días
        $budgets = Budget::where('estado', 'PENDIENTE')
            ->where('estado', '!=', 'ENTREGADA') // Excluir los que ya fueron entregados
            ->whereBetween('delivery_date', [$today, $nextWeek])
            ->get();



        if ($budgets->isEmpty()) {
            $this->info('No hay cotizaciones que entregar.');
            return;
        }

        $adminEmail = 'miriamdominguez.e@gmail.com';
        Mail::to($adminEmail)->send(new ExpiringBudgetsMail($budgets));
        $this->info("Correo enviado al admin: $adminEmail");

        $budgetsByUser = $budgets->groupBy('user.email');

        foreach ($budgetsByUser as $email => $userBudgets) {
            if ($email) {
                Mail::to($email)->send(new ExpiringBudgetsMail($userBudgets));
                $this->info("Correo enviado a vendedor: $email");
            } else {
                $this->info("Presupuesto sin usuario asignado.");
            }
        }
        $this->info('Correos de cotizacion próximos a vencer enviados con éxito.');
    }
}
