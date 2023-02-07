<?php

namespace App\Http\Controllers\Perfil;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use App\Usuario;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Session::has('AUTENTICADO')) {            
            $personas = Usuario::whereIn('estado_id',[1,3])->where('id',session::get('ID_USUARIO'))->first();
            //dd($personas->persona->nombreCompleto());            
            return view('perfil.index',compact('personas'));
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
        if (Session::has('AUTENTICADO')) {
            if ($request->ajax()) {                                
                //ACTUALIZAMOS CONTRASEÑA
                $usuario= Usuario::find(Session::get('ID_USUARIO'));
                $usuario->clave=Hash::make($request['txtContrasena']);
                $usuario->clave_texto=$request['txtContrasena'];
                $usuario->estado_id =1;
                $usuario->save();
                return response()->json(["Mensaje" => "1"]);
                
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
        //
    }
}
