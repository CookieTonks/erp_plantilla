<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrdenListaFacturar extends Mailable
{
    use Queueable, SerializesModels;

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function build()
    {
        return $this->subject('Partida de OC lista para facturar')
            ->markdown('emails.orden_lista_facturar')
            ->with([
                'oc' => $this->item->budget->oc_number,  // Obtiene el número de OC desde el presupuesto
                'partida' => $this->item->id,
                'vendedor' => $this->item->budget->clientUser?->name,  // Usa el operador seguro para evitar errores si está vacío
                'cliente' => $this->item->budget->client?->name,
                'fecha' => now()->format('d/m/Y'),
            ]);
    }
}
