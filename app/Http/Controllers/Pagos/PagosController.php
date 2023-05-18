<?php

namespace App\Http\Controllers\Pagos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Persona;
use App\Pagos;
use App\Cliente;
use App\Contrato;
use App\ContratoDetalle;
use App\LogSeguimiento;
use App\InicioFinCaja;
use App\InicioFinCajaDetalle;
use Carbon\Carbon;
use App\ContaDiario;
use PDF;
use App\NumberToLetterConverter;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\CambioMoneda;
use App\Http\Controllers\Contrato\ContratoController;
use App\Moneda;
use App\Sucursal;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Log;

class PagosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $personas = Persona::orderBy('primerapellido', 'ASC')->orderBy('segundoapellido', 'ASC')->paginate(10);
            $datoValidarCaja =  InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->whereIn('estado_id', [1, 2])
                ->orderBy('id', 'DESC')->first();
            $datoInicioFinCaja =  InicioFinCaja::where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->whereIn('estado_id', [1, 2])
                ->first();
            $fechaActual = Carbon::now('America/La_Paz')->format('Y-m-d');
            if ($request->ajax()) {
                //return view('contrato.modals.listadoContrato', ['personas' => $personas])->render();  
            }
            //return view('contrato.index',compact('personas'));
            $sucursales = Sucursal::where('estado_id', 1)->get();
            return view('pagos.index', compact('datoValidarCaja', 'datoInicioFinCaja', 'fechaActual', 'sucursales'));
        } else {
            return view("layout.login");
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
        \DB::enableQueryLog();
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                $datoInicioCaja = InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                    ->whereIn('estado_id', [1, 2])
                    ->first();
                //dd($datoInicioCaja);

                $contadorInicioCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                    ->where('estado_id', 1)->count();
                //dd($contadorInicioCajaDetalle);                    


                if ($contadorInicioCajaDetalle == 0) {
                    $inicioCajaBs = $datoInicioCaja->inicio_caja_bs;
                    $idInicioCaja = $datoInicioCaja->id;
                } else {
                    $datoCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
                        ->where('caja', session::get('CAJA'))
                        ->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                        ->where('estado_id', 1)->orderBy('id', 'DESC')
                        ->first();
                    $inicioCajaBs = $datoCajaDetalle->inicio_caja_bs;
                    $idInicioCaja = $datoCajaDetalle->inicio_fin_caja_id;
                }

                $resulInicioCaja = (float)$inicioCajaBs + (float)0;

                $datoContrato = Contrato::where('id', $id)->first();
                if ($datoContrato->codigo != "") {
                    $codigoContrato = $datoContrato->codigo;
                } else {
                    $codigoContrato = $datoContrato->codigo_num;
                }
                //dd(round($resulInicioCaja, 2)); 

                InicioFinCajaDetalle::create([
                    'inicio_fin_caja_id'    => $idInicioCaja,
                    'contrato_id'           => $id,
                    'pago_id'               => 0,
                    'sucursal_id'           => session::get('ID_SUCURSAL'),
                    'fecha_pago'            => Carbon::parse($request['fecha_pago'])->format('Y-m-d'),
                    'fecha_hora'            => Carbon::now('America/La_Paz'),
                    'inicio_caja_bs'        => round($resulInicioCaja, 2),
                    'ingreso_bs'             => "0.00",
                    'tipo_de_movimiento'    => 'RETIRO DE LA JOYA DEL CONTRATO: ' . $codigoContrato . ' DEL  SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' EN LA CAJA ' . session::get('CAJA') . '.',
                    'ref'               => 'RET01',
                    'caja'              => session::get('CAJA'),
                    'usuario_id'        => session::get('ID_USUARIO'),
                    'estado_id'         => 1,
                    'moneda_id'         => $datoContrato->moneda_id
                ]);

                $contrato = Contrato::find($id);
                $contrato->estado_pago            = 'Credito cancelado';
                $contrato->estado_entrega         = 'Prenda entregada';
                $contrato->estado_pago_2          = 'entregada';
                $contrato->estado_id              = 1;
                $contrato->usuario_id             = session::get('ID_USUARIO');
                $contrato->save();

                $bitacora = \DB::getQueryLog();
                foreach ($bitacora as $i => $query) {
                    $resultado = json_encode($query);
                }
                \DB::disableQueryLog();
                LogSeguimiento::create([
                    'usuario_id'   => session::get('ID_USUARIO'),
                    'metodo'   => 'PUT',
                    'accion'   => 'ACTUALIZACION',
                    'detalle'  => "el usuario" . session::get('USUARIO') . " actualizo un registro prenda entregada",
                    'modulo'   => 'CONTRATO',
                    'consulta' => $resultado,
                ]);
                return response()->json(["Mensaje" => "1"]);
            } else {
                return response()->json(["Mensaje" => "0"]);
            }
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
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

    public function buscarPagosDetalle(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $pagos = Pagos::where('contrato_id', $request['idContrato'])->where('estado_id', 1)->orderBy('fecha_inio', 'DESC')->get();
            if ($pagos) {
                if ($request->ajax()) {
                    return view('pagos.modals.listadoPagosDetalle', ['pagos' => $pagos])->render();
                }
                return view('pagos.index', compact('pagos'));
            }
        } else {
            return view("layout.login");
        }
    }

    public function buscarContratosPagos(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $cliente = Cliente::where('persona_id', $request['idPersona'])->where('estado_id', 1)->first();
            $datoValidarCaja =  InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->whereIn('estado_id', [1, 2])
                ->orderBy('id', 'DESC')->first();
            //dd($cliente);
            if ($cliente) {
                $contratos = Contrato::where('cliente_id', $cliente->id)->whereIn('estado_id', [1, 3])->orderBy('id', 'DESC')->get();
                //dd($contratos);
                if ($contratos) {
                    if ($request->ajax()) {
                        return view('pagos.modals.listadoContrato', ['contratos' => $contratos, 'datoValidarCaja' => $datoValidarCaja, 'cliente' => $cliente])->render();
                    }
                    return view('pagos.index', compact('contratos', 'cliente', 'datoValidarCaja'));
                }
            }
        } else {
            return view("layout.login");
        }
    }

    public function pagoContratoTotal(Request $request)
    {
        \DB::enableQueryLog();
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                $_contrato = Contrato::find($request['idContrato']);
                $fecha_actual = Carbon::parse($request['fecha_pago'])->format('d-m-Y');
                //$resFechaProximo = date("d-m-Y",strtotime($fecha_actual."+ 1 months"));
                $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+30 day"));
                $idPago = Pagos::create([
                    'contrato_id'          => $request['idContrato'],
                    'sucursal_id'          => session::get('ID_SUCURSAL'),
                    'fecha_inio'           => date('Y-m-d H:i:s', strtotime($request['fecha_pago'])),
                    //'fecha_fin'            => Carbon::parse($resFechaProximo)->format('Y-m-d'),
                    'fecha_fin'            => date('Y-m-d H:i:s', strtotime($request['fecha_pago'])),
                    'fecha_pago'           => $request['fecha_pago'],
                    'caja'                 => session::get('CAJA'),
                    'dias_atraso'          => $request['diasAtraso'],
                    'capital'              => $request['capital'], //Carbon::parse($request['txtFechaNacimiento'])->format('Y-m-d'),
                    'interes'              => $request['interes'],
                    'comision'              => $request['comision'],
                    'cuota_mora'              => $request['cuotaMora'],
                    'total_capital'        => $request['total_capital'],
                    'estado'               => 'PAGO TOTAL',
                    'estado_id'            => 1,
                    'usuario_id'           => session::get('ID_USUARIO'),
                    'moneda_id'             => $_contrato->moneda_id
                ])->id;

                $bitacora = \DB::getQueryLog();
                foreach ($bitacora as $i => $query) {
                    $resultado = json_encode($query);
                }
                \DB::disableQueryLog();
                LogSeguimiento::create([
                    'usuario_id'   => session::get('ID_USUARIO'),
                    'metodo'   => 'POST',
                    'accion'   => 'CREACION',
                    'detalle'  => "el usuario" . session::get('USUARIO') . " agrego un nuevo registro",
                    'modulo'   => 'PAGO TOTAL',
                    'consulta' => $resultado,
                ]);

                $totalPagar = (float)$request['capital'] + (float)$request['interes'] + (float)$request['comision'] + (float)$request['cuotaMora'];

                $datoInicioCaja = InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                    ->whereIn('estado_id', [1, 2])
                    ->first();
                //dd($datoInicioCaja);

                $contadorInicioCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                    ->where('estado_id', 1)->count();
                //dd($contadorInicioCajaDetalle);                    


                if ($contadorInicioCajaDetalle == 0) {
                    $inicioCajaBs = $datoInicioCaja->inicio_caja_bs;
                    $idInicioCaja = $datoInicioCaja->id;
                } else {
                    $datoCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))->where('caja', session::get('CAJA'))->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))->where('estado_id', 1)->orderBy('id', 'DESC')->first();
                    $inicioCajaBs = $datoCajaDetalle->inicio_caja_bs;
                    $idInicioCaja = $datoCajaDetalle->inicio_fin_caja_id;
                }

                $resulInicioCaja = (float)$inicioCajaBs + (float)$totalPagar;

                $datoContrato = Contrato::where('id', $request['idContrato'])->first();
                if ($datoContrato->codigo != "") {
                    $codigoContrato = $datoContrato->codigo;
                } else {
                    $codigoContrato = $datoContrato->codigo_num;
                }
                //dd(round($resulInicioCaja, 2)); 

                InicioFinCajaDetalle::create([
                    'inicio_fin_caja_id'    => $idInicioCaja,
                    'contrato_id'           => $request['idContrato'],
                    'pago_id'               => $idPago,
                    'sucursal_id'           => session::get('ID_SUCURSAL'),
                    'fecha_pago'            => Carbon::parse($request['fecha_pago'])->format('Y-m-d'),
                    'fecha_hora'            => Carbon::now('America/La_Paz'),
                    'inicio_caja_bs'        => round($resulInicioCaja, 2),
                    'ingreso_bs'             => $totalPagar,
                    'tipo_de_movimiento'    => 'PAGO TOTAL AL N° ' . $codigoContrato . ' DEL  SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' EN LA CAJA ' . session::get('CAJA') . '.',
                    'ref'               => 'CA01',
                    'caja'              => session::get('CAJA'),
                    'usuario_id'        => session::get('ID_USUARIO'),
                    'estado_id'         => 1,
                    'moneda_id'             => $_contrato->moneda_id
                ]);

                //ACTUALIZAMOS CONTRATO
                $contrato = Contrato::find($request['idContrato']);
                $contrato->fecha_pago                      = $request['fecha_pago'];
                $contrato->interes                         = $request['interes'];
                $contrato->comision                        = $request['comision'];
                $contrato->cuota_mora                      = $request['cuotaMora'];
                $contrato->estado_pago                     = 'Credito cancelado';
                $contrato->estado_entrega                  = 'Prenda en custodia';
                $contrato->estado_pago_2                   = 'custodia';
                $contrato->estado_id                       = 1;
                $contrato->usuario_id                      = session::get('ID_USUARIO');
                $contrato->save();

                $bitacora = \DB::getQueryLog();
                foreach ($bitacora as $i => $query) {
                    $resultado = json_encode($query);
                }
                \DB::disableQueryLog();
                LogSeguimiento::create([
                    'usuario_id'   => session::get('ID_USUARIO'),
                    'metodo'   => 'PUT',
                    'accion'   => 'ACTUALIZAR',
                    'detalle'  => "el usuario" . session::get('USUARIO') . " actualizo un registro",
                    'modulo'   => 'CONTRATO',
                    'consulta' => $resultado,
                ]);

                /*
                CONVERTIR A BS SI EL CONTRATO/PAGO ESTA EN $US
                */
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                // $_total_pagar = CambioMoneda::ajustaDecimal(round((float)$valores_cambio->valor_bs * (float)$totalPagar, 2));
                // } else {
                $_total_pagar = (float)$totalPagar;
                // }

                /*REGISTRAR PARTE CONTABLE*/
                $numComprobante = ContaDiario::max('num_comprobante');
                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'PAGO TOTAL AL N° ' . $codigoContrato . ' DEL  SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' EN LA CAJA ' . session::get('CAJA') . '.',
                    'cod_deno'              => '11102',
                    'cuenta'                => 'Caja sucursales',
                    'debe'                  => $_total_pagar,
                    'haber'                 => '0.00',
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'CA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);

                $_capital = 0;
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $_capital = round((float)$valores_cambio->valor_bs * (float)$request['capital'], 2);
                // } else {
                $_capital = $request['capital'];
                // }

                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'PAGO TOTAL AL N° ' . $codigoContrato . ' DEL  SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' EN LA CAJA ' . session::get('CAJA') . '.',
                    'cod_deno'              => '11301',
                    'cuenta'                => 'Prestamos a plazo fijo vigentes',
                    'debe'                  => '0.00',
                    'haber'                 => $_capital,
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'CA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);

                $totalComisionInteres = (float)$request['interes'] + (float)$request['comision'];

                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $totalComisionInteres = round((float)$valores_cambio->valor_bs * (float)$totalComisionInteres, 2);
                // }
                $totalComisionInteres = $totalComisionInteres;

                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'PAGO TOTAL AL N° ' . $codigoContrato . ' DEL  SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' EN LA CAJA ' . session::get('CAJA') . '.',
                    'cod_deno'              => '41101',
                    'cuenta'                => 'Intereses prestamos a plazo fijo cartera vigente',
                    'debe'                  => '0.00',
                    'haber'                 => $totalComisionInteres,
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'CA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);


                $_cuotaMora = $request['cuotaMora'];
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $_cuotaMora = round((float)$valores_cambio->valor_bs * (float)$request['cuotaMora'], 2);
                // }
                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'PAGO TOTAL AL N° ' . $codigoContrato . ' DEL  SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' EN LA CAJA ' . session::get('CAJA') . '.',
                    'cod_deno'              => '41104',
                    'cuenta'                => 'Intereses por mora prestamos a plazo fijo cartera vigente',
                    'debe'                  => '0.00',
                    'haber'                 => $_cuotaMora,
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'CA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);
                return response()->json(["Mensaje" => "1", "idPago" => $idPago]);
            } else {
                return response()->json(["Mensaje" => "0"]);
            }
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public function pagoContratoInteres(Request $request)
    {
        \DB::enableQueryLog();
        if (Session::has('AUTENTICADO')) {
            $_contrato = Contrato::find($request['idContrato']);
            $fecha_actual = Carbon::parse($request['fecha_pago'])->format('d-m-Y');
            //$resFechaProximo = date("d-m-Y",strtotime($fecha_actual."+ 1 months"));
            $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+30 day"));
            if ($request->ajax()) {
                $idPago = Pagos::create([
                    'contrato_id'          => $request['idContrato'],
                    'sucursal_id'          => session::get('ID_SUCURSAL'),
                    'fecha_inio'           => date('Y-m-d H:i:s', strtotime($request['fecha_pago'])),
                    'fecha_fin'            => Carbon::parse($resFechaProximo)->format('Y-m-d H:i:s'),
                    'fecha_pago'           => $request['fecha_pago'],
                    'caja'                 => session::get('CAJA'),
                    'dias_atraso'          => $request['diasAtraso'],
                    'capital'              => $request['capital'], //Carbon::parse($request['txtFechaNacimiento'])->format('Y-m-d'),
                    'interes'              => $request['interes'],
                    'comision'              => $request['comision'],
                    'cuota_mora'              => $request['cuotaMora'],
                    'total_capital'        => $request['total_capital'],
                    'estado'               => 'INTERES',
                    'estado_id'            => 1,
                    'usuario_id'           => session::get('ID_USUARIO'),
                    'moneda_id'            => $_contrato->moneda_id
                ])->id;

                $bitacora = \DB::getQueryLog();
                foreach ($bitacora as $i => $query) {
                    $resultado = json_encode($query);
                }
                \DB::disableQueryLog();
                LogSeguimiento::create([
                    'usuario_id'   => session::get('ID_USUARIO'),
                    'metodo'   => 'POST',
                    'accion'   => 'CREACION',
                    'detalle'  => "el usuario" . session::get('USUARIO') . " agrego un nuevo registro",
                    'modulo'   => 'PAGO INTERES',
                    'consulta' => $resultado,
                ]);

                $totalPagar = (float)$request['interes'] + (float)$request['comision'] + (float)$request['cuotaMora'];

                $datoInicioCaja = InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                    ->whereIn('estado_id', [1, 2])
                    ->first();
                //dd($datoInicioCaja);

                $contadorInicioCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                    ->where('estado_id', 1)->count();
                //dd($contadorInicioCajaDetalle);                    


                if ($contadorInicioCajaDetalle == 0) {
                    $inicioCajaBs = $datoInicioCaja->inicio_caja_bs;
                    $idInicioCaja = $datoInicioCaja->id;
                } else {
                    $datoCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))->where('caja', session::get('CAJA'))->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))->where('estado_id', 1)->orderBy('id', 'DESC')->first();
                    $inicioCajaBs = $datoCajaDetalle->inicio_caja_bs;
                    $idInicioCaja = $datoCajaDetalle->inicio_fin_caja_id;
                }

                $resulInicioCaja = (float)$inicioCajaBs + (float)$totalPagar;

                $datoContrato = Contrato::where('id', $request['idContrato'])->first();
                //dd(round($resulInicioCaja, 2)); 

                InicioFinCajaDetalle::create([
                    'inicio_fin_caja_id'    => $idInicioCaja,
                    'contrato_id'           => $request['idContrato'],
                    'pago_id'               => $idPago,
                    'sucursal_id'           => session::get('ID_SUCURSAL'),
                    'fecha_pago'            => Carbon::parse($request['fecha_pago'])->format('Y-m-d'),
                    'fecha_hora'            => Carbon::now('America/La_Paz'),
                    'inicio_caja_bs'        => round($resulInicioCaja, 2),
                    'ingreso_bs'             => round($totalPagar, 2),
                    // 'tipo_de_movimiento'    => 'PAGO TOTAL AL N° '. $datoContrato->codigo .' DEL  SR.(A) '. $datoContrato->cliente->persona->nombreCompleto() .' EN LA CAJA '. session::get('CAJA') .'.' ,
                    'tipo_de_movimiento'    => 'RENOVACIÓN DE CLIENTE ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA' . session::get('CAJA'),
                    'ref'               => 'IA01',
                    'caja'              => session::get('CAJA'),
                    'usuario_id'        => session::get('ID_USUARIO'),
                    'estado_id'         => 1,
                    'moneda_id'             => $_contrato->moneda_id

                ]);

                //ACTUALIZAMOS CONTRATO
                $fecha_actual = Carbon::parse($request['fecha_pago'])->format('d-m-Y');
                //$resFechaProximo = date("d-m-Y",strtotime($fecha_actual."+ 1 months"));
                $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+30 day"));
                $contrato = Contrato::find($request['idContrato']);
                $contrato->fecha_pago                      = $request['fecha_pago'];
                $contrato->interes                         = $request['interes'];
                $contrato->comision                        = $request['comision'];
                $contrato->cuota_mora                      = $request['cuotaMora'];
                $contrato->estado_pago                     = 'interes igual';
                $contrato->estado_entrega                  = 'Prenda en custodia';
                $contrato->estado_pago_2                   = 'custodia';
                $contrato->fecha_contrato                  = $request['fecha_pago'];
                $contrato->fecha_fin                       = Carbon::parse($resFechaProximo)->format('Y-m-d');
                $contrato->estado_id                       = 1;
                $contrato->usuario_id                      = session::get('ID_USUARIO');
                $contrato->save();

                $bitacora = \DB::getQueryLog();
                foreach ($bitacora as $i => $query) {
                    $resultado = json_encode($query);
                }
                \DB::disableQueryLog();
                LogSeguimiento::create([
                    'usuario_id'   => session::get('ID_USUARIO'),
                    'metodo'   => 'PUT',
                    'accion'   => 'ACTUALIZAR',
                    'detalle'  => "el usuario" . session::get('USUARIO') . " actualizo un registro",
                    'modulo'   => 'CONTRATO',
                    'consulta' => $resultado,
                ]);

                /*REGISTRAR PARTE CONTABLE*/
                $numComprobante = ContaDiario::max('num_comprobante');


                $_totalPagar = round($totalPagar, 2);
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $_totalPagar = CambioMoneda::ajustaDecimal(round((float)$valores_cambio->valor_bs * (float)$totalPagar, 2));
                // }
                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'RENOVACIÓN DE CLIENTE ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA' . session::get('CAJA'),
                    'cod_deno'              => '11102',
                    'cuenta'                => 'Caja sucursales',
                    'debe'                  => $_totalPagar,
                    'haber'                 => '0.00',
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'IA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);


                $totalComisionInteres = (float)$request['interes'] + (float)$request['comision'];
                // $totalComisionInteres = round($totalComisionInteres, 2);
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $totalComisionInteres = round((float)$valores_cambio->valor_bs * (float)$totalComisionInteres, 2);
                // }
                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'RENOVACIÓN DE CLIENTE ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA' . session::get('CAJA'),
                    'cod_deno'              => '41101',
                    'cuenta'                => 'Intereses prestamos a plazo fijo cartera vigente',
                    'debe'                  => '0.00',
                    'haber'                 => $totalComisionInteres,
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'IA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);

                $_cuota_mora = round($request['cuotaMora'], 2);
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $_cuota_mora = round((float)$valores_cambio->valor_bs * (float)$request['cuotaMora'], 2);
                // }
                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'RENOVACIÓN DE CLIENTE ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA' . session::get('CAJA'),
                    'cod_deno'              => '41104',
                    'cuenta'                => 'Intereses por mora prestamos a plazo fijo cartera vigente',
                    'debe'                  => '0.00',
                    'haber'                 => $_cuota_mora,
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'IA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);
                return response()->json(["Mensaje" => "1", "idPago" => $idPago]);
            } else {
                return response()->json(["Mensaje" => "0"]);
            }
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public function pagoContratoAmortizacion(Request $request)
    {
        // dd($request->all());
        // dd("boliviaaa");
        \DB::enableQueryLog();
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                $_contrato = Contrato::find($request['idContrato']);
                $fecha_actual = Carbon::parse($request['fecha_pago'])->format('d-m-Y');
                //$resFechaProximo = date("d-m-Y",strtotime($fecha_actual."+ 1 months"));
                $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+30 day"));
                $idPago = Pagos::create([
                    'contrato_id'          => $request['idContrato'],
                    'sucursal_id'          => session::get('ID_SUCURSAL'),
                    'fecha_inio'           => date('Y-m-d H:i:s', strtotime($request['fecha_pago'])),
                    'fecha_fin'            => Carbon::parse($resFechaProximo)->format('Y-m-d H:i:s'),
                    'fecha_pago'           => $request['fecha_pago'],
                    'caja'                 => session::get('CAJA'),
                    'dias_atraso'          => $request['diasAtraso'],
                    'capital'              => $request['capital'], //Carbon::parse($request['txtFechaNacimiento'])->format('Y-m-d'),
                    'dias_atraso_total'     => $request['capitalActual'],
                    'interes'              => $request['interes'],
                    'comision'              => $request['comision'],
                    'cuota_mora'              => $request['cuotaMora'],
                    'total_capital'        => $request['total_capital'],
                    'estado'               => 'AMORTIZACIÓN',
                    'estado_id'            => 1,
                    'usuario_id'           => session::get('ID_USUARIO'),
                    'moneda_id'             => $_contrato->moneda_id
                ])->id;

                $bitacora = \DB::getQueryLog();
                foreach ($bitacora as $i => $query) {
                    $resultado = json_encode($query);
                }
                \DB::disableQueryLog();
                LogSeguimiento::create([
                    'usuario_id'   => session::get('ID_USUARIO'),
                    'metodo'   => 'POST',
                    'accion'   => 'CREACION',
                    'detalle'  => "el usuario" . session::get('USUARIO') . " agrego un nuevo registro",
                    'modulo'   => 'PAGO AMORTIZACIÓN',
                    'consulta' => $resultado,
                ]);

                //INICIO FIN CAJA

                $capitalPagado = (float)$request['capitalActual'] - (float)$request['capital'];

                $totalPagar = (float)$capitalPagado + (float)$request['interes'] + (float)$request['comision'] + (float)$request['cuotaMora'];

                $datoInicioCaja = InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                    ->whereIn('estado_id', [1, 2])
                    ->first();
                //dd($datoInicioCaja);

                $contadorInicioCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                    ->where('estado_id', 1)->count();
                //dd($contadorInicioCajaDetalle);                    

                if ($contadorInicioCajaDetalle == 0) {
                    $inicioCajaBs = $datoInicioCaja->inicio_caja_bs;
                    $idInicioCaja = $datoInicioCaja->id;
                } else {
                    $datoCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))->where('caja', session::get('CAJA'))->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))->where('estado_id', 1)->orderBy('id', 'DESC')->first();
                    $inicioCajaBs = $datoCajaDetalle->inicio_caja_bs;
                    $idInicioCaja = $datoCajaDetalle->inicio_fin_caja_id;
                }

                $resulInicioCaja = (float)$inicioCajaBs + (float)$totalPagar;

                $datoContrato = Contrato::where('id', $request['idContrato'])->first();
                if ($datoContrato->codigo != "") {
                    $codigoContrato = $datoContrato->codigo;
                } else {
                    $codigoContrato = $datoContrato->codigo_num;
                }
                //dd(round($resulInicioCaja, 2)); 

                InicioFinCajaDetalle::create([
                    'inicio_fin_caja_id'    => $idInicioCaja,
                    'contrato_id'           => $request['idContrato'],
                    'pago_id'               => $idPago,
                    'sucursal_id'           => session::get('ID_SUCURSAL'),
                    'fecha_pago'            => Carbon::parse($request['fecha_pago'])->format('Y-m-d'),
                    'fecha_hora'            => Carbon::now('America/La_Paz'),
                    'inicio_caja_bs'        => round($resulInicioCaja, 2),
                    'ingreso_bs'             => round($totalPagar, 2),
                    // 'tipo_de_movimiento'    => 'PAGO TOTAL AL N° '. $datoContrato->codigo .' DEL  SR.(A) '. $datoContrato->cliente->persona->nombreCompleto() .' EN LA CAJA '. session::get('CAJA') .'.' ,
                    'tipo_de_movimiento'    => 'AMORTIZACIÓN N° ' . $codigoContrato . ' DEL SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA ' . session::get('CAJA') . '.',
                    'ref'               => 'MA01',
                    'caja'              => session::get('CAJA'),
                    'usuario_id'        => session::get('ID_USUARIO'),
                    'estado_id'         => 1,
                    'moneda_id'         => $_contrato->moneda_id
                ]);

                //ACTUALIZAMOS CONTRATO
                $fecha_actual = Carbon::parse($request['fecha_pago'])->format('d-m-Y');
                //$resFechaProximo = date("d-m-Y",strtotime($fecha_actual."+ 1 months"));
                $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+30 day"));
                $contrato = Contrato::find($request['idContrato']);
                $contrato->fecha_pago                      = $request['fecha_pago'];
                $contrato->interes                         = $request['interes'];
                $contrato->comision                        = $request['comision'];
                $contrato->cuota_mora                      = $request['cuotaMora'];
                $contrato->capital                         = $request['capital'];
                $contrato->estado_pago                     = 'amortizacion';
                $contrato->estado_entrega                  = 'Prenda en custodia';
                $contrato->estado_pago_2                   = 'custodia';
                $contrato->fecha_contrato                  = $request['fecha_pago'];
                $contrato->fecha_fin                       = Carbon::parse($resFechaProximo)->format('Y-m-d');
                $contrato->estado_id                       = 1;
                $contrato->usuario_id                      = session::get('ID_USUARIO');
                $contrato->save();

                $bitacora = \DB::getQueryLog();
                foreach ($bitacora as $i => $query) {
                    $resultado = json_encode($query);
                }
                \DB::disableQueryLog();
                LogSeguimiento::create([
                    'usuario_id'   => session::get('ID_USUARIO'),
                    'metodo'   => 'PUT',
                    'accion'   => 'ACTUALIZAR CONTRATO',
                    'detalle'  => "el usuario" . session::get('USUARIO') . " actualizo un registro",
                    'modulo'   => 'CONTRATO',
                    'consulta' => $resultado,
                ]);

                /*REGISTRAR PARTE CONTABLE*/
                $numComprobante = ContaDiario::max('num_comprobante');

                $_total_pagar = round($totalPagar, 2);
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $_total_pagar = round((float)$valores_cambio->valor_bs * (float)$totalPagar, 2);
                // }

                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'AMORTIZACIÓN N° ' . $codigoContrato . ' DEL SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA ' . session::get('CAJA') . '.',
                    'cod_deno'              => '11102',
                    'cuenta'                => 'Caja sucursales',
                    'debe'                  => $_total_pagar,
                    'haber'                 => '0.00',
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'MA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);

                //$totalhaber = float($request['capitalActual']) - float($request['capital']);
                $_capitalPagado = round($capitalPagado, 2);
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $_capitalPagado = round((float)$valores_cambio->valor_bs * (float)$capitalPagado, 2);
                // }
                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'AMORTIZACIÓN N° ' . $codigoContrato . ' DEL SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA ' . session::get('CAJA') . '.',
                    'cod_deno'              => '11301',
                    'cuenta'                => 'Prestamos a plazo fijo vigentes',
                    'debe'                  => '0.00',
                    'haber'                 => $_capitalPagado,
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'MA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);

                $totalComisionInteres = (float)$request['interes'] + (float)$request['comision'];
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $totalComisionInteres = round((float)$valores_cambio->valor_bs * (float)$totalComisionInteres, 2);
                // }
                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'AMORTIZACIÓN N° ' . $codigoContrato . ' DEL SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA ' . session::get('CAJA') . '.',
                    'cod_deno'              => '41101',
                    'cuenta'                => 'Intereses prestamos a plazo fijo cartera vigente',
                    'debe'                  => '0.00',
                    'haber'                 => $totalComisionInteres,
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'MA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);

                $_cuota_mora = round($request['cuotaMora'], 2);
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $_cuota_mora = round((float)$valores_cambio->valor_bs * (float)$request['cuotaMora'], 2);
                // }
                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'AMORTIZACIÓN N° ' . $codigoContrato . ' DEL SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA ' . session::get('CAJA') . '.',
                    'cod_deno'              => '41104',
                    'cuenta'                => 'Intereses por mora prestamos a plazo fijo cartera vigente',
                    'debe'                  => '0.00',
                    'haber'                 => $_cuota_mora,
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'MA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);
                return response()->json(["Mensaje" => "1", "idPago" => $idPago]);
            } else {
                return response()->json(["Mensaje" => "0"]);
            }
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public function pagoContratoAmortizacionInteres(Request $request)
    {
        \DB::enableQueryLog();
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                $_contrato = Contrato::find($request['idContrato']);
                $fecha_actual = Carbon::parse($request['fecha_pago'])->format('d-m-Y');
                //$resFechaProximo = date("d-m-Y",strtotime($fecha_actual."+ 1 months"));
                $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+30 day"));

                $ultimoPago = Pagos::where('contrato_id', $_contrato->id)->get()->last();
                $total_cancelado = $request['cancelado_bs'];
                $nuevo_interes = $request['resultado_nuevo_interes_bs'];
                $nuevo_gasto = $request['resultado_nuevo_gasto_bs'];
                if ($_contrato->moneda_id == 2) {
                    $nuevo_interes = $request['resultado_nuevo_interes_sus'];
                    $nuevo_gasto = $request['resultado_nuevo_gasto_sus'];
                    $total_cancelado = $request['cancelado_sus'];
                }

                $ultimoPago = Pagos::where('contrato_id', $_contrato->id)
                    ->orderBy('id', 'ASC')
                    ->get()->last();

                $resta_mora = 0;
                $moratorio_calculado_bs = $request->moratorio_calculado_bs;
                $moratorio_calculado_sus = $request->moratorio_calculado_sus;
                $interes_moratorios = $request->interes_moratorios;
                $interes_moratorios2 = $request->interes_moratorios2;

                $total_ai = 0;
                if ($_contrato->moneda_id == 1) {
                    $resta_mora = $moratorio_calculado_bs;
                    if ((float)$moratorio_calculado_bs > (float)$interes_moratorios) {
                        $resta_mora = $moratorio_calculado_bs - $interes_moratorios;
                    }
                    $total_ai = $request->cancelado_bs;
                } else {
                    $resta_mora = $interes_moratorios2 - $moratorio_calculado_sus;
                    $total_ai = $request->cancelado_sus;
                }
                $resta_mora = \number_format($resta_mora, 2);

                $idPago = Pagos::create([
                    'contrato_id'          => $request['idContrato'],
                    'sucursal_id'          => session::get('ID_SUCURSAL'),
                    'fecha_pago'           => $request['fecha_pago'],
                    'fecha_inio'           => $ultimoPago->fecha_inio,
                    'fecha_fin'            => $ultimoPago->fecha_fin,
                    'caja'                 => session::get('CAJA'),
                    'cuota_mora'           => $resta_mora,
                    'capital'              => $ultimoPago->capital,
                    'total_capital'        => $ultimoPago->total_capital,
                    'interes'              => \number_format($nuevo_interes, 2),
                    'comision'             => \number_format($nuevo_gasto, 2),
                    'total_ai'             => $total_ai,
                    'estado'               => 'AMORTIZACIÓN INTERES',
                    'estado_id'            => 1,
                    'usuario_id'           => session::get('ID_USUARIO'),
                    'moneda_id'            => $_contrato->moneda_id
                ])->id;

                $bitacora = \DB::getQueryLog();
                foreach ($bitacora as $i => $query) {
                    $resultado = json_encode($query);
                }
                \DB::disableQueryLog();
                LogSeguimiento::create([
                    'usuario_id'   => session::get('ID_USUARIO'),
                    'metodo'   => 'POST',
                    'accion'   => 'CREACION',
                    'detalle'  => "el usuario" . session::get('USUARIO') . " agrego un nuevo registro",
                    'modulo'   => 'PAGO AMORTIZACIÓN',
                    'consulta' => $resultado,
                ]);

                //INICIO FIN CAJA
                $datoInicioCaja = InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                    ->whereIn('estado_id', [1, 2])
                    ->first();
                //dd($datoInicioCaja);

                $contadorInicioCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                    ->where('estado_id', 1)->count();
                //dd($contadorInicioCajaDetalle);                    


                if ($contadorInicioCajaDetalle == 0) {
                    $inicioCajaBs = $datoInicioCaja->inicio_caja_bs;
                    $idInicioCaja = $datoInicioCaja->id;
                } else {
                    $datoCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))->where('caja', session::get('CAJA'))->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))->where('estado_id', 1)->orderBy('id', 'DESC')->first();
                    $inicioCajaBs = $datoCajaDetalle->inicio_caja_bs;
                    $idInicioCaja = $datoCajaDetalle->inicio_fin_caja_id;
                }

                $resulInicioCaja = (float)$inicioCajaBs + (float)$request['cancelado_bs'];
                if ($_contrato->moneda_id == 2) {
                    $resulInicioCaja = (float)$inicioCajaBs + (float)$request['cancelado_sus'];
                }

                $datoContrato = Contrato::where('id', $request['idContrato'])->first();
                if ($datoContrato->codigo != "") {
                    $codigoContrato = $datoContrato->codigo;
                } else {
                    $codigoContrato = $datoContrato->codigo_num;
                }

                InicioFinCajaDetalle::create([
                    'inicio_fin_caja_id'    => $idInicioCaja,
                    'contrato_id'           => $request['idContrato'],
                    'pago_id'               => $idPago,
                    'sucursal_id'           => session::get('ID_SUCURSAL'),
                    'fecha_pago'            => Carbon::parse($request['fecha_pago'])->format('Y-m-d'),
                    'fecha_hora'            => Carbon::now('America/La_Paz'),
                    'inicio_caja_bs'        => round($resulInicioCaja, 2),
                    'ingreso_bs'             => round($request['cancelado_bs'], 2),
                    // 'tipo_de_movimiento'    => 'PAGO TOTAL AL N° '. $datoContrato->codigo .' DEL  SR.(A) '. $datoContrato->cliente->persona->nombreCompleto() .' EN LA CAJA '. session::get('CAJA') .'.' ,
                    'tipo_de_movimiento'    => 'AMORTIZACIÓN N° ' . $codigoContrato . ' DEL SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA ' . session::get('CAJA') . '.',
                    'ref'               => 'MA01',
                    'caja'              => session::get('CAJA'),
                    'usuario_id'        => session::get('ID_USUARIO'),
                    'estado_id'         => 1,
                    'moneda_id'         => $_contrato->moneda_id
                ]);

                //ACTUALIZAMOS CONTRATO
                $fecha_actual = Carbon::parse($request['fecha_pago'])->format('d-m-Y');
                //$resFechaProximo = date("d-m-Y",strtotime($fecha_actual."+ 1 months"));
                $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+30 day"));
                $contrato = Contrato::find($request['idContrato']);
                $contrato->fecha_pago                      = $request['fecha_pago'];
                $contrato->interes                         = $nuevo_interes;
                $contrato->comision                         = $nuevo_gasto;
                $contrato->estado_pago                     = 'amortizacion interes';
                $contrato->fecha_fin                       = Carbon::parse($resFechaProximo)->format('Y-m-d');
                $contrato->estado_id                       = 1;
                $contrato->usuario_id                      = session::get('ID_USUARIO');
                $contrato->save();

                $bitacora = \DB::getQueryLog();
                foreach ($bitacora as $i => $query) {
                    $resultado = json_encode($query);
                }
                \DB::disableQueryLog();
                LogSeguimiento::create([
                    'usuario_id'   => session::get('ID_USUARIO'),
                    'metodo'   => 'PUT',
                    'accion'   => 'ACTUALIZAR CONTRATO',
                    'detalle'  => "el usuario" . session::get('USUARIO') . " actualizo un registro",
                    'modulo'   => 'CONTRATO',
                    'consulta' => $resultado,
                ]);

                /*REGISTRAR PARTE CONTABLE*/
                $numComprobante = ContaDiario::max('num_comprobante');

                $total_cancelado = $request['cancelado_bs'];
                $totalComisionInteres = $total_cancelado;
                // if ($_contrato->moneda_id == 2) {
                //     // convertir a bolivianos
                //     $valores_cambio = CambioMoneda::first();
                //     $totalComisionInteres = round((float)$valores_cambio->valor_bs * (float)$total_cancelado, 2);
                // }
                ContaDiario::create([
                    'contrato_id'        => $request['idContrato'],
                    'pagos_id'           => $idPago,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $request['fecha_pago'],
                    'fecha_b'            => $request['fecha_pago'],
                    'glosa'              => 'AMORTIZACIÓN N° ' . $codigoContrato . ' DEL SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' DE LA CAJA ' . session::get('CAJA') . '.',
                    'cod_deno'              => '41101',
                    'cuenta'                => 'Intereses prestamos a plazo fijo cartera vigente',
                    'debe'                  => '0.00',
                    'haber'                 => $totalComisionInteres,
                    'caja'                  => session::get('CAJA'),
                    'num_comprobante'       => $numComprobante + 1,
                    'periodo'               => 'mes',
                    'tcom'                  => 'INGRESO',
                    'ref'                   => 'MA01',
                    'usuario_id'            => session::get('ID_USUARIO'),
                    'estado_id'             => 1
                ]);
                return response()->json(["Mensaje" => "1", "idPago" => $idPago]);
            } else {
                return response()->json(["Mensaje" => "0"]);
            }
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public static function buscaAmortizacionContrato($contrato_id)
    {
        $desembolso_inicial = Pagos::where('contrato_id', $contrato_id)
            ->where('estado', 'DESEMBOLSO')
            ->get()
            ->first();
        $existe_amortizacion_ig = Pagos::where('contrato_id', $contrato_id)
            ->where('estado', 'AMORTIZACIÓN INTERES')
            ->orderBy('id', 'ASC')
            ->get();

        $valores_cambio = CambioMoneda::first();

        $valor_amortizacion_interes_bs = 0;
        $valor_amortizacion_interes_sus = 0;
        $valor_amortizacion_gastos_bs = 0;
        $valor_amortizacion_gastos_sus = 0;
        $sw_amortizacion = false;
        $total_ai_bs = 0;
        $total_ai_sus = 0;
        if (count($existe_amortizacion_ig) > 0) {

            $sw_amortizacion = true;
            foreach ($existe_amortizacion_ig as $ea) {
                $total_ai_bs += (float)$ea->total_ai;
            }
            $total_ai_sus = $total_ai_bs;
            if ($desembolso_inicial->moneda_id == 1) {
                $total_ai_sus = $total_ai_sus / $valores_cambio->valor_bs;
            }
            // $valor_amortizacion_interes_bs = (float)$desembolso_inicial->interes - (float)$existe_amortizacion_ig->interes;
            // $valor_amortizacion_gastos_bs = (float)$desembolso_inicial->comision - (float)$existe_amortizacion_ig->comision;
            // 
            // $valor_amortizacion_interes_sus = (float)$desembolso_inicial->interes - (float)$existe_amortizacion_ig->interes;
            // $valor_amortizacion_gastos_sus = (float)$desembolso_inicial->comision - (float)$existe_amortizacion_ig->comision;
            // 
            // if ($desembolso_inicial->moneda_id == 1) {
            // $valor_amortizacion_interes_sus = $valor_amortizacion_interes_sus / $valores_cambio->valor_bs;
            // $valor_amortizacion_gastos_sus = $valor_amortizacion_gastos_sus / $valores_cambio->valor_bs;
            // } else {
            // $valor_amortizacion_interes_bs = $valor_amortizacion_interes_bs / $valores_cambio->valor_bs;
            // $valor_amortizacion_gastos_bs = $valor_amortizacion_gastos_bs / $valores_cambio->valor_bs;
            // }
        }

        return  [
            'sw' => $sw_amortizacion,
            'valor_amortizacion_interes_bs' => $valor_amortizacion_interes_bs,
            'valor_amortizacion_interes_sus' => $valor_amortizacion_interes_sus,
            'valor_amortizacion_gastos_bs' => $valor_amortizacion_gastos_bs,
            'valor_amortizacion_gastos_sus' => $valor_amortizacion_gastos_sus,
            'total_ai_bs' => $total_ai_bs,
            'total_ai_sus' => $total_ai_sus,
        ];
    }

    public static function getInteresesConAmortizacion($total_ai_bs, $interes, $comision, $cuotaMora)
    {
        $amortizacion_total = $total_ai_bs;
        $nuevo_interes = $amortizacion_total - $interes;
        $nueva_comision = 0;
        $nueva_cuotaMora = 0;
        if ($nuevo_interes > 0) {
            $interes = 0;
            $nueva_comision = $nuevo_interes - $comision;
            if ($nueva_comision > 0) {
                $comision = 0;
                $nueva_cuotaMora = $nueva_comision - $cuotaMora;
                if ($nueva_cuotaMora >= 0) {
                    $cuotaMora = 0;
                } else {
                    $cuotaMora = $nueva_cuotaMora;
                    if ($nueva_cuotaMora < 0) {
                        $cuotaMora = $nueva_cuotaMora * -1;
                    }
                }
            } else {
                $comision = $nueva_comision;
                if ($nueva_comision < 0) {
                    $comision = $nueva_comision * -1;
                }
            }
        } else {
            $interes = $nuevo_interes;
            if ($nuevo_interes < 0) {
                $interes = $nuevo_interes * -1;
            }
        }
        return [
            "interes" => $interes,
            "comision" => $comision,
            "cuotaMora" => $cuotaMora,
            "total_amortizacion_interes" => $amortizacion_total
        ];
    }

    public function buscarPagosDetalleUltimo(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $sw_amortizacion = false;
            $desembolso_inicial = Pagos::where('contrato_id', $request['idContrato'])
                ->where('estado', 'DESEMBOLSO')
                ->get()
                ->first();

            if (!$desembolso_inicial) {
                $desembolso_inicial = ContratoController::inicializa_desembolso_pago($request['idContrato']);
            }

            $existe_amortizacion_ig = Pagos::where('contrato_id', $request['idContrato'])
                ->where('estado', 'AMORTIZACIÓN INTERES')
                ->orderBy('id', 'ASC')
                ->get();

            $valores_cambio = CambioMoneda::first();

            $suma_moratorios_bs = 0;
            $suma_moratorios_sus = 0;
            $moratorios = Pagos::where('contrato_id', $request['idContrato'])
                ->where('estado', 'AMORTIZACIÓN INTERES')
                ->orderBy('id', 'ASC')
                ->get();
            foreach ($moratorios as $value) {
                $suma_moratorios_bs -= $value->cuota_mora;
                $suma_moratorios_sus -= $value->cuota_mora;
            }

            if ($desembolso_inicial->moneda_id == 1) {
                $suma_moratorios_sus = $suma_moratorios_sus / $valores_cambio->valor_bs;
            } else {
                $suma_moratorios_bs = $suma_moratorios_bs * $valores_cambio->valor_bs;
            }

            $valor_amortizacion_interes_bs = 0;
            $valor_amortizacion_interes_sus = 0;
            $valor_amortizacion_gastos_bs = 0;
            $valor_amortizacion_gastos_sus = 0;
            $valor_amortizacion_cuota_mora_bs = 0;
            $valor_amortizacion_cuota_mora_sus = 0;
            $total_ai_bs = 0;
            $total_ai_sus = 0;
            if (count($existe_amortizacion_ig) > 0) {

                $sw_amortizacion = true;
                foreach ($existe_amortizacion_ig as $ea) {
                    $total_ai_bs += (float)$ea->total_ai;
                }
                $total_ai_sus = $total_ai_bs;
                if ($desembolso_inicial->moneda_id == 1) {
                    $total_ai_sus = $total_ai_sus / $valores_cambio->valor_bs;
                }
                // $valor_amortizacion_interes_bs = (float)$desembolso_inicial->interes - (float)$existe_amortizacion_ig->interes;
                // $valor_amortizacion_gastos_bs = (float)$desembolso_inicial->comision - (float)$existe_amortizacion_ig->comision;
                // $valor_amortizacion_cuota_mora_bs = (float)$desembolso_inicial->cuota_mora - (float)$existe_amortizacion_ig->cuota_mora;
                // 
                // $valor_amortizacion_interes_sus = (float)$desembolso_inicial->interes - (float)$existe_amortizacion_ig->interes;
                // $valor_amortizacion_gastos_sus = (float)$desembolso_inicial->comision - (float)$existe_amortizacion_ig->comision;
                // $valor_amortizacion_cuota_mora_sus = (float)$desembolso_inicial->cuota_mora - (float)$existe_amortizacion_ig->cuota_mora;

                // if ($desembolso_inicial->moneda_id == 1) {
                //     $valor_amortizacion_interes_sus = $valor_amortizacion_interes_sus / $valores_cambio->valor_bs;
                //     $valor_amortizacion_gastos_sus = $valor_amortizacion_gastos_sus / $valores_cambio->valor_bs;
                //     $valor_amortizacion_cuota_mora_sus = $valor_amortizacion_cuota_mora_sus / $valores_cambio->valor_bs;
                // } else {
                //     $valor_amortizacion_interes_bs = $valor_amortizacion_interes_bs / $valores_cambio->valor_bs;
                //     $valor_amortizacion_gastos_bs = $valor_amortizacion_gastos_bs / $valores_cambio->valor_bs;
                //     $valor_amortizacion_cuota_mora_bs = $valor_amortizacion_cuota_mora_bs / $valores_cambio->valor_bs;
                // }
            }

            $pagos = Pagos::select('pagos.*', 'monedas.desc_corta', 'contrato.p_interes')
                ->join('monedas', 'monedas.id', '=', 'pagos.moneda_id')
                ->join('contrato', 'contrato.id', '=', 'pagos.contrato_id')
                ->where('contrato_id', $request['idContrato'])->where('pagos.estado_id', 1)->orderBy('fecha_inio', 'DESC')->first();

            //dd($pagos);
            if ($pagos) {
                if ($request->ajax()) {
                    $cambio = CambioMoneda::first();
                    $bs = Moneda::where('id', 1)->get()->first();
                    $sus = Moneda::where('id', 2)->get()->first();

                    return response()->json([
                        "Resultado" => $pagos,
                        "total_ai_bs" => $total_ai_bs,
                        "total_ai_sus" => $total_ai_sus,
                        'txtBs' => $bs,
                        'txtSus' => $sus,
                        'cambioMonedas' => $cambio,
                        'sw_amortizacion' => $sw_amortizacion,
                        'valor_amortizacion_interes_bs' => $valor_amortizacion_interes_bs,
                        'valor_amortizacion_interes_sus' => $valor_amortizacion_interes_sus,
                        'valor_amortizacion_gastos_bs' => $valor_amortizacion_gastos_bs,
                        'valor_amortizacion_gastos_sus' => $valor_amortizacion_gastos_sus,
                        'valor_amortizacion_cuota_mora_bs' => $valor_amortizacion_cuota_mora_bs,
                        'valor_amortizacion_cuota_mora_sus' => $valor_amortizacion_cuota_mora_sus,
                        'suma_moratorios_bs' => $suma_moratorios_bs,
                        'suma_moratorios_sus' => $suma_moratorios_sus,
                    ]);
                }
                return view('pagos.index');
            }
        } else {
            return view("layout.login");
        }
    }
    public function imprimirReporteInteres($id)
    {
        $valores_cambio = CambioMoneda::first();
        $pago = Pagos::where('id', $id)->first();
        //dd($pago->contrato_id);
        $cliente = Cliente::where('id', $pago->contrato->cliente_id)->first();
        //dd($cliente);
        $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora;
        $totalInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;
        //$totalSoloInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;             
        //dd($pago->contrato->);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(80, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $pago->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(90, 34);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(70, 39);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PAGO INTERES Y RENOVACIÓN', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CREDITO No.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(48, 52);
        $gestion = substr($pago->contrato->gestion, 2, 2);
        //$pdf::Cell($w=0, $h=0, $pago->caja .'  '.$pago->contrato->codigo, $border=0, $ln=50, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='B', $valign='B');
        //$resCodigo =  $pago->contrato->sucural->nuevo_codigo .''. Carbon::parse($pago->contrato->fecha_contrato)->format('y') .''. $pago->contrato->codigo_num; 
        $resCodigo =  $pago->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $pago->contrato->codigo_num;
        if ($pago->contrato->codigo != "") {
            $codigoG = $pago->caja . '  ' . $pago->contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->caja . '  ' . $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $pago->caja . '  ' . $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->caja . '  ' . $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }
        $totalInteres_convertido = 0;
        $interes_convertido = 0;
        $comision_convertido = 0;
        $capital_convertido = 0;
        $totalPagar_convertido = 0;
        $cuota_mora_convertido = 0;
        $primer_pago_capital = Pagos::where('contrato_id', $pago->contrato_id)->get()->first();

        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        if ($pago->moneda_id == 2) {
            $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
            $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
            $valor_comparacion3 = 15000 / $valores_cambio->valor_bs;
        }

        if ((float)$pago->capital <= $valor_comparacion1) {
            $interes = ((float)$pago->capital * $pago->contrato->p_interes) / 100;
            $comision = ((float)$pago->capital * 6.04) / 100;
        } elseif ($pago->capital < $valor_comparacion2) {
            $interes = ((float)$pago->capital * $pago->contrato->p_interes) / 100;
            $comision = ((float)$pago->capital * 3.7) / 100;
        } elseif ($pago->capital < $valor_comparacion3) {
            $interes = ((float)$pago->capital * $pago->contrato->p_interes) / 100;
            $comision = ((float)$pago->capital * 3) / 100;
        } else {
            $interes = ((float)$pago->capital * $pago->contrato->p_interes) / 100;
            $comision = ((float)$pago->capital * 2) / 100;
        }

        $totalInteres = (float)$pago->capital + (float)$interes + (float)$comision;

        $existe_amortizacion = PagosController::buscaAmortizacionContrato($pago->contrato_id);
        if ($existe_amortizacion['sw']) {
            // $nuevos_valores = self::getInteresesConAmortizacion($existe_amortizacion["total_ai_bs"], $pago->interes, $pago->comision, $pago->cuotaMora);
            $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora;
            $totalInteres = (float)$pago->capital + (float)$interes + (float)$comision;
        }

        if ($pago->moneda_id == 1) {
            // DOLARES
            $capital_convertido = (float)$pago->capital / (float) $valores_cambio->valor_bs;
            $totalPagar_convertido = (float)$totalPagar / (float) $valores_cambio->valor_bs;
            $totalInteres_convertido = (float)$totalInteres / (float) $valores_cambio->valor_bs;
            $interes_convertido = (float)$pago->interes / (float) $valores_cambio->valor_bs;
            $comision_convertido = (float)$pago->comision / (float) $valores_cambio->valor_bs;
            $cuota_mora_convertido = (float)$pago->cuota_mora / (float) $valores_cambio->valor_bs;
        } else {
            // DOLARES
            $capital_convertido = $pago->capital;
            $totalPagar_convertido = $totalPagar;
            $totalInteres_convertido = $totalInteres;
            $interes_convertido = $pago->interes;
            $comision_convertido = $pago->comision;
            $cuota_mora_convertido = $pago->cuota_mora;
        }

        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        if ($pago->moneda_id == 2) {
            $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
            $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
            $valor_comparacion3 = 15000 / $valores_cambio->valor_bs;
        }

        $tasa_interes = 5;
        if ($totalPagar <= $valor_comparacion1) {
            $tasa_interes = 9.04;
        } elseif ($totalPagar < $valor_comparacion2) {
            $tasa_interes = 6.7;
        } elseif ($totalPagar < $valor_comparacion3) {
            $tasa_interes = 6;
        }

        // $totalPagar_convertido = CambioMoneda::ajustaDecimal($totalPagar_convertido);

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PRESTADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($capital_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = 'SALDO CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($capital_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL A PAGAR MAS INTERES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(85, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($totalInteres_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INICIO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(165, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = date('Y-m-d', strtotime($pago->fecha_inio)), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'VENCIMIENTO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(165, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = date('Y-m-d', strtotime($pago->fecha_fin)), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INTERES DEL CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($interes_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = 'COMISIONES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($comision_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = 'ATRASO DIAS    ' . $pago->dias_atraso, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($cuota_mora_convertido, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = round($totalPagar_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(132, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(138, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(112, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(18, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Antes de firmar verifique sus datos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $generarCodigo = $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $pago->fecha_inio . "-" . round($totalPagar, 2);
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 174, 69, 22, 22, $style, 'N');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Contrato Interes');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('ContratoInteress.pdf');
    }

    public function reImprimirReporteInteres($id)
    {
        $valores_cambio = CambioMoneda::first();
        $pago = Pagos::where('id', $id)->first();
        //dd($pago->contrato_id);
        $cliente = Cliente::where('id', $pago->contrato->cliente_id)->first();
        //dd($cliente);
        $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora;
        $totalInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;
        //$totalSoloInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;             
        //dd($pago->contrato->);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(80, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $pago->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(90, 34);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(60, 39);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PAGO INTERES Y RENOVACIÓN - REIMPRESION', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CREDITO No.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(48, 52);
        $gestion = substr($pago->contrato->gestion, 2, 2);
        //$pdf::Cell($w=0, $h=0, $pago->caja .'  '.$pago->contrato->codigo, $border=0, $ln=50, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='B', $valign='B');
        // $resCodigo =  $pago->contrato->sucural->nuevo_codigo .''. Carbon::parse($pago->contrato->fecha_contrato)->format('y') .''. $pago->contrato->codigo_num; 
        $resCodigo =  $pago->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $pago->contrato->codigo_num;
        if ($pago->contrato->codigo != "") {
            $codigoG = $pago->caja . '  ' . $pago->contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->caja . '  ' . $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $pago->caja . '  ' . $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->caja . '  ' . $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        if ($pago->moneda_id == 2) {
            $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
            $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
            $valor_comparacion3 = 15000 / $valores_cambio->valor_bs;
        }

        if ((float)$pago->capital <= $valor_comparacion1) {
            $interes = ((float)$pago->capital * $pago->contrato->p_interes) / 100;
            $comision = ((float)$pago->capital * 6.04) / 100;
        } elseif ($pago->capital < $valor_comparacion2) {
            $interes = ((float)$pago->capital * $pago->contrato->p_interes) / 100;
            $comision = ((float)$pago->capital * 3.7) / 100;
        } elseif ($pago->capital < $valor_comparacion3) {
            $interes = ((float)$pago->capital * $pago->contrato->p_interes) / 100;
            $comision = ((float)$pago->capital * 3) / 100;
        } else {
            $interes = ((float)$pago->capital * $pago->contrato->p_interes) / 100;
            $comision = ((float)$pago->capital * 2) / 100;
        }

        $totalInteres = (float)$pago->capital + (float)$interes + (float)$comision;

        $existe_amortizacion = PagosController::buscaAmortizacionContrato($pago->contrato_id);
        if ($existe_amortizacion['sw']) {
            // $nuevos_valores = self::getInteresesConAmortizacion($existe_amortizacion["total_ai_bs"], $interes, $comision, $pago->cuotaMora);
            // $interes = $nuevos_valores["interes"];
            // $comision = $nuevos_valores["comision"];
            // $cuota_mora = $nuevos_valores["cuotaMora"];
            $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora;
            $totalInteres = (float)$pago->capital + (float)$interes + (float)$comision;
        }

        $totalInteres_convertido = 0;
        $interes_convertido = 0;
        $comision_convertido = 0;
        $capital_convertido = 0;
        $totalPagar_convertido = 0;
        if ($pago->moneda_id == 1) {
            // $totalPagar = CambioMoneda::ajustaDecimal($totalPagar);
            // DOLARES
            $capital_convertido = round((float)$pago->capital / (float) $valores_cambio->valor_bs, 2);
            $totalPagar_convertido = round((float)$totalPagar / (float) $valores_cambio->valor_bs, 2);
            $totalInteres_convertido = round((float)$totalInteres / (float) $valores_cambio->valor_bs, 2);
            $interes_convertido = round((float)$pago->interes / (float) $valores_cambio->valor_bs, 2);
            $comision_convertido = round((float)$pago->comision / (float) $valores_cambio->valor_bs, 2);
        } else {
            // DOLARES
            $capital_convertido = $pago->capital;
            $totalPagar_convertido = $totalPagar;
            $totalInteres_convertido = $totalInteres;
            $interes_convertido = $pago->interes;
            $comision_convertido = $pago->comision;
        }

        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        if ($pago->moneda_id == 2) {
            $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
            $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
            $valor_comparacion3 = 15000 / $valores_cambio->valor_bs;
        }

        $tasa_interes = 5;
        if ($totalPagar <= $valor_comparacion1) {
            $tasa_interes = 9.04;
        } elseif ($totalPagar < $valor_comparacion2) {
            $tasa_interes = 6.7;
        } elseif ($totalPagar < $valor_comparacion3) {
            $tasa_interes = 6;
        }

        // $totalPagar_convertido = CambioMoneda::ajustaDecimal($totalPagar_convertido);
        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PRESTADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($capital_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = 'SALDO CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($capital_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL A PAGAR MAS INTERES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(85, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($totalInteres_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INICIO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(165, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = date('Y-m-d', strtotime($pago->fecha_inio)), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'VENCIMIENTO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(165, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = date('Y-m-d', strtotime($pago->fecha_fin)), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INTERES DEL CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($interes_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = 'COMISIONES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($pago->comision, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = 'ATRASO DIAS    ' . $pago->dias_atraso, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = round($pago->cuota_mora, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($totalPagar_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(132, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(138, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(112, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(18, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Antes de firmar verifique sus datos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $generarCodigo = "REIMPRESION" . $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $pago->fecha_inio . "-" . round($totalPagar, 2);
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 174, 69, 22, 22, $style, 'N');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Contrato Interes');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('ContratoInteress.pdf');
    }

    public function imprimirReportePagoTotal($id)
    {
        $valores_cambio = CambioMoneda::first();
        $pago = Pagos::where('id', $id)->first();
        //dd($pago->contrato_id);
        $cliente = Cliente::where('id', $pago->contrato->cliente_id)->first();
        //dd($cliente);
        $totalPagar = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora;
        $totalInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;
        //$totalSoloInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;             
        //dd($pago->contrato->);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            //$pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(80, 25);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $pago->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(100, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(75, 35);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PAGO CUOTA CREDITO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 42);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CREDITO No.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 14);
        $pdf::SetXY(45, 48);
        $gestion = substr($pago->contrato->gestion, 2, 2);
        // $pdf::Cell($w=0, $h=0, $txt=$pago->contrato->codigo, $border=0, $ln=50, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='B', $valign='B');
        // $resCodigo =  $pago->contrato->sucural->nuevo_codigo .''. Carbon::parse($pago->contrato->fecha_contrato)->format('y') .''. $pago->contrato->codigo_num; 
        $resCodigo =  $pago->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $pago->contrato->codigo_num;
        if ($pago->contrato->codigo != "") {
            $codigoG = $pago->contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        /*********************************** */

        $interes_convertido = $pago->interes;
        if ($pago->moneda_id == 1) {
            $interes_convertido = round($pago->interes / $valores_cambio->valor_bs, 2);
        }

        $pdf::SetFont('helvetica', 'B', 10);
        $pdf::SetXY(15, 54);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Interés', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(160, 54);
        $pdf::Cell($w = 0, $h = 0, $txt = $interes_convertido . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $capital_convertido = 0;
        $totalPagar_convertido = 0;
        if ($pago->moneda_id == 1) {
            // $totalPagar = CambioMoneda::ajustaDecimal($totalPagar);
            // DOLARES
            $capital_convertido = number_format((float)$pago->total_capital / (float) $valores_cambio->valor_bs, 2, ".", "");
            $totalPagar_convertido = number_format((float)$totalPagar / (float) $valores_cambio->valor_bs, 2, ".", "");
        } else {
            // DOLARES
            $capital_convertido = $pago->total_capital;
            $totalPagar_convertido = $totalPagar;
        }

        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        if ($pago->moneda_id == 2) {
            $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
            $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
            $valor_comparacion3 = 15000 / $valores_cambio->valor_bs;
        }

        $tasa_interes = 5;
        if ($totalPagar <= $valor_comparacion1) {
            $tasa_interes = 9.04;
        } elseif ($totalPagar < $valor_comparacion2) {
            $tasa_interes = 6.7;
        } elseif ($totalPagar < $valor_comparacion3) {
            $tasa_interes = 6;
        }

        $gastos_deuda = 0;
        $porcentaje_deuda = $tasa_interes - $pago->contrato->p_interes;
        // $gastos_deuda = number_format($totalPagar_convertido * ($porcentaje_deuda / 100), 2, '.', ',');
        $gastos_deuda = $pago->comision + $pago->cuota_mora;

        if ($pago->moneda_id == 1) {
            $gastos_deuda = $gastos_deuda / $valores_cambio->valor_bs;
        }

        // $totalPagar_convertido = CambioMoneda::ajustaDecimal($totalPagar_convertido);

        $pdf::SetXY(15, 59);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Gastos de Deuda y Custodia', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(160, 59);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($gastos_deuda, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Sub Total Pagado', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(160, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($gastos_deuda + $interes_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $nl = new NumberToLetterConverter();
        $pdf::SetXY(15, 69);
        $pdf::Cell($w = 0, $h = 0, $nl->numtoletras(\number_format($gastos_deuda + $interes_convertido, 2, '.', '')) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 74);
        $pdf::Cell($w = 0, $h = 0, 'Contrato: ' . $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(75, 74);
        $pdf::Cell($w = 0, $h = 0, 'Código Cliente: ' . $pago->contrato->cliente->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(140, 74);
        $pdf::Cell($w = 0, $h = 0, 'Tipo de Cambio: ' . $valores_cambio->valor_bs, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 79);
        $pdf::Cell($w = 0, $h = 0, 'Cancelación: $us ' . $totalPagar_convertido, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(75, 79);
        $pdf::Cell($w = 0, $h = 0, 'Nuevo Saldo: $us 0.00', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(130, 79);
        $pdf::Cell($w = 0, $h = 0, 'Total Pagado: $us ' . $totalPagar_convertido, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        /*********************************** */
        $pdf::SetFont('helvetica', 'B', 12);
        $pdf::SetXY(124, 42);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FECHA HORA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(154, 42);
        $pdf::Cell($w = 0, $h = 0, Carbon::now('America/La_Paz')->format('Y-m-d H:i:s'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $agencia_recojo = $pago->contrato->sucural->nombre;
        if ($pago->contrato->solicitud) {
            $agencia_recojo = $pago->contrato->solicitud->sucursal->nombre;
        }
        $pdf::SetXY(100, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA RECOJO - ' . $agencia_recojo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(205, 55);

        $pdf::SetXY(132, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(138, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(113, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(18, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Antes de firmar verifique sus datos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $generarCodigo = $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $pago->fecha_inio . "-" . round($totalPagar, 2);
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 180, 65, 100, 100, $style, 'N');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Contrato Pago Total');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('ContratoPagoTotal.pdf');
    }

    public function reImprimirReportePagoTotal($id)
    {
        $valores_cambio = CambioMoneda::first();
        $pago = Pagos::where('id', $id)->first();
        //dd($pago->contrato_id);
        $cliente = Cliente::where('id', $pago->contrato->cliente_id)->first();
        //dd($cliente);
        $totalPagar = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora;
        $totalInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;
        //$totalSoloInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;             
        //dd($pago->contrato->);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            //$pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(80, 25);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $pago->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(100, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(75, 35);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PAGO CUOTA CREDITO - REIMPRESION', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 42);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CREDITO No.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 14);
        $pdf::SetXY(45, 48);
        $gestion = substr($pago->contrato->gestion, 2, 2);
        // $pdf::Cell($w=0, $h=0, $txt=$pago->contrato->codigo, $border=0, $ln=50, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='B', $valign='B');
        // $resCodigo =  $pago->contrato->sucural->nuevo_codigo .''. Carbon::parse($pago->contrato->fecha_contrato)->format('y') .''. $pago->contrato->codigo_num; 
        $resCodigo =  $pago->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $pago->contrato->codigo_num;
        if ($pago->contrato->codigo != "") {
            $codigoG = $pago->contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        /*********************************** */
        $pdf::SetFont('helvetica', 'B', 10);
        $pdf::SetXY(15, 54);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Interés', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(160, 54);
        $pdf::Cell($w = 0, $h = 0, $txt = round($pago->interes, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $capital_convertido = 0;
        $totalPagar_convertido = 0;
        if ($pago->moneda_id == 1) {
            $totalPagar = CambioMoneda::ajustaDecimal($totalPagar);
            // DOLARES
            $capital_convertido = round((float)$pago->total_capital / (float) $valores_cambio->valor_bs, 2);
            $totalPagar_convertido = round((float)$totalPagar / (float) $valores_cambio->valor_bs, 2);
        } else {
            // DOLARES
            $capital_convertido = $pago->total_capital;
            $totalPagar_convertido = $totalPagar;
        }

        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        if ($pago->moneda_id == 2) {
            $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
            $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
            $valor_comparacion3 = 15000 / $valores_cambio->valor_bs;
        }

        $tasa_interes = 5;
        if ($totalPagar <= $valor_comparacion1) {
            $tasa_interes = 9.04;
        } elseif ($totalPagar < $valor_comparacion2) {
            $tasa_interes = 6.7;
        } elseif ($totalPagar < $valor_comparacion3) {
            $tasa_interes = 6;
        }

        // $gastos_deuda = 0;
        // $porcentaje_deuda = $tasa_interes - $pago->contrato->p_interes;
        // $gastos_deuda = number_format($totalPagar_convertido * ($porcentaje_deuda / 100), 2, '.', ',');

        $gastos_deuda = 0;
        $porcentaje_deuda = $tasa_interes - $pago->contrato->p_interes;
        // $gastos_deuda = number_format($totalPagar_convertido * ($porcentaje_deuda / 100), 2, '.', ',');
        $gastos_deuda = $pago->comision + $pago->cuota_mora;

        if ($pago->moneda_id == 1) {
            $gastos_deuda = $gastos_deuda / $valores_cambio->valor_bs;
        }

        // $totalPagar_convertido = CambioMoneda::ajustaDecimal($totalPagar_convertido);

        $pdf::SetXY(15, 59);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Gastos de Deuda y Custodia', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(160, 59);
        $pdf::Cell($w = 0, $h = 0, $txt = round($gastos_deuda, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Sub Total Pagado', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(160, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = round($gastos_deuda + $pago->interes, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $nl = new NumberToLetterConverter();
        $pdf::SetXY(15, 69);
        $pdf::Cell($w = 0, $h = 0, $nl->numtoletras(round($gastos_deuda + $pago->interes, 2)) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 74);
        $pdf::Cell($w = 0, $h = 0, 'Contrato: ' . $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(75, 74);
        $pdf::Cell($w = 0, $h = 0, 'Código Cliente: ' . $pago->contrato->cliente->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(140, 74);
        $pdf::Cell($w = 0, $h = 0, 'Tipo de Cambio: ' . $valores_cambio->valor_bs, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 79);
        $pdf::Cell($w = 0, $h = 0, 'Cancelación: $us ' . $totalPagar_convertido, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(75, 79);
        $pdf::Cell($w = 0, $h = 0, 'Nuevo Saldo: $us 0.00', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(130, 79);
        $pdf::Cell($w = 0, $h = 0, 'Total Pagado: $us ' . $totalPagar_convertido, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        /*********************************** */
        $pdf::SetFont('helvetica', 'B', 12);
        $pdf::SetXY(124, 42);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FECHA HORA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(154, 42);
        $pdf::Cell($w = 0, $h = 0, Carbon::now('America/La_Paz')->format('Y-m-d H:i:s'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(100, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA RECOJO - ' . $pago->contrato->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(205, 55);

        $pdf::SetXY(132, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(138, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(113, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(18, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Antes de firmar verifique sus datos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $generarCodigo = $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $pago->fecha_inio . "-" . round($totalPagar, 2);
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 180, 65, 100, 100, $style, 'N');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Contrato Pago Total');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('ContratoPagoTotal.pdf');
    }

    public function imprimirFactura($id)
    {
        $valores_cambio = CambioMoneda::first();
        $pago = Pagos::where('id', $id)->first();
        //dd($pago->contrato_id);
        $cliente = Cliente::where('id', $pago->contrato->cliente_id)->first();
        //dd($cliente);
        $totalPagar = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora;
        $totalInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;

        //$totalSoloInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;             
        //dd($pago->contrato->);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            //$pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $capital_convertido = 0;
        $interes_convertido = 0;
        $totalPagar_convertido = 0;
        $comision_convertido = 0;
        $mora_convertido = 0;
        if ($pago->moneda_id == 1) {
            $totalPagar = CambioMoneda::ajustaDecimal($totalPagar);
            // DOLARES
            $capital_convertido = round((float)$pago->capital / (float) $valores_cambio->valor_bs, 2);
            $totalPagar_convertido = round((float)$totalPagar / (float) $valores_cambio->valor_bs, 2);
            $comision_convertido = round((float)$pago->comision, 2);
            $interes_convertido = round((float)$pago->interes, 2);
            $mora_convertido = round((float)$pago->mora, 2);
        } else {
            // DOLARES
            $capital_convertido = $pago->capital;
            $totalPagar_convertido = $totalPagar;

            $comision_convertido = (float)$pago->comision * (float) $valores_cambio->valor_bs;
            $interes_convertido = (float)$pago->interes * (float) $valores_cambio->valor_bs;
            $mora_convertido = (float)$pago->mora * (float) $valores_cambio->valor_bs;
        }

        $pdf::AddPage('L', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(40, 10);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PRENDASOL S.R.L.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(47, 14.5);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CASA MATRIZ', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetFont('helvetica', 'N', 11);
        $pdf::SetXY(40, 19);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Calle Murillo Nº 420', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(40, 23.5);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Telf. 2454045, La Paz Bolivia', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 18);
        $pdf::SetXY(110, 15);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PAGO CUOTA DE CRÉDITO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetFont('helvetica', 'N', 14);
        $pdf::SetXY(144, 20.5);
        $pdf::Cell($w = 0, $h = 0, $txt = 'ORIGINAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $_sucursal = Sucursal::find(Session::get('ID_SUCURSAL'));
        $pdf::SetFont('helvetica', 'N', 11);
        $pdf::SetXY(30, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Sucursal ' . $_sucursal->id . ' - ' . $_sucursal->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(30, 36);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FECHA:   La Paz, ' . date('d/m/Y', strtotime($pago->fecha_pago)), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(130, 36);
        $pdf::Cell($w = 0, $h = 0, $txt = 'NIT/CI:  ' . $pago->contrato->cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(180, 36);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CLIENTE:  ' . $pago->contrato->cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(180, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CONTRATO:  ' . $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $style = '
        <style>
        table{
            border: solid 1px black;
            padding:5px;
        }

        td{
            padding:0px;
        }

        .texto{
            text-align:left;
            font-size: 11pt;
            font-weight: normal;
        }
        </style>';

        $pdf::SetXY(30, 40);
        $html = $style . '
        <table width="100%">
            <tr>
                <td class="texto" width="70%">
                Interés
                </td>
                <td class="texto" style="color:black;" width="15%">
                ' . number_format($interes_convertido, 2) . ' Bs
                </td>
            </tr>
            <tr>
                <td class="texto">
                Gastos de Deuda y Custodia
                </td>
                <td class="texto" style="color:black;">
                ' . number_format($comision_convertido, 2) . ' Bs
                </td>
            </tr>
            <tr>
                <td class="texto">
                Mora
                </td>
                <td class="texto" style="color:black;">
                ' . number_format($mora_convertido, 2) . ' Bs
                </td>
            </tr>
            <tr>
                <td class="texto">
                Otros Servicios
                </td>
                <td class="texto" style="color:black;">
                0.00 Bs
                </td>
            </tr>
            <tr>
                <td class="texto">
                Ingresos Devengados
                </td>
                <td class="texto" style="color:black;">
                0.00 Bs
                </td>
            </tr>
            <tr>
                <td class="texto">
                Sub Total Pagado
                </td>
                <td class="texto">
                ' . \number_format($interes_convertido + $pago->cuota_mora + $comision_convertido, 2) . ' Bs
                </td>
            </tr>
        </table>';
        $pdf::writeHTML($html, true, false, true, false, '');


        $pdf::setXY(205, 108);
        $pdf::Cell($w = 0, $h = 0, 'Total Pagado: ' . \number_format($totalPagar_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $pdf::SetXY(160, 100);
        $pdf::Cell($w = 0, $h = 0, 'Días de retraso: ' . $pago->dias_atraso, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $generarCodigo = $pago->contrato->codigo . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $pago->fecha_inio . "-" . round($totalPagar_convertido, 2);
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 255, 45, 35, 35, $style, 'N');

        $nl = new NumberToLetterConverter();
        $pdf::SetXY(30, 100);
        $pdf::Cell($w = 0, $h = 0, $nl->numtoletras(round($totalPagar_convertido, 2)) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(30, 108);
        $pdf::Cell($w = 0, $h = 0, 'Cancelación: ' . $capital_convertido . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(95, 108);
        $pdf::Cell($w = 0, $h = 0, 'Nuevo Saldo: 0.00 $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(150, 108);
        $pdf::Cell($w = 0, $h = 0, 'Fecha Vencimiento:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(30, 116);
        $pdf::Cell($w = 0, $h = 0, 'CÓDIGO DE CONTROL:     AB-8D-6C-41-8D', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $fecha_actual = Carbon::now('America/La_Paz')->format('Y-m-d');
        $pdf::SetXY(30, 124);
        $pdf::Cell($w = 0, $h = 0, 'FECHA LÍMITE DE EMISIÓN: ' . Date('d/m/Y', strtotime($fecha_actual . '+ 60days')), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(205, 100);
        $pdf::Cell($w = 0, $h = 0, 'Tipo de Cambio: ' . $valores_cambio->valor_bs, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Factura');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Factura.pdf');
    }

    public function imprimirCambioPago($id)
    {
        $pago = Pagos::where('id', $id)->first();
        $cliente = Cliente::where('id', $pago->cliente_id)->first();
        $totalPagar = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;
        $resCodigo =  $pago->sucural->nuevo_codigo . '' . Carbon::parse($pago->fecha_contrato)->format('y') . '' . $pago->codigo_num;
        $valores_cambio = CambioMoneda::first();

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 12);
        $pdf::AddPage('P', 'A4', false, false);

        $pdf::SetXY(20, 10);
        $pdf::Cell($w = 0, $h = 0, $txt = 'COMPROBANTE DE CAMBIO DE MONEDA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(140, 10);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PRENDASOL S.R.L.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'N', 12);
        $pdf::SetXY(20, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Agencia:    ' . $pago->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(20, 37);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Dirección:  ' . $pago->sucural->direccion, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(20, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Usuario:     ' . $pago->usuario->usuario, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(20, 51);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Fecha:       ' . date('d/m/Y', strtotime($pago->fecha_pago)), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(110, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI/NIT:   ' . $pago->contrato->cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(110, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Cliente:   ' . $pago->contrato->cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(20, 73);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TIPO CAMBIO OFICIAL:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(20, 82);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Importe recibido:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(20, 89);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Importe entregado:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(180, 73);
        $pdf::Cell($w = 0, $h = 0, $valores_cambio->valor_bs, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $capital_convertido_bs = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$pago->capital;
        $capital_convertido_sus = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$pago->capital;

        $interes_convertido_bs = $pago->interes;
        $interes_convertido_sus = $pago->interes;
        $comision_convetido_bs = $pago->comision;
        $comision_convetido_sus = $pago->comision;

        if ($pago->moneda_id == 2) {
            $interes_convertido_bs = (float)$interes_convertido_bs * (float)$valores_cambio->valor_bs;
            $comision_convetido_bs = (float)$comision_convetido_bs * (float)$valores_cambio->valor_bs;

            $capital_convertido_bs = (float)$pago->capital * (float)$valores_cambio->valor_bs;
            if ($pago->estado == 'AMORTIZACIÓN') {
                $capitalPagado = (float)$pago->dias_atraso_total - (float)$pago->capital;

                $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$capitalPagado;

                $capital_convertido_sus = $totalPagar;
                $capital_convertido_bs = $totalPagar * (float)$valores_cambio->valor_bs;
            }
            if ($pago->estado == 'INTERES') {
                $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora;
                $capital_convertido_sus = $totalPagar;
                $capital_convertido_bs = $totalPagar * (float)$valores_cambio->valor_bs;
            }
        } else {
            $interes_convertido_sus = (float)$interes_convertido_sus / (float)$valores_cambio->valor_bs;
            $comision_convetido_sus = (float)$comision_convetido_sus / (float)$valores_cambio->valor_bs;

            $capital_convertido_sus = CambioMoneda::ajustaDecimal($capital_convertido_sus);
            $capital_convertido_sus = (float)$capital_convertido_sus / (float)$valores_cambio->valor_bs;
            if ($pago->estado == 'AMORTIZACIÓN') {
                $capitalPagado = (float)$pago->dias_atraso_total - (float)$pago->capital;

                $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$capitalPagado;

                $totalPagar = CambioMoneda::ajustaDecimal($totalPagar);
                $capital_convertido_bs = $totalPagar;
                $capital_convertido_sus = $totalPagar / (float)$valores_cambio->valor_bs;
            }
            if ($pago->estado == 'INTERES') {
                $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora;
                $totalPagar = CambioMoneda::ajustaDecimal($totalPagar);
                $capital_convertido_bs = $totalPagar;
                $capital_convertido_sus = $totalPagar / (float)$valores_cambio->valor_bs;
            }
        }

        if ($pago->estado == 'AMORTIZACIÓN INTERES') {
            $penultimoPago = Pagos::where('contrato_id', $pago->contrato_id)->get();
            $penultimoPago = $penultimoPago[count($penultimoPago) - 2];

            $nuevo_interes = (float)$penultimoPago->interes - (float)$pago->interes;
            $nuevo_gasto = (float)$penultimoPago->comision - (float)$pago->comision;

            $capital_convertido_sus = (float)$nuevo_interes + (float)$nuevo_gasto;
            $capital_convertido_sus = \number_format($capital_convertido_sus, 2);

            $capital_convertido_bs = (float)$nuevo_interes + (float)$nuevo_gasto;
            // $capital_convertido_bs = \number_format($capital_convertido_bs, 2);
            if ($pago->moneda_id == 1) {
                $capital_convertido_sus = CambioMoneda::ajustaDecimal($capital_convertido_sus);
                $capital_convertido_sus = \number_format((float)$capital_convertido_sus / $valores_cambio->valor_bs, 2);
            } else {
                $capital_convertido_bs = \number_format($capital_convertido_bs * $valores_cambio->valor_bs, 2);
            }

            $capital_convertido_bs = CambioMoneda::ajustaDecimal($pago->total_ai, 2);
            $capital_convertido_sus = \number_format(CambioMoneda::ajustaDecimal($pago->total_ai) / $valores_cambio->valor_bs, 2);
        }

        $pdf::SetXY(180, 82);
        $pdf::Cell($w = 0, $h = 0, number_format((float)$capital_convertido_sus, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(180, 89);
        $pdf::Cell($w = 0, $h = 0, CambioMoneda::ajustaDecimal($capital_convertido_bs), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $nl = new NumberToLetterConverter();
        $pdf::SetXY(95, 82);

        $capital_convertido_sus = number_format($capital_convertido_sus, 2);
        $capital_convertido_sus = \str_replace(',', '', $capital_convertido_sus);
        $pdf::SetXY(40, 98);
        $pdf::Cell($w = 0, $h = 0, $nl->numtoletras($capital_convertido_sus) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(40, 106);
        $pdf::Cell($w = 0, $h = 0, 'Concepto:  Cambio Dólares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(40, 114);
        $pdf::Cell($w = 0, $h = 0, 'Doc. Nro.:  182 - ' . $pago->id, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Boleta');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Boleta.pdf');
    }

    public function imprimirReporteAmortizacion($id)
    {
        $pago = Pagos::where('id', $id)->first();
        //dd($pago);
        $cliente = Cliente::where('id', $pago->contrato->cliente_id)->first();
        //dd($cliente);
        $valores_cambio = CambioMoneda::first();

        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;

        if ($pago->moneda_id == 2) {
            $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
            $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
            $valor_comparacion3 = 15000 / $valores_cambio->valor_bs;
        }

        $interes = $pago->interes;
        $comision = $pago->comision;

        $totalInteres = (float)$pago->capital + (float)$interes + (float)$comision;
        $capitalPagado = (float)$pago->dias_atraso_total - (float)$pago->capital;
        $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$capitalPagado;
        if ($pago->cuota_mora > 0) {
            $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$capitalPagado;
        }

        $capital_prestado_convertido = 0;
        $capital_convertido = 0;
        $totalInteres_convertido = 0;
        $capitalPagado_convertido = 0;
        $interes_convertido = 0;
        $comision_convertido = 0;
        $cuota_mora_convertido = $pago->cuota_mora;
        $totalPagar_convertido = 0;
        $primer_pago_capital = Pagos::where('contrato_id', $pago->contrato_id)->get()->first();

        if ($pago->moneda_id == 1) {
            $totalPagar = CambioMoneda::ajustaDecimal($totalPagar);
            // CONVERTIR A DOLARES
            $capital_prestado_convertido = round((float)$primer_pago_capital->capital / (float) $valores_cambio->valor_bs, 2);
            $capital_convertido = round((float)$pago->capital / (float) $valores_cambio->valor_bs, 2);
            $totalInteres_convertido = round((float)$totalInteres / (float) $valores_cambio->valor_bs, 2);
            $capitalPagado_convertido = round((float)$capitalPagado / (float) $valores_cambio->valor_bs, 2);
            $interes_convertido = round((float)$pago->interes / (float) $valores_cambio->valor_bs, 2);

            if ($pago->cuota_mora > 0) {
                $comision_convertido = round(((float)$pago->comision) / (float) $valores_cambio->valor_bs, 2);
            } else {
                $comision_convertido = round(((float)$pago->comision) / (float) $valores_cambio->valor_bs, 2);
            }
            $cuota_mora_convertido =  (float)$pago->cuota_mora / (float)$valores_cambio->valor_bs;
            $totalPagar_convertido = round((float)$totalPagar / (float) $valores_cambio->valor_bs, 2);
        } else {
            // DOLARES
            $capital_prestado_convertido = $primer_pago_capital->capital;
            $capital_convertido = $pago->capital;
            $totalInteres_convertido = $totalInteres;
            $capitalPagado_convertido = $capitalPagado;
            $interes_convertido = $pago->interes;

            if ($pago->cuota_mora > 0) {
                $comision_convertido = (float)$pago->comision;
            } else {
                $comision_convertido = (float)$pago->comision;
            }
            $cuota_mora_convertido = $pago->cuota_mora;
            $totalPagar_convertido = $totalPagar;
        }

        // $totalPagar_convertido = CambioMoneda::ajustaDecimal($totalPagar_convertido);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(80, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $pago->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(90, 34);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(70, 39);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AMORTIZACIÓN A CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CREDITO No.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(48, 52);
        $gestion = substr($pago->contrato->gestion, 2, 2);
        // $resCodigo =  $pago->contrato->sucural->nuevo_codigo .''. Carbon::parse($pago->contrato->fecha_contrato)->format('y') .''. $pago->contrato->codigo_num; 
        $resCodigo =  $pago->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $pago->contrato->codigo_num;
        if ($pago->contrato->codigo != "") {
            $codigoG = $pago->contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PRESTADO ACTUALIZADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($capital_prestado_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = 'SALDO CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($capital_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL A PAGAR MAS INTERES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(85, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($totalInteres_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INICIO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(155, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->fecha_inio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'VENCIMIENTO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(155, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->fecha_fin, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetXY(115, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($capitalPagado_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INTERES DEL CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($interes_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = 'COMISIONES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($comision_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = 'ATRASO DIAS    ' . $pago->dias_atraso, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = round($cuota_mora_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($totalPagar_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(132, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(138, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(112, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(18, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Antes de firmar verifique sus datos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $generarCodigo = $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $pago->fecha_inio . "-" . round($totalPagar, 2);
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 174, 69, 22, 22, $style, 'N');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Contrato Amortizacion');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('ContratoAmortizacion.pdf');
    }

    public function reImprimirReporteAmortizacion($id)
    {
        $pago = Pagos::where('id', $id)->first();
        //dd($pago);
        $cliente = Cliente::where('id', $pago->contrato->cliente_id)->first();
        //dd($cliente);

        $valores_cambio = CambioMoneda::first();

        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        if ($pago->moneda_id == 2) {
            $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
            $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
            $valor_comparacion3 = 15000 / $valores_cambio->valor_bs;
        }

        $interes = $pago->interes;
        $comision = $pago->comision;

        $totalInteres = (float)$pago->capital + (float)$interes + (float)$comision;
        $capitalPagado = (float)$pago->dias_atraso_total - (float)$pago->capital;

        $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$capitalPagado;
        if ($pago->cuota_mora > 0) {
            $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$capitalPagado;
        }

        $primer_pago_capital_convertido = 0;
        $capital_convertido = 0;
        $totalInteres_convertido = 0;
        $capitalPagado_convertido = 0;
        $interes_convertido = 0;
        $comision_convertido = 0;
        $cuota_mora_convertido = $pago->cuota_mora;
        $totalPagar_convertido = 0;
        $primer_pago_capital = Pagos::where('contrato_id', $pago->contrato_id)->get()->first();

        if ($pago->moneda_id == 1) {
            $totalPagar = CambioMoneda::ajustaDecimal($totalPagar);
            // DOLARES
            $primer_pago_capital_convertido = round((float)$primer_pago_capital->capital / (float) $valores_cambio->valor_bs, 2);
            $capital_convertido = round((float)$pago->capital / (float) $valores_cambio->valor_bs, 2);
            $totalInteres_convertido = round((float)$totalInteres / (float) $valores_cambio->valor_bs, 2);
            $capitalPagado_convertido = round((float)$capitalPagado / (float) $valores_cambio->valor_bs, 2);
            $interes_convertido = round((float)$interes / (float) $valores_cambio->valor_bs, 2);

            if ($pago->cuota_mora > 0) {
                $comision_convertido = round(((float)$pago->comision) / (float) $valores_cambio->valor_bs, 2);
            } else {
                $comision_convertido = round(((float)$pago->comision) / (float) $valores_cambio->valor_bs, 2);
            }
            $cuota_mora_convertido =  (float)$pago->cuota_mora / (float)$valores_cambio->valor_bs;
            $totalPagar_convertido = round((float)$totalPagar / (float) $valores_cambio->valor_bs, 2);
        } else {
            // DOLARES
            $primer_pago_capital_convertido = $primer_pago_capital->capital;
            $capital_convertido = $pago->capital;
            $totalInteres_convertido = $totalInteres;
            $capitalPagado_convertido = $capitalPagado;
            $interes_convertido = $interes;

            if ($pago->cuota_mora > 0) {
                $comision_convertido = (float)$pago->comision;
            } else {
                $comision_convertido = (float)$pago->comision;
            }
            $cuota_mora_convertido = $pago->cuota_mora;
            $totalPagar_convertido = $totalPagar;
        }
        // $totalPagar_convertido = CambioMoneda::ajustaDecimal($totalPagar_convertido);

        //$totalSoloInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;             
        //dd($pago->contrato->);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(80, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $pago->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(90, 34);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(60, 39);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AMORTIZACIÓN A CAPITAL - REIMPRESION', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CREDITO No.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(48, 52);
        $gestion = substr($pago->contrato->gestion, 2, 2);
        // $resCodigo =  $pago->contrato->sucural->nuevo_codigo .''. Carbon::parse($pago->contrato->fecha_contrato)->format('y') .''. $pago->contrato->codigo_num; 
        $resCodigo =  $pago->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $pago->contrato->codigo_num;
        if ($pago->contrato->codigo != "") {
            $codigoG = $pago->contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PRESTADO ACTUALIZADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($primer_pago_capital_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = 'SALDO CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($capital_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL A PAGAR MAS INTERES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(85, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($totalInteres_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INICIO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(155, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->fecha_inio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'VENCIMIENTO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(155, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->fecha_fin, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetXY(115, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($capitalPagado_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INTERES DEL CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($interes_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = 'COMISIONES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($comision_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = 'ATRASO DIAS    ' . $pago->dias_atraso, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = round($cuota_mora_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($totalPagar_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(132, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(138, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(112, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(18, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Antes de firmar verifique sus datos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $generarCodigo = "REIMPRESION-" . $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $pago->fecha_inio . "-" . round($totalPagar, 2);
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 174, 69, 22, 22, $style, 'N');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Contrato Amortizacion');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('ContratoAmortizacion.pdf');
    }

    public function imprimirReporteAmortizacionInteres($id)
    {
        $pago = Pagos::where('id', $id)->first();
        //dd($pago);
        $cliente = Cliente::where('id', $pago->contrato->cliente_id)->first();
        //dd($cliente);
        $valores_cambio = CambioMoneda::first();

        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        if ($pago->moneda_id == 2) {
            $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
            $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
            $valor_comparacion3 = 15000 / $valores_cambio->valor_bs;
        }

        $totalInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;
        $capitalPagado = (float)$pago->dias_atraso_total - (float)$pago->capital;
        $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$capitalPagado;

        $capital_convertido = 0;
        $totalInteres_convertido = 0;
        $capitalPagado_convertido = 0;
        $interes_convertido = 0;
        $comision_convertido = 0;
        $cuota_mora_convertido = $pago->cuota_mora;
        $totalPagar_convertido = 0;
        if ($pago->moneda_id == 1) {
            $totalPagar = CambioMoneda::ajustaDecimal($totalPagar);
            // DOLARES
            $capital_convertido = round((float)$pago->capital / (float) $valores_cambio->valor_bs, 2);
            $totalInteres_convertido = round((float)$totalInteres / (float) $valores_cambio->valor_bs, 2);
            $capitalPagado_convertido = round((float)$capitalPagado / (float) $valores_cambio->valor_bs, 2);
            $interes_convertido = round((float)$pago->interes / (float) $valores_cambio->valor_bs, 2);
            $comision_convertido = round((float)$pago->comision / (float) $valores_cambio->valor_bs, 2);
            $cuota_mora_convertido =  (float)$pago->cuota_mora / (float)$valores_cambio->valor_bs;

            $totalPagar_convertido = round((float)$totalPagar / (float) $valores_cambio->valor_bs, 2);
        } else {
            // DOLARES
            $capital_convertido = $pago->capital;
            $totalInteres_convertido = $totalInteres;
            $capitalPagado_convertido = $capitalPagado;
            $interes_convertido = $pago->interes;
            $comision_convertido = $pago->comision;
            $cuota_mora_convertido = $pago->cuota_mora;
            $totalPagar_convertido = $totalPagar;
        }

        // $totalInteres_convertido = CambioMoneda::ajustaDecimal($totalInteres_convertido);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(80, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $pago->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(90, 34);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(70, 39);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AMORTIZACIÓN A INTERES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CREDITO No.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(48, 52);
        $gestion = substr($pago->contrato->gestion, 2, 2);
        // $resCodigo =  $pago->contrato->sucural->nuevo_codigo .''. Carbon::parse($pago->contrato->fecha_contrato)->format('y') .''. $pago->contrato->codigo_num; 
        $resCodigo =  $pago->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $pago->contrato->codigo_num;
        if ($pago->contrato->codigo != "") {
            $codigoG = $pago->contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INTERES ACTUALIZADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(85, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($interes_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetXY(15, 62);
        $pdf::Cell($w = 0, $h = 0, $txt = 'COMISIÓN', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(85, 62);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($comision_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 67);
        $pdf::Cell($w = 0, $h = 0, $txt = 'SALDO CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 67);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($capital_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 71);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL A PAGAR MAS INTERES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(85, 71);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($totalInteres_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INICIO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(155, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = date('Y-m-d', strtotime($pago->fecha_inio)), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'VENCIMIENTO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(155, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = date('Y-m-d', strtotime($pago->fecha_fin)), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $penultimoPago = Pagos::where('contrato_id', $pago->contrato_id)->get();
        $penultimoPago = $penultimoPago[count($penultimoPago) - 2];

        // $totalPagar_convertido = (float)$penultimoPago->interes - (float)$pago->interes;
        // $totalPagar_convertido = \number_format($totalPagar_convertido, 2);
        // if ($pago->moneda_id == 1) {
        //     $totalPagar_convertido = \number_format($totalPagar_convertido / $valores_cambio->valor_bs, 2);
        // }

        $totalPagar_convertido = \number_format($pago->total_ai / $valores_cambio->valor_bs, 2);
        $pdf::SetXY(115, 62);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(165, 62);
        $pdf::Cell($w = 0, $h = 0, $txt = \number_format($totalPagar_convertido, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(132, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(138, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(112, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(18, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Antes de firmar verifique sus datos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $generarCodigo = $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $pago->fecha_inio . "-" . round($totalPagar, 2);
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 174, 69, 22, 22, $style, 'N');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Contrato Amortizacion');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('ContratoAmortizacion.pdf');
    }

    public function imprimirReporteContratoEntregado($id)
    {
        $contrato = Contrato::where('id', $id)->first();
        $cliente = Cliente::where('id', $contrato->cliente_id)->first();
        $totalPagar = (float)$contrato->total_capital + (float)$contrato->interes + (float)$contrato->comision;
        //dd($cliente->persona);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');

            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false); 
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(80, 32);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $contrato->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 45);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 50);
        //$pdf::Cell($w=0, $h=0, $txt='CREDITO No.: '.$contrato->codigo, $border=0, $ln=50, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='B', $valign='B');
        $gestion = substr($contrato->gestion, 2, 2);
        // $resCodigo =  $contrato->sucural->nuevo_codigo .''. Carbon::parse($contrato->fecha_contrato)->format('y') .''. $contrato->codigo_num;
        $resCodigo =  $contrato->sucural->nuevo_codigo . '' . $gestion . '' . $contrato->codigo_num;
        if ($contrato->codigo != "") {
            $pdf::Cell($w = 0, $h = 0, $txt = $contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(100, 45);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA DE RETIRO DE PRENDA: ' . $contrato->solicitud->sucursal->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetFont('helvetica', 'B', 12);
        $pdf::SetXY(90, 50);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FECHA DE ENTREGA: ' . $contrato->updated_at, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Cant.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(22, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = '', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::SetXY(130, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = 'P.B', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(142, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = '10k', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(154, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = '14k', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(166, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = '18k', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetXY(178, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = '24k', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 9);
        $detallesContrato = ContratoDetalle::where('contrato_id', $contrato->id)->where('estado_id', 1)->get();
        $cantidad = 0;
        $posicion = 62;
        //$cantidadPosicion = 110;
        foreach ($detallesContrato as $key => $detalleContrato) {
            $pdf::SetXY(15, $posicion);
            $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->cantidad, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(22, $posicion);
            $pdf::Cell($w = 0, $h = 0, $txt = substr($detalleContrato->descripcion, 0, 45), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            //$pdf::Cell($w=0, $h=0, $txt=$detalleContrato->descripcion, $border=0, $ln=50, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='B', $valign='B');

            $pdf::SetXY(130, $posicion);
            $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->peso, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(142, $posicion);
            //dd($detalleContrato->dies);
            if ($detalleContrato->dies) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->dies, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }


            $pdf::SetXY(154, $posicion);
            if ($detalleContrato->catorce) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->catorce, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }


            $pdf::SetXY(166, $posicion);
            if ($detalleContrato->dieciocho) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->dieciocho, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }


            $pdf::SetXY(178, $posicion);
            if ($detalleContrato->veinticuatro) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->veinticuatro, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }

            $posicion = $posicion + 5;
        }
        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(132, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(138, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(112, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(18, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Antes de firmar verifique sus datos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $contrato->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Retiro de Joya');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Contrato.pdf');
    }

    public function pagoRemate(Request $request)
    {
        \DB::enableQueryLog();
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                DB::beginTransaction();
                try {
                    $_contrato = Contrato::find($request['idContrato']);
                    $fecha_actual = Carbon::parse($request['fecha_pago'])->format('d-m-Y');
                    //$resFechaProximo = date("d-m-Y",strtotime($fecha_actual."+ 1 months"));
                    $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+30 day"));
                    $idPago = Pagos::create([
                        'contrato_id'          => $request['idContrato'],
                        'sucursal_id'          => session::get('ID_SUCURSAL'),
                        'fecha_inio'           => $request['fecha_pago'],
                        //'fecha_fin'            => Carbon::parse($resFechaProximo)->format('Y-m-d'),
                        'fecha_fin'            => $request['fecha_pago'],
                        'fecha_pago'           => $request['fecha_pago'],
                        'caja'                 => session::get('CAJA'),
                        'dias_atraso'          => $request['diasAtraso'],
                        'capital'              => $request['capital'], //Carbon::parse($request['txtFechaNacimiento'])->format('Y-m-d'),
                        'interes'              => $request['interes'],
                        'comision'              => $request['comision'],
                        'cuota_mora'              => $request['cuotaMora'],
                        'total_capital'        => $request['total_capital'],
                        'estado'               => 'REMATE',
                        'estado_id'            => 1,
                        'usuario_id'           => session::get('ID_USUARIO'),
                        'moneda_id'            => $_contrato->moneda_id
                    ])->id;

                    $bitacora = \DB::getQueryLog();
                    foreach ($bitacora as $i => $query) {
                        $resultado = json_encode($query);
                    }
                    \DB::disableQueryLog();
                    LogSeguimiento::create([
                        'usuario_id'   => session::get('ID_USUARIO'),
                        'metodo'   => 'POST',
                        'accion'   => 'CREACION',
                        'detalle'  => "el usuario" . session::get('USUARIO') . " agrego un nuevo registro",
                        'modulo'   => 'PAGO TOTAL',
                        'consulta' => $resultado,
                    ]);

                    $totalPagar = (float)$request['capital'] + (float)$request['interes'] + (float)$request['comision'] + (float)$request['cuotaMora'];

                    /* **** REGISTRO EN LA SUCURSAL CENTRAL **** */
                    $datoInicioCaja = InicioFinCaja::where('sucursal_id', 15)
                        ->where('caja', 151)
                        ->where('fecha', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                        ->whereIn('estado_id', [1, 2])
                        ->first();
                    $contadorInicioCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', 15)
                        ->where('caja', 151)
                        ->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))
                        ->where('estado_id', 1)->count();

                    if ($contadorInicioCajaDetalle == 0) {
                        $inicioCajaBs = $datoInicioCaja->inicio_caja_bs;
                        $idInicioCaja = $datoInicioCaja->id;
                    } else {
                        $datoCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', 15)->where('caja', 151)->where('fecha_pago', Carbon::parse($request['fecha_pago'])->format('Y-m-d'))->where('estado_id', 1)->orderBy('id', 'DESC')->first();
                        if (!$datoCajaDetalle) {
                            throw new Exception('No es posible realizar el registro debido a que no se realizó la apertura de la Caja 1 de la Sucursal Central');
                        }
                        $inicioCajaBs = $datoCajaDetalle->inicio_caja_bs;
                        $idInicioCaja = $datoCajaDetalle->inicio_fin_caja_id;
                    }
                    $_capital = round($request['capital'], 2);
                    $resulInicioCaja = (float)$inicioCajaBs + (float)$_capital;

                    $datoContrato = Contrato::where('id', $request['idContrato'])->first();
                    if ($datoContrato->codigo != "") {
                        $codigoContrato = $datoContrato->codigo;
                    } else {
                        $codigoContrato = $datoContrato->codigo_num;
                    }

                    InicioFinCajaDetalle::create([
                        'inicio_fin_caja_id'    => $idInicioCaja,
                        'contrato_id'           => $request['idContrato'],
                        'pago_id'               => $idPago,
                        'sucursal_id'           => 15,
                        'fecha_pago'            => Carbon::parse($request['fecha_pago'])->format('Y-m-d'),
                        'fecha_hora'            => Carbon::now('America/La_Paz'),
                        'inicio_caja_bs'        => round($resulInicioCaja, 2),
                        'ingreso_bs'             => round($_capital, 2),
                        'tipo_de_movimiento'    => 'REMATE DEL CONTRATO N° ' . $codigoContrato . ' DEL  SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' EN LA CAJA ' . 151 . '.',
                        'ref'               => 'REM01',
                        'caja'              => 151,
                        'usuario_id'        => session::get('ID_USUARIO'),
                        'estado_id'         => 1,
                        'moneda_id'             => $_contrato->moneda_id
                    ]);
                    /* **** FIN REGISTRO EN LA SUCURSAL CENTRAL **** */

                    //ACTUALIZAMOS CONTRATO
                    $contrato = Contrato::find($request['idContrato']);
                    $contrato->fecha_pago                      = $request['fecha_pago'];
                    $contrato->interes                         = $request['interes'];
                    $contrato->comision                        = $request['comision'];
                    $contrato->cuota_mora                      = $request['cuotaMora'];
                    $contrato->estado_pago                     = 'Prenda Rematado';
                    $contrato->estado_entrega                  = 'Prenda Rematado';
                    $contrato->estado_pago_2                   = 'Prenda Rematado';
                    $contrato->estado_id                       = 1;
                    $contrato->usuario_id                      = session::get('ID_USUARIO');
                    $contrato->save();

                    $bitacora = \DB::getQueryLog();
                    foreach ($bitacora as $i => $query) {
                        $resultado = json_encode($query);
                    }
                    \DB::disableQueryLog();
                    LogSeguimiento::create([
                        'usuario_id'   => session::get('ID_USUARIO'),
                        'metodo'   => 'PUT',
                        'accion'   => 'ACTUALIZAR',
                        'detalle'  => "el usuario" . session::get('USUARIO') . " actualizo un registro",
                        'modulo'   => 'REMATE',
                        'consulta' => $resultado,
                    ]);

                    /* **** REGISTRAR PARTE CONTABLE CENTRAL **** */
                    $numComprobante = ContaDiario::max('num_comprobante');

                    // $totalPagar = round($totalPagar, 2);
                    $totalPagar = round($request['capital'], 2);
                    // if ($_contrato->moneda_id == 2) {
                    //     // convertir a bolivianos
                    //     $valores_cambio = CambioMoneda::first();
                    //     $totalPagar = round((float)$valores_cambio->valor_bs * (float)$totalPagar, 2);
                    // }
                    ContaDiario::create([
                        'contrato_id'        => $request['idContrato'],
                        'pagos_id'           => $idPago,
                        'sucursal_id'        => 15,
                        'fecha_a'            => $request['fecha_pago'],
                        'fecha_b'            => $request['fecha_pago'],
                        'glosa'              => 'REMATE DEL CONTRATO AL N° ' . $datoContrato->codigo . ' DEL  SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' EN LA CAJA ' . 151 . '.',
                        'cod_deno'              => '11102',
                        'cuenta'                => 'Caja sucursales',
                        'debe'                  => $totalPagar,
                        'haber'                 => '0.00',
                        'caja'                  => 151,
                        'num_comprobante'       => $numComprobante + 1,
                        'periodo'               => 'mes',
                        'tcom'                  => 'INGRESO',
                        'ref'                   => 'REM01',
                        'usuario_id'            => session::get('ID_USUARIO'),
                        'estado_id'             => 1
                    ]);
                    ContaDiario::create([
                        'contrato_id'        => $request['idContrato'],
                        'pagos_id'           => $idPago,
                        'sucursal_id'        => 15,
                        'fecha_a'            => $request['fecha_pago'],
                        'fecha_b'            => $request['fecha_pago'],
                        'glosa'              => 'REMATE DEL CONTRATO AL N° ' . $datoContrato->codigo . ' DEL  SR.(A) ' . $datoContrato->cliente->persona->nombreCompleto() . ' EN LA CAJA ' . 151 . '.',
                        'cod_deno'              => '11301',
                        'cuenta'                => 'Prestamo a plazo fijo vigentes',
                        'debe'                  => '0.00',
                        'haber'                 => $totalPagar,
                        'caja'                  => 151,
                        'num_comprobante'       => $numComprobante + 1,
                        'periodo'               => 'mes',
                        'tcom'                  => 'INGRESO',
                        'ref'                   => 'REM01',
                        'usuario_id'            => session::get('ID_USUARIO'),
                        'estado_id'             => 1
                    ]);
                    /* **** FIN REGISTRAR PARTE CONTABLE CENTRAL **** */

                    DB::commit();
                    return response()->json(["Mensaje" => "1", "idPago" => $idPago]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(["Mensaje" => "-2", "message" => $e->getMessage()]);
                }
            } else {
                return response()->json(["Mensaje" => "0"]);
            }
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public function imprimirReporteRemate($id)
    {
        $pago = Pagos::where('id', $id)->first();
        //dd($pago->contrato_id);
        $cliente = Cliente::where('id', $pago->contrato->cliente_id)->first();
        //dd($pago);
        $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$pago->capital;
        //dd($totalPagar);

        $totalInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;
        //$totalSoloInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;             
        //dd($pago->contrato->);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(80, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $pago->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(90, 34);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(74, 39);
        $pdf::Cell($w = 0, $h = 0, $txt = 'REMATE DE JOYA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CREDITO No.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(48, 52);
        //$pdf::Cell($w=0, $h=0, $pago->caja .'  '.$pago->contrato->codigo, $border=0, $ln=50, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='B', $valign='B');
        $gestion = substr($pago->contrato->gestion, 2, 2);
        // $resCodigo =  $pago->contrato->sucural->nuevo_codigo .''. Carbon::parse($pago->contrato->fecha_contrato)->format('y') .''. $pago->contrato->codigo_num;
        $resCodigo =  $pago->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $pago->contrato->codigo_num;
        if ($pago->contrato->codigo != "") {
            $codigoG = $pago->contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PRESTADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->capital, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = 'SALDO CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL A PAGAR MAS INTERES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(85, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INICIO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(155, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->fecha_inio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'VENCIMIENTO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(155, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->fecha_fin, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = round($pago->capital, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INTERES DEL CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = round($pago->interes, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = 'COMISIONES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = round($pago->comision, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = 'ATRASO DIAS    ' . $pago->dias_atraso, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = round($pago->cuota_mora, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = round($totalPagar, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(132, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(138, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(112, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(18, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Antes de firmar verifique sus datos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $generarCodigo = $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $pago->fecha_inio . "-" . round($totalPagar, 2);
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 174, 69, 22, 22, $style, 'N');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Remate de Contrato');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('ContratoRemate.pdf');
    }

    public function reImprimirReporteRemate($id)
    {
        $pago = Pagos::where('id', $id)->first();
        //dd($pago->contrato_id);
        $cliente = Cliente::where('id', $pago->contrato->cliente_id)->first();
        //dd($pago);
        $totalPagar = (float)$pago->interes + (float)$pago->comision + (float)$pago->cuota_mora + (float)$pago->capital;
        //dd($totalPagar);
        $totalInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;
        //$totalSoloInteres = (float)$pago->capital + (float)$pago->interes + (float)$pago->comision;             
        //dd($pago->contrato->);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(80, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $pago->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(90, 34);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(70, 39);
        $pdf::Cell($w = 0, $h = 0, $txt = 'REMATE DE JOYA - REIMPRESION', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(15, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CREDITO No.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(48, 52);
        //$pdf::Cell($w=0, $h=0, $pago->caja .'  '.$pago->contrato->codigo, $border=0, $ln=50, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='B', $valign='B');
        $gestion = substr($pago->contrato->gestion, 2, 2);
        //$resCodigo =  $pago->contrato->sucural->nuevo_codigo .''. Carbon::parse($pago->contrato->fecha_contrato)->format('y') .''. $pago->contrato->codigo_num;
        $resCodigo =  $pago->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $pago->contrato->codigo_num;
        if ($pago->contrato->codigo != "") {
            $codigoG = $pago->contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $pago->contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PRESTADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->capital, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = 'SALDO CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(85, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL A PAGAR MAS INTERES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(85, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INICIO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(155, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->fecha_inio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'VENCIMIENTO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(155, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->fecha_fin, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = round($pago->capital, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = 'INTERES DEL CAPITAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = round($pago->interes, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = 'COMISIONES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = round($pago->comision, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = 'ATRASO DIAS    ' . $pago->dias_atraso, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = round($pago->cuota_mora, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TOTAL PAGADO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 68);
        $pdf::Cell($w = 0, $h = 0, $txt = round($totalPagar, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(132, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(138, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(112, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(115, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 94);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(18, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Antes de firmar verifique sus datos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(15, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $pago->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $generarCodigo = "REIMPRESION-" . $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $pago->fecha_inio . "-" . round($totalPagar, 2);
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 174, 69, 22, 22, $style, 'N');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Remate de Contrato');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('ContratoRemate.pdf');
    }

    public function buscarContratosCodigo(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $cliente = Cliente::where('persona_id', $request['idPersona'])->where('estado_id', 1)->first();
            $datoValidarCaja =  InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->whereIn('estado_id', [1, 2])
                ->orderBy('id', 'DESC')->first();
            $tipoCodigo = $request['rdoTipoCodigo'];
            if ($tipoCodigo == 'A') {
                $codigosAnterior = Contrato::where('codigo', $request['txtBuscarCodigo'])->whereIn('estado_id', [1, 3])->orderBy('id', 'DESC')->get();
            }

            if ($tipoCodigo == 'N') {
                $codigosAnterior = Contrato::where('codigo', $request['txtBuscarCodigo'])->whereIn('estado_id', [1, 3])->orderBy('id', 'DESC')->get();

                // // $codigosAnterior = DB::select("SELECT *
                // //     FROM contrato
                // //     WHERE  estado_id IN(1,3)
                // //     AND CONCAT(caja, '19', codigo_num) = '1119100505'");
                // $codigosAnterior = Contrato::
                //     // select(DB::raw("CONCAT(caja,SUBSTR(gestion, 3, 2),codigo_num)"))
                //     whereIn('estado_id', [1, 3])
                //     ->where(DB::raw("CONCAT(caja,SUBSTR(gestion, 3, 2),codigo_num)"), $request['txtBuscarCodigo'])
                //     // ->take(10)
                //     ->orderBy('id', 'DESC')->get();
            }

            $codigos = $codigosAnterior;
            // dd($codigos);
            if ($codigos) {
                if ($request->ajax()) {
                    return view('pagos.modals.listadoCodigos', ['codigos' => $codigos, 'cliente' => $cliente])->render();
                }
                return view('pagos.index', compact('codigos', 'datoValidarCaja'));
            }
        } else {
            return view("layout.login");
        }
    }

    public function listaMora()
    {
        return view('pagos.listaMora');
    }

    public function listadoMoras(Request $request)
    {
        $dias = $request->dias;
        $contratos = [];
        $today = Carbon::today();
        if ($dias != 'MAS') {
            $dias_atraso = Carbon::now()->subDays($dias)->startOfDay();
            $contratos = Contrato::where('fecha_fin', '=', $dias_atraso)
                ->whereNotIn("contrato.estado_pago", ["Prenda Rematado", "Credito cancelado"])
                ->paginate(20);
        } else {
            $dias_atraso = Carbon::now()->subDays(390)->startOfDay();
            $contratos = Contrato::where('fecha_fin', '<=', $dias_atraso)
                ->whereNotIn("contrato.estado_pago", ["Prenda Rematado", "Credito cancelado"])
                ->paginate(20);
        }
        $lista_morosidad = $contratos;

        $html = view('pagos.modals.listaMoras', compact('lista_morosidad', 'dias_atraso'))->render();

        return response()->JSON($html);
    }

    public function listadoMorasExcel(Request $request)
    {
        $dias = $request->dias;
        $contratos = [];
        $today = Carbon::today();
        if ($dias != 'MAS') {
            $dias_atraso = Carbon::now()->subDays($dias)->startOfDay();
            $contratos = Contrato::where('fecha_fin', '=', $dias_atraso)
                ->whereNotIn("contrato.estado_pago", ["Prenda Rematado", "Credito cancelado"])
                ->get();
        } else {
            $dias_atraso = Carbon::now()->subDays(390)->startOfDay();
            $contratos = Contrato::where('fecha_fin', '<=', $dias_atraso)
                ->whereNotIn("contrato.estado_pago", ["Prenda Rematado", "Credito cancelado"])
                ->get();
        }
        $lista_morosidad = $contratos;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("PrendaSol")
            ->setLastModifiedBy('Administración')
            ->setTitle('Reporte de Lista de moras')
            ->setSubject('Lista de moras')
            ->setDescription('Excel donde muestra la Lista de moras')
            ->setKeywords('PHPSpreadsheet')
            ->setCategory('Listado');

        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('B1:J1')->applyFromArray($styleArray);
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        // LLENADO DEL REPORTE
        $sheet->setCellValue('B1', 'LISTA MORAS');
        $sheet->mergeCells("B1:I1");  //COMBINAR CELDAS
        // ENCABEZADO
        $sheet->setCellValue('B2', 'Nº');
        $sheet->setCellValue('C2', 'NOMBRES');
        $sheet->setCellValue('D2', 'PRIMER APELLIDO');
        $sheet->setCellValue('E2', 'SEGUNDO APELLIDO');
        $sheet->setCellValue('F2', 'NRO. DOCUMENTO');
        $sheet->setCellValue('G2', 'DÍAS MORA');
        $sheet->setCellValue('H2', 'TOTAL');
        $sheet->setCellValue('I2', 'MONEDA');

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('B2:I2')->applyFromArray($styleArray);

        // RECORRER LOS REGISTROS
        $nro_fila = 3;
        $cont = 1;
        $suma_total = 0;
        foreach ($lista_morosidad as $value) {
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->setCellValue('B' . $nro_fila, $cont++);
            $sheet->setCellValue('C' . $nro_fila, $value->cliente->persona->nombres);
            $sheet->setCellValue('D' . $nro_fila, $value->cliente->persona->primerapellido);
            $sheet->setCellValue('E' . $nro_fila, $value->cliente->persona->segundoapellido);
            $sheet->setCellValue('F' . $nro_fila, $value->cliente->persona->nrodocumento);
            $sheet->setCellValue('G' . $nro_fila, Carbon::parse($value->fecha_fin)->diffInDays());
            $sheet->setCellValue('H' . $nro_fila, $value->total_capital);
            $sheet->setCellValue('I' . $nro_fila, $value->moneda->desc_corta);
            $sheet->getStyle('B' . $nro_fila . ':I' . $nro_fila)->applyFromArray($styleArray);
            
            $nro_fila++;
        }
        // $sheet->getStyle('B' . $nro_fila . ':G' . $nro_fila)->applyFromArray($styleArray);

        // AJUSTAR EL ANCHO DE LAS CELDAS
        foreach (range('B', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        // DESCARGA DEL ARCHIVO
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
        header('Content-Disposition: attachment;filename="ReporteContratosCancelados.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function rectificarPagosAmortizacion2()
    {
        $pagos = Pagos::where('estado', 'AMORTIZACIÓN')
            ->where('dias_atraso', '>', '0')
            ->get();
        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        foreach ($pagos as $pago) {
            $comision = 0;
            $capital = $pago->dias_atraso_total;
            if ((float)($capital) <= $valor_comparacion1) {
                $comision = ($capital * 6.04) / 100;
            } else if ((float)($capital) < $valor_comparacion2) {
                $comision = ($capital * 3.7) / 100;
            } else if ((float)($capital) < $valor_comparacion3) {
                $comision = ($capital * 3) / 100;
            } else {
                $comision = ($capital * 2) / 100;
            }
            $pago->comision = $comision;
            $pago->save();

            // TABLA INICIO FIN CAJA DETALLE
            $datosCaja = InicioFinCajaDetalle::where('pago_id', $pago->id)->get()->first();
            if ($datosCaja) {
                $datosCaja->ingreso_bs = ((float)$pago->dias_atraso_total - (float)$pago->capital)
                    + (float)$pago->cuota_mora + (float)$pago->interes + (float)$pago->comision;
                $datosCaja->ingreso_bs = number_format($datosCaja->ingreso_bs, 2, '.', '');
                $datosCaja->save();
            }


            // TABLA CONTA DIARIO
            $conta_diario1 = ContaDiario::where('pagos_id', $pago->id)
                ->where('cuenta', 'Caja sucursales')
                ->get()->first();
            if ($conta_diario1) {
                $conta_diario1->debe = ((float)$pago->dias_atraso_total - (float)$pago->capital)
                    + (float)$pago->cuota_mora + (float)$pago->interes + (float)$pago->comision;
                $conta_diario1->debe = number_format($conta_diario1->debe, 2, '.', '');
                $conta_diario1->haber = 0;
                $conta_diario1->save();
            }

            $conta_diario2 = ContaDiario::where('pagos_id', $pago->id)
                ->where('cuenta', 'Prestamos a plazo fijo vigentes')
                ->get()->first();
            if ($conta_diario2) {
                $conta_diario2->haber = ((float)$pago->dias_atraso_total - (float)$pago->capital);
                $conta_diario2->haber = number_format($conta_diario2->haber, 2, '.', '');
                $conta_diario2->save();
            }

            $conta_diario3 = ContaDiario::where('pagos_id', $pago->id)
                ->where('cuenta', 'Intereses prestamos a plazo fijo cartera vigente')
                ->get()->first();
            if ($conta_diario3) {
                $conta_diario3->haber = ((float)$pago->interes + (float)$pago->comision);
                $conta_diario3->haber = number_format($conta_diario3->haber, 2, '.', '');
                $conta_diario3->save();
            }
            $conta_diario4 = ContaDiario::where('pagos_id', $pago->id)
                ->where('cuenta', 'Intereses por mora prestamos a plazo fijo cartera vigente')
                ->get()->first();
            if ($conta_diario4) {
                $conta_diario4->haber = ((float)$pago->cuota_mora);
                $conta_diario4->haber = number_format($conta_diario4->haber, 2, '.', '');
                $conta_diario4->save();
            }
        }

        $pagos = Pagos::where('estado', 'AMORTIZACIÓN')
            ->where('dias_atraso', '<=', '0')
            ->get();
        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        foreach ($pagos as $pago) {
            // TABLA INICIO FIN CAJA DETALLE
            $datosCaja = InicioFinCajaDetalle::where('pago_id', $pago->id)->get()->first();
            if ($datosCaja) {
                $datosCaja->ingreso_bs = ((float)$pago->dias_atraso_total - (float)$pago->capital)
                    + (float)$pago->cuota_mora + (float)$pago->interes + (float)$pago->comision;
                $datosCaja->ingreso_bs = number_format($datosCaja->ingreso_bs, 2, '.', '');
                $datosCaja->save();
            }

            // TABLA CONTA DIARIO
            $conta_diario1 = ContaDiario::where('pagos_id', $pago->id)
                ->where('cuenta', 'Caja sucursales')
                ->get()->first();
            if ($conta_diario1) {
                $conta_diario1->debe = ((float)$pago->dias_atraso_total - (float)$pago->capital)
                    + (float)$pago->cuota_mora + (float)$pago->interes + (float)$pago->comision;
                $conta_diario1->debe = number_format($conta_diario1->debe, 2, '.', '');
                $conta_diario1->haber = 0;
                $conta_diario1->save();
            }

            $conta_diario2 = ContaDiario::where('pagos_id', $pago->id)
                ->where('cuenta', 'Prestamos a plazo fijo vigentes')
                ->get()->first();
            if ($conta_diario2) {
                $conta_diario2->haber = ((float)$pago->dias_atraso_total - (float)$pago->capital);
                $conta_diario2->haber = number_format($conta_diario2->haber, 2, '.', '');
                $conta_diario2->save();
            }

            $conta_diario3 = ContaDiario::where('pagos_id', $pago->id)
                ->where('cuenta', 'Intereses prestamos a plazo fijo cartera vigente')
                ->get()->first();
            if ($conta_diario3) {
                $conta_diario3->haber = ((float)$pago->interes + (float)$pago->comision);
                $conta_diario3->haber = number_format($conta_diario3->haber, 2, '.', '');
                $conta_diario3->save();
            }

            $conta_diario4 = ContaDiario::where('pagos_id', $pago->id)
                ->where('cuenta', 'Intereses por mora prestamos a plazo fijo cartera vigente')
                ->get()->first();
            if ($conta_diario4) {
                $conta_diario4->haber = ((float)$pago->cuota_mora);
                $conta_diario4->haber = number_format($conta_diario4->haber, 2, '.', '');
                $conta_diario4->save();
            }
        }

        return 'Pagos corregidos con éxito';
    }

    public function rectificacionCierres()
    {
        $inicio_cajas = InicioFinCaja::where('fecha', '<=', '2021-06-25')->get();
        foreach ($inicio_cajas as $inicio) {
            $valor_inicio = $inicio->inicio_caja_bs; //valor inicial de la caja
            $detalles = InicioFinCajaDetalle::where('inicio_fin_caja_id', $inicio->id)->get();
            foreach ($detalles as $d) {
                // actualizar el valor inicial
                if ($d->ingreso_bs != NULL) {
                    $valor_inicio = (float)$valor_inicio + (float)$d->ingreso_bs;
                } else {
                    $valor_inicio = (float)$valor_inicio - (float)$d->egreso_bs;
                }
                //actualizar el valor del registro detalle
                $d->inicio_caja_bs = $valor_inicio;
                $d->save();
            }
            $inicio->fin_caja_bs = $valor_inicio;
            $inicio->save();
            // ACTUALIZAR EL SIGUIENTE
            $registro_sgte = InicioFinCaja::where('sucursal_id', $inicio->sucursal_id)
                ->where('caja', $inicio->caja)
                ->where('id', '>', $inicio->id)
                ->orderBy('id', 'asc')
                ->get()
                ->first();
            if ($registro_sgte) {
                $registro_sgte->inicio_caja_bs = $inicio->fin_caja_bs;
                $registro_sgte->save();
            }
        }
        return 'Registros corregidos con éxito';
    }
}
