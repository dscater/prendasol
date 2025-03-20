<?php

namespace App\Http\Controllers;

use App\InteresAdministrable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteresAdministrableController extends Controller
{
    public function index()
    {
        $interes_administrables = InteresAdministrable::all();
        return view("interes_administrables.index", compact("interes_administrables"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "monto1" => "required|numeric|min:0",
            "monto2" => "required|numeric|min:0",
            "porcentaje" => "required|numeric|min:0",
        ], [
            "monto1.required" => "Debes completar el campo Monto Desde",
            "monto1.numeric" => "Monto desde debe ser un valor númerico",
            "monto1.min" => "Monto desde debe ser minimo :min",
            "monto2.required" => "Debes completar el campo Monto hasta",
            "monto2.numeric" => "Monto hasta debe ser un valor númerico",
            "monto2.min" => "Monto desde debe ser minimo :min",
            "porcentaje.required" => "Debes completar el campo Porcentaje",
            "porcentaje.numeric" => "Porcentaje debe ser un valor númerico",
            "porcentaje.min" => "Monto desde debe ser minimo :min",
        ]);
        DB::beginTransaction();
        try {
            $existe = InteresAdministrable::where("monto1", $request->monto1)
                ->where("monto2", $request->monto2)->get()->first();
            if ($existe) {
                throw new Exception("Existe");
            }
            InteresAdministrable::create([
                "monto1" => $request["monto1"],
                "monto2" => $request["monto2"],
                "porcentaje" => $request["porcentaje"],
            ]);
            DB::commit();
            return redirect()->back()->with("bien", "Registro éxitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e->getMessage() == 'Existe') {
                return redirect()->back()->with("error", "Esos montos ya fueron registrados");
            }
            return redirect()->back()->with("error", "Ocurrió un error no se pudo registrar");
        }
    }

    public function show(InteresAdministrable $interes_administrable) {}

    public function update(InteresAdministrable $interes_administrable, Request $request)
    {
        $request->validate([
            "monto1" => "required|numeric|min:0",
            "monto2" => "required|numeric|min:0",
            "porcentaje" => "required|numeric|min:0",
        ], [
            "monto1.required" => "Debes completar el campo Monto Desde",
            "monto1.numeric" => "Monto desde debe ser un valor númerico",
            "monto1.min" => "Monto desde debe ser minimo :min",
            "monto2.required" => "Debes completar el campo Monto hasta",
            "monto2.numeric" => "Monto hasta debe ser un valor númerico",
            "monto2.min" => "Monto desde debe ser minimo :min",
            "porcentaje.required" => "Debes completar el campo Porcentaje",
            "porcentaje.numeric" => "Porcentaje debe ser un valor númerico",
            "porcentaje.min" => "Monto desde debe ser minimo :min",
        ]);
        DB::beginTransaction();
        try {
            $existe = InteresAdministrable::where("monto1", $request->monto1)
                ->where("monto2", $request->monto2)
                ->where("id", "!=", $interes_administrable->id)->get()->first();
            if ($existe) {
                throw new Exception("Existe");
            }
            $interes_administrable->update([
                "monto1" => $request["monto1"],
                "monto2" => $request["monto2"],
                "porcentaje" => $request["porcentaje"],
            ]);
            DB::commit();
            return redirect()->back()->with("bien", "Actualización éxitosa");
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e->getMessage() == 'Existe') {
                return redirect()->back()->with("error", "Esos montos ya fueron registrados");
            }
            return redirect()->back()->with("error", "Ocurrió un error no se pudo actualizar");
        }
    }

    public function destroy(InteresAdministrable $interes_administrable, Request $request)
    {
        DB::beginTransaction();
        try {
            $interes_administrable->delete();
            DB::commit();
            if ($request->ajax()) {
                return response()->JSON(true);
            }
            return redirect()->back()->with("bien", "Registro eliminado");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", "Ocurrió un error no se pudo eliminar");
        }
    }
}
