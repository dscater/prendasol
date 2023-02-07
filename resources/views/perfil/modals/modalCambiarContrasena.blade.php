<div id="modalCambiarContrasena" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Cambiar Contraseña</h5>
			</div>
			<form id="frmContrasena">
				<div class="modal-body">					
					<div class="form-group">
						
						<div class="row">
							<div class="col-sm-12">
								<label>Contraseña:</label>
								<input class="form-control" id="txtContrasena" name="txtContrasena" type="password" placeholder="Contraseña">
							</div>							
						</div>

						<div class="row">
							<div class="col-sm-12">
								<label>Repita Contraseña:</label>
								<input class="form-control" id="txtContrasenaCopia" name="txtContrasenaCopia" type="password" placeholder="Contraseña">
							</div>							
						</div>
					</div>
									
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="fnLimpiarControles();">Cancelar</button>
					{!!link_to('#',$title='Aceptar', $attributes=['id'=>'btnCambiar','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>