<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Persona;
use App\Catalogo;
use App\LogSeguimiento;
use Carbon\Carbon;
use App\Cliente;
use App\InicioFinCaja;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            // $codigoPrueba = substr(strtoupper("alvaro"), 0, 1) .''. substr(strtoupper("choque"), 0, 1) .''. substr(strtoupper("telles"), 0, 1) .''. Carbon::parse("04-02-2019")->format('dmY');
            // dd($codigoPrueba);
            //$fechaIngreso = Carbon::now();
            //dd(Carbon::parse($fechaIngreso)->format('Y-m-d'));
            $personas = Persona::orderBy('primerapellido','ASC')->orderBy('segundoapellido','ASC')->where('estado_id',1)->paginate(10);
            $estadoCivil = Catalogo::where('tabla_id',7)->get();
            $datoInicioFinCaja =  InicioFinCaja::where('fecha',Carbon::now('America/La_Paz')->format('Y-m-d'))
                ->where('sucursal_id',session::get('ID_SUCURSAL'))
                ->where('caja',session::get('CAJA'))
                ->where('estado_id',1)
                ->first();
            if ($request->ajax()) {
                return view('administracion.persona.modals.listado', ['personas' => $personas,'estadoCivil' => $estadoCivil,'datoInicioFinCaja' => $datoInicioFinCaja])->render();  
            }
            return view('administracion.persona.index',compact('personas','estadoCivil','datoInicioFinCaja'));
        }else{
            return view("layout.login");
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
        \DB::enableQueryLog();
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                /*VERIFICAMOS SI EXISTE LA PERSONA*/
                $persona = Persona::where('nrodocumento',$request['txtCI'])->count();
                if ($persona == 0) {
                    //INSERTAMOS PERSONA
                    $idPersona = Persona::create([                        
                        'nombres'                           => strtoupper($request['txtNombres']),
                        'primerapellido'                    => strtoupper($request['txtPaterno']),
                        'segundoapellido'                   => strtoupper($request['txtMaterno']),
                        'nrodocumento'                      => $request['txtCI'],
                        'expedido_id'                       => $request['ddlExpedido'],
                        'complemento'                       => strtoupper($request['txtComplemento']),
                        'sexo_genericoid'                   => $request['rdoSexo'],
                        'fechanacimiento'                   => Carbon::parse($request['txtFechaNacimiento'])->format('Y-m-d'),
                        'estadocivil_genericoid'            => $request['ddlEstadocivil'],
                        'correoelectronico'                 => $request['txtCorreo'],
                        'telefonodomicilio'                 => $request['txtTelefonoDomicilio'],
                        'telefonotrabajo'                   => $request['txtTelefonoTrabajo'],
                        'direcciontrabajo'                  => strtoupper($request['txtDireccionTrabajo']),
                        'tipodocumento_genericoid'          => $request['tipodocumento_genericoid'],
                        //'muninacimiento_id'                 => $request['muninacimiento_id'],
                        'nacionalidad_genericoid'           => $request['nacionalidad_genericoid'],
                        //'nivelestudio_genericoid'           => $request['nivelestudio_genericoid'],
                        //'idiomamaterno_genericoid'          => $request['idiomamaterno_genericoid'],
                        //'idioma_genericoid'                 => $request['idioma_genericoid'],
                        //'idioma_genericoid'                 => $request['autopertenencia_genericoid'],
                        'fotografia'                        => $request['fotografia'],
                        'celular'                           => $request['txtCelular'],
                        'domicilio'                         => strtoupper($request['txtDomicilio']),
                        'estado_id'                         => 1,
                        'usuario_id'                        => session::get('ID_USUARIO'),
                    ])->id;

                    $bitacora = \DB::getQueryLog();
                    foreach ($bitacora as $i => $query) {
                        $resultado = json_encode($query);
                    }
                    \DB::disableQueryLog();
                    LogSeguimiento::create([
                        'usuario_id'   => session::get('ID_USUARIO'),
                        'metodo'   => 'POST',
                        'accion'   => 'CREACION',
                        'detalle'  => "el usuario" . session::get('USUARIO') . " agrego un nuevo registro",
                        'modulo'   => 'PERSONA',
                        'consulta' => $resultado,
                    ]);
                    $codigo = substr(strtoupper($request['txtPaterno']), 0, 1) .''. substr(strtoupper($request['txtMaterno']), 0, 1) .''. substr(strtoupper($request['txtNombres']), 0, 1) .''. Carbon::parse($request['txtFechaNacimiento'])->format('dmY');
                    $fechaIngreso = Carbon::now('America/La_Paz');

                    Cliente::create([                        
                        'persona_id'   => $idPersona,
                        'codigo'        => $codigo,
                        'fecha_ingreso'   => Carbon::parse($fechaIngreso)->format('Y-m-d'),
                        'usuario_id'    => session::get('ID_USUARIO'),
                        'estado_id'     => 1
                    ]);

                    

                    return response()->json(["Mensaje" => "1"]);
                }
                else{
                    /*2 = EXISTE UN USUARIO */
                    return response()->json(["Mensaje" => "2"]);
                }           
            }
            else{
                return response()->json(["Mensaje" => "0"]);
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
        \DB::enableQueryLog();
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                /*VERIFICAMOS SI EXISTE LA PERSONA*/
                $persona = Persona::where('nrodocumento',$request['txtCI'])->where('id', '!=',$id)->count();
                //dd($persona);
                if ($persona == 0) {                    
                    //ACTUALIZAMOS ROL
                    $persona= Persona::find($id);
                    $persona->nombres                         = strtoupper($request['txtNombres']);
                    $persona->primerapellido                  = strtoupper($request['txtPaterno']);
                    $persona->segundoapellido                 = strtoupper($request['txtMaterno']);
                    $persona->nrodocumento                    = $request['txtCI'];
                    $persona->complemento                     = strtoupper($request['txtComplemento']);
                    $persona->expedido_id                     = $request['ddlExpedido'];
                    $persona->sexo_genericoid                 = $request['rdoSexo'];
                    $persona->fechanacimiento                 = Carbon::parse($request['txtFechaNacimiento'])->format('Y-m-d');
                    $persona->estadocivil_genericoid          = $request['ddlEstadocivil'];
                    $persona->correoelectronico               = $request['txtCorreo'];
                    $persona->telefonodomicilio               = $request['txtTelefonoDomicilio'];
                    $persona->telefonotrabajo                 = $request['txtTelefonoTrabajo'];
                    $persona->direcciontrabajo                = strtoupper($request['txtDireccionTrabajo']);
                    $persona->tipodocumento_genericoid        = $request['tipodocumento_genericoid'];
                    //$persona->muninacimiento_id               = $request['muninacimiento_id'];
                    $persona->nacionalidad_genericoid         = $request['nacionalidad_genericoid'];
                    // $persona->nivelestudio_genericoid         = $request['nivelestudio_genericoid'];
                    // $persona->idiomamaterno_genericoid        = $request['idiomamaterno_genericoid'];
                    // $persona->idioma_genericoid               = $request['idioma_genericoid'];
                    // $persona->idioma_genericoid               = $request['autopertenencia_genericoid'];
                    $persona->fotografia                      = $request['fotografia'];
                    $persona->celular                         = $request['txtCelular'];
                    $persona->domicilio                       = strtoupper($request['txtDomicilio']);
                    $persona->estado_id                       = 1;
                    //$persona->usuario_id                      = session::get('ID_USUARIO');
                    $persona->save();

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
                        'modulo'   => 'PERSONA',
                        'consulta' => $resultado,
                    ]);
                    return response()->json(["Mensaje" => "1"]);
                }
                else{
                    /*2 = EXISTE UN USUARIO */
                    return response()->json(["Mensaje" => "2"]);
                }
            }
            else{
                return response()->json(["Mensaje" => "0"]);
            }
        }
        else{
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
            $persona= Persona::find($id);
            $persona->estado_id=2;        
            $persona->save();
            return response()->json(["Mensaje" => "1"]);            
        }
        else{
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public function buscarPersonas(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $txtBuscarIdentifiacion = $request['txtBuscarIdentifiacion'];
            $txtBuscarNombres = $request['txtBuscarNombres'];
            $txtBuscarPaterno = $request['txtBuscarPaterno'];
            $txtBuscarMaterno = $request['txtBuscarMaterno'];
            $txtBuscarFechaNacimiento = $request['txtBuscarFechaNacimiento'];
            //dd($txtBuscarFechaNacimiento);
            if ($txtBuscarFechaNacimiento) {
                //dd($txtBuscarFechaNacimiento);
                $txtBuscarFechaNacimiento = Carbon::parse($request['txtBuscarFechaNacimiento'])->format('Y-m-d');
                //dd($txtBuscarFechaNacimiento);
                // $personas = Persona::where('nrodocumento', 'like', '%' . $txtBuscarIdentifiacion . '%')
                //     ->where('nombres', 'like', '%' . strtoupper($txtBuscarNombres) . '%')
                //     ->where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%')
                //     ->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                    where('fechanacimiento', $txtBuscarFechaNacimiento)
                    ->orderBy('primerapellido','ASC')
                    ->orderBy('segundoapellido','ASC')                    
                    ->paginate(10);
            }
            else{
                if ($txtBuscarPaterno) {
                    //dd("ci");
                    $personas = Persona::where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%')
                        //->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                        ->where('estado_id',1)
                        ->orderBy('primerapellido','ASC')
                        ->orderBy('segundoapellido','ASC')                    
                        ->paginate(10);
                }
                if ($txtBuscarIdentifiacion) {
                    $personas = Persona::where('nrodocumento', 'like', '%' . $txtBuscarIdentifiacion . '%')
                            ->where('estado_id',1)
                            //->where('nombres', 'like', '%' . strtoupper($txtBuscarNombres) . '%')
                            //->where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%')
                            //->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                            ->orderBy('primerapellido','ASC')
                            ->orderBy('segundoapellido','ASC')                    
                            ->paginate(10);
                }
                if ($txtBuscarPaterno && $txtBuscarMaterno) {
                    $personas = Persona::
                        //->where('nombres', 'like', '%' . strtoupper($txtBuscarNombres) . '%')
                        where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%')
                        ->where('estado_id',1)
                        ->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                        ->orderBy('primerapellido','ASC')
                        ->orderBy('segundoapellido','ASC')                    
                        ->paginate(10);
                }

                if ($txtBuscarMaterno) {
                    //dd("ci");
                    $personas = Persona::where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                        ->where('estado_id',1)
                        ->orderBy('primerapellido','ASC')
                        ->orderBy('segundoapellido','ASC')                    
                        ->paginate(10);
                }
            }
            //$personas = Persona::where('nrodocumento', 'like', '%' . $request['txtBuscarPersona'] . '%')->latest('created_at')->paginate(10);
            if ($request->ajax()) {
                return view('administracion.persona.modals.listado', ['personas' => $personas])->render();  
            }
            return view('administracion.persona.index',compact('personas'));
        }else{
            return view("layout.login");
        }
    }

    public function habilitarPersona($id)
    {
        if (Session::has('AUTENTICADO')) {     
            //ACTUALIZAMOS ROL
            $persona= Persona::find($id);
            $persona->estado_id=1;        
            $persona->save();
            return response()->json(["Mensaje" => "1"]);            
        }
        else{
            return response()->json(["Mensaje" => "-1"]);
        }
    }
}
