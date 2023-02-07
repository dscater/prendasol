@if(isset($datosPagos))
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
				<th>Estado</th>
				<th>Dias Atraso</th>				
				<th>Cuota Mora</th>
				<th>Capital</th>
				<th>Moneda</th>
				<th>Interes</th>
				<th>Comision</th>
				<th>Usuario</th>				
			</tr>
		</thead>
		<tbody>
			@foreach ($datosPagos as $key => $dato)
							
				<tr>
					<td>{{$key+1}}</td>
					<td>{{ $dato->contrato->cliente->persona->nrodocumento }}</td>
					<td>{{ $dato->contrato->cliente->persona->nombreCompleto() }}</td>
					<td>{{ $dato->sucural->nombre }}</td>
					<td>{{ date('d-m-Y', strtotime($dato->fecha_inio)) }}</td>
					<td>{{ $dato->caja }}</td>
					{{-- <td>{{ $dato->contrato->codigo }}</td> --}}
					@if($dato->contrato->codigo != "")
							<td>{{ $dato->contrato->codigo }}</td>
						@else	
							@php
								//$rescodigo = $dato->sucural->nuevo_codigo .''. \Carbon\Carbon::parse($dato->fecha_contrato)->format('y') .''. $dato->codigo_num;
								//$rescodigo = $dato->contrato->sucural->nuevo_codigo .''. \Carbon\Carbon::parse($dato->contrato->fecha_contrato)->format('y') .''. $dato->contrato->codigo_num;
								$gestion = substr($dato->contrato->gestion, 2, 2);
								$rescodigo = $dato->contrato->sucural->nuevo_codigo .''. $gestion  .''. $dato->contrato->codigo_num;

							@endphp							
							<td>{{ $rescodigo }}</td>
						@endif
					<td>{{ $dato->estado }}</td>
					<td>{{ $dato->dias_atraso_total }}</td>						
					<td>{{ $dato->cuota_mora }}</td>
					<td>{{ $dato->capital }}</td>	
					<td>{{ $dato->contrato->moneda->desc_corta }}</td>	
					<td>{{ $dato->interes }}</td>
					<td>{{ $dato->comision }}</td>
					<td>{{ $dato->usuario->usuario }}</td>
					<td>
						<a href="#" onClick = "fnImprimirContratos({{ $dato }});" data-popup="tooltip" title="Imprimir"><i class="fa fa-fw fa-file-pdf-o"></i></a>		

						@if($dato->estado == 'DESEMBOLSO')
						<br>
						<br>
						<a href="#" onClick = "fnImprimirContratos2({{ $dato }});" data-popup="tooltip" title="Contrato H2"><i class="fa fa-fw fa-file-pdf-o"></i></a>	
						<br>
						<br>
						<a href="#" onClick = "fnIprimirCambioMoneda({{ $dato }});" data-popup="tooltip" title="Cambio Moneda"><i class="fa fa-fw fa-file-pdf-o"></i></a>

						@endif

					{{-- <a href="#" onClick = "fnImprimirBoleta({{ $dato->contrato_id }});" data-popup="tooltip" title="Imprimir">Prueba</a>--}}
					</td>				
				</tr>
			@endforeach
		</tbody>
	</table>	
</div>
{{-- {{ $datosPagos ->links() }} --}}
@endif