<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudRetiro extends Model
{
    protected $fillable = [
        'contrato_id','sucursal_id','estado',
        'observaciones','fecha_solicitud'
    ];

    public function contrato(){
        return $this->belongsTo(Contrato::class,'contrato_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class,'sucursal_id');
    }
}
