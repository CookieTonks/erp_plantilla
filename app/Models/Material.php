<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'nombre',
        'cantidad',
        'precio_unitario',
        'unidad',
        'medida',
        'estatus',
        'descripcion',
        'oc_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function oc()
    {
        return $this->belongsTo(Oc::class);
    }
}
