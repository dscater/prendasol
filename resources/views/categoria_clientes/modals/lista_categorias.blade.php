<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
                <th>Nº</th>
                <th>Categoría</th>
                <th>Número Contratos</th>
                <th>% Interés</th>
                <th>Opción</th>
			</tr>
		</thead>
		<tbody>
            @php
                $cont = 1;
            @endphp
			@foreach ($categoria_clientes as $key => $categoria_cliente)
                <tr>
                    <td>{{$cont++}}</td>
                    <td>{{$categoria_cliente->nombre}}</td>
                    <td>{{$categoria_cliente->numero_contratos}}</td>
                    <td>{{$categoria_cliente->porcentaje}}</td>
                    <td class="opciones">
                        <a href="#" onClick = "fnEditarCategoria({{$categoria_cliente}});" data-popup="tooltip" title="Editar" data-toggle="modal" data-target="#modalEditCategoria" data-keyboard="false"><i class="fa fa-fw fa-edit"></i></a>

                        <a href="#" data-popup="tooltip" title="Eliminar" onClick = "fnEliminarCategoria({{ $categoria_cliente->id }});"><i class="fa fa-fw fa-trash-o"></i></a>
                    </td>
                </tr>
			@endforeach
		</tbody>
	</table>	
</div>