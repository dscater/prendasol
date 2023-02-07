<?php

namespace App\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Pagos;
use App\Sucursal;
use App\Usuario;
use Carbon\Carbon;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;


class HistorialPagosController extends Controller
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
                return view('reporteHistorialPagos.index',compact('sucursales'));
            }
            else{
                return view("layout.login",compact('sucursales'));
            }
            
        }else{
            return view("layout.login");
        }
    }

    public function exportarExcelHistorialPagos($fechaI,$fechaF)
    { 
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            //$datosContaDiario = ContaDiario::whereBetween('fecha_a',[$fechaI,$fechaF])->get();
            $datosPagos = Pagos::whereBetween('fecha_inio',[$fechaInicio,$fechaFinal])
                ->where('estado_id',1)
                ->orderBy('contrato_id','ASC')
                ->orderBy('fecha_inio','ASC')                
                ->get();
            
            $documento = new Spreadsheet();
            /*FIRMA DE DOCUMENTO*/
            $documento
                ->getProperties()
                ->setCreator("PRENDASOL")
                ->setLastModifiedBy('admin') // última vez modificado por
                ->setTitle('Reporte Conta Diario')
                ->setSubject('Reporte')
                ->setDescription('generado por Admin')
                ->setKeywords('etiquetas o palabras clave separadas por espacios')
                ->setCategory('Reporte');

            /**/
            $hoja = $documento->getActiveSheet();
            /*NOMBRE DE LA HOJA*/
            $hoja->setTitle('Historial Pagos');
            
            /*ARMAMOS TITULO DE LOS CAMPOS*/
            $hoja->setCellValue('A1', "#");
            $hoja->setCellValue('B1', "CI");
            $hoja->setCellValue('C1', "Nombre Completo");
            $hoja->setCellValue('D1', "Sucursal");
            $hoja->setCellValue('E1', "Fecha");
            $hoja->setCellValue('F1', "Caja");
            $hoja->setCellValue('G1', "Contrato");
            $hoja->setCellValue('H1', "Capital Anterior");
            $hoja->setCellValue('I1', "Cuota Mora");
            $hoja->setCellValue('J1', "Capital");
            $hoja->setCellValue('K1', "Interes");
            $hoja->setCellValue('L1', "Comisón");
            $hoja->setCellValue('M1', "Total Capital");
            $hoja->setCellValue('N1', "Estado");

            $i = 2;
            $j = 0;
            foreach ($datosPagos as $key => $dato) {
                $hoja->setCellValue('A' . $i, "#");
                $hoja->setCellValue('B' . $i, $dato->contrato->cliente->persona->nrodocumento);
                $hoja->setCellValue('C' . $i, $dato->contrato->cliente->persona->nombreCompleto());
                $hoja->setCellValue('D' . $i, $dato->sucural->nombre);
                $hoja->setCellValue('E' . $i, Carbon::parse($dato->fecha_inio)->format('d-m-Y'));
                $hoja->setCellValue('F' . $i, $dato->caja);
                //$Amortizacion = 
                if ($dato->contrato->codigo) {
                    $hoja->setCellValue('G' . $i, $dato->contrato->codigo);
                }else{
                    $hoja->setCellValue('G' . $i, $dato->contrato->sucural->nuevo_codigo .''. Carbon::parse($dato->contrato->fecha_contrato)->format('y') .''. $dato->contrato->codigo_num);
                }
                $hoja->setCellValue('H' . $i, $dato->dias_atraso_total);
                $hoja->setCellValue('I' . $i, $dato->cuota_mora);
                $hoja->setCellValue('J' . $i, $dato->capital);
                $hoja->setCellValue('K' . $i, $dato->interes);
                $hoja->setCellValue('L' . $i, $dato->comision);
                $hoja->setCellValue('M' . $i, $dato->total_capital);
                $hoja->setCellValue('N' . $i, $dato->estado);
                $i++;
            }
            // foreach ($datosContaDiario as $key => $dato) {
            //     if ($dato->contrato_id == 0) {
            //         $j++;
            //         $hoja->setCellValue('A' . $i, $j);
            //         $hoja->setCellValue('B' . $i, 2773500);
            //         $hoja->setCellValue('C' . $i, "MARIO ROJAS YUCRA");
            //         $hoja->setCellValue('D' . $i, $dato->contrato_id);
            //         $hoja->setCellValue('E' . $i, $dato->sucural->nombre);
            //         $hoja->setCellValue('F' . $i, $dato->periodo);
            //         $hoja->setCellValue('G' . $i, $dato->fecha_a);
            //         $hoja->setCellValue('H' . $i, $dato->glosa);
            //         $hoja->setCellValue('I' . $i, $dato->caja);
            //         $hoja->setCellValue('J' . $i, $dato->num_comprobante);
            //         $hoja->setCellValue('K' . $i, $dato->cod_deno);
            //         $hoja->setCellValue('L' . $i, $dato->cuenta);
            //         $hoja->setCellValue('M' . $i, $dato->debe);
            //         $hoja->setCellValue('N' . $i, $dato->haber);
            //         $hoja->setCellValue('O' . $i, $dato->tcom);
            //         $hoja->setCellValue('P' . $i, $dato->ref);
            //         $i++;
                   
            //     }
            //     else{
            //         if ($dato->contrato1) {
            //             $j++;
            //             $hoja->setCellValue('A' . $i, $j);
            //             $hoja->setCellValue('B' . $i, $dato->contrato1->cliente->persona->nrodocumento);
            //             $hoja->setCellValue('C' . $i, $dato->contrato1->cliente->persona->nombreCompleto());
            //             if ($dato->contrato_id > 0) {
            //                 if ($dato->contrato1->codigo) {
            //                     $hoja->setCellValue('D' . $i, $dato->contrato1->codigo);
            //                 }
            //                 else{
            //                     $hoja->setCellValue('D' . $i, $dato->sucural->nuevo_codigo ."".Carbon::parse($dato->contrato1->fecha_contrato)->format('y') ."". $dato->contrato1->codigo_num);
            //                 }
            //             }
            //             else{
            //                 $hoja->setCellValue('D' . $i, $dato->contrato_id);
            //             }
                        
                        
            //             $hoja->setCellValue('E' . $i, $dato->sucural->nombre);
            //             $hoja->setCellValue('F' . $i, $dato->periodo);
            //             $hoja->setCellValue('G' . $i, $dato->fecha_a);
            //             $hoja->setCellValue('H' . $i, $dato->glosa);
            //             $hoja->setCellValue('I' . $i, $dato->caja);
            //             $hoja->setCellValue('J' . $i, $dato->num_comprobante);
            //             $hoja->setCellValue('K' . $i, $dato->cod_deno);
            //             $hoja->setCellValue('L' . $i, $dato->cuenta);
            //             $hoja->setCellValue('M' . $i, $dato->debe);
            //             $hoja->setCellValue('N' . $i, $dato->haber);
            //             $hoja->setCellValue('O' . $i, $dato->tcom);
            //             $hoja->setCellValue('P' . $i, $dato->ref);
            //             $i++;
            //         }
            //     }                                
            // }

            // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, "Xlsx");
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment; filename="historialPagos"'. Carbon::parse($fechaI)->format('dmY') .'_'.Carbon::parse($fechaF)->format('dmY').'.xlsx"');
            // header('Cache-Control: max-age=0');
            // $writer->save("php://output");

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
                    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
                );
            die(json_encode($response));
            
            
        }else{
            return view("layout.login");
        } 
    }
   
}
