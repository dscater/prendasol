@extends('layout.principal')

@section('main-content')
    @include('plazo_pagos.modal.formulario')
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>PLAZOS DE PAGOS</h3>
                </div>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 form-group">
            <label>Tipo de Busqueda: </label>
            <select class="form-control" id="ddlTipoBusqueda" name="ddlTipoBusqueda" Value="">
                <option></option>
                <option value="CI">
                    CI
                </option>
                <option value="CO">
                    Codigo
                </option>
            </select>
        </div>
    </div>

    <div class="row" style='display:none;' id="divMostrarBusquedaCI">
        <div class="col-md-2 form-group">
            <label>Numero Identificación: </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-list-numbered"></i></span>
                <input type="text" class="form-control" id="txtBuscarIdentifiacion" placeholder="Identificación">
            </div>
        </div>
        <div class="col-md-2 form-group">
            <label>.</label>
            <p><button type="button" class="btn btn-warning btn-xs" onclick="fnBuscarPersonas('CI')"><i
                        class="icon-search4 position-left"></i>Buscar</button></p>
        </div>
    </div>

    <div class="row" style='display:none;' id="divMostrarBusquedaCodigo">
        <div class="col-md-2 form-group">
            <label>Código: </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-users"></i></span>
                <input type="text" class="form-control" id="txtBuscarCodigo" placeholder="Codigos">
            </div>
        </div>
        <div class="col-md-2 form-group">
            <label>.</label>
            <p><button type="button" class="btn btn-warning btn-xs" onclick="fnBuscarPersonas('CO')"><i
                        class="icon-search4 position-left"></i>Buscar</button></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section class="clsClientes">
                @include('plazo_pagos.parcial.listado_clientes')
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section class="clsCodigo">
                @include('plazo_pagos.parcial.lista_codigos')
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section class="clsContratos">
                @include('plazo_pagos.parcial.lista_contratos')
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <hr />
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center" id="filtro_contrato">
            Filtrando contrato con código: <span id="txt_fitro_codigo"
                class="font-weight-bold txt_info_span_plazo_pagos"></span><br>
            Cliente: <span id="txt_fitro_cliente" class="font-weight-bold txt_info_span_plazo_pagos"></span><br>
            <button class="btn btn-sm btn-primary" id="btnCancelaFiltro"><i class="fa fa-list-alt"></i> Mostrar
                todo</button>
        </div>
        <div class="col-md-12">
            <section id="contenedor_registros" class="table-responsive clsPlazoPagos">
            </section>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        let ddlTipoBusqueda = $("#ddlTipoBusqueda");
        let divMostrarBusquedaCI = $("#divMostrarBusquedaCI");
        let divMostrarBusquedaCodigo = $("#divMostrarBusquedaCodigo");
        let contenedor_registros = $("#contenedor_registros");

        // formulario
        let txtId = $("#txtId");
        let txtIdContrato = $("#txtIdContrato");
        let txtCodigoContrato = $("#txtCodigoContrato");
        let txtDescripcion = $("#txtDescripcion");
        let txtFechaProximoPago = $("#txtFechaProximoPago");

        // existe filtro
        let filtro = null;

        $(document).ready(function() {
            $("#filtro_contrato").hide();
            $('[data-mask]').inputmask('dd-mm-yyyy', {
                'placeholder': 'dd-mm-yyyy'
            });

            $('#frmPlazoPago').bootstrapValidator({
                message: 'Este valor es invalido',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    txtDescripcion: {
                        message: 'El Módulo no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Debes ingresar una descripción'
                            },

                        }
                    },
                    txtFechaProximoPago: {
                        message: 'El Módulo no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Fecha es requerida'
                            },

                        }
                    },
                }
            });

            getPlazoPagos("{{ route('plazo_pagos.index') }}");
            swTipoBusqueda();
            ddlTipoBusqueda.change(swTipoBusqueda);

            // paginacion
            contenedor_registros.on("click", "a.page-link", function(e) {
                e.preventDefault();
                let page = $(this).attr("href").split("=")[1];
                let url_paginacion = "{{ route('plazo_pagos.index') }}?page=" + page;
                if (filtro) {
                    getPlazoPagos(url_paginacion, filtro)
                } else {
                    getPlazoPagos(url_paginacion)
                }
            });
        });

        function swTipoBusqueda() {
            divMostrarBusquedaCodigo.hide();
            divMostrarBusquedaCI.hide();
            if (ddlTipoBusqueda.val() == 'CI') {
                divMostrarBusquedaCodigo.hide();
                divMostrarBusquedaCI.show();
            } else if (ddlTipoBusqueda.val() == 'CO') {
                divMostrarBusquedaCI.hide();
                divMostrarBusquedaCodigo.show();
            }
        }

        // abrirFormilarioPlazoPago
        function abrirFormilarioPlazoPago(accion, contrato_id, codigo, id = 0, descripcion = "", fecha_proximo_pago = "") {
            txtId.val(id);
            txtIdContrato.val(contrato_id);
            txtCodigoContrato.val(codigo);
            if (accion == 'nuevo') {
                $("#btnEnviarFormulario").text("Registrar");
                $("#titulo_modal").html('<i class="fa fa-plus"></i> REGISTRAR PLAZO DE PAGO');
            } else {
                $("#btnEnviarFormulario").text("Actualizar");
                txtDescripcion.val(descripcion);
                txtFechaProximoPago.val(fecha_proximo_pago);
                $("#titulo_modal").html('<i class="fa fa-edit"></i> MODIFICAR PLAZO DE PAGO');
            }

            $("#modalPlazoPago").modal("show");
        }

        // LISTADO DE PLAZO DE PAGOS
        function getPlazoPagos(url, contrato_id = 0) {
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    tipo: ddlTipoBusqueda.val(),
                    ci: $("#txtBuscarIdentifiacion").val(),
                    codigo: $("#txtBuscarCodigo").val(),
                    contrato_id: contrato_id,
                },
                dataType: "json",
                success: function(response) {
                    contenedor_registros.html(response.lista_html);
                }
            });
        }

        function verPlazoPagosContrato(contrato_id, codigo, cliente) {
            $("#filtro_contrato").show();
            $("#txt_fitro_codigo").text(codigo);
            $("#txt_fitro_cliente").text(cliente);
            filtro = contrato_id;
            getPlazoPagos("{{ route('plazo_pagos.index') }}", contrato_id);
        }

        $("#btnCancelaFiltro").click(function() {
            reiniciarFiltro();
        });

        function reiniciarFiltro() {
            $("#filtro_contrato").hide();
            $("#txt_fitro_codigo").text("");
            $("#txt_fitro_cliente").text("");
            filtro = null;
            getPlazoPagos("{{ route('plazo_pagos.index') }}");
        }


        /******************************** BUSCA PERSONAS *****************************************/
        function fnBuscarPersonas(tipoBusqueda) {
            $('.clsCodigo').hide();
            $('.clsContratos').hide();
            if (tipoBusqueda == 'CI') {
                var parametros = {
                    'txtBuscarIdentifiacion': $("#txtBuscarIdentifiacion").val(),
                };
                var route = "/BuscarClientes";
                $.ajax({
                    type: 'GET',
                    url: route,
                    data: parametros,
                    //async: false,
                    //contentType: "application/x-www-form-urlencoded;charset=UTF-8; charset=iso-8859-1;application/json;",
                    //dataType: 'json',
                    success: function(data) {
                        $('.clsClientes').show();
                        $('.clsClientes').html(data);
                    },
                    beforeSend: function() {
                        $('.loader').addClass('loader-default  is-active');

                    },
                    complete: function() {
                        $('.loader').removeClass('loader-default  is-active');
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "PERSONA...",
                            text: "Los busqueda no se puede cargar",
                            confirmButtonColor: "#EF5350",
                            confirmButtonText: 'Aceptar',
                            type: "error"
                        });
                    }
                });
            }

            if (tipoBusqueda == 'CO') {
                var parametros = {
                    'txtBuscarCodigo': $("#txtBuscarCodigo").val(),
                    'rdoTipoCodigo': 'N',
                    'plazo_pagos': true
                };
                var route = "/BuscarContratosCodigo";
                $.ajax({
                    type: 'GET',
                    url: route,
                    data: parametros,
                    //async: false,
                    //contentType: "application/x-www-form-urlencoded;charset=UTF-8; charset=iso-8859-1;application/json;",
                    //dataType: 'json',
                    success: function(data) {
                        $(".clsCodigo").show();
                        $('.clsCodigo').html(data);
                    },
                    beforeSend: function() {
                        $('.loader').addClass('loader-default  is-active');

                    },
                    complete: function() {
                        $('.loader').removeClass('loader-default  is-active');
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "PERSONA...",
                            text: "Los busqueda no se puede cargar",
                            confirmButtonColor: "#EF5350",
                            confirmButtonText: 'Aceptar',
                            type: "error"
                        });
                    }
                });
            }
        }

        /*/******************************** LISTADO DE CONTRATOS *****************************************/
        function fnBuscarContratos(id) {
            //var parametros = {'ddlPersona':$("#ddlPersona").val()};
            globalIdPersona = id;
            $('.clsClientes').hide();
            var parametros = {
                'idPersona': id,
                'plazo_pagos': true
            };
            var route = "/BuscarContratosPagos";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    if (data == "") {
                        $('.clsContratos').empty();
                        // $('.clsMensajeAlerta').html('<span class="text-semibold">No existe registro de vacunacion del paciente!</span>');
                        $('.clsMensajeAlerta').show();
                    } else {
                        $('.clsContratos').html(data);
                        $('.clsContratos').show();
                        $('.clsMensajeAlerta').empty();
                        $('.clsMensajeAlerta').hide();
                    }
                },
                beforeSend: function() {
                    $('.loader').addClass('loader-default  is-active');

                },
                complete: function() {
                    $('.loader').removeClass('loader-default  is-active');
                },
                error: function(error) {
                    swal({
                        title: "PERSONA VACUNADA...",
                        text: "Los busqueda no se puede cargar",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: 'Aceptar',
                        type: "error"
                    });
                }
            });

        }

        function fnVolver() {
            $('.clsClientes').show();
            $('.clsCodigo').hide();
            $('.clsContratos').hide();

        }

        function eliminarRegistro(id) {
            var route = "/plazo_pagos/" + id;
            Swal.fire({
                title: "¿Esta seguro de eliminar el Registro Nro. " + id + "?",
                text: "Presione Si para eliminar el registro de la base de datos!",
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
                            resultado = data.Mensaje;
                            if (resultado == 1) {
                                Swal.fire({
                                    title: "CORRECTO",
                                    text: "Se elimino correctamente!!",
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: "#66BB6A",
                                    type: "success"
                                });
                                fnLimpiarControles();
                            }
                            getPlazoPagos();

                            /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                            if (resultado == 0) {
                                swal({
                                    title: "Modulo...",
                                    text: "Hubo problemas al registrar en BD",
                                    confirmButtonColor: "#EF5350",
                                    confirmButtonText: 'Aceptar',
                                    type: "error"
                                });
                            }

                        },
                        error: function(result) {
                            Swal.fire("Opss..!", "Ocurrió un error al intentar eliminar el registro!",
                                "error")
                        }
                    });

                }
            })

        }

        /******************************** REGISTRA Modulo *****************************************/
        $("#btnEnviarFormulario").click(function() {
            var route = "/plazo_pagos";
            let data = {
                'contrato_id': txtIdContrato.val(),
                'descripcion': txtDescripcion.val(),
                'fecha_proximo_pago': txtFechaProximoPago.val(),
            };

            if (txtId.val() != '' && txtId.val() != 0 && txtId.val() != '0') {
                route = "/plazo_pagos/" + txtId.val();
                data["_method"] = "PUT";
            }

            var validarModulo = $("#frmPlazoPago").data('bootstrapValidator');
            validarModulo.validate();
            if (validarModulo.isValid()) {
                $.ajax({
                    url: route,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    success: function(data) {
                        resultado = data.Mensaje;
                        /*resultado = 1  Modulo GUARDADO*/
                        $("#modalPlazoPago").modal("hide");
                        if (resultado == 1) {
                            Swal.fire({
                                title: "CORRECTO",
                                text: "Se registro correctamente!!",
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: "#66BB6A",
                                type: "success"
                            });
                            fnLimpiarControles();
                        }
                        getPlazoPagos();
                        reiniciarFiltro();
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {
                            swal({
                                title: "Modulo...",
                                text: "Hubo problemas al registrar en BD",
                                confirmButtonColor: "#EF5350",
                                confirmButtonText: 'Aceptar',
                                type: "error"
                            });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {
                            swal({
                                title: "Modulo...",
                                text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
                                confirmButtonColor: "#EF5350",
                                confirmButtonText: 'Aceptar',
                                type: "error"
                            });
                        }
                    },
                    error: function(result) {
                        //swal("Opss..!", "Succedio un problema al registrar inserte bien los datos!", "error");
                    }
                });
            }
        });
        /******************************* LIMPIAR CONTROLES *******************************************************/
        function fnLimpiarControles() {
            txtId.val('');
            txtCodigoContrato.val('');
            txtDescripcion.val('');
            txtFechaProximoPago.val('');
            $("#frmPlazoPago").data('bootstrapValidator').resetForm();
            // fnRevalidarFormularioPlazoPago();
        }

        function fnRevalidarFormularioPlazoPago() {
            $('#frmPlazoPago').bootstrapValidator('revalidateField', 'txtDescripcion');
            $('#frmPlazoPago').bootstrapValidator('revalidateField', 'txtFechaProximoPago');
        }
    </script>
@endsection
