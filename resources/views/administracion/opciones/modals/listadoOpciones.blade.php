<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Módulo</th>
				<th>Opción</th>
				<th>URL</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($opciones as $key => $opcion)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{ $opcion->modulo->modulo }}</td>
					<td>{{ $opcion->opcion }}</td>
					<td>{{ $opcion->url }}</td>
					<td>
						<ul class="icons-list">
							@if($opcion->estado_id == 1)
								<li class="text-primary-600"><a href="#" onClick = "fnEditarOpcion({{ $opcion}});" data-popup="tooltip" title="Editar" data-toggle="modal" data-target="#modalUpdateOpciones"><i class="icon-pencil7"></i></a></li>

								<li class="text-danger-600"><a href="#" data-popup="tooltip" title="Eliminar" onClick = "fnEliminarOpcion({{ $opcion->id }});""><i class="icon-trash"></i></a></li>
							@endif
							@if($opcion->estado_id == 2)
								<li class="text-primary-600"><a href="#" data-popup="tooltip" title="Habilitar" onClick = "fnHabilitarOpcion({{ $opcion->id }});""><i class="icon-thumbs-up2"></i></a></li
							@endif	
						</ul>
					</td>					
				</tr>	
			@endforeach
		</tbody>
	</table>	
</div>
{{ $opciones->links() }}