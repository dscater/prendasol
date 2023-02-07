<div id="modalCreateUsuario" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Registrar Usuario</h5>
			</div>
			<form id="frmUsuario">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Datos de la Persona:<span class="text-danger">*</span></label>
								<select class="form-control select2" style="width: 100%;" id="ddlPersona" name="ddlPersona" data-placeholder="Introduzca Primer Apelllio de la Persona">
		                        {{-- <select  class="select-minimum"> --}}
		                            <option></option>
		                        </select>
		                    </div>
						</div>
					</div>	
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Nombre Usuario:<span class="text-danger">*</span></label>
								<input type="text" id="txtUsuario" name="txtUsuario" placeholder="Usuario" class="form-control" readonly="true">
							</div>							
						</div>
					</div>

					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Rol de Asignación:</label>
		                        <select id="ddlRol" name="ddlRol" data-placeholder="Elige Rol" class="select">
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
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="fnLimpiarControles();">Cancelar</button>
					{!!link_to('#',$title='Registrar', $attributes=['id'=>'btnRegistrar','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>