@extends('layout.principal')
@include('precioOro.modal.modalUpdatePrecio')

@section('main-content')
    <script>
        function fnEditarPrecio(datos) {
            $("#id").val(datos.id);
            $("#txtDies").val(datos.dies);
            $("#txtCatorce").val(datos.catorce);
            $("#txtDiesiocho").val(datos.diesiocho);
            $("#txtVeinticuatro").val(datos.veinticuatro);
        }

        /******************************** LIMPIA CONTRAOLES  *****************************************/
        function fnLimpiarControles() {
            $("#id").val('');
            $("#txtDies").val('');
            $("#txtCatorce").val('');
            $("#txtDiesiocho").val('');
            $("#txtVeinticuatro").val('');
            $('#frmPrecio').bootstrapValidator('frmPrecio', true);
        }

        function fnLimpiarControlesParametros() {
            $("#idP").val('');
            $("#txtDiesP").val('');
            $("#txtCatorceP").val('');
            $("#txtDiesiochoP").val('');
            $("#txtVeinticuatroP").val('');
            $('#frmPrecioParametros').bootstrapValidator('frmPrecio', true);
        }

    </script>

    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token">
        --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        PRECIO ORO
                    </h3>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 form-group">
            <label>Fecha: </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="date" class="form-control" value="{{ Carbon\Carbon::now('America/La_Paz')->format('Y-m-d') }}"
                    id="txtBuscaFecha" placeholder="Fecha">
            </div>
        </div>
        <div class="col-md-2 form-group">
            <label>&nbsp;</label>
            <div style="display:flex;">
                <button type="button" class="btn btn-warning btn-xs" onclick="fnBuscarPersonas()"><i
                        class="icon-search4 position-left"></i>Buscar</button>
                <a href="#" data-toggle="modal" data-target="#modalUpdatePrecioParametros" class="btn btn-success btn-sm"
                    id="btnParametrosCreacion" style="margin-left:3px;">Parametros de creación</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section class="clsPrecios" id="contPrecios">
                @include('precioOro.modal.listaPrecios')
            </section>
        </div>
    </div>

    @include('precioOro.modal.parametros_precio')

    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            /*/******************** ANULAR ENTER EN FORMULARIOS ******************/
            $('form').keypress(function(e) {
                if (e == 13) {
                    return false;
                }
            });

            /*/******************** ANULAR ENTER EN FORMULARIOS ******************/
            $('input').keypress(function(e) {
                if (e.which == 13) {
                    return false;
                }
            });

            $('#txtBuscaFecha').change(function() {
                fnListarPrecios('/PrecioOro');
            });

            $('#btnCambioMonedas').click(function() {
                let txtSus = $('#txtSus');
                let txtBs = $('#txtBs');
                txtSus.val(_valorSus.val());
                txtBs.val(_valorBs.val());
            });

            function fnListarPrecios(url) {
                var parametros = {
                    'fecha': $("#txtBuscaFecha").val(),
                };
                var route = url;
                $.ajax({
                    type: 'GET',
                    url: route,
                    data: parametros,
                    //dataType: 'json',
                    success: function(data) {
                        $('#contPrecios').html(data.html);
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "PRECIO...",
                            text: "No se pudo cargar los registros",
                            confirmButtonColor: "#66BB6A",
                            confirmButtonText: 'Aceptar',
                            type: "error"
                        });
                    }
                });
            }

            /******************** VALIDAR FROMULARIO PARA LA ACTUALIZACION ******************/
            $('#frmPrecio').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    dies: {
                        message: 'Precio no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    catorce: {
                        message: 'Precio no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    diesiocho: {
                        message: 'Precio no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    veinticuatro: {
                        message: 'Precio no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                }
            });

            $("#btnActualizar").click(function() {
                let url = '/PrecioOro/' + $('#id').val();
                var validaPrecio = $("#frmPrecio").data('bootstrapValidator');
                validaPrecio.validate();
                //$('#myCreate').html('<div><img src="../img/ajax-loader.gif"/></div>');
                if (validaPrecio.isValid()) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "PUT",
                        url: url,
                        data: {
                            dies: $("#txtDies").val(),
                            catorce: $("#txtCatorce").val(),
                            diesiocho: $("#txtDiesiocho").val(),
                            veinticuatro: $("#txtVeinticuatro").val(),
                        },
                        dataType: 'json',
                        success: function(response) {
                            fnListarPrecios('/PrecioOro');
                            $('#modalUpdatePrecio').modal('toggle');
                            Swal.fire({
                                title: "PRECIO...",
                                text: "Registro actualizado con éxito",
                                confirmButtonColor: "#66BB6A",
                                // EF5350 |color error
                                confirmButtonText: 'Aceptar',
                                type: "success"
                            });
                        }
                    });
                }

            });


            /*************** VALIDAR FROMULARIO PARA LA ACTUALIZACION DE PARAMETROS **************/
            $('#frmPrecioParametros').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    dies: {
                        message: 'Precio no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    catorce: {
                        message: 'Precio no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    diesiocho: {
                        message: 'Precio no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    veinticuatro: {
                        message: 'Precio no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                }
            });

            $('#btnParametrosCreacion').click(function() {
                $.ajax({
                    type: "GET",
                    url: "/ValorOro",
                    dataType: "json",
                    success: function(response) {
                        $("#txtDiesP").val(response.valor_oro.dies)
                        $("#txtCatorceP").val(response.valor_oro.catorce)
                        $("#txtDiesiochoP").val(response.valor_oro.diesiocho)
                        $("#txtVeinticuatroP").val(response.valor_oro.veinticuatro)
                    }
                });
            });

            // ACTUALIZAR PARAMETROS DE CREACIÓN
            $('#btnActualizarParametros').click(function() {
                let url = 'ValorOro/update';
                var validaPrecio = $("#frmPrecioParametros").data('bootstrapValidator');
                validaPrecio.validate();
                //$('#myCreate').html('<div><img src="../img/ajax-loader.gif"/></div>');
                if (validaPrecio.isValid()) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "PUT",
                        url: url,
                        data: {
                            dies: $("#txtDiesP").val(),
                            catorce: $("#txtCatorceP").val(),
                            diesiocho: $("#txtDiesiochoP").val(),
                            veinticuatro: $("#txtVeinticuatroP").val(),
                        },
                        dataType: 'json',
                        success: function(response) {
                            $("#txtDiesP").val(response.valor_oro.dies)
                            $("#txtCatorceP").val(response.valor_oro.catorce)
                            $("#txtDiesiochoP").val(response.valor_oro.diesiocho)
                            $("#txtVeinticuatroP").val(response.valor_oro.veinticuatro)

                            $('#modalUpdatePrecioParametros').modal('toggle');
                            Swal.fire({
                                title: "PRECIO...",
                                text: "Registro actualizado con éxito",
                                confirmButtonColor: "#66BB6A",
                                // EF5350 |color error
                                confirmButtonText: 'Aceptar',
                                type: "success"
                            });
                        }
                    });
                }
            });


        });

    </script>
@endsection
