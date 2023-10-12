@if (isset($fechaI))
    <div class="table-responsive">
        {{-- <a href="{{ url('/') }}/ExportarCoberturaVacunacion/{{ $tipoDestino }}/{{ $resEstablecimiento }}/{{ $resDepartamento }}/{{ $resRed }}/{{ $ddlPeriodo }}/{{ $ddlIntervalo }}/{{ $resMesInicio }}/{{ $resMesFin }}/{{ $resFechaInicio }}/{{ $resFechaFin }}/{{ $ddlVacuna }}/{{ $ddlDosis }}/{{ $ddlGrupoEtareo }}" class="btn btn-success">Exportar Excel</a> --}}
        {{-- <a href="{{ url('/') }}/ExportarContaDiario/{{ $fechaI }}/{{ $fechaF }}" class="btn btn-success">Exportar Excel</a> --}}
        <a href="#" onClick = "fnExportarExcelContaDiario();" class="btn btn-success">Exportar Excel</a>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>CI</th>
                    <th>Nombre Completo</th>
                    <th>Contrato</th>
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
                    $i = 0;
                @endphp
                @foreach ($datosContaDiario as $key => $dato)
                    @if ($dato->contrato_id == 0)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <td>{{ $i }}</td>
                            @if ($dato->ci)
                                <td>{{ $dato->ci }}</td>
                                <td>{{ $dato->nom }}</td>
                            @else
                                <td>2773500</td>
                                <td>MARIO ROJAS YUCRA</td>
                            @endif

                            @if ($dato->contrato_id == 0)
                                <td>{{ $dato->correlativo }} - {{ $dato->gestion }}</td>
                            @else
                                <td>{{ $dato->contrato_id }}</td>
                            @endif


                            <td>{{ $dato->sucural->nombre }}</td>
                            <td>{{ $dato->periodo }}</td>
                            <td>{{ $dato->fecha_a }}</td>
                            <td>{{ $dato->glosa }}</td>
                            <td>{{ $dato->caja }}</td>
                            <td>{{ $dato->num_comprobante }}</td>
                            <td>{{ $dato->cod_deno }}</td>
                            <td>{{ $dato->cuenta }}</td>
                            <td>{{ number_format($dato->debe, 2, '.', ',') }}</td>
                            <td>{{ number_format($dato->haber, 2, '.', ',') }}</td>
                            <td>{{ $dato->tcom }}</td>
                            <td>{{ $dato->ref }}</td>
                            <td><a href="#" onClick = "fnEditarContaDiario({{ $dato }});"
                                    data-popup="tooltip" title="Editar" data-toggle="modal"
                                    data-target="#modalUpdateContaDiario" data-keyboard="false"><i
                                        class="fa fa-fw fa-edit"></i></a>
                                <a href="#" data-popup="tooltip" title="Eliminar"
                                    onClick = "fnEliminarContaDiario({{ $dato->id }});"><i
                                        class="fa fa-fw fa-trash-o"></i></a>
                            </td>
                        </tr>
                    @else
                        @if ($dato->contrato1)
                            @php
                                $i++;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $dato->contrato1->cliente->persona->nrodocumento }}</td>
                                <td>{{ $dato->contrato1->cliente->persona->nombreCompleto() }}</td>
                                @if ($dato->contrato_id > 0)
                                    @if ($dato->contrato1->codigo)
                                        <td>{{ $dato->contrato1->codigo }}</td>
                                    @else
                                        <td>{{ $dato->sucural->nuevo_codigo }}
                                            {{ Carbon\Carbon::parse($dato->contrato1->fecha_contrato)->format('y') }}{{ $dato->contrato1->codigo_num }}
                                        </td>
                                    @endif
                                @else
                                    <td>{{ $dato->contrato_id }}</td>
                                @endif
                                <td>{{ $dato->sucural->nombre }}</td>
                                <td>{{ $dato->periodo }}</td>
                                <td>{{ $dato->fecha_a }}</td>
                                <td>{{ $dato->glosa }}</td>
                                <td>{{ $dato->caja }}</td>
                                <td>{{ $dato->num_comprobante }}</td>
                                <td>{{ $dato->cod_deno }}</td>
                                <td>{{ $dato->cuenta }}</td>
                                <td>{{ number_format($dato->debe, 2, '.', ',') }}</td>
                                <td>{{ number_format($dato->haber, 2, '.', ',') }}</td>
                                <td>{{ $dato->tcom }}</td>
                                <td>{{ $dato->ref }}</td>
                                <td><a href="#" onClick = "fnEditarContaDiario({{ $dato }});"
                                        data-popup="tooltip" title="Editar" data-toggle="modal"
                                        data-target="#modalUpdateContaDiario" data-keyboard="false"><i
                                            class="fa fa-fw fa-edit"></i></a>
                                    <a href="#" data-popup="tooltip" title="Eliminar"
                                        onClick = "fnEliminarContaDiario({{ $dato->id }});"><i
                                            class="fa fa-fw fa-trash-o"></i></a>
                                </td>
                            </tr>
                        @endif
                    @endif
                    {{-- @if ($dato->contrato1)
						@php
							$i++;
						@endphp
						<tr>
							<td>{{$i}}</td>
							
							@if ($dato->contrato_id > 0)
								@if ($dato->contrato1->codigo)
									<td>{{ $dato->contrato1->codigo }}</td>
								@else
									<td>{{$dato->sucural->nuevo_codigo}} {{ Carbon\Carbon::parse($dato->contrato1->fecha_contrato)->format('y')}}{{$dato->contrato1->codigo_num }}</td>
								@endif
							@else
								<td>{{ $dato->contrato_id }}</td>
							@endif
							<td>{{ $dato->sucural->nombre }}</td>
							<td>{{ $dato->periodo }}</td>
							<td>{{ $dato->fecha_a }}</td>
							<td>{{ $dato->glosa }}</td>
							<td>{{ $dato->caja }}</td>
							<td>{{ $dato->num_comprobante }}</td>
							<td>{{ $dato->cod_deno }}</td>
							<td>{{ $dato->cuenta }}</td>						
							<td>{{ $dato->debe }}</td>
							<td>{{ $dato->haber }}</td>	
							<td>{{ $dato->tcom }}</td>
							<td>{{ $dato->ref }}</td>				
						</tr>

					@endif	 --}}
                @endforeach
            </tbody>
        </table>
    </div>
@endif
