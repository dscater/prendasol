@if(isset($contratos))	
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Cliente: </label> {{ $cliente->persona->nombreCompleto() }}
				<input type="hidden" id="txtNombreClienteOculto" name="txtNombreClienteOculto" value="{{ $cliente->persona->nombreCompleto() }}">
				<input type="hidden" id="txtCodigoClienteOculto" name="txtCodigoClienteOculto" value="{{ $cliente->codigo }}">
				<input type="hidden" id="txtIdClienteOculto" name="txtIdClienteOculto" value="{{ $cliente->id }}">
				<input type="hidden" id="txtIdPersonaOculto" name="txtIdPersonaOculto" value="{{ $cliente->persona_id }}">
            </div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Codigo</th>
					<th>Peso Bruto Total</th>
					<th>Fecha Contrato</th>
					<th>Fecha Fin</th>
					<th>Capital</th>
					<th>Total Capital</th>				
					<th>Interes</th>
					<th>Comisión</th>
					<th>Sucursal</th>
					<th>Caja</th>
					<th>Estado Pago</th>
					<th>Estado</th>
					<th>Estado Entrega</th>
					<th colspan="2"></th>
					<th colspan="4" class="text-center">Pagos</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($contratos as $key => $contrato)
					<tr>
						<td>{{$key+1}}</td>
						{{-- <td style="display:none;">{{$contrato->sucural}}</td> --}}
						@if($contrato->codigo != "")
							<td>{{ $contrato->codigo }}</td>
						@else
							@php
								$gestion = substr($contrato->gestion, 2, 2);
								$rescodigo = $contrato->sucural->nuevo_codigo .''. $gestion .''. $contrato->codigo_num;

							@endphp							
							<td>{{ $rescodigo }}</td>
						@endif
						<td>{{ $contrato->peso_total }}</td>
						<td>@if(isset($contrato->fecha_contrato)){{ date('d-m-Y', strtotime($contrato->fecha_contrato)) }}@endif</td>
						<td>@if(isset($contrato->fecha_fin)){{ date('d-m-Y', strtotime($contrato->fecha_fin)) }}@endif</td>
						<td>{{ $contrato->capital }}</td>	
						<td>{{ $contrato->total_capital }}</td>	
						<td>{{ $contrato->interes }}</td>
						<td>{{ $contrato->comision }}</td>
						@if (isset ($contrato->sucural->nombre))<td>{{ $contrato->sucural->nombre }}</td>
						@else <td></td>
						@endif
						
						<td>{{ $contrato->caja }}</td>
						<td>{{ $contrato->estado_pago }}</td>
						<td>{{ $contrato->estado_pago_2 }}</td>
						<td>{{ $contrato->estado_entrega }}</td>
						<td style="display:none;">{{ $contrato->cliente->persona  }}</td>	
						<td>
							<a href="#" onClick = "fnDetalleContratos({{ $contrato->id }});" data-popup="tooltip" data-toggle="modal" data-target="#modalDetalleContrato" title="Contratos"><i class="fa fa-fw fa-edit"></i></a>
						</td>
						<td>
							<a href="#" onClick = "fnDetallePagos({{ $contrato->id }});" data-popup="tooltip" data-toggle="modal" data-target="#modalDetallePagos" title="Pagos"><i class="fa fa-fw fa-money"></i></a>
						</td>
								
									@if($contrato->estado_pago==="interes igual" || $contrato->estado_pago==="amortizacion" || $contrato->estado_pago==="amortizacion interes" || $contrato->estado_pago==="DESEMBOLSO DE CREDITO" || $contrato->estado_pago==="" || is_null($contrato->estado_pago))
										<td>
											<button type="button" class="btn btn-block btn-success btn-xs"  title="Pagar Total" onClick = "fnDatoPagoTotal({{ $contrato }});">Total</button>
										</td>
										<td>
											<button type="button" class="btn btn-block btn-info btn-xs" title="Pagar Interes" onClick = "fnDatoPagoInteres({{ $contrato }});">Interes</button>
										</td>
										<td>
											<button type="button" class="btn btn-block btn-warning btn-xs" data-popup="tooltip" data-toggle="modal" data-target="#modalPagarContratos" title="Pagar" onClick = "fnDatoPagoAmortizacion({{ $contrato }});">Amortización</button>
										</td>
										<td>
											<button type="button" class="btn btn-block btn-primary btn-xs" data-popup="tooltip" title="Amortización Interes" onClick = "fnDatoPagoAmortizacionInteres({{ $contrato }});">Amortización Interes</button>
										</td>
										<td>
											<button type="button" class="btn btn-block btn-warning btn-xs" data-popup="tooltip" data-toggle="modal" title="Rematar" onClick = "fnDatoRemate({{ $contrato }});" style="background-color:#f00;">Remate</button>
										</td>
									@endif	
								

									@if($contrato->estado_pago==="Credito cancelado" && $contrato->estado_pago_2==="custodia"  && $contrato->estado_entrega==="Prenda en custodia")
									
									<td>
									@if($contrato->solicitud)
										@if($contrato->solicitud->estado != 'RENOVACION')
										<button type="button" class="btn btn-block btn-success btn-xs"  title="Pagar Total" onClick = "fnRetiroPrenda({{ $contrato }});">Retiro de Prenda</button>
										@else
										<a href="{{route('contratos.EnviaRenovacion',$contrato->id)}}" class="btn btn-block btn-info btn-xs"  title="Prenda para renovación" >Prenda para renovación</a>
										@endif
										@else
										<a href="#" data-toggle="modal" data-target="#modalSolicitud" class="btn btn-block btn-success btn-xs" onclick="fnObtieneIdContrato({{$contrato->id}})">Solicitud de retiro de joya</a>
										@endif
									@endif
									</td>
						
							@if($contrato->estado_pago==="Credito cancelado"  && $contrato->estado_pago_2==="entregada")
								<td colspan="3">
									<p class="text-green">PRENDA ENTREGADA<strong></strong></p>
								</td>
							@endif	
						
							@if($contrato->estado_pago==="Prenda Rematado"  && $contrato->estado_pago_2==="Prenda Rematado")
								<td colspan="3">
									<p class="text-green">PRENDA REMATADO<strong></strong></p>
								</td>
								
							@endif			
					</tr>
				@endforeach			
			</tbody>
		</table>	
	</div>

	<div class="row">            
        <div class="col-md-12">  
           	<button type="button" class="btn btn-warning btn-labeled btn-sm" onClick="fnVolver();"><b><i class="icon-reload-alt"></i></b>Volver</button>
        </div>
    </div>	
@endif
