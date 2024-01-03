<?php

namespace App\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\ContaDiario;
use App\ContaDeno;
use App\ContaDenominacion;
use App\Usuario;
use Carbon\Carbon;
use App\Sucursal;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;


class ReportesContablesController extends Controller
{
    private $fechaInicialGlobal;
    private $fechaFinalGlobal;

    public function __construct()
    {
        $this->fechaInicialGlobal = "";
        $this->fechaFinalGlobal = "";
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
                return view('reporteContable.index', compact('sucursales'));
            } else {
                return view("layout.login", compact('sucursales'));
            }
        } else {
            return view("layout.login", compact('sucursales'));
        }
    }

    public function imprmirLibroDiario($fechaI, $fechaF, Request $request)
    {
        ini_set('max_execution_time', 100000);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        //dd($fechaI);
        if (Session::has('AUTENTICADO')) {
            $caja = $request->caja;
            $sucursal = Sucursal::find($request->sucursal);
            $id_sucursal = $sucursal->id;
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

            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            $datosContaDiario = ContaDiario::select('conta_diario.*')
                ->join('pagos', 'pagos.id', '=', 'conta_diario.pagos_id')
                ->whereBetween('conta_diario.fecha_a', [$fechaInicio, $fechaFinal])
                ->where('conta_diario.estado_id', 1)
                ->where('conta_diario.sucursal_id', $sucursal->id)
                ->where('pagos.caja', $idCaja)
                ->orderBy('conta_diario.num_comprobante', 'ASC')
                ->get();
            //dd($datosContaDiario);
            $paperSize = 'LETTER';
            $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $paperSize, true, 'UTF-8', false);
            $pdf::setHeaderCallback(function ($pdf) use ($request) {
                $sucursal = Sucursal::find($request->sucursal);

                $now = Carbon::now('America/La_Paz');
                $pdf->SetFont('Helvetica', '', 7.5);
                $pdf->Cell($w = 0, $h = 30, $txt = $now, $border = 0, $ln = 0, $align = 'R', $fill = false);

                $pdf->SetFont('helvetica', 'B', 8);

                $pdf->SetXY(15, 20);
                $pdf->Cell(0, -15, 'PRENDASOL S.R.L.', 0, false, 'L', 0, '', 0, false, 'M', 'M');
                $pdf->SetXY(15, 25);
                $pdf->Cell(0, -15, 'DIARIO GENERAL DE MOVIMIENTOS', 0, false, 'L', 0, '', 0, false, 'M', 'M');
                $pdf->SetXY(15, 30);
                $pdf->Cell(0, -15, 'SUCURSAL ' . $sucursal->nombre . ' - CAJA ' . $request->caja, 0, false, 'L', 0, '', 0, false, 'M', 'M');
                $pdf->SetXY(15, 35);
                $pdf->Cell(0, -15, 'EN FECHA ', 0, false, 'L', 0, '', 0, false, 'M', 'M');
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


            $view = \View::make('reporteContable.listadoLibroDiario')->with([
                'datosContaDiario' => $datosContaDiario,
                'fechaI' => $fechaI,
                'fechaF' => $fechaF,
                'sucursal' => $sucursal,
                'caja' => $caja
            ]);
            $html_content =  $view->render();

            $pdf::writeHTML($html_content, true, false, true, false, '');

            $pdf::lastPage();

            $pdf::Output('reporteLibriDiario.pdf');
        }
    }

    public function imprmirLibroDiario11($fechaI, $fechaF)
    {
        ini_set('max_execution_time', 3600);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        //dd($fechaI);
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            $datosContaDiario = ContaDiario::distinct()->select('fecha_a', 'num_comprobante')->whereBetween('fecha_a', [$fechaInicio, $fechaFinal])
                ->where('estado_id', 1)
                //->orderBy('fecha_a','ASC')
                ->orderBy('num_comprobante', 'ASC')
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
                $pdf->Cell(0, -15, 'DIARIO GENERAL DE MOVIMIENTOS', 0, false, 'L', 0, '', 0, false, 'M', 'M');
                $pdf->SetXY(15, 30);
                $pdf->Cell(0, -15, 'EN FECHA ', 0, false, 'L', 0, '', 0, false, 'M', 'M');
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
            $html = "";

            $html .= '<table border="0" cellspacing="0" cellpadding="3">';
            $html .= '<tbody>';

            $totalImporteDebe = 0;
            $totalImporteHaber = 0;

            foreach ($datosContaDiario as $key => $dato) {
                $fechaLetras = strftime("%d  - %B - %Y", strtotime($dato->fecha_a));
                $html .= '<tr>';
                $html .= '<td align="center" width="100%"colspan="4"><strong>____________ ' . $dato->num_comprobante . ' ___________</strong></td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td align="center"  colspan="2" width="20%"><strong>' . $fechaLetras . '</strong></td>';
                $html .= '<td align="center"  width="40%"><strong></strong></td>';
                $html .= '<td align="center"  width="20%"><strong><u> DEBE </u> </strong></td>';
                $html .= '<td align="center"  width="20%"><strong><u> HABER </u></strong></td>';
                $html .= '</tr>';
                $detalleContaIdario = ContaDiario::where('estado_id', 1)->where('num_comprobante', $dato->num_comprobante)->orderBy('id', 'ASC')->get();
                $sumaDebe = 0;
                $sumaHaber = 0;
                $glosaGeneral = "";
                foreach ($detalleContaIdario as $key => $dato2) {
                    $sumaDebe = $sumaDebe + $dato2->debe;
                    $sumaHaber = $sumaHaber + $dato2->haber;
                    $html .= '<tr>';
                    $html .= '<td align="center"  width="10%">' . $dato2->cod_deno . '</td>';
                    $html .= '<td align="left"  width="40%">' . $dato2->cuenta . '</td>';
                    $html .= '<td align="right"  width="25%">' . number_format($dato2->debe, 2, ',', '.') . '</td>';
                    $html .= '<td align="right"  width="20%">' . number_format($dato2->haber, 2, ',', '.') . '</td>';
                    $html .= '</tr>';
                    $totalImporteDebe = $totalImporteDebe + $dato2->debe;
                    $totalImporteHaber = $totalImporteHaber + $dato2->haber;
                    $glosaGeneral = $glosaGeneral . ' ' . $dato2->glosa;
                }
                $html .= '<tr>';
                $html .= '<td align="right"  width="10%">GLOSA:</td>';
                $html .= '<td align="left"  width="50%">' . $dato2->glosa . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td align="right"  width="60%" colspan="2"><strong>SUMA</strong></td>';
                $html .= '<td align="right"  width="15%" ><strong><u> ' . number_format($sumaDebe, 2, ',', '.') . ' </u></strong></td>';
                $html .= '<td align="right"  width="20%" ><strong><u> ' . number_format($sumaHaber, 2, ',', '.') . ' </u></strong></td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '<tfoot>';
            $html .= '<tr>';
            $html .= '<th scope="row" colspan="2" align="right"><strong>SUMA TOTAL DE MOVIMIENTOS:</strong></th>';
            $html .= '<td align="right"><strong>' . number_format($totalImporteDebe, 2, ',', '.') . '</strong></td>';
            $html .= '<td align="right"><strong>' . number_format($totalImporteHaber, 2, ',', '.') . '</strong></td>';
            $html .= '</tr>';
            $html .= '</tfoot>';
            $html .= "</table>";

            $pdf::writeHTML($html, true, false, true, false, '');

            $pdf::lastPage();

            $pdf::Output('reporteLibriDiario.pdf');
        }
    }

    public function imprmirLibroDiarioExcel($fechaI, $fechaF, Request $request)
    {
        ini_set('max_execution_time', 3600);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        //dd($fechaI);
        if (Session::has('AUTENTICADO')) {
            $caja = $request->caja;
            $sucursal = Sucursal::find($request->sucursal);
            $id_sucursal = $sucursal->id;
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


            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            $datosContaDiario = ContaDiario::distinct()->select('fecha_a', 'num_comprobante')->whereBetween('fecha_a', [$fechaInicio, $fechaFinal])
                ->where('estado_id', 1)
                //->orderBy('fecha_a','ASC')
                ->orderBy('num_comprobante', 'ASC')
                ->get();
            $datosContaDiario = ContaDiario::select('conta_diario.*')
                ->join('pagos', 'pagos.id', '=', 'conta_diario.pagos_id')
                ->whereBetween('conta_diario.fecha_a', [$fechaInicio, $fechaFinal])
                ->where('conta_diario.estado_id', 1)
                ->where('conta_diario.sucursal_id', $sucursal->id)
                ->where('pagos.caja', $idCaja)
                ->orderBy('conta_diario.num_comprobante', 'ASC')
                ->get();
            //dd($datosContaDiario);


            $documento = new Spreadsheet();
            $documento
                ->getProperties()
                ->setCreator("MASTIN")
                ->setLastModifiedBy('admin') // última vez modificado por
                ->setTitle('Reporte de Libro Diario')
                ->setSubject('Reporte')
                ->setDescription('generado por Admin')
                ->setKeywords('etiquetas o palabras clave separadas por espacios')
                ->setCategory('Contabilidad');

            $hoja = $documento->getActiveSheet();

            /*NOMBRE DE LA HOJA*/
            $hoja->setTitle('Libro Diario');

            /*TITULO DEL REPORTES COMBINANDO CELDAS*/
            $hoja->getCell('A1')->setValue("DIARIO GENERAL DE MOVIMIENTOS");
            $hoja->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            //$documento->getActiveSheet()->mergeCells("{$rangoCInicio}:{$rangoCFin}");
            $hoja->mergeCells('A1:D1');
            $hoja->getStyle('A1')->getFont()->setBold(true);
            /*TAMAÑO DE LA LETRA*/
            $hoja->getStyle("A1:D1")->getFont()->setSize(16);

            /*SUCURSAL CAJA*/
            $hoja->getCell('A2')->setValue("SUCURSAL " . $sucursal->nombre . ' - CAJA ' . $caja);
            $hoja->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            //$documento->getActiveSheet()->mergeCells("{$rangoCInicio}:{$rangoCFin}");
            $hoja->mergeCells('A2:D2');
            $hoja->getStyle('A2')->getFont()->setBold(true);
            /*TAMAÑO DE LA LETRA*/
            $hoja->getStyle("A2:D2")->getFont()->setSize(14);

            $totalImporteDebe = 0;
            $totalImporteHaber = 0;
            $i = 3;

            foreach ($datosContaDiario as $key => $dato) {
                $fechaLetras = strftime("%d  - %B - %Y", strtotime($dato->fecha_a));
                $hoja->setCellValue('A' . $i,  '___________' . $dato->num_comprobante . '___________');

                $celdasCInicio = $i;
                $celdasCFin = $i;
                $rangoCInicio = 'A' . $celdasCInicio;
                $rangoCFin = 'D' . $celdasCInicio;
                $hoja->mergeCells("{$rangoCInicio}:{$rangoCFin}");
                $hoja->getStyle('A' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                //$hoja->getStyle('D'.$i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $hoja->getStyle('A' . $i)->getFont()->setBold(true);
                //$hoja->mergeCells('A'.$i.':D7'.$i.'');
                $i++;
                $hoja->setCellValue('A' . $i,  $fechaLetras);
                $hoja->getStyle('A' . $i)->getFont()->setBold(true);
                //$hoja->getColumnDimension('A')->setAutoSize(true);
                $hoja->getColumnDimension('A')->setWidth(10);
                $hoja->setCellValue('C' . $i,  'DEBE');
                $hoja->setCellValue('D' . $i,  'HABER');
                $hoja->getStyle('C' . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
                $hoja->getStyle('D' . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
                $hoja->getStyle('C' . $i)->getFont()->setBold(true);
                $hoja->getStyle('D' . $i)->getFont()->setBold(true);

                $detalleContaIdario = ContaDiario::where('estado_id', 1)->where('num_comprobante', $dato->num_comprobante)->orderBy('id', 'ASC')->get();
                $sumaDebe = 0;
                $sumaHaber = 0;
                // $glosaGeneral = "";
                foreach ($detalleContaIdario as $key => $dato2) {
                    $sumaDebe = $sumaDebe + $dato2->debe;
                    $sumaHaber = $sumaHaber + $dato2->haber;
                    $i++;
                    $hoja->setCellValue('A' . $i,  $dato2->cod_deno);
                    $hoja->setCellValue('B' . $i,  $dato2->cuenta);
                    $hoja->getStyle('A' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('C' . $i,  number_format($dato2->debe, 2, ',', '.'));
                    $hoja->setCellValue('D' . $i,  number_format($dato2->haber, 2, ',', '.'));
                    $totalImporteDebe = $totalImporteDebe + $dato2->debe;
                    $totalImporteHaber = $totalImporteHaber + $dato2->haber;
                }
                $i++;
                $hoja->setCellValue('A' . $i,  'GLOSA:');
                $hoja->getStyle('A' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $hoja->getStyle('A' . $i)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                $hoja->setCellValue('B' . $i,  $dato2->glosa);
                $hoja->getColumnDimension('B')->setWidth(50);
                $i++;
                $hoja->setCellValue('B' . $i,  'SUMA');
                $hoja->getStyle('B' . $i)->getFont()->setBold(true);
                $hoja->getStyle('B' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $hoja->setCellValue('C' . $i,  number_format($sumaDebe, 2, ',', '.'));
                $hoja->getStyle('C' . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
                $hoja->setCellValue('D' . $i,  number_format($sumaHaber, 2, ',', '.'));
                $hoja->getStyle('D' . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
                $hoja->getStyle('C' . $i)->getFont()->setBold(true);
                $hoja->getStyle('D' . $i)->getFont()->setBold(true);
                $hoja->getColumnDimension('B')->setWidth(50);
                $i++;
            }

            $hoja->setCellValue('B' . $i, "SUMA TOTAL DE MOVIMIENTOS");
            $hoja->getStyle('B' . $i)->getFont()->setBold(true);
            $hoja->getStyle('B' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $hoja->setCellValue('C' . $i, number_format($totalImporteDebe, 2, ',', '.'));
            $hoja->getStyle('C' . $i)->getFont()->setBold(true);
            $hoja->getColumnDimension('C')->setAutoSize(true);
            $hoja->setCellValue('D' . $i, number_format($totalImporteHaber, 2, ',', '.'));
            $hoja->getStyle('D' . $i)->getFont()->setBold(true);
            $hoja->getColumnDimension('D')->setAutoSize(true);

            $documento->getDefaultStyle()->getAlignment()->setWrapText(true);
            $hoja->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

            $hoja->getPageMargins()->setTop(1);
            $hoja->getPageMargins()->setRight(0);
            $hoja->getPageMargins()->setLeft(0);
            $hoja->getPageMargins()->setBottom(1);

            $nombreDelDocumento = "Libro_Dario.xlsx";

            $writer = new Xlsx($documento);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
            header('Cache-Control: max-age=0');
            $writer = IOFactory::createWriter($documento, 'Xlsx');

            ob_start();
            $writer->save("php://output");
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
            );
            die(json_encode($response));



            // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, "Xlsx");
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment; filename="libroDiario"'. Carbon::parse($fechaI)->format('dmY') .'_'.Carbon::parse($fechaF)->format('dmY').'.xlsx"');
            // header('Cache-Control: max-age=0');
            // $writer->save("php://output");

        }
    }


    public function imprmirSumaySaldos($fechaF, Request $request)
    {
        ini_set('max_execution_time', 3600);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        //dd($fechaI);
        if (Session::has('AUTENTICADO')) {
            // $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            // $datosContaDiario = ContaDiario::whereBetween('fecha_a',[$fechaInicio,$fechaFinal])
            //     ->where('estado_id',1)
            //     ->orderBy('cod_deno','ASC')
            //     ->get();

            $datosContaDiario = DB::table('conta_diario')
                ->select('cod_deno', 'cuenta', DB::raw('SUM(debe) as debe', 'SUM(haber) as haber'), DB::raw('SUM(haber) as haber'))
                ->where('fecha_a', '<=', $fechaFinal)
                ->groupBy('cod_deno', 'cuenta')
                //->havingRaw('SUM(price) > ?', [2500])
                ->get();

            $sw = false;
            $sucursal = null;
            $caja = null;
            if ($request->caja != '' && $request->sucursal != '' && $request->caja != null && $request->sucursal != null && isset($request->caja) && isset($request->sucursal)) {
                $sw = true;
                $caja = $request->caja;
                $sucursal = Sucursal::find($request->sucursal);
                $id_sucursal = $sucursal->id;
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

                $datosContaDiario = DB::table('conta_diario')
                    ->select('conta_diario.cod_deno', 'conta_diario.cuenta', DB::raw('SUM(conta_diario.debe) as debe', 'SUM(conta_diario.haber) as haber'), DB::raw('SUM(haber) as haber'))
                    ->join('pagos', 'pagos.id', '=', 'conta_diario.pagos_id')
                    ->where('pagos.caja', $idCaja)
                    ->where('conta_diario.sucursal_id', $sucursal->id)
                    ->where('conta_diario.fecha_a', '<=', $fechaFinal)
                    ->groupBy('conta_diario.cod_deno', 'cuenta')
                    //->havingRaw('SUM(price) > ?', [2500])
                    ->get();
            }

            //dd($datosContaDiario);
            $paperSize = 'LETTER';
            $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $paperSize, true, 'UTF-8', false);
            $pdf::setHeaderCallback(function ($pdf) use ($sucursal, $caja, $sw) {
                $now = Carbon::now('America/La_Paz');
                $pdf->SetFont('Helvetica', '', 7.5);
                $pdf->SetXY(15, 20);
                $pdf->Cell(0, -15, 'PRENDASOL', 0, false, 'C', 0, '', 0, false, 'M', 'M');
                if ($sw) {
                    $pdf->SetXY(15, 25);
                    $pdf->Cell(0, -15, 'SUCURSAL ' . $sucursal->nombre . ' - CAJA ' . $caja, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                    $pdf->SetFont('helvetica', 'B,U', 8);
                    $pdf->SetXY(15, 30);
                    $pdf->Cell(0, -15, 'Expresado en Bolivianos (Bs)', 0, false, 'C', 0, '', 0, false, 'M', 'M');
                } else {
                    $pdf->SetFont('helvetica', 'B,U', 8);
                    $pdf->SetXY(15, 25);
                    $pdf->Cell(0, -15, 'Expresado en Bolivianos (Bs)', 0, false, 'C', 0, '', 0, false, 'M', 'M');
                }
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

            $pdf::SetTitle('Reporte Suma y Saldos');

            $pdf::AddPage();
            $pdf::SetFont('Helvetica', '', 10);
            $pdf::Ln(30);

            $pdf::SetMargins(10, 40, 0);
            $html = "";

            $html .= '<table border="0" cellspacing="0" cellpadding="3" width="100%">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th align="center" width="100%" colspan="6">___________________________________________________________________________________________________</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th align="center" width="45%" colspan="2"></th>';
            $html .= '<th align="center" width="22%" colspan="2"><strong>SUMAS</strong></th>';
            $html .= '<th align="center" width="25%" colspan="2"><strong>SALDOS</strong></th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th align="center"  width="10%"><strong>CODIGO</strong></th>';
            $html .= '<th align="center"  width="35%"><strong>CUENTA</strong></th>';
            $html .= '<th align="center"  width="12%"><strong>DEBE</strong></th>';
            $html .= '<th align="center"  width="12%"><strong>HABER</strong></th>';
            $html .= '<th align="center"  width="12%"><strong>DEUDOR</strong></th>';
            $html .= '<th align="center"  width="12%"><strong>ACREEDOR</strong></th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th align="center" width="100%" colspan="6">___________________________________________________________________________________________________</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $totalImporteDebe = 0;
            $totalImporteHaber = 0;
            $totalDeudor = 0;
            $totalAcreedor = 0;

            foreach ($datosContaDiario as $key => $dato) {
                $debe = $dato->debe;
                $haber = $dato->haber;
                $totalImporteDebe = $totalImporteDebe + $debe;
                $totalImporteHaber = $totalImporteHaber + $haber;
                if ($debe >= $haber) {
                    $resDebe = $debe - $haber;
                    $totalDeudor = $totalDeudor + $resDebe;
                } else {
                    $resDebe = 0;
                }

                if ($haber >= $debe) {
                    $resHaber = $haber - $debe;
                    $totalAcreedor = $totalAcreedor + $resHaber;
                } else {
                    $resHaber = 0;
                }

                $html .= '<tr>';
                $html .= '<td align="center" width="8%">' . $dato->cod_deno . '</td>';
                $html .= '<td width="35%">' . $dato->cuenta . '</td>';
                $html .= '<td align="right" width="12%">' . number_format($debe, 2, ',', '.') . '</td>';
                $html .= '<td align="right" width="12%">' . number_format($haber, 2, ',', '.') . '</td>';
                $html .= '<td align="right" width="12%">' . number_format($resDebe, 2, ',', '.') . '</td>';
                $html .= '<td align="right" width="12%">' . number_format($resHaber, 2, ',', '.') . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody>';
            $html .= '<tfoot>';
            $html .= '<tr>';
            $html .= '<th scope="row" colspan="2" align="right"></th>';
            $html .= '<th scope="row" colspan="4" align="right">_____________________________________________</th>';
            $html .= '</tr>';
            $html .= '<tr>';

            $html .= '<th scope="row" colspan="2" align="right"><strong>Total General:</strong></th>';
            $html .= '<td align="center"><strong>' . number_format($totalImporteDebe, 2, ',', '.') . '</strong></td>';
            $html .= '<td align="center"><strong>' . number_format($totalImporteHaber, 2, ',', '.') . '</strong></td>';
            $html .= '<td align="center"><strong>' . number_format($totalDeudor, 2, ',', '.') . '</strong></td>';
            $html .= '<td align="center"><strong>' . number_format($totalAcreedor, 2, ',', '.') . '</strong></td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th scope="row" colspan="2" align="right"></th>';
            $html .= '<th scope="row" colspan="4" align="right">_____________________________________________</th>';
            $html .= '</tr>';
            $html .= '</tfoot>';
            $html .= "</table>";


            $pdf::writeHTML($html, true, false, true, false, '');

            $pdf::lastPage();

            $pdf::Output('reporteSumaSaldos.pdf');
        }
    }


    public function imprmirBalanceGeneral($fechaI, $fechaF)
    {
        ini_set('max_execution_time', 3600);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        //dd($fechaI);
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            // $datosContaDiario = ContaDiario::whereBetween('fecha_a',[$fechaInicio,$fechaFinal])
            //     ->where('estado_id',1)
            //     ->orderBy('cod_deno','ASC')
            //     ->get();
            $datosContaDiario = DB::table('conta_diario')
                ->select('cod_deno', 'cuenta', DB::raw('SUM(debe) as debe', 'SUM(haber) as haber'), DB::raw('SUM(haber) as haber'))
                ->groupBy('cod_deno', 'cuenta')
                //->havingRaw('SUM(price) > ?', [2500])
                ->get();
            //dd($datosContaDiario);
            $paperSize = 'LETTER';
            $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $paperSize, true, 'UTF-8', false);
            $pdf::setHeaderCallback(function ($pdf) {
                $now = Carbon::now('America/La_Paz');
                $pdf->SetFont('Helvetica', '', 7.5);
                $pdf->SetXY(15, 20);
                $pdf->Cell(0, -15, 'PRENDASOL', 0, false, 'C', 0, '', 0, false, 'M', 'M');

                $pdf->SetFont('helvetica', 'B,U', 8);
                $pdf->SetXY(15, 25);
                $pdf->Cell(0, -15, 'Expresado en Bolivianos (Bs)', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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

            $pdf::SetTitle('Reporte Suma y Saldos');

            $pdf::AddPage();
            $pdf::SetFont('Helvetica', '', 10);
            $pdf::Ln(30);

            $pdf::SetMargins(10, 40, 0);
            $html = "";




            $pdf::writeHTML($html, true, false, true, false, '');

            $pdf::lastPage();

            $pdf::Output('reporteSumaSaldos.pdf');
        }
    }

    public function imprmirLibroMayor($fechaF)
    {
        ini_set('max_execution_time', 100000);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        //dd($fechaI);
        if (Session::has('AUTENTICADO')) {
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            // $datosContaDiario = ContaDiario::whereBetween('fecha_a',[$fechaInicio,$fechaFinal])
            //     ->where('estado_id',1)
            //     ->orderBy('cod_deno','ASC')
            //     ->get();
            $datosContaDiario = DB::table('conta_diario')
                ->select('cod_deno', 'cuenta', DB::raw('SUM(debe) as debe', 'SUM(haber) as haber'), DB::raw('SUM(haber) as haber'))
                ->groupBy('cod_deno', 'cuenta')
                //->havingRaw('SUM(price) > ?', [2500])
                ->get();
            //dd($datosContaDiario);
            $paperSize = 'LETTER';
            $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $paperSize, true, 'UTF-8', false);
            $pdf::setHeaderCallback(function ($pdf) {
                $now = Carbon::now('America/La_Paz');
                $pdf->SetFont('helvetica', 'B', 20);
                // Title
                //$this->Cell(0, 15, 'RENDICIÓN DE CUENTAS', 0, false, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->SetXY(15, 20);
                $pdf->Cell(0, -15, 'LIBRO MAYOR', 0, false, 'C', 0, '', 0, false, 'M', 'M');

                $pdf->SetFont('helvetica', 'B,U', 8);
                $pdf->SetXY(15, 25);
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

            $pdf::SetTitle('Reporte Libro Mayor');

            $pdf::AddPage();
            $pdf::SetFont('Helvetica', '', 10);
            $pdf::Ln(30);

            $pdf::SetMargins(10, 40, 0);

            $datosContaDiario = ContaDiario::distinct()->select('cod_deno', 'cuenta')->where('fecha_a', '<=', $fechaFinal)
                ->where('estado_id', 1)
                //->orderBy('fecha_a','ASC')
                ->orderBy('cod_deno', 'ASC')
                ->get();
            $html = "";
            $html .= '<table border="0" cellspacing="0" cellpadding="3" width="100%">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th align="center"  width="10%"><strong><u>FECHA</u></strong></th>';
            $html .= '<th align="center"  width="10%"><strong><u>CMPBTE</u></strong></th>';
            // $html .='<th align="center"  width="30%"><strong><u>CONCEPTO</u></strong></th>';
            $html .= '<th align="center"  width="50%"><strong><u>GLOSA ABREV.</u></strong></th>';
            $html .= '<th align="center"  width="10%"><strong><u>DEBE</u></strong></th>';
            $html .= '<th align="center"  width="10%"><strong><u>HABER</u></strong></th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th align="center"  width="10%"></th>';
            $html .= '<th align="center"  width="10%"></th>';
            $html .= '<th align="center"  width="30%"></th>';
            $html .= '<th align="center"  width="30%"></th>';
            $html .= '<th align="center"  width="10%"></th>';
            $html .= '<th align="center"  width="10%"></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $totalDebe = 0;
            $totalHaber = 0;

            foreach ($datosContaDiario as $key => $dato) {
                $html .= '<tr>';
                $html .= '<td align="left" width="20%" colspan="2"><strong>' . $dato->cod_deno . '</strong></td>';
                $html .= '<td align="left" width="80%" colspan="4"><strong>' . $dato->cuenta . '</strong></td>';
                $html .= '</tr>';
                $detallesContaDiario = ContaDiario::where('estado_id', 1)
                    ->where('cod_deno', $dato->cod_deno)
                    ->where('fecha_a', '<=', $fechaFinal)
                    ->orderBy('fecha_a', 'ASC')
                    ->get();
                $html .= '<tr>';
                $html .= '<td align="left" width="100%" colspan="6">';
                $html .= '<table border="1" cellspacing="0" cellpadding="3" width="100%">';
                $sumaDebe = 0;
                $sumaHaber = 0;
                foreach ($detallesContaDiario as $key => $dato2) {
                    $sumaDebe = (float)$sumaDebe + (float)$dato2->debe;
                    $sumaHaber = (float)$sumaHaber + (float)$dato2->haber;

                    $html .= '<tr>';
                    $html .= '<td align="center"  width="10%">' . date("d/m/Y", strtotime($dato2->fecha_a)) . '</td>';
                    $html .= '<td align="center"  width="10%">' . $dato2->num_comprobante . '</td>';
                    // $html .='<td align="left"  width="30%"></td>';
                    $html .= '<td align="left"  width="50%">' . $dato2->glosa . '</td>';
                    $html .= '<td align="right"  width="10%">' . number_format($dato2->debe, 2, ',', '.') . '</td>';
                    $html .= '<td align="right"  width="10%">' . number_format($dato2->haber, 2, ',', '.') . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td align="right" width="70%" colspan="4"><strong>Total&nbsp;&nbsp;&nbsp;&nbsp;' . $dato->cod_deno . '&nbsp;&nbsp;&nbsp;&nbsp;' . $dato->cuenta . '</strong></td>';
                $html .= '<td align="right" width="10%" colspan="4"><strong>' . number_format($sumaDebe, 2, ',', '.') . '</strong></td>';
                $html .= '<td align="right" width="10%" colspan="4"><strong>' . number_format($sumaHaber, 2, ',', '.') . '</strong></td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td align="right" width="100%" colspan="6"></td>';
                $html .= '</tr>';
                $totalDebe = $totalDebe + $sumaDebe;
                $totalHaber = $totalHaber + $sumaHaber;
            }

            $html .= '<tr>';
            $html .= '<td align="right" width="70%" colspan="4"><strong>Suma Final</strong></td>';
            $html .= '<td align="right" width="10%"><strong>' . number_format($totalDebe, 2, ',', '.') . '</strong></td>';
            $html .= '<td align="right" width="10%"><strong>' . number_format($totalHaber, 2, ',', '.') . '</strong></td>';
            $html .= '</tr>';
            $html .= '</tbody>';
            $html .= '</table>';

            // $detalleContaIdario = ContaDiario::where('estado_id',1)->where('num_comprobante',$dato->num_comprobante)->orderBy('id','ASC')->get();




            $pdf::writeHTML($html, true, false, true, false, '');

            $pdf::lastPage();

            $pdf::Output('reporteLibroMayor.pdf');
        }
    }


    public function imprmirLibroMayorExcel($fechaF)
    {
        ini_set('max_execution_time', 100000);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        //dd($fechaI);
        if (Session::has('AUTENTICADO')) {
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');

            $datosContaDiario = ContaDiario::distinct()->select('cod_deno', 'cuenta')->where('fecha_a', '<=', $fechaFinal)
                ->where('estado_id', 1)
                //->orderBy('fecha_a','ASC')
                ->orderBy('cod_deno', 'ASC')
                ->get();

            $documento = new Spreadsheet();

            /*FIRMA DE DOCUMENTO*/
            $documento
                ->getProperties()
                ->setCreator("MASTIN")
                ->setLastModifiedBy('admin') // última vez modificado por
                ->setTitle('Reporte de Libro Diario')
                ->setSubject('Reporte')
                ->setDescription('generado por Admin')
                ->setKeywords('etiquetas o palabras clave separadas por espacios')
                ->setCategory('Contabilidad');

            /**/
            $hoja = $documento->getActiveSheet();

            /*NOMBRE DE LA HOJA*/
            $hoja->setTitle('Libro Diario');

            /*TITULO DEL REPORTES COMBINANDO CELDAS*/
            $hoja->getCell('A1')->setValue("LIBRO MAYOR");
            $hoja->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            //$documento->getActiveSheet()->mergeCells("{$rangoCInicio}:{$rangoCFin}");
            $hoja->mergeCells('A1:F1');
            $hoja->getStyle('A1')->getFont()->setBold(true);
            /*TAMAÑO DE LA LETRA*/
            $hoja->getStyle("A1:F1")->getFont()->setSize(16);

            $hoja->getCell('A2')->setValue("(MONEDA EXTRANJERA)");
            $hoja->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $hoja->mergeCells('A2:F2');
            $hoja->getStyle('A2')->getFont()->setBold(true);

            /*ARMAMOS TITULO DE LOS CAMPOS*/
            $hoja->setCellValue('A6', "FECHA");
            $hoja->setCellValue('B6', "CMPBTE");
            $hoja->setCellValue('C6', "GLOSA ABREV.");
            $hoja->setCellValue('D6', "DEBE");
            $hoja->setCellValue('E6', "HABER");

            /*AJUSTAR CELDA TAMAÑO ALTO*/
            $hoja->getRowDimension('6')->setRowHeight(40);

            /*ALINEAMOS LAS CELDAS AL CENTRO EN HORIZONTAL Y VERTICAL*/
            $hoja->getStyle('A6:E6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $hoja->getStyle('A6:E6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            /*CONVIERTE NEGRILLA*/
            $hoja->getStyle('A6:E6')->getFont()->setBold(true);
            $hoja->getStyle('A6:E6')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
            $hoja->getStyle('A6:E6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

            $i = 7;

            $totalDebe = 0;
            $totalHaber = 0;

            foreach ($datosContaDiario as $key => $dato) {
                //$resCodigo = $resC["cucodigo"];
                $hoja->setCellValue('A' . $i, $dato->cod_deno);
                $hoja->mergeCells('A' . $i . ':B' . $i . '');
                $hoja->getColumnDimension('A')->setAutoSize(true);


                $hoja->setCellValue('C' . $i, $dato->cuenta);
                $hoja->mergeCells('C' . $i . ':E' . $i . '');

                $hoja->getStyle('A' . $i . ':E' . $i . '')->getFont()->setBold(true);
                $hoja->getRowDimension('' . $i . '')->setRowHeight(27);
                $hoja->getStyle('A' . $i . ':E' . $i . '')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $hoja->getStyle('A' . $i . ':E' . $i . '')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $i++;


                $detallesContaDiario = ContaDiario::where('estado_id', 1)
                    ->where('cod_deno', $dato->cod_deno)
                    ->where('fecha_a', $fechaFinal)
                    ->orderBy('fecha_a', 'ASC')
                    ->get();

                $sumaDebe = 0;
                $sumaHaber = 0;
                foreach ($detallesContaDiario as $key => $dato2) {
                    $sumaDebe = (float)$sumaDebe + (float)$dato2->debe;
                    $sumaHaber = (float)$sumaHaber + (float)$dato2->haber;

                    $hoja->setCellValue('A' . $i, date("d/m/Y", strtotime($dato2->fecha_a)));
                    $hoja->setCellValue('B' . $i, $dato2->num_comprobante);
                    $hoja->setCellValue('C' . $i, $dato2->glosa);
                    $hoja->setCellValue('D' . $i, number_format($dato2->debe, 2, ',', '.'));
                    $hoja->setCellValue('E' . $i, number_format($dato2->haber, 2, ',', '.'));

                    $hoja->getStyle('A' . $i . '')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('A' . $i . '')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('A' . $i . '')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('A' . $i . '')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    $hoja->getStyle('B' . $i . '')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('B' . $i . '')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('B' . $i . '')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('B' . $i . '')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    $hoja->getStyle('C' . $i . '')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('C' . $i . '')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('C' . $i . '')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('C' . $i . '')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    $hoja->getStyle('D' . $i . '')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('D' . $i . '')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('D' . $i . '')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('D' . $i . '')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    $hoja->getStyle('E' . $i . '')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('E' . $i . '')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('E' . $i . '')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $hoja->getStyle('E' . $i . '')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    // $hoja->getStyle('F'.$i.'')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    // $hoja->getStyle('F'.$i.'')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    // $hoja->getStyle('F'.$i.'')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    // $hoja->getStyle('F'.$i.'')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    $hoja->getStyle('A' . $i . ':D' . $i . '')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $hoja->getStyle('D' . $i . ':E' . $i . '')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $hoja->getStyle('A' . $i . ':B' . $i . '')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $hoja->getStyle('E' . $i . ':E' . $i . '')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $hoja->getStyle('C' . $i . ':D' . $i . '')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                    $hoja->getStyle('C' . $i . '')->getAlignment()->setWrapText(true);
                    //$hoja->getStyle('D'.$i.'')->getAlignment()->setWrapText(true);

                    $i++;
                }

                $hoja->setCellValue('C' . $i, 'TOTAL:    ' . $dato->cod_deno . '    ' . $dato->cuenta);
                $hoja->setCellValue('D' . $i, number_format($sumaDebe, 2, ',', '.'));
                $hoja->setCellValue('E' . $i, number_format($sumaHaber, 2, ',', '.'));
                //$hoja->mergeCells('C'.$i.':D'.$i.'');
                $hoja->getStyle('C' . $i . ':E' . $i . '')->getFont()->setBold(true);
                $hoja->getStyle('D' . $i . ':E' . $i . '')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $i++;
                $i++;
                $totalDebe = $totalDebe + $sumaDebe;
                $totalHaber = $totalHaber + $sumaHaber;
            }

            $hoja->setCellValue('C' . $i, 'SUMA FINAL');
            $hoja->setCellValue('D' . $i, number_format($totalDebe, 2, ',', '.'));
            $hoja->setCellValue('E' . $i, number_format($totalHaber, 2, ',', '.'));
            //$hoja->mergeCells('C'.$i.':D'.$i.'');
            $hoja->getStyle('C' . $i . ':E' . $i . '')->getFont()->setBold(true);
            $hoja->getStyle('D' . $i . ':E' . $i . '')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $hoja->getColumnDimension('A')->setAutoSize(true);
            $hoja->getColumnDimension('B')->setAutoSize(true);
            $hoja->getColumnDimension('C')->setWidth(25);
            //$hoja->getColumnDimension('C')->setAutoSize(true);
            $hoja->getColumnDimension('D')->setWidth(25);
            $hoja->getColumnDimension('E')->setAutoSize(true);
            $hoja->getColumnDimension('F')->setAutoSize(true);

            /*TAMAÑO DE LA HOJA*/
            $hoja->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

            /*MARAGENES DE LA HOJA*/
            $hoja->getPageMargins()->setTop(1);
            $hoja->getPageMargins()->setRight(0);
            $hoja->getPageMargins()->setLeft(0);
            $hoja->getPageMargins()->setBottom(1);


            /*NOMBRE DEL ARCHIVO*/
            $nombreDelDocumento = "Libro_mayor.xlsx";

            $writer = new Xlsx($documento);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
            header('Cache-Control: max-age=0');
            $writer = IOFactory::createWriter($documento, 'Xlsx');

            ob_start();
            $writer->save("php://output");
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
            );
            die(json_encode($response));

            // $pdf::writeHTML($html, true, false, true, false, '');

            // $pdf::lastPage();

            // $pdf::Output('reporteLibroMayor.pdf');
        }
    }

    public function imprimirEstadoResultados($fechaI, $fechaF)
    {
        ini_set('max_execution_time', 3600);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        //dd($fechaI);
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            $this->fechaInicialGlobal = $fechaI;
            $this->fechaFinalGlobal = $fechaF;

            //dd($datosContaDiario);
            $paperSize = 'LETTER';
            $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $paperSize, true, 'UTF-8', false);
            $pdf::setHeaderCallback(function ($pdf) {
                $now = Carbon::now('America/La_Paz');
                $pdf->SetFont('helvetica', '', 8);
                // Title
                //$this->Cell(0, 15, 'RENDICIÓN DE CUENTAS', 0, false, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->SetXY(10, 10);
                $pdf->Cell(0, 2, 'Fecha de Impresión: ' . date("d/m/Y"), 0, false, 'L', 0, '', 0, false, 'M', 'M');

                $pdf->SetFont('helvetica', 'B', 20);
                $pdf->SetXY(15, 20);
                $pdf->Cell(0, -15, 'RESÚMEN DE EGRESOS E INGRESOS', 0, false, 'C', 0, '', 0, false, 'M', 'M');

                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetXY(15, 25);
                $pdf->Cell(0, -15, 'Desde ' . $this->fechaInicialGlobal . ' Al ' . $this->fechaFinalGlobal . '', 0, false, 'C', 0, '', 0, false, 'M', 'M');

                $pdf->SetFont('helvetica', 'B,U', 8);
                $pdf->SetXY(15, 29);
                $pdf->Cell(0, -15, 'Moneda Local', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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

            // set default header data
            $pdf::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

            // set header and footer fonts
            $pdf::setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf::setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf::SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf::setLanguageArray($l);
            }

            $pdf::SetCreator(PDF_CREATOR);
            $pdf::SetAuthor('PRENDASOL');
            $pdf::SetTitle('ESTADO DE RESULTADOS');
            $pdf::SetSubject('ESTADO DE RESULTADOS');
            $pdf::SetKeywords('ESTADO DE RESULTADOS');



            $pdf::SetFont('helvetica', '', 8);
            // add a page
            $pdf::AddPage();

            $pdf::SetFont('helvetica', 'B', 15);
            $html = "";
            $html .= '<table border="0" cellspacing="0" cellpadding="3" width="100%">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th align="center" width="100%" colspan="6">_________________________________________________________________________________________________________________</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th align="center"  width="10%"><strong>CODIGO</strong></th>';
            $html .= '<th align="center"  width="30%"><strong>CUENTA</strong></th>';
            //$html .='<th align="center"  width="10%"><strong>NIVEL</strong></th>';
            $html .= '<th align="center"  width="12%"><strong></strong></th>';
            $html .= '<th align="center"  width="12%"><strong></strong></th>';
            $html .= '<th align="center"  width="12%"><strong></strong></th>';
            $html .= '<th align="center"  width="12%"><strong></strong></th>';
            $html .= '<th align="center"  width="12%"><strong></strong></th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th align="center" width="100%" colspan="6">_________________________________________________________________________________________________________________</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            /*INGRESOS*/
            $datoTotalIngreso = ContaDenominacion::fnTotalCuentaGeneral($fechaInicio, $fechaFinal, 4);
            //dd($datoTotalIngreso);
            $resTotalIngreso = (float)$datoTotalIngreso->haber - (float)$datoTotalIngreso->debe;
            //dd($resTotalIngreso);
            $html .= '<tr bgcolor="#7f7c7c">';
            $html .= '<td align="center" width="10%">40000</td>';
            $html .= '<td width="50%">INGRESOS</td>';
            $html .= '<td align="right" width="30%">' . number_format($resTotalIngreso, 2, ',', '.') . '</td>';
            $html .= '</tr>';



            $html .= '<tr>';
            $html .= '<td align="center" width="10%">41100</td>';
            $html .= '<td width="30%">INGRESOS OPERATIVOS</td>';
            $html .= '<td align="right" width="12%"></td>';
            $html .= '</tr>';

            $query411 = ContaDenominacion::where('subgrupo2', '411')->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
            foreach ($query411 as $key => $dato411) {
                $datoTotal411 = $dato411->fnTotalCuenta($fechaInicio, $fechaFinal, $dato411->cod_deno);
                $resTotal411 = (float)$datoTotal411->haber - (float)$datoTotal411->debe;
                $formatResTotal411 = number_format($resTotal411, 2, ',', '.');
                if ($datoTotal411->haber) {
                    if ($formatResTotal411 != "0,00") {
                        $html .= '<tr>';
                        $html .= '<td align="center" width="10%">' . $dato411->cod_deno . '</td>';
                        $html .= '<td width="50%">' . $dato411->descripcion . '</td>';
                        $html .= '<td align="right" width="30%">' . number_format($resTotal411, 2, ',', '.') . '</td>';
                        $html .= '</tr>';
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="center" width="10%">41200</td>';
            $html .= '<td width="30%">INGRESOS NO OPERATIVOS</td>';
            $html .= '<td align="right" width="12%"></td>';
            $html .= '</tr>';

            $query412 = ContaDenominacion::where('subgrupo2', '412')->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
            foreach ($query412 as $key => $dato412) {
                $datoTotal412 = $dato412->fnTotalCuenta($fechaInicio, $fechaFinal, $dato412->cod_deno);
                $resTotal412 = (float)$datoTotal412->haber - (float)$datoTotal412->debe;
                $formatResTotal412 = number_format($resTotal412, 2, ',', '.');
                if ($datoTotal412->haber) {
                    if ($formatResTotal412 != "0,00") {
                        $html .= '<tr>';
                        $html .= '<td align="center" width="10%">' . $dato412->cod_deno . '</td>';
                        $html .= '<td width="50%">' . $dato412->descripcion . '</td>';
                        $html .= '<td align="right" width="30%">' . $formatResTotal412 . '</td>';
                        $html .= '</tr>';
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="center" width="10%">41300</td>';
            $html .= '<td width="30%">OTROS INGRESOS</td>';
            $html .= '<td align="right" width="12%"></td>';
            $html .= '</tr>';

            $query413 = ContaDenominacion::where('subgrupo2', '413')->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
            foreach ($query413 as $key => $dato413) {
                $datoTotal413 = $dato413->fnTotalCuenta($fechaInicio, $fechaFinal, $dato413->cod_deno);
                $resTotal413 = (float)$datoTotal413->haber - (float)$datoTotal413->debe;
                $formatResTotal413 = number_format($resTotal413, 2, ',', '.');
                if ($datoTotal413->haber) {
                    if ($formatResTotal413 != "0,00") {
                        $html .= '<tr>';
                        $html .= '<td align="center" width="10%">' . $dato413->cod_deno . '</td>';
                        $html .= '<td width="50%">' . $dato413->descripcion . '</td>';
                        $html .= '<td align="right" width="30%">' . $formatResTotal413 . '</td>';
                        $html .= '</tr>';
                    }
                }
            }

            /*EGRESOS*/
            $datoTotalEgreso = ContaDenominacion::fnTotalCuentaGeneral($fechaInicio, $fechaFinal, 5);
            $resTotalEgreso = (float)$datoTotalEgreso->debe - (float)$datoTotalEgreso->haber;
            $html .= '<tr bgcolor="#7f7c7c">';
            $html .= '<td align="center" width="10%">50000</td>';
            $html .= '<td width="50%">GASTOS</td>';
            $html .= '<td align="right" width="30%">' . number_format($resTotalEgreso, 2, ',', '.') . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td align="center" width="10%">51100</td>';
            $html .= '<td width="30%">costo de la explotacion del servicio</td>';
            $html .= '<td align="right" width="12%"></td>';
            $html .= '</tr>';

            $query511 = ContaDenominacion::where('subgrupo2', '511')->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
            foreach ($query511 as $key => $dato511) {
                $datoTotal511 = $dato511->fnTotalCuenta($fechaInicio, $fechaFinal, $dato511->cod_deno);
                $resTotal511 = (float)$datoTotal511->debe - (float)$datoTotal511->haber;
                $formatResTotal511 = number_format($resTotal511, 2, ',', '.');
                if ($datoTotal511->debe) {
                    if ($formatResTotal511 != "0,00") {
                        $html .= '<tr>';
                        $html .= '<td align="center" width="10%">' . $dato511->cod_deno . '</td>';
                        $html .= '<td width="50%">' . $dato511->descripcion . '</td>';
                        $html .= '<td align="right" width="30%">' . $formatResTotal511 . '</td>';
                        $html .= '</tr>';
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="center" width="10%">51200</td>';
            $html .= '<td width="30%">GASTO DE ADMINISTRACION</td>';
            $html .= '<td align="right" width="12%"></td>';
            $html .= '</tr>';

            $query512 = ContaDenominacion::where('subgrupo2', '512')->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
            foreach ($query512 as $key => $dato512) {
                $datoTotal512 = $dato512->fnTotalCuenta($fechaInicio, $fechaFinal, $dato512->cod_deno);
                $resTotal512 = (float)$datoTotal512->debe - (float)$datoTotal512->haber;
                $formatResTotal512 = number_format($resTotal512, 2, ',', '.');
                if ($datoTotal512->debe) {
                    if ($formatResTotal512 != "0,00") {
                        $html .= '<tr>';
                        $html .= '<td align="center" width="10%">' . $dato512->cod_deno . '</td>';
                        $html .= '<td width="50%">' . $dato512->descripcion . '</td>';
                        $html .= '<td align="right" width="30%">' . $formatResTotal512 . '</td>';
                        $html .= '</tr>';
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="center" width="10%">51300</td>';
            $html .= '<td width="30%">GASTOS DE VENTA</td>';
            $html .= '<td align="right" width="12%"></td>';
            $html .= '</tr>';

            $query513 = ContaDenominacion::where('subgrupo2', '513')->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
            foreach ($query513 as $key => $dato513) {
                $datoTotal513 = $dato513->fnTotalCuenta($fechaInicio, $fechaFinal, $dato513->cod_deno);
                $resTotal513 = (float)$datoTotal513->debe - (float)$datoTotal513->haber;
                $formatResTotal513 = number_format($resTotal513, 2, ',', '.');
                if ($datoTotal513->debe) {
                    if ($formatResTotal513 != "0,00") {
                        $html .= '<tr>';
                        $html .= '<td align="center" width="10%">' . $dato513->cod_deno . '</td>';
                        $html .= '<td width="50%">' . $dato513->descripcion . '</td>';
                        $html .= '<td align="right" width="30%">' . $formatResTotal513 . '</td>';
                        $html .= '</tr>';
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="center" width="10%">51400</td>';
            $html .= '<td width="30%">GASTOS FINANCIEROS</td>';
            $html .= '<td align="right" width="12%"></td>';
            $html .= '</tr>';

            $query514 = ContaDenominacion::where('subgrupo2', '514')->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
            foreach ($query514 as $key => $dato514) {
                $datoTotal514 = $dato514->fnTotalCuenta($fechaInicio, $fechaFinal, $dato514->cod_deno);
                $resTotal514 = (float)$datoTotal514->debe - (float)$datoTotal514->haber;
                $formatResTotal514 = number_format($resTotal514, 2, ',', '.');
                if ($datoTotal514->debe) {
                    if ($formatResTotal514 != "0,00") {
                        $html .= '<tr>';
                        $html .= '<td align="center" width="10%">' . $dato514->cod_deno . '</td>';
                        $html .= '<td width="50%">' . $dato514->descripcion . '</td>';
                        $html .= '<td align="right" width="30%">' . $formatResTotal514 . '</td>';
                        $html .= '</tr>';
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="center" width="10%">51500</td>';
            $html .= '<td width="30%">OTROS GASTOS</td>';
            $html .= '<td align="right" width="12%"></td>';
            $html .= '</tr>';

            $query515 = ContaDenominacion::where('subgrupo2', '515')->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
            foreach ($query515 as $key => $dato515) {
                $datoTotal515 = $dato515->fnTotalCuenta($fechaInicio, $fechaFinal, $dato515->cod_deno);
                $resTotal515 = (float)$datoTotal515->debe - (float)$datoTotal515->haber;
                $formatResTotal515 = number_format($resTotal515, 2, ',', '.');
                if ($datoTotal515->debe) {
                    if ($formatResTotal515 != "0,00") {
                        $html .= '<tr>';
                        $html .= '<td align="center" width="10%">' . $dato515->cod_deno . '</td>';
                        $html .= '<td width="50%">' . $dato515->descripcion . '</td>';
                        $html .= '<td align="right" width="30%">' . $formatResTotal515 . '</td>';
                        $html .= '</tr>';
                    }
                }
            }


            $html .= '<tr>';
            $html .= '<td align="center" width="10%">51600</td>';
            $html .= '<td width="30%">GASTOS DE OPERACIONES NOMINALES</td>';
            $html .= '<td align="right" width="12%"></td>';
            $html .= '</tr>';

            $query516 = ContaDenominacion::where('subgrupo2', '516')->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
            foreach ($query516 as $key => $dato516) {
                $datoTotal516 = $dato516->fnTotalCuenta($fechaInicio, $fechaFinal, $dato516->cod_deno);
                $resTotal516 = (float)$datoTotal516->debe - (float)$datoTotal516->haber;
                $formatResTotal516 = number_format($resTotal516, 2, ',', '.');
                if ($datoTotal516->debe) {
                    if ($formatResTotal516 != "0,00") {
                        $html .= '<tr>';
                        $html .= '<td align="center" width="10%">' . $dato516->cod_deno . '</td>';
                        $html .= '<td width="50%">' . $dato516->descripcion . '</td>';
                        $html .= '<td align="right" width="30%">' . $formatResTotal516 . '</td>';
                        $html .= '</tr>';
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="center" width="10%">59000</td>';
            $html .= '<td width="30%">IMPUESTOS A LAS UTILIDADES</td>';
            $html .= '<td align="right" width="12%"></td>';
            $html .= '</tr>';

            $query590 = ContaDenominacion::where('subgrupo2', '590')->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
            foreach ($query590 as $key => $dato590) {
                $datoTotal590 = $dato590->fnTotalCuenta($fechaInicio, $fechaFinal, $dato590->cod_deno);
                $resTotal590 = (float)$datoTotal590->debe - (float)$datoTotal590->haber;
                $formatResTotal590 = number_format($resTotal590, 2, ',', '.');
                if ($datoTotal590->debe) {
                    if ($formatResTotal590 != "0,00") {
                        $html .= '<tr>';
                        $html .= '<td align="center" width="10%">' . $dato590->cod_deno . '</td>';
                        $html .= '<td width="50%">' . $dato590->descripcion . '</td>';
                        $html .= '<td align="right" width="30%">' . $formatResTotal590 . '</td>';
                        $html .= '</tr>';
                    }
                }
            }

            $totalResultadoPeriodo = (float)$resTotalIngreso - (float)$resTotalEgreso;
            $html .= '<tr>';
            $html .= '<td align="center" width="10%"></td>';
            $html .= '<td width="50%">RESULTADO DEL PERIODO</td>';
            $html .= '<td align="right" width="30%">' . number_format($totalResultadoPeriodo, 2, ',', '.') . '</td>';
            $html .= '</tr>';
            $pdf::SetFont('helvetica', '', 8);
            $html .= '</tbody>';
            $html .= "</table>";

            $pdf::writeHTML($html, true, false, true, false, '');

            $pdf::lastPage();

            $pdf::Output('reporteEstadoResultados.pdf');
        }
    }

    public function imprimirBalanceGeneral($fechaF)
    {
        ini_set('max_execution_time', 3600);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        //dd($fechaI);
        if (Session::has('AUTENTICADO')) {
            $fechaI = ContaDiario::min('fecha_a');
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            $this->fechaInicialGlobal = $fechaI;
            $this->fechaFinalGlobal = $fechaF;

            $paperSize = 'LETTER';
            $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $paperSize, true, 'UTF-8', false);
            $pdf::setHeaderCallback(function ($pdf) {
                $now = Carbon::now('America/La_Paz');
                $pdf->SetFont('helvetica', '', 8);
                // Title
                //$this->Cell(0, 15, 'RENDICIÓN DE CUENTAS', 0, false, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->SetXY(10, 10);
                $pdf->Cell(0, 2, 'Fecha de Impresión: ' . date("d/m/Y"), 0, false, 'L', 0, '', 0, false, 'M', 'M');

                $pdf->SetFont('helvetica', 'B', 20);
                $pdf->SetXY(15, 20);
                $pdf->Cell(0, -15, 'BALANCE GENERAL', 0, false, 'C', 0, '', 0, false, 'M', 'M');

                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetXY(15, 25);
                $pdf->Cell(0, -15, 'Desde ' . $this->fechaInicialGlobal . ' Al ' . $this->fechaFinalGlobal . '', 0, false, 'C', 0, '', 0, false, 'M', 'M');

                $pdf->SetFont('helvetica', 'B,U', 8);
                $pdf->SetXY(15, 29);
                $pdf->Cell(0, -15, 'Moneda Local', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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

            $pdf::SetCreator(PDF_CREATOR);
            $pdf::SetAuthor('PRENDASOL');
            $pdf::SetTitle('BALANCE GENERAL');
            $pdf::SetSubject('BALANCE GENERAL');
            $pdf::SetKeywords('BALANCE GENERAL');

            // set default header data
            $pdf::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

            // set header and footer fonts
            $pdf::setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf::setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf::SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf::setLanguageArray($l);
            }

            $pdf::SetFont('helvetica', '', 8);
            // add a page
            $pdf::AddPage();

            $pdf::SetFont('helvetica', 'B', 15);
            $html = "";
            $html .= '<table border="0" cellspacing="0" cellpadding="3" width="100%">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th align="center" width="100%" colspan="6">_______________________________________________________________________________________________________________</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th align="center"  width="10%"><strong>CODIGO</strong></th>';
            $html .= '<th align="center"  width="30%"><strong>CUENTA</strong></th>';
            //$html .='<th align="center"  width="10%"><strong>NIVEL</strong></th>';
            $html .= '<th align="center"  width="12%"><strong></strong></th>';
            $html .= '<th align="center"  width="12%"><strong></strong></th>';
            $html .= '<th align="center"  width="12%"><strong></strong></th>';
            $html .= '<th align="center"  width="12%"><strong></strong></th>';
            $html .= '<th align="center"  width="12%"><strong></strong></th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th align="center" width="100%" colspan="6">_______________________________________________________________________________________________________________</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $totalActivo = 0;
            $totalPasivo = 0;
            $totalPatrimonio = 0;

            $html .= '<tr>';
            $html .= '<td align="left" width="15%">10000</td>';
            $html .= '<td width="50%">activo</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;11000</td>';
            $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;activo corriente</td>';
            $html .= '</tr>';

            /*ACTIVO*/
            $datosCabeceraActivoCorriente = ContaDeno::whereIn('cod_deno', [111, 112, 113, 114, 115, 116, 117])->get();
            foreach ($datosCabeceraActivoCorriente as $key => $datoAC) {

                $longitud = strlen($datoAC->cod_deno);
                if ($longitud == 3) {
                    $datoExisteCuenta = ContaDiario::whereBetween('cod_deno', [$datoAC->cod_deno . '01', $datoAC->cod_deno . '99'])
                        ->where('estado_id', 1)
                        ->count();
                    if ($datoExisteCuenta > 0) {
                        $html .= '<tr>';
                        $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoAC->cod_deno . '00</td>';
                        $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoAC->descripcion . '</td>';
                        $html .= '</tr>';
                        $datosDetalles = ContaDenominacion::where('subgrupo2', $datoAC->cod_deno)->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
                        foreach ($datosDetalles as $key => $datoDetalle) {
                            $datoTotalActivoCorriente = $datoDetalle->fnTotalCuenta($fechaInicio, $fechaFinal, $datoDetalle->cod_deno);
                            $resTotalActivoCorriente = (float)$datoTotalActivoCorriente->debe - (float)$datoTotalActivoCorriente->haber;
                            if ($resTotalActivoCorriente > 0 || $resTotalActivoCorriente < 0) {
                                $totalActivo = (float)$totalActivo + (float)$resTotalActivoCorriente;
                                $html .= '<tr>';
                                $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoDetalle->cod_deno . '</td>';
                                $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoDetalle->descripcion . '</td>';
                                $html .= '<td align="right" width="30%">' . number_format($resTotalActivoCorriente, 2, ',', '.') . '</td>';
                                $html .= '</tr>';
                            }
                        }
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;12000</td>';
            $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;activo no corriente</td>';
            $html .= '</tr>';

            $datosCabeceraActivoNoCorriente = ContaDeno::whereIn('cod_deno', [121, 122, 123, 124, 125])->get();
            foreach ($datosCabeceraActivoNoCorriente as $key => $datoANC) {

                $longitud = strlen($datoANC->cod_deno);
                if ($longitud == 3) {
                    $datoExisteCuenta = ContaDiario::whereBetween('cod_deno', [$datoANC->cod_deno . '01', $datoANC->cod_deno . '99'])
                        ->where('estado_id', 1)
                        ->count();
                    if ($datoExisteCuenta > 0) {
                        $html .= '<tr>';
                        $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoANC->cod_deno . '00</td>';
                        $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoANC->descripcion . '</td>';
                        $html .= '</tr>';
                        $datosDetalles = ContaDenominacion::where('subgrupo2', $datoANC->cod_deno)->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
                        foreach ($datosDetalles as $key => $datoDetalle) {
                            $datoTotalActivoCorriente = $datoDetalle->fnTotalCuenta($fechaInicio, $fechaFinal, $datoDetalle->cod_deno);
                            $resTotalActivoCorriente = (float)$datoTotalActivoCorriente->debe - (float)$datoTotalActivoCorriente->haber;
                            if ($resTotalActivoCorriente > 0 || $resTotalActivoCorriente < 0) {
                                $totalActivo = (float)$totalActivo + (float)$resTotalActivoCorriente;
                                $html .= '<tr>';
                                $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoDetalle->cod_deno . '</td>';
                                $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoDetalle->descripcion . '</td>';
                                $html .= '<td align="right" width="30%">' . number_format($resTotalActivoCorriente, 2, ',', '.') . '</td>';
                                $html .= '</tr>';
                            }
                        }
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="left" width="15%"></td>';
            $html .= '<td width="50%"><strong>TOTAL ACTIVO</strong></td>';
            $html .= '<td align="right" width="30%"><strong>' . number_format($totalActivo, 2, ',', '.') . '</strong></td>';
            $html .= '</tr>';


            /*PASIVO*/
            $html .= '<tr>';
            $html .= '<td align="left" width="15%">20000</td>';
            $html .= '<td width="50%">pasivo</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;21000</td>';
            $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;pasivo corriente</td>';
            $html .= '</tr>';

            $datosCabeceraPasivoCorriente = ContaDeno::whereIn('cod_deno', [211, 212, 213, 214, 215])->get();
            foreach ($datosCabeceraPasivoCorriente as $key => $datoPC) {
                $longitud = strlen($datoPC->cod_deno);
                if ($longitud == 3) {
                    $datoExisteCuenta = ContaDiario::whereBetween('cod_deno', [$datoPC->cod_deno . '01', $datoPC->cod_deno . '99'])
                        ->where('estado_id', 1)
                        ->count();
                    if ($datoExisteCuenta > 0) {
                        $html .= '<tr>';
                        $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoPC->cod_deno . '00</td>';
                        $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoPC->descripcion . '</td>';
                        $html .= '</tr>';
                        $datosDetalles = ContaDenominacion::where('subgrupo2', $datoPC->cod_deno)->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
                        foreach ($datosDetalles as $key => $datoDetalle) {
                            $datoTotalActivoCorriente = $datoDetalle->fnTotalCuenta($fechaInicio, $fechaFinal, $datoDetalle->cod_deno);
                            $resTotalActivoCorriente = (float)$datoTotalActivoCorriente->haber - (float)$datoTotalActivoCorriente->debe;
                            // print_r($datoDetalle->cod_deno ."--". $datoTotalActivoCorriente->debe . "--" . $datoTotalActivoCorriente->haber ."<br>");
                            // print_r($resTotalActivoCorriente ."<br>");
                            if ($resTotalActivoCorriente > 0 || $resTotalActivoCorriente < 0) {
                                $totalPasivo = (float)$totalPasivo + (float)$resTotalActivoCorriente;
                                $html .= '<tr>';
                                $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoDetalle->cod_deno . '</td>';
                                $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoDetalle->descripcion . '</td>';
                                $html .= '<td align="right" width="30%">' . number_format($resTotalActivoCorriente, 2, ',', '.') . '</td>';
                                $html .= '</tr>';
                            }
                        }
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;22000</td>';
            $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;pasivo no corriente</td>';
            $html .= '</tr>';

            $datosCabeceraPasivoNoCorriente = ContaDeno::whereIn('cod_deno', [221, 222])->get();
            foreach ($datosCabeceraPasivoNoCorriente as $key => $datoPNC) {
                $longitud = strlen($datoPNC->cod_deno);
                if ($longitud == 3) {
                    $datoExisteCuenta = ContaDiario::whereBetween('cod_deno', [$datoPNC->cod_deno . '01', $datoPNC->cod_deno . '99'])
                        ->where('estado_id', 1)
                        ->count();
                    if ($datoExisteCuenta > 0) {
                        $html .= '<tr>';
                        $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoPNC->cod_deno . '00</td>';
                        $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoPNC->descripcion . '</td>';
                        $html .= '</tr>';
                        $datosDetalles = ContaDenominacion::where('subgrupo2', $datoPNC->cod_deno)->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
                        foreach ($datosDetalles as $key => $datoDetalle) {
                            $datoTotalActivoCorriente = $datoDetalle->fnTotalCuenta($fechaInicio, $fechaFinal, $datoDetalle->cod_deno);
                            $resTotalActivoCorriente = (float)$datoTotalActivoCorriente->haber - (float)$datoTotalActivoCorriente->debe;

                            if ($resTotalActivoCorriente > 0) {
                                $totalPasivo = (float)$totalPasivo + (float)$resTotalActivoCorriente;
                                $html .= '<tr>';
                                $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoDetalle->cod_deno . '</td>';
                                $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoDetalle->descripcion . '</td>';
                                $html .= '<td align="right" width="30%">' . number_format($resTotalActivoCorriente, 2, ',', '.') . '</td>';
                                $html .= '</tr>';
                            }
                        }
                    }
                }
            }

            $html .= '<tr>';
            $html .= '<td align="left" width="15%"></td>';
            $html .= '<td width="50%"><strong>TOTAL PASIVO</strong></td>';
            $html .= '<td align="right" width="30%"><strong>' . number_format($totalPasivo, 2, ',', '.') . '</strong></td>';
            $html .= '</tr>';

            /*PATRIMONIO*/
            $html .= '<tr>';
            $html .= '<td align="left" width="15%">30000</td>';
            $html .= '<td width="50%">patrimonio</td>';
            $html .= '</tr>';

            $datoTotalIngreso = ContaDenominacion::fnTotalCuentaGeneral($fechaInicio, $fechaFinal, 4);
            $resTotalIngreso = (float)$datoTotalIngreso->haber - (float)$datoTotalIngreso->debe;

            $datoTotalEgreso = ContaDenominacion::fnTotalCuentaGeneral($fechaInicio, $fechaFinal, 5);
            $resTotalEgreso = (float)$datoTotalEgreso->debe - (float)$datoTotalEgreso->haber;

            $totalResultadoPeriodo = (float)$resTotalIngreso - (float)$resTotalEgreso;


            $datosCabeceraPatrimonio = ContaDeno::whereIn('cod_deno', [311, 312, 313])->get();
            foreach ($datosCabeceraPatrimonio as $key => $datoP) {
                $longitud = strlen($datoP->cod_deno);
                if ($longitud == 3) {
                    $datoExisteCuenta = ContaDiario::whereBetween('cod_deno', [$datoP->cod_deno . '01', $datoP->cod_deno . '99'])
                        ->where('estado_id', 1)
                        ->count();
                    if ($datoExisteCuenta > 0) {
                        $html .= '<tr>';
                        $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoP->cod_deno . '00</td>';
                        $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoP->descripcion . '</td>';
                        $html .= '</tr>';
                        $datosDetalles = ContaDenominacion::where('subgrupo2', $datoP->cod_deno)->where('estado_id', 1)->orderBy('numerocod', 'ASC')->get();
                        foreach ($datosDetalles as $key => $datoDetalle) {
                            $datoTotalActivoCorriente = $datoDetalle->fnTotalCuenta($fechaInicio, $fechaFinal, $datoDetalle->cod_deno);
                            $resTotalActivoCorriente = (float)$datoTotalActivoCorriente->haber - (float)$datoTotalActivoCorriente->debe;
                            //print_r($datoDetalle->cod_deno ."--". $datoTotalActivoCorriente->debe . "--" . $datoTotalActivoCorriente->haber ."<br>");
                            if ($resTotalActivoCorriente > 0 || $resTotalActivoCorriente < 0) {
                                $totalPatrimonio = (float)$totalPatrimonio + (float)$resTotalActivoCorriente;
                                $html .= '<tr>';
                                $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoDetalle->cod_deno . '</td>';
                                $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $datoDetalle->descripcion . '</td>';
                                $html .= '<td align="right" width="30%">' . number_format($resTotalActivoCorriente, 2, ',', '.') . '</td>';
                                $html .= '</tr>';
                            }
                        }
                    }
                }
            }



            $html .= '<tr>';
            $html .= '<td align="left" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;31320</td>';
            $html .= '<td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Resultado de la Gestión</td>';
            $html .= '<td align="right" width="30%">' . number_format($totalResultadoPeriodo, 2, ',', '.') . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td align="left" width="15%"></td>';
            $html .= '<td width="50%"><strong>TOTAL PATRIMONIO</strong></td>';
            $html .= '<td align="right" width="30%"><strong>' . number_format($totalPatrimonio, 2, ',', '.') . '</strong></td>';
            $html .= '</tr>';

            $totalPM = (float)$totalPasivo + (float)$totalPatrimonio + (float)$totalResultadoPeriodo;

            $html .= '<tr>';
            $html .= '<td align="left" width="15%"></td>';
            $html .= '<td width="50%"><strong>TOTAL PASIVO PATRIMONIO</strong></td>';
            $html .= '<td align="right" width="30%"><strong>' . number_format($totalPM, 2, ',', '.') . '</strong></td>';
            $html .= '</tr>';

            $pdf::SetFont('helvetica', '', 8);
            $html .= '</tbody>';
            $html .= "</table>";

            $pdf::writeHTML($html, true, false, true, false, '');

            $pdf::lastPage();

            $pdf::Output('reporteBalnaceGeneral.pdf');
        }
    }
}
