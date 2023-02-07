<div id="modalEditCategoria" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Registrar Categoría</h5>
			</div>
			<form id="frmCategoriasEdit">
				<div class="modal-body">
                    <input type="hidden" id="id">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Nombre categoría:<span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="e_nombre" class="form-control" required>
		                    </div>
                        </div>
                        <div class="col-sm-6">
							<div class="form-group">
								<label>Número de contratos pagados al día:<span class="text-danger">*</span></label>
								<input type="text" id="e_numero_contratos" name="numero_contratos" class="form-control">
							</div>							
						</div>
					</div>	
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>% de interés para contratos (1 - 100):<span class="text-danger">*</span></label>
								<input type="text" id="e_porcentaje" min="1" max="100" name="porcentaje" class="form-control">
							</div>							
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="fnLimpiarControles();">Cancelar</button>
					{!!link_to('#',$title='Registrar', $attributes=['id'=>'btnActualizar','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>