<?php

namespace App\Http\Controllers;

use App\Contrato;
use Illuminate\Http\Request;
use App\SolicitudRetiro;
use App\Sucursal;
use Carbon\Carbon;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SolicitudRetiroController extends Controller
{
    public function index()
    {
        return view('solicitud_retiros.reporte');
    }

    public function store(Request $request)
    {
        $nueva_solicitud = new SolicitudRetiro();
        $nueva_solicitud->contrato_id = $request->contrato_id;
        if ($request->sucursal_id != 'RENOVACION') {
            $nueva_solicitud->sucursal_id = $request->sucursal_id;
            $nueva_solicitud->estado = 'ENVIADO';
        } else {
            $nueva_solicitud->estado = 'RENOVACION';
            $nueva_solicitud->sucursal_id = 0;
        }
        $nueva_solicitud->observaciones = mb_strtoupper($request->observaciones);
        $nueva_solicitud->fecha_solicitud = Carbon::now('America/La_Paz')->format('Y-m-d');
        $nueva_solicitud->save();

        return response()->JSON([
            'sw' => true,
        ]);
    }

    public function reporte_pdf(Request $request)
    {
        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf::setHeaderCallback(function ($pdf) {
            $now = Carbon::now('America/La_Paz')->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            //$pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);
        });

        $pdf::setPrintHeader(false);
        $pdf::setPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);

        $pdf::SetFont('helvetica', 'B', 15);

        $pdf::AddPage('L', 'A4', false, false);
        //$pdf::Cell(0, 12, 'First Page', 1, 1, 'C');
        //$pdf::Cell($w=0, $h=30, $txt="aloooooo", $border=0, $ln=0, $align='R', $fill=false); 

        $pdf::SetXY(90, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'REPORTE DE SOLICITUDES DE RETIRO DE JOYAS', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 10);
        $pdf::SetXY(20, 50);
        $pdf::Cell($w = 10, $h = 5.5, 'Nº', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(30, 50);
        $pdf::Cell($w = 50, $h = 5.5, 'AGENCIA SOLICITANTE', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(80, 50);
        $pdf::Cell($w = 50, $h = 5.5, 'AGENCIA ORIGEN', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(130, 50);
        $pdf::Cell($w = 50, $h = 5.5, 'CÓDIGO DE CONTRATO', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(180, 50);
        $pdf::Cell($w = 25, $h = 5.5, 'PESO BRUTO', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(205, 50);
        $pdf::Cell($w = 40, $h = 5.5, 'FECHA DE SOLICITUD', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(245, 50);
        $pdf::Cell($w = 40, $h = 5.5, 'VALOR DE TASACIÓN', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $solicitudes = SolicitudRetiro::whereBetween('fecha_solicitud', [date('Y-m-d', strtotime($request->fecha_ini)), date('Y-m-d', strtotime($request->fecha_fin))])->get();
        $posicion_y = 55.5;
        $pdf::SetFont('helvetica', 'N', 10);
        $suma_total = 0;
        $cont = 1;
        foreach ($solicitudes as $solicitud) {
            $pdf::SetXY(20, $posicion_y);
            $pdf::Cell($w = 10, $h = 5.5, $cont++, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(30, $posicion_y);
            if ($solicitud->sucursal) {
                $pdf::Cell($w = 50, $h = 5.5, $solicitud->sucursal->nombre, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            } else {
                $pdf::Cell($w = 50, $h = 5.5, '>>RENOVACIÓN<<', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            }
            $pdf::SetXY(80, $posicion_y);
            $pdf::Cell($w = 50, $h = 5.5, $solicitud->contrato->sucural->nombre, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(130, $posicion_y);
            $pdf::Cell($w = 50, $h = 5.5, $solicitud->contrato->codigo, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(180, $posicion_y);
            $pdf::Cell($w = 25, $h = 5.5, $solicitud->contrato->peso_total, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(205, $posicion_y);
            $pdf::Cell($w = 40, $h = 5.5, $solicitud->fecha_solicitud, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(245, $posicion_y);
            $pdf::Cell($w = 40, $h = 5.5, $solicitud->contrato->totalTasacion, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $suma_total += $solicitud->contrato->totalTasacion;
            $posicion_y += 5.5;

            if ($posicion_y >= 190) {
                $posicion_y = 20;
                $pdf::AddPage();
            }
        }

        $pdf::SetFont('helvetica', 'B', 12);
        $pdf::SetXY(205, $posicion_y);
        $pdf::Cell($w = 40, $h = 5.5, 'TOTAL', $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(245, $posicion_y);
        $pdf::Cell($w = 40, $h = 5.5, $suma_total, $border = 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Comprobate');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Comprobate.pdf');
    }

    public function reporte_excel(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("PrendaSol")
            ->setLastModifiedBy('Administración')
            ->setTitle('Reporte de Solicitudes')
            ->setSubject('Solicitudes de Retiro')
            ->setDescription('Excel donde muestra las solicitudes de retiro de prendas')
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
        $solicitudes = SolicitudRetiro::whereBetween('fecha_solicitud', [date('Y-m-d', strtotime($request->fecha_ini)), date('Y-m-d', strtotime($request->fecha_fin))])->get();

        $sheet->setCellValue('B1', 'REPORTE DE SOLICITUDES DE RETIRO DE JOYAS');
        $sheet->mergeCells("B1:H1");  //COMBINAR CELDAS
        // ENCABEZADO
        $sheet->setCellValue('B2', 'Nº');
        $sheet->setCellValue('C2', 'AGENCIA SOLICITANTE');
        $sheet->setCellValue('D2', 'AGENCIA ORIGEN');
        $sheet->setCellValue('E2', 'CÓDIGO DE CONTRATO');
        $sheet->setCellValue('F2', 'PESO BRUTO');
        $sheet->setCellValue('G2', 'FECHA DE SOLICITUD');
        $sheet->setCellValue('H2', 'VALOR DE TASACIÓN');

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
        $sheet->getStyle('B2:H2')->applyFromArray($styleArray);

        // RECORRER LOS REGISTROS
        $nro_fila = 3;
        $cont = 1;
        $suma_total = 0;
        foreach ($solicitudes as $solicitud) {
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle('B' . $nro_fila . ':H' . $nro_fila)->applyFromArray($styleArray);

            $sheet->setCellValue('B' . $nro_fila, $cont++);
            if ($solicitud->sucursal) {
                $sheet->setCellValue('C' . $nro_fila, $solicitud->sucursal->nombre);
            } else {
                $sheet->setCellValue('C' . $nro_fila, '>>RENOVACIÓN<<');
            }
            $sheet->setCellValue('D' . $nro_fila, $solicitud->contrato->sucural->nombre);
            $sheet->setCellValue('E' . $nro_fila, $solicitud->contrato->codigo);
            $sheet->setCellValue('F' . $nro_fila, $solicitud->contrato->peso_total);
            $sheet->setCellValue('G' . $nro_fila, $solicitud->fecha_solicitud);
            $sheet->setCellValue('H' . $nro_fila, $solicitud->contrato->totalTasacion);
            $suma_total += $solicitud->contrato->totalTasacion;
            $nro_fila++;
        }

        $sheet->setCellValue('G' . $nro_fila, 'TOTAL');
        $sheet->setCellValue('H' . $nro_fila, $suma_total);
        $sheet->getStyle('B' . $nro_fila . ':H' . $nro_fila)->applyFromArray($styleArray);

        // AJUSTAR EL ANCHO DE LAS CELDAS
        foreach (range('B', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        // DESCARGA DEL ARCHIVO
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteSolicitudes.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function retiros_pendientes()
    {
        $sucursales = Sucursal::where('estado_id', 1)->get();
        return view('solicitud_retiros.retiros_pendientes', compact('sucursales'));
    }

    public function retiros_pendientes_pdf(Request $request)
    {
        $sucursal  = $request->sucursal;
        $fecha_ini = Carbon::parse($request->fecha_ini)->format('Y-m-d');
        $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');

        $contratos = Contrato::whereBetween('fecha_contrato', [$fecha_ini, $fecha_fin])
            ->where('sucursal_id', $sucursal)
            ->where('estado_pago', 'Credito cancelado')
            ->where('estado_entrega', 'Prenda en custodia')
            ->orderBy('id', 'ASC')
            ->get();
        $o_sucursal = Sucursal::find($sucursal);

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
        $pdf::SetXY(1, 30);
        $pdf::Cell($w = 300, $h = 0, 'REPORTE RETIRO PENDIENTE DE JOYAS O PRENDAS', $border = 0, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(1, 35);
        $pdf::Cell($w = 300, $h = 0, $o_sucursal->nombre, $border = 0, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(1, 40);
        $pdf::Cell($w =  300, $h = 5.5, $txt = 'FECHA DE EMISIÓN: ' . Carbon::now('America/La_Paz')->format('Y-m-d'), $border = 0, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $posicion = 50.5;

        $suma1 = 0;
        $suma2 = 0;
        $h = 5.5;

        foreach ($contratos as $value) {
            $pdf::SetFont('helvetica', 'B', 11);
            $pdf::SetXY(15, $posicion);
            $pdf::Cell($w = 45, $h, 'CÓDIGO: ' . $value->codigo, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(60, $posicion);
            $pdf::Cell($w = 120, $h, 'CLIENTE: ' . $value->cliente->persona->nombres . ' ' . $value->cliente->persona->primerapellido . ' ' . $value->cliente->persona->segundoapellido, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(180, $posicion);
            $pdf::Cell($w = 30, $h, 'FECHA CONTRATO: ' . $value->fecha_contrato, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(250, $posicion);
            $pdf::Cell($w = 30, $h, 'PESO TOTAL: ' . $value->peso_total, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetFont('helvetica', 'B', 13);
            $pdf::SetXY(130, $posicion);
            $pdf::MultiCell(60, 15, 'DETALLE', 0, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');
            $posicion += 20.5;
            $pdf::SetFont('helvetica', 'B', 8);
            $pdf::SetXY(15, $posicion);
            $pdf::Cell($w = 15, $h, 'NRO.', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(30, $posicion);
            $pdf::Cell($w = 20, $h, 'CANTIDAD', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(50, $posicion);
            $pdf::Cell($w = 180, $h, 'DESCRIPCIÓN', $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(230, $posicion);
            $pdf::Cell($w = 20, $h, 'PESO BRUTO', $border = 1, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(250, $posicion);
            $pdf::Cell($w = 10, $h, '10K', $border = 1, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(260, $posicion);
            $pdf::Cell($w = 10, $h, '14K', $border = 1, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(270, $posicion);
            $pdf::Cell($w = 10, $h, '18K', $border = 1, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $pdf::SetXY(280, $posicion);
            $pdf::Cell($w = 10, $h, '24K', $border = 1, $ln = 50, $align = 'C', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $cont = 1;
            foreach ($value->detalle as $detalle) {
                $h = 8;
                $pdf::SetFont('helvetica', 'N', 8);
                $pdf::SetXY(15, $posicion);
                $pdf::MultiCell(15, $h, $cont++, 1, '', 0, 0, '', '', true, 0, false, true, $h, 'M');
                $pdf::SetXY(30, $posicion);
                $pdf::MultiCell(20, $h, $detalle->cantidad, 1, '', 0, 0, '', '', true, 0, false, true, $h, 'M');
                $pdf::SetXY(50, $posicion);
                $pdf::MultiCell(180, $h, $detalle->descripcion, 1, '', 0, 0, '', '', true, 0, false, true, $h, 'M');
                $pdf::SetXY(230, $posicion);
                $pdf::MultiCell(20, $h, $detalle->peso, 1, '', 0, 0, '', '', true, 0, false, true, $h, 'M');
                $pdf::SetXY(250, $posicion);
                $pdf::MultiCell(10, $h, $detalle->pdieseso, 1, 'C', 0, 0, '', '', true, 0, false, true, $h, 'M');
                $pdf::SetXY(260, $posicion);
                $pdf::MultiCell(10, $h, $detalle->catorce, 1, 'C', 0, 0, '', '', true, 0, false, true, $h, 'M');
                $pdf::SetXY(270, $posicion);
                $pdf::MultiCell(10, $h, $detalle->dieciocho, 1, 'C', 0, 0, '', '', true, 0, false, true, $h, 'M');
                $pdf::SetXY(280, $posicion);
                $pdf::MultiCell(10, $h, $detalle->veinticuatro, 1, 'C', 0, 0, '', '', true, 0, false, true, $h, 'M');
                $posicion += 8;
                if ($posicion >= 185) {
                    $posicion = 20;
                    // $pdf->SetXY($x, $y);
                    $pdf::AddPage();
                }
            }
            $posicion += 5;
            $pdf::SetXY(1, $posicion);
            $pdf::SetFont('helvetica', 'B', 18);
            $pdf::Cell($w = 400, $h, '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
            $posicion += 12;
            if ($posicion >= 185) {
                $posicion = 20;
                // $pdf->SetXY($x, $y);
                $pdf::AddPage();
            }
        }

        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Retiros Pendientes');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('RetirosPendientes_' . time() . '.pdf');
    }
}
