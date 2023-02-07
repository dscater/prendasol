<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContratoDetalle extends Model
{
    protected $table      = 'detalle_contrato';
    
    protected $fillable   = [
        'id',
	    'contrato_id',
	    'cantidad',
	    'descripcion',
	    'peso',
	    'dies',
	    'catorce',
	    'dieciocho',
	    'veinticuatro',
	    'usuario_id',
	    'estado_id',
		'foto'
    ];
}
