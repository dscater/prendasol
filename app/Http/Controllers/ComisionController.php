<?php

namespace App\Http\Controllers;

use App\CambioMoneda;
use App\Contrato;
use App\InteresAdministrable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ComisionController extends Controller
{
    public function comisionContrato(Request $request)
    {
        $monto = $request->monto;
        $moneda = $request->moneda;
        $contrato_id = 0;
        if (isset($request->contrato_id)) {
            $contrato_id = $request->contrato_id ? $request->contrato_id : 0;
        }
        // Log::debug($request);
        return response()->JSON([
            "interes_administrable" => $this->getInteresAdministrableMonto($monto, $moneda, $contrato_id)
        ]);
    }

    public function prueba()
    {
        $res = '';

        // PROBAR INTERES ADMINISTRABLE
        // $interesAdministrable = $this->getInteresAdministrableMonto(2000, 1);
        // $res = "$interesAdministrable->porcentaje";
        // return $res;

        // PROBAR MONTO TOTAL INTERES COMISION
        // $datosMonto = $this->calcularTotalInteresComision(5000, 3, 1);
        // return $datosMonto;

        // PROBAR VALOR INTERES Y COMISION
        // $datosValor = $this->getInteresComision(2000, 3, 1);
        // return $datosValor;
    }

    private function getInteresAdministrableMonto($monto, $moneda, $contrato_id = 0)
    {
        $interes_administrables = InteresAdministrable::orderBy("monto2", "asc")->where("tipo", "NUEVOS")->get();
        if ($contrato_id != 0) {
            $contrato = Contrato::find($contrato_id);
            if ($contrato) {
                $fecha_contrato = $contrato->fecha_contrato;

                $paraAntiguos = InteresAdministrable::orderBy("monto2", "asc")
                    ->where("fecha", ">=", $fecha_contrato)
                    ->where("tipo", "ANTIGUOS")
                    ->get();

                if (count($paraAntiguos) > 0) {
                    $interes_administrables = $paraAntiguos;
                }
            }
        }

        $cambio_moneda = CambioMoneda::first();
        $valor_bs = 0;
        if (!$cambio_moneda) {
            $valor_bs = 6.96;
        } else {
            $valor_bs = $cambio_moneda->valor_bs;
        }

        $elegido = null;
        $ultimo_item = null;
        foreach ($interes_administrables as $item) {
            $monto_comparacion = $item->monto2;
            if ($moneda == 2) {
                $monto_comparacion = $monto_comparacion / $valor_bs;
            }
            if ($monto < $monto_comparacion) {
                $elegido = $item;
                break;
            }
            $ultimo_item = $item;
        }

        // no se encuentra dentro de ningun rango, se asigna el último valor
        if (!$elegido) {
            $elegido = $ultimo_item;
        }

        return $elegido;
    }

    public function calcularTotalInteresComision($monto, $interes, $moneda)
    {
        $interesAdministrable = $this->getInteresAdministrableMonto((float)($monto), $moneda);
        $totalInteres = ($interes ?? 0) + $interesAdministrable->porcentaje;
        $total = ((float)$monto * $totalInteres) / 100;
        $comision = ((float)$monto * (float)($interesAdministrable->porcentaje)) / 100;
        $interes = ((float)$monto * $interes) / 100;
        return [$total, $interes, $comision];
    }

    public function getInteresComision($monto, $interes, $moneda)
    {
        $interesAdministrable = $this->getInteresAdministrableMonto((float)($monto), $moneda);
        $totalInteres = ($interes ?? 0) + $interesAdministrable->porcentaje;
        return [$totalInteres, $interesAdministrable->porcentaje];
    }
}
