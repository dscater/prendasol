<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompraVentaDolar extends Model
{
    protected $fillable = [
        'venta_sus', 'venta_bs', 'compra_sus',
        'compra_bs',
    ];

    public function cambios()
    {
        return $this->hasMany(CambioDolar::class, 'compra_venta_id');
    }
}
