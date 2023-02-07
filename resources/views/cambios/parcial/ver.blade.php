@php
$color_modo = 'bg-blue';
$titulo = 'VER REGISTRO - VENTA';
$tipo = '(VENTA)';
@endphp
@if ($value->modo_cambio == 'DÓLARES A BOLIVIANOS')
    @php
        $color_modo = 'bg-green';
        $titulo = 'VER REGISTRO - COMPRA';
        $tipo = '(COMPRA)';
    @endphp
@endif
<div id="modalVerCambio{{ $value->id }}" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{ $titulo }}</h5>
            </div>
            <div class="modal-body">
                <input id="id" type="hidden">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="{{ $color_modo }}">
                                    <th colspan="2">CAMBIO DE {{ $value->modo_cambio }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="130px">Sucursal: </td>
                                    <td>{{ $value->sucursal->nombre }}</td>
                                </tr>
                                <tr>
                                    <td>Fecha:</td>
                                    <td>{{ $value->fecha }}</td>
                                </tr>
                                <tr>
                                    <td>Señor(es):</td>
                                    <td>{{ $value->cliente }}</td>
                                </tr>
                                <tr>
                                    <td>CI/NIT:</td>
                                    <td>{{ $value->nit }}</td>
                                </tr>
                                <tr>
                                    <td>Usuario:</td>
                                    <td>{{ $value->usuario->usuario }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table">
                            <tbody>
                                <tr class="{{ $color_modo }}">
                                    <td>Efectivo</td>
                                    <td>Cotiz.</td>
                                    <td>Equivalente</td>
                                </tr>
                                <tr>
                                    <td>{{ $value->monto }}</td>
                                    @if ($value->modo_cambio == 'DÓLARES A BOLIVIANOS')
                                        <td>{{ $compra_venta->compra_bs }}</td>
                                    @else
                                        <td>{{ $compra_venta->venta_bs }}</td>
                                    @endif
                                    <td>{{ $value->equivalencia }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        {{ $nl->numtoletras(\number_format($value->equivalencia, 2, '.', '')) }}
                                        @if ($value->modo_cambio == 'DÓLARES A BOLIVIANOS')
                                            Bolivianos
                                        @else
                                            Dólares
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
