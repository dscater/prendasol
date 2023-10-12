@if(isset($datosContaDiario))	
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Contrato</th>
					<th>CI</th>
					<th>Nombres</th>
					<th>Sucursal</th>
					<th>Periodo</th>
					<th>Fecha</th>
					<th>Glosa</th>
					<th>Caja</th>
					<th>Comprobante</th>
					<th>Codigo</th>
					<th>Cuenta</th>				
					<th>Debe</th>
					<th>Haber</th>
					<th>Tipo Comprobante</th>
					<th>Referencia</th>				
				</tr>
			</thead>
			<tbody>
				@php
					$i=0;
				@endphp
				@foreach ($datosContaDiario as $key => $dato)
					{{-- @if ($dato->contrato1) --}}
						@php
							$i++;
						@endphp
						<tr>
							<td>{{$i}}</td>
							<td>0</td>
							<td>{{ $dato->ci }}</td>
							<td>{{ $dato->nom }}</td>
							<td>{{ $dato->sucural->nombre }}</td>
							<td>{{ $dato->periodo }}</td>
							<td>{{ $dato->fecha_a }}</td>
							<td>{{ $dato->glosa }}</td>
							<td>{{ $dato->caja }}</td>
							<td>{{ $dato->num_comprobante }}</td>
							<td>{{ $dato->cod_deno }}</td>
							<td>{{ $dato->cuenta }}</td>						
							<td>{{ number_format($dato->debe,2,".",",") }}</td>
							<td>{{ number_format($dato->haber,2,".",",") }}</td>	
							<td>{{ $dato->tcom }}</td>
							<td>{{ $dato->ref }}</td>				
						</tr>
					{{-- @endif					 --}}
				@endforeach
			</tbody>
		</table>	
		<button type="button" class="btn btn-brand" onClick="fnGenerarComprobante();"><b><i class="icon-reload-alt"></i></b>Generar</button>
	</div>
@endif