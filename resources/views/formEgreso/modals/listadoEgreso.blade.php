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
				<tr>
					<td>{{ $dato->rownum }}</td>
					<td>{{ $dato->sucural->nombre }}</td>
					<td>{{ $dato->periodo }}</td>
					<td>{{ $dato->fecha_a }}</td>
					<td>{{ $dato->glosa }}</td>
					<td>{{ $dato->cod_deno }}</td>
					<td>{{ $dato->cuenta }}</td>
					<td>{{ $dato->caja }}</td>
					<td>{{ $dato->num_comprobante }}</td>
					<td>{{ $dato->haber }}</td>	
					<td><a href="#" onClick = "fnReimprimirEgreso({{ $dato->id }});" data-popup="tooltip" title="Imprimir"><i class="fa fa-fw fa-file-pdf-o"></i></a></td>			
				</tr>
			@endforeach
		</tbody>
	</table>
	<div class="" style="text-align:center;">
		{{$datosContaDiario->render()}}
	</div>
</div>