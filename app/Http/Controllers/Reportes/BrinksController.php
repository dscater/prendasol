<?php

namespace App\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Contrato;
use App\Usuario;
use Carbon\Carbon;
use App\Sucursal;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BrinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Session::has('AUTENTICADO')) { 
            $sucursales = Sucursal::where('estado_id',1)->get();
            if (session::get('ID_ROL') == 1 || session::get('ID_ROL') == 3) {
                return view('brinks.index',compact('sucursales'));
            }
            else{
                return view("layout.login",compact('sucursales'));
            }           
            
        }else{
            return view("layout.login",compact('sucursales'));
        }
    }

    public function buscarContratosBrinks(Request $request)
    {
        $fechaI = $request['txtFechaInicio'];
        $fechaF = $request['txtFechaFin'];
        $sucursal = $request['ddlSucursal'];
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            $datosContratos = Contrato::whereBetween('fecha_contrato',[$fechaInicio,$fechaFinal])
                ->where('sucursal_id',$sucursal)
                ->where('estado_id',1)
                ->where('estado_pago','<>','Credito cancelado')
                ->whereNotNull('totalTasacion')
                ->orderBy('fecha_contrato','ASC')
                ->orderBy('sucursal_id','ASC')
                ->orderBy('codigo','ASC')
                ->orderBy('codigo_num','ASC')
                ->get();
            //dd($datosContratos);
            if ($datosContratos) {                
                if ($request->ajax()) {
                    //dd($actoVacunaciones);
                    return view('brinks.modals.listadoBrinks', ['datosContratos' => $datosContratos,'fechaI' => $fechaI,'fechaF' => $fechaF,'sucursal' => $sucursal])->render();
                } 
                return view('brinks.index',compact('datosContratos','fechaI','fechaF'));
            }            
        }else{
            return view("layout.login");
        }       
    }
    public function imprmirContratosBrinks($fechaI,$fechaF,$sucursal)
    {
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            $datosContratos = Contrato::whereBetween('fecha_contrato',[$fechaInicio,$fechaFinal])
                ->where('sucursal_id',$sucursal)
                ->where('estado_id',1)
                ->where('estado_pago','<>','Credito cancelado')
                ->whereNotNull('totalTasacion')
                ->orderBy('fecha_contrato','ASC')
                ->orderBy('sucursal_id','ASC')
                ->orderBy('codigo','ASC')
                ->orderBy('codigo_num','ASC')
                ->get();
            //dd($datosContratos);
            if ($datosContratos) { 
                $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);               
                $pdf::setHeaderCallback(function($pdf){ 
                    $now = Carbon::now('America/La_Paz');  
                    $pdf->SetFont('Helvetica', '', 7.5);
                    $pdf->Cell($w=0, $h=30, $txt=$now, $border=0, $ln=0, $align='R', $fill=false);

                    $pdf->SetFont('helvetica', 'B', 8);  

                    $pdf->SetXY(15, 20);
                    $pdf->Cell(0, -15, 'PRENDASOL S.R.L.', 0, false, 'L', 0, '', 0, false, 'M', 'M'); 
                    $pdf->SetXY(15, 25);
                    $pdf->Cell(0, -15, 'BRINKS', 0, false, 'L', 0, '', 0, false, 'M', 'M');  
                    $pdf->SetXY(15, 30);
                    $pdf->Cell(0, -15, 'EN FECHA ', 0, false, 'L', 0, '', 0, false, 'M', 'M'); 

                    
                });

                $pdf::setFooterCallback(function($pdf) {
                    $usuario = Usuario::where('id',session::get('ID_USUARIO'))->first();
                    // Position at 15 mm from bottom
                    $pdf->SetY(-15);
                    // Set font
                    $pdf->SetFont('helvetica', 'I', 5.6);
                    // Page number
                    $pdf->Cell(0, 10, 'Pagina '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

                    $pdf->SetFont('helvetica', 'I', 8);
                    // Page number
                    // $pdf->Cell(0, 10, 'Pagina '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

                    $pdf->SetY(-15);
                    $pdf->Cell(0, 10, $usuario->persona->nombreCompleto(), 0, false, 'L', 0, '', 0, false, 'M', 'M');  
                });

                $pdf::SetTitle('Reporte Brinks');
        
                $pdf::AddPage();
                $pdf::SetFont('Helvetica', '', 10); 
                $pdf::Ln(30);
                
                
                        
                //$pdf::SetMargins(10,50, 40);
                $pdf::SetMargins(10,40, 0);
                   
                $html='';
                $html.= '<table cellpadding="3" border="1">';
                $html.= '<thead>';               
                $html.= '<tr>'; 
                $html.= '<th width="30" align="center"><strong>#</strong></th>';
                $html.= '<th width="110" align="center"><strong>AGENCIA ORIGEN</strong></th>'; 
                $html.= '<th align="center"><strong>CODIGO DE CONTRATO</strong></th>'; 
                $html.= '<th align="center"><strong>PESO BRUTO</strong></th>'; 
                $html.= '<th align="center"><strong>PESO NETO</strong></th>'; 
                $html.= '<th align="center"><strong>VALOR DE TASACION</strong></th>'; 
                $html.= '<th align="center"><strong>FECHA DE INGRESO</strong></th>'; 
                $html.= '</tr>';        
                $html.= '</thead>';
                $html.= '<tbody>';
                
                
                $i = 0;
                foreach ($datosContratos as $key => $contrato) {
                    $i =$i + 1;
                    if($i % 2 == 0){
                        $color ='#f2f2f2';
                    }
                    else{
                        $color ='#ffffff';
                    }
                    $html.= '<tr nobr="true" bgcolor="'.$color.'">'; 
                    $html.= '<td width="30" align="center">'. $i .'</td>';
                    $html.= '<td width="110" align="center">'. $contrato->sucural->nombre  .'</td>'; 
                    if ($contrato->codigo != "") {
                        $html.= '<td align="center">'. $contrato->codigo .'</td>'; 
                    }
                    else{
                        $rescodigo = $contrato->sucural->nuevo_codigo .''. Carbon::parse($contrato->fecha_contrato)->format('y') .''. $contrato->codigo_num;
                        $html.= '<td align="center">'. $rescodigo .'</td>'; 
                    }
                    
                    $html.= '<td align="center">'. $contrato->peso_total .'</td>'; 
                    $html.= '<td align="center">'. $contrato->totalPesoNeto($contrato->id) .'</td>'; 
                    $html.= '<td align="center">'. number_format($contrato->totalTasacion, 2, ',', '.') .'</td>'; 
                    $html.= '<td align="center">'. $contrato->fecha_contrato .'</td>'; 
                    $html.= '</tr>'; 
                }
                
                
                $html.= '</tbody>';          
                $html.= '</table>'; 



                $pdf::writeHTML($html, true, false, true, false, '');

                $pdf::lastPage();
                
                $pdf::Output('reporteBrinks.pdf');
            }            
        }else{
            return view("layout.login");
        }  
    }

    public function exportarContratosBrinks($fechaI,$fechaF,$sucursal)
    {
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            $datosContratos = Contrato::whereBetween('fecha_contrato',[$fechaInicio,$fechaFinal])
                ->where('sucursal_id',$sucursal)
                ->where('estado_id',1)
                ->where('estado_pago','<>','Credito cancelado')
                ->whereNotNull('totalTasacion')
                ->orderBy('fecha_contrato','ASC')
                ->orderBy('sucursal_id','ASC')
                ->orderBy('codigo','ASC')
                ->orderBy('codigo_num','ASC')
                ->get();
            //dd($datosContratos);
            if ($datosContratos) { 
                $documento = new Spreadsheet();
                /*FIRMA DE DOCUMENTO*/
                $documento
                    ->getProperties()
                    ->setCreator("PRENDASOL")
                    ->setLastModifiedBy('admin') // última vez modificado por
                    ->setTitle('Reporte Brinks')
                    ->setSubject('Reporte')
                    ->setDescription('generado por Admin')
                    ->setKeywords('etiquetas o palabras clave separadas por espacios')
                    ->setCategory('Reporte');

                /**/
                $hoja = $documento->getActiveSheet();
                /*NOMBRE DE LA HOJA*/
                $hoja->setTitle('Brinks');
                
                /*ARMAMOS TITULO DE LOS CAMPOS*/
                $hoja->setCellValue('A1', "#");
                $hoja->setCellValue('B1', "AGENCIA ORIGEN");
                $hoja->setCellValue('C1', "CODIGO DE CONTRATO");
                $hoja->setCellValue('D1', "PESO BRUTO");
                $hoja->setCellValue('E1', "PESO NETO");
                $hoja->setCellValue('F1', "VALOR DE TASACION");
                $hoja->setCellValue('G1', "FECHA DE INGRESO");

                $i = 2;
                foreach ($datosContratos as $key => $contrato) {
                    $hoja->setCellValue('A' . $i, $key+1);
                    $hoja->setCellValue('B' . $i, $contrato->sucural->nombre);
                    if ($contrato->codigo != "") {
                        //$html.= '<td align="center">'. $contrato->codigo .'</td>'; 
                        $hoja->setCellValue('C' . $i, $contrato->codigo);
                    }
                    else{
                        $rescodigo = $contrato->sucural->nuevo_codigo .''. Carbon::parse($contrato->fecha_contrato)->format('y') .''. $contrato->codigo_num;
                        //$html.= '<td align="center">'. $rescodigo .'</td>'; 
                        $hoja->setCellValue('C' . $i, $rescodigo);
                    }
                   
                    $hoja->setCellValue('D' . $i, $contrato->peso_total);
                    $hoja->setCellValue('E' . $i, $contrato->totalPesoNeto($contrato->id));
                    $hoja->setCellValue('F' . $i, number_format($contrato->totalTasacion, 2, ',', '.'));
                    $hoja->setCellValue('G' . $i, $contrato->fecha_contrato);
                    $i++;
                }

                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, "Xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="brinks"'. Carbon::parse($fechaI)->format('dmY') .'_'.Carbon::parse($fechaF)->format('dmY').'.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save("php://output");
                
            }            
        }else{
            return view("layout.login");
        } 

    }

}
