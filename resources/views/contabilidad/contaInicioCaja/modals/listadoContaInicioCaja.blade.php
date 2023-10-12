@isset($fechaI)
	<div class="row">            
        <div class="col-md-12">            
            <div class="table-responsive">
            	<a href="{{ url('/') }}/ExportarInicioFinCaja/{{ $fechaI }}/{{ $fechaF }}" class="btn btn-success">Exportar Excel</a>
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Sucursal</th>
							<th>Caja</th>
							<th>Codigo</th>
							<th>Nombre Cliente</th>
							<th>CI</th>
							<th>Ref.</th>
							<th>Fecha Pago</th>
							<th>Inicio Caja</th>
							<th>Ingreso Bs</th>
							<th>Egreso Bs</th>
							<th>Tipo Movimiento</th>
						</tr>
					</thead>
					<tbody>
						@php
							$i=0;							
						@endphp
						@foreach ($datosValidarCaja as $key => $datoCaja)
							@if ($datoCaja->fecha_hora)
								@php
									$i++;
									$j =0;
								@endphp
								<tr bgcolor="#00FF00">
									<td>{{$i}}</td>
									<td>{{ $datoCaja->sucural1->nombre }}</td>
									<td>{{ $datoCaja->caja }}</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td>{{ $datoCaja->fecha_hora }}</td>
									<td>{{ number_format($datoCaja->inicio_caja_bs,2,".",",") }}</td>
									<td>SALDO INICIAL</td>
								</tr>
								@foreach ($datoCaja->detalleIniciFinCaja($datoCaja->id) as $key1 => $detalle)
									<tr>
										@php
											$j++;
										@endphp		
										@if ($detalle->contrato_id == 0)											
											<tr>
												<td>{{$i}}</td>.
												<td>{{ $detalle->sucursal->nombre}}</td>
												<td>{{ $detalle->caja}}</td>
												<td>{{ $detalle->contrato_id }}</td>
												<td>MARIO ROJAS YUCRA</td>
												<td>2773500</td>
												<td>{{ $detalle->ref }}</td>
												<td>{{ $detalle->created_at }}</td>
												<td>{{ number_format($detalle->inicio_caja_bs,2,".",",") }}</td>
												<td>{{ number_format($detalle->ingreso_bs,2,".",",") }}</td>
												<td>{{ number_format($detalle->egreso_bs,2,".",",") }}</td>
												<td>{{ $detalle->tipo_de_movimiento }}</td>
												<td><a href="#" onClick = "fnEditarContaDiario({{ $detalle }});" data-popup="tooltip" title="Editar" data-toggle="modal" data-target="#modalUpdateContaDiario" data-keyboard="false"><i class="fa fa-fw fa-edit"></i></a>
												<a href="#" data-popup="tooltip" title="Eliminar" onClick = "fnEliminarContaDiario({{ $detalle->id }});"><i class="fa fa-fw fa-trash-o"></i></a></td>	
											</tr>
										@else								
											@if ($detalle->contrato)
												<td>{{ $j }}</td>
												<td>{{ $detalle->sucursal->nombre}}</td>
												<td>{{ $detalle->caja}}</td>
												@if ($detalle->contrato_id != 0)
													@if ($detalle->contrato->codigo != "")
														<td>{{ $detalle->contrato->codigo }}</td>
													@else
														@php
															$rescodigo = $detalle->contrato->sucural->nuevo_codigo .''. Carbon\Carbon::parse($detalle->contrato->fecha_contrato)->format('y') .''. $detalle->contrato->codigo_num;
														@endphp
														<td>{{ $rescodigo }}</td>
													@endif						
													<td>{{ $detalle->contrato->cliente->persona->nombreCompleto() }}</td>
													<td>{{ $detalle->contrato->cliente->persona->nrodocumento }}</td>		
												@else
													<td></td>
													<td>{{ $detalle->persona->nombreCompleto()  }}</td>
													<td>{{ $detalle->persona->nrodocumento  }}</td>
												@endif

																					
												<td>{{ $detalle->ref }}</td>
												<td>{{ $detalle->created_at }}</td>
												<td>{{ number_format($detalle->inicio_caja_bs,2,".",",") }}</td>
												<td>{{ number_format($detalle->ingreso_bs,2,".",",") }}</td>
												<td>{{ number_format($detalle->egreso_bs,2,".",",") }}</td>
												<td>{{ $detalle->tipo_de_movimiento }}</td>
												<td><a href="#" onClick = "fnEditarContaInicioCaja({{ $detalle }});" data-popup="tooltip" title="Editar" data-toggle="modal" data-target="#modalUpdateContaInicioCaja" data-keyboard="false"><i class="fa fa-fw fa-edit"></i></a>
												<a href="#" data-popup="tooltip" title="Eliminar" onClick = "fnEliminarContaDiario({{ $detalle->id }});"><i class="fa fa-fw fa-trash-o"></i></a></td>	
											@endif
										@endif
									</tr>
								@endforeach

								<tr bgcolor="#00FF00">
									<td>{{$i}}</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td>{{ $datoCaja->fecha_cierre }}</td>
									<td>{{ number_format($datoCaja->fin_caja_bs,2,".",",") }}</td>
									<td>CIERRE CAJA</td>
								</tr>
							@endif
						@endforeach			
					</tbody>
				</table>	
			</div>           
        </div>
    </div>
    	{{-- <button type="button" class="btn btn-primary btn-labeled btn-sm" onClick="fnImprimirInicioFinCaja();"><b><i class="icon-reload-alt"></i></b>Imprimir</button> --}}
    </div>
@endisset