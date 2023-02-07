<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
                <th>Nº</th>
                <th>Código</th>
                <th>Nombres</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th>Nro. CI</th>
                <th>Categoría</th>
			</tr>
		</thead>
		<tbody>
            @php
                $cont = 1;
            @endphp
			@foreach ($clientes as $key => $cliente_preferencial)
                <tr>
                    <td>{{$cont++}}</td>
                    <td>{{$cliente_preferencial->cliente->codigo}}</td>
                    <td>{{$cliente_preferencial->cliente->persona->nombres}}</td>
                    <td>{{$cliente_preferencial->cliente->persona->primerapellido}}</td>
                    <td>{{$cliente_preferencial->cliente->persona->segundoapellido}}</td>
                    <td>{{$cliente_preferencial->cliente->persona->nrodocumento}}</td>
                    <td>{{$cliente_preferencial->categoria->nombre}}</td>
                </tr>
			@endforeach
		</tbody>
	</table>	
</div>

@if(count($clientes) > 0)
{{ $clientes->links() }}
@endif
