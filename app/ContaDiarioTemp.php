<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContaDiarioTemp extends Model
{
    //
    protected $table      = 'conta_diario_temp';
    
    protected $fillable   = [
        'contrato_id',
        'pagos_id',
        'sucursal_id',
	    'periodo',
	    'fecha_a',
	    'fecha_b',
	    'glosa',
	    'cod_deno',
	    'cuenta',
	    'debe',
	    'haber',
	    'caja',
	    'ci',
	    'nom',
	    'num_comprobante',
	    'tcom',
	    'ref',
	    'usuario_id',
	    'estado_id',
    ];

    public function sucural(){
        return $this->belongsTo(Sucursal::class,'sucursal_id');
    }

    public function contrato1(){
        return $this->belongsTo(Contrato::class,'contrato_id');
    }
}
