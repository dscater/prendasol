<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Contrato extends Model
{
	protected $table      = 'contrato';

	protected $fillable   = [
		'cliente_id',
		'sucursal_id',
		'codigo',
		'peso_total',
		'fecha_contrato',
		'fecha_fin',
		'fecha_pago',
		'total_capital',
		'plazo',
		'cuota_mora',
		'capital',
		'p_interes',
		'interes',
		'comision',
		'gestion',
		'caja',
		'estado_pago',
		'estado_entrega',
		'estado_pago_2',
		'usuario_id',
		'estado_id',
		'totalTasacion',
		'codigo_num',
		'moneda_id'
	];

	public function sucural()
	{
		return $this->belongsTo(Sucursal::class, 'sucursal_id');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'usuario_id');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'cliente_id');
	}

	public function totalPesoNeto($id)
	{
		$tota10 = DB::table("detalle_contrato")->where('estado_id', 1)
			->where('contrato_id', $id)
			->sum('dies');
		$tota14 = DB::table("detalle_contrato")->where('estado_id', 1)
			->where('contrato_id', $id)
			->sum('catorce');
		$tota18 = DB::table("detalle_contrato")->where('estado_id', 1)
			->where('contrato_id', $id)
			->sum('dieciocho');
		$tota24 = DB::table("detalle_contrato")->where('estado_id', 1)
			->where('contrato_id', $id)
			->sum('veinticuatro');
		$res = $tota10 + $tota14 + $tota18 + $tota24;
		return $res;
	}

	public function detalle()
	{
		return $this->hasMany(ContratoDetalle::class, 'contrato_id');
	}

	public function moneda()
	{
		return $this->belongsTo(Moneda::class, 'moneda_id');
	}

	public function solicitud()
	{
		return $this->hasOne(SolicitudRetiro::class, 'contrato_id');
	}

	public static function ultimoNumero($sucursal)
	{

		$valor_comprueba_codigo = Contrato::compruebaCodigos($sucursal);
		if ($valor_comprueba_codigo != null) {
			return $valor_comprueba_codigo;
		}
		$anio = Carbon::now('America/La_Paz')->format('Y');

		// A PARTIR DE ESTA GESTIÓN SE INICIARA A REGISTRAR LOS CONTRATOS CON EL NUEVO CÓDIGO
		if ($sucursal == 10 || $sucursal == 11 || $sucursal == 12 || $sucursal == 13 || $sucursal == 14) {
			$ultimo = DB::select("SELECT SUBSTRING(codigo,9) as cod FROM `contrato`
			WHERE sucursal_id = $sucursal
			-- AND fecha_contrato LIKE '$anio-%'
			ORDER BY cod * 1 ASC");
		} else {
			$ultimo = DB::select("SELECT SUBSTRING(codigo,8) as cod FROM `contrato`
			WHERE sucursal_id = $sucursal
			-- AND fecha_contrato LIKE '$anio-%'
			ORDER BY cod * 1 ASC");
		}
		if ($ultimo) {
			$ultimo = $ultimo[count($ultimo) - 1];

			return (int)$ultimo->cod;
		}

		return null;
	}

	public static function compruebaCodigos($sucursal)
	{
		$anio = Carbon::now('America/La_Paz')->format('Y');

		if ($sucursal == 10) {
			$registros = DB::select("SELECT SUBSTRING(codigo,9) as cod FROM `contrato`
			WHERE sucursal_id = $sucursal
			-- AND fecha_contrato LIKE '$anio-%'
			ORDER BY cod * 1 ASC");
		} else {
			$registros = DB::select("SELECT SUBSTRING(codigo,8) as cod FROM `contrato`
			WHERE sucursal_id = $sucursal
			-- AND fecha_contrato LIKE '$anio-%'
			ORDER BY cod * 1 ASC");
		}

		$total_registros = count($registros);

		if ($total_registros > 0) {
			$array_codigos = [];
			$numero_inicial = 2001;
			$ultimo_numero = $registros[count($registros) - 1]->cod;

			// LLENAR EL ARRAY CON LOS CÓDIGOS QUE DEBERIAN EXISTIR
			for ($i = $numero_inicial; $i <= (int)$ultimo_numero; $i++) {
				$array_codigos[] = $i;
			}

			// LLENAR EL ARRAY CON LOS CÓDIGOS DE LOS REGISTROS
			$array_cod_reg = [];
			foreach ($registros as $registro) {
				$array_cod_reg[] = $registro->cod;
			}

			$no_existen = \array_diff($array_codigos, $array_cod_reg);
			$no_existen = array_values($no_existen);

			if (count($no_existen) > 0) {
				// SIEMPRE RETORNAR EL 1RO
				return (int)$no_existen[0] - 1;
			}
		}

		return null;
	}
}
