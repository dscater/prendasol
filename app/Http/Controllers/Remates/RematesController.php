<?php

namespace App\Http\Controllers\Remates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contrato;
use Session;
use Carbon\Carbon;
use PDF;
use App\Sucursal;
use App\CambioMoneda;

class RematesController extends Controller
{
    private $opcionGeneral;

    public function __construct()
    {
        $this->opcionGeneral = "";
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
                return view('remates.index');
            } else {
                return view("layout.login", compact('sucursales'));
            }
            //$sucursales = Sucursal::where('estado_id',1)->get();
            //$datosContaDiario = ContaDiario::where('tcom','EGRESO1')->where('ref','T126')->get();


        } else {
            return view("layout.login", compact('sucursales'));
        }
    }

    public function buscarRemates(Request $request)
    {
        $filtro = $request->filtro;
        $fecha_ini = date('Y-m-d', strtotime($request->fecha_ini));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));

        $fechaActualInicial = Carbon::now();
        $fechaActualFinal = Carbon::now();
        //$fActual = $fechaActual->format('Y-m-d');
        //$endDate = $fechaActual->subMonth(2);
        //dd($endDate->format('Y-m-d'));
        if (Session::has('AUTENTICADO')) {
            $contratos = Contrato::where('estado_id', 1)
                ->whereIN('estado_pago', ['interes igual', 'amortizacion', 'DESEMBOLSO DE CREDITO', ''])
                ->where('fecha_fin', $fecha_ini)
                ->orderBy('fecha_fin', 'DESC')
                ->orderBy('capital', 'ASC')
                ->get();
            if ($filtro != 'diario') {
                $contratos = Contrato::where('estado_id', 1)
                    ->whereIN('estado_pago', ['interes igual', 'amortizacion', 'DESEMBOLSO DE CREDITO', ''])
                    ->whereBetween('fecha_fin', [$fecha_ini, $fecha_fin])
                    ->orderBy('fecha_fin', 'DESC')
                    ->orderBy('capital', 'ASC')
                    ->get();
            }

            if ($contratos) {
                if ($request->ajax()) {
                    //dd($actoVacunaciones);
                    $cambio = CambioMoneda::first();
                    return view('remates.modals.listadoRemates', ['contratos' => $contratos, 'cambio' => $cambio])->render();
                }
                return view('remates.index', compact('contratos'));
                //return view('contabilidad.contaDiario.index',compact('datosContaDiario'));                           
            }
        } else {
            return view("layout.login");
        }
    }

    public function imprimirRemate(Request $request)
    {
        $filtro = $request->filtro;
        $fecha_ini = date('Y-m-d', strtotime($request->fecha_ini));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->request = $request;

        $pdf::setHeaderCallback(function ($pdf) {
            $filtro = $this->request->filtro;
            $fecha_ini = date('Y-m-d', strtotime($this->request->fecha_ini));
            $fecha_fin = date('Y-m-d', strtotime($this->request->fecha_fin));
            Carbon::setLocale('es');
            $now = Carbon::now()->format('d/m/Y');
            $pdf->SetFont('Helvetica', '', 8.5);
            $pdf->Cell($w = 0, $h = 30, $txt = $now, $border = 0, $ln = 0, $align = 'R', $fill = false);

            $pdf->Ln(3);
            $pdf->SetFont('Helvetica', 'B', 11);
            $table = '<table cellpadding="3">';
            $table .= '<tr>';
            $table .= '<th>REMATE</th>';
            $table .= '</tr>';
            $table .= '<tr>';
            if ($filtro != 'diario') {
                $table .= '<th>' . date('d-m-Y',strtotime($fecha_ini)) . ' AL ' . date('d-m-Y',strtotime($fecha_fin)) . '  </th>';
            }
            else{
                $table .= '<th>' . date('d-m-Y',strtotime($fecha_ini)) . '  </th>';
            }
            $table .= '</tr>';
            $table .= '</table>';
            $pdf->writeHTMLCell($w = '', $h = '', $x = '', $y = '15', $html = $table, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
        });

        $pdf::setFooterCallback(function ($pdf) {
            // Position at 15 mm from bottom
            $pdf->SetY(-15);
            // Set font
            $pdf->SetFont('helvetica', 'I', 5.6);
            // Page number
            $pdf->Cell(0, 10, 'Pagina ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });
        //$pdf::Header();

        $pdf::SetTitle('Reporte Remates');

        //$pdf::AddPage('L', 'A4');
        $pdf::AddPage();
        $pdf::Ln(20);

        $pdf::SetFont('Helvetica', '', 6.6);
        //$pdf::SetMargins(10,50, 40);
        $pdf::SetMargins(10, 30, 0);

        $html = '';
        $html .= '<table cellpadding="3">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th colspan="24">---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</th>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th width="20" align="center"><strong>N</strong></th>';
        $html .= '<th width="40" align="center"><strong>CI</strong></th>';
        $html .= '<th width="100" align="center"><strong>Nombre Completo</strong></th>';
        $html .= '<th width="47" align="center"><strong>Sucursal</strong></th>';
        $html .= '<th width="40" align="center"><strong>Fecha</strong></th>';
        $html .= '<th width="30" align="center"><strong>Caja</strong></th>';
        $html .= '<th width="50" align="center"><strong>Contrato</strong></th>';
        $html .= '<th width="60" align="center"><strong>Capital</strong></th>';
        $html .= '<th width="30" align="center"><strong>Dias Atraso</strong></th>';
        $html .= '<th width="35" align="center"><strong>Importe Interes</strong></th>';
        $html .= '<th width="35" align="center"><strong>Importe Mora</strong></th>';
        $html .= '<th width="50" align="center"><strong>Telefono</strong></th>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th colspan="24">---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        $fechaActualInicial = Carbon::now();
        $fechaActualFinal = Carbon::now();
        $contratos = Contrato::where('estado_id', 1)
            ->whereIN('estado_pago', ['interes igual', 'amortizacion', 'DESEMBOLSO DE CREDITO', ''])
            ->where('fecha_fin', $fecha_ini)
            ->orderBy('fecha_fin', 'DESC')
            ->orderBy('capital', 'ASC')
            ->get();
        if ($filtro != 'diario') {
            $contratos = Contrato::where('estado_id', 1)
                ->whereIN('estado_pago', ['interes igual', 'amortizacion', 'DESEMBOLSO DE CREDITO', ''])
                ->whereBetween('fecha_fin', [$fecha_ini, $fecha_fin])
                ->orderBy('fecha_fin', 'DESC')
                ->orderBy('capital', 'ASC')
                ->get();
        }

        $i = 0;
        $totalInteres = 0;
        $totalMorosidad = 0;
        foreach ($contratos as $key => $contrato) {
            $i = $i + 1;
            if ($i % 2 == 0) {
                $color = '#f2f2f2';
            } else {
                $color = '#ffffff';
            }

            $date = Carbon::parse($contrato->fecha_fin);
            $now =  Carbon::now();
            $diff = $date->diffInDays($now);
            $diaActual = 30;
            $cambio = CambioMoneda::first();
            $capital = $contrato->capital;
            if ($contrato->moneda_id == 2) {
                $capital = $contrato->capital * $cambio->valor_bs;
            }

            if ((float)$capital <= 3499) {
                $totalInteresValor = ($capital * 10.4) / 100;
            } else {
                $totalInteresValor = ($capital * 7.4) / 100;
            }
            $totalMora = ((float)$totalInteresValor / $diaActual) * $diff;
            $totalInteres = $totalInteres + $totalInteresValor;
            $totalMorosidad = $totalMorosidad + $totalMora;

            $html .= '<tr nobr="true" bgcolor="' . $color . '">';
            $html .= '<td width="20" align="center">' . $i . '</td>';
            $html .= '<td width="40">' . $contrato->cliente->persona->nrodocumento . '</td>';
            $html .= '<td width="100">' . $contrato->cliente->persona->nombreCompleto() . '</td>';
            if ($contrato->sucural->nombre) {
                $html .= '<td width="47">' . $contrato->sucural->nombre . '</td>';
            } else {
                $html .= '<td width="47"></td>';
            }
            $html .= '<td width="40">' . Carbon::parse($contrato->fecha_fin)->format('d-m-Y') . '</td>';
            $html .= '<td width="30">' . $contrato->caja . '</td>';

            if ($contrato->codigo != "") {
                $html .= '<td width="50">' . $contrato->codigo . '</td>';
            } else {
                $rescodigo = $contrato->sucural->nuevo_codigo . '' . Carbon::parse($contrato->fecha_contrato)->format('y') . '' . $contrato->codigo_num;
                $html .= '<td width="50">' . $rescodigo . '</td>';
            }


            $html .= '<td width="60">' . $contrato->capital . '</td>';
            $html .= '<td width="30">' . $diff . '</td>';
            $html .= '<td width="35">' . number_format($totalInteresValor, 2, ',', '.') . '</td>';
            $html .= '<td width="35">' . number_format($totalMora, 2, ',', '.') . '</td>';
            $html .= '<td width="50">' . $contrato->cliente->persona->celular . '</td>';
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= '<td width="20"></td>';
        $html .= '<td width="40"></td>';
        $html .= '<td width="100"></td>';
        $html .= '<td width="40"></td>';
        $html .= '<td width="40"></td>';
        $html .= '<td width="30"></td>';
        $html .= '<td width="50"></td>';
        $html .= '<td width="60"></td>';
        $html .= '<td width="30"></td>';
        $html .= '<td width="40"><strong>' . number_format($totalInteres, 2, ',', '.') . '</strong></td>';
        $html .= '<td width="40"><strong>' . number_format($totalMorosidad, 2, ',', '.') . '</strong></td>';
        $html .= '<td width="20"></td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';

        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::lastPage();

        $pdf::Output('remate.pdf');
    }
}
