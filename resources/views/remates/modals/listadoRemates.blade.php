@if(isset($contratos))
<div>
	<button class="btn btn-default" data-dismiss="modal" style="background:#A5A5B2" type="button" onclick="fnImprimirRemate();">imprimir</button>
</div>
<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>CI</th>
				<th>Nombre Completo</th>
				<th>Sucursal</th>
				<th>Fecha</th>
				<th>Caja</th>
				<th>Contrato</th>
				<th>Capital</th>
				<th>Días Atraso</th>
				<th>Importe Interes</th>
				<th>Importe Mora</th>
				<th>Telefono</th>				
			</tr>
		</thead>
		<tbody>
			@php				
				$totalCapital = 0;
				$totalInteres = 0;
				$totalMorosidad = 0;
			@endphp
			@foreach ($contratos as $key => $contrato)
				@php
					$date = \Carbon\Carbon::parse($contrato->fecha_fin);
    				$now = \Carbon\Carbon::now();
    				$diff = $date->diffInDays($now);
					$diaActual = 30;
					
					$capital = $contrato->capital;
					if($contrato->moneda_id == 2)
					{
						$capital = $contrato->capital * $cambio->valor_bs;
					}

    				if ((float)$capital <= 3499) {
    					$totalInteresValor = ($capital * 10.4)/100;
    				}else{
    					$totalInteresValor = ($capital * 7.4)/100;
    				}
    				$totalMora = ((float)$totalInteresValor/$diaActual) * $diff;
    				$totalInteres = $totalInteres + $totalInteresValor;
    				$totalMorosidad = $totalMorosidad + $totalMora;
				@endphp
				
								
				<tr>
					<td>{{$key+1}}</td>
					<td>{{ $contrato->cliente->persona->nrodocumento }}</td>
					<td>{{ $contrato->cliente->persona->nombreCompleto() }}</td>
					@isset ($contrato->sucural->nombre)
					    <td>{{ $contrato->sucural->nombre }}</td>
					@endisset					
					<td>{{ date('d-m-Y', strtotime($contrato->fecha_fin)) }}</td>
					<td>{{ $contrato->caja }}</td>
					{{-- <td>{{ $dato->contrato->codigo }}</td> --}}
					@if($contrato->codigo != "")
							<td>{{ $contrato->codigo }}</td>
						@else
							@php
								//$rescodigo = $dato->sucural->nuevo_codigo .''. \Carbon\Carbon::parse($dato->fecha_contrato)->format('y') .''. $dato->codigo_num;
								$rescodigo = $contrato->sucural->nuevo_codigo .''. \Carbon\Carbon::parse($contrato->fecha_contrato)->format('y') .''. $contrato->codigo_num;

							@endphp							
							<td>{{ $rescodigo }}</td>
						@endif					
					<td>{{ $contrato->capital }}</td>	
					<td>{{ $diff }}</td>
					<td>{{ number_format($totalInteresValor, 2, ',', '.') }}</td>
					<td>{{ number_format($totalMora, 2, ',', '.') }}</td>
					<td>{{ $contrato->cliente->persona->celular }}</td>							
				</tr>
			@endforeach
			<tr>
				<td></td>
				<td></td>
				<td></td>				
				<td></td>				
				<td></td>
				<td></td>
				<td></td>	
				<td></td>	
				<td></td>
				<td><strong>{{ number_format($totalInteres, 2, ',', '.') }}</strong></td>
				<td><strong>{{ number_format($totalMorosidad, 2, ',', '.') }}</strong></td>
				<td></td>							
			</tr>
		</tbody>
	</table>	
</div>
{{-- {{ $datosPagos ->links() }} --}}
@endif