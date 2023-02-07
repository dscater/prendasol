<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CompraVentaDolar;

class CompraVentaDolarController extends Controller
{
    public function index(Request $request)
    {
        $compra_venta = CompraVentaDolar::orderBy('created_at', 'asc')->get()->last();
        if ($request->ajax()) {
            $resultado = view('monedas.modal.listaCompraVenta', compact('compra_venta'))->render();
            return response()->json(['html' => $resultado]);
        }
    }

    public function update(Request $request)
    {
        $nuevo = CompraVentaDolar::create($request->all());
        return response()->JSON([
            'sw' => true,
            'Mensaje' => 'Registros actualizados correctamente',
            'compra_venta' => $nuevo
        ]);
    }
}
