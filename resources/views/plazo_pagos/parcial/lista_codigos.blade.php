@if (isset($codigos))

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>CI</th>
                    <th>Nombre Completo</th>
                    <th>Codigo</th>
                    <th>Peso Bruto Total</th>
                    <th>Fecha Contrato</th>
                    <th>Fecha Fin</th>
                    <th>Capital</th>
                    <th>Total Capital</th>
                    <th>Interes</th>
                    <th>Comisión</th>
                    <th>Sucursal</th>
                    <th>Caja</th>
                    <th>Estado Pago</th>
                    <th>Estado</th>
                    <th>Estado Entrega</th>
                    <th colspan="2" class="text-center">Plazo de Pagos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($codigos as $key => $codigo)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        {{-- <td style="display:none;">{{$codigo->sucural}}</td> --}}
                        <td>{{ $codigo->cliente->persona->nrodocumento }}</td>
                        <td>{{ $codigo->cliente->persona->nombreCompleto() }}</td>
                        @if ($codigo->codigo != '')
                            <td>{{ $codigo->codigo }}</td>
                        @else
                            @php
                                $gestion = substr($codigo->gestion, 2, 2);
                                $rescodigo = $codigo->sucural->nuevo_codigo . '' . $gestion . '' . $codigo->codigo_num;

                            @endphp
                            <td>{{ $rescodigo }}</td>
                        @endif
                        <td>{{ $codigo->peso_total }}</td>
                        <td>
                            @if (isset($codigo->fecha_contrato))
                                {{ date('d-m-Y', strtotime($codigo->fecha_contrato)) }}
                            @endif
                        </td>
                        <td>
                            @if (isset($codigo->fecha_fin))
                                {{ date('d-m-Y', strtotime($codigo->fecha_fin)) }}
                            @endif
                        </td>
                        <td>{{ $codigo->capital }}</td>
                        <td>{{ $codigo->total_capital }}</td>
                        <td>{{ $codigo->interes }}</td>
                        <td>{{ $codigo->comision }}</td>
                        @if (isset($codigo->sucural->nombre))
                            <td>{{ $codigo->sucural->nombre }}</td>
                        @else
                            <td></td>
                        @endif

                        <td>{{ $codigo->caja }}</td>
                        <td>{{ $codigo->estado_pago }}</td>
                        <td>{{ $codigo->estado_pago_2 }}</td>
                        <td>{{ $codigo->estado_entrega }}</td>
                        <td style="display:none;">{{ $codigo->cliente->persona }}</td>
                        <td> <button
                                class="btn btn-success"onclick="abrirFormilarioPlazoPago('nuevo',{{ $codigo->id }},'{{ $codigo->codigo }}')"><i
                                    class="fa fa-plus"></i> Agregar</button>
                        </td>
                        <td> <button class="btn btn-primary"
                                onclick="verPlazoPagosContrato({{ $codigo->id }},'{{ $codigo->codigo }}','{{ $codigo->cliente->persona->nombreCompleto() }}')"><i
                                    class="fa fa-eye"></i> Ver registros</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-warning btn-labeled btn-sm" onClick="fnVolver();"><b><i
                        class="icon-reload-alt"></i></b>Volver</button>
        </div>
    </div>
@endif
