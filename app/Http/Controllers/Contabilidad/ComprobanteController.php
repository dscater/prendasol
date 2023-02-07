<?php

namespace App\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use Carbon\Carbon;
use App\ContaDiarioTemp;
use App\ContaDiario;
use App\Sucursal;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class ComprobanteController extends Controller
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
                //return view('inicioFinCaja.index',compact('datosCaja','datoValidarCaja'));
                return view('contabilidad.regComprobantes.index');
            }
            else{
                return view("layout.login",compact('sucursales'));
            }
            
        }else{
            return view("layout.login",compact('sucursales'));
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
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                $datosContaDiarioTemp = ContaDiarioTemp::get();
                $numComprobante = ContaDiario::max('num_comprobante');
                foreach ($datosContaDiarioTemp as $key => $dato) {
                    ContaDiario::create([
                        'contrato_id'        => $dato->contrato_id,
                        'pagos_id'           => $dato->pagos_id,
                        'sucursal_id'        => $dato->sucursal_id,
                        'fecha_a'            => $dato->fecha_a,
                        'fecha_b'            => $dato->fecha_b,
                        'glosa'              => $dato->glosa,
                        'cod_deno'           => $dato->cod_deno,
                        'cuenta'             => $dato->cuenta,
                        'debe'               => round($dato->debe,2),
                        'haber'              => round($dato->haber,2),
                        'caja'               => $dato->caja,
                        'num_comprobante'    => $numComprobante +1,
                        'periodo'            => $dato->periodo,
                        'tcom'               => $dato->tcom,
                        'ref'                => $dato->ref,
                        'ci'                 => $dato->ci,
                        'nom'                 =>$dato->nom,
                        'usuario_id'         => session::get('ID_USUARIO'),
                        'estado_id'          => 1
                    ]);
                }
                return response()->json(["Mensaje" => "1"]);
            }
        }
        else{
            return response()->json(["Mensaje" => "-1"]);
        }
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
        //
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
    }

    public function storeTemp(Request $request)
    {
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['file']['name']);
            $extension = end($arr_file);
            //dd($extension);


            if('xlsx' == $extension) {
                $reader = new Xlsx();
                //$reader->setReadDataOnly(TRUE);
                //$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            if('xls' == $extension) {
                //$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $reader = new Xls();
                //$reader->setReadDataOnly(TRUE);
            }

            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
     
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            //dd($sheetData);
            $i = 0;
            ContaDiarioTemp::truncate();
            foreach ($sheetData as $row) {
                //$numComprobante = ContaDiario::max('num_comprobante');
                //dd((double)ltrim(rtrim($row[12])));
                ContaDiarioTemp::create([
                    'contrato_id'        => 0,
                    'pagos_id'           => 0,
                    'sucursal_id'        => session::get('ID_SUCURSAL'),
                    'fecha_a'            => $row[6],
                    'fecha_b'            => $row[6],
                    'glosa'              => $row[7],
                    'cod_deno'           => $row[10],
                    'cuenta'             => $row[11],
                    //'debe'               => number_format(ltrim(rtrim($row[12])), 2, ',', '.'),
                    'debe'               => (double)ltrim(rtrim($row[12])),
                    'haber'              => $row[13],
                    'caja'               => session::get('CAJA'),
                    'num_comprobante'    => $i++,
                    'periodo'            => 'mes',
                    'tcom'               => $row[14],
                    'ref'                => $row[15],
                    'ci'                 => $row[1],
                    'nom'                 => $row[2],
                    'usuario_id'         => session::get('ID_USUARIO'),
                    'estado_id'          => 1
                ]);
            }


         
        }

        $datosContaDiario = ContaDiarioTemp::orderBy('num_comprobante','ASC')
                ->get();

        if ($datosContaDiario) {                
            if ($request->ajax()) {
                //dd($actoVacunaciones);
                return view('contabilidad.regComprobantes.modals.listadoComprobanteTemp', ['datosContaDiario' => $datosContaDiario])->render();
            }     
            return view('contabilidad.regComprobantes.index',compact('datosContaDiario'));                           
        }
    }
}
