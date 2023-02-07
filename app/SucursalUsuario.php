<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SucursalUsuario extends Model
{
    protected $table      = 'sucursal_usuario';
    
    protected $fillable   = [
        'id_usuario',
        'id_sucursal',
        'caja',
    	'estado_id',
    ];

    public function sucursal(){
        return $this->belongsTo(Sucursal::class,'id_sucursal');
    }

    public function usuario(){
        return $this->belongsTo(Usuario::class,'id_usuario');
    }

    public function personaDatos($id)
    {
    	return Persona::where('id',$id)->first();
    }

    public function usuarioDatos($id)
    {
    	return Usuario::where('id',6409)->first();
    }


}
