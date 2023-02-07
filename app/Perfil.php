<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table      = 'perfil';
    
    protected $fillable   = [
        'rol_id',
        'opcion_id',
        'usuario_id',
    	'estado_id'
    ];
}
