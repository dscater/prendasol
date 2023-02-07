<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label>PAGO EN EFECTIVO </label>
			
        </div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label>CREDITO CON CAJA:</label>
			<input type="text" name="txtCodigo" id="txtCodigo" class="form-control" readonly>			
        </div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>CI:</label>
			<input type="text" name="txtCI" id="txtCI" class="form-control" readonly>			
        </div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>NOMBRES:</label>
			<input type="text" name="txtNombres" id="txtNombres" class="form-control" readonly>			
        </div>
	</div>
</div>
<div class="row">	
	<div class="col-md-4">
		<fieldset>
			<legend>CREDITO</legend>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>VIGENCIA:</label>
						<input type="text" name="txtVigencia" id="txtVigencia" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>MONTO TOTAL:</label>
						<div class="input-group">
							<input type="text" name="txtMontoTotal" id="txtMontoTotal" class="form-control" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
					</div>
				</div>	
				<div class="col-md-6">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtMontoTotal2" id="txtMontoTotal2" class="form-control" readonly>	
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>						
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>PAGO(S):</label>
						<input type="text" name="txtPagos" id="txtPagos" class="form-control" readonly>
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>MONEDA:</label>
						<input type="text" name="txtMoneda" id="txtMoneda" class="form-control" value="Bs" readonly>
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS ATRASO DESCUENTO:</label>
						<input type="text" name="txtAtrasoDiasDescuentoT" id="txtAtrasoDiasDescuentoT" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
		</fieldset>			
	</div>
	<div class="col-md-4">
		<fieldset>
			<legend>PROXIMA CUOTA</legend>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>VENCE EL:</label>
						<input type="text" name="txtVenceEl" id="txtVenceEl" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>CUOTA:</label>
						<input type="text" name="txtCuota" id="txtCuota" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>No.:</label>
						<input type="text" name="txtNro" id="txtNro" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS CUOTA:</label>
						<input type="text" name="txtDiasCuota" id="txtDiasCuota" class="form-control" readonly>
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS DE COBRO ATRASO  DESCUENTO:</label>
						<input type="text" name="txtCobroDiasDescuentoT" id="txtCobroDiasDescuentoT" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">						
						<input type="hidden" name="txtFechaActualT" id="txtFechaActualT" value="{{ $fechaActual }}" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
		</fieldset>			
	</div>
	<div class="col-md-4">
		<fieldset>
			<legend>MOROSIDAD</legend>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>CUOTA(S) EN MORA:</label>
						<input type="text" name="txtCoutasMora" id="txtCoutasMora" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS TRANSCURRIDOS:</label>
						<input type="text" name="txtDiasTranscurridos" id="txtDiasTranscurridos" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS PERMITIDOS:</label>
						<input type="text" name="txtDiasPermitidos" id="txtDiasPermitidos" class="form-control" value="-30" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS ATRASO:</label>
						<input type="text" name="txtDiasAtraso" id="txtDiasAtraso" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
		</fieldset>			
	</div>
</div>

<div class="row">	
	<div class="col-md-12">
		<fieldset>
			<legend>PAGO</legend>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>CAPITAL:</label>
						<div class="input-group">
							<input type="text" name="txtCapitalPagoTotal" id="txtCapitalPagoTotal" class="form-control" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
					</div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtCapitalPagoTotal2" id="txtCapitalPagoTotal2" class="form-control" readonly>	
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>INTERES A LA FECHA:</label>
						<div class="input-group">
							<input type="text" name="txtInteresFecha" id="txtInteresFecha" class="form-control" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
			        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresFecha2" id="txtInteresFecha2" class="form-control" readonly>			
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>INTERES A LA FECHA CON DESCUENTO:</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaID" id="txtInteresFechaTD" class="form-control" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaTD2" id="txtInteresFechaTD2" class="form-control" readonly>			
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>GASTOS ADMINISTRATIVOS A LA FECHA:</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativos" id="txtGastosAdministrativos" class="form-control" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
						<div id="mensajePagoTotal">Los calculos de interes y gastos estan considerando una amortización</div>
			        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="">&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativos2" id="txtGastosAdministrativos2" class="form-control" readonly>			
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>GASTOS ADMINISTRATIVOS A LA FECHA CON DESCUENTO:</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosID" id="txtGastosAdministrativosTD" class="form-control" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>		
			        </div>
				</div>		
				<div class="col-md-3">
					<div class="form-group">
						<label for="">&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosTD2" id="txtGastosAdministrativosTD2" class="form-control" readonly>			
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>				
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>INTERESES MORATORIOS:</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorio" id="txtInteresMoratorio" class="form-control" value="Bs" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>		
			        </div>			        
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorio2" id="txtInteresMoratorio2" class="form-control" value="Bs" readonly>	
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>	
				<div class="col-md-3">
					<label>I. MORATORIOS CON DESCUENTO:</label>
					<div class="form-group">
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioID" id="txtInteresMoratorioTD" class="form-control" value="Bs" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
			        </div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioID2" id="txtInteresMoratorioTD2" class="form-control" value="Bs" readonly>	
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>TOTAL:</label>
						<div class="input-group">
							<input type="text" name="txtTotal" id="txtTotal" class="form-control" value="Bs" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
			        </div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtTotal2" id="txtTotal2" class="form-control" value="Bs" readonly>			
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>					
			</div>
		</fieldset>			
	</div>	
</div>


<div class="row">            
    <div class="col-md-12">
    	<button type="button" class="btn btn-succes btn-labeled btn-sm" onClick="registraSolicitud(1);"><b><i class="icon-reload-alt"></i></b>Pagar</button>
       	<button type="button" class="btn btn-warning btn-labeled btn-sm" onClick="fnVolverContratos();"><b><i class="icon-reload-alt"></i></b>Volver</button>
    </div>
</div>	
