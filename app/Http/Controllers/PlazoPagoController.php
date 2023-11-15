<?php

namespace App\Http\Controllers;

use App\PlazoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\InicioFinCaja;
use App\InicioFinCajaDetalle;
use App\Persona;
use App\Sucursal;
use Carbon\Carbon;
use Session;

class PlazoPagoController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('AUTENTICADO')) {
            $fecha_actual = Carbon::now('America/La_Paz')->format('Y-m-d');
            $fecha_comparacion = date("Y-m-d", strtotime($fecha_actual . '-1 days'));
            if ($request->ajax()) {
                $plazo_pagos = [];

                if ($request->contrato_id != 0) {
                    $plazo_pagos = PlazoPago::select("plazo_pagos.*")
                        ->where("contrato_id", $request->contrato_id)
                        ->where("fecha_proximo_pago", ">=", $fecha_comparacion)
                        ->orderBy("fecha_proximo_pago", "asc")
                        ->orderBy("id", "asc")
                        ->paginate(10);
                } else {
                    $plazo_pagos = PlazoPago::select("plazo_pagos.*")
                        ->where("fecha_proximo_pago", ">=", $fecha_comparacion)
                        ->orderBy("fecha_proximo_pago", "asc")
                        ->orderBy("id", "asc")
                        ->paginate(10);
                }

                $lista_html = view("plazo_pagos.parcial.lista_registros", compact("plazo_pagos", "fecha_comparacion", "fecha_actual"))->render();
                return response()->JSON([
                    "lista_html" => $lista_html
                ]);
            }

            return view('plazo_pagos.index', compact('fecha_comparacion'));
        } else {
            return view("layout.login");
        }
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            PlazoPago::create([
                "contrato_id" => $request->contrato_id,
                "descripcion" => mb_strtoupper($request->descripcion),
                "fecha_proximo_pago" => Carbon::parse($request['fecha_proximo_pago'])->format('Y-m-d'),
            ]);

            DB::commit();
            return response()->JSON([
                "Mensaje" => 1
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                "message" => $e->getMessage(),
                "Mensaje" => 0
            ]);
        }
    }

    public function edit()
    {
    }

    public function update(PlazoPago $plazo_pago, Request $request)
    {
        DB::beginTransaction();
        try {
            $plazo_pago->update([
                "contrato_id" => $request->contrato_id,
                "descripcion" => mb_strtoupper($request->descripcion),
                "fecha_proximo_pago" => Carbon::parse($request['fecha_proximo_pago'])->format('Y-m-d'),
            ]);

            DB::commit();
            return response()->JSON([
                "Mensaje" => 1
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                "message" => $e->getMessage(),
                "Mensaje" => 0
            ]);
        }
    }

    public function destroy(PlazoPago $plazo_pago)
    {
        DB::beginTransaction();
        try {
            $plazo_pago->delete();
            DB::commit();
            return response()->JSON([
                "Mensaje" => 1
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                "message" => $e->getMessage(),
                "Mensaje" => 0
            ]);
        }
    }
}
