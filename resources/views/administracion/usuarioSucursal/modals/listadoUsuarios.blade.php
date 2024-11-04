<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nro. CI</th>
                <th>Nombre</th>
                <th>Paterno</th>
                <th>Materno</th>
                <th>Usuario</th>
                <th>Sucursal</th>
                <th>Caja</th>
                <th>Rol</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuariosSucursales as $key => $usuarioSucursal)
                @if (isset($usuarioSucursal))
                    <tr>
                        <td>
                            {{ $key + 1 }}
                        </td>

                        <td>
                            {{ $usuarioSucursal->personaDatos($usuarioSucursal->usuario->persona_id)->nrodocumento }}

                        </td>
                        <td>
                            {{ $usuarioSucursal->personaDatos($usuarioSucursal->usuario->persona_id)->nombres }}

                        </td>
                        <td>
                            {{ $usuarioSucursal->personaDatos($usuarioSucursal->usuario->persona_id)->primerapellido }}
                        </td>
                        <td>
                            {{ $usuarioSucursal->personaDatos($usuarioSucursal->usuario->persona_id)->segundoapellido }}

                        </td>
                        <td>
                            {{ $usuarioSucursal->usuario->usuario }}

                        </td>
                        <td>
                            {{ $usuarioSucursal->sucursal ? $usuarioSucursal->sucursal->nombre : '' }}
                        </td>
                        <td>
                            {{ $usuarioSucursal->caja }}
                        </td>
                        <td>
                            {{ $usuarioSucursal->usuario->usuarioRol->rol->rol }}
                        </td>
                        <td>
                            @if ($usuarioSucursal->estado_id == 1 || $usuarioSucursal->estado_id == 3)
                                {{-- <a href="#" data-popup="tooltip" title="Resetear" onClick = "fnEliminarUsuarioSucursal({{ $usuarioSucursal->id }});"><i class="fa fa-fw fa-trash-o"></i></a> --}}
                                <a href="#" data-popup="tooltip" title="Deshabilitar"
                                    onClick = "fnEliminarUsuarioSucursal({{ $usuarioSucursal->id }});"><i
                                        class="fa fa-fw fa-thumbs-o-down"></i></a>
                            @endif

                            @if ($usuarioSucursal->estado_id == 2)
                                <a href="#" data-popup="tooltip" title="Habilitar"
                                    onClick = "fnHabilitarUsuarioSucursal({{ $usuarioSucursal->id }});"><i
                                        class="fa fa-fw fa-thumbs-o-up"></i></a>
                            @endif
                        </td>


                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

{{-- @if (isset($usuario->usuario)) --}}
{{ $usuariosSucursales->links() }}
{{-- @endif --}}
