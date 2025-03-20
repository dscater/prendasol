<?php

namespace App\Http\Controllers\Parametro;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use App\PrecioOro;
use App\Sucursal;
use Carbon\Carbon;
use App\ValorOro;

class PrecioOroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {

            $fecha = Carbon::now('America/La_Paz')->format('Y-m-d');
            if (isset($request->fecha)) {
                $fecha = $request->fecha;
            }

            $precios = PrecioOro::where('fecha', $fecha)->paginate(10);
            $sucursales = Sucursal::where('estado_id', 1)->get();

            if ($request->ajax()) {
                $resultado = view('precioOro.modal.listaPrecios', compact('precios'))->render();
                return response()->json(['html' => $resultado]);
            }

            $valor_oro = ValorOro::first();

            return view('precioOro.index', compact('precios','valor_oro'));
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
        $precio = PrecioOro::find($id);
        $precio->update($request->all());
        // $precio->dies = $request->dies;
        // $precio->catorce = $request->catorce;
        // $precio->diesiocho = $request->diesiocho;
        // $precio->veinticuatro = $request->veinticuatro;
        // $precio->save();

        return response()->JSON([
            'sw' => true,
        ]);
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

    public function obtnerPrecioOro(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $precioOro = PrecioOro::where('fecha', $request['fechaActual'])->first();
            if ($precioOro) {
                return response()->json(["Resultado" => $precioOro]);
            }
        } else {
            return view("layout.login");
        }
    }
}
