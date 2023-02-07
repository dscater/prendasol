<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Sucursal</th>
				<th>Caja</th>
				<th>fecha</th>
				<th>fecha inicio</th>
				<th>fecha Cierre</th>
				<th>inicio Bs</th>
				<th>inicio $us</th>
				<th>fin Bs</th>
				<th>fin $us</th>
			</tr>
		</thead>
		<tbody>
			@php
				$valores_cambio = App\CambioMoneda::first();
			@endphp
			@foreach ($datosInicioFinCaja as $key => $dato)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{ $dato->sucural1->nombre }}</td>
					<td>{{ $dato->caja }}</td>
					<td>{{ $dato->fecha }}</td>
					<td>{{ $dato->fecha_hora }}</td>
					<td>{{ $dato->fecha_cierre }}</td>
					@if($dato->moneda_id == 2)
					<td>{{ number_format(((float)$dato->inicio_caja_bs * $valores_cambio->valor_bs),2,'.',',') }} Bs</td>
					<td>{{ number_format(((float)$dato->inicio_caja_bs),2,'.',',') }} $us</td>
					@else
					<td>{{ number_format($dato->inicio_caja_bs,2,'.',',') }} Bs</td>
					<td>{{ number_format((float)$dato->inicio_caja_bs / $valores_cambio->valor_bs,2,'.',',') }} $us</td>
					@endif
					@if($dato->moneda_id == 2)
					<td>{{ number_format(((float)$dato->fin_caja_bs * $valores_cambio->valor_bs),2,'.',',') }} Bs</td>
					<td>{{ number_format(((float)$dato->fin_caja_bs),2,'.',',') }} $us</td>
					@else
					<td>{{ number_format($dato->fin_caja_bs,2,'.',',') }} Bs</td>
					<td>{{ number_format((float)$dato->fin_caja_bs / $valores_cambio->valor_bs,2,'.',',') }} $us</td>
					@endif			
				</tr>
			@endforeach
		</tbody>
	</table>	
</div>