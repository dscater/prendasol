<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opciones extends Model
{
    protected $table      = 'opciones';
    
    protected $fillable   = [
        'modulo_id',
        'opcion',
        'url',
        'imagen',
        'orden',
        'usuario_id',
    	'estado_id'
    ];
    
    //public $timestamps    = false;

    public function modulo(){
        return $this->belongsTo(Modulo::class);
    }
}
