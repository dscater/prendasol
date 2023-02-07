<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table      = 'cliente';
    
    protected $fillable   = [
        'persona_id',
        'codigo',
        'fecha_ingreso',
    	'estado_id'
    ];

    public function persona(){
        return $this->belongsTo(Persona::class,'persona_id');
    } 

    public function contratos()
    {
        return $this->hasMany(Contrato::class,'cliente_id');
    }

    public function categoria()
    {
        return $this->hasOne(ClienteCategoria::class,'cliente_id');
    }
}
