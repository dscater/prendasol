@if(isset($datosContaDiario))
	@if(count($datosContaDiario)>0)
	<div class="table-responsive">		
		<table border="0" cellspacing="0" cellpadding="3">
			<tbody>
				@php
					$totalImporteDebe =0;
            		$totalImporteHaber =0;
            		$sumaDebe=0;
                	$sumaHaber=0; 
            		$a = "";
            		$b = "";
				@endphp	
				@foreach ($datosContaDiario as $dato)
					@php
						$fechaLetras = strftime("%d  - %B - %Y", strtotime($dato->fecha_a));
						$a = $dato->num_comprobante;
					@endphp
					{{-- valor A:{{ $a }} => {{ $dato->num_comprobante }}<br>
					valor B:{{ $b }} => {{ $dato->num_comprobante }} --}}
					@if ($a != $b)
						<tr>
							<td align="center" width="100%"colspan="4"><strong>____________ {{ $dato->num_comprobante }} ___________</strong></td>
						</tr>
						<tr>
							<td align="center"  colspan="2" width="20%"><strong>{{ $fechaLetras }}</strong></td>
							<td align="center"  width="40%"><strong></strong></td>
							<td align="center"  width="20%"><strong><u> DEBE </u> </strong></td>
							<td align="center"  width="20%"><strong><u> HABER </u></strong></td>
						</tr>
						@php
							$sumaDebe = $sumaDebe + $dato->debe;
                    		$sumaHaber = $sumaHaber + $dato->haber;
						@endphp
						<tr>
							<td align="center"  width="10%">{{ $dato->cod_deno }}</td>
							<td align="left"  width="40%">{{ $dato->cuenta }}</td>
							<td align="right"  width="25%">{{ number_format($dato->debe, 2, ',', '.') }}</td>
							<td align="right"  width="20%">{{ number_format($dato->haber, 2, ',', '.') }}</td>
						</tr>
					@else
						<tr>
							<td align="center"  width="10%">{{ $dato->cod_deno }}</td>
							<td align="left"  width="40%">{{ $dato->cuenta }}</td>
							<td align="right"  width="25%">{{ number_format($dato->debe, 2, ',', '.') }}</td>
							<td align="right"  width="20%">{{ number_format($dato->haber, 2, ',', '.') }}</td>
						</tr>
					@endif
					
					
					{{-- @foreach ($detalleContaIdario as $dato2)
						@php
							$sumaDebe = $sumaDebe + $dato2->debe;
                    		$sumaHaber = $sumaHaber + $dato2->haber;
						@endphp	
						<tr>
							<td align="center"  width="10%">{{ $dato2->cod_deno }}</td>
							<td align="left"  width="40%">{{ $dato2->cuenta }}</td>
							<td align="right"  width="25%">{{ number_format($dato2->debe, 2, ',', '.') }}</td>
							<td align="right"  width="20%">{{ number_format($dato2->haber, 2, ',', '.') }}</td>
						</tr>
						@php
							$totalImporteDebe = $totalImporteDebe+$dato2->debe;
                    		$totalImporteHaber = $totalImporteHaber+$dato2->haber;
						@endphp	
					@endforeach
					<tr>
						<td align="right"  width="10%">GLOSA:</td>
						<td align="left"  width="50%">{{ $dato2->glosa }}</td>
					</tr>
					<tr>
						<td align="right"  width="60%" colspan="2"><strong>SUMA</strong></td>
						<td align="right"  width="15%" ><strong><u>{{ number_format($sumaDebe, 2, ',', '.') }}</u></strong></td>
						<td align="right"  width="20%" ><strong><u>{{ number_format($sumaHaber, 2, ',', '.') }}</u></strong></td>
					</tr> --}}
					@php
						$b = $dato->num_comprobante;
					@endphp
					
				@endforeach
			</tbody>
			{{-- <tfoot>
				<tr>
					<th scope="row" colspan="2" align="right"><strong>SUMA TOTAL DE MOVIMIENTOS:</strong></th>
					<td align="right"><strong>{{ number_format($totalImporteDebe, 2, ',', '.') }}</strong></td>
					<td align="right"><strong>{{ number_format($totalImporteHaber, 2, ',', '.') }}</strong></td>
				</tr>
			</tfoot> --}}
		</table>				
	</div>
	@else
	No se encontrarón registros
	@endif
@else
	No se encontrarón registros
@endif

