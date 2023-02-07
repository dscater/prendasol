<div id="modalUpdateContaInicioCaja" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Actualizar Conta Diario</h5>
			</div>
			<form id="frmContaDiario">                
				<div class="modal-body">
                    <input id="id" type="hidden">                    
					<div class="row">    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CI:</label>
                                <input type="text" name="txtCI" id="txtCI" class="form-control" readonly>
                            </div>
                        </div> 

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NOMBRE COMPLETO:</label>
                                <input type="text" name="txtNombreCompleto" id="txtNombreCompleto" class="form-control" readonly>
                            </div>
                        </div> 

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CONTRATO:</label>
                                <input type="text" name="txtContrato" id="txtContrato" class="form-control" readonly>
                            </div>
                        </div>         
                    </div>
                    <div class="row">    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>SUCURSAL:</label>
                                <input type="text" name="txtSucursal" id="txtSucursal" class="form-control" readonly>
                            </div>
                        </div> 

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>FECHA PAGO:</label>
                                <input type="text" name="txtFecha" id="txtFecha" class="form-control" readonly>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>TIPO DE MOVIMIENTO:</label>
                                <input type="text" name="txtGlosa" id="txtGlosa" class="form-control" readonly>
                            </div>
                        </div>         
                    </div> 

                    <div class="row">    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>INGRESO Bs:</label>
                                <input type="text" name="txtDebe" id="txtDebe" class="form-control">
                            </div>
                        </div> 

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>EGRESO Bs:</label>
                                <input type="text" name="txtHaber" id="txtHaber" class="form-control">
                            </div>
                        </div>  
                    </div> 
                
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
					{!!link_to('#',$title='Actualizar', $attributes=['id'=>'btnActualizar','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>