<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table      = 'modulo';
    
    protected $fillable   = [
        'modulo',
        'imagen',
        'usuario_id',
    	'estado_id'
    ];
}
