<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Budget;
use Carbon\Carbon;


class UpdateBudgetsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budgets:update-status';

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
        // Buscar presupuestos con más de 3 días y estado ABIERTA
        $budgets = Budget::where('created_at', '<', Carbon::now()->subDays(3))
            ->where('estado', 'ABIERTA')
            ->get();

        if ($budgets->isEmpty()) {
            $this->info('No hay presupuestos para actualizar.');
            return;
        }

        // Cambiar estado a PENDIENTE
        foreach ($budgets as $budget) {
            $budget->estado = 'PENDIENTE';
            $budget->save();
        }

        $this->info('Presupuestos actualizados correctamente.');
    }
}
