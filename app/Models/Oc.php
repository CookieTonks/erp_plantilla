<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oc extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'moneda',
        'supplier_id',
        'estatus',
    ];

    public function materials()
    {
        return $this->hasMany(Material::class, 'oc_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
