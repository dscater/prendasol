<?php

namespace App\Http\Controllers\InicioFinCaja;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use App\InicioFinCaja;
use App\InicioFinCajaDetalle;
use App\Sucursal;
use Carbon\Carbon;

class DatoInicioFinCajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $sucursales = Sucursal::where('estado_id', 1)->get();
            if (session::get('ID_ROL') == 1 || session::get('ID_ROL') == 3) {
                $datosInicioFinCaja =  InicioFinCaja::where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                    ->whereIn('estado_id', [1, 2])
                    ->orderBy('id', 'DESC')->get();
                //dd($datosInicioFinCaja); 

                if ($request->ajax()) {
                    return view('datoInicioFinCaja.modals.listadoInicioFinCaja', ['datosInicioFinCaja' => $datosInicioFinCaja, 'sucursales' => $sucursales])->render();
                }
                //return view('contrato.index',compact('personas'));
                return view('datoInicioFinCaja.index', compact('datosInicioFinCaja', 'sucursales'));
            } else {
                return view("layout.login", compact('sucursales'));
            }
        } else {
            return view("layout.login", compact('sucursales'));
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
                    //DB::beginTransaction();
                    /*VERIFICAMOS SI EXISTE USUARIO*/
                    $id_sucursal = $request['ddlSucursal'];
                    $caja = $request['ddlCaja'];
                    if ($id_sucursal == 1) {
                        if ($caja == 1) {
                            $idCaja = 11;
                        } else {
                            $idCaja = 12;
                        }
                    }

                    if ($id_sucursal == 2) {
                        if ($caja == 1) {
                            $idCaja = 31;
                        } else {
                            $idCaja = 32;
                        }
                    }

                    if ($id_sucursal == 3) {
                        if ($caja == 1) {
                            $idCaja = 51;
                        } else {
                            $idCaja = 52;
                        }
                    }

                    if ($id_sucursal == 5) {
                        if ($caja == 1) {
                            $idCaja = 41;
                        } else {
                            $idCaja = 42;
                        }
                    }
                    if ($id_sucursal == 6) {
                        if ($caja == 1) {
                            $idCaja = 61;
                        } else {
                            $idCaja = 62;
                        }
                    }
                    if ($id_sucursal == 7) {
                        if ($caja == 1) {
                            $idCaja = 71;
                        } else {
                            $idCaja = 72;
                        }
                    }

                    if ($id_sucursal == 4) {
                        if ($caja == 1) {
                            $idCaja = 21;
                        } else {
                            $idCaja = 22;
                        }
                    }

                    if ($id_sucursal == 8) {
                        if ($caja == 1) {
                            $idCaja = 81;
                        } else {
                            $idCaja = 82;
                        }
                    }

                    // nuevas sucursales
                    if ($id_sucursal == 9) {
                        if ($caja == 1) {
                            $idCaja = 91;
                        } else {
                            $idCaja = 92;
                        }
                    }

                    if ($id_sucursal == 10) {
                        if ($caja == 1) {
                            $idCaja = 101;
                        } else {
                            $idCaja = 102;
                        }
                    }

                    if ($id_sucursal == 11) {
                        if ($caja == 1) {
                            $idCaja = 111;
                        } else {
                            $idCaja = 112;
                        }
                    }

                    if ($id_sucursal == 12) {
                        if ($caja == 1) {
                            $idCaja = 121;
                        } else {
                            $idCaja = 122;
                        }
                    }

                    if ($id_sucursal == 13) {
                        if ($caja == 1) {
                            $idCaja = 131;
                        } else {
                            $idCaja = 132;
                        }
                    }

                    if ($id_sucursal == 14) {
                        if ($caja == 1) {
                            $idCaja = 141;
                        } else {
                            $idCaja = 142;
                        }
                    }

                    if ($id_sucursal == 15) {
                        if ($caja == 1) {
                            $idCaja = 151;
                        } else {
                            $idCaja = 152;
                        }
                    }

                    if ($id_sucursal == 16) {
                        if ($caja == 1) {
                            $idCaja = 161;
                        } else {
                            $idCaja = 162;
                        }
                    }

                    if ($id_sucursal == 17) {
                        if ($caja == 1) {
                            $idCaja = 171;
                        } else {
                            $idCaja = 172;
                        }
                    }

                    if ($id_sucursal == 18) {
                        if ($caja == 1) {
                            $idCaja = 181;
                        } else {
                            $idCaja = 182;
                        }
                    }

                    if ($id_sucursal == 19) {
                        if ($caja == 1) {
                            $idCaja = 191;
                        } else {
                            $idCaja = 192;
                        }
                    }

                    if ($id_sucursal == 20) {
                        if ($caja == 1) {
                            $idCaja = 201;
                        } else {
                            $idCaja = 202;
                        }
                    }

                    if ($id_sucursal == 21) {
                        if ($caja == 1) {
                            $idCaja = 211;
                        } else {
                            $idCaja = 212;
                        }
                    }

                    //dd(Carbon::parse($request['txtFecha'])->format('Y-m-d'));
                    $verificarDato = InicioFinCaja::where('sucursal_id', $request['ddlSucursal'])
                        ->where('caja', $idCaja)
                        ->where('fecha', Carbon::parse($request['txtFecha'])->format('Y-m-d'))
                        ->count();
                    //dd($verificarDato);           
                    if ($verificarDato == 0) {
                        //INSERTAMOS USUARIO
                        InicioFinCaja::create([
                            'sucursal_id'         => $request['ddlSucursal'],
                            'caja'                => $idCaja,
                            'fecha'               => Carbon::parse($request['txtFecha'])->format('Y-m-d'),
                            'fecha_hora'               => Carbon::parse($request['txtFecha'])->format('Y-m-d H:i:s'),
                            'inicio_caja_bs'      => $request['txtMonto'],
                            'tipo_de_movimiento'  => 'Inicio de caja realizado',
                            'estado_id'                => 1,
                            'usuario_id'                => session::get('ID_USUARIO'),
                            'moneda_id'             => $request['moneda_id']
                        ]);

                        return response()->json(["Mensaje" => "1"]);
                    } else {
                        /*2 = EXISTE UN USUARIO */
                        return response()->json(["Mensaje" => "2"]);
                    }
                    //DB::commit(); 

                } catch (Exception $e) {
                    //DB::rollback();
                    return response()->json(["Mensaje" => "-1"]);
                }
            } else {
                return response()->json(["Mensaje" => "0"]);
            }
        } else {
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
}
