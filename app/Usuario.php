<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table      = 'usuario';

    protected $fillable   = [
        'persona_id',
        'usuario',
        'clave',
        'login_usu',
        'estado_id',
        'clave_texto'
    ];

    public function usuarioRol()
    {
        return $this->hasOne(UsuarioRol::class, 'usuario_id');
    }

    public function usuarioSucursal()
    {
        return $this->hasOne(SucursalUsuario::class, 'id_usuario');
    }

    public function nombreSucursal($id)
    {
        //return $this->belongsTo(Persona::class,'persona_id');
        return Sucursal::where('id', $id)->first();
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function cambios()
    {
        return $this->belongsTo(CambioDolar::class, 'usuario_id');
    }
}
