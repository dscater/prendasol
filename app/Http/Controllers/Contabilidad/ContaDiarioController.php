<?php

namespace App\Http\Controllers\Contabilidad;

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
use App\ContaDenominacion;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\LogSeguimiento;

class ContaDiarioController extends Controller
{
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
                $datosContaDiario = ContaDiario::where('tcom', 'EGRESO1')->where('ref', 'T126')->get();
                if ($request->ajax()) {
                    //return view('formEgreso.modals.listadoEgreso', ['sucursales' => $sucursales,'datosContaDiario'=>$datosContaDiario,'cuentas'=>$cuentas])->render(); 
                }
                //return view('inicioFinCaja.index',compact('datosCaja','datoValidarCaja'));
                return view('contabilidad.contaDiario.index', compact('datosContaDiario'));
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
        \DB::enableQueryLog();
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {

                $datoC = ContaDiario::find($id);
                $datoC->debe                         = $request['txtDebe'];
                $datoC->haber                        = $request['txtHaber'];
                $datoC->estado_id                    = 1;
                $datoC->usuario_id                   = session::get('ID_USUARIO');
                $datoC->save();

                $bitacora = \DB::getQueryLog();
                foreach ($bitacora as $i => $query) {
                    $resultado = json_encode($query);
                }
                \DB::disableQueryLog();
                LogSeguimiento::create([
                    'usuario_id'   => session::get('ID_USUARIO'),
                    'metodo'   => 'POST',
                    'accion'   => 'ACTUALIZACION',
                    'detalle'  => "el usuario" . session::get('USUARIO') . " actualizo un registro",
                    'modulo'   => 'CONTA DIARIO',
                    'consulta' => $resultado,
                ]);
                return response()->json(["Mensaje" => "1"]);
            } else {
                return response()->json(["Mensaje" => "0"]);
            }
        } else {
            return response()->json(["Mensaje" => "-1"]);
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
        if (Session::has('AUTENTICADO')) {
            //ACTUALIZAMOS PERSONA
            $contaDiario = ContaDiario::find($id);
            $contaDiario->estado_id = 2;
            $contaDiario->save();
            return response()->json(["Mensaje" => "1"]);
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public function buscarContaDiario(Request $request)
    {
        ini_set('memory_limit', '1024M');
        $fechaI = $request['txtFechaInicio'];
        $fechaF = $request['txtFechaFin'];
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            //$datosContaDiario = ContaDiario::whereBetween('fecha_a',[$fechaI,$fechaF])->get();
            $datosContaDiario = ContaDiario::whereBetween('fecha_a', [$fechaInicio, $fechaFinal])
                ->where('estado_id', 1)
                //->orderBy('fecha_a','ASC')
                ->orderBy('num_comprobante', 'ASC')
                ->get();

            //dd($datosContaDiario);
            if ($datosContaDiario) {
                if ($request->ajax()) {
                    //dd($actoVacunaciones);
                    return view('contabilidad.contaDiario.modals.listadoContaDiario', ['datosContaDiario' => $datosContaDiario, 'fechaI' => $fechaI, 'fechaF' => $fechaF])->render();
                }
                return view('contabilidad.contaDiario.index', compact('datosContaDiario', 'fechaI', 'fechaF'));
            }
        } else {
            return view("layout.login");
        }
    }
    public function exportarContaDiario($fechaI, $fechaF)
    {
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            //$datosContaDiario = ContaDiario::whereBetween('fecha_a',[$fechaI,$fechaF])->get();
            $datosContaDiario = ContaDiario::whereBetween('fecha_a', [$fechaInicio, $fechaFinal])
                //->orderBy('fecha_a','ASC')
                ->where('estado_id', 1)
                ->orderBy('num_comprobante', 'ASC')
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
            $hoja->setTitle('ContaDiario');

            /*ARMAMOS TITULO DE LOS CAMPOS*/
            $hoja->setCellValue('A1', "#");
            $hoja->setCellValue('B1', "CI");
            $hoja->setCellValue('C1', "Nombre Completo");
            $hoja->setCellValue('D1', "Codigo");
            $hoja->setCellValue('E1', "Sucursal");
            $hoja->setCellValue('F1', "Periodo");
            $hoja->setCellValue('G1', "Fecha");
            $hoja->setCellValue('H1', "Glosa");
            $hoja->setCellValue('I1', "Caja");
            $hoja->setCellValue('J1', "Comprobante");
            $hoja->setCellValue('K1', "Codigo");
            $hoja->setCellValue('L1', "Cuenta");
            $hoja->setCellValue('M1', "Debe");
            $hoja->setCellValue('N1', "Haber");
            $hoja->setCellValue('O1', "Tipo Comprobante");
            $hoja->setCellValue('P1', "Referencia");

            $i = 2;
            $j = 0;
            foreach ($datosContaDiario as $key => $dato) {
                if ($dato->contrato_id == 0) {
                    $j++;
                    $hoja->setCellValue('A' . $i, $j);
                    if ($dato->ci) {
                        $hoja->setCellValue('B' . $i, $dato->ci);
                        $hoja->setCellValue('C' . $i, $dato->nom);
                    } else {
                        $hoja->setCellValue('B' . $i, 2773500);
                        $hoja->setCellValue('C' . $i, "MARIO ROJAS YUCRA");
                    }

                    if ($dato->contrato_id == 0) {
                        $hoja->setCellValue('D' . $i, $dato->correlativo . ' - ' . $dato->gestion);
                    } else {
                        $hoja->setCellValue('D' . $i, $dato->contrato_id);
                    }


                    $hoja->setCellValue('E' . $i, $dato->sucural->nombre);
                    $hoja->setCellValue('F' . $i, $dato->periodo);
                    $hoja->setCellValue('G' . $i, $dato->fecha_a);
                    $hoja->setCellValue('H' . $i, $dato->glosa);
                    $hoja->setCellValue('I' . $i, $dato->caja);
                    $hoja->setCellValue('J' . $i, $dato->num_comprobante);
                    $hoja->setCellValue('K' . $i, $dato->cod_deno);
                    $hoja->setCellValue('L' . $i, $dato->cuenta);
                    $hoja->setCellValue('M' . $i, $dato->debe);
                    $hoja->setCellValue('N' . $i, $dato->haber);
                    $hoja->setCellValue('O' . $i, $dato->tcom);
                    $hoja->setCellValue('P' . $i, $dato->ref);
                    $i++;
                } else {
                    if ($dato->contrato1) {
                        $j++;
                        $hoja->setCellValue('A' . $i, $j);
                        $hoja->setCellValue('B' . $i, $dato->contrato1->cliente->persona->nrodocumento);
                        $hoja->setCellValue('C' . $i, $dato->contrato1->cliente->persona->nombreCompleto());
                        if ($dato->contrato_id > 0) {
                            if ($dato->contrato1->codigo) {
                                $hoja->setCellValue('D' . $i, $dato->contrato1->codigo);
                            } else {
                                $hoja->setCellValue('D' . $i, $dato->sucural->nuevo_codigo . "" . Carbon::parse($dato->contrato1->fecha_contrato)->format('y') . "" . $dato->contrato1->codigo_num);
                            }
                        } else {
                            $hoja->setCellValue('D' . $i, $dato->contrato_id);
                        }


                        $hoja->setCellValue('E' . $i, $dato->sucural->nombre);
                        $hoja->setCellValue('F' . $i, $dato->periodo);
                        $hoja->setCellValue('G' . $i, $dato->fecha_a);
                        $hoja->setCellValue('H' . $i, $dato->glosa);
                        $hoja->setCellValue('I' . $i, $dato->caja);
                        $hoja->setCellValue('J' . $i, $dato->num_comprobante);
                        $hoja->setCellValue('K' . $i, $dato->cod_deno);
                        $hoja->setCellValue('L' . $i, $dato->cuenta);
                        $hoja->setCellValue('M' . $i, $dato->debe);
                        $hoja->setCellValue('N' . $i, $dato->haber);
                        $hoja->setCellValue('O' . $i, $dato->tcom);
                        $hoja->setCellValue('P' . $i, $dato->ref);
                        $i++;
                    }
                }
            }

            // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, "Xlsx");
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment; filename="contaDiario"'. Carbon::parse($fechaI)->format('dmY') .'_'.Carbon::parse($fechaF)->format('dmY').'.xlsx"');
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
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
            );
            die(json_encode($response));
        } else {
            return view("layout.login");
        }
    }
}
