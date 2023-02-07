@if(isset($personas))
<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Codigo</th>
				<th>Nombres</th>
				<th>Primer Apellido</th>
				<th>Segundo Apellido</th>
				<th>Nro. CI</th>
				<th>Fecha Nacimiento</th>				
			</tr>
		</thead>
		<tbody>
			@foreach ($personas as $key => $persona)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{ $persona->cliente->codigo }}</td>
					<td>{{ $persona->nombres }}</td>
					<td>{{ $persona->primerapellido }}</td>
					<td>{{ $persona->segundoapellido }}</td>
					<td>{{ $persona->nrodocumento }}</td>	
					<td>@if(isset($persona->fechanacimiento)){{ date('d-m-Y', strtotime($persona->fechanacimiento)) }}@endif</td>									
					<td>
						<a href="#" onClick = "fnBuscarContratos({{ $persona->id }});" data-popup="tooltip" title="Editar"><i class="fa fa-fw fa-edit"></i></a>
					</td>
				</tr>
			@endforeach						
		</tbody>
	</table>	
</div>
{{ $personas->links() }}
@endif