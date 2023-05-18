@if (isset($lista_morosidad))
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombres</th>
                    <th>Primer Apellido</th>
                    <th>Segundo Apellido</th>
                    <th>Nro. Documento</th>
                    <th>Días Mora</th>
                    <th>Total</th>
                    <th>Moneda</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $ennumeracion = 1;
                    $pagina = (int) $_GET['page'];
                    if ($pagina > 1) {
                        $ennumeracion = ($pagina - 1) * 20 + 1;
                    }
                @endphp
                @foreach ($lista_morosidad as $key => $value)
                    <tr>
                        <td>{{ $ennumeracion++ }}</td>
                        <td>{{ $value->cliente->persona->nombres }}</td>
                        <td>{{ $value->cliente->persona->primerapellido }}</td>
                        <td>{{ $value->cliente->persona->segundoapellido }}</td>
                        <td>{{ $value->cliente->persona->nrodocumento }}</td>
                        <td>{{ Carbon\Carbon::parse($value->fecha_fin)->diffInDays() }}</td>
                        <td>{{ $value->total_capital }}</td>
                        <td>{{ $value->moneda->desc_corta }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $lista_morosidad->links() }}
    </div>
@endif
