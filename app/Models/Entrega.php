<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'cantidad', 'tipo_documento', 'numero_documento', 'razon_social', 'persona_entrega', 'persona_recibe', 'fecha_entrega', 'hora_entrega', 'observaciones'];

    // Relación con Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
