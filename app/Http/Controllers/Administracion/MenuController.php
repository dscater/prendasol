<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Perfil;


class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function menuMaster($id)
    {
        $queryMenuMaster = Perfil::join('opciones','opciones.id' ,'=','perfil.opcion_id')
            ->join('modulo','modulo.id','=','opciones.modulo_id')
            ->distinct()
            ->select('modulo.id', 'modulo.modulo','modulo.imagen')
            ->where('perfil.rol_id','=',$id)
            ->where('perfil.estado_id',1)
            ->get();
        //dd($queryMenuMaster);
        return $queryMenuMaster;
    }

    public function subMenus($id)
    {
        $querySubMenu = Perfil::join('opciones','opciones.id' ,'=','perfil.opcion_id')
            ->join('modulo','modulo.id','=','opciones.modulo_id')
            ->select('opciones.id', 'opciones.opcion','opciones.url','opciones.imagen')
            ->where('modulo.id','=',$id)
            ->where('perfil.estado_id',1)
            ->where('opciones.estado_id',1)
            ->where('perfil.rol_id',session::get('ID_ROL'))
            ->orderBy('opciones.orden','ASC')
            //->where('adm_perfil.id_acceso',session::get('id_acceso'))
            //->groupBy('adm_modulo.modulo')
            ->get();
        return $querySubMenu;
    }
}
