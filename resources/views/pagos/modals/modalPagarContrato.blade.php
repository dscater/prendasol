<div id="modalPagarContratos" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Pagar Amortización</h5>
			</div>			
			<div class="modal-body">
				<div class="row">
					<input type="hidden" name="txtIdContrato" id="txtIdContrato">					
					<div class="col-md-4">
						<div class="form-group">
							<label>Capital:</label>
							<input type="text" class="form-control" id="txtCapital" placeholder="Capital" readonly>
						</div>						
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Interes:</label>
							<input type="text" class="form-control" id="txtInteres" placeholder="Interes" readonly>
						</div>						
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label>Comisión:</label>
							<input type="text" class="form-control" id="txtComisión" placeholder="Interes" readonly>
						</div>						
					</div>					
				</div>
				<div class="row">	
					<div class="col-md-4">
						<div class="form-group">
							<label>Amortización:</label>
							<input type="text" class="form-control" id="txtAmortizacion" placeholder="Amortizacion" onchange="fnCalcularAmortizacion(this.value)">
						</div>						
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Nuevo interes:</label>
							<input type="text" class="form-control" id="txtNuevoInteres" placeholder="nuevo interes" readonly>
						</div>						
					</div>				
					<div class="col-md-4">
						<div class="form-group">
							<label>Total a Pagar ( amortizacion + capital):</label>
							<input type="text" class="form-control" id="txtTotalPagar" placeholder="Total Pagar"  readonly>
						</div>						
					</div>					
				</div>						
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="fnPagarContratoAmortizacion();">Pagar</button>
			</div>			
		</div>
	</div>
</div>