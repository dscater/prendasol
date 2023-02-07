<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CambioDolar extends Model
{
    protected $fillable = [
        'sucursal_id', 'fecha', 'cliente',
        'nit', 'usuario_id', 'monto',
        'equivalencia', 'modo_cambio', 'compra_venta_id'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function compra_venta()
    {
        return $this->belongsTo(CompraVentaDolar::class, 'compra_venta_id');
    }
}
