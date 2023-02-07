<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label>REMATE DE CONTRATO</label>
			
        </div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label>CREDITO CON CAJA:</label>
			<input type="text" name="txtCodigoR" id="txtCodigoR" class="form-control" readonly>			
        </div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>CI:</label>
			<input type="text" name="txtCIR" id="txtCIR" class="form-control" readonly>			
        </div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>NOMBRES:</label>
			<input type="text" name="txtNombresR" id="txtNombresR" class="form-control" readonly>			
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
						<input type="text" name="txtVigenciaR" id="txtVigenciaR" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>MONTO TOTAL:</label>
						<div class="input-group">
							<input type="text" name="txtMontoTotalR" id="txtMontoTotalR" class="form-control" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
			        </div>
				</div>	
				<div class="col-md-6">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtMontoTotalR2" id="txtMontoTotalR2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>	
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>PAGO(S):</label>
						<input type="text" name="txtPagosR" id="txtPagosR" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>MONEDA:</label>
						<input type="text" name="txtMonedaR" id="txtMonedaR" class="form-control" value="Bs" readonly>			
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
						<input type="text" name="txtVenceElR" id="txtVenceElR" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>CUOTA:</label>
						<input type="text" name="txtCuotaR" id="txtCuotaR" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>No.:</label>
						<input type="text" name="txtNroR" id="txtNroR" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS CUOTA:</label>
						<input type="text" name="txtDiasCuotaR" id="txtDiasCuotaR" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">						
						<input type="hidden" name="txtFechaActualR" id="txtFechaActualR" value="{{ $fechaActual }}" class="form-control" readonly>			
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
						<input type="text" name="txtCoutasMoraR" id="txtCoutasMoraR" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS TRANSCURRIDOS:</label>
						<input type="text" name="txtDiasTranscurridosR" id="txtDiasTranscurridosR" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS PERMITIDOS:</label>
						<input type="text" name="txtDiasPermitidosR" id="txtDiasPermitidosR" class="form-control" value="-30" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS ATRASO:</label>
						<input type="text" name="txtDiasAtrasoR" id="txtDiasAtrasoR" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
		</fieldset>			
	</div>
</div>

<div class="row">	
	<div class="col-md-4">
		<fieldset>
			<legend>PAGO</legend>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>CAPITAL:</label>
						<div class="input-group">
							<input type="text" name="txtCapitalPagoTotalR" id="txtCapitalPagoTotalR" class="form-control" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>	
				<div class="col-md-6">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtCapitalPagoTotalR2" id="txtCapitalPagoTotalR2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>INTERES A LA FECHA:</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaR" id="txtInteresFechaR" class="form-control" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>		
			        </div>
				</div>	
				<div class="col-md-6">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaR2" id="txtInteresFechaR2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>GASTOS ADMINISTRATIVOS A LA FECHA:</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosR" id="txtGastosAdministrativosR" class="form-control" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>	
				<div class="col-md-6">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosR2" id="txtGastosAdministrativosR2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>							
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>INTERESES MORATORIOS:</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioR" id="txtInteresMoratorioR" class="form-control" value="Bs" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>		
				<div class="col-md-6">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioR2" id="txtInteresMoratorioR2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>TOTAL:</label>
						<div class="input-group">
							<input type="text" name="txtTotalR" id="txtTotalR" class="form-control" value="Bs" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>		
			        </div>
				</div>		
				<div class="col-md-6">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtTotalR2" id="txtTotalR2" class="form-control" value="" readonly>
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
    	<button type="button" class="btn btn-succes btn-labeled btn-sm" onClick="fnPagarRemateContrato(1);"><b><i class="icon-reload-alt"></i></b>Rematar</button>
       	<button type="button" class="btn btn-warning btn-labeled btn-sm" onClick="fnVolverContratos();"><b><i class="icon-reload-alt"></i></b>Volver</button>
    </div>
</div>	
