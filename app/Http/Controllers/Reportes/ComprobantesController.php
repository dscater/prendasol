<?php

namespace App\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\ContaDiario;
use App\Sucursal;
use App\Usuario;
use Carbon\Carbon;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

class ComprobantesController extends Controller
{
    private $fechaInicialGlobal;
    private $cajaGlobal;

    public function __construct()
    {
        $this->fechaInicialGlobal = "";
        $this->cajaGlobal = "";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Session::has('AUTENTICADO')) {
            $sucursales = Sucursal::where('estado_id', 1)->get();
            if (session::get('ID_ROL') == 1 || session::get('ID_ROL') == 3) {
                return view('reporteComprobante.index', compact('sucursales'));
            } else {
                return view("layout.login", compact('sucursales'));
            }
        } else {
            return view("layout.login", compact('sucursales'));
        }
    }

    public function imprmirComprobante($fechaI, $id_sucursal, $caja)
    {
        ini_set('max_execution_time', 3600);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        //$caja = $request['ddlCaja'];
        $this->fechaInicialGlobal = $fechaI;
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
        //dd($fechaI);
        $this->cajaGlobal = $idCaja;
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            //$fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            $datosContaDiario = ContaDiario::where('fecha_a', $fechaInicio)
                ->where('estado_id', 1)
                ->where('sucursal_id', $id_sucursal)
                ->where('caja', $idCaja)
                //->orderBy('fecha_a','ASC')
                ->orderBy('num_comprobante', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();
            //dd($datosContaDiario);
            $paperSize = 'LETTER';
            $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $paperSize, true, 'UTF-8', false);
            $pdf::setHeaderCallback(function ($pdf) {
                $now = Carbon::now('America/La_Paz');
                $pdf->SetFont('Helvetica', '', 7.5);
                $pdf->Cell($w = 0, $h = 30, $txt = $now, $border = 0, $ln = 0, $align = 'R', $fill = false);

                $pdf->SetFont('helvetica', 'B', 8);

                $pdf->SetXY(15, 20);
                $pdf->Cell(0, -15, 'PRENDASOL S.R.L.', 0, false, 'L', 0, '', 0, false, 'M', 'M');
                $pdf->SetXY(15, 25);
                $pdf->Cell(0, -15, 'DIARIO GENERAL DE MOVIMIENTOS - CAJA No. ' . $this->cajaGlobal, 0, false, 'L', 0, '', 0, false, 'M', 'M');
                $pdf->SetXY(15, 30);
                $pdf->Cell(0, -15, 'EN FECHA: ' . $this->fechaInicialGlobal, 0, false, 'L', 0, '', 0, false, 'M', 'M');
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
                // $pdf->Cell(0, 10, 'Pagina '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

                $pdf->SetY(-15);
                $pdf->Cell(0, 10, $usuario->persona->nombreCompleto(), 0, false, 'L', 0, '', 0, false, 'M', 'M');
            });

            $pdf::SetTitle('Reporte Libro Diario');

            $pdf::AddPage();
            $pdf::SetFont('Helvetica', '', 10);
            $pdf::Ln(30);

            $pdf::SetMargins(10, 40, 0);

            $view = \View::make('reporteContable.listadoLibroDiario')->with(['datosContaDiario' => $datosContaDiario, 'fechaI' => $fechaI, 'fechaF' => $fechaI]);
            $html_content =  $view->render();

            $pdf::writeHTML($html_content, true, false, true, false, '');

            $pdf::lastPage();

            $pdf::Output('reporteLibriDiario.pdf');
        }
    }
}
