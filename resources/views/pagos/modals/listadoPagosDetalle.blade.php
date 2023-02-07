@if(isset($pagos))
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				{{-- <label>Cliente:{{ $cliente->persona->nombreCompleto() }}</label> --}}
				{{-- {{ $paciente->persona->nombreCompleto() }} --}}
            </div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Sucursal</th>
					<th>Caja</th>
					<th>Fecha Inicio</th>
					<th>Fecha Fin</th>
					<th>Días Atraso</th>
					<th>Días Atraso Total</th>
					<th>Cuota Mora</th>
					<th>Capital</th>
					<th>Interes</th>
					<th>Comisión</th>
					<th>Total Capital</th>
					<th>Moneda</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($pagos as $key => $pagos)
					<tr>
						<td>{{$key+1}}</td>
						<td>{{ $pagos->sucural->nombre }}</td>
						<td>{{ $pagos->caja }}</td>
						<td>{{ date('Y-m-d',strtotime($pagos->fecha_inio)) }}</td>
						<td>{{ date('Y-m-d',strtotime($pagos->fecha_fin)) }}</td>
						<td>{{ $pagos->dias_atraso }}</td>
						<td>{{ $pagos->dias_atraso_total }}</td>
						<td>{{ $pagos->cuota_mora }}</td>
						<td>{{ $pagos->capital }}</td>
						<td>{{ $pagos->interes }}</td>
						<td>{{ $pagos->comision }}</td>
						<td>{{ $pagos->total_capital }}</td>
						<td>{{ $pagos->moneda->desc_corta }}</td>
						<td>{{ $pagos->estado }}</td>
					</tr>
				@endforeach			
			</tbody>
		</table>	
	</div>
@endif