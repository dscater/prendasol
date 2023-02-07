<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Usuario;
use App\Persona;
use App\Rol;
use App\SucursalUsuario;
use App\Sucursal;

class UsuarioSucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {            
                         
            //$personasSucursales = SucursalUsuario::select('id_usuario')->whereIn('estado_id',[1,2])->get();
            //dd($personasSucursales);
            //$usuarios = Usuario::whereIn('id',$personasSucursales)->latest('created_at')->paginate(10); 
            $usuariosSucursales = SucursalUsuario::whereIn('estado_id',[1,2])->orderBy('id_sucursal','ASC')->orderBy('estado_id','ASC')->paginate(10); 
            //$usuariosTodos = Usuario::select('id')->where('estado_id',1)->get(); 
            //dd($usuarios);
            $personasRegistradas = Usuario::select('persona_id')->whereIn('estado_id',[1])->get();
            //dd($personasRegistradas);
            $personas = Persona::whereIn('id',$personasRegistradas)->get();
            //dd($usuariosSucursales);
            $roles = Rol::where('estado_id',1)->get();

            if ($request->ajax()) {
                $resultado = view('administracion.usuarioSucursal.modals.listadoUsuarios',compact('usuariosSucursales','roles'))->render(); 
                return response()->json(['html'=>$resultado]);
            }
            return view('administracion.usuarioSucursal.index',compact('usuariosSucursales','roles','personas'));
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
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {
                try {                    
                    $idUsuario = Usuario::where('persona_id',$request['ddlPersona'])->first();
                    $verificarUsuarioSucursal = SucursalUsuario::where('id_usuario',$idUsuario->id)
                        ->where('id_sucursal',$request['ddlSucursal'])
                        ->where('caja',$request['ddlCaja'])
                        ->count();
                    if ($verificarUsuarioSucursal == 0) {
                        SucursalUsuario::create([                        
                            'id_usuario'               => $idUsuario->id,
                            'id_sucursal'              => $request['ddlSucursal'],
                            'caja'                     =>  $request['ddlCaja'],                   
                            'estado_id'                => 1,
                            //'login_usu'                => session::get('ID_USUARIO'),
                        ]);
                        return response()->json(["Mensaje" => "1"]);
                    }
                    else{
                        return response()->json(["Mensaje" => "2"]);
                    }
                    
                    
                    
                } catch (Exception $e) {
                    //DB::rollback();
                    return response()->json(["Mensaje" => "-1"]);
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
            $usuario= SucursalUsuario::find($id);
            $usuario->estado_id=2;        
            $usuario->save();
            return response()->json(["Mensaje" => "1"]);            
        }
        else{
            return response()->json(["Mensaje" => "-1"]);
        }
    }

    public function listadoSucursalesNoAsignados($id){
        $queryUsuarioSucursal = SucursalUsuario::select('id_sucursal')->where('id_usuario',$id)->get();
        $querySucursal =Sucursal::where('estado_id',1)->whereNotIn('id',$queryUsuarioSucursal)->get();        
        return response()->json(["Mensaje" => $querySucursal]);
    }

    public function habilitarUsuarioSucursal($id)
    {
        if (Session::has('AUTENTICADO')) {     
            //ACTUALIZAMOS ROL
            $usuario= SucursalUsuario::find($id);
            $usuario->estado_id=1;        
            $usuario->save();
            return response()->json(["Mensaje" => "1"]);            
        }
        else{
            return response()->json(["Mensaje" => "-1"]);
        }
    }
}
