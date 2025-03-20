@extends('layout.principal')
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
                                <th width="9%"></th>
                            </tr>
                        </thead>
                        <tbody id="contenedorReg">
                            @foreach ($interes_administrables as $ia)
                                <tr>
                                    <td>{{ $ia->monto1 }}</td>
                                    <td>{{ $ia->monto2 }}</td>
                                    <td>{{ $ia->porcentaje }}%</td>
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
