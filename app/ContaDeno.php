<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContaDeno extends Model
{
    protected $table      = 'conta_deno';
    
    protected $fillable   = [
        'id',
        'tipo',
        'descripcion',
    	'cod_deno'
    ];
}
