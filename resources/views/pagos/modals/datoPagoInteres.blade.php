<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label>PAGO INTERES </label>
			
        </div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label>CREDITO CON CAJA:</label>
			<input type="text" name="txtCodigoI" id="txtCodigoI" class="form-control" readonly>			
        </div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>CI:</label>
			<input type="text" name="txtCII" id="txtCII" class="form-control" readonly>			
        </div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>NOMBRES:</label>
			<input type="text" name="txtNombresI" id="txtNombresI" class="form-control" readonly>			
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
						<input type="text" name="txtVigenciaI" id="txtVigenciaI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>MONTO TOTAL:</label>
						<div class="input-group">
							<input type="text" name="txtMontoTotalI" id="txtMontoTotalI" class="form-control" readonly>			
							<span class="input-group-addon _txtBs">Bs</span>
						</div>
			        </div>
				</div>		
				<div class="col-md-6">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtMontoTotalI2" id="txtMontoTotalI2" class="form-control" readonly>	
							<span class="input-group-addon _txtSus">$us</span>
						</div>
					</div>
				</div>				
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>PAGO(S):</label>
						<input type="text" name="txtPagosI" id="txtPagosI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>MONEDA:</label>
						<input type="text" name="txtMonedaI" id="txtMonedaI" class="form-control" value="Bs" readonly>			
			        </div>
				</div>						
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS ATRASO DESCUENTO:</label>
						<input type="text" name="txtAtrasoDiasDescuentoI" id="txtAtrasoDiasDescuentoI" class="form-control" readonly>			
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
						<input type="text" name="txtVenceElI" id="txtVenceElI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>CUOTA:</label>
						<input type="text" name="txtCuotaI" id="txtCuotaI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>No.:</label>
						<input type="text" name="txtNroI" id="txtNroI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS CUOTA:</label>
						<input type="text" name="txtDiasCuotaI" id="txtDiasCuotaI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS DE COBRO ATRASO  DESCUENTO:</label>
						<input type="text" name="txtCobroDiasDescuentoI" id="txtCobroDiasDescuentoI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">						
						<input type="hidden" name="txtFechaActualI" id="txtFechaActualI" value="{{ $fechaActual }}" class="form-control" readonly>			
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
						<input type="text" name="txtCoutasMoraI" id="txtCoutasMoraI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS TRANSCURRIDOS:</label>
						<input type="text" name="txtDiasTranscurridosI" id="txtDiasTranscurridosI" class="form-control" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>DIAS PERMITIDOS:</label>
						<input type="text" name="txtDiasPermitidosI" id="txtDiasPermitidosI" class="form-control" value="-30" readonly>			
			        </div>
				</div>						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>TOTAL DIAS ATRASO:</label>
						<input type="text" name="txtDiasAtrasoI" id="txtDiasAtrasoI" class="form-control" readonly>			
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
							<input type="text" name="txtCapitalPagoTotalI" id="txtCapitalPagoTotalI" class="form-control" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>		
			        </div>
				</div>					
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtCapitalPagoTotalI2" id="txtCapitalPagoTotalI2" class="form-control" value="" readonly>
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
							<input type="text" name="txtInteresFechaI" id="txtInteresFechaI" class="form-control" readonly>
							<span class="input-group-addon _txtBs">Bs</span>
						</div>			
			        </div>
				</div>					
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaI2" id="txtInteresFechaI2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>INTERES A LA FECHA CON DESCUENTO:</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaID" id="txtInteresFechaID" class="form-control" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>					
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresFechaID2" id="txtInteresFechaID2" class="form-control" value="" readonly>
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
							<input type="text" name="txtGastosAdministrativosI" id="txtGastosAdministrativosI" class="form-control" readonly>	
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
						<div id="mensajePagoInteres">Los calculos de interes y gastos estan considerando una amortización</div>	
			        </div>
				</div>				
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosI2" id="txtGastosAdministrativosI2" class="form-control" value="" readonly>
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>GASTOS ADMINISTRATIVOS A LA FECHA CON DESCUENTO:</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosID" id="txtGastosAdministrativosID" class="form-control" readonly>		
							<span class="input-group-addon _txtBs">Bs</span>
						</div>	
			        </div>
				</div>				
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtGastosAdministrativosID2" id="txtGastosAdministrativosID2" class="form-control" value="" readonly>
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
							<input type="text" name="txtInteresMoratorioI" id="txtInteresMoratorioI" class="form-control" value="Bs" readonly>
							<span class="input-group-addon _txtBs">Bs</span>
						</div>			
			        </div>
				</div>				
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioI2" id="txtInteresMoratorioI2" class="form-control" value="" readonly>			
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<label>I. MORATORIOS CON DESCUENTO:</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioID" id="txtInteresMoratorioID" class="form-control" value="Bs" readonly>
							<span class="input-group-addon _txtBs">Bs</span>
						</div>			
			        </div>
				</div>				
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtInteresMoratorioID2" id="txtInteresMoratorioID2" class="form-control" value="" readonly>			
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
							<input type="text" name="txtTotalI" id="txtTotalI" class="form-control" value="" readonly>
							<span class="input-group-addon _txtBs">Bs</span>
						</div>			
			        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<input type="text" name="txtTotalI2" id="txtTotalI2" class="form-control" value="" readonly>			
							<span class="input-group-addon _txtSus">$us</span>
						</div>
			        </div>
				</div>		
				{{-- <div class="col-md-6">
					<div class="form-group">
						<label>TOTAL CON DESCUENTO:</label>
						<input type="text" name="txtTotalID" id="txtTotalID" class="form-control" value="" readonly>			
			        </div>
				</div>	 --}}					
			</div>
		</fieldset>			
	</div>	
</div>


<div class="row">            
    <div class="col-md-12">
    	<button type="button" class="btn btn-succes btn-labeled btn-sm" onClick="fnPagarContratoInteres(1);"><b><i class="icon-reload-alt"></i></b>Pagar</button>
       	<button type="button" class="btn btn-warning btn-labeled btn-sm" onClick="fnVolverContratos();"><b><i class="icon-reload-alt"></i></b>Volver</button>
    </div>
</div>	
