<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogSeguimiento extends Model
{
    protected $table      = 'log_seguimiento';
    
    protected $fillable   = [
        'usuario_id',        
        'metodo',
    	'accion',
    	'detalle',
    	'modulo',
    	'consulta'
    ];
}
