@if (isset($contratos))
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Cliente: </label> {{ $cliente->persona->nombreCompleto() }}
                <input type="hidden" id="txtNombreClienteOculto" name="txtNombreClienteOculto"
                    value="{{ $cliente->persona->nombreCompleto() }}">
                <input type="hidden" id="txtCodigoClienteOculto" name="txtCodigoClienteOculto"
                    value="{{ $cliente->codigo }}">
                <input type="hidden" id="txtIdClienteOculto" name="txtIdClienteOculto" value="{{ $cliente->id }}">
                <input type="hidden" id="txtIdPersonaOculto" name="txtIdPersonaOculto"
                    value="{{ $cliente->persona_id }}">
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
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
                @foreach ($contratos as $key => $contrato)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        {{-- <td style="display:none;">{{$contrato->sucural}}</td> --}}
                        @if ($contrato->codigo != '')
                            <td>{{ $contrato->codigo }}</td>
                        @else
                            @php
                                $gestion = substr($contrato->gestion, 2, 2);
                                $rescodigo = $contrato->sucural->nuevo_codigo . '' . $gestion . '' . $contrato->codigo_num;

                            @endphp
                            <td>{{ $rescodigo }}</td>
                        @endif
                        <td>{{ $contrato->peso_total }}</td>
                        <td>
                            @if (isset($contrato->fecha_contrato))
                                {{ date('d-m-Y', strtotime($contrato->fecha_contrato)) }}
                            @endif
                        </td>
                        <td>
                            @if (isset($contrato->fecha_fin))
                                {{ date('d-m-Y', strtotime($contrato->fecha_fin)) }}
                            @endif
                        </td>
                        <td>{{ $contrato->capital }}</td>
                        <td>{{ $contrato->total_capital }}</td>
                        <td>{{ $contrato->interes }}</td>
                        <td>{{ $contrato->comision }}</td>
                        @if (isset($contrato->sucural->nombre))
                            <td>{{ $contrato->sucural->nombre }}</td>
                        @else
                            <td></td>
                        @endif

                        <td>{{ $contrato->caja }}</td>
                        <td>{{ $contrato->estado_pago }}</td>
                        <td>{{ $contrato->estado_pago_2 }}</td>
                        <td>{{ $contrato->estado_entrega }}</td>
                        <td style="display:none;">{{ $contrato->cliente->persona }}</td>
                        <td> <button class="btn btn-success"
                                onclick="abrirFormilarioPlazoPago('nuevo',{{ $contrato->id }},'{{ $contrato->codigo }}')"><i
                                    class="fa fa-plus"></i> Agregar</button>
                        </td>
                        <td> <button class="btn btn-primary"
                                onclick="verPlazoPagosContrato({{ $contrato->id }},'{{ $contrato->codigo }}','{{ $contrato->cliente->persona->nombreCompleto() }}')"><i
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
