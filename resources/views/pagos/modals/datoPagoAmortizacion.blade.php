<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label>PAGO AMORTIZACIÓN </label>
			
        </div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label>CREDITO CON CAJA:</label>
			<input type="text" name="txtCodigoA" id="txtCodigoA" class="form-control" readonly>			
        </div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>CI:</label>
			<input type="text" name="txtCIA" id="txtCIA" class="form-control" readonly>			
        </div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>NOMBRES:</label>
			<input type="text" name="txtNombresA" id="txtNombresA" class="form-control" readonly>			
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
						<input type="text" name="txtVigenciaA" id="txtVigenciaA" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>MONTO TOTAL:</label>
						<div class="input-group">
							<input type="text" name="txtMontoTotalA" id="txtMontoTotalA" class="form-control" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>	
				<div class="col-md-6">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtMontoTotalA2" id="txtMontoTotalA2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>					
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>PAGO(S):</label>
						<input type="text" name="txtPagosA" id="txtPagosA" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>MONEDA:</label>
						<input type="text" name="txtMonedaA" id="txtMonedaA" class="form-control" value="Bs" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS ATRASO DESCUENTO:</label>
						<input type="text" name="txtAtrasoDiasDescuentoA" id="txtAtrasoDiasDescuentoA" class="form-control" readonly>			
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
						<input type="text" name="txtVenceElA" id="txtVenceElA" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>CUOTA:</label>
						<input type="text" name="txtCuotaA" id="txtCuotaA" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>No.:</label>
						<input type="text" name="txtNroA" id="txtNroA" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS CUOTA:</label>
						<input type="text" name="txtDiasCuotaA" id="txtDiasCuotaA" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS DE COBRO ATRASO  DESCUENTO:</label>
						<input type="text" name="txtCobroDiasDescuentoA" id="txtCobroDiasDescuentoA" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">						
						<input type="hidden" name="txtFechaActualA" id="txtFechaActualA" value="{{ $fechaActual }}" class="form-control" readonly>			
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
						<input type="text" name="txtCoutasMoraA" id="txtCoutasMoraA" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS TRANSCURRIDOS:</label>
						<input type="text" name="txtDiasTranscurridosA" id="txtDiasTranscurridosA" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS PERMITIDOS:</label>
						<input type="text" name="txtDiasPermitidosA" id="txtDiasPermitidosA" class="form-control" value="-30" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS ATRASO:</label>
						<input type="text" name="txtDiasAtrasoA" id="txtDiasAtrasoA" class="form-control" readonly>			
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
							<input type="text" name="txtCapitalPagoTotalA" id="txtCapitalPagoTotalA" class="form-control" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>		
			        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtCapitalPagoTotalA2" id="txtCapitalPagoTotalA2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>AMORTIZACIÓN:</label>
						<input type="text" name="txtAmortizacion" id="txtAmortizacion" class="form-control" onchange="fnCalcularAmortizacion(this.value)">			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>INTERES A LA FECHA:</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaA" id="txtInteresFechaA" class="form-control" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
			        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaA2" id="txtInteresFechaA2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>INTERES A LA FECHA CON DESCUENTO:</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaAD" id="txtInteresFechaAD" class="form-control" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
			        </div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaAD2" id="txtInteresFechaAD2" class="form-control" value="" readonly>
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
							<input type="text" name="txtGastosAdministrativosA" id="txtGastosAdministrativosA" class="form-control" readonly>
							<span class="input-group-addon _txtBs">Bs</span>
						</div>		
						<div id="mensajePagoAmortizacion">Los calculos de interes y gastos estan considerando una amortización</div>	
			        </div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosA2" id="txtGastosAdministrativosA2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>GASTOS ADMINISTRATIVOS A LA FECHA CON DESCUENTO:</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosAD" id="txtGastosAdministrativosAD" class="form-control" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>		
			        </div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosAD2" id="txtGastosAdministrativosAD2" class="form-control" value="" readonly>
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
							<input type="text" name="txtInteresMoratorioA" id="txtInteresMoratorioA" class="form-control" value="Bs" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>				
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioA2" id="txtInteresMoratorioA2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>I. MORATORIOS CON DESCUENTO:</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioAD" id="txtInteresMoratorioAD" class="form-control" value="Bs" readonly>
							<span class="input-group-addon _txtBs">Bs</span>
						</div>			
			        </div>
				</div>				
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioAD2" id="txtInteresMoratorioAD2" class="form-control" value="" readonly>
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
							<input type="text" name="txtTotalA" id="txtTotalA" class="form-control" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>					
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtTotalA2" id="txtTotalA2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>						
			</div>
		</fieldset>			
	</div>	
	{{-- <div class="col-md-4">
		<fieldset>
			<legend>AMORTIZACIÓN</legend>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>AMORTIZACION:</label>
						<input type="text" name="txtAmortizacion" id="txtAmortizacion" class="form-control" onchange="fnCalcularAmortizacion(this.value)">			
			        </div>
				</div>						
			</div>
			
		</fieldset>			
	</div> --}}
</div>


<div class="row">            
    <div class="col-md-12">
    	<button type="button" class="btn btn-succes btn-labeled btn-sm" onClick="fnPagarContratoAmortizacion(1);"><b><i class="icon-reload-alt"></i></b>Pagar</button>
       	<button type="button" class="btn btn-warning btn-labeled btn-sm" onClick="fnVolverContratos();"><b><i class="icon-reload-alt"></i></b>Volver</button>
    </div>
</div>	
	