<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table      = 'rol';
    
    protected $fillable   = [
        'rol',
        'usuario_id',
    	'estado_id'
    	//'updated_at',
    	//'created_at'
    ];
}
