<?php

namespace App\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use Carbon\Carbon;
use App\Persona;
use App\Sucursal;
use App\ContaDiario;
use App\ContaDenominacion;
use Illuminate\Support\Facades\DB;

class RegistroContableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            //$datosContaDiario = ContaDiario::select(DB::raw('sucursal_id','periodo','fecha_a','caja','num_comprobante','tcom','ref'))
                //orderBy('fecha_a','DESC')
                //->groupBy('sucursal_id','periodo','fecha_a','caja','num_comprobante','tcom','ref')
                //->paginate(10);
            $sucursales = Sucursal::where('estado_id',1)->get();
            if (session::get('ID_ROL') == 1 || session::get('ID_ROL') == 3) {
                $datosContaDiario = ContaDiario::distinct()->select('sucursal_id','periodo','fecha_a','caja','num_comprobante','tcom','ref')
                    ->orderBy('fecha_a','DESC')
                    ->orderBy('num_comprobante','DESC')
                    ->paginate(10);
                
                if ($request->ajax()) {
                    return view('contabilidad.registrosContables.modals.listadoRegistrosContables', ['datosContaDiario'=>$datosContaDiario,])->render(); 
                }
                //return view('inicioFinCaja.index',compact('datosCaja','datoValidarCaja'));
                return view('contabilidad.registrosContables.index',compact('datosContaDiario'));
            }
            else{
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
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                try {
                    
                    $array_cuenta      = array();
                    $array_glosa       = array();
                    $array_debe        = array();
                    $array_haber       = array();

                    $array_cuenta     = $request['cuenta'];
                    $array_glosa      = $request['glosa'];
                    $array_debe       = $request['debe'];
                    $array_haber      = $request['haber'];

                    $numComprobante = ContaDiario::max('num_comprobante');  
                    // number_format($totalImporteDebe, 2, ',', '.')                  

                    for ($i=0; $i < count($array_cuenta) ; $i++) {
                        $nombreCuenta = ContaDenominacion::where('id',$array_cuenta[$i])->first();
                        //dd($array_debe[$i]);
                        if ($array_debe[$i]) {
                            $valorDebe = $array_debe[$i];
                        }
                        else{
                            $valorDebe = "0.00";
                        }

                        if ($array_haber[$i]) {
                            $valorHaber = $array_haber[$i];
                        }
                        else{
                            $valorHaber = "0.00";
                        }

                        //dd($valorDebe);
                        //INSERTAMOS CONTRATO DETALLE
                        ContaDiario::create([
                            'contrato_id'        => 0,
                            'pagos_id'           => 0,
                            'sucursal_id'        => session::get('ID_SUCURSAL'),                            
                            'fecha_a'            => Carbon::parse($request['txtFecha'])->format('Y-m-d'),
                            'fecha_b'            => Carbon::parse($request['txtFecha'])->format('Y-m-d'),
                            'glosa'              => $array_glosa[$i],
                            'cod_deno'           => $nombreCuenta->cod_deno,
                            'cuenta'             => $nombreCuenta->descripcion,
                            'debe'               => round($valorDebe,2),
                            'haber'              => round($valorHaber,2),
                            'caja'               => session::get('CAJA'),
                            'num_comprobante'    => $numComprobante +1,
                            'periodo'            => 'mes',
                            'tcom'               => $request['ddlTipoComprobante'],
                            'ref'                => 'MA',
                            'usuario_id'         => session::get('ID_USUARIO'),
                            'estado_id'          => 1
                        ]);
                    }
                    return response()->json(["Mensaje" => "1","numComprobante" => $numComprobante+1,"fecha"=> $request['txtFecha']]);
                    //DB::commit(); 
                    
                } catch (Exception $e) {
                    //DB::rollback();
                    return response()->json(["Mensaje" => "-1"]);
                }
                        
            }
            else{
                return response()->json(["Mensaje" => "0"]);
            }
        }
        else{
            return response()->json(["Mensaje" => "-1"]);
        }
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

    public function cargarCuentas()
    {
        $cuentas = ContaDenominacion::get();
        return response()->json(["data" => $cuentas]);
    }
}
