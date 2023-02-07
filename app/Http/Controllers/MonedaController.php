<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Moneda;
use App\Sucursal;
use App\CambioMoneda;
use App\CompraVentaDolar;
use Session;

class MonedaController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {

            $monedas = Moneda::paginate(10);
            $sucursales = Sucursal::where('estado_id', 1)->get();

            $cambio = CambioMoneda::first();
            $compra_venta = CompraVentaDolar::orderBy('created_at', 'asc')->get()->last();

            if ($request->ajax()) {
                $resultado = view('monedas.modal.listaMonedas', compact('monedas', 'cambio'))->render();
                return response()->json(['html' => $resultado]);
            }
            return view('monedas.index', compact('monedas', 'cambio', 'compra_venta'));
        } else {
            return view("layout.login", compact('sucursales'));
        }
    }

    public function store(Request $request)
    {
    }

    public function update(Request $request)
    {
        $moneda = Moneda::find($request->id);
        $moneda->moneda = $request->moneda;
        $moneda->desc_corta = $request->desc_corta;
        $moneda->save();
        return response()->JSON([
            'sw' => true,
        ]);
    }

    public function actualizaCambio(Request $request)
    {
        $cambio = CambioMoneda::first();
        $cambio->valor_sus = $request->valor_sus;
        $cambio->valor_bs = $request->valor_bs;
        $cambio->save();
        return response()->JSON([
            'sw' => true,
        ]);
    }

    public function valor(Request $request)
    {
        $cambio = CambioMoneda::first();
        return response()->JSON([
            'sw' => true,
            'bs' => $cambio->valor_bs,
            'sus' => $cambio->valor_sus,
        ]);
    }
}
