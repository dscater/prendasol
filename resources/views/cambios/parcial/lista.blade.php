<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Sucursal</th>
                <th>Cliente</th>
                <th>Nit</th>
                <th>Usuario</th>
                <th>Monto</th>
                <th>Equivalencia</th>
                <th>Modo de Cambio</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cambios as $key => $value)
                @php
                    $tipo = '(VENTA)';
                @endphp
                @if ($value->modo_cambio == 'DÓLARES A BOLIVIANOS')
                    @php
                        $tipo = '(COMPRA)';
                    @endphp
                @endif
                <tr>
                    <td>{{ $value->fecha }}</td>
                    <td>{{ $value->sucursal->nombre }}</td>
                    <td>{{ $value->cliente }}</td>
                    <td>{{ $value->nit }}</td>
                    <td>{{ $value->usuario->usuario }}</td>
                    <td>{{ $value->monto }}</td>
                    <td>{{ $value->equivalencia }}</td>
                    <td>{{ $value->modo_cambio }} <small>{{$tipo}}</small></td>
                    <td class="opciones">
                        <a href="#" onclick="fnImprimirCambio($(this))"
                            data-url="{{ route('cambios.cambio_pdf', $value->id) }}"><i
                                class="fa fa-fw fa-file-pdf-o"></i></a>

                        @include('cambios.parcial.ver')
                        <a href="#" data-popup="tooltip" title="Ver" data-toggle="modal"
                            data-target="#modalVerCambio{{ $value->id }}" data-keyboard="false"><i
                                class="fa fa-fw fa-eye"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
