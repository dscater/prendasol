<div id="modalUpdateModulo" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Actualizar Módulo</h5>
			</div>

			<form id="frmModuloA">
				<div class="modal-body">
					<input id="id" type="hidden">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Módulo:</label>
								<input type="text" id="txtModuloA" name="txtModuloA" placeholder="Módulo" class="form-control">
		                    </div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="limpiarControles();">Cancelar</button>
					{!!link_to('#',$title='Actualizar', $attributes=['id'=>'btnActualizar','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>