<?php

namespace App\Http\Controllers\Contrato;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use App\Persona;
use App\Cliente;
use App\Contrato;
use App\ContratoDetalle;
use App\SucursalUsuario;
use App\Sucursal;
use App\InicioFinCaja;
use App\InicioFinCajaDetalle;
use App\ContaDiario;
use App\Pagos;
use Carbon\Carbon;
use App\NumberToLetterConverter;
use PDF;
use App\CambioMoneda;
use App\CategoriaCliente;
use App\ClienteCategoria;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\PrecioOro;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function prueba()
    {
        $sucursal = 1;
        $anio = Carbon::now('America/La_Paz')->format('Y');

        $registros = Contrato::where('fecha_contrato', 'LIKE', Carbon::now('America/La_Paz')->format('Y') . '-%')
            ->where('sucursal_id', $sucursal)
            ->orderBy('id', 'ASC')
            ->get();
        $registros = DB::select("SELECT SUBSTRING(codigo,8) as cod FROM `contrato`
		WHERE sucursal_id = $sucursal
		AND fecha_contrato LIKE '$anio-%'
		ORDER BY cod * 1 ASC");

        $total_registros = count($registros);

        $array_codigos = [];
        $numero_inicial = 2001;
        $ultimo_numero = $registros[count($registros) - 1]->cod;

        if ($total_registros > 0) {
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
                return $no_existen[0];
            }
        }
        return ' xD';
    }

    public function index(Request $request)
    {
        //dd(session::get('CAJA'));
        //dd(session::get('ID_SUCURSAL'));
        //$codigoSucursal = Sucursal::where('id',session::get('ID_SUCURSAL'))->first();
        //$resCodigo = (int)$codigoSucursal->genera_codigo  + 1;
        //dd($resCodigo);
        if (Session::has('AUTENTICADO')) {
            $datoValidarCaja =  InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->whereIn('estado_id', [1, 2])
                ->first();
            $personas = Persona::orderBy('primerapellido', 'ASC')->orderBy('segundoapellido', 'ASC')->paginate(10);
            $datoInicioFinCaja =  InicioFinCaja::where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->whereIn('estado_id', [1, 2])
                ->first();
            //dd($datoInicioFinCaja);
            $fechaActual = Carbon::now('America/La_Paz')->format('d-m-Y');
            if ($request->ajax()) {
                //return view('contrato.modals.listadoContrato', ['personas' => $personas])->render();  
            }
            //return view('contrato.index',compact('personas'));
            $cambio = CambioMoneda::first();
            return view('contrato.index', compact('datoValidarCaja', 'datoInicioFinCaja', 'fechaActual', 'cambio'));
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
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                try {
                    //DB::beginTransaction();

                    //$totalCapital =(float)$request['txtCapitaBs'] + (float)$request['txtGarantia'];
                    //dd($totalCapital);
                    // $fecha_actual = Carbon::parse($request['txtFechaContrato'])->format('d-m-Y');
                    // $resFechaProximo = date("d-m-Y",strtotime($fecha_actual."+ 29 days")); 
                    // dd($resFechaProximo);

                    //dd(get('ID_SUCURSAL'));
                    $codigoSucursal = Sucursal::where('id', session::get('ID_SUCURSAL'))->first();

                    //$ultimoContrato = Contrato::whereRaw('id = (select max(`id`) from contrato)')->get();
                    //$ultimoContrato = Contrato::max('id');
                    $ultimoContrato = Contrato::where('estado_id', 1)
                        ->where('sucursal_id', session::get('ID_SUCURSAL'))
                        ->whereNotNull('codigo_num')
                        //Carbon::now('America/La_Paz')->format('Y-m-d')
                        ->where('gestion', Carbon::now('America/La_Paz')->format('Y'))
                        ->max('id');
                    //dd($ultimoContrato);
                    if ($ultimoContrato) {
                        $datoContrato = Contrato::where('id', $ultimoContrato)->first();
                        $resCodigo = (float)$datoContrato->codigo_num  + (float)1;
                    } else {
                        $resCodigo = (float)$codigoSucursal->codigo_inicial  + (float)1;
                    }

                    //dd($datoContrato->codigo);
                    //dd($codigoSucursal);

                    //$bigInt = gmp_init($resCodigo);

                    //$bigIntVal = gmp_intval($bigInt);
                    //dd($resCodigo);
                    //$resConcatCodigo =  $codigoSucursal->nuevo_codigo .''. Carbon::parse($request['txtFechaContrato'])->format('y') .''. $resCodigo; 
                    $resConcatCodigo = (float)$resCodigo;
                    //dd($resConcatCodigo);
                    $array_cantidad         = array();
                    $array_descripción      = array();
                    $array_pesoBruto        = array();
                    $array_diezKlts         = array();
                    $array_catorceKlts      = array();
                    $array_dieciochoKlts    = array();
                    $array_veintiCuatroKlts = array();

                    $array_cantidad         = $request['cantidad'];
                    $array_descripción      = $request['descripción'];
                    $array_pesoBruto        = $request['pesoBruto'];
                    $array_diezKlts         = $request['diezKlts'];
                    $array_catorceKlts      = $request['catorceKlts'];
                    $array_dieciochoKlts    = $request['dieciochoKlts'];
                    $array_veintiCuatroKlts = $request['veintiCuatroKlts'];
                    $array_fotos = $request['fotos'];

                    $fecha_actual = Carbon::parse($request['txtFechaContrato'])->format('d-m-Y');
                    //$resFechaProximo = date("d-m-Y",strtotime($fecha_actual."+ 1 months")); 
                    $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+30 day"));
                    //dd($resFechaProximo);

                    /*************************
                        GENERACIÓN DE CÓDIGO
                     **************************/
                    $codigo_contrato = '';
                    if ((int)session::get('ID_SUCURSAL') == 1) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = 'EG1';
                        } else {
                            $codigo_contrato = 'EG2';
                        }
                    }

                    if ((int)session::get('ID_SUCURSAL') == 2) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = 'RO1';
                        } else {
                            $codigo_contrato = 'RO2';
                        }
                    }

                    if ((int)session::get('ID_SUCURSAL') == 3) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = 'EA1';
                        } else {
                            $codigo_contrato = 'EA2';
                        }
                    }

                    if ((int)session::get('ID_SUCURSAL') == 4) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = 'VC1';
                        } else {
                            $codigo_contrato = 'VC2';
                        }
                    }

                    if ((int)session::get('ID_SUCURSAL') == 5) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = 'VF1';
                        } else {
                            $codigo_contrato = 'VF2';
                        }
                    }

                    if ((int)session::get('ID_SUCURSAL') == 6) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = 'GA1';
                        } else {
                            $codigo_contrato = 'GA2';
                        }
                    }

                    if ((int)session::get('ID_SUCURSAL') == 7) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = 'SB1';
                        } else {
                            $codigo_contrato = 'SB2';
                        }
                    }

                    if (session::get('ID_SUCURSAL') == 8) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = 'RS1';
                        } else {
                            $codigo_contrato = 'RS2';
                        }
                    }

                    if (session::get('ID_SUCURSAL') == 9) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = 'CR1';
                        } else {
                            $codigo_contrato = 'CR2';
                        }
                    }

                    if (session::get('ID_SUCURSAL') == 10) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = '16J1';
                        } else {
                            $codigo_contrato = '16J2';
                        }
                    }

                    if (session::get('ID_SUCURSAL') == 11) {
                        if (Session::get('NROCAJA') == 1) {
                            $codigo_contrato = '21C1';
                        } else {
                            $codigo_contrato = '21C2';
                        }
                    }

                    $codigo_contrato .= '-' . Carbon::parse($request['txtFechaContrato'])->format('y');

                    $nro_incremental = 2001;
                    if (Contrato::ultimoNumero(session::get('ID_SUCURSAL')) != '' && Contrato::ultimoNumero(session::get('ID_SUCURSAL'))) {
                        $nro_incremental = Contrato::ultimoNumero(session::get('ID_SUCURSAL')) + 1;
                    }
                    $codigo_contrato .= '.' . $nro_incremental;

                    $idContrato = Contrato::create([
                        //'persona_id'               => $request['ddlPersona'],
                        'cliente_id'        => $request['txtIdClienteOculto'],
                        'sucursal_id'       => session::get('ID_SUCURSAL'),
                        //'codigo'            => $request['txtCodigoCredito'],
                        'codigo'            => $codigo_contrato,
                        'peso_total'        => $request['txtPesoBruto'],
                        'fecha_contrato'    => Carbon::parse($request['txtFechaContrato'])->format('Y-m-d'),
                        'fecha_fin'         => Carbon::parse($resFechaProximo)->format('Y-m-d'),
                        'total_capital'     => (float)$request['txtCreditoMax'],
                        'plazo'             => 30,
                        'cuota_mora'        => 0,
                        'capital'           => $request['txtCreditoPrestar'],
                        'gestion'           => Carbon::parse($request['txtFechaContrato'])->format('Y'),
                        'p_interes'         => $request['p_interes'],
                        'interes'           => $request['interes'],
                        'comision'          => $request['comision'],
                        'caja'              => session::get('CAJA'),
                        'estado_pago'       => 'DESEMBOLSO DE CREDITO',
                        'estado_entrega'    => 'PRENDA CUSTODIA',
                        'estado_pago_2'     => 'CUSTODIA',
                        'usuario_id'        => session::get('ID_USUARIO'),
                        'estado_id'         => 1,
                        'totalTasacion'     => $request['totalTasacionGeneral'],
                        'codigo_num'        => (float)$nro_incremental,
                        'moneda_id'         => $request['txtMoneda']
                    ])->id;

                    $cont = 1;
                    for ($i = 0; $i < count($array_cantidad); $i++) {
                        //INSERTAMOS CONTRATO DETALLE
                        $nuevo_detalle = contratoDetalle::create([
                            'contrato_id'   => $idContrato,
                            'cantidad'      => $array_cantidad[$i],
                            'descripcion'   => $array_descripción[$i],
                            'peso'          => $array_pesoBruto[$i],
                            'dies'          => $array_diezKlts[$i],
                            'catorce'       => $array_catorceKlts[$i],
                            'dieciocho'     => $array_dieciochoKlts[$i],
                            'veinticuatro'  => $array_veintiCuatroKlts[$i],
                            'usuario_id'    => session::get('ID_USUARIO'),
                            'estado_id'     => 1,
                            'foto' => NULL
                        ]);
                        if ($array_fotos[$i] != '' && $array_fotos[$i] != null) {
                            $info_imagen = explode(',', $array_fotos[$i], 2);
                            $info_imagen = $info_imagen[1];
                            $foto = base64_decode($info_imagen);
                            $nombre_foto = $cont . '_' . $idContrato . '_' . time() . '.png';
                            $ruta =  'template/imgs/' . $nombre_foto;
                            file_put_contents($ruta, $foto);
                            $nuevo_detalle->foto = $nombre_foto;
                            $nuevo_detalle->save();
                            $cont++;
                        }
                    }

                    Sucursal::where("id", session::get('ID_SUCURSAL'))->update(["genera_codigo" => (int)$resCodigo]);

                    $idPago = Pagos::create([
                        'contrato_id'          => $idContrato,
                        'sucursal_id'          => session::get('ID_SUCURSAL'),
                        'fecha_inio'           => Carbon::parse($request['txtFechaContrato'])->format('Y-m-d H:i:s'),
                        'fecha_fin'            => Carbon::parse($resFechaProximo)->format('Y-m-d H:i:s'),
                        'fecha_pago'           => $request['fecha_pago'],
                        'caja'                 => session::get('CAJA'),
                        'dias_atraso'          => 0,
                        'capital'              => $request['txtCreditoPrestar'],
                        'interes'              => $request['interes'],
                        'comision'             => $request['comision'],
                        'cuota_mora'           => 0,
                        'total_capital'        => $request['txtCreditoMax'],
                        'estado'               => 'DESEMBOLSO',
                        'estado_id'            => 1,
                        'usuario_id'           => session::get('ID_USUARIO'),
                        'moneda_id'             => $request['txtMoneda']
                    ])->id;

                    $datoInicioCaja = InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                        ->where('caja', session::get('CAJA'))
                        ->where('fecha', Carbon::parse($request['txtFechaContrato'])->format('Y-m-d'))
                        ->whereIn('estado_id', [1, 2])
                        ->first();
                    //dd($datoInicioCaja);

                    $contadorInicioCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
                        ->where('caja', session::get('CAJA'))
                        ->where('fecha_pago', Carbon::parse($request['txtFechaContrato'])->format('Y-m-d'))
                        ->where('estado_id', 1)->count();
                    //dd($contadorInicioCajaDetalle);                    


                    if ($contadorInicioCajaDetalle == 0) {
                        $inicioCajaBs = $datoInicioCaja->inicio_caja_bs;
                        $idInicioCaja = $datoInicioCaja->id;
                    } else {
                        $datoCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))->where('caja', session::get('CAJA'))->where('fecha_pago', Carbon::parse($request['txtFechaContrato'])->format('Y-m-d'))->where('estado_id', 1)->orderBy('id', 'DESC')->first();
                        $inicioCajaBs = $datoCajaDetalle->inicio_caja_bs;
                        $idInicioCaja = $datoCajaDetalle->inicio_fin_caja_id;
                    }

                    $resulInicioCaja = (float)$inicioCajaBs - (float)$request['txtCreditoPrestar'];
                    //dd(round($resulInicioCaja, 2)); 

                    InicioFinCajaDetalle::create([
                        'inicio_fin_caja_id'    => $idInicioCaja,
                        'contrato_id'           => $idContrato,
                        'pago_id'               => $idPago,
                        'sucursal_id'           => session::get('ID_SUCURSAL'),
                        'fecha_pago'            => Carbon::parse($request['txtFechaContrato'])->format('Y-m-d'),
                        'fecha_hora'            => Carbon::parse($resFechaProximo)->format('Y-m-d'),
                        'inicio_caja_bs'        => round($resulInicioCaja, 2),
                        'egreso_bs'             => round($request['txtCreditoPrestar'], 2),
                        'tipo_de_movimiento'    => 'DESEMBOLSO CAPITAL CREDITO S/C N°' . $resConcatCodigo,
                        'ref'                   => 'DA01',
                        'caja'              => session::get('CAJA'),
                        'usuario_id'        => session::get('ID_USUARIO'),
                        'estado_id'         => 1,
                        'moneda_id'         => $request['txtMoneda']
                    ]);

                    /*REGISTRAR PARTE CONTABLE*/
                    /*
                    PARA GUARDAR REGISTROS EN "ContaDiario"
                    SI LA MONEDA REGISTRADA ES 2(dolares) CONVERTIR A Bs.
                    EN LA MAYORIA DE LOS CASOS
                    */
                    $numComprobante = ContaDiario::max('num_comprobante');

                    if ($request['txtMoneda'] == 2) {
                        // convertir a bolivianos
                        $valores_cambio = CambioMoneda::first();
                        $credito_convertido = round((float)$valores_cambio->valor_bs * (float)$request['txtCreditoPrestar'], 2);
                    } else {
                        $credito_convertido = round((float)$request['txtCreditoPrestar'], 2);
                    }

                    ContaDiario::create([
                        'contrato_id'        => $idContrato,
                        'pagos_id'           => $idPago,
                        'sucursal_id'        => session::get('ID_SUCURSAL'),
                        'fecha_a'            => Carbon::parse($request['txtFechaContrato'])->format('Y-m-d'),
                        'fecha_b'            => Carbon::parse($request['txtFechaContrato'])->format('Y-m-d'),
                        'glosa'                 => 'DESEMBOLSO CAPITAL CREDITO S/C N°' . $resConcatCodigo,
                        'cod_deno'              => '11301',
                        'cuenta'                => 'Prestamos a plazo fijo vigentes',
                        'debe'                  => $credito_convertido,
                        'haber'                 => '0.00',
                        'caja'                  => session::get('CAJA'),
                        'num_comprobante'       => $numComprobante + 1,
                        'periodo'               => 'mes',
                        'tcom'                  => 'EGRESO',
                        'ref'                   => 'DA01',
                        'usuario_id'            => session::get('ID_USUARIO'),
                        'estado_id'             => 1
                    ]);

                    ContaDiario::create([
                        'contrato_id'        => $idContrato,
                        'pagos_id'           => $idPago,
                        'sucursal_id'        => session::get('ID_SUCURSAL'),
                        'fecha_a'            => Carbon::parse($request['txtFechaContrato'])->format('Y-m-d'),
                        'fecha_b'            => Carbon::parse($request['txtFechaContrato'])->format('Y-m-d'),
                        'glosa'                 => 'DESEMBOLSO CAPITAL CREDITO S/C N°' . $resConcatCodigo,
                        'cod_deno'              => '11102',
                        'cuenta'                => 'Caja sucursales',
                        'debe'                  => '0.00',
                        'haber'                 => $credito_convertido,
                        'caja'                  => session::get('CAJA'),
                        'num_comprobante'       => $numComprobante + 1,
                        'periodo'               => 'mes',
                        'tcom'                  => 'EGRESO',
                        'ref'                   => 'DA01',
                        'usuario_id'            => session::get('ID_USUARIO'),
                        'estado_id'             => 1
                    ]);

                    $contrato = Contrato::find($idContrato);
                    return response()->json(["Mensaje" => $contrato->codigo, "idContrato" => $idContrato]);
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


    static function inicializa_desembolso_pago($contrato_id)
    {
        try {

            $idContrato = $contrato_id;
            $contrato = Contrato::where('id', $idContrato)->first();

            $idPago = Pagos::create([
                'contrato_id'          => $idContrato,
                'sucursal_id'          => $contrato->sucursal_id,
                'fecha_inio'           => Carbon::parse($contrato->fecha_contrato)->format('Y-m-d H:i:s'),
                'fecha_fin'            => Carbon::parse($contrato->fecha_fin)->format('Y-m-d H:i:s'),
                'fecha_pago'           => $contrato->fecha_pago,
                'caja'                 => $contrato->caja,
                'dias_atraso'          => 0,
                'capital'              => $contrato->capital,
                'interes'              => $contrato->interes,
                'comision'             => $contrato->comision,
                'cuota_mora'           => 0,
                'total_capital'        => $contrato->total_capital,
                'estado'               => 'DESEMBOLSO',
                'estado_id'            => 1,
                'usuario_id'           => $contrato->usuario_id,
                'moneda_id'            => $contrato->moneda_id
            ])->id;

            $datoInicioCaja = InicioFinCaja::where('sucursal_id', $contrato->sucursal_id)
                ->where('caja', $contrato->caja)
                ->where('fecha', Carbon::parse($contrato->fecha_contrato)->format('Y-m-d'))
                ->whereIn('estado_id', [1, 2])
                ->first();
            //dd($datoInicioCaja);

            $contadorInicioCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', $contrato->sucursal_id)
                ->where('caja', $contrato->caja)
                ->where('fecha_pago', Carbon::parse($contrato->fecha_contrato)->format('Y-m-d'))
                ->where('estado_id', 1)->count();
            //dd($contadorInicioCajaDetalle);                    


            if ($contadorInicioCajaDetalle == 0) {
                $inicioCajaBs = $datoInicioCaja->inicio_caja_bs;
                $idInicioCaja = $datoInicioCaja->id;
            } else {
                $datoCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', $contrato->sucursal_id)->where('caja', $contrato->caja)->where('fecha_pago', Carbon::parse($contrato->fecha_contrato)->format('Y-m-d'))->where('estado_id', 1)->orderBy('id', 'DESC')->first();
                $inicioCajaBs = $datoCajaDetalle->inicio_caja_bs;
                $idInicioCaja = $datoCajaDetalle->inicio_fin_caja_id;
            }

            $resulInicioCaja = (float)$inicioCajaBs - (float)$contrato->capital;
            //dd(round($resulInicioCaja, 2)); 

            InicioFinCajaDetalle::create([
                'inicio_fin_caja_id'    => $idInicioCaja,
                'contrato_id'           => $idContrato,
                'pago_id'               => $idPago,
                'sucursal_id'           => $contrato->sucursal_id,
                'fecha_pago'            => Carbon::parse($contrato->fecha_contrato)->format('Y-m-d'),
                'fecha_hora'            => Carbon::parse($contrato->fecha_pago)->format('Y-m-d'),
                'inicio_caja_bs'        => round($resulInicioCaja, 2),
                'egreso_bs'             => round($contrato->capital, 2),
                'tipo_de_movimiento'    => 'DESEMBOLSO CAPITAL CREDITO S/C N°' . $contrato->codigo,
                'ref'                   => 'DA01',
                'caja'              => $contrato->caja,
                'usuario_id'        => $contrato->usuario_id,
                'estado_id'         => 1,
                'moneda_id'         => $contrato->moneda_id
            ]);

            /*REGISTRAR PARTE CONTABLE*/
            /*
                    PARA GUARDAR REGISTROS EN "ContaDiario"
                    SI LA MONEDA REGISTRADA ES 2(dolares) CONVERTIR A Bs.
                    EN LA MAYORIA DE LOS CASOS
                    */
            $numComprobante = ContaDiario::max('num_comprobante');

            if ($contrato->moneda_id == 2) {
                // convertir a bolivianos
                $valores_cambio = CambioMoneda::first();
                $credito_convertido = round((float)$valores_cambio->valor_bs * (float)$contrato->capital, 2);
            } else {
                $credito_convertido = round((float)$contrato->capital, 2);
            }

            ContaDiario::create([
                'contrato_id'        => $idContrato,
                'pagos_id'           => $idPago,
                'sucursal_id'        => $contrato->sucursal_id,
                'fecha_a'            => Carbon::parse($contrato->fecha_contrato)->format('Y-m-d'),
                'fecha_b'            => Carbon::parse($contrato->fecha_contrato)->format('Y-m-d'),
                'glosa'                 => 'DESEMBOLSO CAPITAL CREDITO S/C N°' . $contrato->codigo,
                'cod_deno'              => '11301',
                'cuenta'                => 'Prestamos a plazo fijo vigentes',
                'debe'                  => $credito_convertido,
                'haber'                 => '0.00',
                'caja'                  => $contrato->caja,
                'num_comprobante'       => $numComprobante + 1,
                'periodo'               => 'mes',
                'tcom'                  => 'EGRESO',
                'ref'                   => 'DA01',
                'usuario_id'            => $contrato->usuario_id,
                'estado_id'             => 1
            ]);

            ContaDiario::create([
                'contrato_id'        => $idContrato,
                'pagos_id'           => $idPago,
                'sucursal_id'        => $contrato->sucursal_id,
                'fecha_a'            => Carbon::parse($contrato->fecha_contrato)->format('Y-m-d'),
                'fecha_b'            => Carbon::parse($contrato->fecha_contrato)->format('Y-m-d'),
                'glosa'                 => 'DESEMBOLSO CAPITAL CREDITO S/C N°' . $contrato->codigo,
                'cod_deno'              => '11102',
                'cuenta'                => 'Caja sucursales',
                'debe'                  => '0.00',
                'haber'                 => $credito_convertido,
                'caja'                  => $contrato->caja,
                'num_comprobante'       => $numComprobante + 1,
                'periodo'               => 'mes',
                'tcom'                  => 'EGRESO',
                'ref'                   => 'DA01',
                'usuario_id'            => $contrato->usuario_id,
                'estado_id'             => 1
            ]);

            return Pagos::find($idPago);
            //DB::commit(); 

        } catch (Exception $e) {
            //DB::rollback();
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

    public function ObtieneCategoria(Request $request)
    {
        $id_cliente = $request->id_cliente;
        // BUSCAR LOS CONTRATOS QUE SE HALLAN PAGADO A TIEMPO
        $contratos_cancelados = Contrato::where('estado_pago', 'Credito cancelado')
            ->where('cliente_id', $id_cliente)->get();
        // BUSCAR  LOS ULTIMOS PAGOS DEL CONTRATO
        $contador_contratos_dia = 0;
        foreach ($contratos_cancelados as $contrato_cancelado) {
            $pagos_contrato_cancelado = Pagos::where('contrato_id', $contrato_cancelado->id)
                ->whereOr('dias_atraso', '<=', 0)
                ->whereOr('dias_atraso', null)
                ->get()
                ->last();
            if ($pagos_contrato_cancelado) {
                $contador_contratos_dia++;
            }
        }

        // BUSCAR EN CATEGORIA DE CLIENTES
        $categoria_cliente = CategoriaCliente::where('numero_contratos', '<=', $contador_contratos_dia)
            ->orderBy('numero_contratos', 'ASC')
            ->get()
            ->last();
        if ($categoria_cliente) {

            // ACTUALIZAR INFORMACIÓN DE LA CATEGORIA DEL CLIENTE
            $existe = ClienteCategoria::where('cliente_id', $id_cliente)->get()->first();

            if ($existe) {
                $existe->categoria_id = $categoria_cliente->id;
                $existe->save();
            } else {
                ClienteCategoria::create([
                    'cliente_id' => $id_cliente,
                    'categoria_id' => $categoria_cliente->id,
                ]);
            }

            return response()->JSON([
                'sw' => true,
                'existe' => true,
                'porcentaje' => $categoria_cliente->porcentaje
            ]);
        } else {
            $existe = ClienteCategoria::where('cliente_id', $id_cliente)->get()->first();
            // SI NO PERTENE A NINGUNA CATEGORIA
            // PERO YA SE ENCUENTRA REGISTRADO ELIMINAR EL REGISTRO
            if ($existe) {
                $existe->delete();
            }
        }

        return response()->JSON([
            'sw' => true,
            'existe' => false,
            'porcentaje' => 3
        ]);
    }

    public function buscarClientes(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $txtBuscarIdentifiacion = $request['txtBuscarIdentifiacion'];
            $txtBuscarNombres = $request['txtBuscarNombres'];
            $txtBuscarPaterno = $request['txtBuscarPaterno'];
            $txtBuscarMaterno = $request['txtBuscarMaterno'];
            $txtBuscarFechaNacimiento = $request['txtBuscarFechaNacimiento'];
            //dd($txtBuscarFechaNacimiento);
            if ($txtBuscarFechaNacimiento) {
                //dd($txtBuscarFechaNacimiento);
                $txtBuscarFechaNacimiento = Carbon::parse($request['txtBuscarFechaNacimiento'])->format('Y-m-d');
                //dd($txtBuscarFechaNacimiento);
                // $personas = Persona::where('nrodocumento', 'like', '%' . $txtBuscarIdentifiacion . '%')
                //     ->where('nombres', 'like', '%' . strtoupper($txtBuscarNombres) . '%')
                //     ->where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%')
                //     ->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                where('fechanacimiento', $txtBuscarFechaNacimiento)
                    ->orderBy('primerapellido', 'ASC')
                    ->orderBy('segundoapellido', 'ASC')
                    ->paginate(10);
            } else {
                if ($txtBuscarPaterno) {
                    //dd("ci");
                    $personas = Persona::where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%')
                        //->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                        ->where('estado_id', 1)
                        ->orderBy('primerapellido', 'ASC')
                        ->orderBy('segundoapellido', 'ASC')
                        ->paginate(10);
                }
                if ($txtBuscarIdentifiacion) {
                    $personas = Persona::where('nrodocumento', 'like', '%' . $txtBuscarIdentifiacion . '%')
                        //->where('nombres', 'like', '%' . strtoupper($txtBuscarNombres) . '%')
                        //->where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%')
                        //->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                        ->where('estado_id', 1)
                        ->orderBy('primerapellido', 'ASC')
                        ->orderBy('segundoapellido', 'ASC')
                        ->paginate(10);
                }
                if ($txtBuscarPaterno && $txtBuscarMaterno) {
                    $personas = Persona::
                        //->where('nombres', 'like', '%' . strtoupper($txtBuscarNombres) . '%')
                        where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%')
                        ->where('estado_id', 1)
                        ->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                        ->orderBy('primerapellido', 'ASC')
                        ->orderBy('segundoapellido', 'ASC')
                        ->paginate(10);
                }

                if ($txtBuscarMaterno) {
                    //dd("ci");
                    $personas = Persona::where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                        ->where('estado_id', 1)
                        ->orderBy('primerapellido', 'ASC')
                        ->orderBy('segundoapellido', 'ASC')
                        ->paginate(10);
                }
            }

            //dd($personas);
            if ($request->ajax()) {
                return view('contrato.modals.listadoClientes', ['personas' => $personas])->render();
            }
            return view('contrato.index', compact('personas'));
        } else {
            return view("layout.login");
        }
    }

    public function buscarContratos(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $cliente = Cliente::where('persona_id', $request['idPersona'])->where('estado_id', 1)->first();
            //dd($cliente);
            if ($cliente) {
                $contratos = Contrato::where('cliente_id', $cliente->id)->whereIn('estado_id', [1, 3])->orderBy('id', 'DESC')->get();
                //dd($contratos);
                if ($contratos) {
                    if ($request->ajax()) {
                        return view('contrato.modals.listadoContrato', ['contratos' => $contratos, 'cliente' => $cliente])->render();
                    }
                    return view('contrato.index', compact('contratos', 'cliente'));
                }
            }
        } else {
            return view("layout.login");
        }
    }

    public function buscarContratosDetalle(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $contrato = Contrato::where('id', $request['idContrato'])->first();
            $cliente = Cliente::where('id', $contrato->cliente_id)->where('estado_id', 1)->first();
            $contratoDetalle = ContratoDetalle::where('contrato_id', $request['idContrato'])->where('estado_id', 1)->get();
            //dd($contratoDetalle);
            if ($contratoDetalle) {
                if ($request->ajax()) {
                    return view('contrato.modals.listadoContratoDetalle', ['contratoDetalle' => $contratoDetalle, 'cliente' => $cliente])->render();
                }
                return view('contrato.index', compact('contratoDetalle', 'cliente'));
            }
        } else {
            return view("layout.login");
        }
    }

    public function imprimirReporteContrato($id)
    {
        $contrato = Contrato::where('id', $id)->first();
        $cliente = Cliente::where('id', $contrato->cliente_id)->first();
        $totalPagar = (float)$contrato->capital + (float)$contrato->interes + (float)$contrato->comision;
        //dd($contrato->sucural->nuevo_codigo);

        $resCodigo =  $contrato->sucural->nuevo_codigo . '' . Carbon::parse($contrato->fecha_contrato)->format('y') . '' . $contrato->codigo_num;

        $valores_cambio = CambioMoneda::first();

        //dd($cliente->persona);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setPrintHeader(false);
        $pdf::setPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);

        $pdf::SetFont('helvetica', '', 11);


        $pdf::AddPage('L', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetFont('helvetica', 'B', 25);
        $pdf::SetXY(215, 30);


        if ($contrato->codigo != "") {
            $codigoG = $contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetFont('helvetica', 'B', 15);
        $pdf::SetXY(45, 50);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(45, 57);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(45, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->domicilio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(45, 71);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->zona, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(45, 78);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->celular, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 15);
        $pdf::SetXY(225, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = $contrato->fecha_contrato . ' ' . Carbon::parse($contrato->created_at)->format('H:i:s'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(225, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = $contrato->fecha_fin  . ' ' . Carbon::parse($contrato->created_at)->format('H:i:s'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $interes = (float)$contrato->interes + (float)$contrato->comision;
        $pdf::SetXY(220, 95);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($interes, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(220, 103);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($contrato->capital, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        if ($contrato->moneda_id == 2) {
            // convertir a bolivianos
            $credito_convertido = round((float)$valores_cambio->valor_bs * (float)$totalPagar, 2);
        } else {
            // DOLARES
            $credito_convertido = round((float)$totalPagar / (float) $valores_cambio->valor_bs, 2);
        }

        if ($contrato->moneda_id == 2) {
            $pdf::SetXY(80, 110);
            $pdf::Cell($w = 0, $h = 0, $txt = "Total a Cancelar Bs.: " . number_format($credito_convertido, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(80, 117);
            $pdf::Cell($w = 0, $h = 0, $txt = "Total a Cancelar \$us: " . number_format($totalPagar, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $pdf::SetXY(80, 110);
            $pdf::Cell($w = 0, $h = 0, $txt = "Total a Cancelar Bs.: " . number_format($totalPagar, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(80, 117);
            $pdf::Cell($w = 0, $h = 0, $txt = "Total a Cancelar \$us: " . number_format($credito_convertido, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $nl = new NumberToLetterConverter();

        if ($contrato->moneda_id == 2) {
            $pdf::SetXY(168, 110);
            $pdf::SetFont('helvetica', 'B', 9);
            $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($credito_convertido) . ' Bolivianos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(168, 117);
            $pdf::SetFont('helvetica', 'B', 9);
            $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($totalPagar) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $pdf::SetXY(168, 110);
            $pdf::SetFont('helvetica', 'B', 9);
            $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($totalPagar) . ' Bolivianos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(168, 117);
            $pdf::SetFont('helvetica', 'B', 9);
            $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($credito_convertido) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetFont('helvetica', 'B', 16);
        $pdf::SetXY(20, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "Cant.", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(210, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "P.B.", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(225, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "10k", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(240, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "14k", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(255, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "18k", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(270, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "24k", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $detallesContrato = ContratoDetalle::where('contrato_id', $contrato->id)->where('estado_id', 1)->get();
        $cantidad = 0;
        $posicion = 132;
        $totalCantidad = 0;
        $totalPesoBruto = 0;
        $total10k = 0;
        $total18k = 0;
        $total14k = 0;
        $total24k = 0;


        //$cantidadPosicion = 110;
        foreach ($detallesContrato as $key => $detalleContrato) {
            $pdf::SetFont('helvetica', 'B', 13);
            $totalCantidad = $totalCantidad + $detalleContrato->cantidad;
            $totalPesoBruto = $totalPesoBruto + $detalleContrato->peso;
            $total10k = $total10k + $detalleContrato->dies;
            $total14k = $total14k + $detalleContrato->catorce;
            $total18k = $total18k + $detalleContrato->dieciocho;
            $total24k = $total24k + $detalleContrato->veinticuatro;
            $pdf::SetXY(21, $posicion);
            $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->cantidad, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(30, $posicion);
            $pdf::Cell($w = 0, $h = 0, $txt = substr($detalleContrato->descripcion, 0, 64), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


            $pdf::SetFont('helvetica', 'B', 15);
            $pdf::SetXY(212, $posicion);
            $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->peso, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(227, $posicion);


            //dd($detalleContrato->dies);
            if ($detalleContrato->dies) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->dies, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }


            $pdf::SetXY(242, $posicion);
            if ($detalleContrato->catorce) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->catorce, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }


            $pdf::SetXY(257, $posicion);
            if ($detalleContrato->dieciocho) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->dieciocho, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }

            $pdf::SetXY(272, $posicion);
            if ($detalleContrato->veinticuatro) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->veinticuatro, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }

            $posicion = $posicion + 5;
        }

        $pdf::SetFont('helvetica', 'B', 13);
        //dd($posicion);

        //$posicion = $posicion + 25;

        $pdf::SetXY(21, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $totalCantidad, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(30, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PESO NETO EN ORO(Gr)', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 15);
        $pdf::SetXY(212, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $totalPesoBruto, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(227, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $total10k, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(242, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $total14k, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(257, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $total18k, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(272, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $total24k, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(30, 187);
        $pdf::Cell($w = 0, $h = 0, $txt = 'VALOR DE TASACIÓN:  ' . $contrato->totalTasacion, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(120, 187);
        $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($contrato->totalTasacion) . ' ' . $contrato->moneda->moneda, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(30, 197);
        $pdf::Cell($w = 0, $h = 0, $txt = $contrato->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetXY(150, 195);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $generarCodigo = $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $contrato->fecha_contrato . "-" . number_format($totalPagar, 2, '.', ',');
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 265, 177, 25, 25, $style, 'N');

        $pdf::SetCellMargins(0, 0, 0, 0);
        $pdf::SetMargins(0, 0, 0);

        PDF::SetTitle('Reporte de Contrato');
        //PDF::AddPage('L', 'A5');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Contrato.pdf');
    }

    public function reImprimirReporteContrato($id)
    {
        $contrato = Contrato::where('id', $id)->first();
        $cliente = Cliente::where('id', $contrato->cliente_id)->first();
        $totalPagar = (float)$contrato->capital + (float)$contrato->interes + (float)$contrato->comision;
        //dd($contrato->sucural->nuevo_codigo);

        $resCodigo =  $contrato->sucural->nuevo_codigo . '' . Carbon::parse($contrato->fecha_contrato)->format('y') . '' . $contrato->codigo_num;

        $valores_cambio = CambioMoneda::first();
        //dd($cliente->persona);

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setPrintHeader(false);
        $pdf::setPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);

        $pdf::SetFont('helvetica', '', 11);


        $pdf::AddPage('L', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetFont('helvetica', 'B', 25);
        $pdf::SetXY(145, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = "REIMPRESION", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(215, 30);


        if ($contrato->codigo != "") {
            $codigoG = $contrato->codigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $codigoG = $resCodigo;
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetFont('helvetica', 'B', 15);
        $pdf::SetXY(45, 50);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(45, 57);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(45, 64);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->domicilio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(45, 71);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->zona, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(45, 78);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->celular, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 15);
        $pdf::SetXY(225, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = $contrato->fecha_contrato . ' ' . Carbon::parse($contrato->created_at)->format('H:i:s'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(225, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = $contrato->fecha_fin  . ' ' . Carbon::parse($contrato->created_at)->format('H:i:s'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $interes = (float)$contrato->interes + (float)$contrato->comision;
        $pdf::SetXY(220, 95);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($interes, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(220, 103);
        $pdf::Cell($w = 0, $h = 0, $txt = number_format($contrato->capital, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        if ($contrato->moneda_id == 2) {
            // convertir a bolivianos
            $credito_convertido = round((float)$valores_cambio->valor_bs * (float)$totalPagar, 2);
        } else {
            // DOLARES
            $credito_convertido = round((float)$totalPagar / (float) $valores_cambio->valor_bs, 2);
        }

        if ($contrato->moneda_id == 2) {
            $pdf::SetXY(80, 110);
            $pdf::Cell($w = 0, $h = 0, $txt = "Total a Cancelar Bs.: " . number_format($credito_convertido, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(80, 117);
            $pdf::Cell($w = 0, $h = 0, $txt = "Total a Cancelar \$us: " . number_format($totalPagar, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $pdf::SetXY(80, 110);
            $pdf::Cell($w = 0, $h = 0, $txt = "Total a Cancelar Bs.: " . number_format($totalPagar, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(80, 117);
            $pdf::Cell($w = 0, $h = 0, $txt = "Total a Cancelar \$us: " . number_format($credito_convertido, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $nl = new NumberToLetterConverter();

        if ($contrato->moneda_id == 2) {
            $pdf::SetXY(168, 110);
            $pdf::SetFont('helvetica', 'B', 9);
            $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($credito_convertido) . ' Bolivianos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(168, 117);
            $pdf::SetFont('helvetica', 'B', 9);
            $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($totalPagar) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $pdf::SetXY(168, 110);
            $pdf::SetFont('helvetica', 'B', 9);
            $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($totalPagar) . ' Bolivianos', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(168, 117);
            $pdf::SetFont('helvetica', 'B', 9);
            $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($credito_convertido) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetFont('helvetica', 'B', 16);
        $pdf::SetXY(20, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "Cant.", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(210, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "P.B.", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(225, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "10k", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(240, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "14k", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(255, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "18k", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(270, 124);
        $pdf::Cell($w = 0, $h = 0, $txt = "24k", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $detallesContrato = ContratoDetalle::where('contrato_id', $contrato->id)->where('estado_id', 1)->get();
        $cantidad = 0;
        $posicion = 132;
        $totalCantidad = 0;
        $totalPesoBruto = 0;
        $total10k = 0;
        $total18k = 0;
        $total14k = 0;
        $total24k = 0;


        //$cantidadPosicion = 110;
        foreach ($detallesContrato as $key => $detalleContrato) {
            $pdf::SetFont('helvetica', 'B', 13);
            $totalCantidad = $totalCantidad + $detalleContrato->cantidad;
            $totalPesoBruto = $totalPesoBruto + $detalleContrato->peso;
            $total10k = $total10k + $detalleContrato->dies;
            $total14k = $total14k + $detalleContrato->catorce;
            $total18k = $total18k + $detalleContrato->dieciocho;
            $total24k = $total24k + $detalleContrato->veinticuatro;
            $pdf::SetXY(21, $posicion);
            $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->cantidad, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(30, $posicion);
            $pdf::Cell($w = 0, $h = 0, $txt = substr($detalleContrato->descripcion, 0, 64), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


            $pdf::SetFont('helvetica', 'B', 15);
            $pdf::SetXY(212, $posicion);
            $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->peso, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(227, $posicion);


            //dd($detalleContrato->dies);
            if ($detalleContrato->dies) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->dies, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }


            $pdf::SetXY(242, $posicion);
            if ($detalleContrato->catorce) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->catorce, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }

            $pdf::SetXY(257, $posicion);
            if ($detalleContrato->dieciocho) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->dieciocho, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }

            $pdf::SetXY(272, $posicion);
            if ($detalleContrato->veinticuatro) {
                $pdf::Cell($w = 0, $h = 0, $txt = $detalleContrato->veinticuatro, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 0, $h = 0, $txt = 0, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }

            $posicion = $posicion + 5;
        }

        $pdf::SetFont('helvetica', 'B', 13);
        //dd($posicion);

        //$posicion = $posicion + 25;

        $pdf::SetXY(21, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $totalCantidad, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(30, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PESO NETO EN ORO(Gr)', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 15);
        $pdf::SetXY(212, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $totalPesoBruto, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(227, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $total10k, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(242, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $total14k, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(257, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $total18k, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(272, 177);
        $pdf::Cell($w = 0, $h = 0, $txt = $total24k, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(30, 187);
        $pdf::Cell($w = 0, $h = 0, $txt = 'VALOR DE TASACIÓN:  ' . $contrato->totalTasacion, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(120, 187);
        $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($contrato->totalTasacion) . ' ' . $contrato->moneda->moneda, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(30, 197);
        $pdf::Cell($w = 0, $h = 0, $txt = $contrato->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetXY(150, 195);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $generarCodigo = "REIMPRESION-" . $codigoG . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $contrato->fecha_contrato . "-" . number_format($totalPagar, 2, '.', ',');
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 265, 177, 25, 25, $style, 'N');

        $pdf::SetCellMargins(0, 0, 0, 0);
        $pdf::SetMargins(0, 0, 0);

        PDF::SetTitle('Reporte de Contrato');
        //PDF::AddPage('L', 'A5');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Contrato.pdf');
    }

    public function imprimirDesembolso($id)
    {
        $contrato = Contrato::where('id', $id)->first();
        $cliente = Cliente::where('id', $contrato->cliente_id)->first();
        $totalPagar = (float)$contrato->capital + (float)$contrato->interes + (float)$contrato->comision;
        $resCodigo =  $contrato->sucural->nuevo_codigo . '' . Carbon::parse($contrato->fecha_contrato)->format('y') . '' . $contrato->codigo_num;

        $valores_cambio = CambioMoneda::first();
        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 15);

        $pdf::AddPage('L', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(115, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AGENCIA:  ' . $contrato->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(125, 35);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(110, 40);
        $pdf::Cell($w = 0, $h = 0, $txt = 'COMPROBANTE DE DESEMBOLSO DE CREDITO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 17);
        $pdf::SetXY(30, 50);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(30, 60);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CREDITO No.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 15);
        $pdf::SetXY(80, 60);
        //$pdf::Cell($w=0, $h=0, $txt=$contrato->codigo, $border=0, $ln=50, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='B', $valign='B');
        if ($contrato->codigo != "") {
            $pdf::Cell($w = 0, $h = 0, $txt = $contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $pdf::Cell($w = 0, $h = 0, $txt = $resCodigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $pdf::SetXY(165, 50);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FECHA/HORA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(210, 50);
        $pdf::Cell($w = 0, $h = 0, $txt = $contrato->fecha_contrato . ' ' . Carbon::parse($contrato->created_at)->format('H:i:s'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        if ($contrato->moneda_id == 1) {
            // DOLARES
            $credito_convertido = round((float)$totalPagar / (float) $valores_cambio->valor_bs, 2);
        }

        if ($contrato->moneda_id == 1) {
            $pdf::SetXY(165, 57);
            $pdf::Cell($w = 0, $h = 0, "\$us.", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(210, 57);
            $pdf::Cell($w = 0, $h = 0, $txt = number_format($credito_convertido, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $pdf::SetXY(165, 57);
            $pdf::Cell($w = 0, $h = 0, "\$us", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(210, 57);
            $pdf::Cell($w = 0, $h = 0, $txt = number_format($totalPagar, 2, '.', ','), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }

        $nl = new NumberToLetterConverter();
        $pdf::SetXY(30, 80);
        if ($contrato->moneda_id == 1) {
            //$pdf::SetFont('helvetica', 'B', 9);
            $pdf::Cell($w = 0, $h = 0, $txt = "ENTREGAMOS LA SUMA DE " . $nl->numtoletras((float)$totalPagar) . ' ' . 'Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $pdf::Cell($w = 0, $h = 0, $txt = "ENTREGAMOS LA SUMA DE " . $nl->numtoletras((float)$totalPagar) . ' ' . $contrato->moneda->moneda, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }
        $pdf::SetXY(60, 90);
        $pdf::Cell($w = 0, $h = 0, $txt = "POR CONCEPTO DE DESEMBOLSO DE CREDITO DE REFERENCIA", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetXY(155, 105);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(170, 105);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(155, 110);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(155, 115);
        $pdf::Cell($w = 0, $h = 0, $txt = '**NO VALIDO PARA CREDITO FISCAL**', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Desembolso');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Desembolso.pdf');
    }

    /*********************************************************
     * ==================================================== *
                    COMPROBANTE CONTRATOS
     * ==================================================== *
     **********************************************************/
    // COMPROBANTE 1 $pdf::Ln(5);
    public function ImprimirComprobante($id)
    {
        $contrato = Contrato::where('id', $id)->first();
        $cliente = Cliente::where('id', $contrato->cliente_id)->first();
        $totalPagar = (float)$contrato->capital + (float)$contrato->interes + (float)$contrato->comision;
        $resCodigo =  $contrato->sucural->nuevo_codigo . '' . Carbon::parse($contrato->fecha_contrato)->format('y') . '' . $contrato->codigo_num;

        $valores_cambio = CambioMoneda::first();
        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
        });

        $pdf::SetFont('helvetica', 'B', 12);

        $pdf::AddPage('P', 'mm', array(1200, 2000));

        $pdf::SetFont('helvetica', 'N', 11);
        $pdf::Ln(4);
        // ciudad
        $pdf::SetXY(10, 34);
        $pdf::Cell($w = 20, $h = 0, $contrato->sucural->ciudad, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(27, 33);

        // fecha de contrato
        $pdf::Cell($w = 20, $h = 0, $contrato->fecha_contrato, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(28, 37);
        $pdf::Cell($w = 20, $h = 0, date('H:i:s'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // codigo de contrato
        $pdf::SetFont('helvetica', 'B', 18);
        $pdf::SetXY(62, 36);
        $pdf::Cell($w = 22, $h = 0, $contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'N', 11);

        // plazo contrato
        $pdf::SetXY(108, 34);
        $pdf::Cell($w = 20, $h = 0, '30 días', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // fecha fin contrato
        $pdf::SetXY(126, 34);
        $pdf::Cell($w = 22, $h = 0, $contrato->fecha_fin, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // codigo del cliente
        $pdf::SetXY(156, 34);
        $pdf::Cell($w = 40, $h = 0, $contrato->cliente->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // categoria cliente
        $pdf::SetXY(190, 34);
        $pdf::Cell($w = 25, $h = 0, $contrato->cliente->categoria ? $contrato->cliente->categoria->categoria->nombre : '', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // CONSULTAR EL TOTAL DE PIEZAS
        $total_piezas = DB::select("SELECT SUM(cantidad) AS total_piezas FROM detalle_contrato WHERE contrato_id = $contrato->id")[0];
        $peso_b = DB::select("SELECT SUM(peso) AS peso_b FROM detalle_contrato WHERE contrato_id = $contrato->id")[0];

        $pdf::SetFont('helvetica', 'N', 11);

        // Nro. de piezas
        $pdf::SetXY(18, 54);
        $pdf::Cell($w = 25, $h = 0, $total_piezas->total_piezas, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // peso bruto
        $pdf::SetXY(41, 54);
        $pdf::Cell($w = 15, $h = 0, number_format($peso_b->peso_b, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $credito_convertido = 0;
        if ($contrato->moneda_id == 1) {
            // CONVERTIR A DOLARES
            $credito_convertido = number_format((float)$contrato->total_capital / (float) $valores_cambio->valor_bs, 2);

            // credito maximo a prestar (total_capital)
            $pdf::SetXY(75, 54);
            $pdf::Cell($w = 63, $h = 0, $credito_convertido . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {

            // credito maximo a prestar (total_capital)
            $pdf::SetXY(75, 54);
            $pdf::Cell($w = 63, $h = 0, number_format($contrato->total_capital, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $credito_convertido = $contrato->total_capital;
        }

        $capital_convertido = number_format($contrato->capital, 2);
        $interes_convertido = number_format($contrato->interes, 2);
        $comision_convertido = number_format($contrato->comision, 2);
        if ($contrato->moneda_id == 1) {
            $capital_convertido = number_format($contrato->capital / $valores_cambio->valor_bs, 2);
            $interes_convertido = number_format($contrato->interes / $valores_cambio->valor_bs, 2);
            $comision_convertido = number_format($contrato->comision / $valores_cambio->valor_bs, 2);
        }

        // % de interes
        $pdf::SetXY(133, 54);
        $pdf::Cell($w = 33, $h = 0, $contrato->p_interes . '%', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // total interes
        $pdf::SetXY(158, 54);
        $pdf::Cell($w = 18, $h = 0, $interes_convertido . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // gastos de deuda (comision)
        $pdf::SetXY(190, 54);
        $pdf::Cell($w = 35, $h = 0, $comision_convertido . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // nombre cliente
        $pdf::SetXY(20, 62);
        $pdf::Cell($w = 0, $h = 0, $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // telefono cliente
        $pdf::SetXY(180, 62);
        $pdf::Cell($w = 0, $h = 0, $cliente->persona->celular, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // direccion cliente
        $pdf::SetXY(40, 67);
        $pdf::Cell($w = 0, $h = 0, $cliente->persona->domicilio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // c.i. cliente
        $pdf::SetXY(180, 67);
        $pdf::Cell($w = 0, $h = 0, $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // agencia (sucursal)
        $pdf::SetFont('helvetica', 'N', 10);
        $pdf::SetXY(20, 72);
        $pdf::Cell($w = 0, $h = 0,  $contrato->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'N', 11);
        // usuario
        $pdf::SetXY(100, 72);
        $pdf::Cell($w = 0, $h = 0,  $contrato->usuario->usuario, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // moneda
        $pdf::SetXY(174, 72);
        $pdf::Cell($w = 0, $h = 0, "\$us", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // capital solicitado
        $pdf::SetXY(31, 82);
        $pdf::Cell($w = 30, $h = 0,  $capital_convertido . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $nl = new NumberToLetterConverter();


        $capital_convertido = \str_replace(',', '', $capital_convertido);
        $capital_convertido = \number_format($capital_convertido, 2, '.', '');
        $pdf::SetXY(64, 82);
        $pdf::Cell($w = 100, $h = 0,  $nl->numtoletras((float)$capital_convertido) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $detallesContrato = ContratoDetalle::where('contrato_id', $contrato->id)->where('estado_id', 1)->get();

        $valor_acordado = 0;
        $precio_oro = PrecioOro::where('fecha', $contrato->fecha_contrato)->get()->first();
        $style = '
        <style>
        table{
            position:relative;
            width:570px;
        }

        td{
            padding:0px;
        }

        .texto{
            text-align:left;
            font-size: 0.85em;
            font-weight: normal;
        }

        .bold{
            font-weight:bold;
        }

        .texto_header{
            font-size: 0.8em;
        }

        .bgray{
            background-color: #e4e4e4;
            font-weight: bold;
        }

        </style>';

        $html = $style . '
        <table>';

        $total_piezas = 0;
        $total_pb = 0;
        $total_10k = 0;
        $total_14k = 0;
        $total_18k = 0;
        $total_24k = 0;
        $total_valor_acordado = 0;
        $cont = 1;
        foreach ($detallesContrato as $key => $detalleContrato) {
            // calculo precio acordado
            $valor_acordado = 0;
            if ($detalleContrato->dies != null && $detalleContrato->dies != '' && $detalleContrato->dies != 0) {
                $valor_acordado += $detalleContrato->dies * $precio_oro->dies;
            }

            if ($detalleContrato->catorce != null && $detalleContrato->catorce != '' && $detalleContrato->catorce != 0) {
                $valor_acordado += $detalleContrato->catorce * $precio_oro->catorce;
            }

            if ($detalleContrato->dieciocho != null && $detalleContrato->dieciocho != '' && $detalleContrato->dieciocho != 0) {
                $valor_acordado += $detalleContrato->dieciocho * $precio_oro->diesiocho;
            }

            if ($detalleContrato->veinticuatro != null && $detalleContrato->veinticuatro != '' && $detalleContrato->veinticuatro != 0) {
                $valor_acordado += $detalleContrato->veinticuatro * $precio_oro->veinticuatro;
            }

            $aux_acordado = (float)$valor_acordado / (float)$valores_cambio->valor_bs;
            $valor_acordado =  \number_format((float)$valor_acordado / (float)$valores_cambio->valor_bs, 2);

            $total_piezas += $detalleContrato->cantidad;
            $total_valor_acordado += $aux_acordado;
            $total_pb += $detalleContrato->peso;

            $html .= '
            <tr>
                <td width="9%" class="texto"> &nbsp;&nbsp;&nbsp;' . $cont++ . '</td>
                <td width="6%" class="texto"> &nbsp;&nbsp;&nbsp;' . $detalleContrato->cantidad . '</td>
                <td width="37%" class="texto">' . $detalleContrato->descripcion . '</td>
                <td width="11%" class="texto">' . $detalleContrato->peso . '</td>';

            if ($detalleContrato->dies) {
                $html .= '<td width="7%" class="texto">' . $detalleContrato->dies . '</td>';
                $total_10k += $detalleContrato->dies;
            } else {
                $total_10k += 0;
                $html .= '<td width="7%" class="texto">0</td>';
            }

            if ($detalleContrato->catorce) {
                $total_14k += $detalleContrato->catorce;
                $html .= '<td width="7%" class="texto">' . $detalleContrato->catorce . '</td>';
            } else {
                $total_14k += 0;
                $html .= '<td width="7%" class="texto">0</td>';
            }

            if ($detalleContrato->dieciocho) {
                $total_18k += $detalleContrato->dieciocho;
                $html .= '<td width="7%" class="texto">' . $detalleContrato->dieciocho . '</td>';
            } else {
                $total_18k += 0;
                $html .= '<td width="7%" class="texto">0</td>';
            }

            if ($detalleContrato->veinticuatro) {
                $total_24k += $detalleContrato->veinticuatro;
                $html .= '<td width="8%" class="texto">' . $detalleContrato->veinticuatro . '</td>';
            } else {
                $total_24k += 0;
                $html .= '<td width="8%" class="texto">0</td>';
            }

            $html .= '<td width="18%" class="texto">' . $valor_acordado . '</td>
            </tr>';
        }

        $html .= '
        <tr>
            <td class="bold"></td>   
            <td class="bold" style="text-align:center;">' . $total_piezas . '</td>   
            <td class="bold"></td>   
            <td class="bold">' . $total_pb . '</td>
            <td class="bold">' . $total_10k . '</td>
            <td class="bold">' . $total_14k . '</td>
            <td class="bold">' . $total_18k . '</td>
            <td class="bold">' . $total_24k . '</td>
            <td class="bold">' . number_format($total_valor_acordado, 2) . '</td>
        </tr>';
        $html .= '</table>';

        // tabla de bienes
        $pdf::SetXY(6, 109);
        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::SetMargins(0, 0, 0, true);
        PDF::SetTitle('Reporte de Comprobate');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Comprobate.pdf');
    }

    // COMPROBANTE 2
    public function ImprimirComprobante2($id)
    {
        $contrato = Contrato::where('id', $id)->first();
        $cliente = Cliente::where('id', $contrato->cliente_id)->first();
        $totalPagar = (float)$contrato->capital + (float)$contrato->interes + (float)$contrato->comision;
        $resCodigo =  $contrato->sucural->nuevo_codigo . '' . Carbon::parse($contrato->fecha_contrato)->format('y') . '' . $contrato->codigo_num;

        $valores_cambio = CambioMoneda::first();
        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
        });

        $pdf::SetFont('helvetica', 'B', 11);

        $pdf::AddPage('P', 'A4', false, false);

        $pdf::SetFont('helvetica', 'N', 11);
        $pdf::Ln(5);
        // ciudad
        $pdf::SetXY(9, 34);
        $pdf::Cell($w = 20, $h = 0, $contrato->sucural->ciudad, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        // fecha contrato
        $pdf::SetXY(28, 33);
        $pdf::Cell($w = 20, $h = 0, $contrato->fecha_contrato, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(28, 37);
        $pdf::Cell($w = 20, $h = 0, date('H:i:s'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        // codigo contrato
        $pdf::SetFont('helvetica', 'B', 18);
        $pdf::SetXY(64, 36);
        $pdf::Cell($w = 30, $h = 0, $contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'N', 12);
        // plazo contrato
        $pdf::SetXY(108, 35);
        $pdf::Cell($w = 20, $h = 0, '30 días', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // fecha fin contrato
        $pdf::SetXY(126, 35);
        $pdf::Cell($w = 30, $h = 0, $contrato->fecha_fin, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // codigo cliente
        $pdf::SetXY(155, 35);
        $pdf::Cell($w = 50, $h = 0, $contrato->cliente->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // categoria cliente
        $pdf::SetXY(190, 35);
        $pdf::Cell($w = 25, $h = 0, $contrato->cliente->categoria ? $contrato->cliente->categoria->categoria->nombre : '', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // CONSULTAR EL TOTAL DE PIEZAS
        $total_piezas = DB::select("SELECT SUM(cantidad) AS total_piezas FROM detalle_contrato WHERE contrato_id = $contrato->id")[0];
        $peso_b = DB::select("SELECT SUM(peso) AS peso_b FROM detalle_contrato WHERE contrato_id = $contrato->id")[0];

        $pdf::SetFont('helvetica', 'N', 11);

        // Nro. de piezas
        $pdf::SetXY(15, 53);
        $pdf::Cell($w = 25, $h = 0, $total_piezas->total_piezas, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // peso bruto
        $pdf::SetXY(37, 53);
        $pdf::Cell($w = 15, $h = 0, $peso_b->peso_b, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $credito_convertido = 0;
        if ($contrato->moneda_id == 1) {
            // convertir a dolares
            $credito_convertido = number_format((float)$contrato->total_capital / (float) $valores_cambio->valor_bs, 2);

            // credito maximo a prestar (total_capital)
            $pdf::SetXY(68, 53);
            $pdf::Cell($w = 70, $h = 0, $credito_convertido . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {

            // credito maximo a prestar (total_capital)
            $pdf::SetXY(68, 53);
            $pdf::Cell($w = 70, $h = 0, number_format($contrato->total_capital, 2) . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $credito_convertido = $contrato->total_capital;
        }

        $capital_convertido = number_format($contrato->capital, 2);
        $interes_convertido = number_format($contrato->interes, 2);
        $comision_convertido = number_format($contrato->comision, 2);
        if ($contrato->moneda_id == 1) {
            $capital_convertido = number_format($contrato->capital / $valores_cambio->valor_bs, 2);
            $interes_convertido = number_format($contrato->interes / $valores_cambio->valor_bs, 2);
            $comision_convertido = number_format($contrato->comision / $valores_cambio->valor_bs, 2);
        }

        // % de interes
        $pdf::SetXY(128, 53);
        $pdf::Cell($w = 33, $h = 0, $contrato->p_interes . '%', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // total interes
        $pdf::SetXY(157, 53);
        $pdf::Cell($w = 18, $h = 0, $interes_convertido . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // gastos de deuda (comision)
        $pdf::SetXY(185, 53);
        $pdf::Cell($w = 35, $h = 0, $comision_convertido . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // nombre cliente
        $pdf::SetXY(20, 64);
        $pdf::Cell($w = 0, $h = 0, $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // telefono cliente
        $pdf::SetXY(180, 64);
        $pdf::Cell($w = 0, $h = 0, $cliente->persona->celular, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // direccion cliente
        $pdf::SetXY(40, 69);
        $pdf::Cell($w = 0, $h = 0, $cliente->persona->domicilio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // c.i. cliente
        $pdf::SetXY(180, 69);
        $pdf::Cell($w = 0, $h = 0, $cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // sucursal
        $pdf::SetFont('helvetica', 'N', 10);
        $pdf::SetXY(20, 74.5);
        $pdf::Cell($w = 0, $h = 0,  $contrato->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'N', 12);
        // usuario
        $pdf::SetXY(100, 74.5);
        $pdf::Cell($w = 0, $h = 0,  $contrato->usuario->usuario, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // moneda
        $pdf::SetXY(171, 74.5);
        $pdf::Cell($w = 0, $h = 0, "\$us", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // credito solicitado
        $pdf::SetXY(32, 84);
        $pdf::Cell($w = 30, $h = 0,  $capital_convertido . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $capital_convertido = \str_replace(',', '', $capital_convertido);
        $capital_convertido = \number_format($capital_convertido, 2, '.', '');
        $nl = new NumberToLetterConverter();
        $pdf::SetXY(64, 84);
        $pdf::Cell($w = 100, $h = 0,  $nl->numtoletras((float)$capital_convertido) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetMargins(0, 0, 0, true);
        PDF::SetTitle('Reporte de Comprobate');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Comprobate.pdf');
    }

    /***********************************************************
     * ====================================================== *
                    FIN COMPROBANTE CONTRATOS
     * ====================================================== *
     ************************************************************/



    public function ImprimirBoleta($id)
    {
        $contrato = Contrato::where('id', $id)->first();
        $cliente = Cliente::where('id', $contrato->cliente_id)->first();
        $totalPagar = (float)$contrato->capital + (float)$contrato->interes + (float)$contrato->comision;
        $resCodigo =  $contrato->sucural->nuevo_codigo . '' . Carbon::parse($contrato->fecha_contrato)->format('y') . '' . $contrato->codigo_num;

        $valores_cambio = CambioMoneda::first();

        $totalPagar_convertido = $totalPagar;
        if ($contrato->moneda_id == 1) {
            $totalPagar_convertido = (float)$totalPagar / (float)$valores_cambio->valor_bs;
        }

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::AddPage('P', 'A4', false, false);

        // $pdf::SetXY(0, 5);
        // $url_img = asset('template/dist/img/icono.png');
        // $html = '<img src="' . $url_img  . '" style="width:90px;">';
        // $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::SetTextColor(5, 117, 5);
        $pdf::SetXY(40, 10);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PRENDASOL S.R.L.', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 12);
        $pdf::SetXY(175, 12);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Nº', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetTextColor(0, 0, 0);
        $pdf::SetXY(175, 17);
        $pdf::Cell($w = 0, $h = 0, $txt = $contrato->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = '
        <style>
        table{
            border: solid 1px green;
            padding:5px;
        }

        td{
            padding:0px;
        }

        .texto{
            text-align:left;
            font-size: 8pt;
            font-weight: normal;
        }

        </style>';

        $pdf::SetTextColor(5, 117, 5);
        $pdf::SetXY(14, 25);
        $html = $style . '
        <table width="85%">
            <tr>
                <td class="texto" width="15%" style="font-weight:bold;">
                Cliente:
                </td>
                <td class="texto" style="color:black;">
                ' . $contrato->cliente->persona->nombreCompleto() . '
                </td>
            </tr>
            <tr>
                <td class="texto" style="font-weight:bold;">
                C.I.:
                </td>
                <td class="texto" style="color:black;">
                ' . $contrato->cliente->persona->nrodocumento . '
                </td>
            </tr>
            <tr>
                <td class="texto" style="font-weight:bold;">
                Dirección:
                </td>
                <td class="texto" style="color:black;">
                ' . $contrato->cliente->persona->domicilio . '
                </td>
            </tr>
            <tr>
                <td class="texto" style="font-weight:bold;">
                Zona:
                </td>
                <td class="texto" style="color:black;">
                ' . $contrato->cliente->persona->zona . '
                </td>
            </tr>
            <tr>
                <td class="texto" style="font-weight:bold;">
                Teléfono:
                </td>
                <td class="texto" style="color:black;">
                ' . $contrato->cliente->persona->celular . '
                </td>
            </tr>
        </table>';
        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::SetXY(123, 25.5);
        $html = $style . '
        <table width="108%">
            <tr>
                <td class="texto" style="font-weight:bold;" width="45%">
                </td>
                <td class="texto">
                </td>
            </tr>
            <tr>
                <td class="texto" style="font-weight:bold;" width="45%">
                Fecha de Contrato:
                </td>
                <td class="texto" style="color:black;">
                ' . $contrato->fecha_contrato . '
                </td>
            </tr>
            <tr>
                <td class="texto" style="font-weight:bold;">
                Fecha de Vencimiento:
                </td>
                <td class="texto" style="color:black;">
                ' . $contrato->fecha_fin . '
                </td>
            </tr>
            <tr>
                <td class="texto" style="font-weight:bold;" width="45%">
                </td>
                <td class="texto">
                </td>
            </tr>
        </table>';

        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::SetFont('helvetica', 'N', 8);
        $pdf::SetXY(14, 79.5);

        $complex_cell_border = array(
            'T' => array('width' => 0.3, 'color' => array(5, 117, 5), 'dash' => 0, 'cap' => 'square'),
            'R' => array('width' => 0.3, 'color' => array(5, 117, 5), 'dash' => 0, 'cap' => 'square'),
            'B' => array('width' => 0.3, 'color' => array(5, 117, 5), 'dash' => 0, 'cap' => 'square'),
            'L' => array('width' => 0.3, 'color' => array(5, 117, 5), 'dash' => 0, 'cap' => 'square'),
        );

        // CONSULTAR EL TOTAL DE PIEZAS
        $total_piezas = DB::select("SELECT SUM(cantidad) AS total_piezas FROM detalle_contrato WHERE contrato_id = $contrato->id")[0];
        $peso_b = DB::select("SELECT SUM(peso) AS peso_b FROM detalle_contrato WHERE contrato_id = $contrato->id")[0];

        $valor_comparacion1 = 3499;
        $valor_comparacion2 = 10000;
        $valor_comparacion3 = 15000;
        if ($contrato->moneda_id == 2) {
            $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
            $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
            $valor_comparacion3 = 15000 / $valores_cambio->valor_bs;
        }

        $tasa_interes = 5;
        if ($contrato->total_capital <= $valor_comparacion1) {
            $tasa_interes = 9.04;
        } elseif ($contrato->total_capital < $valor_comparacion2) {
            $tasa_interes = 6.7;
        } elseif ($contrato->total_capital < $valor_comparacion3) {
            $tasa_interes = 6;
        }

        $total_capital_convertido = number_format($contrato->total_capital, 2);
        if ($contrato->moneda_id == 1) {
            $total_capital_convertido = number_format($contrato->total_capital / $valores_cambio->valor_bs, 2);
        }

        $pdf::SetFont('helvetica', 'N', 8);
        $pdf::SetXY(20, 68);
        $pdf::Cell($w = 35, $h = 0, 'CAPITAL SOLICITADO:', $complex_cell_border);
        $pdf::SetTextColor(0, 0, 0);
        $pdf::SetXY(55, 68);
        $pdf::Cell($w = 30, $h = 0,  $total_capital_convertido . ' $us', $complex_cell_border);
        $nl = new NumberToLetterConverter();
        $pdf::SetTextColor(0, 0, 0);
        $pdf::SetXY(95, 68);
        $pdf::Cell($w = 100, $h = 0,  $nl->numtoletras((float)$total_capital_convertido) . ' Dolares', $complex_cell_border);

        // $pdf::SetFont('helvetica', 'N', 8);
        // $pdf::SetXY(15, 66.1);
        // $pdf::Cell($w = 25, $h = 0, 'TOTAL PIEZAS', $complex_cell_border);
        // $pdf::SetTextColor(0, 0, 0);
        // $pdf::SetXY(15, 74);
        // $pdf::Cell($w = 25, $h = 0, $total_piezas->total_piezas, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // $pdf::SetTextColor(5, 117, 5);
        // $pdf::SetXY(40, 70);
        // $pdf::Cell($w = 15, $h = 0, 'P. B.(GR)', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        // $pdf::SetTextColor(0, 0, 0);
        // $pdf::SetXY(40, 74);
        // $pdf::Cell($w = 15, $h = 0, $peso_b->peso_b, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // $credito_convertido = 0;
        // $pdf::SetTextColor(5, 117, 5);
        // if ($contrato->moneda_id == 1) {
        //     // DOLARES
        //     $credito_convertido = round((float)$contrato->total_capital / (float) $valores_cambio->valor_bs, 2);

        //     $pdf::SetXY(55, 70);
        //     $pdf::Cell($w = 70, $h = 0, 'VALOR ACORDADO DEL (DE LOS) BIENES', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        //     $pdf::SetTextColor(0, 0, 0);
        //     $pdf::SetXY(55, 74);
        //     $pdf::Cell($w = 70, $h = 0, $credito_convertido . ' $us', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        // } else {
        //     // DOLARES
        //     $pdf::SetXY(55, 70);
        //     $pdf::Cell($w = 70, $h = 0, 'VALOR ACORDADO DEL (DE LOS) BIENES', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        //     $pdf::SetTextColor(0, 0, 0);
        //     $pdf::SetXY(55, 74);
        //     $pdf::Cell($w = 70, $h = 0, $contrato->total_capital . ' $us', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        //     $credito_convertido = $contrato->total_capital;
        // }

        // $valor_comparacion1 = 3499;
        // $valor_comparacion2 = 10000;
        // if ($contrato->moneda_id == 2) {
        //     $valor_comparacion1 = 3499 / $valores_cambio->valor_bs;
        //     $valor_comparacion2 = 10000 / $valores_cambio->valor_bs;
        // }

        // $tasa_interes = 6;
        // if ($contrato->total_capital <= $valor_comparacion1) {
        //     $tasa_interes = 9.04;
        // } elseif ($contrato->total_capital < $valor_comparacion2) {
        //     $tasa_interes = 6.7;
        // }

        // $pdf::SetTextColor(5, 117, 5);
        // $pdf::SetXY(125, 70);
        // $pdf::Cell($w = 30, $h = 0, 'TASA DE INTERÉS', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        // $pdf::SetTextColor(0, 0, 0);
        // $pdf::SetXY(125, 74);
        // $pdf::Cell($w = 30, $h = 0, $contrato->p_interes, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        // $pdf::SetTextColor(5, 117, 5);
        // $pdf::SetXY(155, 70);
        // $pdf::Cell($w = 20, $h = 0, 'INTERÉS', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        // $pdf::SetTextColor(0, 0, 0);
        // $pdf::SetXY(155, 74);
        // $pdf::Cell($w = 20, $h = 0, $tasa_interes, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // $gastos_deuda = 0;
        // $porcentaje_deuda = $tasa_interes - $contrato->p_interes;
        // $gastos_deuda = number_format($credito_convertido * ($porcentaje_deuda / 100), 2, '.', ',');

        // $pdf::SetTextColor(5, 117, 5);
        // $pdf::SetXY(175, 70);
        // $pdf::Cell($w = 27, $h = 0, 'GASTOS DEUDA', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        // $pdf::SetTextColor(0, 0, 0);
        // $pdf::SetXY(175, 74);
        // $pdf::Cell($w = 27, $h = 0, $gastos_deuda . ' $us', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetTextColor(5, 76);
        $pdf::SetFont('helvetica', 'N', 7);
        $pdf::SetXY(14, 80);
        $pdf::Cell($w = 0, $h = 0, 'PRENDASOL S.R.L. recepciona en garantía del préstamo otorgado, la prenda a continuación, de acuerdo a las condiciones en el contrato suscrito en el anverso', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(14, 82);
        $pdf::Cell($w = 0, $h = 0, 'Total a cancelar interés Convencional ', $complex_cell_border);

        $pdf::SetTextColor(0, 0, 0);
        $pdf::SetFont('helvetica', 'B', 8);
        $pdf::SetXY(66, 85.5);
        $pdf::Cell($w = 0, $h = 0, 'Total a Cancelar $us ' . number_format($totalPagar_convertido, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $nl = new NumberToLetterConverter();
        $pdf::SetXY(115, 85.5);
        $pdf::Cell($w = 150, $h = 0,  $nl->numtoletras((float)$totalPagar_convertido) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetTextColor(5, 117, 5);
        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(20, 90);
        $html = $style . '
        <table width="100%">
            <tr>
                <th style="border-bottom:solid 1px green;" width="10%">Cant.</th>
                <th style="border-bottom:solid 1px green;" width="40%">Descripción Prenda</th>
                <th style="border-bottom:solid 1px green;" width="10%">P.B.</th>
                <th style="border-bottom:solid 1px green;" width="10%">10k</th>
                <th style="border-bottom:solid 1px green;" width="10%">14K</th>
                <th style="border-bottom:solid 1px green;" width="10%">18K</th>
                <th style="border-bottom:solid 1px green;" width="10%">24K</th>
            </tr>';

        $detallesContrato = ContratoDetalle::where('contrato_id', $contrato->id)->where('estado_id', 1)->get();
        $cantidad = 0;
        $posicion = 104;
        $totalCantidad = 0;
        $totalPesoBruto = 0;
        $total10k = 0;
        $total18k = 0;
        $total14k = 0;
        $total24k = 0;

        $contador_filas = 0;
        $pdf::SetTextColor(0, 0, 0);
        foreach ($detallesContrato as $key => $detalleContrato) {
            $pdf::SetFont('helvetica', 'N', 9);
            $totalCantidad = $totalCantidad + $detalleContrato->cantidad;
            $totalPesoBruto = $totalPesoBruto + $detalleContrato->peso;
            $total10k = $total10k + $detalleContrato->dies;
            $total14k = $total14k + $detalleContrato->catorce;
            $total18k = $total18k + $detalleContrato->dieciocho;
            $total24k = $total24k + $detalleContrato->veinticuatro;

            $html .= '
            <tr>
                <td>' . $detalleContrato->cantidad . '</td>
                <td>' . substr($detalleContrato->descripcion, 0, 64) . ' Prenda</td>
                <td>' . $detalleContrato->peso . '</td>';

            if ($detalleContrato->dies) {
                $html .= '<td>' . $detalleContrato->dies . '</td>';
            } else {
                $html .= '<td>0</td>';
            }

            if ($detalleContrato->catorce) {
                $html .= '<td>' . $detalleContrato->catorce . '</td>';
            } else {
                $html .= '<td>0</td>';
            }

            if ($detalleContrato->dieciocho) {
                $html .= '<td>' . $detalleContrato->dieciocho . '</td>';
            } else {
                $html .= '<td>0</td>';
            }

            if ($detalleContrato->veinticuatro) {
                $html .= '<td>' . $detalleContrato->veinticuatro . '</td>';
            } else {
                $html .= '<td>0</td>';
            }

            $html .= '</tr>';

            $posicion = $posicion + 5;
            $contador_filas++;
        }

        for ($i = 1; $i <= (15 - $contador_filas); $i++) {
            $html .= '
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
        }

        $pdf::SetFont('helvetica', 'B', 9);
        $html .= '
            <tr>
                <td style="border-top:solid 1px green;">' . $totalCantidad . '</td>
                <td style="border-top:solid 1px green;">PESO NETO EN ORO(Gr)</td>
                <td style="border-top:solid 1px green;">' . $totalPesoBruto . '</td>
                <td style="border-top:solid 1px green;">' . $total10k . '</td>
                <td style="border-top:solid 1px green;">' . $total14k . '</td>
                <td style="border-top:solid 1px green;">' . $total18k . '</td>
                <td style="border-top:solid 1px green;">' . $total24k . '</td>
            </tr>';

        $html .= '</table>';
        $pdf::writeHTML($html, true, false, true, false, '');

        $posicion = 225;

        $valor_tasacion = $contrato->totalTasacion;
        $valor_tasacion = number_format((float)$contrato->totalTasacion / (float)$valores_cambio->valor_bs, 2, '.', ',');

        $pdf::SetFont('helvetica', 'B', 9);
        $pdf::SetXY(30, $posicion + 6);
        $pdf::Cell($w = 0, $h = 0, $txt = 'VALOR DE TASACIÓN:  ' . $valor_tasacion . ' $us', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(100, $posicion + 6);
        $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($valor_tasacion) . ' Dolares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetTextColor(0, 0, 0);
        $pdf::SetXY(30, $posicion + 12);
        $pdf::Cell($w = 0, $h = 0, $txt = $contrato->usuario->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(120, $posicion + 12);
        $pdf::Cell($w = 0, $h = 0, $txt = $cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $posicion += 14;

        $pdf::SetTextColor(5, 117, 5);
        $pdf::SetXY(30, $posicion);
        $pdf::Cell($w = 60, $h = 0, '............................................................', $border = 0, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(30, $posicion + 4);
        $pdf::Cell($w = 60, $h = 0, 'ADMINISTRADOR(A)', $border = 0, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(120, $posicion);
        $pdf::Cell($w = 60, $h = 0, '............................................................', $border = 0, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(120, $posicion + 4);
        $pdf::Cell($w = 60, $h = 0, 'CLIENTE', $border = 0, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetTextColor(5, 117, 5);
        $pdf::SetFont('helvetica', 'N', 6.6);
        $pdf::SetXY(10, $posicion + 9);
        $pdf::Cell($w = 0, $h = 0, 'El cliente que suscribe la presente declaración Voluntaria de forma libre y sin que medie vicio alguno del consentimiento, declara que los datos del presente contrato de préstamo con', $border = 0, $ln = 50, $align = 'L', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(10, $posicion + 13);
        $pdf::Cell($w = 0, $h = 0, 'garantía prendaria de Oro son auténticos y verdaderos, por lo que constituye declaración extrajudicial de conformidad a lo previsto por el Art. 426 del Código de Procedimiento Civil,', $border = 0, $ln = 50, $align = 'L', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(10, $posicion + 17);
        $pdf::Cell($w = 0, $h = 0, 'concordante con el Art. 132 del Código Civil', $border = 0, $ln = 50, $align = 'L', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // $pdf::SetXY(140, $posicion + 4);
        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $generarCodigo = $contrato->codigo . "-" . $cliente->persona->nombreCompleto() . "-" . $cliente->persona->nrodocumento . "-" . $contrato->fecha_contrato . "-" . number_format($totalPagar, 2, '.', ',');
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 180, $posicion + 15, 50, 50, $style, 'N');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Boleta');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Boleta.pdf');
    }

    public function imprimirContratoCambioMoneda($id)
    {
        $contrato = Contrato::where('id', $id)->first();
        $cliente = Cliente::where('id', $contrato->cliente_id)->first();
        $totalPagar = (float)$contrato->capital + (float)$contrato->interes + (float)$contrato->comision;
        $resCodigo =  $contrato->sucural->nuevo_codigo . '' . Carbon::parse($contrato->fecha_contrato)->format('y') . '' . $contrato->codigo_num;
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
        $pdf::Cell($w = 0, $h = 0, $txt = 'Agencia:    ' . $contrato->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(20, 37);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Dirección:  ' . $contrato->sucural->direccion, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(20, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Usuario:     ' . $contrato->usuario->usuario, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(20, 51);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Fecha:       ' . date('d/m/Y', strtotime($contrato->fecha_contrato)), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(100, 58);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI/NIT:   ' . $contrato->cliente->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(100, 65);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Cliente:   ' . $contrato->cliente->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(20, 73);
        $pdf::Cell($w = 0, $h = 0, $txt = 'TIPO CAMBIO OFICIAL:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(20, 82);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Importe recibido:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(20, 89);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Importe entregado:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(180, 73);
        $pdf::Cell($w = 0, $h = 0, $valores_cambio->valor_bs, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $capital_convertido_bs = $contrato->capital;
        $capital_convertido_sus = $contrato->capital;
        if ($contrato->moneda_id == 2) {
            $capital_convertido_bs = (float)$contrato->capital * (float)$valores_cambio->valor_bs;
        } else {
            $capital_convertido_sus = (float)$contrato->capital / (float)$valores_cambio->valor_bs;
        }

        $pdf::SetXY(180, 82);
        $pdf::Cell($w = 0, $h = 0, \number_format($capital_convertido_bs, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(180, 89);
        $pdf::Cell($w = 0, $h = 0, \number_format($capital_convertido_sus, 2), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $nl = new NumberToLetterConverter();
        $pdf::SetXY(95, 82);

        $pdf::SetXY(40, 98);
        $pdf::Cell($w = 0, $h = 0, $nl->numtoletras((float)$capital_convertido_sus), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(40, 106);
        $pdf::Cell($w = 0, $h = 0, 'Concepto:  Cambio Dólares', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(40, 114);
        $pdf::Cell($w = 0, $h = 0, 'Doc. Nro.:  182 - ' . $contrato->id, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Boleta');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Boleta.pdf');
    }

    public function contratos_cancelados()
    {
        return view('contrato.contratos_cancelados');
    }

    public function contratos_cancelados_pdf(Request $request)
    {
        $fecha_ini = date('Y-m-d', strtotime($request->fecha_ini));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
        $filtro = $request->filtro;

        $contratos_cancelados = Contrato::where('estado_pago', 'Credito cancelado')
            ->where('fecha_pago', $fecha_ini)->get();
        if ($filtro == 'fecha') {
            $contratos_cancelados = Contrato::where('estado_pago', 'Credito cancelado')
                ->whereBetween('fecha_pago', [$fecha_ini, $fecha_fin])->get();
        }
        $valores_cambio = CambioMoneda::first();
        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setPrintHeader(false);
        $pdf::setPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 14);
        $pdf::AddPage('L', 'A4', false, false);
        $pdf::SetXY(100, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'REPORTE DE CONTRATOS CANCELADOS', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(110, 40);
        $pdf::Cell($w = 0, $h = 5.5, $txt = 'FECHA DE EMISIÓN: ' . Carbon::now('America/La_Paz')->format('Y-m-d'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(20, 45);
        $pdf::MultiCell(15, 15, 'Nº', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(35, 45);
        $pdf::MultiCell(130, 15, 'CLIENTE', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(165, 45);
        $pdf::MultiCell(40, 15, 'CÓDIGO CONTRATO', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(205, 45);
        $pdf::MultiCell(25, 15, 'FECHA CONTRATO', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(230, 45);
        $pdf::MultiCell(25, 15, 'FECHA PAGO', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(255, 45);
        $pdf::MultiCell(35, 15, 'TOTAL CANCELADO (Bs.)', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $posicion = 65.5;
        $suma_total = 0;
        $cont = 1;
        $pdf::SetFont('helvetica', 'N', 11);
        foreach ($contratos_cancelados as $contrato) {
            $pdf::SetXY(20, $posicion);
            $pdf::Cell($w = 15, $h = 5.5, $cont++, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(35, $posicion);
            $pdf::Cell($w = 130, $h = 5.5, $contrato->cliente->persona->nombreCompleto(), $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(165, $posicion);
            $pdf::Cell($w = 40, $h = 5.5, $contrato->codigo, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(205, $posicion);
            $pdf::Cell($w = 25, $h = 5.5, date('d/m/Y', strtotime($contrato->fecha_contrato)), $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(230, $posicion);
            $pdf::Cell($w = 25, $h = 5.5, date('d/m/Y', strtotime($contrato->fecha_pago)), $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $total_pago_bs = (float)$contrato->capital + (float)$contrato->interes + (float)$contrato->comision;
            if ($contrato->moneda_id == 2) {
                // convertir a bolivianos
                $total_pago_bs = (float)$valores_cambio->valor_bs * (float)$total_pago_bs->total_pago;
            }
            $pdf::SetXY(255, $posicion);
            $pdf::Cell($w = 35, $h = 5.5, \number_format($total_pago_bs, 2, '.', ''), $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $suma_total += $total_pago_bs;
            $posicion += 5.5;
            if ($posicion >= 190) {
                $posicion = 20;
                // $pdf->SetXY($x, $y);
                $pdf::AddPage();
            }
        }

        $suma_total = \number_format($suma_total, 2, '.', '');
        $pdf::SetFont('helvetica', 'B', 13);
        $pdf::SetXY(230, $posicion + 0.1);
        $pdf::Cell($w = 25, $h = 5.5, 'TOTAL', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(255, $posicion + 0.1);
        $pdf::Cell($w = 35, $h = 5.5, $suma_total, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Comprobate');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Comprobate.pdf');
    }

    public function contratos_cancelados_excel(Request $request)
    {
        $fecha_ini = date('Y-m-d', strtotime($request->fecha_ini));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
        $filtro = $request->filtro;

        $contratos_cancelados = Contrato::where('estado_pago', 'Credito cancelado')
            ->where('fecha_pago', $fecha_ini)->get();
        if ($filtro == 'fecha') {
            $contratos_cancelados = Contrato::where('estado_pago', 'Credito cancelado')
                ->whereBetween('fecha_pago', [$fecha_ini, $fecha_fin])->get();
        }
        $valores_cambio = CambioMoneda::first();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("PrendaSol")
            ->setLastModifiedBy('Administración')
            ->setTitle('Reporte de Contratos Cancelados')
            ->setSubject('Contratos cancelados')
            ->setDescription('Excel donde muestra los contratos cancelados')
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
        $sheet->setCellValue('B1', 'REPORTE DE CONTRATOS CANCELADOS');
        $sheet->mergeCells("B1:G1");  //COMBINAR CELDAS
        // ENCABEZADO
        $sheet->setCellValue('B2', 'Nº');
        $sheet->setCellValue('C2', 'CLIENTE');
        $sheet->setCellValue('D2', 'CÓDIGO CONTRATO');
        $sheet->setCellValue('E2', 'FECHA CONTRATO');
        $sheet->setCellValue('F2', 'FECHA PAGO');
        $sheet->setCellValue('G2', 'TOTAL CANCELADO (Bs.)');

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
        $sheet->getStyle('B2:G2')->applyFromArray($styleArray);

        // RECORRER LOS REGISTROS
        $nro_fila = 3;
        $cont = 1;
        $suma_total = 0;
        foreach ($contratos_cancelados as $contrato) {
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle('B' . $nro_fila . ':G' . $nro_fila)->applyFromArray($styleArray);

            $sheet->setCellValue('B' . $nro_fila, $cont++);
            $sheet->setCellValue('C' . $nro_fila, $contrato->cliente->persona->nombreCompleto());
            $sheet->setCellValue('D' . $nro_fila, $contrato->codigo);
            $sheet->setCellValue('E' . $nro_fila, date('d/m/Y', strtotime($contrato->fecha_contrato)));
            $sheet->setCellValue('F' . $nro_fila, date('d/m/Y', strtotime($contrato->fecha_pago)));

            $total_pago_bs = (float)$contrato->capital + (float)$contrato->interes + (float)$contrato->comision;
            if ($contrato->moneda_id == 2) {
                // convertir a bolivianos
                $total_pago_bs = (float)$valores_cambio->valor_bs * (float)$total_pago_bs->total_pago;
            }
            $sheet->setCellValue('G' . $nro_fila, \number_format($total_pago_bs, 2, '.', ''));
            $suma_total += $total_pago_bs;
            $nro_fila++;
        }
        $suma_total = \number_format($suma_total, 2, '.', ',');
        $sheet->setCellValue('F' . $nro_fila, 'TOTAL');
        $sheet->setCellValue('G' . $nro_fila, $suma_total);

        $sheet->getStyle('B' . $nro_fila . ':G' . $nro_fila)->applyFromArray($styleArray);

        // AJUSTAR EL ANCHO DE LAS CELDAS
        foreach (range('B', 'J') as $columnID) {
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

    public function contratos_vigentes()
    {
        $sucursales = Sucursal::where('estado_id', 1)->get();
        return view('contrato.contratos_vigentes', compact('sucursales'));
    }

    public function contratos_vigentes_pdf(Request $request)
    {
        $fecha = $request->fecha;
        $sucursal = $request->sucursal;
        $fecha_fin = $request->fecha_fin;

        $contratos = Contrato::where('estado_id', 1)
            ->where('estado_pago', '!=', 'Credito cancelado')
            ->get();

        if ($fecha != 'todos' && $sucursal != 'todos') {
            $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');
            $contratos = Contrato::where('estado_id', 1)
                ->where('estado_pago', '!=', 'Credito cancelado')
                ->where('sucursal_id', $sucursal)
                ->where('fecha_contrato', '<=', $fecha_fin)
                ->get();
        } else if ($fecha != 'todos' && $sucursal == 'todos') {
            $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');
            $contratos = Contrato::where('estado_id', 1)
                ->where('estado_pago', '!=', 'Credito cancelado')
                ->where('fecha_contrato', '<=', $fecha_fin)
                ->get();
        } else if ($fecha == 'todos' && $sucursal != 'todos') {
            $contratos = Contrato::where('estado_id', 1)
                ->where('estado_pago', '!=', 'Credito cancelado')
                ->where('sucursal_id', $sucursal)
                ->get();
        }

        $valores_cambio = CambioMoneda::first();

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setPrintHeader(false);
        $pdf::setPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 14);
        $pdf::AddPage('L', 'A4', false, false);
        $pdf::SetXY(100, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'REPORTE DE CONTRATOS VIGENTES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(110, 40);
        $pdf::Cell($w = 0, $h = 5.5, $txt = 'FECHA DE EMISIÓN: ' . Carbon::now('America/La_Paz')->format('Y-m-d'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 45);
        $pdf::MultiCell(15, 15, 'Nº', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(30, 45);
        $pdf::MultiCell(50, 15, 'SUCURSAL', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(80, 45);
        $pdf::MultiCell(30, 15, 'CÓDIGO DE CONTRATO', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(110, 45);
        $pdf::MultiCell(15, 15, 'PESO BRUTO', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(125, 45);
        $pdf::MultiCell(15, 15, 'PESO NETO', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(140, 45);
        $pdf::MultiCell(25, 15, 'VALOR DE TASACIÓN', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(165, 45);
        $pdf::MultiCell(25, 15, 'IMPORTE TOTAL', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(190, 45);
        $pdf::MultiCell(25, 15, 'IMPORTE AMORTIZADO', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(215, 45);
        $pdf::MultiCell(25, 15, 'SALDO', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(240, 45);
        $pdf::MultiCell(15, 15, 'DÍAS DE MORA', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(255, 45);
        $pdf::MultiCell(20, 15, 'FECHA DE INGRESO A PRENDA SOL', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(275, 45);
        $pdf::MultiCell(20, 15, 'INGRESO DE FECHA A CUSTODIA', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $posicion = 65.5;
        $suma_total = 0;
        $cont = 1;
        $pdf::SetFont('helvetica', 'N', 8);
        $h = 5.5;

        $suma_peso_bruto = 0;
        $suma_peso_neto = 0;
        $suma_tasacion = 0;
        $suma_importe = 0;
        $suma_amortizado = 0;
        $suma_saldo = 0;

        foreach ($contratos as $contrato) {
            $pdf::SetXY(15, $posicion);
            $pdf::Cell($w = 15, $h, $cont++, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(30, $posicion);
            $pdf::cell(50, $h, $contrato->sucural->nombre, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(80, $posicion);
            $pdf::cell(30, $h, $contrato->codigo, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(110, $posicion);
            $pdf::cell(15, $h, $contrato->peso_total, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $detallesContrato = ContratoDetalle::where('contrato_id', $contrato->id)->where('estado_id', 1)->get();
            $peso_neto = 0;
            foreach ($detallesContrato as $detalleContrato) {
                if ($detalleContrato->dies) {
                    $peso_neto += (float)$detalleContrato->dies;
                }
                if ($detalleContrato->catorce) {
                    $peso_neto += (float)$detalleContrato->catorce;
                }
                if ($detalleContrato->dieciocho) {
                    $peso_neto += (float)$detalleContrato->dieciocho;
                }
                if ($detalleContrato->veinticuatro) {
                    $peso_neto += (float)$detalleContrato->veinticuatro;
                }
            }

            $pdf::SetXY(125, $posicion);
            $pdf::cell(15, $h, $peso_neto, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(140, $posicion);
            $pdf::cell(25, $h, \number_format($contrato->total_capital, 2, '.', ''), $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pago_ini = Pagos::where('contrato_id', $contrato->id)->get()->first();
            $pago_amortizacion = Pagos::where('contrato_id', $contrato->id)
                ->where('estado', 'AMORTIZACIÓN')
                ->get()
                ->last();
            $amortización = 0;
            $pago_ultimo = Pagos::where('contrato_id', $contrato->id)
                ->get()
                ->last();
            if ($pago_amortizacion) {
                $amortización = $pago_ini['capital'] - $pago_amortizacion['capital'];
            }
            $saldo = $pago_ultimo['capital'] + $pago_ultimo['interes'] + $pago_ultimo['comision'] + $pago_ultimo['cuota_mora'];

            $pdf::SetXY(165, $posicion);
            $pdf::cell(25, $h, \number_format($pago_ini['capital'], 2, '.', ''), $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(190, $posicion);
            $pdf::cell(25, $h, \number_format($amortización, 2, '.', ''), $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(215, $posicion);
            $pdf::cell(25, $h, \number_format($saldo, 2, '.', ''), $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(240, $posicion);
            $pdf::cell(15, $h, $pago_ultimo['dias_atraso'], $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(255, $posicion);
            $pdf::cell(20, $h, $contrato->fecha_contrato, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(275, $posicion);
            $pdf::cell(20, $h, $contrato->fecha_contrato, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $suma_peso_bruto += $contrato->peso_total;
            $suma_peso_neto += $peso_neto;
            $suma_tasacion += (float)\number_format($contrato->total_capital, 2, '.', '');
            $suma_importe += (float)\number_format($pago_ini['capital'], 2, '.', '');
            $suma_amortizado += (float)\number_format($amortización, 2, '.', '');
            $suma_saldo += (float)\number_format($saldo, 2, '.', '');

            $posicion += 5.5;
            if ($posicion >= 190) {
                $posicion = 20;
                // $pdf->SetXY($x, $y);
                $pdf::AddPage();
            }
        }
        $pdf::SetFont('helvetica', 'B', 8.5);
        $pdf::SetXY(15, $posicion);
        $pdf::Cell($w = 95, $h, 'TOTALES', $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(110, $posicion);
        $pdf::cell(15, $h, $suma_peso_bruto, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(125, $posicion);
        $pdf::cell(15, $h, $suma_peso_neto, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(140, $posicion);
        $pdf::cell(25, $h, $suma_tasacion, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(165, $posicion);
        $pdf::cell(25, $h, $suma_importe, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(190, $posicion);
        $pdf::cell(25, $h, $suma_amortizado, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(215, $posicion);
        $pdf::cell(25, $h, $suma_saldo, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Comprobate');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Comprobate.pdf');
    }

    public function resumen_prestamos()
    {
        $array_meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];

        $array_anios = [];

        $anio1 = Contrato::where('sucursal_id', session::get('ID_SUCURSAL'))
            ->min('gestion');

        $anio2 = Contrato::where('sucursal_id', session::get('ID_SUCURSAL'))
            ->max('gestion');

        for ($i = $anio1; $i <= $anio2; $i++) {
            $array_anios[] = $i;
        }

        return view('contrato.resumen_prestamos', compact('array_meses', 'array_anios'));
    }

    public function resumen_prestamos_pdf(Request $request)
    {
        $array_meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];
        $mes = $request->mes;
        $mes_aux = $mes;
        $nro_mes = (int)$mes;
        $mes_anterior = 0;
        $mes_anterior_aux = $mes_anterior;
        $nro_mes--;
        $anio = $request->anio;
        $mes_anterior = $anio . '-' . $nro_mes;
        $mes_anterior_aux = $nro_mes;
        if ($nro_mes < 1) {
            $nro_mes = '12';
            $mes_anterior = ((int)$anio - 1) . '-' . $nro_mes;
            $mes_anterior_aux = $nro_mes;
        } elseif ($nro_mes < 10) {
            $mes_anterior_aux = '0' . $nro_mes;
            $mes_anterior = $anio . '-0' . $nro_mes;
        }

        $mes = $anio . '-' . $mes;
        if ($mes < 10) {
            $mes = $anio . '-0' . $mes;
        }

        $total_dias = date('t', strtotime($mes . '-01'));
        $sucursales = Sucursal::where('estado_id', 1)->get();
        $array_contratos = [];
        $array_cajas = [];

        $array_totales = [0, 0, 0, 0];

        foreach ($sucursales as $sucursal) {
            $array_contratos[$sucursal->id] = [
                0 => [
                    'contratos_anterior' => '',
                    'importe_anterior' => 0.00,
                    'contratos_actual' => '',
                    'importe_actual' => 0.00,
                ],
                1 => [
                    'contratos_anterior' => '',
                    'importe_anterior' => 0.00,
                    'contratos_actual' => '',
                    'importe_actual' => 0.00,
                ]
            ];
            $array_cajas[$sucursal->id] = [];

            $id_sucursal = $sucursal->id;
            if ((int)$id_sucursal == 1) {
                $idCaja = [11, 12];
            }

            if ((int)$id_sucursal == 2) {
                $idCaja = [31, 32];
            }

            if ((int)$id_sucursal == 3) {
                $idCaja = [51, 52];
            }

            if ((int)$id_sucursal == 5) {
                $idCaja = [41, 42];
            }
            if ((int)$id_sucursal == 6) {
                $idCaja = [61, 62];
            }
            if ((int)$id_sucursal == 7) {
                $idCaja = [71, 72];
            }

            if ((int)$id_sucursal == 4) {
                $idCaja = [21, 22];
            }

            if ($id_sucursal == 8) {
                $idCaja = [81, 82];
            }

            if ($id_sucursal == 9) {
                $idCaja = [91, 92];
            }

            if ($id_sucursal == 10) {
                $idCaja = [101, 102];
            }

            if ($id_sucursal == 11) {
                $idCaja = [111, 112];
            }

            $array_cajas[$sucursal->id] = $idCaja;

            for ($i = 0; $i < count($idCaja); $i++) {
                $contratos_anterior = Pagos::where('sucursal_id', $sucursal->id)
                    ->where('caja', $idCaja[$i])
                    ->where('fecha_inio', 'LIKE', "$mes_anterior%")
                    ->where('estado', 'DESEMBOLSO')
                    ->get();

                $contratos_actual = Pagos::where('sucursal_id', $sucursal->id)
                    ->where('caja', $idCaja[$i])
                    ->where('fecha_inio', 'LIKE', "$mes%")
                    ->where('estado', 'DESEMBOLSO')
                    ->get();

                $importe_anterior = Pagos::where('sucursal_id', $sucursal->id)
                    ->where('caja', $idCaja[$i])
                    ->where('fecha_inio', 'LIKE', "$mes_anterior%")
                    ->where('estado', 'DESEMBOLSO')
                    ->sum('capital');

                $importe_actual = Pagos::where('sucursal_id', $sucursal->id)
                    ->where('caja', $idCaja[$i])
                    ->where('fecha_inio', 'LIKE', "$mes%")
                    ->where('estado', 'DESEMBOLSO')
                    ->sum('capital');

                $array_contratos[$sucursal->id][$i]['contratos_anterior'] = count($contratos_anterior);
                $array_totales[1] += (int)count($contratos_anterior);
                $array_contratos[$sucursal->id][$i]['contratos_actual'] = count($contratos_actual);
                $array_totales[3] += (int)count($contratos_actual);

                if ($importe_anterior > 0) {
                    $array_totales[0] += (float)$importe_anterior;
                    $array_contratos[$sucursal->id][$i]['importe_anterior'] = $importe_anterior;
                }

                if ($importe_actual > 0) {
                    $array_totales[2] += (float)$importe_actual;
                    $array_contratos[$sucursal->id][$i]['importe_actual'] = $importe_actual;
                }
            }
        }

        $valores_cambio = CambioMoneda::first();

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setPrintHeader(false);
        $pdf::setPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 14);
        $pdf::AddPage('L', 'A4', false, false);
        $pdf::SetXY(120, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'RESUMEN DE PRÉSTAMOS', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(130, 37);
        $pdf::Cell($w = 0, $h = 5.5, $txt = 'al ' . $total_dias . ' de ' . $array_meses[$mes_aux] . ' ' . $anio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(120, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = '(Expresado en Bolivianos)', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 45);
        $pdf::MultiCell(25, 15, 'Código Sucursal', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(40, 45);
        $pdf::MultiCell(85, 15, 'Nombre Sucursal', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(125, 45);
        $pdf::MultiCell(80, 7.5, $array_meses[$mes_anterior_aux], 1, 'C', 0, 0, '', '', true, 0, false, true, 7.5, 'M');
        $pdf::SetXY(125, 52.5);
        $pdf::MultiCell(40, 7.5, 'IMPORTE', 1, 'C', 0, 0, '', '', true, 0, false, true, 7.5, 'M');
        $pdf::SetXY(165, 52.5);
        $pdf::MultiCell(40, 7.5, 'CANTIDAD', 1, 'C', 0, 0, '', '', true, 0, false, true, 7.5, 'M');

        $pdf::SetXY(205, 45);
        $pdf::MultiCell(80, 7.5, $array_meses[$mes_aux], 1, 'C', 0, 0, '', '', true, 0, false, true, 7.5, 'M');
        $pdf::SetXY(205, 52.5);
        $pdf::MultiCell(40, 7.5, 'IMPORTE', 1, 'C', 0, 0, '', '', true, 0, false, true, 7.5, 'M');
        $pdf::SetXY(245, 52.5);
        $pdf::MultiCell(40, 7.5, 'CANTIDAD', 1, 'C', 0, 0, '', '', true, 0, false, true, 7.5, 'M');

        $posicion = 65.5;
        $suma_total = 0;
        $cont = 1;
        $pdf::SetFont('helvetica', 'N', 10);
        $h = 5.5;
        foreach ($sucursales as $sucursal) {
            for ($i = 0; $i < count($array_cajas[$sucursal->id]); $i++) {
                $pdf::SetXY(15, $posicion);
                $pdf::Cell(25, $h, $array_cajas[$sucursal->id][$i], 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

                $pdf::SetXY(40, $posicion);
                $pdf::Cell(85, $h, $sucursal->nombre, 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

                $pdf::SetXY(125, $posicion);
                $pdf::Cell(40, $h, $array_contratos[$sucursal->id][$i]['importe_anterior'], 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
                $pdf::SetXY(165, $posicion);
                $pdf::Cell(40, $h, $array_contratos[$sucursal->id][$i]['contratos_anterior'], 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

                $pdf::SetXY(205, $posicion);
                $pdf::Cell(40, $h, $array_contratos[$sucursal->id][$i]['importe_actual'], 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
                $pdf::SetXY(245, $posicion);
                $pdf::Cell(40, $h, $array_contratos[$sucursal->id][$i]['contratos_actual'], 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

                $posicion += 5.5;
                if ($posicion >= 190) {
                    $posicion = 20;
                    // $pdf->SetXY($x, $y);
                    $pdf::AddPage();
                }
            }
        }
        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, $posicion);
        $pdf::Cell(110, $h, 'TOTALES', 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(125, $posicion);
        $pdf::Cell(40, $h, $array_totales[0], 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(165, $posicion);
        $pdf::Cell(40, $h, $array_totales[1], 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(205, $posicion);
        $pdf::Cell(40, $h, $array_totales[2], 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(245, $posicion);
        $pdf::Cell(40, $h, $array_totales[3], 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Comprobate');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Comprobate.pdf');
    }

    public function resumen_ingresos()
    {
        $array_meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];

        $array_anios = [];

        $anio1 = Contrato::where('sucursal_id', session::get('ID_SUCURSAL'))
            ->min('gestion');

        $anio2 = Contrato::where('sucursal_id', session::get('ID_SUCURSAL'))
            ->max('gestion');

        for ($i = $anio1; $i <= $anio2; $i++) {
            $array_anios[] = $i;
        }
        return view('contrato.resumen_ingresos', compact('array_meses', 'array_anios'));
    }

    public function resumen_ingresos_pdf(Request $request)
    {
        $array_meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];
        $mes = $request->mes;
        $mes_aux = $mes;
        $nro_mes = (int)$mes;
        $mes_anterior = 0;
        $mes_anterior_aux = $mes_anterior;
        $nro_mes--;
        $anio = $request->anio;
        $mes_anterior = $anio . '-' . $nro_mes;
        $mes_anterior_aux = $nro_mes;
        if ($nro_mes < 1) {
            $nro_mes = '12';
            $mes_anterior = ((int)$anio - 1) . '-' . $nro_mes;
            $mes_anterior_aux = $nro_mes;
        } elseif ($nro_mes < 10) {
            $mes_anterior_aux = '0' . $nro_mes;
            $mes_anterior = $anio . '-0' . $nro_mes;
        }

        $mes = $anio . '-' . $mes;
        if ($mes < 10) {
            $mes = $anio . '-0' . $mes;
        }

        $total_dias = date('t', strtotime($mes . '-01'));
        $sucursales = Sucursal::where('estado_id', 1)->get();
        $array_contratos = [];
        $array_cajas = [];

        $array_totales = [0, 0, 0, 0];

        foreach ($sucursales as $sucursal) {
            $array_contratos[$sucursal->id] = [
                0 => [
                    'mes_anterior' => 0.00,
                    'mes_actual' => 0.00,
                ],
                1 => [
                    'mes_anterior' => 0.00,
                    'mes_actual' => 0.00,
                ]
            ];
            $array_cajas[$sucursal->id] = [];

            $id_sucursal = $sucursal->id;
            if ((int)$id_sucursal == 1) {
                $idCaja = [11, 12];
            }

            if ((int)$id_sucursal == 2) {
                $idCaja = [31, 32];
            }

            if ((int)$id_sucursal == 3) {
                $idCaja = [51, 52];
            }

            if ((int)$id_sucursal == 5) {
                $idCaja = [41, 42];
            }
            if ((int)$id_sucursal == 6) {
                $idCaja = [61, 62];
            }
            if ((int)$id_sucursal == 7) {
                $idCaja = [71, 72];
            }

            if ((int)$id_sucursal == 4) {
                $idCaja = [21, 22];
            }

            if ($id_sucursal == 8) {
                $idCaja = [81, 82];
            }

            if ($id_sucursal == 9) {
                $idCaja = [91, 92];
            }

            if ($id_sucursal == 10) {
                $idCaja = [101, 102];
            }

            if ($id_sucursal == 11) {
                $idCaja = [111, 112];
            }

            $array_cajas[$sucursal->id] = $idCaja;

            for ($i = 0; $i < count($idCaja); $i++) {
                $pagos_anterior_interes = Pagos::where('fecha_pago', 'LIKE', "$mes_anterior%")
                    ->where('caja', $idCaja[$i])
                    ->where('estado', '!=', 'DESEMBOLSO')
                    ->sum('interes');
                $pagos_anterior_comision = Pagos::where('fecha_pago', 'LIKE', "$mes_anterior%")
                    ->where('caja', $idCaja[$i])
                    ->where('estado', '!=', 'DESEMBOLSO')
                    ->sum('comision');
                $ingresos_anterior = (float)$pagos_anterior_interes + (float)$pagos_anterior_comision;

                // $ingresos_anterior = InicioFinCajaDetalle::where('fecha_pago', 'LIKE', "$mes_anterior%")
                //     ->where('caja', $idCaja[$i])
                //     ->where('ingreso_bs', '!=', NULL)
                //     ->sum('ingreso_bs');


                $ingresos_interes = Pagos::where('fecha_pago', 'LIKE', "$mes%")
                    ->where('caja', $idCaja[$i])
                    ->where('estado', '!=', 'DESEMBOLSO')
                    ->sum('interes');
                $ingresos_comision = Pagos::where('fecha_pago', 'LIKE', "$mes%")
                    ->where('caja', $idCaja[$i])
                    ->where('estado', '!=', 'DESEMBOLSO')
                    ->sum('comision');
                $ingresos = (float)$ingresos_interes + (float)$ingresos_comision;

                // $ingresos = InicioFinCajaDetalle::where('fecha_pago', 'LIKE', "$mes%")
                //     ->where('caja', $idCaja[$i])
                //     ->where('ingreso_bs', '!=', NULL)
                //     ->sum('ingreso_bs');

                $array_contratos[$sucursal->id][$i]['mes_anterior'] = (float)$ingresos_anterior;
                $array_contratos[$sucursal->id][$i]['mes_actual'] = (float)$ingresos;

                $array_totales[0] +=  (float)$array_contratos[$sucursal->id][$i]['mes_anterior'];
                $array_totales[1] +=  (float)$array_contratos[$sucursal->id][$i]['mes_actual'];
            }
        }

        $valores_cambio = CambioMoneda::first();

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setPrintHeader(false);
        $pdf::setPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);

        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::SetFont('helvetica', 'B', 14);
        $pdf::AddPage('L', 'A4', false, false);
        $pdf::SetXY(120, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'RESUMEN INGRESOS', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(80, 37);
        $pdf::Cell($w = 0, $h = 5.5, $txt = 'Por el Periodo comprendido entre el 1 de ' . $array_meses[$mes_aux] . ' al' . $total_dias . ' de ' . $array_meses[$mes_aux] . ' ' . $anio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(120, 44);
        $pdf::Cell($w = 0, $h = 0, $txt = '(Expresado en Bolivianos)', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 45);
        $pdf::MultiCell(25, 15, 'Código Sucursal', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(40, 45);
        $pdf::MultiCell(85, 15, 'Nombre Sucursal', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(125, 45);
        $pdf::MultiCell(50, 15, $array_meses[$mes_anterior_aux], 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(175, 45);
        $pdf::MultiCell(50, 15, $array_meses[$mes_aux], 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(225, 45);
        $pdf::MultiCell(30, 15, '%', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');
        $pdf::SetXY(255, 45);
        $pdf::MultiCell(30, 15, 'AH %', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $posicion = 65.5;
        $suma_total = 0;
        $cont = 1;
        $pdf::SetFont('helvetica', 'N', 10);
        $h = 5.5;
        $suma_porcentajes = 0;
        $suma_ah = 0;
        foreach ($sucursales as $sucursal) {
            for ($i = 0; $i < count($array_cajas[$sucursal->id]); $i++) {
                $pdf::SetXY(15, $posicion);
                $pdf::Cell(25, $h, $array_cajas[$sucursal->id][$i], 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

                $pdf::SetXY(40, $posicion);
                $pdf::Cell(85, $h, $sucursal->nombre, 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

                $pdf::SetXY(125, $posicion);
                $pdf::Cell(50, $h, round($array_contratos[$sucursal->id][$i]['mes_anterior'], 2), 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
                $pdf::SetXY(175, $posicion);
                $pdf::Cell(50, $h, round($array_contratos[$sucursal->id][$i]['mes_actual'], 2), 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

                $porcentaje = 0;
                if ($array_totales[1] > 0) {
                    $porcentaje = ((float)$array_contratos[$sucursal->id][$i]['mes_actual'] / $array_totales[1]) * 100;
                }
                $suma_porcentajes += round($porcentaje, 2);

                $ah = 0;
                if ((float)$array_contratos[$sucursal->id][$i]['mes_anterior'] == 0 && (float)$array_contratos[$sucursal->id][$i]['mes_actual'] > 0) {
                    $ah = 100;
                } else if ((float)$array_contratos[$sucursal->id][$i]['mes_anterior'] > 0 && (float)$array_contratos[$sucursal->id][$i]['mes_actual'] == 0) {
                    $ah = -100;
                } else if ((float)$array_contratos[$sucursal->id][$i]['mes_anterior'] == 0 && (float)$array_contratos[$sucursal->id][$i]['mes_actual'] == 0) {
                    $ah = 100;
                } else {
                    $ah = (((float)round($array_contratos[$sucursal->id][$i]['mes_actual'], 2) - (float)round($array_contratos[$sucursal->id][$i]['mes_anterior'], 2)) / (float)round($array_contratos[$sucursal->id][$i]['mes_anterior'], 2)) * 100;
                }
                $suma_ah += round($ah, 2);

                $pdf::SetXY(225, $posicion);
                $pdf::Cell(30, $h, round($porcentaje, 2), 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
                $pdf::SetXY(255, $posicion);
                $pdf::Cell(30, $h, round($ah, 2), 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

                $posicion += 5.5;
                if ($posicion >= 190) {
                    $posicion = 20;
                    // $pdf->SetXY($x, $y);
                    $pdf::AddPage();
                }
            }
        }
        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, $posicion);
        $pdf::Cell(110, $h, 'TOTALES', 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(125, $posicion);
        $pdf::Cell(50, $h, $array_totales[0], 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(175, $posicion);
        $pdf::Cell(50, $h, $array_totales[1], 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(225, $posicion);
        $pdf::Cell(30, $h, round($suma_porcentajes, 2), 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(255, $posicion);
        $pdf::Cell(30, $h, round($suma_ah, 2), 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Resumen de Ingresos');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Comprobate.pdf');
    }

    public function EnviaRenovacion(Contrato $contrato)
    {
        return \redirect('Contrato')->with('personaNroDoc', $contrato->cliente->persona->nrodocumento);
    }

    public function corrige_codigos()
    {
        $anio = date('Y');
        $contratos = Contrato::where('sucursal_id', 10)->orderBy('created_at', 'asc')->get();
        $inicio = 2001;
        foreach ($contratos as $registro) {
            if ($registro->caja == 101) {
                $registro->codigo = '16J1-21.' . $inicio;
            } else {
                $registro->codigo = '16J2-21.' . $inicio;
            }
            $inicio++;
            $registro->save();
        }
        return \redirect('/Inicio');
    }
}
