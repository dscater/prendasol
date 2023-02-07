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
						@if($persona->estado_id == 1)
							<a href="#" onClick = "fnEditarPersona({{ $persona}});" data-popup="tooltip" title="Editar" data-toggle="modal" data-target="#modalUpdatePersona" data-keyboard="false"><i class="fa fa-fw fa-edit"></i></a>

							<a href="#" data-popup="tooltip" title="Eliminar" onClick = "fnEliminarPersona({{ $persona->id }});"><i class="fa fa-fw fa-trash-o"></i></a>
						@endif
						@if($persona->estado_id == 2)
							<a href="#" data-popup="tooltip" title="Habilitar" onClick = "fnHabilitarPersona({{ $persona->id }});"><i class="fa fa-fw fa-thumbs-o-up"></i></a>
						@endif
					</td>
				</tr>
			@endforeach						
		</tbody>
	</table>	
</div>
{{ $personas->links() }}