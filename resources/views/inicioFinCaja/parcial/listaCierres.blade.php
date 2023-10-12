<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Sucursal</th>
                <th>Caja</th>
                <th>Monto</th>
                <th>Fecha y hora de cierre</th>
                <th>Usuario</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lista_cierres as $key => $value)
                <tr>
                    <td>{{ $value->sucural1->nombre }}</td>
                    <td>{{ $value->caja }}</td>
                    <td>{{ number_format($value->inicio_caja_bs,2,".",",") }}</td>
                    <td>{{ $value->fecha_cierre }}</td>
                    <td>{{ $value->usuario->usuario }}</td>
                    <td>
                        <a href="#" onclick="fnEliminarCierre(event, '{{$value->id}}','{{$value->fecha_cierre}}','{{$value->sucural1->nombre}}','{{$value->caja}}');">Eliminar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {!! $lista_cierres->links() !!}
</div>
