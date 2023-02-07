<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $table      = 'catalogos_generico';
    
    protected $fillable   = [
        'entidadsalud_id',
        'tabla_id',
        'catalogoid',
        'catalogodescripcion',
        'catalogovigente'
    ];
    
    public $timestamps    = false;
}
