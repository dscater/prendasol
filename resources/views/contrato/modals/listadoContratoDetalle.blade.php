@if(isset($contratoDetalle))
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Cliente:{{ $cliente->persona->nombreCompleto() }}</label>
				{{-- {{ $paciente->persona->nombreCompleto() }} --}}
            </div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Cantidad</th>
					<th>Descripción</th>
					<th>Peso Bruto</th>
					<th>10 Klts</th>
					<th>14 Klts</th>				
					<th>18 Klts</th>
					<th>24 Klts</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($contratoDetalle as $key => $contratoD)
					<tr>
						<td>{{$key+1}}</td>
						<td>{{ $contratoD->cantidad }}</td>
						<td>{{ $contratoD->descripcion }}</td>
						<td>{{ $contratoD->peso }}</td>
						<td>{{ $contratoD->dies }}</td>
						<td>{{ $contratoD->catorce }}</td>
						<td>{{ $contratoD->dieciocho }}</td>
						<td>{{ $contratoD->veinticuatro }}</td>
						<td class="ver_detalle">
							@if($contratoD->foto != NULL && $contratoD->foto !='')
							<a href="#" data-foto="{{asset('template/imgs/'.$contratoD->foto)}}" data-toggle="modal" data-target="#modal_img{{$contratoD->id}}">Foto</a>
							@else
							<a href="#" data-foto="{{asset('template/imgs/img_default.png')}}" data-toggle="modal" data-target="#modal_img{{$contratoD->id}}">Foto</a>
							@endif
						</td>
					</tr>
				@endforeach	
										
			</tbody>
		</table>	
	</div>
@endif