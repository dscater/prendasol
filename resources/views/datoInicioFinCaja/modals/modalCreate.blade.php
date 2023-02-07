<div id="modalCreateInicioFinCaja" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Registrar Caja</h5>
			</div>

			<form id="frmDatoInicioCaja">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Sucursal:</label>
								<select class="form-control select2" id="ddlSucursal" name="ddlSucursal" data-placeholder="Seleccionar Sucursal" required>
					                <option></option>
					                @if(!empty($sucursales))
					                    @foreach($sucursales as $sucursal)
					                        <option value="{{ $sucursal->id }}"> {{ $sucursal->nombre }}</option>
					                    @endforeach
					                @endif
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
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>fecha:</label>
								<input type="text" id="txtFecha" name="txtFecha" placeholder="Fecha" class="form-control" data-mask required>
		                    </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Monto Inicial:</label>
								<input type="text" id="txtMonto" name="txtMonto" placeholder="Monto Inicial" class="form-control"  required>
		                    </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Moneda:</label>
									<select name="txtMoneda" id="txtMoneda" class="form-control">
										<option value="1">Bs</option>
										<option value="2">$us</option>
									</select>
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