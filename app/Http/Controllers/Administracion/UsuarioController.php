<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Hash;
use App\Usuario;
use App\Persona;
use App\Rol;
use Illuminate\Support\Str;
use App\UsuarioRol;
use App\SucursalUsuario;
use App\Sucursal;
use Carbon\Carbon;

use App\ValorOro;
use App\PrecioOro;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {

            $usuarios = Usuario::latest('created_at')->paginate(10);
            $personasRegistradas = Usuario::select('persona_id')->whereIn('estado_id', [1, 3])->get();
            $personas = Persona::whereNotIn('id', $personasRegistradas)->get();
            $sucursales = Sucursal::where('estado_id', 1)->get();
            //dd($personas);
            $roles = Rol::where('estado_id', 1)->get();

            if ($request->ajax()) {
                $resultado = view('administracion.usuario.modals.listadoUsuarios', compact('usuarios', 'roles'))->render();
                return response()->json(['html' => $resultado]);
            }
            return view('administracion.usuario.index', compact('usuarios', 'roles', 'sucursales'));
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
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                try {
                    //DB::beginTransaction();
                    /*VERIFICAMOS SI EXISTE USUARIO*/
                    $usuario = Usuario::where('usuario', $request['txtUsuario'])->orWhere('persona_id', $request['ddlPersona'])->count();
                    if ($usuario == 0) {
                        //INSERTAMOS USUARIO
                        $idUsuario = Usuario::create([
                            'persona_id'               => $request['ddlPersona'],
                            //'centro_salud_id'          => $centroSaludId,//$request['ddlEstablecimiento'],
                            'usuario'                  => $request['txtUsuario'],
                            //'tipo_destino'             => $request['rdoTipoDestino'],
                            'clave'                    => Hash::make(123456), //Hash::make($request['txtContrasena']),
                            'clave_texto'              => '',
                            'estado_id'                => 1,
                            'login_usu'                => session::get('ID_USUARIO'),
                        ])->id;

                        //INSERTAMOS USUARIO ROL
                        UsuarioRol::create([
                            'rol_id'               => $request['ddlRol'],
                            'usuario_id'           => $idUsuario,
                            'estado_id'            => 1,
                            'login_usu'            => session::get('ID_USUARIO'),
                        ]);
                        return response()->json(["Mensaje" => "1"]);
                    } else {
                        /*2 = EXISTE UN USUARIO */
                        return response()->json(["Mensaje" => "2"]);
                    }
                    //DB::commit(); 

                } catch (Exception $e) {
                    //DB::rollback();
                    return response()->json(["Mensaje" => "-1"]);
                }
            } else {
                return response()->json(["Mensaje" => "0"]);
            }
        } else {
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
        if (Session::has('AUTENTICADO')) {
            //ACTUALIZAMOS ROL
            $usuario = Usuario::find($id);
            $usuario->estado_id = 2;
            $usuario->save();
            return response()->json(["Mensaje" => "1"]);
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }


    public function postLogin(Request $request)
    {
        Session::flush();   //Elimina todos los elementos de la sesión
        $usuario = $request['txtUsuario'];
        $password = $request['txtPassword'];
        $id_sucursal = $request['ddlSucursal'];
        $caja = $request['ddlCaja'];
        //dd($id_sucursal);
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

        // nuevas sucursales
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

        /* INICIAR PRECIO DE ORO */
        $_fecha = Carbon::now('America/La_Paz')->format('Y-m-d');
        $precio_oro = PrecioOro::where('fecha', $_fecha)->get()->first();
        $valor_oro = ValorOro::first();
        if (!$precio_oro) {
            if ($valor_oro) {
                PrecioOro::create([
                    'dies' => $valor_oro->dies,
                    'catorce' => $valor_oro->catorce,
                    'diesiocho' => $valor_oro->diesiocho,
                    'veinticuatro' => $valor_oro->veinticuatro,
                    'fecha' => $_fecha
                ]);
            } else {
                PrecioOro::create([
                    'dies' => 140,
                    'catorce' => 160,
                    'diesiocho' => 186,
                    'veinticuatro' => 190,
                    'fecha' => $_fecha
                ]);
            }
        }

        /* FIN INICIAR PRECIO ORO */

        //dd($idCaja);
        $queryUsuario = Usuario::where('usuario', $usuario)->where('estado_id', 1)->first();
        $sucursales = Sucursal::where('estado_id', 1)->get();
        //dd($queryUsuario);
        if ($queryUsuario) {
            //dd("usuario y contraseña correcta");          
            // VERIFICAR QUE LA CONTRASEÑA SEA CORRECTA
            if (Hash::check($password, $queryUsuario->clave)) {
                //dd("usuario y contraseña correcta787");             
                $mensaje = "";
                if ($queryUsuario->usuarioRol) {
                    //dd($queryUsuario->id);
                    $verificarSucursal = SucursalUsuario::where('id_usuario', $queryUsuario->id)
                        ->where('id_sucursal', $id_sucursal)
                        ->where('caja', $caja)
                        ->first();
                    // VERIFICAR SI EL USUARIO ESTA HABILITADO
                    if ($verificarSucursal) {
                        if ($verificarSucursal->estado_id == 1) {
                            $datoSucursal = Sucursal::where('id', $id_sucursal)->first();
                            //dd($datoSucursal->nuevo_codigo);
                            //dd("dsdsd");
                            Session::put('USUARIO', $queryUsuario->usuario);
                            Session::put('ID_USUARIO', $queryUsuario->id);
                            Session::put('AUTENTICADO', true);
                            Session::put('ID_ROL', $queryUsuario->usuarioRol->rol->id);
                            Session::put('NOMBRE_ROL', $queryUsuario->usuarioRol->rol->rol);
                            Session::put('NOMBRE_COMPLETO', $queryUsuario->persona->nombreCompleto());
                            Session::put('SEXO', $queryUsuario->persona->sexo_genericoid);
                            Session::put('ESTADO_ID', $queryUsuario->estado_id);
                            Session::put('ID_SUCURSAL', $verificarSucursal->id_sucursal);
                            Session::put('CAJA', $idCaja);
                            Session::put('NROCAJA', $caja);
                            Session::put('CODIGO_SUCURSAL', $datoSucursal->nuevo_codigo);
                            return redirect('Inicio');
                        } else {
                            $mensaje = "El usuario fue deshabilitado. Para mayor información comuniquese con un administrador";
                            return redirect()->back()->with('mensaje', $mensaje);
                        }
                    } else {
                        $mensaje = "El Usuario no fue asignado a una Sucursal y/o Caja";
                        return redirect()->back()->with('mensaje', $mensaje);
                    }
                } else {
                    $mensaje = "El Usuario no fue asignado a un Rol";
                    return redirect()->back()->with('mensaje', $mensaje);
                }
            } else {
                $mensaje = "Contraseña Incorrecta...";
                return redirect()->back()->with('mensaje', $mensaje);
            }
        } else {
            $mensaje = "Usuario Incorrecto...";
            return redirect()->back()->with('mensaje', $mensaje);
        }
    }

    public function buscarUsuarios(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $txtBuscarIdentifiacion = $request['txtBuscarIdentifiacion'];
            $txtBuscarNombres = $request['txtBuscarNombres'];
            $txtBuscarPaterno = $request['txtBuscarPaterno'];
            $txtBuscarMaterno = $request['txtBuscarMaterno'];
            $txtBuscarFechaNacimiento = $request['txtBuscarFechaNacimiento'];
            if ($txtBuscarFechaNacimiento) {
                $usuarios = Usuario::whereHas('persona', function ($query) use ($txtBuscarIdentifiacion, $txtBuscarNombres, $txtBuscarPaterno, $txtBuscarMaterno, $txtBuscarFechaNacimiento) {
                    $query->where('nrodocumento', 'like', '%' . $txtBuscarIdentifiacion . '%');
                    $query->where('nombres', 'like', '%' . strtoupper($txtBuscarNombres) . '%');
                    $query->where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%');
                    $query->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%');
                    $query->where('fechanacimiento', 'like', '%' . $txtBuscarFechaNacimiento . '%');
                })
                    ->paginate(10);
                // $personas = Persona::where('nrodocumento', 'like', '%' . $txtBuscarIdentifiacion . '%')
                //     ->where('nombres', 'like', '%' . strtoupper($txtBuscarNombres) . '%')
                //     ->where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%')
                //     ->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%')
                //     ->where('fechanacimiento', $txtBuscarFechaNacimiento)
                //     ->orderBy('primerapellido','ASC')
                //     ->orderBy('segundoapellido','ASC')                    
                //     ->paginate(10);
            } else {
                $usuarios = Usuario::whereHas('persona', function ($query) use ($txtBuscarIdentifiacion, $txtBuscarNombres, $txtBuscarPaterno, $txtBuscarMaterno) {
                    $query->where('nrodocumento', 'like', '%' . $txtBuscarIdentifiacion . '%');
                    $query->where('nombres', 'like', '%' . strtoupper($txtBuscarNombres) . '%');
                    $query->where('primerapellido', 'like', '%' . strtoupper($txtBuscarPaterno) . '%');
                    $query->where('segundoapellido', 'like', '%' . strtoupper($txtBuscarMaterno) . '%');
                })
                    ->paginate(10);
            }

            //dd($valor);
            //$usuarios = Usuario::join('personas','personas.id','=','rnve_usuario.persona_id')
            //  ->where('personas.nrodocumento', 'like', '%' . $request['txtBuscarUsuario'] . '%')
            //  ->latest('personas.created_at')->paginate(2);

            // $usuarios = Usuario::with(['persona'=>function($query) use ($valor){                
            //     $query->where('nrodocumento','like', '%' . $valor . '%');                
            // }])

            // $usuarios = Usuario::whereHas('persona' ,function($query) use ($valor){                
            //     $query->where('nrodocumento','like', '%' . $valor . '%');                
            // })
            //->with('centroSaludDatos')
            //->paginate(10);
            //dd($usuarios);
            if ($request->ajax()) {
                //return view('administracion.usuario.modals.listadoUsuarios', ['usuarios' => $usuarios])->render();

                if (isset($request->sw)) {
                    $sw = true;
                    $resultado = view('administracion.usuario.modals.listadoUsuarios', compact('usuarios', 'sw'))->render();
                } else {
                    $resultado = view('administracion.usuario.modals.listadoUsuarios', compact('usuarios'))->render();
                }
                return response()->json(['html' => $resultado]);
            }
            return view('administracion.usuario.index', compact('usuarios'));
        } else {
            //$mensaje="Sesion Expirado....";
            //return redirect()->back()->with('mensaje', $mensaje);
            return view("layout.login");
        }
    }

    public function salirSesion()
    {
        Session::flush();
        $sucursales = Sucursal::where('estado_id', 1)->get();
        //return redirect()->route('/');
        return redirect()->guest('');
        //return view("layout.login",compact('sucursales'));
    }

    public function generarUsuario($id)
    {
        $queryUsuario = Persona::where('id', $id)->first();
        $concatenarUsuario = Str::lower(substr($queryUsuario->nombres, 0, 1)) . '' . Str::lower($queryUsuario->primerapellido);
        $verificaUsuario = Usuario::where('usuario', $concatenarUsuario)->count();
        if ($verificaUsuario > 0) {
            $resultadoConcatenar = $concatenarUsuario . '' . Str::lower(substr($queryUsuario->segundoapellido, 0, 1));
        } else {
            $resultadoConcatenar = $concatenarUsuario;
        }
        return response()->json(trim($resultadoConcatenar));
    }

    public function personasNoHabilitadas()
    {
        $personasRegistradas = Usuario::select('persona_id')->whereIn('estado_id', [1, 3])->get();
        $personas = Persona::whereNotIn('id', $personasRegistradas)
            ->orderBy('primerapellido', 'ASC')
            ->get();
        return response()->json($personas);
    }

    public function habilitarUsuario($id)
    {
        if (Session::has('AUTENTICADO')) {
            //ACTUALIZAMOS ROL
            $usuario = Usuario::find($id);
            $usuario->estado_id = 1;
            $usuario->save();
            return response()->json(["Mensaje" => "1"]);
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public function buscarPersonasNoRegistradas(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            //dd($request->all());
            $valor = $request['searchTerm'];
            $personasRegistradas = Usuario::select('persona_id')->whereIn('estado_id', [1, 3])->get();
            $personas = Persona::whereNotIn('id', $personasRegistradas)
                //->where('')
                ->where('primerapellido', 'like', '%' . strtoupper($valor) . '%')
                ->orderBy('primerapellido', 'ASC')
                ->orderBy('segundoapellido', 'ASC')
                //->orderBy('nombres','ASC')
                ->get();
            return response()->json($personas);
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public function resetearUsuario($id)
    {
        if (Session::has('AUTENTICADO')) {
            //ACTUALIZAMOS ROL
            $usuario = Usuario::find($id);
            $usuario->estado_id = 1;
            $usuario->clave = Hash::make(123456);
            $usuario->save();
            return response()->json(["Mensaje" => "1"]);
        } else {
            return response()->json(["Mensaje" => "-1"]);
        }
    }
}
