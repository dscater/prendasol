<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
                <th>Moneda</th>
                <th>Descripción Corta</th>
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($monedas as $key => $value)
                <tr>
                    <td>{{$value->moneda}}</td>
                    <td>{{$value->desc_corta}}</td>
                    <td class="opciones">
                        <a href="#" onClick = "fnEditarMoneda({{$value}});" data-popup="tooltip" title="Editar" data-toggle="modal" data-target="#modalUpdateMoneda" data-keyboard="false"><i class="fa fa-fw fa-edit"></i></a>
                    </td>
                </tr>
			@endforeach
		</tbody>
	</table>	
</div>