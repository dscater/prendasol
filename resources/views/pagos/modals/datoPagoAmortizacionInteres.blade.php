<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label>PAGO AMORTIZACIÓN INTERES</label>
			
        </div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label>CREDITO CON CAJA:</label>
			<input type="text" name="txtCodigoAI" id="txtCodigoAI" class="form-control" readonly>			
        </div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>CI:</label>
			<input type="text" name="txtCIAI" id="txtCIAI" class="form-control" readonly>			
        </div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>NOMBRES:</label>
			<input type="text" name="txtNombresAI" id="txtNombresAI" class="form-control" readonly>			
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
						<input type="text" name="txtVigenciaAI" id="txtVigenciaAI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>MONTO TOTAL:</label>
						<div class="input-group">
							<input type="text" name="txtMontoTotalAI" id="txtMontoTotalAI" class="form-control" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>	
				<div class="col-md-6">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtMontoTotalAI2" id="txtMontoTotalAI2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>					
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>PAGO(S):</label>
						<input type="text" name="txtPagosAI" id="txtPagosAI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>MONEDA:</label>
						<input type="text" name="txtMonedaAI" id="txtMonedaAI" class="form-control" value="Bs" readonly>			
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
						<input type="text" name="txtVenceElAI" id="txtVenceElAI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>CUOTA:</label>
						<input type="text" name="txtCuotaAI" id="txtCuotaAI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>No.:</label>
						<input type="text" name="txtNroAI" id="txtNroAI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS CUOTA:</label>
						<input type="text" name="txtDiasCuotaAI" id="txtDiasCuotaAI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">						
						<input type="hidden" name="txtFechaActualAI" id="txtFechaActualAI" value="{{ $fechaActual }}" class="form-control" readonly>			
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
						<input type="text" name="txtCoutasMoraAI" id="txtCoutasMoraAI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS TRANSCURRIDOS:</label>
						<input type="text" name="txtDiasTranscurridosAI" id="txtDiasTranscurridosAI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS PERMITIDOS:</label>
						<input type="text" name="txtDiasPermitidosAI" id="txtDiasPermitidosAI" class="form-control" value="-30" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS ATRASO:</label>
						<input type="text" name="txtDiasAtrasoAI" id="txtDiasAtrasoAI" class="form-control" readonly>			
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
							<input type="text" name="txtCapitalPagoTotalAI" id="txtCapitalPagoTotalAI" class="form-control" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>		
			        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtCapitalPagoTotalAI2" id="txtCapitalPagoTotalAI2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>					
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>TOTAL INTERES CAPITAL:</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaAI" id="txtInteresFechaAI" class="form-control" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
			        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaAI2" id="txtInteresFechaAI2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>AMORTIZACIÓN INTERES:</label>
						<input type="text" name="txtAmortizacionInteres" id="txtAmortizacionInteres" class="form-control" onchange="fnCalcularAmortizacionInteres(this.value)">			
			        </div>
				</div>	
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>TOTAL DE GASTOS ADMINISTRATIVOS:</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosAI" id="txtGastosAdministrativosAI" class="form-control" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
						<div id="mensajePagoAmortizacionInteres">Los calculos de interes y gastos estan considerando una amortización</div>	
			        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosAI2" id="txtGastosAdministrativosAI2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>
				{{-- <div class="col-md-6">
					<div class="form-group">
						<label>AMORTIZACIÓN GASTOS ADMINISTRATIVOS:</label>
						<input type="text" name="txtAmortizacionGAIA" id="txtAmortizacionGAIA" class="form-control" onchange="fnCalcularAmortizacionGastos(this.value)">
			        </div>
				</div> --}}
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>INTERESES MORATORIOS:</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioAI" id="txtInteresMoratorioAI" class="form-control" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioAI2" id="txtInteresMoratorioAI2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>
				{{-- <div class="col-md-6">
					<div class="form-group">
						<label>AMORTIZACIÓN INTERESES MORATORIOS:</label>
						<input type="text" name="txtAmortizacionIMIA" id="txtAmortizacionIMIA" class="form-control" onchange="fnCalcularAmortizacionMoratorios(this.value)">
			        </div>
				</div> --}}
			</div>
		
			{{-- <div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>SUMA TOTAL:</label>
						<div class="input-group">
							<input type="text" name="txtTotalAITotales" id="txtTotalAITotales" class="form-control" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>					
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtTotalAITotales2" id="txtTotalAITotales2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>						
			</div>
		 --}}
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>TOTAL CANCELADO:</label>
						<div class="input-group">
							<input type="text" name="txtTotalAI" id="txtTotalAI" class="form-control" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>					
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtTotalAI2" id="txtTotalAI2" class="form-control" value="" readonly>
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
    	<button type="button" class="btn btn-succes btn-labeled btn-sm" onClick="fnPagarContratoAmortizacionInteres(1);"><b><i class="icon-reload-alt"></i></b>Pagar</button>
       	<button type="button" class="btn btn-warning btn-labeled btn-sm" onClick="fnVolverContratos();"><b><i class="icon-reload-alt"></i></b>Volver</button>
    </div>
</div>	
	