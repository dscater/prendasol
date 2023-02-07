<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriaCliente extends Model
{
    protected $fillable = [
        'nombre','numero_contratos','porcentaje'
    ];

    public function clientes()
    {
        return $this->hasMany(ClienteCategoria::class,'categoria_id');
    }
}
