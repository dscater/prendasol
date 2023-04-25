<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\InicioFinCaja;
use App\InicioFinCajaDetalle;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\DB;

class InicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        InicioFinCaja::iniciaCajaCentral();

        if (session::get('ID_ROL') == 2) {
            $opciones = 1;
            $countInicioFinCaja =  InicioFinCaja::where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->whereIn('estado_id', [1, 2])
                ->count();
            //dd(Carbon::now('America/La_Paz')->format('Y-m-d'));
            if ($countInicioFinCaja > 0) {
                $datoInicioFinCaja =  InicioFinCaja::where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                    ->where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->whereIn('estado_id', [1, 2])
                    ->first();
                //dd($datoInicioFinCaja->fecha_hora);
                if (!$datoInicioFinCaja->fecha_hora) {
                    //dd($$datoInicioFinCaja);
                    $inicioCaja = InicioFinCaja::find($datoInicioFinCaja->id);
                    $inicioCaja->fecha_hora                  = Carbon::now('America/La_Paz');
                    $inicioCaja->usuarioIniciado             = session::get('ID_USUARIO');
                    $inicioCaja->save();
                }
            } else {
                $inicioCaja = DB::table('inicio_fin_caja')->where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('estado_id', 1)
                    ->max('id');
                //dd($inicioCaja);
                $inicioCajaDetalle = DB::table('inicio_fin_caja_detalle')->where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('inicio_fin_caja_id', $inicioCaja)
                    ->where('estado_id', 1)
                    ->max('id');
                //dd($inicioCajaDetalle);
                $fecha_actual = Carbon::now('America/La_Paz')->format('Y-m-d');
                if ($inicioCajaDetalle) {
                    //dd($inicioCajaDetalle);
                    $datoInicioCajaDetalle = InicioFinCajaDetalle::where('id', $inicioCajaDetalle)->first();
                    //dd($datoInicioCajaDetalle);

                    $inicioCaja = InicioFinCaja::find($inicioCaja);
                    $inicioCaja->fecha_cierre               = Carbon::now('America/La_Paz');
                    $inicioCaja->fin_caja_bs                = $datoInicioCajaDetalle->inicio_caja_bs;
                    $inicioCaja->estado_id                  = 1;
                    $inicioCaja->usuario_id                 = session::get('ID_USUARIO');
                    $inicioCaja->save();

                    InicioFinCaja::create([
                        'sucursal_id'          => session::get('ID_SUCURSAL'),
                        'fecha'                => $fecha_actual, //Carbon::parse($resFechaProximo)->format('Y-m-d'),
                        'inicio_caja_bs'       => $datoInicioCajaDetalle->inicio_caja_bs,
                        'caja'                 => session::get('CAJA'),
                        'tipo_de_movimiento'   => 'Inicio de caja realizado por',
                        'estado_id'            => 1,
                        'usuario_id'           => session::get('ID_USUARIO'),
                        'moneda_id'            => 1
                    ]);
                } else {
                    $datoInicioCaja = InicioFinCaja::where('id', $inicioCaja)->first();
                    //dd($datoInicioCaja);

                    $inicioCaja = InicioFinCaja::find($inicioCaja);
                    $inicioCaja->fecha_cierre                  = Carbon::now('America/La_Paz');
                    $inicioCaja->fin_caja_bs                   = $datoInicioCaja->inicio_caja_bs;
                    $inicioCaja->estado_id                     = 1;
                    $inicioCaja->usuario_id                    = session::get('ID_USUARIO');
                    $inicioCaja->save();
                    //dd($datoInicioCaja);
                    InicioFinCaja::create([
                        'sucursal_id'          => session::get('ID_SUCURSAL'),
                        'fecha'                => $fecha_actual, //Carbon::parse($resFechaProximo)->format('Y-m-d'),
                        'inicio_caja_bs'       => $datoInicioCaja->inicio_caja_bs,
                        'caja'                 => session::get('CAJA'),
                        'tipo_de_movimiento'   => 'Inicio de caja realizado por',
                        'estado_id'            => 1,
                        'usuario_id'           => session::get('ID_USUARIO'),
                        'moneda_id'            => 1
                    ]);
                }
            }
            return view('layout.principal', compact('opciones', 'countInicioFinCaja', 'datoInicioFinCaja'));
        } else {
            return view('layout.principal');
        }

        //dd($countInicioFinCaja);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
