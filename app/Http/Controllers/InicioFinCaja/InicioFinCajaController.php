<?php

namespace App\Http\Controllers\InicioFinCaja;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use App\Persona;
use App\Usuario;
use App\Cliente;
use App\Contrato;
use App\ContratoDetalle;
use App\SucursalUsuario;
use App\Sucursal;
use App\InicioFinCaja;
use App\InicioFinCajaDetalle;
use App\Pagos;
use App\ContaDiario;

use Carbon\Carbon;
use App\NumberToLetterConverter;
use Exception;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InicioFinCajaController extends Controller
{
    private $fechaCierreGlobal;
    private $cajaGlobal;
    private $usuarioGlobal;
    public function __construct()
    {
        $this->fechaCierreGlobal = "";
        $this->cajaGlobal = "";
        $this->usuarioGlobal = "";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            ///Carbon::setLocale('es');   
            $datoValidarCaja =  InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->whereIn('estado_id', [1, 2])
                ->orderBy('id', 'DESC')->first();
            //dd(Carbon::now()->format('Y-m-d'));
            $datosCaja = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->where('estado_id', 1)
                ->orderBy('id', 'DESC')->get();

            // $datosCaja = InicioFinCajaDetalle::where('sucursal_id', 6)
            //     ->where('caja', 62)
            //     ->where('fecha_pago', '2021-06-08')
            //     ->where('estado_id', 1)
            //     ->orderBy('id', 'DESC')->get();

            $datoInicioFinCaja =  InicioFinCaja::where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->whereIn('estado_id', [1, 2])
                ->first();
            if ($request->ajax()) {
                //return view('contrato.modals.listadoContrato', ['personas' => $personas])->render();  
            }
            //return view('contrato.index',compact('personas'));
            $lista_cierres =  InicioFinCaja::where('estado_id', 2)
                ->orderBy('created_at', 'DESC')->paginate(15);

            return view('inicioFinCaja.index', compact('datosCaja', 'datoValidarCaja', 'datoInicioFinCaja', 'lista_cierres'));
        } else {
            return view("layout.login");
        }
    }

    public function lista_cierres(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            ///Carbon::setLocale('es');   
            $lista_cierres =  InicioFinCaja::where('estado_id', 2)
                ->orderBy('created_at', 'DESC')->paginate(15);

            return view("inicioFinCaja.parcial.listaCierres", compact("lista_cierres"))->render();
        } else {
            return view("layout.login");
        }
    }

    public function saldos_caja()
    {
        return view('inicioFinCaja.saldos_caja');
    }

    public function get_saldos_caja(Request $request)
    {
        $fecha = $request->fecha;
        $fecha = Carbon::parse($fecha)->format('Y-m-d');

        $sucursales = Sucursal::where('estado_id', 1)->get();
        $array_saldos = [];
        $array_cajas = [];

        $array_totales = [0, 0, 0];

        foreach ($sucursales as $sucursal) {
            $array_saldos[$sucursal->id] = [
                0 => [
                    'usuario' => '',
                    'inicio' => 0.00,
                    'prestamo' => 0.00,
                    'saldo' => 0.00,
                ],
                1 => [
                    'usuario' => '',
                    'inicio' => 0.00,
                    'prestamo' => 0.00,
                    'saldo' => 0.00,
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

            // nuevas sucursales
            if ($id_sucursal == 9) {
                $idCaja = [91, 92];
            }

            if ($id_sucursal == 10) {
                $idCaja = [101, 102];
            }

            if ($id_sucursal == 11) {
                $idCaja = [111, 112];
            }

            if ($id_sucursal == 12) {
                $idCaja = [121, 122];
            }

            if ($id_sucursal == 13) {
                $idCaja = [131, 132];
            }

            if ($id_sucursal == 14) {
                $idCaja = [141, 142];
            }

            if ($id_sucursal == 15) {
                $idCaja = [151, 152];
            }

            if ($id_sucursal == 16) {
                $idCaja = [161, 162];
            }

            if ($id_sucursal == 17) {
                $idCaja = [171, 172];
            }

            if ($id_sucursal == 18) {
                $idCaja = [181, 182];
            }

            if ($id_sucursal == 19) {
                $idCaja = [191, 192];
            }

            if ($id_sucursal == 20) {
                $idCaja = [201, 202];
            }

            if ($id_sucursal == 21) {
                $idCaja = [211, 212];
            }

            if ($id_sucursal == 22) {
                $idCaja = [221, 222];
            }

            if ($id_sucursal == 23) {
                $idCaja = [231, 232];
            }

            if ($id_sucursal == 24) {
                $idCaja = [241, 242];
            }

            $array_cajas[$sucursal->id] = $idCaja;

            for ($i = 0; $i < count($idCaja); $i++) {
                $inicio_caja = InicioFinCaja::where('sucursal_id', $sucursal->id)
                    ->where('caja', $idCaja[$i])
                    ->where('fecha', $fecha)
                    ->get()
                    ->first();
                if ($inicio_caja) {
                    $array_saldos[$sucursal->id][$i]['usuario'] = $inicio_caja->usuario->usuario;
                    $array_totales[0] += (float)$inicio_caja->inicio_caja_bs;
                    $array_saldos[$sucursal->id][$i]['inicio'] = $inicio_caja->inicio_caja_bs;

                    $prestamos = InicioFinCajaDetalle::where('inicio_fin_caja_id', $inicio_caja->id)
                        ->where('egreso_bs', '!=', NULL)
                        ->sum('egreso_bs');
                    $ingresos = InicioFinCajaDetalle::where('inicio_fin_caja_id', $inicio_caja->id)
                        ->where('ingreso_bs', '!=', NULL)
                        ->sum('ingreso_bs');
                    $saldo = (float)$inicio_caja->inicio_caja_bs - (float)$prestamos + (float)$ingresos;
                    $array_totales[1] += (float)$prestamos;
                    $array_saldos[$sucursal->id][$i]['prestamo'] = $prestamos;
                    $array_totales[2] += (float)$saldo;
                    $array_saldos[$sucursal->id][$i]['saldo'] = $saldo;
                }
            }
        }

        $html = view('inicioFinCaja.parcial.saldos', compact('sucursales', 'array_saldos', 'array_cajas', 'fecha', 'array_totales'))->render();
        return response()->JSON([
            'sw' => true,
            'html' => $html
        ]);
    }

    public function rectificarPagosAmortizacion()
    {
        // TABLA INICIO FIN CAJA DETALLE
        $datosCaja = InicioFinCajaDetalle::where('fecha_pago', '<=', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('estado_id', 1)
            ->where('tipo_de_movimiento', 'LIKE', "%AMORTIZACIÓN%")
            ->orderBy('id', 'DESC')->get();

        foreach ($datosCaja as $datoCaja) {
            if ($datoCaja->pago_id != 0) {
                $penultimoPago = Pagos::where('id', '<', $datoCaja->pago_id)
                    ->where('contrato_id', $datoCaja['contrato_id'])
                    ->orderBy('id', 'asc')
                    ->get()
                    ->last();
                $suma_comision = (float) $penultimoPago['comision'] - (float) $datoCaja->pago['comision'];
            }
            $datoCaja->ingreso_bs = (float)$datoCaja->ingreso_bs + $suma_comision;
            $datoCaja->save();
        }

        // TABLA CONTA DIARIO
        $conta_diario = ContaDiario::where('fecha_a', '<=', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('estado_id', 1)
            ->where('glosa', 'LIKE', "%AMORTIZACIÓN%")
            ->get();

        foreach ($conta_diario as $conta) {
            if ($conta->pagos_id != 0) {
                $penultimoPago = Pagos::where('id', '<', $conta->pagos_id)
                    ->where('contrato_id', $conta['contrato_id'])
                    ->orderBy('id', 'asc')
                    ->get()
                    ->last();
                $suma_comision = (float) $penultimoPago['comision'] - (float) $conta->pago['comision'];
            }
            $conta->haber = (float)$conta->haber + $suma_comision;
            $conta->save();
        }

        return 'Pagos corregidos con éxito';
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
        DB::beginTransaction();
        try {
            // validar existencia de un cierre
            $existe_cierre = InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->where('estado_id', 2)
                ->orderBy('id', 'DESC')->first();

            if ($existe_cierre) {
                return response()->json(["Mensaje" => "2"]);
                // throw new Exception('No es posible eliminar el registro debido a que ya existe un cierre');
            }

            $datoCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->where('estado_id', 1)
                ->orderBy('id', 'DESC')->first();
            //dd($datoCajaDetalle);
            if ($datoCajaDetalle) {
                $inicioCajaBs = $datoCajaDetalle->inicio_caja_bs;
                $idInicioCaja = $datoCajaDetalle->inicio_fin_caja_id;

                $inicioCaja = InicioFinCaja::find($idInicioCaja);
                $inicioCaja->fecha_cierre                  = Carbon::now('America/La_Paz');
                $inicioCaja->fin_caja_bs                   = $inicioCajaBs;
                $inicioCaja->estado_id                       = 2;
                $inicioCaja->usuario_id                      = session::get('ID_USUARIO');
                $inicioCaja->save();

                $fecha_actual = Carbon::now('America/La_Paz')->format('Y-m-d');
                $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+ 1 days"));


                InicioFinCaja::create([
                    'sucursal_id'          => session::get('ID_SUCURSAL'),
                    'fecha'           => Carbon::parse($resFechaProximo)->format('Y-m-d'),
                    'inicio_caja_bs'       => $inicioCajaBs,
                    'caja'                 => session::get('CAJA'),
                    'tipo_de_movimiento'   => 'Inicio de caja realizado por',
                    'estado_id'            => 1,
                    'moneda_id'            => 1,
                    'usuario_id'           => session::get('ID_USUARIO')
                ]);
            } else {
                $datoCajaDetalle = InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
                    ->where('caja', session::get('CAJA'))
                    ->where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                    ->whereIn('estado_id', [1, 2])
                    ->orderBy('id', 'DESC')->first();

                $inicioCaja = InicioFinCaja::find($datoCajaDetalle->id);
                $inicioCaja->fecha_cierre                  = Carbon::now('America/La_Paz');
                $inicioCaja->fin_caja_bs                   = $datoCajaDetalle->inicio_caja_bs;
                $inicioCaja->estado_id                       = 2;
                $inicioCaja->usuario_id                    = session::get('ID_USUARIO');
                $inicioCaja->save();

                $fecha_actual = Carbon::now('America/La_Paz')->format('Y-m-d');
                $resFechaProximo = date("d-m-Y", strtotime($fecha_actual . "+ 1 days"));


                InicioFinCaja::create([
                    'sucursal_id'          => session::get('ID_SUCURSAL'),
                    'fecha'           => Carbon::parse($resFechaProximo)->format('Y-m-d'),
                    'inicio_caja_bs'       => $datoCajaDetalle->inicio_caja_bs,
                    'caja'                 => session::get('CAJA'),
                    'tipo_de_movimiento'   => 'Inicio de caja realizado por',
                    'estado_id'            => 1,
                    'moneda_id'            => 1,
                    'usuario_id'           => session::get('ID_USUARIO')
                ]);
            }
            DB::commit();

            return response()->json(["Mensaje" => "1"]);
        } catch (\Exception $e) {
            Log::debug($e);
            DB::rollBack();
            return response()->json(["Mensaje" => "0"]);
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
        $inicio_caja = InicioFinCaja::find($id);
        $inicio_caja->estado_id = 1;
        $inicio_caja->fecha_cierre = NULL;
        $inicio_caja->save();

        return response()->JSON([
            "sw" => true,
            "message" => "Registro eliminado exitosamente"
        ]);
    }

    public function imprimirInicioFinCaja()
    {
        $datoValidarCaja =  InicioFinCaja::where('sucursal_id', session::get('ID_SUCURSAL'))
            ->where('caja', session::get('CAJA'))
            ->where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->whereIn('estado_id', [1, 2])
            ->orderBy('id', 'DESC')->first();
        //dd($datoValidarCaja);
        //dd(Carbon::now()->format('Y-m-d'));
        $datosCajaDetalle = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
            ->where('caja', session::get('CAJA'))
            ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('estado_id', 1)
            ->orderBy('id', 'DESC')->get();
        if ($datoValidarCaja->fecha_cierre) {
            $this->fechaCierreGlobal = $datoValidarCaja->fecha_cierre;
            $this->cajaGlobal = $datoValidarCaja->caja;
        }

        $this->usuarioGlobal = $datoValidarCaja->usuario->persona->nombreCompleto();



        $totalInteres = DB::table("inicio_fin_caja_detalle")->where('sucursal_id', session::get('ID_SUCURSAL'))
            ->where('caja', session::get('CAJA'))
            ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('ref', 'IA01')
            ->where('estado_id', 1)
            ->sum('ingreso_bs');
        $contInteres = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
            ->where('caja', session::get('CAJA'))
            ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('ref', 'IA01')
            ->where('estado_id', 1)
            ->orderBy('id', 'DESC')->count();

        $totalAmortizacion = DB::table("inicio_fin_caja_detalle")->where('sucursal_id', session::get('ID_SUCURSAL'))
            ->where('caja', session::get('CAJA'))
            ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('ref', 'MA01')
            ->where('estado_id', 1)
            ->sum('ingreso_bs');
        $contAmortizacion = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
            ->where('caja', session::get('CAJA'))
            ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('ref', 'MA01')
            ->where('estado_id', 1)
            ->orderBy('id', 'DESC')->count();

        $totalPagoTotal = DB::table("inicio_fin_caja_detalle")->where('sucursal_id', session::get('ID_SUCURSAL'))
            ->where('caja', session::get('CAJA'))
            ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('ref', 'CA01')
            ->where('estado_id', 1)
            ->sum('ingreso_bs');
        $contPagoTotal = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
            ->where('caja', session::get('CAJA'))
            ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('ref', 'CA01')
            ->where('estado_id', 1)
            ->orderBy('id', 'DESC')->count();

        $totalDesembolso = DB::table("inicio_fin_caja_detalle")->where('sucursal_id', session::get('ID_SUCURSAL'))
            ->where('caja', session::get('CAJA'))
            ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('ref', 'DA01')
            ->where('estado_id', 1)
            ->sum('egreso_bs');
        $contDesembolso = InicioFinCajaDetalle::where('sucursal_id', session::get('ID_SUCURSAL'))
            ->where('caja', session::get('CAJA'))
            ->where('fecha_pago', Carbon::now('America/La_Paz')->format('Y-m-d'))
            ->where('ref', 'DA01')
            ->where('estado_id', 1)
            ->orderBy('id', 'DESC')->count();

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //$this->opcionGeneral = $opcionValor;


        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz');
            $pdf->SetFont('Helvetica', '', 7.5);
            $pdf->Cell($w = 0, $h = 30, $txt = $now, $border = 0, $ln = 0, $align = 'R', $fill = false);

            $pdf->SetFont('helvetica', 'B', 8);

            $pdf->SetXY(15, 20);
            $pdf->Cell(0, -15, 'PRENDASOL S.R.L.', 0, false, 'L', 0, '', 0, false, 'M', 'M');
            $pdf->SetXY(15, 25);
            $pdf->Cell(0, -15, 'CIERRE DE LA CAJA No.' . $this->cajaGlobal, 0, false, 'L', 0, '', 0, false, 'M', 'M');
            $pdf->SetXY(15, 30);
            $pdf->Cell(0, -15, 'EN FECHA ' . $this->fechaCierreGlobal, 0, false, 'L', 0, '', 0, false, 'M', 'M');

            $pdf->SetXY(15, 35);
            $pdf->Cell(0, -15, 'USUARIO CIERRE: ' . $this->usuarioGlobal, 0, false, 'L', 0, '', 0, false, 'M', 'M');
        });

        $pdf::setFooterCallback(function ($pdf) {
            $usuario = Usuario::where('id', session::get('ID_USUARIO'))->first();
            // Position at 15 mm from bottom
            $pdf->SetY(-15);
            // Set font
            $pdf->SetFont('helvetica', 'I', 5.6);
            // Page number
            $pdf->Cell(0, 10, 'Pagina ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

            $pdf->SetY(-15);
            // $pdf->Cell(0, 10, $usuario->persona->nombreCompleto(), 0, false, 'L', 0, '', 0, false, 'M', 'M'); 

        });
        //$pdf::Header();

        $posicion = 60;


        $pdf::SetTitle('Reporte Cierre de caja');

        $pdf::AddPage();
        $pdf::SetFont('Helvetica', '', 10);
        // $posicion = $posicion + 20;
        // $pdf::SetXY(15, $posicion);
        // $pdf::Cell(0, -15, 'PRENDASOL S.R.L.', 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $posicion = $posicion + 5;
        $pdf::SetXY(15, $posicion);
        $pdf::Cell(0, -15, 'CIERRE DE LA CAJA ' . $this->fechaCierreGlobal, 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $posicion = $posicion + 5;
        $pdf::SetXY(15, $posicion);
        $pdf::Cell(0, -15, 'EN FECHA ', 0, false, 'L', 0, '', 0, false, 'M', 'M');


        $posicion = $posicion + 5;
        $pdf::SetXY(40, $posicion);
        $pdf::Cell(0, -15, 'CONCEPTO ', 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $pdf::SetXY(100, $posicion);
        $pdf::Cell(0, -15, 'CASOS ', 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $pdf::SetXY(160, $posicion);
        $pdf::Cell(0, -15, 'MONTO ', 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $posicion = $posicion + 5;
        $pdf::SetXY(40, $posicion);
        $pdf::Cell(0, -15, 'INICIO ', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf::SetXY(100, $posicion);
        $pdf::Cell(0, -15, 1, 0, false, 'L', 0, '', 0, false, 'M', 'M');


        $posicion = $posicion + 5;
        $pdf::SetXY(40, $posicion);
        $pdf::Cell(0, -15, 'DESEMBOLSO ', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf::SetXY(100, $posicion);
        $pdf::Cell(0, -15, $contDesembolso, 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf::SetXY(160, $posicion);
        $pdf::Cell(0, -15, $totalDesembolso, 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $posicion = $posicion + 5;
        $pdf::SetXY(40, $posicion);
        $pdf::Cell(0, -15, 'PAGO TOTAL ', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf::SetXY(100, $posicion);
        $pdf::Cell(0, -15, $contPagoTotal, 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf::SetXY(160, $posicion);
        $pdf::Cell(0, -15, $totalPagoTotal, 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $posicion = $posicion + 5;
        $pdf::SetXY(40, $posicion);
        $pdf::Cell(0, -15, 'INTERES ', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf::SetXY(100, $posicion);
        $pdf::Cell(0, -15, $contInteres, 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf::SetXY(160, $posicion);
        $pdf::Cell(0, -15, $totalInteres, 0, false, 'L', 0, '', 0, false, 'M', 'M');


        $posicion = $posicion + 5;
        $pdf::SetXY(40, $posicion);
        $pdf::Cell(0, -15, 'AMORTIZACIÓN ', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf::SetXY(100, $posicion);
        $pdf::Cell(0, -15, $contAmortizacion, 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf::SetXY(160, $posicion);
        $pdf::Cell(0, -15, $totalAmortizacion, 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $posicion = $posicion + 5;
        $pdf::SetXY(40, $posicion);
        $pdf::Cell(0, -15, 'RETIRO ', 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $posicion = $posicion + 5;
        $pdf::SetXY(40, $posicion);
        $pdf::Cell(0, -15, 'REMATE ', 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $posicion = $posicion + 5;
        $pdf::SetXY(40, $posicion);
        $pdf::Cell(0, -15, 'TOTAL DEL DIA ', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        //$pdf::AddPage('L', 'A4');
        $pdf::AddPage();
        $pdf::SetFont('Helvetica', '', 6.6);
        $pdf::Ln(30);


        //$pdf::SetMargins(10,50, 40);
        $pdf::SetMargins(10, 40, 0);

        $html = '';
        $html .= '<table cellpadding="3" border="1">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th width="20" align="center"><strong>N</strong></th>';
        $html .= '<th width="50" align="center"><strong>Codigo</strong></th>';
        $html .= '<th width="80" align="center"><strong>Nombre Cliente</strong></th>';
        $html .= '<th width="30" align="center"><strong>Ref.</strong></th>';
        $html .= '<th width="50" align="center"><strong>Fecha Pago</strong></th>';
        $html .= '<th width="50" align="center"><strong>Inicio Caja</strong></th>';
        $html .= '<th width="50" align="center"><strong>Ingreso Bs</strong></th>';
        $html .= '<th width="50" align="center"><strong>Egreso Bs</strong></th>';
        $html .= '<th width="150" align="center"><strong>Tipo Movimiento</strong></th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td width="20" align="center"><strong></strong></td>';
        $html .= '<td width="50" align="center"><strong></strong></td>';
        $html .= '<td width="80" align="center"><strong></strong></td>';
        $html .= '<td width="30" align="center"><strong></strong></td>';
        $html .= '<td width="50" align="center"><strong></strong></td>';
        $html .= '<td width="50" align="center"><strong></strong></td>';
        $html .= '<td width="50" align="center"><strong>' . $datoValidarCaja->fecha_hora . '</strong></td>';
        $html .= '<td width="50" align="center"><strong>' . $datoValidarCaja->inicio_caja_bs . '</strong></td>';
        $html .= '<td width="150" align="center"><strong>SALDO INICIAL</strong></td>';
        $html .= '</tr>';

        $i = 0;
        foreach ($datosCajaDetalle as $key => $dato) {
            $i = $i + 1;
            if ($i % 2 == 0) {
                //$color ='#f2f2f2';
                $color = '#ffffff';
            } else {
                $color = '#ffffff';
            }

            $html .= '<tr nobr="true" bgcolor="' . $color . '">';
            $html .= '<td width="20" align="center">' . $i . '</td>';
            if ($dato->contrato_id != 0) {
                if ($dato->contrato->codigo != "") {
                    $html .= '<td width="50" align="center">' . $dato->contrato->codigo . '</td>';
                } else {
                    $gestion = substr($dato->contrato->gestion, 2, 2);
                    $rescodigo = $dato->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $dato->contrato->codigo_num;
                    $html .= '<td width="50" align="center">' . $rescodigo . '</td>';
                }
                $html .= '<td width="80" align="center">' . $dato->contrato->cliente->persona->nombreCompleto() . '</td>';
            } else {
                $html .= '<td width="50" align="center">' . $dato->correlativo . ' - ' . $dato->gestion . '</td>';
                $html .= '<td width="80" align="center">' . $dato->persona->nombreCompleto() . '</td>';
            }
            //$html.= '<td width="80" align="center">'. $dato->contrato->cliente->persona->nombreCompleto() .'</td>';
            $html .= '<td width="30" align="center">' . $dato->ref . '</td>';
            $html .= '<td width="50" align="center">' . $dato->created_at . '</td>';
            $html .= '<td width="50" align="center">' . $dato->inicio_caja_bs . '</td>';
            $html .= '<td width="50" align="center">' . $dato->ingreso_bs . '</td>';
            $html .= '<td width="50" align="center">' . $dato->egreso_bs . '</td>';
            $html .= '<td width="150">' . $dato->tipo_de_movimiento . '</td>';
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= '<td width="20" align="center"><strong></strong></td>';
        $html .= '<td width="50" align="center"><strong></strong></td>';
        $html .= '<td width="80" align="center"><strong></strong></td>';
        $html .= '<td width="30" align="center"><strong></strong></td>';
        $html .= '<td width="50" align="center"><strong></strong></td>';
        $html .= '<td width="50" align="center"><strong></strong></td>';
        $html .= '<td width="50" align="center"><strong>' . $datoValidarCaja->fecha_cierre . '</strong></td>';
        $html .= '<td width="50" align="center"><strong>' . $datoValidarCaja->fin_caja_bs . '</strong></td>';
        $html .= '<td width="150" align="center"><strong>CIERRE CAJA</strong></td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';


        //PDF::AddPage('L', 'A4');
        PDF::writeHTML($html, true, false, true, false, '');
        //PDF::Output('CierreCaja_'.Carbon::now('America/La_Paz')->format('dmY').'.pdf', 'D');
        PDF::Output('CierreCaja_' . Carbon::now('America/La_Paz')->format('dmY') . '.pdf', 'I');
    }
}
