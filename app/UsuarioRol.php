<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    protected $table      = 'usuario_rol';
    
    protected $fillable   = [
        'usuario_id',
        'rol_id',        
        'login_usu',
    	'estado_id'
    ];
    
    

    public function rol(){
        return $this->belongsTo(Rol::class);
    }
}
