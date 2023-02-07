<div id="modalCreateUsuarioSucursal" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Registrar Usuario Sucursal</h5>
			</div>
			<form id="frmUsuarioSucursal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Datos del Usuario:<span class="text-danger">*</span></label>
								<select class="form-control select2" style="width: 100%;" id="ddlPersona" name="ddlPersona" data-placeholder="Introduzca Primer Apelllio de la Persona">
		                        {{-- <select  class="select-minimum"> --}}
		                            <option></option>
		                            @if(!empty($personas))
					                    @foreach($personas as $persona)
					                        <option value="{{ $persona->id }}"> {{ $persona->nombreCompleto() }}</option>
					                    @endforeach
					                @endif
		                        </select>
		                    </div>
						</div>
					</div>	
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Rol de Asignación:</label>
		                        <select id="ddlSucursal" name="ddlSucursal" data-placeholder="Elige Rol" class="form-control select2 ddlSucursal">
		                            <option></option>		                            
		                        </select>
		                    </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Caja:</label>
		                        <select class="form-control select2" id="ddlCaja" name="ddlCaja" data-placeholder="Seleccionar Caja" required>
					                <option></option>
					                <option value="1">
					                    1
					                </option>
					                <option value="2">
					                    2
					                </option>                
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