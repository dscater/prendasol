<?php

namespace App\Http\Controllers\IngresoCaja;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use Carbon\Carbon;
use PDF;
use App\Sucursal;
use App\Persona;
use App\InicioFinCaja;
use App\InicioFinCajaDetalle;
use App\ContaDiario;
use App\NumberToLetterConverter;

class IngresoCajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $datosContaDiario = ContaDiario::where('tcom', 'INGRESO')->where('ref', 'T037')->get();
            $sucursales = Sucursal::where('estado_id', 1)->get();
            $fechaActual = Carbon::now('America/La_Paz')->format('d-m-Y');
            if (session::get('ID_ROL') == 1 || session::get('ID_ROL') == 3) {
                if ($request->ajax()) {
                    return view('formIngreso.modals.listadoIngreso', ['sucursales' => $sucursales, 'datosContaDiario' => $datosContaDiario, 'fechaActual' => $fechaActual])->render();
                }

                $datoValidarCaja =  InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                    ->whereIn('estado_id', [1, 2])
                    ->orderBy('id', 'DESC')->first();

                //return view('inicioFinCaja.index',compact('datosCaja','datoValidarCaja'));
                $resFechaProximo = date("d-m-Y", strtotime($fechaActual . "+ 1 days"));
                return view('formIngreso.index', compact('sucursales', 'datosContaDiario', 'fechaActual', 'resFechaProximo', 'datoValidarCaja'));
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
                    /*VERIFICAMOS SI EXISTE PERSONA*/
                    $persona = Persona::where('nrodocumento', $request['txtCI'])->first();
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

                    /*INSERTAR INICIO FIN CAJA*/
                    $datoInicioCaja = InicioFinCaja::where('sucursal_id', $request['ddlSucursal'])
                        ->where('caja', $idCaja)
                        ->where('fecha', Carbon::parse($request['txtFecha'])->format('Y-m-d'))
                        ->whereIn('estado_id', [1, 2])
                        ->first();
                    // dd($datoInicioCaja);

                    $contadorInicioCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', $request['ddlSucursal'])
                        ->where('caja', $idCaja)
                        ->where('fecha_pago', Carbon::parse($request['txtFecha'])->format('Y-m-d'))
                        ->where('estado_id', 1)->count();
                    //dd($contadorInicioCajaDetalle);                    


                    if ($contadorInicioCajaDetalle == 0) {
                        $inicioCajaBs = $datoInicioCaja->inicio_caja_bs;
                        $idInicioCaja = $datoInicioCaja->id;
                    } else {
                        $datoCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', $request['ddlSucursal'])->where('caja', $idCaja)
                            ->where('fecha_pago', Carbon::parse($request['txtFecha'])->format('Y-m-d'))
                            ->where('estado_id', 1)->orderBy('id', 'DESC')->first();
                        $inicioCajaBs = $datoCajaDetalle->inicio_caja_bs;
                        $idInicioCaja = $datoCajaDetalle->inicio_fin_caja_id;
                    }

                    $totalPagar = $request['txtMonto'];

                    $resulInicioCaja = (float)$inicioCajaBs + (float)$totalPagar;

                    //$datoContrato = Contrato::where('id',$request['idContrato'])->first();
                    //dd(round($resulInicioCaja, 2)); 

                    $gestion = Carbon::now()->format('Y');
                    // dd($gestion);

                    $correlativoDato = InicioFinCajaDetalle::where('gestion', $gestion)->where('ref', 'T037')->max('correlativo');
                    // dd($correlativoDato);
                    if ($correlativoDato) {
                        $correlativo = (int) $correlativoDato + 1;
                    } else {
                        $correlativo =  1;
                    }
                    // dd($correlativo);

                    $idInicioCaja = InicioFinCajaDetalle::create([
                        'inicio_fin_caja_id'    => $idInicioCaja,
                        'contrato_id'           => 0,
                        'pago_id'               => 0,
                        'sucursal_id'           => $request['ddlSucursal'],
                        'persona_id'            => $persona->id,
                        'fecha_pago'            => Carbon::parse($request['txtFecha'])->format('Y-m-d'),
                        'fecha_hora'            => Carbon::now('America/La_Paz'),
                        'inicio_caja_bs'        => round($resulInicioCaja, 2),
                        'ingreso_bs'            => round($totalPagar, 2),
                        // 'tipo_de_movimiento'    => 'PAGO TOTAL AL N° '. $datoContrato->codigo .' DEL  SR.(A) '. $datoContrato->cliente->persona->nombreCompleto() .' EN LA CAJA '. session::get('CAJA') .'.' ,
                        'tipo_de_movimiento'    => $request['txtGlosa'],
                        'ref'               => 'T037',
                        'caja'              => $idCaja,
                        'correlativo'       => $correlativo,
                        'gestion'           => $gestion,
                        'usuario_id'        => session::get('ID_USUARIO'),
                        'estado_id'         => 1,
                        'moneda_id'         => 1
                    ])->id;

                    /*REGISTRAR PARTE CONTABLE*/
                    $ddlTipoMovimiento = $request["ddlTipoMovimiento"];
                    $cuenta1 = "Caja sucursales";
                    $cod_deno1 = "11102";
                    $cuenta2 = "Caja general";
                    $cod_deno2 = "11101";
                    if ($ddlTipoMovimiento == 3) {
                        $cuenta1 = "Traspaso de caja";
                        $cod_deno1 = "11103";
                    }
                    if ($ddlTipoMovimiento == 4) {
                        $cuenta1 = "Otros ingresos adicionales";
                        $cod_deno1 = "11104";
                    }

                    $numComprobante = ContaDiario::max('num_comprobante');
                    $idContaDiario = ContaDiario::create([
                        'contrato_id'        => 0,
                        'pagos_id'           => 0,
                        'sucursal_id'        => $request['ddlSucursal'],
                        'fecha_a'            => Carbon::parse($request['txtFecha'])->format('Y-m-d'),
                        'fecha_b'            => Carbon::parse($request['txtFecha'])->format('Y-m-d'),
                        'glosa'              => $request['txtGlosa'],
                        'cod_deno'              => $cod_deno1,
                        'cuenta'                => $cuenta1,
                        'debe'                  => round($request['txtMonto'], 2),
                        'haber'                 => '0.00',
                        'caja'                  => $idCaja,
                        'num_comprobante'       => $numComprobante + 1,
                        'periodo'               => 'mes',
                        'tcom'                  => 'INGRESO',
                        'ref'                   => 'T037',
                        'correlativo'       => $correlativo,
                        'gestion'           => $gestion,
                        'usuario_id'            => session::get('ID_USUARIO'),
                        'estado_id'             => 1
                    ])->id;


                    ContaDiario::create([
                        'contrato_id'        => 0,
                        'pagos_id'           => 0,
                        'sucursal_id'        => $request['ddlSucursal'],
                        'fecha_a'            => Carbon::parse($request['txtFecha'])->format('Y-m-d'),
                        'fecha_b'            => Carbon::parse($request['txtFecha'])->format('Y-m-d'),
                        'glosa'              => $request['txtGlosa'],
                        'cod_deno'              => $cod_deno2,
                        'cuenta'                => $cuenta2,
                        'debe'                  => '0.00',
                        'haber'                 => round($request['txtMonto'], 2),
                        'caja'                  => $idCaja,
                        'num_comprobante'       => $numComprobante + 1,
                        'periodo'               => 'mes',
                        'tcom'                  => 'INGRESO',
                        'ref'                   => 'T037',
                        'correlativo'       => $correlativo,
                        'gestion'           => $gestion,
                        'usuario_id'            => session::get('ID_USUARIO'),
                        'estado_id'             => 1
                    ]);

                    return response()->json(["Mensaje" => "1", "idContaDiario" => $idContaDiario, "idInicioCaja" => $idInicioCaja]);

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

    public function imprimirReporteIngreso($idContaDiario, $idInicioCaja)
    {
        $InicioFinCajaDetalle = InicioFinCajaDetalle::where('id', $idInicioCaja)->first();
        //dd($InicioFinCajaDetalle);
        $contaDiario = ContaDiario::where('id', $idContaDiario)->first();
        //dd($contaDiario);

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

        $pdf::SetXY(80, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = "AGENCIA " . $InicioFinCajaDetalle->sucursal->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(90, 34);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        // $pdf::SetXY(230, 35);
        // $pdf::Cell($w=0, $h=0, $txt=$InicioFinCajaDetalle->correlativo .' - '. $InicioFinCajaDetalle->gestion, $border=0, $ln=50, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='B', $valign='B');



        $pdf::SetXY(70, 38);
        $pdf::Cell($w = 0, $h = 0, $txt = 'DEPOSITO DE FONDOS A CAJA ' . $InicioFinCajaDetalle->caja, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(25, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = $InicioFinCajaDetalle->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(30, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = $InicioFinCajaDetalle->persona->domicilio, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(125, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FECHA/HORA: ' . $contaDiario->created_at, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(125, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'Bs.: ' . $contaDiario->debe, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $nl = new NumberToLetterConverter();
        $pdf::SetXY(65, 56);
        $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($contaDiario->debe), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $totalR = $contaDiario->debe;

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 62);
        $pdf::Cell($w = 0, $h = 0, $txt = 'DEPOSITADO POR:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(15, 66);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(15, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 'POR:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(60, 62);
        $pdf::Cell($w = 0, $h = 0, $txt = $InicioFinCajaDetalle->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(50, 66);
        $pdf::Cell($w = 0, $h = 0, $txt = $InicioFinCajaDetalle->persona->nrodocumento, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(50, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITALIZACIÓN (INCREMENTO DE CAPITAL)', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(15, 74);
        $pdf::Cell($w = 0, $h = 0, $txt = 'POR CONCEPTO DE: ' . $contaDiario->glosa, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 14);

        $pdf::SetXY(10, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $contaDiario->correlativo . ' - ' . $contaDiario->gestion, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetFont('helvetica', 'B', 11);


        $pdf::SetXY(120, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = "FIRMA", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(120, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = $InicioFinCajaDetalle->persona->nombreCompleto(), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $generarCodigo = "REIMPRESION INGRESO-MARIO ROJAS YUCRA-2773500-" . $totalR . "-" . $contaDiario->created_at . "-" . $contaDiario->glosa;
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 170, 64, 22, 22, $style, 'N');


        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Ingreso Caja');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('IngresoCaja.pdf');
    }

    public function reImprimirReporteIngreso($id)
    {
        //$InicioFinCajaDetalle = InicioFinCajaDetalle::where('id',$idInicioCaja)->first();
        //dd($InicioFinCajaDetalle);
        $contaDiario = ContaDiario::where('id', $id)->first();
        //dd($contaDiario);

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

        $pdf::SetXY(80, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = "AGENCIA " . $contaDiario->sucural->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(90, 34);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAJA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(60, 38);
        $pdf::Cell($w = 0, $h = 0, $txt = 'DEPOSITO DE FONDOS A CAJA ' . $contaDiario->caja . ' - REIMPRESION', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(25, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'MARIO ROJAS YUCRA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(30, 52);
        $pdf::Cell($w = 0, $h = 0, $txt = 'AV. LITORAL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(125, 48);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FECHA/HORA: ' . $contaDiario->created_at, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(125, 52);
        if ($contaDiario->debe != '0.00') {
            $pdf::Cell($w = 0, $h = 0, $txt = 'Bs.: ' . $contaDiario->debe, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $nl = new NumberToLetterConverter();
            $pdf::SetXY(65, 56);
            $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($contaDiario->debe), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $totalR = $contaDiario->debe;
        } else {
            $pdf::Cell($w = 0, $h = 0, $txt = 'Bs.: ' . $contaDiario->haber, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $nl = new NumberToLetterConverter();
            $pdf::SetXY(65, 56);
            $pdf::Cell($w = 0, $h = 0, $txt = $nl->numtoletras($contaDiario->haber), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $totalR = $contaDiario->haber;
        }

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 62);
        $pdf::Cell($w = 0, $h = 0, $txt = 'DEPOSITADO POR:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(15, 66);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CI:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(15, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 'POR:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(60, 62);
        $pdf::Cell($w = 0, $h = 0, $txt = 'MARIO ROJAS YUCRA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(50, 66);
        $pdf::Cell($w = 0, $h = 0, $txt = '2773500', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(50, 70);
        $pdf::Cell($w = 0, $h = 0, $txt = 'CAPITALIZACIÓN (INCREMENTO DE CAPITAL)', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(15, 74);
        $pdf::Cell($w = 0, $h = 0, $txt = 'POR CONCEPTO DE: ' . $contaDiario->glosa, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 14);

        $pdf::SetXY(10, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = $contaDiario->correlativo . ' - ' . $contaDiario->gestion, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetFont('helvetica', 'B', 11);

        $pdf::SetXY(120, 99);
        $pdf::Cell($w = 0, $h = 0, $txt = "FIRMA", $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(120, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = 'MARIO ROJAS YUCRA', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(35, 104);
        $pdf::Cell($w = 0, $h = 0, $txt = 'FIRMA Y SELLO', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $generarCodigo = "REIMPRESION INGRESO-MARIO ROJAS YUCRA-2773500-" . $totalR . "-" . $contaDiario->created_at . "-" . $contaDiario->glosa;
        $pdf::write2DBarcode($generarCodigo, 'QRCODE,H', 170, 64, 22, 22, $style, 'N');


        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Ingreso Caja');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('IngresoCaja.pdf');
    }
}
