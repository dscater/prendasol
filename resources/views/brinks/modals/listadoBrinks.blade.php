@if(isset($datosContratos))
<div>
	<button class="btn btn-default" data-dismiss="modal" style="background:#A5A5B2" type="button" onclick="fnImprimirBrinks();">Imprimir PDF</button>
	<a href="{{ url('/') }}/ExportarContratosBrinks/{{ $fechaI }}/{{ $fechaF }}/{{ $sucursal }}" class="btn btn-success">Exportar Excel</a>
</div>
<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>AGENCIA ORIGEN</th>
				<th>CODIGO DE CONTRATO</th>
				<th>PESO BRUTO</th>
				<th>PESO NETO</th>
				<th>VALOR DE TASACION</th>
				<th>FECHA DE INGRESO</th>		
			</tr>
		</thead>
		<tbody>
			@foreach ($datosContratos as $key => $contrato)
				<tr>
					<td>{{$key+1}}</td>
					@isset ($contrato->sucural->nombre)
					    <td>{{ $contrato->sucural->nombre }}</td>
					@endisset
					@if($contrato->codigo != "")
							<td>{{ $contrato->codigo }}</td>
						@else
							@php
								//$rescodigo = $dato->sucural->nuevo_codigo .''. \Carbon\Carbon::parse($dato->fecha_contrato)->format('y') .''. $dato->codigo_num;
								$rescodigo = $contrato->sucural->nuevo_codigo .''. \Carbon\Carbon::parse($contrato->fecha_contrato)->format('y') .''. $contrato->codigo_num;

							@endphp							
							<td>{{ $rescodigo }}</td>
						@endif					
					<td>{{ $contrato->peso_total }}</td>	
					<td>{{ $contrato->totalPesoNeto($contrato->id) }}</td>	
					<td>{{ number_format($contrato->totalTasacion, 2, ',', '.') }}</td>
					<td>{{ $contrato->fecha_contrato }}</td>							
				</tr>
			@endforeach
		</tbody>
	</table>	
</div>
{{-- {{ $datosPagos ->links() }} --}}
@endif