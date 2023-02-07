<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Sucursal</th>
				<th>Periodo</th>
				<th>Fecha</th>				
				<th>Caja</th>
				<th>Comprobante</th>
				<th>Tipo Comprobante</th>
				<th>Referencia</th>				
			</tr>
		</thead>
		<tbody>
			@foreach ($datosContaDiario as $key => $dato)				
				<tr>
					<td>{{$key+1}}</td>
					<td>{{ $dato->sucural->nombre }}</td>
					<td>{{ $dato->periodo }}</td>
					<td>{{ $dato->fecha_a }}</td>
					<td>{{ $dato->caja }}</td>
					<td>{{ $dato->num_comprobante }}</td>
					<td>{{ $dato->tcom }}</td>
					<td>{{ $dato->ref }}</td>				
				</tr>
			@endforeach
		</tbody>
	</table>	
</div>
{{ $datosContaDiario->links() }}