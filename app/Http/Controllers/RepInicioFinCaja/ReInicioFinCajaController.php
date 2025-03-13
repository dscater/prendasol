<?php

namespace App\Http\Controllers\RepInicioFinCaja;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pagos;
use Session;
use Carbon\Carbon;
use PDF;
use App\Sucursal;
use App\Usuario;
use App\InicioFinCaja;
use App\InicioFinCajaDetalle;
use Illuminate\Support\Facades\DB;

class ReInicioFinCajaController extends Controller
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
            $sucursales = Sucursal::where('estado_id', 1)->get();
            if (session::get('ID_ROL') == 1 || session::get('ID_ROL') == 3) {
                if ($request->ajax()) {
                    //return view('formEgreso.modals.listadoEgreso', ['sucursales' => $sucursales,'datosContaDiario'=>$datosContaDiario,'cuentas'=>$cuentas])->render(); 
                }
                //return view('inicioFinCaja.index',compact('datosCaja','datoValidarCaja'));
                //return view('contabilidad.contaDiario.index',compact('datosContaDiario'));
                return view('repInicioFinCaja.index', compact('sucursales'));
            } else {
                return view("layout.login", compact('sucursales'));
            }
            //$datosContaDiario = ContaDiario::where('tcom','EGRESO1')->where('ref','T126')->get();


        } else {
            return view("layout.login");
        }
    }

    public function buscarReInicioFinCaja(Request $request)
    {
        $fechaC = $request['txtFechaInicio'];
        //dd($fechaC);
        $id_sucursal = $request['ddlSucursal'];
        //dd($id_sucursal);
        $caja = $request['ddlCaja'];
        if ((int)$id_sucursal == 1) {
            if ($caja == 1) {
                $idCaja = 11;
            } else {
                $idCaja = 12;
            }
        }

        if ((int)$id_sucursal == 2) {
            if ($caja == 1) {
                $idCaja = 31;
            } else {
                $idCaja = 32;
            }
        }

        if ((int)$id_sucursal == 3) {
            if ($caja == 1) {
                $idCaja = 51;
            } else {
                $idCaja = 52;
            }
        }

        if ((int)$id_sucursal == 4) {
            if ($caja == 1) {
                $idCaja = 21;
            } else {
                $idCaja = 22;
            }
        }

        if ((int)$id_sucursal == 5) {
            if ($caja == 1) {
                $idCaja = 41;
            } else {
                $idCaja = 42;
            }
        }
        if ((int)$id_sucursal == 6) {
            if ($caja == 1) {
                $idCaja = 61;
            } else {
                $idCaja = 62;
            }
        }
        if ((int)$id_sucursal == 7) {
            if ($caja == 1) {
                $idCaja = 71;
            } else {
                $idCaja = 72;
            }
        }
        if ((int)$id_sucursal == 8) {
            if ($caja == 1) {
                $idCaja = 81;
            } else {
                $idCaja = 82;
            }
        }
        if ((int)$id_sucursal == 9) {
            if ($caja == 1) {
                $idCaja = 91;
            } else {
                $idCaja = 92;
            }
        }
        if ((int)$id_sucursal == 10) {
            if ($caja == 1) {
                $idCaja = 101;
            } else {
                $idCaja = 102;
            }
        }
        if ((int)$id_sucursal == 11) {
            if ($caja == 1) {
                $idCaja = 111;
            } else {
                $idCaja = 112;
            }
        }
        if ((int)$id_sucursal == 12) {
            if ($caja == 1) {
                $idCaja = 121;
            } else {
                $idCaja = 122;
            }
        }
        if ((int)$id_sucursal == 13) {
            if ($caja == 1) {
                $idCaja = 131;
            } else {
                $idCaja = 132;
            }
        }
        if ((int)$id_sucursal == 14) {
            if ($caja == 1) {
                $idCaja = 141;
            } else {
                $idCaja = 142;
            }
        }
        if ((int)$id_sucursal == 15) {
            if ($caja == 1) {
                $idCaja = 151;
            } else {
                $idCaja = 152;
            }
        }
        if ((int)$id_sucursal == 16) {
            if ($caja == 1) {
                $idCaja = 161;
            } else {
                $idCaja = 162;
            }
        }
        if ((int)$id_sucursal == 17) {
            if ($caja == 1) {
                $idCaja = 171;
            } else {
                $idCaja = 172;
            }
        }
        if ((int)$id_sucursal == 18) {
            if ($caja == 1) {
                $idCaja = 181;
            } else {
                $idCaja = 182;
            }
        }
        if ((int)$id_sucursal == 19) {
            if ($caja == 1) {
                $idCaja = 191;
            } else {
                $idCaja = 192;
            }
        }
        if ((int)$id_sucursal == 20) {
            if ($caja == 1) {
                $idCaja = 201;
            } else {
                $idCaja = 202;
            }
        }
        if ((int)$id_sucursal == 21) {
            if ($caja == 1) {
                $idCaja = 211;
            } else {
                $idCaja = 212;
            }
        }
        if ((int)$id_sucursal == 22) {
            if ($caja == 1) {
                $idCaja = 221;
            } else {
                $idCaja = 222;
            }
        }
        if ((int)$id_sucursal == 23) {
            if ($caja == 1) {
                $idCaja = 231;
            } else {
                $idCaja = 232;
            }
        }
        if ((int)$id_sucursal == 24) {
            if ($caja == 1) {
                $idCaja = 241;
            } else {
                $idCaja = 242;
            }
        }
        if ((int)$id_sucursal == 25) {
            if ($caja == 1) {
                $idCaja = 251;
            } else {
                $idCaja = 252;
            }
        }
        if ((int)$id_sucursal == 26) {
            if ($caja == 1) {
                $idCaja = 261;
            } else {
                $idCaja = 262;
            }
        }
        if ((int)$id_sucursal == 27) {
            if ($caja == 1) {
                $idCaja = 271;
            } else {
                $idCaja = 272;
            }
        }
        //  dd($idCaja);
        if (Session::has('AUTENTICADO')) {
            $datoValidarCaja =  InicioFinCaja::where('sucursal_id', $id_sucursal)
                ->where('caja', $idCaja)
                ->where('fecha', Carbon::parse($fechaC)->format('Y-m-d'))
                ->whereIn('estado_id', [1, 2])
                ->orderBy('id', 'ASC')->first();
            //dd($datoValidarCaja);
            $datosCaja = InicioFinCajaDetalle::where('sucursal_id', $id_sucursal)
                ->where('caja', $idCaja)
                ->where('fecha_pago', Carbon::parse($fechaC)->format('Y-m-d'))
                ->where('estado_id', 1)
                ->orderBy('id', 'ASC')->get();

            //dd($datosCaja);
            if ($datosCaja) {
                if ($request->ajax()) {
                    //dd($actoVacunaciones);
                    return view('repInicioFinCaja.modals.listadoRepInicioFinCaja', ['datoValidarCaja' => $datoValidarCaja, 'datosCaja' => $datosCaja])->render();
                }
                return view('repInicioFinCaja.index', compact('datoValidarCaja', 'datosCaja'));
            }
        } else {
            return view("layout.login");
        }
    }

    public function imprimirReInicioFinCaja($fechaC, $id_sucursal, $caja)
    {
        if ((int)$id_sucursal == 1) {
            if ($caja == 1) {
                $idCaja = 11;
            } else {
                $idCaja = 12;
            }
        }

        if ((int)$id_sucursal == 2) {
            if ($caja == 1) {
                $idCaja = 31;
            } else {
                $idCaja = 32;
            }
        }

        if ((int)$id_sucursal == 3) {
            if ($caja == 1) {
                $idCaja = 51;
            } else {
                $idCaja = 52;
            }
        }

        if ((int)$id_sucursal == 5) {
            if ($caja == 1) {
                $idCaja = 41;
            } else {
                $idCaja = 42;
            }
        }
        if ((int)$id_sucursal == 6) {
            if ($caja == 1) {
                $idCaja = 61;
            } else {
                $idCaja = 62;
            }
        }
        if ((int)$id_sucursal == 7) {
            if ($caja == 1) {
                $idCaja = 71;
            } else {
                $idCaja = 72;
            }
        }

        if ((int)$id_sucursal == 4) {
            if ($caja == 1) {
                $idCaja = 21;
            } else {
                $idCaja = 22;
            }
        }

        if ((int)$id_sucursal == 8) {
            if ($caja == 1) {
                $idCaja = 81;
            } else {
                $idCaja = 82;
            }
        }
        if ((int)$id_sucursal == 9) {
            if ($caja == 1) {
                $idCaja = 91;
            } else {
                $idCaja = 92;
            }
        }
        if ((int)$id_sucursal == 10) {
            if ($caja == 1) {
                $idCaja = 101;
            } else {
                $idCaja = 102;
            }
        }

        if ((int)$id_sucursal == 11) {
            if ($caja == 1) {
                $idCaja = 111;
            } else {
                $idCaja = 112;
            }
        }

        if ((int)$id_sucursal == 12) {
            if ($caja == 1) {
                $idCaja = 121;
            } else {
                $idCaja = 122;
            }
        }

        if ((int)$id_sucursal == 13) {
            if ($caja == 1) {
                $idCaja = 131;
            } else {
                $idCaja = 132;
            }
        }

        if ((int)$id_sucursal == 14) {
            if ($caja == 1) {
                $idCaja = 141;
            } else {
                $idCaja = 142;
            }
        }

        if ((int)$id_sucursal == 15) {
            if ($caja == 1) {
                $idCaja = 151;
            } else {
                $idCaja = 152;
            }
        }

        if ((int)$id_sucursal == 16) {
            if ($caja == 1) {
                $idCaja = 161;
            } else {
                $idCaja = 162;
            }
        }

        if ((int)$id_sucursal == 17) {
            if ($caja == 1) {
                $idCaja = 171;
            } else {
                $idCaja = 172;
            }
        }

        if ((int)$id_sucursal == 18) {
            if ($caja == 1) {
                $idCaja = 181;
            } else {
                $idCaja = 182;
            }
        }

        if ((int)$id_sucursal == 19) {
            if ($caja == 1) {
                $idCaja = 191;
            } else {
                $idCaja = 192;
            }
        }

        if ((int)$id_sucursal == 20) {
            if ($caja == 1) {
                $idCaja = 201;
            } else {
                $idCaja = 202;
            }
        }

        if ((int)$id_sucursal == 21) {
            if ($caja == 1) {
                $idCaja = 211;
            } else {
                $idCaja = 212;
            }
        }

        if ((int)$id_sucursal == 22) {
            if ($caja == 1) {
                $idCaja = 221;
            } else {
                $idCaja = 222;
            }
        }

        if ((int)$id_sucursal == 23) {
            if ($caja == 1) {
                $idCaja = 231;
            } else {
                $idCaja = 232;
            }
        }

        if ((int)$id_sucursal == 24) {
            if ($caja == 1) {
                $idCaja = 241;
            } else {
                $idCaja = 242;
            }
        }

        if ((int)$id_sucursal == 25) {
            if ($caja == 1) {
                $idCaja = 251;
            } else {
                $idCaja = 252;
            }
        }

        if ((int)$id_sucursal == 26) {
            if ($caja == 1) {
                $idCaja = 261;
            } else {
                $idCaja = 262;
            }
        }

        if ((int)$id_sucursal == 27) {
            if ($caja == 1) {
                $idCaja = 271;
            } else {
                $idCaja = 272;
            }
        }

        $datoValidarCaja =  InicioFinCaja::where('sucursal_id', $id_sucursal)
            ->where('caja', $idCaja)
            ->where('fecha', Carbon::parse($fechaC)->format('Y-m-d'))
            ->whereIn('estado_id', [1, 2])
            ->orderBy('id', 'ASC')->first();
        //dd($datoValidarCaja);
        $datosCaja = InicioFinCajaDetalle::where('sucursal_id', $id_sucursal)
            ->where('caja', $idCaja)
            ->where('fecha_pago', Carbon::parse($fechaC)->format('Y-m-d'))
            ->where('estado_id', 1)
            ->orderBy('id', 'ASC')->get();
        if ($datoValidarCaja->fecha_cierre) {
            $this->fechaCierreGlobal = $datoValidarCaja->fecha_cierre;
            $this->cajaGlobal = $datoValidarCaja->caja;
        }

        $this->usuarioGlobal = $datoValidarCaja->usuario->persona->nombreCompleto();

        $totalInteres = DB::table("inicio_fin_caja_detalle")->where('sucursal_id', $id_sucursal)
            ->where('caja', $idCaja)
            ->where('fecha_pago', Carbon::parse($fechaC)->format('Y-m-d'))
            ->where('ref', 'IA01')
            ->where('estado_id', 1)
            ->sum('ingreso_bs');
        $contInteres = InicioFinCajaDetalle::where('sucursal_id', $id_sucursal)
            ->where('caja', $idCaja)
            ->where('fecha_pago', Carbon::parse($fechaC)->format('Y-m-d'))
            ->where('ref', 'IA01')
            ->where('estado_id', 1)
            ->orderBy('id', 'DESC')->count();

        $totalAmortizacion = DB::table("inicio_fin_caja_detalle")->where('sucursal_id', $id_sucursal)
            ->where('caja', $idCaja)
            ->where('fecha_pago', Carbon::parse($fechaC)->format('Y-m-d'))
            ->where('ref', 'MA01')
            ->where('estado_id', 1)
            ->sum('ingreso_bs');
        $contAmortizacion = InicioFinCajaDetalle::where('sucursal_id', $id_sucursal)
            ->where('caja', $idCaja)
            ->where('fecha_pago', Carbon::parse($fechaC)->format('Y-m-d'))
            ->where('ref', 'MA01')
            ->where('estado_id', 1)
            ->orderBy('id', 'DESC')->count();

        $totalPagoTotal = DB::table("inicio_fin_caja_detalle")->where('sucursal_id', $id_sucursal)
            ->where('caja', $idCaja)
            ->where('fecha_pago', Carbon::parse($fechaC)->format('Y-m-d'))
            ->where('ref', 'CA01')
            ->where('estado_id', 1)
            ->sum('ingreso_bs');
        $contPagoTotal = InicioFinCajaDetalle::where('sucursal_id', $id_sucursal)
            ->where('caja', $idCaja)
            ->where('fecha_pago', Carbon::parse($fechaC)->format('Y-m-d'))
            ->where('ref', 'CA01')
            ->where('estado_id', 1)
            ->orderBy('id', 'DESC')->count();

        $totalDesembolso = DB::table("inicio_fin_caja_detalle")->where('sucursal_id', $id_sucursal)
            ->where('caja', $idCaja)
            ->where('fecha_pago', Carbon::parse($fechaC)->format('Y-m-d'))
            ->where('ref', 'DA01')
            ->where('estado_id', 1)
            ->sum('egreso_bs');
        $contDesembolso = InicioFinCajaDetalle::where('sucursal_id', $id_sucursal)
            ->where('caja', $idCaja)
            ->where('fecha_pago', Carbon::parse($fechaC)->format('Y-m-d'))
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
            $pdf->Cell(0, -15, 'EN FECHA:' . $this->fechaCierreGlobal, 0, false, 'L', 0, '', 0, false, 'M', 'M');

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

            $pdf->SetFont('helvetica', 'I', 8);
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
        //dd($datosCaja);
        foreach ($datosCaja as $key => $dato) {
            $i = $i + 1;
            if ($i % 2 == 0) {
                $color = '#f2f2f2';
            } else {
                $color = '#ffffff';
            }

            $html .= '<tr nobr="true" bgcolor="' . $color . '">';
            $html .= '<td width="20" align="center">' . $i . '</td>';
            if ($dato->contrato_id != 0) {
                if ($dato->contrato->codigo != "") {
                    $html .= '<td width="50" align="center">' . $dato->contrato->codigo . '</td>';
                } else {
                    $rescodigo = $dato->contrato->sucural->nuevo_codigo . '' . Carbon::parse($dato->contrato->fecha_contrato)->format('y') . '' . $dato->contrato->codigo_num;
                    $html .= '<td width="50" align="center">' . $rescodigo . '</td>';
                }
                $html .= '<td width="80" align="center">' . $dato->contrato->cliente->persona->nombreCompleto() . '</td>';
            } else {
                $html .= '<td width="50" align="center"></td>';
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
            //}
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



        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::lastPage();

        $pdf::Output('cierreCaja.pdf');
    }
}
