<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Sucursal</th>
                <th>Periodo</th>
                <th>Fecha</th>
                <th>Glosa</th>
                <th>Codigo</th>
                <th>Cuenta</th>
                <th>Caja</th>
                <th>Comprobante</th>
                <th>Monto</th>
                <th></th>

            </tr>
        </thead>
        <tbody>
            @foreach ($datosContaDiario as $key => $dato)
                @if ($dato->debe > 0)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $dato->sucural->nombre }}</td>
                        <td>{{ $dato->periodo }}</td>
                        <td>{{ $dato->fecha_a }}</td>
                        <td>{{ $dato->glosa }}</td>
                        <td>{{ $dato->cod_deno }}</td>
                        <td>{{ $dato->cuenta }}</td>
                        <td>{{ $dato->caja }}</td>
                        <td>{{ $dato->num_comprobante }}</td>
                        <td>{{ number_format($dato->debe, 2, '.', ',') }}</td>
                        <td><a href="#" onClick = "fnReImprimirIngreso({{ $dato->id }});" data-popup="tooltip"
                                title="Imprimir"><i class="fa fa-fw fa-file-pdf-o"></i></a></td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
