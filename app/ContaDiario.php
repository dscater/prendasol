<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContaDiario extends Model
{
	protected $table      = 'conta_diario';

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
		'num_comprobante',
		'ci',
		'nom',
		'tcom',
		'ref',
		'gestion',
		'correlativo',
		'usuario_id',
		'estado_id',
	];

	public function sucural()
	{
		return $this->belongsTo(Sucursal::class, 'sucursal_id');
	}

	public function contrato1()
	{
		return $this->belongsTo(Contrato::class, 'contrato_id');
	}

	public function pago()
	{
		return $this->belongsTo(Pagos::class, 'pagos_id');
	}
}
