<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InicioFinCajaDetalle extends Model
{
	protected $table      = 'inicio_fin_caja_detalle';

	protected $fillable   = [
		'id',
		'inicio_fin_caja_id',
		'contrato_id',
		'pago_id',
		'sucursal_id',
		'persona_id',
		'cod_caja_n',
		'fecha_pago',
		'fecha_hora',
		'inicio_caja_bs',
		'fin_caja_bs',
		'inicio_caja_s',
		'fin_caja_s',
		'caja',
		'ingreso_bs',
		'ingreso_s',
		'egreso_bs',
		'egreso_s',
		'tipo_de_movimiento',
		'ref',
		'gestion',
		'correlativo',
		'usuario_id',
		'estado_id',
		'moneda_id'
	];

	public function contrato()
	{
		return $this->belongsTo(Contrato::class, 'contrato_id');
	}

	public function persona()
	{
		return $this->belongsTo(Persona::class, 'persona_id');
	}

	public function sucursal()
	{
		return $this->belongsTo(Sucursal::class, 'sucursal_id');
	}

	public function moneda()
	{
		return $this->belongsTo(Moneda::class, 'moneda_id');
	}

	public function pago()
	{
		return $this->belongsTo(Pagos::class, 'pago_id');
	}
}
