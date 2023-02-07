<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Módulo</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($modulos as $key => $modulo)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{ $modulo->modulo }}</td>					
					<td>
						<ul class="icons-list">
							@if($modulo->estado_id == 1)
								<li class="text-primary-600"><a href="#" onClick = "fnEditarModulo({{ $modulo}});" data-popup="tooltip" title="Editar" data-toggle="modal" data-target="#modalUpdateModulo"><i class="icon-pencil7"></i></a></li>

								<li class="text-danger-600"><a href="#" data-popup="tooltip" title="Eliminar" onClick = "fnEliminarModulo({{ $modulo->id }});""><i class="icon-trash"></i></a></li>
							@endif
							@if($modulo->estado_id == 2)
								<li class="text-primary-600"><a href="#" data-popup="tooltip" title="Habilitar" onClick = "fnHabilitarModulo({{ $modulo->id }});""><i class="icon-thumbs-up2"></i></a></li
							@endif	
						</ul>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>	
</div>
{{ $modulos->links() }}