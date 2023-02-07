<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Carbon\Carbon;
use App\InicioFinCaja;
use App\CambioDolar;
use App\Sucursal;
use App\CompraVentaDolar;
use App\NumberToLetterConverter;
use PDF;

class CambioDolarController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $cambios = CambioDolar::where('sucursal_id', Session::get('ID_SUCURSAL'))->get();

            $datoInicioFinCaja =  InicioFinCaja::where('fecha', Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->where('sucursal_id', session::get('ID_SUCURSAL'))
                ->where('caja', session::get('CAJA'))
                ->where('estado_id', 1)
                ->first();

            $sucursal = Sucursal::find(Session::get('ID_SUCURSAL'));
            $sucursales = Sucursal::where('estado_id', 1)->get();
            $compra_venta = CompraVentaDolar::orderBy('created_at', 'asc')->get()->last();
            $nl = new NumberToLetterConverter();
            if ($request->ajax()) {
                $lista = view('cambios.parcial.lista', compact('cambios', 'compra_venta', 'nl'))->render();
                return response()->JSON($lista);
            }
            return view('cambios.index', compact('cambios', 'datoInicioFinCaja', 'sucursales', 'compra_venta', 'sucursal', 'nl'));
        } else {
            return view("layout.login", compact('sucursales'));
        }
    }

    public function store(Request $request)
    {
        $request['usuario_id'] = Session::get('ID_USUARIO');
        $compra_venta = CompraVentaDolar::orderBy('created_at', 'asc')->get()->last();
        CambioDolar::create(array_map('mb_strtoupper', [
            'sucursal_id' => $request->sucursal_id,
            'fecha' => Carbon::parse($request->fecha)->format('Y-m-d'),
            'cliente' => $request->txtCliente,
            'nit' => $request->txtNit,
            'usuario_id' => $request->usuario_id,
            'monto' => $request->txtMonto,
            'equivalencia' => $request->txtEquivalencia,
            'modo_cambio' => $request->txtModoCambio,
            'compra_venta_id' => $compra_venta->id
        ]));
        return response()->JSON([
            'sw' => true
        ]);
    }

    public function reporte()
    {
        $sucursales = Sucursal::where('estado_id', 1)->get();
        return view('cambios.reporte', compact('sucursales'));
    }

    public function cambio_pdf(CambioDolar $cambio)
    {
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
        $pdf::AddPage('P', 'A4', false, false);
        $pdf::SetXY(90, 30);
        $pdf::Cell($w = 0, $h = 0, $txt = 'PRENDASOL SRL', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(20, 40);
        $pdf::MultiCell(178, 15, 'CAMBIO DE ' . $cambio->modo_cambio, 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetFont('helvetica', 'n', 11);
        $pdf::SetXY(25, 65);
        $pdf::Cell(50, 0, 'SUCURSAL:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(60, 65);
        $pdf::Cell(210, 0, $cambio->sucursal->nombre, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(25, 75);
        $pdf::Cell(50, 0, 'FECHA:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(60, 75);
        $pdf::Cell(210, 0, $cambio->fecha, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(25, 85);
        $pdf::Cell(50, 0, 'SEÑOR(ES):', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(60, 85);
        $pdf::Cell(210, 0, $cambio->cliente, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(25, 95);
        $pdf::Cell(50, 0, 'CI/NIT:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(60, 95);
        $pdf::Cell(210, 0, $cambio->nit, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(25, 105);
        $pdf::Cell(50, 0, 'USUARIO:', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(60, 105);
        $pdf::Cell(210, 0, $cambio->usuario->usuario, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(20, 115);
        $pdf::MultiCell(60, 15, 'EFECTIVO', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');
        $pdf::SetXY(80, 115);
        $pdf::MultiCell(60, 15, 'COTIZACIÓN', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');
        $pdf::SetMargins(10, 25, 10);
        $pdf::SetXY(140, 115);
        $pdf::MultiCell(60, 15, 'EQUIVALENTE', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetFont('helvetica', 'n', 11);
        $pdf::SetXY(30, 140);
        $pdf::Cell(60, 0, $cambio->monto, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(90, 140);
        if ($cambio->modo_cambio == 'DÓLARES A BOLIVIANOS') {
            $pdf::Cell(60, 0, $cambio->compra_venta->venta_bs, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        } else {
            $pdf::Cell(60, 0, $cambio->compra_venta->compra_bs, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        }
        $pdf::SetXY(150, 140);
        $pdf::Cell(60, 0, $cambio->equivalencia, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $moneda = 'Dólares';
        if ($cambio->modo_cambio == 'DÓLARES A BOLIVIANOS') {
            $moneda = 'Bolivianos';
        }
        $nl = new NumberToLetterConverter();
        $pdf::SetXY(50, 155);
        $pdf::Cell(80, 0, $nl->numtoletras(\number_format($cambio->equivalencia, 2, '.', '')) . ' ' . $moneda, $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        PDF::SetTitle('Reporte de Comprobate');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Comprobate.pdf');
    }

    public function reporte_pdf(Request $request)
    {
        $sucursal  = $request->sucursal;
        $fecha_ini = Carbon::parse($request->fecha_ini)->format('Y-m-d');
        $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');

        $cambios = CambioDolar::where('sucursal_id', $sucursal)
            ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
            ->get();

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
        $pdf::Cell($w = 0, $h = 0, $txt = 'REPORTE DE CAMBIO A DÓLARES', $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(110, 40);
        $pdf::Cell($w = 0, $h = 5.5, $txt = 'FECHA DE EMISIÓN: ' . Carbon::now('America/La_Paz')->format('Y-m-d'), $border = 0, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, 45);
        $pdf::MultiCell(15, 15, 'Nº', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(30, 45);
        $pdf::MultiCell(45, 15, 'SUCURSAL', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(75, 45);
        $pdf::MultiCell(30, 15, 'FECHA', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(105, 45);
        $pdf::MultiCell(60, 15, 'SEÑOR(ES)', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(165, 45);
        $pdf::MultiCell(25, 15, 'CI/NIT', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(190, 45);
        $pdf::MultiCell(40, 15, 'USUARIO', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(230, 45);
        $pdf::MultiCell(30, 15, 'MONTO $us', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $pdf::SetXY(260, 45);
        $pdf::MultiCell(30, 15, 'EQUIVALENCIA Bs', 1, 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

        $posicion = 65.5;
        $suma_total = 0;
        $cont = 1;
        $pdf::SetFont('helvetica', 'N', 8);
        $h = 5.5;
        $suma1 = 0;
        $suma2 = 0;
        foreach ($cambios as $value) {
            $pdf::SetXY(15, $posicion);
            $pdf::Cell($w = 15, $h, $cont++, $border = 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(30, $posicion);
            $pdf::cell(45, $h, $value->sucursal->nombre, 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(75, $posicion);
            $pdf::cell(30, $h, $value->fecha, 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(105, $posicion);
            $pdf::cell(60, $h, $value->cliente, 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(165, $posicion);
            $pdf::cell(25, $h, $value->nit, 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $pdf::SetXY(190, $posicion);
            $pdf::cell(40, $h, $value->usuario->usuario, 1, $ln = 50, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $suma1 += (float)$value->monto;
            $pdf::SetXY(230, $posicion);
            $pdf::cell(30, $h, $value->monto, 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $suma2 += (float)$value->equivalencia;
            $pdf::SetXY(260, $posicion);
            $pdf::cell(30, $h, $value->equivalencia, 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

            $posicion += 5.5;
            if ($posicion >= 190) {
                $posicion = 20;
                // $pdf->SetXY($x, $y);
                $pdf::AddPage();
            }
        }
        $pdf::SetFont('helvetica', 'B', 11);
        $pdf::SetXY(15, $posicion);
        $pdf::Cell(215, $h, 'TOTALES', 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');

        $pdf::SetXY(230, $posicion);
        $pdf::Cell(30, $h, $suma1, 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');
        $pdf::SetXY(260, $posicion);
        $pdf::Cell(30, $h, $suma2, 1, $ln = 50, $align = 'R', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'B', $valign = 'B');


        $pdf::SetMargins(10, 25, 10);
        PDF::SetTitle('Reporte de Comprobate');
        //PDF::AddPage('L', 'A4');
        PDF::writeHTML("", true, false, true, false, '');
        PDF::Output('Comprobate.pdf');
    }
}
