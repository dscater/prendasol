<div id="modalCreateRegistroContable" class="modal fade" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Registrar Registro Contables</h5>
			</div>

			<form id="frmRegsitroContable">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Tipo Comprobante:</label>
								<select class="form-control select2" id="ddlTipoComprobante" name="ddlTipoComprobante" data-placeholder="Seleccionar Sucursal" required>
					                <option></option>
					                <option value="INGRESO">
					                    INGRESO
					                </option>
					                <option value="EGRESO">
					                    EGRESO
					                </option> 
					                <option value="DIARIO">
					                    DIARIO
					                </option>  
					            </select>
		                    </div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Fecha:</label>
								<input type="text" id="txtFecha" name="txtFecha" placeholder="Fecha" class="form-control" data-mask required>
		                    </div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<table class="table table-striped- table-bordered table-hover table-checkable" id="tabla_cuentas">
	    	                        <thead>
	                                    <tr>
	                                        <th width="20%">Codigo</th>
		                                    <th width="30%">Nombre Cuenta</th> 
		                                    <th width="30%">Glosa</th>  
		                                    <th width="10%">DEBE</th>
		                                    <th width="10%">HABER</th>
		                                    
	                                    </tr>
	                                </thead>
	                                <tbody id="detalle_cuentas">
	                                </tbody>
	                                <tfoot>
	          	                        <tr>
	          	                        	<td></td>
	          	                        	<td></td>
	          	                        	<td></td>	                                  		
	                                  		<td><span id="total_debe" class="text-success">0.00</span></td>
	                                  		<td><span id="total_haber" class="text-success">0.00</span></td>
	                                  		
	          	                        </tr>
	                                </tfoot>
	                            </table>
	                            <a href="#" class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air" onclick="agregar_fila_cuenta()" id="boton_nueva_fila">
	                                <span>
	                                    <i class="la la-plus"></i>
	                                    <span>
	                                        Nueva fila
	                                    </span>
	                                </span>
	                            </a>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="limpiarControles();">Cancelar</button>
					{!!link_to('#',$title='Registrar', $attributes=['id'=>'btnRegistrar','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>