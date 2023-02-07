<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteCategoria extends Model
{
    protected $fillable = [
        'cliente_id',
        'categoria_id'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaCliente::class,'categoria_id');
    }
}
