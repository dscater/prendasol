@if(isset($contratos))
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<small>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateContrato" onClick="fnDatosCliente();"><i class="icon-new-tab position-left"></i>Nuevo</button>
				</small>
			</div>
		</div>		
	</div>
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
					<th>Moneda</th>			
					<th>Interes</th>
					<th>Sucursal</th>
					<th>Caja</th>
					<th>Estado Pago</th>
					<th>Estado</th>
					<th>Estado Entrega</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($contratos as $key => $contrato)
					<tr>
						<td>{{$key+1}}</td>
						@if($contrato->codigo != "")
							<td>{{ $contrato->codigo }}</td>
						@else							
							<td>{{$contrato->sucural->nuevo_codigo}} {{ Carbon\Carbon::parse($contrato->fecha_contrato)->format('y')}}{{$contrato->codigo_num }}</td>
						@endif
						{{-- <td>{{ $contrato->codigo }}</td> --}}
						<td>{{ $contrato->peso_total }}</td>
						<td>@if(isset($contrato->fecha_contrato)){{ date('d-m-Y', strtotime($contrato->fecha_contrato)) }}@endif</td>
						<td>@if(isset($contrato->fecha_fin)){{ date('d-m-Y', strtotime($contrato->fecha_fin)) }}@endif</td>
						<td>{{ $contrato->capital }}</td>	
						<td>{{ $contrato->total_capital }}</td>	
						<td>{{$contrato->moneda->desc_corta}}</td>
						<td>{{ $contrato->interes }}</td>
						<td>@if(isset($contrato->sucural->nombre)){{ $contrato->sucural->nombre }}@endif</td>
						<td>{{ $contrato->caja }}</td>
						<td>{{ $contrato->estado_pago }}</td>
						<td>{{ $contrato->estado_pago_2 }}</td>
						<td>{{ $contrato->estado_entrega }}</td>	
						<td>
							<a href="#" onClick = "fnDetalleContratos({{ $contrato->id }});" data-popup="tooltip" data-toggle="modal" data-target="#modalDetalleContrato" title="Editar"><i class="fa fa-fw fa-edit"></i></a>
						</td>
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
