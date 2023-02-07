<div id="modalUpdateUsuario" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Actualizar Usuario</h5>
			</div>
			<form id="frmUsuarioA">
				<div class="modal-body">
					<input id="id" type="hidden">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Datos de la Persona:<span class="text-danger">*</span></label>
								<input class="form-control" id="txtPersonaA" name="txtPersonaA" type="text" placeholder="Contraseña" readonly="readonly">
								<input id="idPersona" type="hidden">
		                        {{-- <select id="ddlPersonaA" name="ddlPersonaA" data-placeholder="Elige Nombre de la Persona" class="select" re>
		                            <option></option>
		                            @if(!empty($personas))
								  		@foreach($personas as $persona)
									    	<option value="{{ $persona->id }}"> {{ $persona->nombreCompleto() }}</option>
								  		@endforeach
									@endif
		                        </select> --}}
		                    </div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<div class="col-sm-12">
								<label>Nombre Usuario:<span class="text-danger">*</span></label>
								<input type="text" id="txtUsuarioA" name="txtUsuarioA" placeholder="Usuario" class="form-control" readonly="readonly">
							</div>							
						</div>


						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Rol de Asignación:</label>
			                        <select id="ddlRolA" name="ddlRolA" data-placeholder="Elige Rol" class="select">
			                            <option></option>
			                            @if(!empty($roles))
									  		@foreach($roles as $rol)
										    	<option value="{{ $rol->id }}"> {{ $rol->rol }}</option>
									  		@endforeach
										@endif
			                        </select>
			                    </div>
							</div>
						</div>						
						{{-- <div class="row">
							<div class="col-sm-12">
								<label>Contraseña:<span class="text-danger">*</span></label>
								<input class="form-control" id="txtContrasenaA" name="txtContrasenaA" type="password" placeholder="Contraseña" >
							</div>							
						</div> --}}

						{{-- <div class="row">
							<div class="col-sm-12">
								<label>Repita Contraseña:<span class="text-danger">*</span></label>
								<input class="form-control" id="txtContrasenaCopiaA" name="txtContrasenaCopiaA" type="password" placeholder="Contraseña">
							</div>							
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Rol de Asignación:</label>
			                        <select id="ddlRolA" name="ddlRolA" data-placeholder="Elige Rol" class="select">
			                            <option></option>
			                            @if(!empty($roles))
									  		@foreach($roles as $rol)
										    	<option value="{{ $rol->id }}"> {{ $rol->rol }}</option>
									  		@endforeach
										@endif
			                        </select>
			                    </div>
							</div>
						</div> --}}

						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Tipo de Destino:<span class="text-danger">*</span></label>							
	                                <div class="radio">
	                                    <label>
	                                        <input type="radio" id="rdoTipoDestino1A" name="rdoTipoDestinoA" value="NA">
	                                        Nacional
	                                    </label>
	                                </div>                                    
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label>.</label>						
	                                <div class="radio">
	                                    <label>
	                                        <input type="radio" id="rdoTipoDestino2A" name="rdoTipoDestinoA" value="N">
	                                        Departamental
	                                    </label>
	                                </div>                                    
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label>.</label>	
	                                <div class="radio">
	                                    <label>
	                                        <input type="radio" id="rdoTipoDestino3A" name="rdoTipoDestinoA" value="R">
	                                        Red
	                                    </label>
	                                </div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label>.</label>	
	                                <div class="radio">
	                                    <label>
	                                        <input type="radio" id="rdoTipoDestino4A" name="rdoTipoDestinoA" value="E">
	                                        Establecimiento
	                                    </label>
	                                </div>
								</div>
							</div>
						</div>

						<div id="divMostrarDepartamentoA" style='display:none;'>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Departamento:<span class="text-danger">*</span></label>
				                        <select id="ddlDepartamentoA" name="ddlDepartamentoA" data-placeholder="Elige Departamento" class="select">
				                            <option></option>
				                            @if(!empty($departamentos))
										  		@foreach($departamentos as $departamento)
											    	<option value="{{ $departamento->dpt_codigo }}"> {{ $departamento->dpt_nombre }}</option>
										  		@endforeach
											@endif
				                        </select>
				                    </div>
								</div>
								<div id="divMostrarRedA" style='display:none;'>
									<div class="col-md-4">
										<div class="form-group">
											<label>Red:<span class="text-danger">*</span></label>
					                        <select id="ddlRedA" name="ddlRedA" data-placeholder="Elige Red" class="select">		                            
					                        </select>
					                    </div>
									</div>
								</div>
								

								<div id="divMostrarEstableciemientoA" style='display:none;'>
									<div class="col-md-4">
										<div class="form-group">
											<label>Establecimiento:<span class="text-danger">*</span></label>
					                        <select id="ddlEstablecimientoA" name="ddlEstablecimientoA" data-placeholder="Elige Establecimiento" class="select">		                            
					                        </select>
					                    </div>
									</div>
								</div>

								
							</div>
	             		</div>	

						{{-- <div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Departamento:<span class="text-danger">*</span></label>
			                        <select id="ddlDepartamentoA" name="ddlDepartamentoA" data-placeholder="Elige DepartamentoA" class="select">
			                            <option></option>
			                            @if(!empty($departamentos))
									  		@foreach($departamentos as $departamento)
										    	<option value="{{ $departamento->dpt_codigo }}"> {{ $departamento->dpt_nombre }}</option>
									  		@endforeach
										@endif
			                        </select>
			                    </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Red:<span class="text-danger">*</span></label>
			                        <select id="ddlRedA" name="ddlRedA" data-placeholder="Elige Red" class="select">		                            
			                        </select>
			                    </div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label>Establecimiento:<span class="text-danger">*</span></label>
			                        <select id="ddlEstablecimientoA" name="ddlEstablecimientoA" data-placeholder="Elige Establecimiento" class="select">		                            
			                        </select>
			                    </div>
							</div>
						</div> --}}


					</div>
									
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="fnLimpiarControles();">Cancelar</button>
					{!!link_to('#',$title='Actualizar', $attributes=['id'=>'btnActualizar','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>