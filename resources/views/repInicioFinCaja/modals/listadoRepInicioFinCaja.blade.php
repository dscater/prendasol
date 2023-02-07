@isset($datoValidarCaja->fecha_hora)
	<div class="row">            
        <div class="col-md-12">            
            <div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Codigo</th>
							<th>Nombre Cliente</th>
							<th>Ref.</th>
							<th>Fecha Pago</th>
							<th>Inicio Caja</th>
							<th>Ingreso Bs</th>
							<th>Egreso Bs</th>
							<th>Tipo Movimiento</th>
						</tr>
					</thead>
					<tbody>
						@isset ($datosCaja)
						    @foreach ($datosCaja as $key => $datoCaja)
								<tr>
									<td>{{$key+1}}</td>
									@if ($datoCaja->contrato)
										{{-- <td>{{ $datoCaja->contrato->codigo }}</td> --}}
										@if($datoCaja->contrato->codigo != "")
											<td>{{ $datoCaja->contrato->codigo }}</td>
										@else							
											<td>{{$datoCaja->contrato->sucural->nuevo_codigo}} {{ Carbon\Carbon::parse($datoCaja->contrato->fecha_contrato)->format('y')}}{{$datoCaja->contrato->codigo_num }}</td>
										@endif
									@else	
										<td></td>
									@endif
									@if ($datoCaja->contrato)
										<td>{{ $datoCaja->contrato->cliente->persona->nombreCompleto() }}</td>
									@else	
										<td>{{ $datoCaja->persona->nombreCompleto() }}</td>
									@endif
									
									
									{{-- <td>{{ date('d-m-Y', strtotime($datoCaja->fecha_pago)) }}</td> --}}
									<td>{{ $datoCaja->ref }}</td>
									<td>{{ $datoCaja->created_at }}</td>
									<td>{{ $datoCaja->inicio_caja_bs }}</td>
									<td>{{ $datoCaja->ingreso_bs }}</td>
									<td>{{ $datoCaja->egreso_bs }}</td>
									<td>{{ $datoCaja->tipo_de_movimiento }}</td>
								</tr>
							@endforeach	
						@endisset
											
					</tbody>
				</table>	
			</div>           
        </div>
    </div>
    	<button type="button" class="btn btn-primary btn-labeled btn-sm" onClick="fnImprimirInicioFinCaja();"><b><i class="icon-reload-alt"></i></b>Imprimir</button>
    </div>
@endisset