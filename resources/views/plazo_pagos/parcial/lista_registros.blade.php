<div class="col-md-12">
    <h4 class="w-100 text-center font-weight-bold">LISTA DE PLAZOS DE PAGOS</h4>
</div>
<div class="col-md-12" style="padding:0px;">
    <div class="panel panel-default"style="overflow: auto;">
        <table class="table table-bordered panel-body">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Codigo contrato</th>
                    <th>Cliente</th>
                    <th>Descripción</th>
                    <th>Fecha próximo pago</th>
                    <th colspan="2">Acción</th>
                </tr>
            </thead>
            <tbody>
                @if (count($plazo_pagos) > 0)
                    @foreach ($plazo_pagos as $value)
                        @php
                            $clase = '';
                            if ($value->fecha_proximo_pago == $fecha_comparacion) {
                                $clase = 'bg-rojo';
                            } elseif ($value->fecha_proximo_pago == $fecha_actual) {
                                $clase = 'bg-amarillo';
                            }
                        @endphp
                        <tr class="{{ $clase }}">
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->contrato->codigo }}</td>
                            <td>{{ $value->contrato->cliente->persona->nombreCompleto() }}</td>
                            <td>{{ $value->descripcion }}</td>
                            <td>{{ $value->fecha_proximo_pago }}</td>
                            <td width="2%"><button class="btn btn-sm btn-danger"
                                    onclick="eliminarRegistro({{ $value->id }})"><i class="fa fa-trash"></i></button>
                            </td>
                            <td width="2%"><button class="btn btn-sm btn-warning"
                                    onclick="abrirFormilarioPlazoPago('editar',{{ $value->contrato->id }},'{{ $value->contrato->codigo }}',{{ $value->id }},'{{ $value->descripcion }}','{{ $value->fecha_proximo_pago_t }}')"><i
                                        class="fa fa-edit"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">SIN REGISTROS</td>
                    </tr>
                @endif
            </tbody>
        </table>
        @if (count($plazo_pagos) > 0)
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        {{ $plazo_pagos->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
