@foreach ($sucursales as $sucursal)
    @for ($i = 0; $i < count($array_cajas[$sucursal->id]); $i++)
        <tr>
            <td>{{ $sucursal->nombre }}</td>
            <td>{{ $array_cajas[$sucursal->id][$i] }}</td>
            <td>{{ date('d/m/Y', strtotime($fecha)) }}</td>
            <td>{{ $array_saldos[$sucursal->id][$i]['usuario'] }}</td>
            <td class="text-right">{{ $array_saldos[$sucursal->id][$i]['inicio'] }}</td>
            <td class="text-right">{{ $array_saldos[$sucursal->id][$i]['prestamo'] }}</td>
            <td class="text-right">{{ $array_saldos[$sucursal->id][$i]['saldo'] }}</td>
        </tr>
    @endfor
@endforeach
<tr class="bg-green">
    <td colspan="4" class="text-right">TOTAL</td>
    <td class="text-right">{{$array_totales[0]}}</td>
    <td class="text-right">{{$array_totales[1]}}</td>
    <td class="text-right">{{$array_totales[2]}}</td>
</tr>
