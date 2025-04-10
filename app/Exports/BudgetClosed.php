<?php

namespace App\Exports;

use App\Models\Budget;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class BudgetClosed implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Obtiene la colección de datos a exportar.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Budget::with('client', 'clientUser', 'user')
            ->where('estado', 'CERRADA')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
    }

    /**
     * Define los encabezados del archivo Excel.
     * @return array
     */
    public function headings(): array
    {
        return ['ID', 'Código', 'Monto', 'Estatus', 'Vendedor', 'Cliente', 'Usuario', 'Fecha de Creación'];
    }

    /**
     * Mapea los datos para que coincidan con los encabezados.
     * @param mixed $budget
     * @return array
     */
    public function map($budget): array
    {
        return [
            $budget->id,
            $budget->codigo,
            $budget->monto,
            $budget->estado,
            $budget->user->name ?? 'Sin Vendedor',
            $budget->client->name ?? 'Sin Cliente',
            $budget->clientUser->name ?? 'Sin Usuario',
            $budget->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
