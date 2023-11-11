<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlazoPago extends Model
{
    protected $fillable = [
        "contrato_id",
        "descripcion",
        "fecha_proximo_pago",
    ];

    protected $appends = ["fecha_proximo_pago_t"];

    public function getFechaProximoPagoTAttribute()
    {
        return date("d-m-Y", strtotime($this->fecha_proximo_pago));
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }
}
