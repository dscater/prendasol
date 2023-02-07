<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ValorOro;

class ValorOroController extends Controller
{

    public function valor_oro()
    {
        $valor_oro = ValorOro::first();
        return response()->JSON([
            'sw' => true,
            'valor_oro' => $valor_oro
        ]);
    }

    public function update(Request $request)
    {
        $valor_oro = ValorOro::first();
        $valor_oro->update($request->all());

        return response()->JSON([
            'sw' => true,
            'valor_oro' => $valor_oro
        ]);
    }
}
