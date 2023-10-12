@extends('layout.principal')
@include('reporte.modalReporte')
@include('reporte.modalReporteCargar')

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        DATOS INICIO FIN CAJA
                        @if (Session::get('ID_ROL') == 1)
                            <button class="btn btn-danger" data-toggle="modal" data-target="#modal_cierres">Eliminar
                                cierres</button>
                        @endif
                    </h3>
                </div>
            </section>
        </div>
    </div>
    <input type="hidden" value="{{ route('InicioFinCaja.lista_cierres') }}" id="urlListaCierres">
    @if (empty($datoValidarCaja->fecha_cierre))
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Nombre Cliente</th>
                                <th>Ref.</th>
                                <th>Fecha Pago</th>
                                <th>Inicio Caja Bs</th>
                                <th>Inicio Caja $us</th>
                                <th>Ingreso</th>
                                <th>Egreso</th>
                                <th>Tipo Movimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $valores_cambio = App\CambioMoneda::first();
                            @endphp
                            @foreach ($datosCaja as $key => $datoCaja)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if ($datoCaja->contrato)
                                        {{-- <td>{{ $datoCaja->contrato->codigo }}</td> --}}
                                        @if ($datoCaja->contrato->codigo != '')
                                            <td>{{ $datoCaja->contrato->codigo }}</td>
                                        @else
                                            @php
                                                $gestion = substr($datoCaja->contrato->gestion, 2, 2);
                                                $rescodigo = $datoCaja->contrato->sucural->nuevo_codigo . '' . $gestion . '' . $datoCaja->contrato->codigo_num;

                                            @endphp
                                            <td>{{ $rescodigo }}</td>
                                        @endif
                                    @else
                                        <td></td>
                                    @endif
                                    @if ($datoCaja->contrato)
                                        <td>{{ $datoCaja->contrato->cliente->persona->nombreCompleto() }}</td>
                                    @else
                                        <td>{{ $datoCaja->persona->nombreCompleto() }}</td>
                                    @endif


                                    {{-- <td>{{ date('d-m-Y', strtotime($datoCaja->fecha_pago)) }}</td> --}}
                                    <td>{{ $datoCaja->ref }}</td>
                                    <td>{{ $datoCaja->created_at }}</td>
                                    @if ($datoCaja->moneda_id == 2)
                                        <td>{{ number_format((float) $datoCaja->inicio_caja_bs * $valores_cambio->valor_bs, 2, '.', ',') }}
                                            Bs</td>
                                        <td>{{ number_format(((float) $datoCaja->inicio_caja_bs), 2, '.', ',') }} $us</td>
                                    @else
                                        <td>{{ number_format($datoCaja->inicio_caja_bs, 2, '.', ',') }} Bs</td>
                                        <td>{{ number_format((float) $datoCaja->inicio_caja_bs / $valores_cambio->valor_bs, 2, '.', ',') }}
                                            $us</td>
                                    @endif
                                    <td>{{ number_format($datoCaja->ingreso_bs, 2, '.', ',') }}</td>
                                    <td>{{ number_format($datoCaja->egreso_bs, 2, '.', ',') }}</td>
                                    <td>{{ $datoCaja->tipo_de_movimiento }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <button type="button" class="btn btn-warning btn-labeled btn-sm" onClick="fnCerrarCaja();"><b><i
                        class="icon-reload-alt"></i></b>Cerrar Caja</button>

            <button type="button" class="btn btn-primary btn-labeled btn-sm" onClick="fnImprimirInicioFinCaja();"><b><i
                        class="icon-reload-alt"></i></b>Imprimir</button>
        </div>
    @endif

    @include('inicioFinCaja.modal.cierres')


    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {});

        /******************************* PAGAR PAGO  OPCION*****************************************/
        function fnCerrarCaja() {
            var value = 1;
            var route = "/InicioFinCaja/" + value + "";
            Swal.fire({
                title: "Estas seguro de cerar la caja",
                //html: mensaje,
                text: "Presione Si para cerrar la  caja en la base de datos!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, Cerrar!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: route,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'PUT',
                        dataType: 'json',
                        data: {

                        },
                        success: function(data) {
                            console.log(data.Mensaje);
                            resultado = data.Mensaje;

                            /*resultado = 1  ROL ELIMINADO*/
                            if (resultado == 1) {
                                Swal.fire({
                                    title: "CAJA!",
                                    text: "Se cerro correctamente!!",
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: "#66BB6A",
                                    type: "success"
                                });
                                fnImprimirInicioFinCajaCerrar();
                            }

                            if (resultado == 2) {
                                Swal.fire({
                                    title: "ATENCIÓN",
                                    text: "Ya se realizó el cierre de cajas",
                                    confirmButtonColor: "#EF5350",
                                    confirmButtonText: 'Aceptar',
                                    type: "info"
                                });
                            }

                            /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                            if (resultado == 0) {
                                Swal.fire({
                                    title: "PAGO...",
                                    text: "hubo problemas al registrar en BD",
                                    confirmButtonColor: "#EF5350",
                                    confirmButtonText: 'Aceptar',
                                    type: "error"
                                });
                            }

                            /*resultado = -1 SESION EXPIRADA*/
                            if (resultado == "-1") {
                                Swal.fire({
                                    title: "PAGO...",
                                    text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
                                    confirmButtonColor: "#EF5350",
                                    confirmButtonText: 'Aceptar',
                                    type: "error"
                                });
                            }
                        },
                        error: function(result) {
                            Swal.fire("Opss..!", "El La persona tiene registros en otras tablas!",
                                "error")
                        }
                    });
                }
            })

        }

        function fnImprimirInicioFinCaja() {
            $("#reporteModal").modal();
            var src = "/ImprimirInicioFinCaja";
            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);
            $("#dialog").show();
        }

        function fnImprimirInicioFinCajaCerrar() {
            $("#reporteModalC").modal();
            var src = "/ImprimirInicioFinCaja";
            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogC").html(object);
            $("#dialogC").show();
        }

        function fnCargarPagina() {
            setTimeout(function() {
                location.reload();
            }, 2000);
        }


        // LISTA DE CIERRES
        $(document).on('click', '.contenedor_lista_cierres .pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetch_data(page);
        });

        function fetch_data(page) {
            fnListaCierres(page);
        }

        function fnListaCierres(page = 1) {
            $.ajax({
                url: $("#urlListaCierres").val() + "?page=" + page,
                success: function(data) {
                    $('#table_data_cierres').html(data);
                }
            });
        }

        function fnEliminarCierre(e, id, fecha, sucursal, caja) {
            e.preventDefault();
            console.log("asdasdasd");
            var route = "/InicioFinCaja/" + id + "";
            Swal.fire({
                title: `ELIMINAR REGISTRO`,
                html: `¿Está seguro(a) de eliminar este registro?<br/><b>Fecha:</b> ${fecha}<br/><b>Sucursal:</b> ${sucursal}<br/><b>Caja:</b> ${caja}`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Si, Eliminar!",
                closeOnConfirm: false,
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: route,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        dataType: 'json',
                        success: function(data) {
                            fnListaCierres();
                            Swal.fire({
                                title: "¡CORRECTO!",
                                text: "Registro eliminado correctamente",
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: "#66BB6A",
                                type: "success"
                            });
                        },
                        error: function(result) {
                            Swal.fire("Opss..!", "Ocurrió un error inesperado",
                                "error")
                        }
                    });

                }
            })


        }
    </script>
@endsection
