@if(isset($lista_morosidad))
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
			@foreach ($lista_morosidad as $key => $value)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{ $value->contrato->cliente->persona->nombres }}</td>
					<td>{{ $value->contrato->cliente->persona->primerapellido }}</td>
                    <td>{{ $value->contrato->cliente->persona->segundoapellido }}</td>
                    <td>{{ $value->contrato->cliente->persona->nrodocumento }}</td>
                    <td>{{$value->dias_atraso}}</td>
                    <td>{{ $value->total_capital }}</td>	
                    <td>{{$value->moneda->desc_corta}}</td>
				</tr>
			@endforeach						
		</tbody>
	</table>	
</div>
@endif