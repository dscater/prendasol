<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Session;

class InicioFinCaja extends Model
{
	protected $table      = 'inicio_fin_caja';

	protected $fillable   = [
		'id',
		'sucursal_id',
		'caja',
		'fecha',
		'fecha_hora',
		'fecha_cierre',
		'inicio_caja_bs',
		'fin_caja_bs',
		'inicio_caja_s',
		'fin_caja_s',
		'ingreso_bs',
		'ingreso_s',
		'egreso_bs',
		'egreso_s',
		'tipo_de_movimiento',
		'usuarioIniciado',
		'usuario_id',
		'estado_id',
		'moneda_id'
	];

	public function sucural1()
	{
		return $this->belongsTo(Sucursal::class, 'sucursal_id');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'usuario_id');
	}

	public static function detalleIniciFinCaja($id)
	{
		$datosDetalle = InicioFinCajaDetalle::where('inicio_fin_caja_id', $id)
			->where('estado_id', 1)
			->orderBy('id', 'ASC')->get();
		return $datosDetalle;
	}

	public function moneda()
	{
		return $this->belongsTo(Moneda::class, 'moneda_id');
	}

	public static function iniciaCajaCentral()
	{
		$fecha_actual = Carbon::now('America/La_Paz')->format('Y-m-d');
		$sucursal_id = 15;
		$caja_id = 151;
		$usuario_id = 1;
		$role_id = session::get('ID_ROL');
		if ($role_id != 1) {
			// asignar el id del primer usuario administrador
			$usuario_rol_primero = UsuarioRol::select("usuario_rol.*")
				->join("usuario", "usuario.id", "=", "usuario_rol.usuario_id")
				->where("rol_id", 1)
				->where("usuario.estado_id", 1)
				->get()
				->first();
			$usuario_id = $usuario_rol_primero->usuario_id;
		} else {
			$usuario_id = session::get('ID_USUARIO');
		}

		// verificar la existencia de un inicio de caja
		$countInicioFinCaja =  InicioFinCaja::where('fecha', $fecha_actual)
			->where('sucursal_id', $sucursal_id)
			->where('caja', $caja_id)
			->whereIn('estado_id', [1, 2])
			->count();
		if ($countInicioFinCaja > 0) {
			// caja iniciada
			// verificar el estado de la fecha,hora
			$datoInicioFinCaja =  InicioFinCaja::where('fecha', $fecha_actual)
				->where('sucursal_id', $sucursal_id)
				->where('caja', $caja_id)
				->whereIn('estado_id', [1, 2])
				->first();
			if (!$datoInicioFinCaja->fecha_hora) {
				$inicioCaja = InicioFinCaja::find($datoInicioFinCaja->id);
				$inicioCaja->fecha_hora                  = Carbon::now('America/La_Paz');
				$inicioCaja->usuarioIniciado             = $usuario_id;
				$inicioCaja->save();
			}
		} else {
			// iniciar caja
			$inicioCaja = DB::table('inicio_fin_caja')->where('sucursal_id', $sucursal_id)
				->where('caja', $caja_id)
				->where('estado_id', 1)
				->max('id');

			$inicioCajaDetalle = DB::table('inicio_fin_caja_detalle')->where('sucursal_id', $sucursal_id)
				->where('caja', $caja_id)
				->where('inicio_fin_caja_id', $inicioCaja)
				->where('estado_id', 1)
				->max('id');
			if ($inicioCajaDetalle) {
				$datoInicioCajaDetalle = InicioFinCajaDetalle::where('id', $inicioCajaDetalle)->first();
				$inicioCaja = InicioFinCaja::find($inicioCaja);
				$inicioCaja->fecha_cierre               = Carbon::now('America/La_Paz');
				$inicioCaja->fin_caja_bs                = $datoInicioCajaDetalle->inicio_caja_bs;
				$inicioCaja->estado_id                  = 1;
				$inicioCaja->usuario_id                 = $usuario_id;
				$inicioCaja->save();

				InicioFinCaja::create([
					'sucursal_id'          => $sucursal_id,
					'fecha'                => $fecha_actual, //Carbon::parse($resFechaProximo)->format('Y-m-d'),
					'fecha_hora'		   => Carbon::now('America/La_Paz'),
					'inicio_caja_bs'       => $datoInicioCajaDetalle->inicio_caja_bs,
					'caja'                 => $caja_id,
					'tipo_de_movimiento'   => 'Inicio de caja realizado por',
					'estado_id'            => 1,
					'usuario_id'           => $usuario_id,
					'usuarioIniciado'      => $usuario_id,
					'moneda_id'            => 1
				]);
			} else {
				$datoInicioCaja = InicioFinCaja::where('id', $inicioCaja)->first();
				//dd($datoInicioCaja);

				$inicioCaja = InicioFinCaja::find($inicioCaja);
				$inicioCaja->fecha_cierre                  = Carbon::now('America/La_Paz');
				$inicioCaja->fin_caja_bs                   = $datoInicioCaja->inicio_caja_bs;
				$inicioCaja->estado_id                     = 1;
				$inicioCaja->usuario_id                    = $usuario_id;
				$inicioCaja->save();
				//dd($datoInicioCaja);
				InicioFinCaja::create([
					'sucursal_id'          => $sucursal_id,
					'fecha'                => $fecha_actual, //Carbon::parse($resFechaProximo)->format('Y-m-d'),
					'fecha_hora' => Carbon::now('America/La_Paz'),
					'inicio_caja_bs'       => $datoInicioCaja->inicio_caja_bs,
					'caja'                 => $caja_id,
					'tipo_de_movimiento'   => 'Inicio de caja realizado por',
					'estado_id'            => 1,
					'usuario_id'           => $usuario_id,
					'usuarioIniciado' => $usuario_id,
					'moneda_id'            => 1,
				]);
			}
		}
		return true;
	}
}
