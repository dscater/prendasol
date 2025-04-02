@extends('layout.principal')
@section('css')
    <style>
        .nuevos {
            background: rgb(234, 252, 234);
        }

        .antiguos {
            background: rgb(236, 235, 218);
        }

        .oculto {
            display: none;
        }
    </style>
@endsection

@include('reporte.modalReporteContratosCancelados')
@section('main-content')
    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>INTERESES ADMINISTRABLES</h3>
                </div>
            </section>
        </div>
    </div>

    <div class="row" style="margin-bottom: 8px;">
        <div class="col-md-12">
            <button type="button" data-toggle="modal" data-target="#modalNuevo" class="btn btn-primary"><i
                    class="fa fa-plus"></i> Nuevo</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if (session('bien'))
                                <div class="alert alert-success">{{ session('bien') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="bg-green">
                                <th>MONTO DESDE</th>
                                <th>MONTO HASTA</th>
                                <th>%</th>
                                <th>FECHA</th>
                                <th>CONTRATOS</th>
                                <th width="9%"></th>
                            </tr>
                        </thead>
                        <tbody id="contenedorReg">
                            @foreach ($interes_administrables as $ia)
                                @php
                                    $css = 'nuevos';
                                    if ($ia->tipo == 'ANTIGUOS') {
                                        $css = 'antiguos';
                                    }
                                    $fecha = '-';
                                    if ($ia->fecha) {
                                        $fecha = date('d/m/Y', strtotime($ia->fecha));
                                    }
                                @endphp
                                <tr class="{{ $css }}">
                                    <td>{{ $ia->monto1 }}</td>
                                    <td>{{ $ia->monto2 }}</td>
                                    <td>{{ $ia->porcentaje }}%</td>
                                    <td>{{ $fecha }}</td>
                                    <td>{{ $ia->tipo }}</td>
                                    <td>
                                        @include('interes_administrables.parcial.edit')
                                        <button type="button" data-toggle="modal"
                                            data-target="#modalEdit{{ $ia->id }}" class="btn btn-warning"><i
                                                class="fa fa-edit"></i></button>

                                        <button class="btn btn-danger eliminar"
                                            data-url="{{ route('interes_administrables.destroy', $ia->id) }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <form action="{{ route('actualizaFechaContratosAntiguos') }}" method="POST">
                            @csrf
                            <div class="col-md-4">
                                <label>Fecha contratos antiguos: <i style="font-size: 1.1rem;display:block;">Se aplicaran a
                                        todos los contratos
                                        cuya fecha sea
                                        menor o igual</i></label>
                                <input type="date" name="fecha" class="form-control" value="{{ $fecha_antiguos }}">
                                <button class="btn btn-primary" style="margin-top:4px;">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalNuevo">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('interes_administrables.store') }}" method="post">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Nuevo registro</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Monto desde:</label>
                            <input type="number" name="monto1" min="0" step="0.01" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Monto hasta:</label>
                            <input type="number" name="monto2" min="0" step="0.01" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Contratos:</label>
                            <select name="tipo" id="" class="form-control select_sw">
                                <option value="">- Seleccione -</option>
                                <option value="NUEVOS">NUEVOS</option>
                                <option value="ANTIGUOS">ANTIGUOS</option>
                            </select>
                        </div>

                        {{-- <div class="col-md-12 form-group contenedor_fecha oculto">
                            <label>Fecha: <i style="font-size: 1.1rem;display:block;">Se aplicaran a todos los contratos
                                    cuya fecha sea
                                    menor o igual</i></label>
                            <input type="date" name="fecha" class="form-control" value="">
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Porcentaje:</label>
                            <input type="number" name="porcentaje" min="0" step="0.01" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    <button id="btnGuardar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <input type="hidden" id="urlGetSaldos" value="{{ route('cajas.get_saldos_caja') }}">

@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script>
        // $(document).on("change", ".select_sw", function() {
        //     const form_group = $(this).parent();
        //     const contenedor_fecha = form_group.siblings(".contenedor_fecha");
        //     const value = $(this).val();
        //     contenedor_fecha.hide();
        //     if (value) {
        //         if (value == 'NUEVOS') {
        //             contenedor_fecha.hide();
        //         } else {
        //             contenedor_fecha.show();
        //         }
        //     }
        // });

        $("#contenedorReg").on("click", ".eliminar", function() {
            const url = $(this).attr("data-url");
            Swal.fire({
                title: "¿ESTAS SEGURO(A) DE ELIMINAR ESTE REGISTRO?",
                text: '',
                type: "question",
                showCancelButton: true,
                confirmButtonColor: "#007236",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "Cancelar",
            }).then(confirm => {
                if (confirm.value) {
                    $.ajax({
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            _method: "delete"
                        },
                        dataType: 'json',
                        success: function(resp) {
                            Swal.fire({
                                title: "CORRECTO",
                                text: "Registro eliminado",
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: "#66BB6A",
                                type: "success"
                            });
                            setTimeout(() => {
                                window.location.reload();
                            }, 700);
                        }
                    });
                }
            });
        })
    </script>
@endsection
