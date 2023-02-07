<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table      = 'sucursal';

    protected $fillable   = [
        'nombre',
        'codigo',
        'nuevo_codigo',
        'genera_codigo',
        'ciudad',
        'direccion',
        'zona',
        'referencia',
        'estado_id',
        'codigo_inicial'
    ];

    public function solicituds()
    {
        return $this->hasMany(SolicitudRetiro::class, 'sucursal_id');
    }

    public function cambios()
    {
        return $this->hasMany(CambioDolar::class, 'sucursal_id');
    }
}
