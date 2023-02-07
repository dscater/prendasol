@if(isset($codigos))	
	
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>CI</th>
					<th>Nombre Completo</th>
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
					<th colspan="3" class="text-center">Pagos</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($codigos as $key => $codigo)
					<tr>
						<td>{{$key+1}}</td>
						{{-- <td style="display:none;">{{$codigo->sucural}}</td> --}}
						<td>{{ $codigo->cliente->persona->nrodocumento }}</td>
						<td>{{ $codigo->cliente->persona->nombreCompleto() }}</td>
						@if($codigo->codigo != "")
							<td>{{ $codigo->codigo }}</td>
						@else
							@php
								$gestion = substr($codigo->gestion, 2, 2);
								$rescodigo = $codigo->sucural->nuevo_codigo .''. $gestion .''. $codigo->codigo_num;

							@endphp							
							<td>{{ $rescodigo }}</td>
						@endif
						<td>{{ $codigo->peso_total }}</td>
						<td>@if(isset($codigo->fecha_contrato)){{ date('d-m-Y', strtotime($codigo->fecha_contrato)) }}@endif</td>
						<td>@if(isset($codigo->fecha_fin)){{ date('d-m-Y', strtotime($codigo->fecha_fin)) }}@endif</td>
						<td>{{ $codigo->capital }}</td>	
						<td>{{ $codigo->total_capital }}</td>	
						<td>{{ $codigo->interes }}</td>
						<td>{{ $codigo->comision }}</td>
						@if (isset ($codigo->sucural->nombre))<td>{{ $codigo->sucural->nombre }}</td>
						@else <td></td>
						@endif
						
						<td>{{ $codigo->caja }}</td>
						<td>{{ $codigo->estado_pago }}</td>
						<td>{{ $codigo->estado_pago_2 }}</td>
						<td>{{ $codigo->estado_entrega }}</td>
						<td style="display:none;">{{ $codigo->cliente->persona  }}</td>	
						<td>
							<a href="#" onClick = "fnDetalleContratos({{ $codigo->id }});" data-popup="tooltip" data-toggle="modal" data-target="#modalDetalleContrato" title="Contratos"><i class="fa fa-fw fa-edit"></i></a>
						</td>
						<td>
							<a href="#" onClick = "fnDetallePagos({{ $codigo->id }});" data-popup="tooltip" data-toggle="modal" data-target="#modalDetallePagos" title="Pagos"><i class="fa fa-fw fa-money"></i></a>
						</td>	
							@if($codigo->estado_pago==="interes igual" || $codigo->estado_pago==="amortizacion" || $codigo->estado_pago==="DESEMBOLSO DE CREDITO" || $codigo->estado_pago==="" || is_null($codigo->estado_pago))
								<td>
									<button type="button" class="btn btn-block btn-success btn-xs"  title="Pagar Total" onClick = "fnDatoPagoTotal({{ $codigo }});">Total</button>
								</td>
								<td>
									<button type="button" class="btn btn-block btn-info btn-xs" title="Pagar Interes" onClick = "fnDatoPagoInteres({{ $codigo }});">Interes</button>
								</td>
								<td>
									<button type="button" class="btn btn-block btn-warning btn-xs" data-popup="tooltip" data-toggle="modal" data-target="#modalPagarContratos" title="Pagar" onClick = "fnDatoPagoAmortizacion({{ $codigo }});">Amortización</button>
								</td>

								<td>
									<button type="button" class="btn btn-block btn-warning btn-xs" data-popup="tooltip" data-toggle="modal" title="Rematar" onClick = "fnDatoRemate({{ $codigo }});" style="background-color:#f00;">Remate</button>
								</td>
							@endif	
						

							@if($codigo->estado_pago==="Credito cancelado" && $codigo->estado_pago_2==="custodia"  && $codigo->estado_entrega==="Prenda en custodia")
								<td>
									<button type="button" class="btn btn-block btn-success btn-xs"  title="Pagar Total" onClick = "fnRetiroPrenda({{ $codigo }});">Retiro de Prenda</button>
								</td>								
							@endif
							@if($codigo->estado_pago==="Credito cancelado"  && $codigo->estado_pago_2==="entregada")
								<td colspan="3">
									<p class="text-green">PRENDA ENTREGADA<strong></strong></p>
								</td>								
							@endif	
				

						
							@if($codigo->estado_pago==="Prenda Rematado"  && $codigo->estado_pago_2==="Prenda Rematado")
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
