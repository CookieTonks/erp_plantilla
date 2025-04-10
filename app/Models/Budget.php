<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'estado',
        'codigo',
        'moneda',
        'monto',
        'oc_number',
        'delivery_time',
        'client_user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class); // Relación con partidas
    }

    public function clientUser()
    {
        return $this->belongsTo(ClientUser::class, 'client_user_id');
    }
    public function materiales()
    {
        return $this->hasMany(Material::class);
    }

    public function proceso()
    {
        return $this->hasOne(Proceso::class);
    }
}
