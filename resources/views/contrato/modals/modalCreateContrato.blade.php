<style type="text/css">
	fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
   
</style>
<div id="modalCreateContrato" class="modal fade">
	<div class="modal-dialog" style="width: 900px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Registro Contratos</h5>
			</div>
			<form id="frmContrato">
				<div class="modal-body">
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Datos</legend>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Cliente:</label>
									<input type="text" id="txtCliente" name="txtCliente" placeholder="Cliente" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Codigo Cliente:</label>
									<input type="text" id="txtCodigoCliente" name="txtCodigoCliente" placeholder="Codigo" class="form-control" readonly>
								</div>
							</div>	
							<div class="col-md-4">
								<div class="form-group">
									<label>Fecha Contrato:</label>
									<input type="text" id="txtFechaContrato" name="txtFechaContrato" class="form-control" data-mask readonly value="{{ $fechaActual }}">
								</div>
							</div>												
						</div>
						<div class="row">
							{{-- <div class="col-md-4">
								<div class="form-group">
									<label>Codigo Credito:</label>
									<input type="text" id="txtCodigoCredito" name="txtCodigoCredito" placeholder="Codigo Credito" class="form-control">
								</div>
							</div> --}}
							<div class="col-md-4">
								<div class="form-group">
									<label>Moneda:</label>
									<select name="txtMoneda" id="txtMoneda" class="form-control">
										<option value="1">Bs</option>
										<option value="2">$us</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Valor Garantia:</label>
									<input type="text" id="txtGarantia" name="txtGarantia" placeholder="Valor Garantia" class="form-control" readonly>
								</div>
							</div>		
							<div class="col-md-4">
								<div class="form-group">
									<label>Interés % (0 - 100)</label>
									@php
									$readonly = '';
									if(session::get('ID_ROL') == 2)
									{
										$readonly = 'readonly';
									}
									@endphp

									<input type="number" name="txtInteres" id="txtInteres" value="3" step="0.01" class="form-control" min="0" max="100" {{$readonly}}>
								</div>
							</div>										
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Fondo:</label>
									<input type="text" id="txtFondo" name="txtFondo" placeholder="Fondo" class="form-control" value="PrendaSol" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Peso Bruto:</label>
									<input type="text" id="txtPesoBruto" name="txtPesoBruto" placeholder="Peso Bruto" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Tipo Contrato:</label>
									<input type="text" id="txtTipoContrato" name="txtTipoContrato" placeholder="Tipo Contrato" class="form-control" value="Deudor" readonly>
								</div>
							</div>												
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Peso Neto:</label>
									<input type="text" id="txtPesoNeto" name="txtPesoNeto" placeholder="Peso Neto" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Credito Max:</label>
									<input type="text" id="txtCreditoMax" name="txtCreditoMax" placeholder="Credito Max" class="form-control" readonly>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label>Credito Prestar:</label>
									<input type="text" id="txtCreditoPrestar" name="txtCreditoPrestar" placeholder="Credito a Prestar" class="form-control" onchange="fnCreditoPrestar(this.value)">
								</div>
							</div>						
						</div>
					</fieldset>

					{{-- <fieldset class="scheduler-border">
						<legend class="scheduler-border">Datos de Aprobación</legend>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Monto Crédito:</label>
									<input type="text" id="txtMontoCredito" name="txtMontoCredito" placeholder="Monto Crédito" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Tipo PP:</label>
									<input type="text" id="txtTipoPP" name="txtTipoPP" placeholder="Tipo PP:" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Capital Bs.:</label>
									<input type="text" id="txtCapitaBs" name="txtCapitaBs" placeholder="Capital Bs." class="form-control">
								</div>
							</div>																		
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Interes Conv. %:</label>
									<input type="text" id="txtInteresConv" name="txtInteresConv" placeholder="Interes Conv. %" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Tipo Interes:</label>
									<input type="text" id="txtTipoInteres" name="txtTipoInteres" placeholder="Tipo Interes" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Intreses:</label>
									<input type="text" id="txtIntereses" name="txtIntereses" placeholder="Intereses" class="form-control">
								</div>
							</div>												
						</div>
						<div class="row">							
							<div class="col-md-4">
								<div class="form-group">
									<label>Nro. Cuotas:</label>
									<input type="text" id="txtNroCuotas" name="txtNroCuotas" placeholder="Nro. Cuotas" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Gastos Adm.:</label>
									<input type="text" id="txtGastosAdm" name="txtGastosAdm" placeholder="Gastos Adm." class="form-control">
								</div>
							</div>												
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Forma Pago:</label>
									<input type="text" id="txtFormaPago" name="txtFormaPago" placeholder="Forma Pago" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Totales:</label>
									<input type="text" id="txtTotales" name="txtTotales" placeholder="Totales" class="form-control">
								</div>
							</div>																		
						</div>
					</fieldset> --}}
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<table class="table table-striped- table-bordered table-hover table-checkable" id="tabla_contratos">
	    	                        <thead>
	                                    <tr>
	                                        <th width="10%">Cantidad</th>
		                                    <th width="30%">Descripción</th>   
		                                    <th width="10%">Peso Bruto</th>
		                                    <th width="10%">10 Klts</th>
		                                    <th width="10%">14 Klts</th>
		                                    <th width="10%">18 Klts</th>
		                                    <th width="10%">24 Klts</th>
		                                    <th width="10%" >ACCIONES</th>
	                                    </tr>
	                                </thead>
	                                <tbody id="detalle_contratos">
	                                </tbody>
	                                <tfoot>
	          	                        <tr>
	                                  		<td><span id="total_cantidad" class="text-success">0.00</span></td>
	                                  		<td><span id="total_descripcion" class="text-success">VARIOS</span></td>
	                                  		<td><span id="total_peso_bruto" class="text-success">0.00</span></td>
	                                  		<td><span id="total_10_klts" class="text-success">0.00</span></td>
	                                  		<td><span id="total_14_klts" class="text-success">0.00</span></td>
	                                  		<td><span id="total_18_klts" class="text-success">0.00</span></td>
	                                  		<td><span id="total_24_klts" class="text-success">0.00</span></td>
	          	                        </tr>
	                                </tfoot>
	                            </table>
	                            <a href="#" class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air" onclick="agregar_fila()" id="boton_nueva_fila">
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
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="fnLimpiarControles();">Cancelar</button>
					@if ($datoValidarCaja)
						@if($datoValidarCaja->fecha_hora)
							@if(empty($datoValidarCaja->fecha_cierre))
							<button type="button" id="btnRegistrarContrato" class="btn btn-primary" onclick="fnRegistrarContrato(this.id);this.disabled = true">Registrar</button>
							{{-- {!!link_to('#',$title='Registrar', $attributes=['id'=>'btnRegistrar','class'=>'btn btn-primary'], $secure=null)!!} --}}
							@endif
						@endif
						{{-- expr --}}
					@endif
					
					
				</div>
			</form>
		</div>
	</div>
</div>