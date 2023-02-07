<?php

namespace App\Http\Controllers\Reimpresiones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pagos;
use Session;
use Carbon\Carbon;
use PDF;
use App\Sucursal;

class ReimpresionesVacunalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $sucursales = Sucursal::where('estado_id',1)->get();
            //$datosContaDiario = ContaDiario::where('tcom','EGRESO1')->where('ref','T126')->get();
            if (session::get('ID_ROL') == 1 || session::get('ID_ROL') == 3) {
                if ($request->ajax()) {
                    //return view('formEgreso.modals.listadoEgreso', ['sucursales' => $sucursales,'datosContaDiario'=>$datosContaDiario,'cuentas'=>$cuentas])->render(); 
                }
                //return view('inicioFinCaja.index',compact('datosCaja','datoValidarCaja'));
                //return view('contabilidad.contaDiario.index',compact('datosContaDiario'));
                return view('reimpresiones.index',compact('sucursales'));
            }else{
                return view("layout.login",compact('sucursales'));
            }
            
            
        }else{
            return view("layout.login",compact('sucursales'));
        }
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

    public function buscarReimpresiones(Request $request)
    {
        $fechaI = $request['txtFechaInicio'];
        $fechaF = $request['txtFechaFin'];
        $sucursal = $request['ddlSucursal'];
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            //$datosContaDiario = ContaDiario::whereBetween('fecha_a',[$fechaI,$fechaF])->get();
            //$datosPagos = Pagos::whereBetween('fecha_pago',[$fechaInicio,$fechaFinal])
            $datosPagos = Pagos::whereBetween('fecha_inio',[$fechaInicio,$fechaFinal])
                ->where('sucursal_id',$sucursal)
                ->where('estado_id',1)
                //->orderBy('fecha_inio','DESC')
                ->orderBy('estado','ASC')
                ->orderBy('contrato_id','ASC')
                //->orderBy('contrato_id','ASC')
                ->get();
            
            //dd($datosContaDiario);
            if ($datosPagos) {                
                if ($request->ajax()) {
                    //dd($actoVacunaciones);
                    return view('reimpresiones.modals.listadoReimpresiones', ['datosPagos' => $datosPagos])->render();
                } 
                return view('Reimpresiones.index',compact('datosPagos'));
                //return view('contabilidad.contaDiario.index',compact('datosContaDiario'));                           
            }
            
        }else{
            return view("layout.login");
        }       
    }
}
