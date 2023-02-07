<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
                <th>Fecha</th>
                <th>10 Klts</th>
                <th>14 Klts</th>
                <th>18 Klts</th>
                <th>24 Klts</th>
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($precios as $key => $value)
                <tr>
                    <td>{{$value->fecha}}</td>
                    <td>{{$value->dies}}</td>
                    <td>{{$value->catorce}}</td>
                    <td>{{$value->diesiocho}}</td>
                    <td>{{$value->veinticuatro}}</td>
                    <td class="opciones">
                        <a href="#" onClick = "fnEditarPrecio({{$value}});" data-popup="tooltip" title="Editar" data-toggle="modal" data-target="#modalUpdatePrecio" data-keyboard="false"><i class="fa fa-fw fa-edit"></i></a>
                    </td>
                </tr>
			@endforeach
		</tbody>
	</table>	
</div>