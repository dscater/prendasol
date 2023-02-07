<div id="modalCreateEgresoCaja" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Registrar Egreso Caja</h5>
			</div>

			<form id="frmEgresoCaja">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
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
						<div class="col-md-6">
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
						<div class="col-md-6">
							<div class="form-group">
								<label>Fecha:</label>
								<input type="text" id="txtFecha" name="txtFecha" placeholder="Fecha" class="form-control"  data-mask required value="{{ $fechaActual }}" readonly>
		                    </div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Tipo de Movimiento:</label>
								<select class="form-control select2" id="ddlTipoMovimiento" name="ddlTipoMovimiento" data-placeholder="Seleccionar Tipo de Movimiento" required>
					                <option></option>
					                @if(!empty($cuentas))
					                    @foreach($cuentas as $cuenta)
					                        <option value="{{ $cuenta->id }}"> {{ $cuenta->descripcion }}</option>
					                    @endforeach
					                    <option value="{{ $cuenta1->id }}"> {{ $cuenta1->descripcion }}</option>
					                @endif               
					            </select>
		                    </div>
						</div>
					</div>

					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Monto:</label>
								<input type="text" id="txtMonto" name="txtMonto" placeholder="Monto Inicial" class="form-control"  required>
		                    </div>		                    
						</div>						
					</div>

					<div class="row">						
						<div class="col-md-12">
							<div class="form-group">
								<label>GLOSA:</label>
								<textarea id="txtGlosa" name="txtGlosa" placeholder="Glosa" class="form-control"  required></textarea>
		                    </div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>CI:</label>
								<input type="text" id="txtCI" name="txtCI" placeholder="CI" class="form-control"  required>
		                    </div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>CLIENTE:</label>
								<input type="text" id="txtCliente" name="txtCliente" placeholder="Cliente" class="form-control"  required>
		                    </div>
						</div>
					</div>

					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="limpiarControles();">Cancelar</button>
					<button id="btnRegistrar" class="btn btn-primary">Registrar</button>
					{{-- {!!link_to('#',$title='Registrar', $attributes=['id'=>'btnRegistrar','class'=>'btn btn-primary'], $secure=null)!!} --}}
				</div>
			</form>
		</div>
	</div>
</div>