<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Nro. CI</th>
				<th>Nombre</th>
				<th>Paterno</th>
				<th>Materno</th>				
				<th>Usuario</th>
				<th>Rol</th>
				<th>Contraseña</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($usuarios as $key => $usuario)
				@if(isset($usuario->usuario))
					<tr>
						<td>
							{{$key+1}}
						</td>
						<td>
							@if(isset($usuario->persona->nrodocumento))
								{{ $usuario->persona->nrodocumento }}
							@endif

							@if(isset($usuario->nrodocumento))
								{{ $usuario->nrodocumento }}
							@endif							
						</td>
						<td>
							@if(isset($usuario->persona->nombres))
								{{ $usuario->persona->nombres }}
							@endif

							@if(isset($usuario->nombres))
								{{ $usuario->nombres }}
							@endif							
						</td>
						<td>
							@if(isset($usuario->persona->primerapellido))
								{{ $usuario->persona->primerapellido }}
							@endif

							@if(isset($usuario->primerapellido))
								{{ $usuario->primerapellido }}
							@endif							
						</td>
						<td>
							@if(isset($usuario->persona->segundoapellido))
								{{ $usuario->persona->segundoapellido }}
							@endif

							@if(isset($usuario->segundoapellido))
								{{ $usuario->segundoapellido }}
							@endif							
						</td>
						<td>
							@if(isset($usuario->usuario->usuario))
								{{ $usuario->usuario->usuario }}
							@endif
							@if(isset($usuario->usuario))
								{{ $usuario->usuario }}
							@endif
						</td>
						<td>
							{{ $usuario->usuarioRol->rol->rol }}													
						</td>	
						<td>
							@if ($usuario->usuarioRol->rol_id == 2)
								{{ $usuario->clave_texto }}
							@endif
						</td>					
						<td>
							@if(isset($sw))
								@if($usuario->usuarioSucursal->estado_id == 1 || $usuario->usuarioSucursal->estado_id == 3)
								<a href="#" data-popup="tooltip" title="Eliminar" onClick = "fnEliminarUsuarioSucursal({{ $usuario->id }});"><i class="fa fa-fw fa-thumbs-o-down"></i></a>
								@endif
								@if($usuario->usuarioSucursal->estado_id == 2)
								<a href="#" data-popup="tooltip" title="Habilitar" onClick = "fnHabilitarUsuarioSucursal({{ $usuario->id }});"><i class="fa fa-fw fa-thumbs-o-up"></i></a>
								@endif
							@else
								@if($usuario->estado_id == 1 || $usuario->estado_id == 3)
									<a href="#" data-popup="tooltip" title="Eliminar" onClick = "fnEliminarUsuario({{ $usuario->id }});"><i class="fa fa-fw fa-trash-o"></i></a>
								@endif									

								@if($usuario->estado_id == 2)
									<a href="#" data-popup="tooltip" title="Habilitar" onClick = "fnHabilitarUsuario({{ $usuario->id }});"><i class="fa fa-fw fa-thumbs-o-up"></i></a>
								@endif	
							@endif

						</td>
					</tr>
				@endif				
			@endforeach
		</tbody>
	</table>	
</div>

@if(isset($usuario->usuario))
	{{ $usuarios->links() }}
@endif