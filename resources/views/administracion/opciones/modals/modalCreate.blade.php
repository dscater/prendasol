<div id="modalCreateOpciones" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Registrar Opción</h5>
			</div>

			<form id="frmOpcion">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Módulo:</label>								
		                        <select id="ddlModulo" name="ddlModulo" data-placeholder="Seleccione Módulo" class="select">
		                        	<option></option>
		                            @if(!empty($modulos))
								  		@foreach($modulos as $modulo)
									    	<option value="{{ $modulo->id }}"> {{ $modulo->modulo }}</option>
								  		@endforeach
									@endif
		                        </select>
		                    </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Opción:</label>
								<input type="text" id="txtOpcion" name="txtOpcion" placeholder="Opcion" class="form-control">
		                    </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>URL:</label>
								<input type="text" id="txtUrl" name="txtUrl" placeholder="URL" class="form-control">
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