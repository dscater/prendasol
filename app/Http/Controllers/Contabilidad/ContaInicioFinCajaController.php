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

class ContaInicioFinCajaController extends Controller
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
                if ($request->ajax()) {
                    //return view('formEgreso.modals.listadoEgreso', ['sucursales' => $sucursales,'datosContaDiario'=>$datosContaDiario,'cuentas'=>$cuentas])->render(); 
                }
                //return view('inicioFinCaja.index',compact('datosCaja','datoValidarCaja'));
                return view('contabilidad.contaInicioCaja.index');
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
        //dd($id);
        \DB::enableQueryLog();
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {

                $inicioFinCaja = InicioFinCajaDetalle::find($id);
                $inicioFinCaja->ingreso_bs                  = $request['txtDebe'];
                $inicioFinCaja->egreso_bs                   = $request['txtHaber'];
                $inicioFinCaja->estado_id                    = 1;
                $inicioFinCaja->usuario_id                   = session::get('ID_USUARIO');
                $inicioFinCaja->save();

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
                    'modulo'   => 'CONTA INICIO FIN CAJA',
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
        //
        if (Session::has('AUTENTICADO')) {
            //ACTUALIZAMOS PERSONA
            $inicioFinCaja = InicioFinCajaDetalle::find($id);
            $inicioFinCaja->estado_id = 2;
            $inicioFinCaja->save();
            return response()->json(["Mensaje" => "1"]);
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public function buscarContaInicioCaja(Request $request)
    {
        $fechaI = $request['txtFechaInicio'];
        $fechaF = $request['txtFechaFin'];

        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            $datosValidarCaja =  InicioFinCaja::whereBetween('fecha', [$fechaInicio, $fechaFinal])
                ->whereIn('estado_id', [1, 2])
                ->orderBy('fecha', 'ASC')->get();
            //dd($datoValidarCaja);

            if ($datosValidarCaja) {
                if ($request->ajax()) {
                    //dd($actoVacunaciones);
                    return view('contabilidad.contaInicioCaja.modals.listadoContaInicioCaja', ['datosValidarCaja' => $datosValidarCaja, 'fechaI' => $fechaI, 'fechaF' => $fechaF])->render();
                }
                return view('contabilidad.contaInicioCaja.index', compact('datosValidarCaja'));
            }
        } else {
            return view("layout.login");
        }
    }

    public function exportarInicioFinCaja($fechaI, $fechaF)
    {
        if (Session::has('AUTENTICADO')) {
            $fechaInicio = Carbon::parse($fechaI)->format('Y-m-d');
            $fechaFinal = Carbon::parse($fechaF)->format('Y-m-d');
            //$datosContaDiario = ContaDiario::whereBetween('fecha_a',[$fechaI,$fechaF])->get();
            $datosValidarCaja =  InicioFinCaja::whereBetween('fecha', [$fechaInicio, $fechaFinal])
                ->whereIn('estado_id', [1, 2])
                ->orderBy('fecha', 'ASC')->get();

            $documento = new Spreadsheet();
            /*FIRMA DE DOCUMENTO*/
            $documento
                ->getProperties()
                ->setCreator("PRENDASOL")
                ->setLastModifiedBy('admin') // última vez modificado por
                ->setTitle('Reporte Cierre Caja')
                ->setSubject('Reporte')
                ->setDescription('generado por Admin')
                ->setKeywords('etiquetas o palabras clave separadas por espacios')
                ->setCategory('Reporte');

            /**/
            $hoja = $documento->getActiveSheet();
            /*NOMBRE DE LA HOJA*/
            $hoja->setTitle('Cirre Caja');

            /*ARMAMOS TITULO DE LOS CAMPOS*/
            $hoja->setCellValue('A1', "#");
            $hoja->setCellValue('B1', "Sucursal");
            $hoja->setCellValue('C1', "Caja");
            $hoja->setCellValue('D1', "Codigo");
            $hoja->setCellValue('E1', "Nombre Cliente");
            $hoja->setCellValue('F1', "CI");
            $hoja->setCellValue('G1', "Ref.");
            $hoja->setCellValue('H1', "Fecha Pago");
            $hoja->setCellValue('I1', "Inicio Caja");
            $hoja->setCellValue('J1', "Ingreso Bs");
            $hoja->setCellValue('K1', "Egreso Bs");
            $hoja->setCellValue('L1', "Tipo Movimiento");

            $i = 0;
            $k = 2;
            foreach ($datosValidarCaja as $key => $datoCaja) {
                if ($datoCaja->fecha_hora) {
                    $i++;
                    $j = 0;
                    $hoja->setCellValue('A' . $k, $i);
                    $hoja->setCellValue('B' . $k, $datoCaja->sucural1->nombre);
                    $hoja->setCellValue('C' . $k, $datoCaja->caja);
                    $hoja->setCellValue('D' . $k, "");
                    $hoja->setCellValue('E' . $k, "");
                    $hoja->setCellValue('F' . $k, "");
                    $hoja->setCellValue('G' . $k, "");
                    //$hoja->setCellValue('H'. $k, "");
                    $hoja->setCellValue('H' . $k, $datoCaja->fecha_hora);
                    $hoja->setCellValue('I' . $k, $datoCaja->inicio_caja_bs);
                    $hoja->setCellValue('L' . $k, "SALDO INICIAL");
                    $k++;

                    foreach ($datoCaja->detalleIniciFinCaja($datoCaja->id) as $key1 => $detalle) {
                        $j++;
                        if ($detalle->contrato_id == 0) {
                            $hoja->setCellValue('A' . $k, $j);
                            $hoja->setCellValue('B' . $k, $detalle->sucursal->nombre);
                            $hoja->setCellValue('C' . $k, $detalle->caja);
                            $hoja->setCellValue('D' . $k, $detalle->contrato_id);
                            $hoja->setCellValue('E' . $k, "MARIO ROJAS YUCRA");
                            $hoja->setCellValue('F' . $k, "2773500");
                            $hoja->setCellValue('G' . $k, $detalle->ref);
                            $hoja->setCellValue('H' . $k, $detalle->created_at);
                            $hoja->setCellValue('I' . $k, $detalle->inicio_caja_bs);
                            $hoja->setCellValue('J' . $k, $detalle->ingreso_bs);
                            $hoja->setCellValue('K' . $k, $detalle->egreso_bs);
                            $hoja->setCellValue('L' . $k, $detalle->tipo_de_movimiento);
                        } else {
                            if ($detalle->contrato) {
                                $hoja->setCellValue('A' . $k, $j);
                                $hoja->setCellValue('B' . $k, $detalle->sucursal->nombre);
                                $hoja->setCellValue('C' . $k, $detalle->caja);
                                if ($detalle->contrato_id != 0) {
                                    if ($detalle->contrato->codigo != "") {
                                        $hoja->setCellValue('D' . $k, $detalle->contrato->codigo);
                                    } else {
                                        $rescodigo = $detalle->contrato->sucural->nuevo_codigo . '' . Carbon::parse($detalle->contrato->fecha_contrato)->format('y') . '' . $detalle->contrato->codigo_num;
                                        $hoja->setCellValue('D' . $k, $rescodigo);
                                    }
                                    $hoja->setCellValue('E' . $k, $detalle->contrato->cliente->persona->nombreCompleto());
                                    $hoja->setCellValue('F' . $k, $detalle->contrato->cliente->persona->nrodocumento);
                                } else {
                                    $hoja->setCellValue('D' . $k, "");
                                    $hoja->setCellValue('E' . $k, $detalle->persona->nombreCompleto());
                                    $hoja->setCellValue('F' . $k, $detalle->persona->nrodocumento);
                                }
                                $hoja->setCellValue('G' . $k, $detalle->ref);
                                $hoja->setCellValue('H' . $k, $detalle->created_at);
                                $hoja->setCellValue('I' . $k, $detalle->inicio_caja_bs);
                                $hoja->setCellValue('J' . $k, $detalle->ingreso_bs);
                                $hoja->setCellValue('K' . $k, $detalle->egreso_bs);
                                $hoja->setCellValue('L' . $k, $detalle->tipo_de_movimiento);
                            }
                        }

                        $k++;
                    }


                    $hoja->setCellValue('A' . $k, $i);
                    $hoja->setCellValue('B' . $k, "");
                    $hoja->setCellValue('C' . $k, "");
                    $hoja->setCellValue('D' . $k, "");
                    $hoja->setCellValue('E' . $k, "");
                    $hoja->setCellValue('F' . $k, "");
                    $hoja->setCellValue('G' . $k, "");
                    //$hoja->setCellValue('H'. $k, "");
                    $hoja->setCellValue('H' . $k, $datoCaja->fecha_cierre);
                    $hoja->setCellValue('I' . $k, $datoCaja->fin_caja_bs);
                    $hoja->setCellValue('L' . $k, "CIERRE CAJA");
                    $k++;
                }
            }

            // $i = 2;
            // $j = 0;
            // foreach ($datosContaDiario as $key => $dato) {
            //     if ($dato->contrato1) {
            //         $j++;
            //         $hoja->setCellValue('A' . $i, $j);
            //         if ($dato->contrato_id > 0) {
            //             if ($dato->contrato1->codigo) {
            //                 $hoja->setCellValue('B' . $i, $dato->contrato1->codigo);
            //             }
            //             else{
            //                 $hoja->setCellValue('B' . $i, $dato->sucural->nuevo_codigo ."".Carbon::parse($dato->contrato1->fecha_contrato)->format('y') ."". $dato->contrato1->codigo_num);
            //             }
            //         }
            //         else{
            //             $hoja->setCellValue('B' . $i, $dato->contrato_id);
            //         }


            //         $hoja->setCellValue('C' . $i, $dato->sucural->nombre);
            //         $hoja->setCellValue('D' . $i, $dato->periodo);
            //         $hoja->setCellValue('E' . $i, $dato->fecha_a);
            //         $hoja->setCellValue('F' . $i, $dato->glosa);
            //         $hoja->setCellValue('G' . $i, $dato->caja);
            //         $hoja->setCellValue('H' . $i, $dato->num_comprobante);
            //         $hoja->setCellValue('I' . $i, $dato->cod_deno);
            //         $hoja->setCellValue('J' . $i, $dato->cuenta);
            //         $hoja->setCellValue('K' . $i, $dato->debe);
            //         $hoja->setCellValue('L' . $i, $dato->haber);
            //         $hoja->setCellValue('M' . $i, $dato->tcom);
            //         $hoja->setCellValue('N' . $i, $dato->ref);
            //         $i++;
            //     }                
            // }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, "Xlsx");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="cierreCaja"' . Carbon::parse($fechaI)->format('dmY') . '_' . Carbon::parse($fechaF)->format('dmY') . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save("php://output");
        } else {
            return view("layout.login");
        }
    }
}
