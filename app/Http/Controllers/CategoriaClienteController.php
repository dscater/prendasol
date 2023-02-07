<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoriaCliente;
use App\Cliente;
use App\Contrato;
use App\Pagos;
use App\ClienteCategoria;

class CategoriaClienteController extends Controller
{
    public function index(Request $request)
    {
        $categoria_clientes = CategoriaCliente::all();

        if ($request->ajax()) {
            $html = view('categoria_clientes.modals.lista_categorias', compact('categoria_clientes'))->render();
            return response()->JSON([
                'sw' => true,
                'html' => $html
            ]);
        }

        return view('categoria_clientes.index', compact('categoria_clientes'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        CategoriaCliente::create(array_map('mb_strtoupper', $request->all()));
        return response()->JSON([
            'sw' => true
        ]);
    }

    public function edit(CategoriaCliente $categoria_cliente)
    {
    }

    public function update(Request $request, CategoriaCliente $categoria_cliente)
    {
        $categoria_cliente->update(array_map('mb_strtoupper', $request->all()));
        return response()->JSON([
            'sw' => true
        ]);
    }

    public function destroy(CategoriaCliente $categoria_cliente)
    {
        $categoria_cliente->delete();
        return response()->JSON([
            'sw' => true
        ]);
    }

    public function clientes_preferenciales(Request $request)
    {
        $categorias = CategoriaCliente::all();
        return view('clientes_preferenciales.index', compact('categorias'));
    }

    public function obtiene_preferenciales(Request $request)
    {
        // $clientes = Cliente::select('cliente.*')
        //     ->join('contrato', 'contrato.cliente_id', '=', 'cliente.id')
        //     ->where('cliente.estado_id', 1)
        //     ->where('contrato.estado_pago', 'Credito cancelado')
        //     ->orderBy('cliente.id', 'ASC')
        //     ->distinct('cliente.id')
        //     ->paginate(10);

        // $array_clientes_preferenciales = [];
        // foreach ($clientes as $index => $cliente) {
        //     $contratos_cancelados = Contrato::where('estado_pago', 'Credito cancelado')
        //         ->where('cliente_id', $cliente->id)->get();
        //     foreach ($contratos_cancelados as $contrato_cancelado) {
        //         // BUSCAR  LOS ULTIMOS PAGOS DEL CONTRATO
        //         $contador_cancelado = 0;
        //         $pagos_contrato_cancelado = Pagos::where('contrato_id', $contrato_cancelado->id)
        //             ->whereOr('dias_atraso', '<=', 0)
        //             ->whereOr('dias_atraso', null)
        //             ->get()
        //             ->last();
        //         if ($pagos_contrato_cancelado) {
        //             $contador_cancelado++;
        //         }
        //     }
        //     // BUSCAR EN CATEGORIA DE CLIENTES
        //     $categoria_cliente = CategoriaCliente::where('numero_contratos', '<=', $contador_cancelado)
        //         ->orderBy('numero_contratos', 'ASC')
        //         ->get()
        //         ->last();
        //     if ($request->categoria != 'todos') {
        //         $categoria_cliente = CategoriaCliente::where('numero_contratos', '<=', $contador_cancelado)
        //             ->where('id',$request->categoria)
        //             ->orderBy('numero_contratos', 'ASC')
        //             ->get()
        //             ->last();
        //     }

        //     if ($categoria_cliente) {
        //         $array_clientes_preferenciales[] = [
        //             'cliente' => $cliente,
        //             'categoria' => $categoria_cliente
        //         ];
        //     } else {
        //         unset($clientes[$index]);
        //     }
        // }

        $clientes = ClienteCategoria::paginate(10);
        if ($request->categoria != 'todos') {
            $clientes = ClienteCategoria::where('categoria_id',$request->categoria)->paginate(10);
        }
        if ($request->ajax()) {
            $html = view('clientes_preferenciales.modals.lista_preferenciales', compact('clientes'))->render();
            return response()->JSON([
                'sw' => true,
                'html' => $html
            ]);
        }
    }
}
