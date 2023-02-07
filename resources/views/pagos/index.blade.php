@extends('layout.principal')
@include('pagos.modals.modalDetalleContrato')
@include('pagos.modals.modalDetallePagos')
@include('pagos.modals.solicitud_retiro')
{{-- @include('pagos.modals.modalPagarContrato') --}}
@include('reporte.modalReporte')
@include('reporte.modalReporteAmortizacion')
@include('reporte.modalReporteAmortizacionInteres')
@include('reporte.modalReporteFactura')
@include('reporte.modalPagoCambio')
@include('contrato.modals.modal_img')
@section('main-content')
    <script>
        function fnLimpiarControlesSolicitud() {
            $('#_contrato_id').val('');
            $('#_sucursal_id').val('');
            $('#_observaciones').val('');
            $('#frmSolicitud').bootstrapValidator('resetForm', true);
        }

        function fnObtieneIdContrato(id) {
            $('#_contrato_id').val(id);
        }

    </script>

    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        PAGOS
                        <small>
                            <a href="#" data-toggle="modal" data-target="#modalListaMora" class="btn btn-primary">Lista
                                Moras</a>
                        </small>
                    </h3>
                </div>
            </section>
        </div>
    </div>

    @if ($datoValidarCaja)
        @if ($datoValidarCaja->estado_id == 1)
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
        @endif
        @if ($datoValidarCaja->estado_id == 2)
            @if(Session::get('ID_ROL') != 1)
            <div class="alert alert-danger">
                <button class="close" data-dismiss="alert">&times;</button>
                Nose pueden realizar mas pagos porque la caja <b>{{ session::get('CAJA') }}</b> de la sucursal
                <b>{{ session::get('ID_SUCURSAL') }}</b> ya cerro.
            </div>
            @else
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
            @endif
        @endif
    @else
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
    @endif

    <div class="row" style='display:none;' id="divMostrarBusquedaCI">
        <div class="col-md-2 form-group">
            <label>Numero Identificación: </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-list-numbered"></i></span>
                <input type="text" class="form-control" id="txtBuscarIdentifiacion" placeholder="Identificación">
            </div>
        </div>
        <div class="col-md-2 form-group">
            <label>Primer Apellido: </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" class="form-control" id="txtBuscarPaterno" placeholder="Primer Apellido">
            </div>
        </div>
        <div class="col-md-2 form-group">
            <label>Segundo Apellido: </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" class="form-control" id="txtBuscarMaterno" placeholder="Segundo Apellido">
            </div>
        </div>
        <div class="col-md-2 form-group">
            <label>Nombres: </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-users"></i></span>
                <input type="text" class="form-control" id="txtBuscarNombres" placeholder="Nombres">
            </div>
        </div>
        <div class="col-md-2 form-group">
            <label>Fecha Nacimiento: </label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" id="txtBuscarFechaNacimiento" name="txtBuscarFechaNacimiento" class="form-control"
                    data-mask>
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
            <label>Tipo de Contrato:</label>
            <div class="input-group">
                <input type="radio" id="rdoTipoCodigo" name="rdoTipoCodigo" value="A">
                ANTIGUO
                <input type="radio" id="rdoTipoCodigo" name="rdoTipoCodigo" value="N">
                NUEVO
            </div>
        </div>
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
                @include('pagos.modals.listadoClientes')
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section class="clsContratos">
                @include('pagos.modals.listadoContrato')
            </section>
        </div>
    </div>

    <div class="row" style='display:none;' id="divMostrarPagoTotal">
        <div class="col-md-12">
            <section class="clsDatoPagoTotal">
                @include('pagos.modals.datoPagoTotal')
            </section>
        </div>
    </div>

    <div class="row" style='display:none;' id="divMostrarPagoInteres">
        <div class="col-md-12">
            <section class="clsDatoPagoInteres">
                @include('pagos.modals.datoPagoInteres')
            </section>
        </div>
    </div>

    <div class="row" style='display:none;' id="divMostrarPagoAmortizacion">
        <div class="col-md-12">
            <section class="clsDatoPagoAmortizacion">
                @include('pagos.modals.datoPagoAmortizacion')
            </section>
        </div>
    </div>

    <div class="row" style='display:none;' id="divMostrarPagoAmortizacionInteres">
        <div class="col-md-12">
            <section class="clsDatoPagoAmortizacionInteres">
                @include('pagos.modals.datoPagoAmortizacionInteres')
                <div class="col-md-12">
                    <div id="mensaje_llenar_campos_ai" style="display:none; color:rgb(204, 54, 54);">Debe indicar un valor en las amortizaciones</div>
                </div>
            </section>
        </div>
    </div>

    <div class="row" style='display:none;' id="divMostrarRemate">
        <div class="col-md-12">
            <section class="clsDatoRemate">
                @include('pagos.modals.datoPagoRemate')
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section class="clsCodigo">
                @include('pagos.modals.listadoCodigos')
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-primary no-border clsMensajeAlerta">
                .
            </div>
        </div>
    </div>

    @include('pagos.modals.modalListaMora')
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        var globalIdPersona;
        var globalContratoJson;
        var globalInteresAmor = 3;
        var global_cambioMonedas = {
            valor_bs: 0,
            valor_sus: 0
        };
        var global_monedaContrato = 1;
        var global_idPago = 0;
        var sw_global_reporte = true;
        var existe_pago_total = false;
        $(document).ready(function() {
            $('#mensajePagoTotal').css('display','none');
            $('#mensajePagoInteres').css('display','none');
            $('#mensajePagoAmortizacion').css('display','none');
            fnListaMoras();
            $('#txtDiasRango').change(function() {
                fnListaMoras();
            });

            // ACCIONES CAMARA
			$(document).on('click','.ver_detalle a',function(){
				let url = $(this).attr('data-foto');
				$('#contenedorImagenDetalle').attr('src',url);
				$('#contenedorImagenDetalle').attr('src',url);
				$('#modal_img').modal('show');
			});

            /******************** VALIDAR FROMULARIO PARA LA ACTUALIZACION ******************/
            $('#frmSolicitud').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    _sucursal_id: {
                        message: 'Valor no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    _observaciones: {
                        message: 'Valor no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },

                }
            });

            $('#btnRegistrarSolicitud').click(function() {
                var validaSolicitud = $("#frmSolicitud").data('bootstrapValidator');
                validaSolicitud.validate();
                //$('#myCreate').html('<div><img src="../img/ajax-loader.gif"/></div>');
                if (validaSolicitud.isValid()) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "/solicitud_retiros/store",
                        data: {
                            contrato_id: $('#_contrato_id').val(),
                            sucursal_id: $('#_sucursal_id').val(),
                            observaciones: $('#_observaciones').val(),
                        },
                        dataType: "json",
                        success: function(response) {
                            if(existe_pago_total)
                            {
                                fnImprimirContratoPagoTotal(data.idPago);
                            }
                            else{
                                $("#modalSolicitud").modal('toggle');
                                fnPagarContratoTotal(1);
                                Swal.fire({
                                    title: "PAGOS",
                                    text: "Solicitud enviada correctamente",
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: "#66BB6A",
                                    type: "success"
                                });
                                fnVolverContratos();
                                fnLimpiarControlesSolicitud();
                            }
                        }
                    });
                }
            });

            function fnListaMoras() {
                $('#contListaMoras').html('Cargando...');
                $.ajax({
                    type: 'GET',
                    url: '/Pagos/lista/listadoMoras',
                    data: {
                        dias: $('#txtDiasRango').val()
                    },
                    //dataType: 'json',
                    success: function(data) {
                        $('#contListaMoras').html(data);
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "LISTA MORAS...",
                            text: "No se pudo cargar los registros",
                            confirmButtonColor: "#EF5350",
                            confirmButtonText: 'Aceptar',
                            type: "error"
                        });
                    }
                });
            }

            $('.clsDatoPagoTotal').hide();
            $('[data-mask]').inputmask('dd-mm-yyyy', {
                'placeholder': 'dd-mm-yyyy'
            });
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



            /******************** FUNCION PARA LA PAGINACIÓN AL HACER CLICK MEDIANTE AJAX ******************/
            $('body').on('click', '.pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                fnListadoPersonas(url);
                window.history.pushState("", "", url);
            });


            /*OBTENER FECHA ACTUAL */
            var fecha = new Date();
            var vDia;
            var vMes;
            if ((fecha.getMonth() + 1) < 10) {
                vMes = "0" + (fecha.getMonth() + 1);
            } else {
                vMes = (fecha.getMonth() + 1);
            }
            if (fecha.getDate() < 10) {
                vDia = "0" + fecha.getDate();
            } else {
                vDia = fecha.getDate();
            }
            //document.getElementById("txtFechaContrato").value = vDia +"-"+  vMes + "-" + fecha.getFullYear();         
        });

        /******************************** BUSCA PERSONAS *****************************************/
        function fnBuscarPersonas(tipoBusqueda) {
            //fnImprimirContrato(1706);
            $('.clsContratos').hide();
            document.getElementById('divMostrarPagoTotal').style.display = 'none';
            document.getElementById('divMostrarPagoInteres').style.display = 'none';
            document.getElementById('divMostrarPagoAmortizacion').style.display = 'none';
            document.getElementById('divMostrarPagoAmortizacionInteres').style.display = 'none';
            document.getElementById('divMostrarRemate').style.display = 'none';
            //console.log("valor",valor);
            //var parametros = {'txtBuscarPersona':$("#txtBuscarPersona").val()};
            if (tipoBusqueda == 'CI') {
                var parametros = {
                    'txtBuscarIdentifiacion': $("#txtBuscarIdentifiacion").val(),
                    'txtBuscarNombres': $("#txtBuscarNombres").val(),
                    'txtBuscarPaterno': $("#txtBuscarPaterno").val(),
                    'txtBuscarMaterno': $("#txtBuscarMaterno").val(),
                    'txtBuscarFechaNacimiento': $("#txtBuscarFechaNacimiento").val(),
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
                        console.log("data", data);
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
                console.log($('input[name=rdoTipoCodigo]:checked').val());
                console.log('codigo', $("#txtBuscarCodigo").val());
                if ($('input[name=rdoTipoCodigo]:checked').val() != null) {
                    if ($("#txtBuscarCodigo").val() != "") {
                        var parametros = {
                            'txtBuscarCodigo': $("#txtBuscarCodigo").val(),
                            'rdoTipoCodigo': $('input[name=rdoTipoCodigo]:checked').val()
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
                                console.log("data", data);
                                $('.clsCodigo').show();
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
            }
        }

        /*/******************************** LISTADO DE CONTRATOS *****************************************/
        function fnBuscarContratos(id) {
            //var parametros = {'ddlPersona':$("#ddlPersona").val()};
            globalIdPersona = id;
            $('.clsClientes').hide();
            var parametros = {
                'idPersona': id
            };
            var route = "/BuscarContratosPagos";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    if (data == "") {
                        console.log("datosss vacios");
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

        /*/******************************** LISTADO DETALE DE CONTRATOS *****************************************/
        function fnDetalleContratos(id) {
            var parametros = {
                'idContrato': id
            };
            var route = "/BuscarContratosDetalle";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    if (data == "") {
                        console.log("datosss vacios");
                        $('.clsContratos').empty();
                        $('.clsMensajeAlerta').html(
                            '<span class="text-semibold">No existe registro de vacunacion del paciente!</span>'
                        );
                        $('.clsMensajeAlerta').show();
                    } else {
                        $('.clsContratoDetalle').html(data);
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

        /*/******************************** LISTADO DETALE DE PAGOS *****************************************/
        function fnDetallePagos(id) {
            var parametros = {
                'idContrato': id
            };
            var route = "/BuscarPagosDetalle";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    if (data == "") {
                        console.log("datosss vacios");
                        $('.clsContratos').empty();
                        $('.clsMensajeAlerta').html('<span class="text-semibold">No existe registro !</span>');
                        $('.clsMensajeAlerta').show();
                    } else {
                        $('.clsPagoDetalle').html(data);
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

        /******************************** PAGAR PAGO TOTAL *****************************************/
        function registraSolicitud(contrato){
            Swal.fire({
                title: "Esta seguro de pagar el pago total?",
                html: '',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, Pagar!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.value) {
                    var contrato = globalContratoJson;
                    var idContrato = contrato.id;
                    $('#_contrato_id').val(idContrato);
                    $('#modalSolicitud').modal('show');
                }
            })
        }

        function fnPagarContratoTotal(contrato) {
            existe_pago_total = true;
            console.log("contrato", contrato);
            var contrato = globalContratoJson;
            var idContrato = contrato.id;
            $('#_contrato_id').val(idContrato);
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    var fecha = new Date();
                    var vDia;
                    var vMes;
                    if ((fecha.getMonth() + 1) < 10) {
                        vMes = "0" + (fecha.getMonth() + 1);
                    } else {
                        vMes = (fecha.getMonth() + 1);
                    }
                    if (fecha.getDate() < 10) {
                        vDia = "0" + fecha.getDate();
                    } else {
                        vDia = fecha.getDate();
                    }
                    if (fecha.getHours() < 10) {
                        hora = "0" + fecha.getHours();
                    } else {
                        hora = fecha.getHours();
                    }
                    if (fecha.getMinutes() < 10) {
                        minutos = "0" + fecha.getMinutes();
                    } else {
                        minutos = fecha.getMinutes();
                    }
                    //var fechaActual = moment(fecha.getFullYear() +"-" +vMes +"-"+ vDia);
                    var fechaActual = moment($("#txtFechaActualT").val());
                    if (data == "") {
                        console.log("datosss vacios");
                        var fechaFin = moment(contrato.fecha_fin).format('YYYY-MM-DD');
                        var interes = contrato.interes;
                        var comision = contrato.comision;
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        var capital = contrato.capital;
                        var fechaContrato = contrato.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }
                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }

                    } else {
                        console.log("con detalle", data.Resultado);
                        var fechaFin = moment(data.Resultado.fecha_fin).format('YYYY-MM-DD');
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        // var capital = data.Resultado.capital;
                        var fechaContrato = data.Resultado.fecha_inio;
                        // var capital = data.Resultado.capital;
                        var capital = contrato.capital;
                        //var fechaContrato = data.Resultado.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }
                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }
                    }

                    var fechaInteresInicial = '2020-03-22';
                    var fechaInteresFin = '2020-05-11';
                    var fechaInteresFinM = moment('2020-05-11');
                    console.log(fechaInteresFinM.diff(fechaInteresInicial, 'days'),
                        ' días de diferencia para descuneto interes');
                    console.log("fecha Inicial Interes", fechaInteresInicial);
                    console.log("fecha Final Interes", fechaInteresFin);
                    console.log("fecha fin", fechaFin);
                    console.log("fecha actual", fechaActual);
                    console.log(fechaActual.diff(fechaFin, 'days'), ' días de diferencia');
                    console.log("dias del mes actual", moment(fechaActual, "YYYY-MM").daysInMonth());
                    console.log("total Interes", totalInteresValor);
                    //var diaActual = moment(fechaActual, "YYYY-MM").daysInMonth();
                    var diaActual = 30;
                    var diaAtrasados = fechaActual.diff(fechaFin, 'days');
                    console.log("diaAtrasados", diaAtrasados);
                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");
                        // var diasDecuento = diaAtrasados;
                        var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    } else {
                        console.log("intervalo NO ACEPATDO");
                        if (fechaFin > fechaInteresFin) {
                            console.log("FECHA MAYOR");
                            var diasDecuento = 0;
                        } else {
                            console.log("FECHA MENOR");
                            var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        }
                        //var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    }
                    if (diaAtrasados > 0) {
                        // var diaAtrasados1 = diaAtrasados - diasDecuento;// var diaAtrasados1 = diaAtrasados;
                        var calc = diaAtrasados - diasDecuento;
                        if (calc > 0) {
                            var diaAtrasados1 = diaAtrasados - diasDecuento;
                        } else {
                            var diaAtrasados1 = 0;
                        }
                        
                        console.log("diaAtrasados1 valoo", diaAtrasados1);
                        console.log("totalInteresValor valoo", totalInteresValor);
                        console.log("diaActual valoo", diaActual);
                        var totalInteres = (parseFloat(totalInteresValor) / diaActual) * diaAtrasados1;
                        var cuotaMora = totalInteres;
                        console.log("Interes Calculado", totalInteres);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(capital) + parseFloat(
                            totalInteresValor);
                        console.log("Total General", totalGeneral);
                        var mensaje = 'El contrato tiene <b>' + diaAtrasados + ' dias </b> de atraso,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) + ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'Por lo tanto el Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(
                                2) + ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El Capital mas el interes seria un total de: <b>' + parseFloat(totalGeneral)
                            .toFixed(2) + ' ' + data.Resultado.desc_corta + '</b>';
                        var valorDiasAtrasados = diaAtrasados;
                    } else {
                        var fechaFin = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaActual = moment();
                        var diasRango = fechaActual.diff(fechaFin, 'days');
                        var valorDiasAtrasados = diaAtrasados;
                        console.log("diasRango", diasRango);
                        var cuotaMora = 0;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 6.04) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3.7) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }else{
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 2) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }

                        var totalInteres = parseFloat(totalInteresValor1);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(capital);
                        var mensaje = 'El contrato tiene <b>' + diasRango + ' dias </b> habiles,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El Capital mas el interes seria un total de: <b>' + parseFloat(totalGeneral)
                            .toFixed(2) + ' ' + data.Resultado.desc_corta + '</b>';
                    }

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");

                        var fechaFin3 = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaInteresInicial33 = moment('2020-03-22', 'YYYY/MM/DD');

                        var solodiaC = fechaInteresInicial33.diff(fechaFin3, 'days');
                        var solodiaC1 = fechaActual.diff(fechaInteresFinM, 'days');
                        console.log("solodiaC", solodiaC);
                        console.log("solodiaC11111", solodiaC1);

                        var diasMora = solodiaC + solodiaC1;

                        var interesDescuento = (parseFloat(interes) / 30) * diasMora;
                        var comisionDescuento = (parseFloat(comision) / 30) * diasMora;
                        //var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento);
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento) +
                            parseFloat(cutotaMoraDescuento);

                        totalGeneral = totalDescuento;

                    } else {
                        console.log("intervalo NO ACEPATDO");
                        var interesDescuento = 0;
                        var comisionDescuento = 0;
                        var totalDescuento = 0;
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        totalGeneral = totalGeneral + cutotaMoraDescuento;
                        console.log("ffff", cutotaMoraDescuento);
                    }

                    if (interesDescuento > 0) {
                        interes = interesDescuento;
                        comision = comisionDescuento;
                        cuotaMora = cutotaMoraDescuento;
                    } else {
                        cuotaMora = cuotaMora + cutotaMoraDescuento;
                    }

                    console.log("interes", interes);
                    console.log("comision", comision);
                    console.log("capital", capital);
                    console.log("cuotaMora", cuotaMora);

                    var route = "/PagoContratoTotal";
                    //console.log("comision",comision);
                    // Swal.fire({
                    //     title: "Esta seguro de pagar el pago total?",
                    //     html: '',
                    //     type: "warning",
                    //     showCancelButton: true,
                    //     confirmButtonColor: "#3085d6",
                    //     cancelButtonColor: '#d33',
                    //     confirmButtonText: "Si, Pagar!",
                    //     closeOnConfirm: false
                    // }).then((result) => {
                        // if (result.value) {
                            $.ajax({
                                url: route,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    'diasAtraso': valorDiasAtrasados,
                                    //'fecha_pago':moment().format("YYYY-MM-DD"),
                                    'fecha_pago': $("#txtFechaActualT").val(),
                                    'fecha_fin': contrato.fecha_fin,
                                    'fecha_Incio': contrato.fecha_contrato,
                                    'total_capital': contrato.total_capital,
                                    'capital': parseFloat(capital).toFixed(2),
                                    'interes': parseFloat(interes).toFixed(2),
                                    'comision': parseFloat(comision).toFixed(2),
                                    'cuotaMora': parseFloat(cuotaMora).toFixed(2),
                                    //'comision':0,
                                    'idContrato': contrato.id,
                                },
                                success: function(data) {
                                    console.log(data.Mensaje);
                                    resultado = data.Mensaje;

                                    /*resultado = 1  ROL ELIMINADO*/
                                    if (resultado == 1) {
                                        Swal.fire({
                                            title: "PAGO!",
                                            text: "Se pago correctamente!!",
                                            confirmButtonText: 'Aceptar',
                                            confirmButtonColor: "#66BB6A",
                                            type: "success"
                                        });
                                        global_idPago = data.idPago;
                                        fnBuscarContratos(globalIdPersona);

                                        fnImprimirContratoPagoTotal(data.idPago);
                                        // imprimirFacturaPagoTotal(data.idPago);
                                        fnVolverContratos();
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
                                    Swal.fire("Opss..!",
                                        "El La persona tiene registros en otras tablas!",
                                        "error")
                                }
                            });
                        // }
                    // })
                },
                beforeSend: function() {
                    $('.loader').addClass('loader-default  is-active');

                },
                complete: function() {
                    $('.loader').removeClass('loader-default  is-active');
                },
                error: function(error) {
                    swal({
                        title: "ERROR PAGO...",
                        text: "Los busqueda no se puede cargar",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: 'Aceptar',
                        type: "error"
                    });
                }
            });
        }

        /******************************** PAGAR PAGO INTERES *****************************************/
        function fnPagarContratoInteres(contrato) {
            existe_pago_total = false;
            console.log("contrato", contrato);
            contrato = globalContratoJson;
            var idContrato = contrato.id;
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    var fecha = new Date();
                    var vDia;
                    var vMes;
                    if ((fecha.getMonth() + 1) < 10) {
                        vMes = "0" + (fecha.getMonth() + 1);
                    } else {
                        vMes = (fecha.getMonth() + 1);
                    }
                    if (fecha.getDate() < 10) {
                        vDia = "0" + fecha.getDate();
                    } else {
                        vDia = fecha.getDate();
                    }
                    if (fecha.getHours() < 10) {
                        hora = "0" + fecha.getHours();
                    } else {
                        hora = fecha.getHours();
                    }
                    if (fecha.getMinutes() < 10) {
                        minutos = "0" + fecha.getMinutes();
                    } else {
                        minutos = fecha.getMinutes();
                    }
                    //var fechaActual = moment(fecha.getFullYear() +"-" +vMes +"-"+ vDia);
                    var fechaActual = moment($("#txtFechaActualI").val());
                    if (data == "") {
                        console.log("datosss vacios");
                        var fechaFin = moment(contrato.fecha_fin).format('YYYY-MM-DD');
                        var interes = contrato.interes;
                        var comision = contrato.comision;
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        var capital = contrato.capital;
                        var fechaContrato = contrato.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }
                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }

                    } else {
                        console.log("con detalle", data.Resultado);
                        var fechaFin = moment(data.Resultado.fecha_fin).format('YYYY-MM-DD');
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        // var capital = data.Resultado.capital;
                        var fechaContrato = data.Resultado.fecha_inio;
                        // var capital = data.Resultado.capital;
                        var capital = contrato.capital;
                        //var fechaContrato = data.Resultado.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }
                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }

                    }

                    var fechaInteresInicial = '2020-03-22';
                    var fechaInteresFin = '2020-05-11';
                    var fechaInteresFinM = moment('2020-05-11');
                    console.log(fechaInteresFinM.diff(fechaInteresInicial, 'days'),
                        ' días de diferencia para descuneto interes');
                    console.log("fecha Inicial Interes", fechaInteresInicial);
                    console.log("fecha Final Interes", fechaInteresFin);
                    console.log("fecha fin", fechaFin);
                    console.log("fecha actual", fechaActual);
                    console.log(fechaActual.diff(fechaFin, 'days'), ' días de diferencia');
                    console.log("dias del mes actual", moment(fechaActual, "YYYY-MM").daysInMonth());
                    console.log("total Interes", totalInteresValor);
                    //var diaActual = moment(fechaActual, "YYYY-MM").daysInMonth();
                    var diaActual = 30;

                    var diaAtrasados = fechaActual.diff(fechaFin, 'days');
                    console.log("diaAtrasados", diaAtrasados);
                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");
                        // var diasDecuento = diaAtrasados
                        var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    } else {
                        console.log("intervalo NO ACEPATDO");
                        if (fechaFin > fechaInteresFin) {
                            console.log("FECHA MAYOR");
                            var diasDecuento = 0;
                        } else {
                            console.log("FECHA MENOR");
                            var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        }
                        //var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    }
                    if (diaAtrasados > 0) {
                        var calc = diaAtrasados - diasDecuento;
                        if (calc > 0) {
                            var diaAtrasados1 = diaAtrasados - diasDecuento;
                        } else {
                            var diaAtrasados1 = 0;
                        }
                        var diaAtrasados1 = diaAtrasados - diasDecuento;
                        console.log("diaAtrasados1 valoo", diaAtrasados1);
                        console.log("totalInteresValor valoo", totalInteresValor);
                        console.log("diaActual valoo", diaActual);
                        var totalInteres = (parseFloat(totalInteresValor) / diaActual) * diaAtrasados1;
                        var cuotaMora = totalInteres;
                        console.log("Interes Calculado", totalInteres);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(totalInteresValor);
                        console.log("Total General", totalGeneral);
                        var mensaje = 'El contrato tiene <b>' + diaAtrasados + ' dias </b> de atraso,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de::<b>' + parseFloat(totalInteres).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El total del interes a pagar es de:<b>' + parseFloat(totalGeneral).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b>';
                        var valorDiasAtrasados = diaAtrasados;
                    } else {
                        var fechaFin = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaActual = moment();
                        var diasRango = fechaActual.diff(fechaFin, 'days');
                        var valorDiasAtrasados = diaAtrasados;
                        console.log("diasRango", diasRango);
                        var cuotaMora = 0;
                        // if (diasRango <= 15) {                   
                        //  var totalInteres = parseFloat(totalInteresValor) / 2;   
                        //  var totalGeneral = parseFloat(totalInteres);    
                        //  if (parseFloat(capital)<= 3499) {                       
                        //      var interes = ((capital/2) * 3)/100;
                        //      var comision = ((capital/2) * 7.4)/100;                     
                        //     }
                        //     else{                        
                        //          var interes = ((capital/2) * 3)/100;
                        //      var comision = ((capital/2) * 4.4)/100;                     
                        //     }                        
                        //  var mensaje = 'El contrato tiene <b>'+diasRango+' dias </b> habiles y es menor a 15 días,<br> ' +
                        //  'Tiene un capital de :<b>'+parseFloat(capital).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> con un interes de <b>'+parseFloat(totalInteresValor).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> <br>'+
                        //  'El Interes seria un total de: <b>'+totalInteres+' '+ data.Resultado.desc_corta+'</b><br>'+
                        //  'El total del interes a pagar es de:<b>'+totalGeneral+' '+ data.Resultado.desc_corta+'</b>'; 
                        // }
                        // else{

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 6.04) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3.7) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }else{
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 2) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }

                        var totalInteres = parseFloat(totalInteresValor1);
                        var totalGeneral = parseFloat(totalInteres);
                        var mensaje = 'El contrato tiene <b>' + diasRango + ' dias </b> habiles,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El total del interes a pagar es de:<b>' + parseFloat(totalGeneral).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b>';
                        // }
                    }

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");

                        var fechaFin3 = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaInteresInicial33 = moment('2020-03-22', 'YYYY/MM/DD');

                        var solodiaC = fechaInteresInicial33.diff(fechaFin3, 'days');
                        var solodiaC1 = fechaActual.diff(fechaInteresFinM, 'days');
                        console.log("solodiaC", solodiaC);
                        console.log("solodiaC11111", solodiaC1);

                        var diasMora = solodiaC + solodiaC1;

                        var interesDescuento = (parseFloat(interes) / 30) * diasMora;
                        var comisionDescuento = (parseFloat(comision) / 30) * diasMora;
                        //var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento);
                        console.log("diasDecuento", diasDecuento);
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        console.log("cccc", cutotaMoraDescuento);
                        var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento) +
                            parseFloat(cutotaMoraDescuento);

                        totalGeneral = totalDescuento;

                    } else {
                        console.log("intervalo NO ACEPATDO");
                        var interesDescuento = 0;
                        var comisionDescuento = 0;
                        var totalDescuento = 0;
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        totalGeneral = totalGeneral + cutotaMoraDescuento;
                        console.log("ffff", cutotaMoraDescuento);
                    }

                    if (interesDescuento > 0) {
                        interes = interesDescuento;
                        comision = comisionDescuento;
                        cuotaMora = cutotaMoraDescuento;
                    } else {
                        cuotaMora = cuotaMora + cutotaMoraDescuento;
                    }


                    console.log("interes", interes);
                    console.log("comision", comision);
                    console.log("capital", capital);
                    console.log("cuotaMora", cuotaMora);
                    var route = "/PagoContratoInteres";
                    //console.log("comision",comision);
                    Swal.fire({
                        title: "Esta seguro de pagar el Interes?",
                        html: '',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: '#d33',
                        confirmButtonText: "Si, Pagar!",
                        closeOnConfirm: false
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: route,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    'diasAtraso': valorDiasAtrasados,
                                    //'fecha_pago':moment().format("YYYY-MM-DD"),
                                    'fecha_pago': $("#txtFechaActualI").val(),
                                    'fecha_fin': contrato.fecha_fin,
                                    'fecha_Incio': contrato.fecha_contrato,
                                    'total_capital': contrato.total_capital,
                                    'capital': parseFloat(capital).toFixed(2),
                                    'interes': parseFloat(interes).toFixed(2),
                                    'comision': parseFloat(comision).toFixed(2),
                                    'cuotaMora': parseFloat(cuotaMora).toFixed(2),
                                    'idContrato': contrato.id,
                                },
                                success: function(data) {
                                    console.log(data.Mensaje);
                                    resultado = data.Mensaje;

                                    /*resultado = 1  ROL ELIMINADO*/
                                    if (resultado == 1) {
                                        Swal.fire({
                                            title: "INTERES!",
                                            text: "Se pago el Interes correctamente!!",
                                            confirmButtonText: 'Aceptar',
                                            confirmButtonColor: "#66BB6A",
                                            type: "success"
                                        });
                                        global_idPago = data.idPago;
                                        fnBuscarContratos(globalIdPersona);
                                        fnImprimirContratoInteres(data.idPago);
                                        fnVolverContratos();
                                    }

                                    /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                                    if (resultado == 0) {
                                        Swal.fire({
                                            title: "INTERES...",
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
                                    Swal.fire("Opss..!",
                                        "El La persona tiene registros en otras tablas!",
                                        "error")
                                }
                            });
                        }
                    })
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

        /******************************** PAGAR PAGO CUOTA - AMORTIZACIÓN **********************************/
        function fnPagarContratoCuota(contrato) {
            var idContrato = contrato.id;
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    var fechaActual = moment();
                    if (data == "") {
                        console.log("datosss vacios");
                    } else {
                        $("#txtIdContrato").val(data.Resultado.contrato_id);
                        $("#txtCapital").val(data.Resultado.capital);
                        $("#txtInteres").val(data.Resultado.interes);
                        $("#txtComisión").val(data.Resultado.comision);
                        globalContratoJson = data.Resultado;
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



        /*************************** CALCULA LA AMORTIZACIÓN ************************************/
        function fnCalcularAmortizacion(valor) {
            console.log("valor", valor);
            valorMaximo = $("#txtCapitalPagoTotalA").val();
            //var interes = $("#txtInteres").val();
            //var comision = $("#txtComisión").val();
            console.log("valorMaximo", valorMaximo);
            if (parseFloat(valor) <= parseFloat(valorMaximo)) {
                var valorRestatnte = parseFloat(valorMaximo) - parseFloat(valor);

                var valor_comparacion1 = 3499;
                var valor_comparacion2 = 10000;
                var valor_comparacion3 = 15000;
                if (global_monedaContrato == 2) {
                    valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                    valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                    valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                }

                if (parseFloat(valor) <= valor_comparacion1) {
                    var valorGarantia = (valorRestatnte * 9.04) / 100;
                    //$('#txtNuevoInteres').val(Number(valorGarantia).toFixed(2));
                    var interes = (valorRestatnte * globalInteresAmor) / 100;
                    var comision = (valorRestatnte * 7.4) / 100;
                } else if (parseFloat(valor) < valor_comparacion2) {
                    var valorGarantia = (valorRestatnte * 6.7) / 100;
                    var interes = (valorRestatnte * globalInteresAmor) / 100;
                    var comision = (valorRestatnte * 4.4) / 100;
                    //$('#txtNuevoInteres').val(Number(valorGarantia).toFixed(2));

                } else if (parseFloat(valor) < valor_comparacion3) {
                    var valorGarantia = (valorRestatnte * 6) / 100;
                    var interes = (valorRestatnte * globalInteresAmor) / 100;
                    var comision = (valorRestatnte * 4.4) / 100;
                }else{
                    var valorGarantia = (valorRestatnte * 5) / 100;
                    var interes = (valorRestatnte * globalInteresAmor) / 100;
                    var comision = (valorRestatnte * 4.4) / 100;
                }

                var txtInteresFechaAD = parseFloat($("#txtInteresFechaAD").val());
                if (txtInteresFechaAD > 0) {
                    console.log("AAAAAA");
                    var totalPagar = parseFloat(valor) + parseFloat($("#txtInteresFechaAD").val()) + parseFloat($(
                        "#txtGastosAdministrativosAD").val()) + parseFloat($("#txtInteresMoratorioAD").val());
                } else {
                    console.log("BBBBB");
                    var totalPagar = parseFloat(valor) + parseFloat($("#txtInteresFechaA").val()) + parseFloat($(
                            "#txtGastosAdministrativosA").val()) + parseFloat($("#txtInteresMoratorioA").val()) +
                        parseFloat($("#txtInteresMoratorioAD").val());

                }

                if (global_monedaContrato == 1) {
                    $('#txtTotalA').val(Number(totalPagar).toFixed(2));
                    $('#txtTotalA2').val(parseFloat((totalPagar / global_cambioMonedas.valor_bs)).toFixed(2))
                } else {
                    $('#txtTotalA').val(parseFloat((totalPagar * global_cambioMonedas.valor_bs)).toFixed(2))
                    $('#txtTotalA2').val(Number(totalPagar).toFixed(2));
                }
            } else {
                $("#txtAmortizacion").val('');
                //$('#frmContrato').bootstrapValidator('revalidateField', 'txtCreditoPrestar');
                Swal.fire({
                    title: "Contrato...",
                    //html: 'El Contrato  <b>'+  data.Mensaje +'</b> y se registro correctamente los datos',//text: "La sesión 
                    html: 'Su amortizacion no puede mayor a <b>' + valorMaximo +
                        '</b>', //text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
                    confirmButtonColor: "#EF5350",
                    confirmButtonText: 'Aceptar',
                    type: "error"
                });
            }
        }

        function fnCalcularAmortizacionInteres(valor) {
            let interes_capital = $('#txtInteresFechaAI');
            let interes_capital2 = $('#txtInteresFechaAI2');
            let gastos_administrativos = $('#txtGastosAdministrativosAI');
            let gastos_administrativos2 = $('#txtGastosAdministrativosAI2');
            let interes_moratorios = $('#txtInteresMoratorioAI');
            let interes_moratorios2 = $('#txtInteresMoratorioAI2');
            if(valor != ''){
                valor = parseFloat(valor);
                valorMaximo = Math.round((parseFloat(interes_capital.attr('data-val')) + parseFloat(gastos_administrativos.attr('data-val')) + parseFloat(interes_moratorios.attr('data-val'))) * 100) / 100;
                if (parseFloat(valor) <= parseFloat(valorMaximo)) {
                    let resta = parseFloat(interes_capital.attr('data-val')) - valor;
                    if(resta < 0){
                        interes_capital.val('0.00');
                        interes_capital2.val('0.00');
                        resta = parseFloat(gastos_administrativos.attr('data-val')) - (resta * -1);
                        if(resta < 0){
                            gastos_administrativos.val('0.00');
                            gastos_administrativos2.val('0.00');
                            resta = parseFloat(interes_moratorios.attr('data-val')) - (resta * -1);
                            if(resta < 0){
                                interes_moratorios.val('0.00');
                                interes_moratorios2.val('0.00');
                            }else{
                                interes_moratorios.val(resta.toFixed(2));
                                interes_moratorios2.val((parseFloat(resta / global_cambioMonedas.valor_bs)).toFixed(2));
                            }
                        }else{
                            gastos_administrativos.val(resta.toFixed(2));
                            gastos_administrativos2.val((parseFloat(resta / global_cambioMonedas.valor_bs)).toFixed(2));
                        }
                    }else{
                        interes_capital.val(resta.toFixed(2));
                        interes_capital2.val((parseFloat(resta / global_cambioMonedas.valor_bs)).toFixed(2));
                    }
                    $('#txtTotalAI').val(parseFloat(valor).toFixed(2));
                    $('#txtTotalAI2').val((parseFloat(valor / global_cambioMonedas.valor_bs)).toFixed(2));
                } else {
                    $("#txtAmortizacionInteres").val('');
                    interes_capital.val(interes_capital.attr('data-val'))
                    gastos_administrativos.val(gastos_administrativos.attr('data-val'))
                    interes_moratorios.val(interes_moratorios.attr('data-val'))
                    interes_capital2.val(interes_capital2.attr('data-val'))
                    gastos_administrativos2.val(gastos_administrativos2.attr('data-val'))
                    interes_moratorios2.val(interes_moratorios2.attr('data-val'))
                    Swal.fire({
                        title: "Contrato...",
                        html: 'Su amortizacion de interes no puede mayor a <b>' + valorMaximo.toFixed(2) +
                            '</b>',
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: 'Aceptar',
                        type: "error"
                    });
                }
            }else{
                interes_capital.val(interes_capital.attr('data-val'))
                gastos_administrativos.val(gastos_administrativos.attr('data-val'))
                interes_moratorios.val(interes_moratorios.attr('data-val'))
                interes_capital2.val(interes_capital2.attr('data-val'))
                gastos_administrativos2.val(gastos_administrativos2.attr('data-val'))
                interes_moratorios2.val(interes_moratorios2.attr('data-val'))
            }
        }

        function fnCalcularAmortizacionGastos(valor) {
            valor = parseFloat(valor);
            valorMaximo = $("#txtGastosAdministrativosAI").val();
            if (parseFloat(valor) <= parseFloat(valorMaximo)) {
                let valorAI = $('#txtAmortizacionInteres').val();
                // let valorIM = $('#txtAmortizacionIMIA').val();
                if(valorAI == '')
                {
                    valorAI = 0;
                }
                // if(valorIM == '')
                // {
                //     valorIM = 0;
                // }
                valor +=  parseFloat(valorAI);
                // valor +=  parseFloat(valorIM);
                if (global_monedaContrato == 1) {
                    $('#txtTotalAI').val(Number(valor).toFixed(2));
                    $('#txtTotalAI2').val(parseFloat((valor / global_cambioMonedas.valor_bs)).toFixed(2))
                } else {
                    $('#txtTotalAI').val(parseFloat((valor * global_cambioMonedas.valor_bs)).toFixed(2))
                    $('#txtTotalAI2').val(Number(valor).toFixed(2));
                }
            } else {
                $("#txtAmortizacionInteres").val('');
                Swal.fire({
                    title: "Contrato...",
                    html: 'Su amortizacion de gastos adminsitrativos no puede mayor a <b>' + valorMaximo +
                        '</b>',
                    confirmButtonColor: "#EF5350",
                    confirmButtonText: 'Aceptar',
                    type: "error"
                });
            }
        }

        function fnCalcularAmortizacionMoratorios(valor){
            valor = parseFloat(valor);
            valorMaximo = $("#txtInteresMoratorioAI").val();
            if (parseFloat(valor) <= parseFloat(valorMaximo)) {
                let valorAI = $('#txtAmortizacionInteres').val();
                let valorGA = $('#txtAmortizacionGAIA').val();
                if(valorAI == '')
                {
                    valorAI = 0;
                }
                if(valorGA == '')
                {
                    valorGA = 0;
                }
                valor +=  parseFloat(valorAI);
                valor +=  parseFloat(valorGA);
                if (global_monedaContrato == 1) {
                    $('#txtTotalAI').val(Number(valor).toFixed(2));
                    $('#txtTotalAI2').val(parseFloat((valor / global_cambioMonedas.valor_bs)).toFixed(2))
                } else {
                    $('#txtTotalAI').val(parseFloat((valor * global_cambioMonedas.valor_bs)).toFixed(2))
                    $('#txtTotalAI2').val(Number(valor).toFixed(2));
                }
            } else {
                $("#txtAmortizacionInteres").val('');
                Swal.fire({
                    title: "Contrato...",
                    html: 'Su amortizacion de gastos adminsitrativos no puede mayor a <b>' + valorMaximo +
                        '</b>',
                    confirmButtonColor: "#EF5350",
                    confirmButtonText: 'Aceptar',
                    type: "error"
                });
            }
        }

        /******************************* PAGAR PAGO  OPCION*****************************************/
        function fnPagarContratoAmortizacion() {
            existe_pago_total = false;
            //var idContrato = $("#txtIdContrato").val();
            var capital = $("#txtCapitalPagoTotalA").val();
            var interes = $("#txtInteres").val();
            var amortizacion = $("#txtAmortizacion").val();
            var nuevoCapital = parseFloat(capital) - parseFloat(amortizacion);
            var contrato = globalContratoJson;
            console.log("contrato", contrato);
            var idContrato = contrato.id;
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    var fecha = new Date();
                    var vDia;
                    var vMes;
                    if ((fecha.getMonth() + 1) < 10) {
                        vMes = "0" + (fecha.getMonth() + 1);
                    } else {
                        vMes = (fecha.getMonth() + 1);
                    }
                    if (fecha.getDate() < 10) {
                        vDia = "0" + fecha.getDate();
                    } else {
                        vDia = fecha.getDate();
                    }
                    if (fecha.getHours() < 10) {
                        hora = "0" + fecha.getHours();
                    } else {
                        hora = fecha.getHours();
                    }
                    if (fecha.getMinutes() < 10) {
                        minutos = "0" + fecha.getMinutes();
                    } else {
                        minutos = fecha.getMinutes();
                    }
                    var fechaActual = moment($("#txtFechaActualA").val());
                    if (data == "") {
                        console.log("datosss vacios");
                        var fechaFin = moment(contrato.fecha_fin).format('YYYY-MM-DD');
                        var interes = contrato.interes;
                        var comision = contrato.comision;
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        var capital = contrato.capital;
                        var fechaContrato = contrato.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(valor) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(valor) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }

                    } else {
                        console.log("con detalle", data.Resultado);
                        var fechaFin = moment(data.Resultado.fecha_fin).format('YYYY-MM-DD');
                        // var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        // var capital = data.Resultado.capital;
                        var fechaContrato = data.Resultado.fecha_inio;
                        // var capital = data.Resultado.capital;
                        var capital = contrato.capital;
                        // var fechaContrato = data.Resultado.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(valor) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(valor) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }
                    }

                    // var fechaFin = moment(contrato.fecha_fin).format('YYYY-MM-DD');
                    // var interes = contrato.interes;  
                    // var comision = contrato.comision;
                    // var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                    // var capital = contrato.capital;
                    // var fechaContrato = contrato.fecha_inio;

                    var valor_comparacion1 = 3499;
                    var valor_comparacion2 = 10000;
                    var valor_comparacion3 = 15000;
                    if (data.Resultado.moneda_id == 2) {
                        valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                        valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                        valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                    }

                    if (parseFloat(capital) <= valor_comparacion1) {
                        var totalInteresValor = (capital * 9.04) / 100;
                    } else if (parseFloat(capital) < valor_comparacion2) {
                        var totalInteresValor = (capital * 6.7) / 100;
                        //var interes = (capital * data.Resultado.p_interes)/100;
                        //var comision = (capital * 3.7)/100;
                    } else if (parseFloat(capital) < valor_comparacion3) {
                        var totalInteresValor = (capital * 6) / 100;
                    }else{
                        var totalInteresValor = (capital * 5) / 100;
                    }

                    var fechaInteresInicial = '2020-03-22';
                    var fechaInteresFin = '2020-05-11';
                    var fechaInteresFinM = moment('2020-05-11');
                    console.log(fechaInteresFinM.diff(fechaInteresInicial, 'days'),
                        ' días de diferencia para descuneto interes');
                    console.log("fecha Inicial Interes", fechaInteresInicial);
                    console.log("fecha Final Interes", fechaInteresFin);
                    console.log("fecha fin", fechaFin);
                    console.log("fecha actual", fechaActual);
                    console.log(fechaActual.diff(fechaFin, 'days'), ' días de diferencia');
                    console.log("dias del mes actual", moment(fechaActual, "YYYY-MM").daysInMonth());
                    console.log("total Interes", totalInteresValor);
                    //var diaActual = moment(fechaActual, "YYYY-MM").daysInMonth();
                    var diaActual = 30;
                    var diaAtrasados = fechaActual.diff(fechaFin, 'days');
                    console.log("diaAtrasados123", diaAtrasados);
                    console.log("interesAAAAA", interes);
                    console.log("comisionAAAA", comision);

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");
                        // var diasDecuento = diaAtrasados;
                        var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    } else {
                        console.log("intervalo NO ACEPATDO");
                        if (fechaFin > fechaInteresFin) {
                            console.log("FECHA MAYOR");
                            var diasDecuento = 0;
                        } else {
                            console.log("FECHA MENOR");
                            var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        }
                        //var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    }

                    if (diaAtrasados > 0) {
                        console.log("aleeeee");
                        // var diaAtrasados1 = diaAtrasados - diasDecuento;
                        var calc = diaAtrasados - diasDecuento;
                        console.log("calc", calc);
                        if (calc > 0) {
                            var diaAtrasados1 = diaAtrasados - diasDecuento;
                        } else {
                            var diaAtrasados1 = 0;
                        }
                        console.log("diaAtrasados1 valoo", diaAtrasados1);
                        console.log("totalInteresValor valoo", totalInteresValor);
                        console.log("diaActual valoo", diaActual);
                        console.log("diaAtrasados1 valoo", diaAtrasados1);
                        console.log("totalInteresValor valoo", totalInteresValor);
                        console.log("diaActual valoo", diaActual);
                        var totalInteres = (parseFloat(totalInteresValor) / diaActual) * diaAtrasados1;
                        var cuotaMora = totalInteres;
                        console.log("Interes Calculado", totalInteres);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(totalInteresValor) +
                            parseFloat(amortizacion);
                        console.log("Total General", totalGeneral);
                        // var mensaje = 'El contrato tiene <b>'+diaAtrasados+' dias </b> de atraso,<br> ' + 'Tiene un capital de :<b>'+parseFloat(capital).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> con un interes de <b>'+parseFloat(totalInteresValor).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> <br>'+
                        // 'El Interes seria un total de::<b>'+parseFloat(totalInteres).toFixed(2)+' '+ data.Resultado.desc_corta+'</b><br>'+'La Amortización de <b>'+parseFloat(amortizacion).toFixed(2)+' '+ data.Resultado.desc_corta+'</b> mas el interes  seria un total de: <b>'+parseFloat(totalGeneral).toFixed(2)+' '+ data.Resultado.desc_corta+' a pagar</b>';
                        var valorDiasAtrasados = diaAtrasados;
                    } else {
                        var diaAtrasados1 = 0;
                        var fechaFin = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaActual = moment();
                        var diasRango = fechaActual.diff(fechaFin, 'days');
                        var valorDiasAtrasados = diaAtrasados;
                        // console.log("diasRango", diasRango);
                        var cuotaMora = 0;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 6.04) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3.7) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }else{
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 2) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }

                        var totalInteres = parseFloat(totalInteresValor1);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(amortizacion);
                        var mensaje = 'El contrato tiene <b>' + diasRango + ' dias </b> habiles,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) + ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(2) + ' ' +
                            data.Resultado.desc_corta + '</b><br>' + 'La Amortización de <b>' + parseFloat(
                                amortizacion).toFixed(2) + ' ' + data.Resultado.desc_corta +
                            '</b> mas el interes  seria un total de: <b>' + parseFloat(totalGeneral)
                            .toFixed(2) + ' ' + data.Resultado.desc_corta + ' a pagar</b>';
                    }

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");

                        var fechaFin3 = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaInteresInicial33 = moment('2020-03-22', 'YYYY/MM/DD');

                        var solodiaC = fechaInteresInicial33.diff(fechaFin3, 'days');
                        var solodiaC1 = fechaActual.diff(fechaInteresFinM, 'days');
                        console.log("solodiaC", solodiaC);
                        console.log("solodiaC11111", solodiaC1);
                        console.log("interes12", interes);
                        console.log("comision12", comision);

                        var diasMora = solodiaC + solodiaC1;

                        var interesDescuento = (parseFloat(interes) / 30) * diasMora;
                        var comisionDescuento = (parseFloat(comision) / 30) * diasMora;
                        //var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento);
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) * diasDecuento) * 70) / 100;
                        var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento) +
                            parseFloat(cutotaMoraDescuento);

                        totalGeneral = totalDescuento;
                        console.log("interesDescuento", interesDescuento);
                        console.log("comisionDescuento", comisionDescuento);
                        console.log("cutotaMoraDescuento", cutotaMoraDescuento);
                        console.log("totalDescuento", totalDescuento);

                    } else {
                        console.log("intervalo NO ACEPATDO");
                        var interesDescuento = 0;
                        var comisionDescuento = 0;
                        var totalDescuento = 0;
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        totalGeneral = totalGeneral + cutotaMoraDescuento;
                        console.log("ffff", cutotaMoraDescuento);
                    }

                    if (interesDescuento > 0) {
                        interes = interesDescuento;
                        comision = comisionDescuento;
                        cuotaMora = cutotaMoraDescuento;
                    } else {
                        cuotaMora = cuotaMora + cutotaMoraDescuento;
                    }

                    console.log("nuevoCapital", nuevoCapital);
                    console.log("interes", interes);
                    console.log("comision", comision);
                    console.log("valorDiasAtrasados", valorDiasAtrasados);
                    console.log("fecha_pago", moment().format("YYYY-MM-DD"));
                    console.log("fecha_fin", contrato.fecha_fin);
                    console.log("total_capital", contrato.total_capital);
                    console.log("cuotaMora", cuotaMora);
                    console.log("contrato", contrato.id);

                    var route = "/PagoContratoAmortizacion";
                    Swal.fire({
                        title: "Estas seguro de pagar la Amortización?",
                        html: mensaje,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: '#d33',
                        confirmButtonText: "Si, Pagar!",
                        closeOnConfirm: false
                    }).then((result) => {
                        if (result.value) {
                            console.log("nuevoCapital", nuevoCapital);
                            console.log("interes", interes);
                            console.log("comision", comision);
                            console.log("valorDiasAtrasados", valorDiasAtrasados);
                            console.log("fecha_pago", moment().format("YYYY-MM-DD"));
                            console.log("fecha_fin", contrato.fecha_fin);
                            console.log("total_capital", contrato.total_capital);
                            console.log("cuotaMora", cuotaMora);
                            console.log("contratoooo", contrato.id);
                            $.ajax({
                                url: route,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    'diasAtraso': valorDiasAtrasados,
                                    //'fecha_pago':moment().format("YYYY-MM-DD"),
                                    'fecha_pago': $("#txtFechaActualA").val(),
                                    'fecha_fin': contrato.fecha_fin,
                                    'capitalActual': $("#txtCapitalPagoTotalA").val(),
                                    //'fecha_Incio':contrato.fecha_contrato,
                                    'total_capital': globalContratoJson.total_capital,
                                    'capital': parseFloat(nuevoCapital).toFixed(2),
                                    // 'interes':parseFloat(interes).toFixed(2),

                                    'interes': parseFloat(interes).toFixed(2),
                                    // 'interes':$("#txtInteresFechaA").val(),
                                    // 'comision': parseFloat(comision).toFixed(2),
                                    'comision':$("#txtGastosAdministrativosA").val(),
                                    'cuotaMora': parseFloat(cuotaMora).toFixed(2),
                                    // 'cuotaMora':$("#txtInteresMoratorioA").val(),
                                    //'comision':0,
                                    'idContrato': contrato.id,
                                },
                                success: function(data) {
                                    console.log(data.Mensaje);
                                    resultado = data.Mensaje;

                                    /*resultado = 1  ROL ELIMINADO*/
                                    if (resultado == 1) {
                                        Swal.fire({
                                            title: "PAGO!",
                                            text: "Se pago correctamente!!",
                                            confirmButtonText: 'Aceptar',
                                            confirmButtonColor: "#66BB6A",
                                            type: "success"
                                        });
                                        global_idPago = data.idPago;
                                        fnBuscarContratos(globalIdPersona);
                                        $("#modalPagarContratos").modal('toggle');
                                        fnImprimirContratoAmortizacion(data.idPago);
                                        fnVolverContratos();
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
                                    Swal.fire("Opss..!",
                                        "El La persona tiene registros en otras tablas!",
                                        "error")
                                }
                            });
                        }
                    })

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

        function fnPagarContratoAmortizacionInteres() {
            existe_pago_total = false;
            //var idContrato = $("#txtIdContrato").val();
            var capital = $("#txtCapitalPagoTotalAI").val();
            var interes = $("#txtInteres").val();
            var amortizacion = $("#txtAmortizacion").val();
            var nuevoCapital = parseFloat(capital) - parseFloat(amortizacion);
            var contrato = globalContratoJson;
            console.log("contrato", contrato);
            var idContrato = contrato.id;
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            if($('#txtAmortizacionInteres').val() != '' && $('#txtAmortizacionGAIA').val() != '' ){
                $('#mensaje_llenar_campos_ai').css('display','none');
                $.ajax({
                    url: route,
                    data: parametros,
                    method: 'GET',
                    //dataType:'json',
                    success: function(data) {
                        var fecha = new Date();
                        var vDia;
                        var vMes;
                        if ((fecha.getMonth() + 1) < 10) {
                            vMes = "0" + (fecha.getMonth() + 1);
                        } else {
                            vMes = (fecha.getMonth() + 1);
                        }
                        if (fecha.getDate() < 10) {
                            vDia = "0" + fecha.getDate();
                        } else {
                            vDia = fecha.getDate();
                        }
                        if (fecha.getHours() < 10) {
                            hora = "0" + fecha.getHours();
                        } else {
                            hora = fecha.getHours();
                        }
                        if (fecha.getMinutes() < 10) {
                            minutos = "0" + fecha.getMinutes();
                        } else {
                            minutos = fecha.getMinutes();
                        }

                        var fechaActual = moment($("#txtFechaActualA").val());
                        var capital = contrato.capital;
                        var fechaContrato = contrato.fecha_contrato;
                        var fechaFin = moment(fechaContrato, 'YYYY/MM/DD');
                        var diasRango = fechaActual.diff(fechaFin, 'days');

                        let interes_capital = $('#txtInteresFechaAI');
                        let interes_capital2 = $('#txtInteresFechaAI2');
                        let gastos_administrativos = $('#txtGastosAdministrativosAI');
                        let gastos_administrativos2 = $('#txtGastosAdministrativosAI2');
                        let interes_moratorios = $('#txtInteresMoratorioAI');
                        let interes_moratorios2 = $('#txtInteresMoratorioAI2');

                        let total_nuevo_ic_bs = (parseFloat(interes_capital.val()) + parseFloat(gastos_administrativos.val()) + parseFloat(interes_moratorios.val())).toFixed(2);
                        let total_nuevo_ic_sus = (parseFloat(interes_capital2.val()) + parseFloat(gastos_administrativos2.val()) + parseFloat(interes_moratorios2.val())).toFixed(2);

                        var total_ic = parseFloat(contrato.interes) + parseFloat(contrato.comision) + parseFloat(interes_moratorios.attr('data-val'));
                        if(contrato.moneda_id == 2){
                            total_ic = parseFloat(contrato.interes) + parseFloat(contrato.comision) + parseFloat(interes_moratorios2.attr('data-val'));
                        }

                        var total_nuevo_ic = (parseFloat(gastos_administrativos.val()) + parseFloat(interes_moratorios.val())).toFixed(2);
                        var txtInteresMoratorio = interes_moratorios.attr('data-val');
                        if(contrato.moneda_id == 2){
                            txtInteresMoratorio = interes_moratorios2.attr('data-val');
                        }

                        var mensaje = 'El contrato tiene un capital de :<b>'+ data.Resultado.desc_corta +' '+ parseFloat(capital).toFixed(2) + '</b> con un interes de <b>' + parseFloat(contrato.interes).toFixed(2) + '</b> mas gastos administrativos de <b>' + parseFloat(data.Resultado.comision) +'</b> mas intereses moratorios de <b> '+ parseFloat(txtInteresMoratorio).toFixed(2) +' </b> haciendo un total de <b>' + parseFloat(total_ic).toFixed(2) + '</b><br>' +'El nuevo interes tendra un total de: <b> Bs.' + parseFloat(interes_capital.val()).toFixed(2) + '  </b> mas el nuevo valor de gastos administrativos de <b>'+parseFloat(gastos_administrativos.val()).toFixed(2)+' Bs.</b> mas el nuevo interes moratorio de <b>'+parseFloat(interes_moratorios.val()).toFixed(2)+'</b>por lo que el nuevo monto total es de <b>'+total_nuevo_ic+' Bs.</b><br>Total a pagar es de: <b>' +parseFloat($('#txtTotalAI').val()).toFixed(2) + ' Bs. / ' + parseFloat($('#txtTotalAI2').val()).toFixed(2) + ' $us';

                        var route = "/PagoContratoAmortizacionInteres";
                        Swal.fire({
                            title: "Estas seguro de pagar la Amortización?",
                            html: mensaje,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: '#d33',
                            confirmButtonText: "Si, Pagar!",
                            closeOnConfirm: false
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: route,
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        'fecha_pago': $("#txtFechaActualAI").val(),
                                        'interes': parseFloat(interes).toFixed(2),
                                        'resultado_nuevo_interes_bs':parseFloat(interes_capital.val()).toFixed(2),
                                        'resultado_nuevo_interes_sus':parseFloat(interes_capital2.val()).toFixed(2),
                                        'resultado_nuevo_gasto_bs':parseFloat(gastos_administrativos.val()).toFixed(2),
                                        'resultado_nuevo_gasto_sus':parseFloat(gastos_administrativos2.val()).toFixed(2),
                                        'moratorio_calculado_bs':parseFloat(interes_moratorios.attr('data-val')).toFixed(2),
                                        'moratorio_calculado_sus':parseFloat(interes_moratorios2.attr('data-val')).toFixed(2),
                                        'interes_moratorios':parseFloat(interes_moratorios.val()).toFixed(2),
                                        'interes_moratorios2':parseFloat(interes_moratorios2.val()).toFixed(2),
                                        'cancelado_bs': parseFloat($('#txtTotalAI').val()).toFixed(2),
                                        'cancelado_sus': parseFloat($('#txtTotalAI2').val()).toFixed(2),
                                        'idContrato': contrato.id,
                                    },
                                    success: function(data) {
                                        console.log(data.Mensaje);
                                        resultado = data.Mensaje;

                                        /*resultado = 1  ROL ELIMINADO*/
                                        if (resultado == 1) {
                                            Swal.fire({
                                                title: "PAGO!",
                                                text: "Se pago correctamente!!",
                                                confirmButtonText: 'Aceptar',
                                                confirmButtonColor: "#66BB6A",
                                                type: "success"
                                            });
                                            global_idPago = data.idPago;
                                            fnBuscarContratos(globalIdPersona);
                                            $("#modalPagarContratos").modal('toggle');
                                            fnImprimirContratoAmortizacionInteres(data.idPago);
                                            fnVolverContratos();
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
                                        Swal.fire("Opss..!",
                                            "El La persona tiene registros en otras tablas!",
                                            "error")
                                    }
                                });
                            }
                        })

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
            else{
                $('#mensaje_llenar_campos_ai').css('display','block');
            }
        }

        /*FUNCION IMPRIME CONTRATO INTERES*/
        function fnImprimirContratoInteres(id) {
            sw_global_reporte = true;
            console.log("id:::", id);
            $("#reporteModal").modal();
            var src = "/ImprimirReporteInteres/" + id;
            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);
            $("#dialog").show();
        };

        /*FUNCION IMPRIME PAGO TOTAL*/
        function fnImprimirContratoPagoTotal(id) {
            sw_global_reporte = true;
            console.log("id:::", id);
            $("#reporteModal").modal();
            var src = "/ImprimirReportePagoTotal/" + id;
            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);
            $("#dialog").show();
        };

        function imprimirFacturaPagoTotal(id) {
            $("#reporteModalFactura").modal();
            var src = "/imprimirFactura/" + id;
            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogFac").html(object);
            $("#dialogFac").show();
        }

        function imprimirCambioPago(id) {
            $("#reporteModalPagoCambio").modal();
            var src = "/imprimirCambioPago/" + id;
            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogPCambio").html(object);
            $("#dialogPCambio").show();
        }

        function fnCerrarReportePago() {
            var id = global_idPago;
            if (sw_global_reporte) {
            //    imprimirFacturaPagoTotal(id);
             imprimirCambioPago(id);
            }
        }

        function fnCerrarReporteAmortizacion() {
            var id = global_idPago;
            imprimirCambioPago(id);
        }

        function fnCerrarReporteCambio() {
            // AL CERRAR EL MODAL DEL CAMBIO MONEDA
            // MOSTRAR EL MODAL PARA ESCOGER LA SUCURSAL PARA RECOGER
            // LA PRENDA
            if(existe_pago_total == true){
                $('#modalSolicitud').modal('show');
            }
        }

        function fnCerrarReporteAmortizacionInteres() {
            var id = global_idPago;
            imprimirCambioPago(id);
        }

        function fnCerrarReporteFactura() {
            var id = global_idPago;
            if (sw_global_reporte) {
                imprimirCambioPago(id);
            }
        }

        /*FUNCION IMPRIME AMORTIZACION*/
        function fnImprimirContratoAmortizacion(id) {
            sw_global_reporte = true;
            console.log("id:::", id);
            $("#reporteModalAmortizacion").modal();
            var src = "/ImprimirReporteAmortizacion/" + id;
            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogAmortizacion").html(object);
            $("#dialogAmortizacion").show();
        };

        /*FUNCION IMPRIME AMORTIZACION INTERES*/
        function fnImprimirContratoAmortizacionInteres(id) {
            sw_global_reporte = true;
            console.log("id:::", id);
            $("#reporteModalAmortizacionInteres").modal();
            var src = "/ImprimirReporteAmortizacionInteres/" + id;
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogAmortizacionInteres").html(object);
            $("#dialogAmortizacionInteres").show();
        }

        /*FUNCION RETIRO DE PRENDA*/
        function fnRetiroPrenda(contrato) {
            var idContrato = contrato.id;
            var route = "/Pagos/" + idContrato + "";
            //console.log("comision",comision);
            Swal.fire({
                title: "Esta seguro de entregar la prenda?",
                //html: mensaje,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, Entregar!",
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
                            'idContrato': contrato.id,
                        },
                        success: function(data) {
                            console.log(data.Mensaje);
                            resultado = data.Mensaje;

                            /*resultado = 1  ROL ELIMINADO*/
                            if (resultado == 1) {
                                Swal.fire({
                                    title: "PRENDA!",
                                    text: "Se entrego la prenda correctamente!!",
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: "#66BB6A",
                                    type: "success"
                                });
                                fnBuscarContratos(globalIdPersona);
                                fnImprimirContratoPrenda(idContrato);
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
        };

        /*FUNCION IMPRIME AMORTIZACION*/
        function fnImprimirContratoPrenda(id) {
            sw_global_reporte = false;
            console.log("id:::", id);
            $("#reporteModal").modal();
            var src = "/ImprimirReporteContratoEntregado/" + id;
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);
            $("#dialog").show();
        };

        /************************** DATO PAGOS  ****************************************/
        /*/******************************** LISTADO DETALE DE PAGOS *****************************************/
        function fnDatoPagoTotal(contrato) {
            $('#_contrato_id').val(contrato.id);
            console.log($('#_contrato_id').val() + "------------");
            document.getElementById('divMostrarPagoTotal').style.display = 'block';
            document.getElementById('divMostrarPagoInteres').style.display = 'none';
            if ($('#ddlTipoBusqueda').val() == 'CI') {
                $('.clsContratos').hide();
            }
            if ($('#ddlTipoBusqueda').val() == 'CO') {
                $('.clsCodigo').hide();
            }

            $('.clsDatoPagoTotal').show();
            globalContratoJson = contrato;
            console.log("contrato", contrato);
            console.log("contrato", contrato.capital);
            var fechaContratoCodigo = new Date(contrato.fecha_contrato);
            var codigoContratoT = contrato.sucural.nuevo_codigo + '' + fechaContratoCodigo.getFullYear().toString().substr(-
                2) + '' + contrato.codigo_num;


            var idContrato = contrato.id;
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {

                    let txtBs = data.txtBs;
                    let txtSus = data.txtSus;
                    let cambioMonedas = data.cambioMonedas;

                    $('._txtBs').text(txtBs.desc_corta);
                    $('._txtSus').text(txtSus.desc_corta);

                    let moneda_pago = data.Resultado.moneda_id

                    var fecha = new Date();
                    var vDia;
                    var vMes;
                    if ((fecha.getMonth() + 1) < 10) {
                        vMes = "0" + (fecha.getMonth() + 1);
                    } else {
                        vMes = (fecha.getMonth() + 1);
                    }
                    if (fecha.getDate() < 10) {
                        vDia = "0" + fecha.getDate();
                    } else {
                        vDia = fecha.getDate();
                    }
                    if (fecha.getHours() < 10) {
                        hora = "0" + fecha.getHours();
                    } else {
                        hora = fecha.getHours();
                    }
                    if (fecha.getMinutes() < 10) {
                        minutos = "0" + fecha.getMinutes();
                    } else {
                        minutos = fecha.getMinutes();
                    }
                    //var fechaActual = moment(fecha.getFullYear() +"-" +vMes +"-"+ vDia);
                    var fechaActual = moment($("#txtFechaActualT").val());
                    if (data == "") {
                        console.log("datosss vacios");
                        var fechaFin = moment(contrato.fecha_fin).format('YYYY-MM-DD');
                        var interes = contrato.interes;
                        var comision = contrato.comision;
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        var capital = contrato.capital;
                        var fechaContrato = contrato.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }

                    } else {
                        console.log("con detalle", data.Resultado);
                        var fechaFin = moment(data.Resultado.fecha_fin).format('YYYY-MM-DD');
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        // var capital = data.Resultado.capital;
                        var fechaContrato = data.Resultado.fecha_inio;
                        // var capital = data.Resultado.capital;
                        var capital = contrato.capital;
                        //var fechaContrato = data.Resultado.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }
                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }
                    }

                    console.log("fecha fin", fechaFin);
                    $("#txtVenceEl").val(fechaFin);
                    var fechaInteresInicial = '2020-03-22';
                    var fechaInteresFin = '2020-05-11';
                    var fechaInteresFinM = moment('2020-05-11');
                    console.log(fechaInteresFinM.diff(fechaInteresInicial, 'days'),
                        ' días de diferencia para descuneto interes');
                    console.log("fecha Inicial Interes", fechaInteresInicial);
                    console.log("fecha Final Interes", fechaInteresFin);
                    console.log("fecha fin", fechaFin);
                    console.log("fecha actual", fechaActual);
                    console.log(fechaActual.diff(fechaFin, 'days'), ' días de diferencia');
                    console.log("dias del mes actual", moment(fechaActual, "YYYY-MM").daysInMonth());
                    console.log("total Interes", totalInteresValor);
                    //var diaActual = moment(fechaActual, "YYYY-MM").daysInMonth();
                    var diaActual = 30;
                    var diaAtrasados = fechaActual.diff(fechaFin, 'days');

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");
                        // var diasDecuento = diaAtrasados;
                        var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    } else {
                        console.log("intervalo NO ACEPATDO");
                        if (fechaFin > fechaInteresFin) {
                            var diasDecuento = 0;
                        } else {
                            var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        }
                        //var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    }

                    var fecha1 = new Date(fechaContrato);
                    var fecha2 = new Date(fechaActual);
                    var diasDif = fecha2.getTime() - fecha1.getTime();
                    var fechaEmision = moment(fechaContrato, 'YYYY/MM/DD');
                    var fechaExpiracion = moment(fechaActual, 'YYYY/MM/DD');
                    //var diasTranscurridos = Math.round(diasDif/(1000 * 60 * 60 * 24)) + 1;
                    var diasTranscurridos = fechaExpiracion.diff(fechaEmision, 'days');
                    console.log("diasTranscurridos", diasTranscurridos);

                    console.log("diaAtrasados", diaAtrasados);
                    if (diaAtrasados > 0) {
                        // var diaAtrasados1 = diaAtrasados - diasDecuento;
                        var calc = diaAtrasados - diasDecuento;
                        if (calc > 0) {
                            var diaAtrasados1 = diaAtrasados - diasDecuento;
                        } else {
                            var diaAtrasados1 = 0;
                        }
                        // var diaAtrasados1 = diaAtrasados;
                        console.log("diaAtrasados1 valoo", diaAtrasados1);
                        console.log("totalInteresValor valoo", totalInteresValor);
                        console.log("diaActual valoo", diaActual);
                        var totalInteres = (parseFloat(totalInteresValor) / diaActual) * diaAtrasados1;
                        var cuotaMora = totalInteres;
                        console.log("Interes Calculado", totalInteres);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(capital) + parseFloat(
                            totalInteresValor);
                        console.log("Total General", totalGeneral);
                        var mensaje = 'El contrato tiene <b>' + diaAtrasados + ' dias </b> de atraso,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) + ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'Por lo tanto el Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(
                                2) + ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El Capital mas el interes seria un total de: <b>' + parseFloat(totalGeneral)
                            .toFixed(2) + ' ' + data.Resultado.desc_corta + '</b>';
                        var valorDiasAtrasados = diaAtrasados;
                    } else {
                        var diaAtrasados1 = 0;
                        var fechaFin = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaActual = moment();
                        var diasRango = fechaActual.diff(fechaFin, 'days');
                        var valorDiasAtrasados = diaAtrasados;
                        console.log("diasRango", diasRango);
                        var cuotaMora = 0;
                        // if (diasRango <= 15) {                   
                        //  var totalInteres = parseFloat(totalInteresValor) / 2;   
                        //  var totalGeneral = parseFloat(totalInteres) + parseFloat(capital);  
                        //  if (parseFloat(capital)<= 3499) {                       
                        //      var interes = ((capital/2) data.Resultado.p_interes)/100;
                        //      var comision = ((capital/2) * 7.4)/100;                     
                        //     }
                        //     else{                        
                        //          var interes = ((capital/2) data.Resultado.p_interes)/100;
                        //      var comision = ((capital/2) * 4.4)/100;                     
                        //     }                        
                        //  var mensaje = 'El contrato tiene <b>'+diasRango+' dias </b> habiles y es menor a 15 días,<br> ' +
                        //  'Tiene un capital de :<b>'+parseFloat(capital).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> con un interes de <b>'+parseFloat(totalInteresValor).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> <br>'+
                        //  'El Interes seria un total de: <b>'+totalInteres+' '+ data.Resultado.desc_corta+'</b><br>'+
                        //  'El Capital mas el interes seria un total de: <b>'+totalGeneral+' '+ data.Resultado.desc_corta+'</b>'; 
                        // }
                        // else{

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }
                        if (parseFloat(capital) <= valor_comparacion1) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 6.04) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3.7) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }else{
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 2) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }

                        var totalInteres = parseFloat(totalInteresValor1 + interes);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(capital);
                        var mensaje = 'El contrato tiene <b>' + diasRango + ' dias </b> habiles,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) + ' ' + data.Resultado
                            .desc_corta + '</b> con un interes de <b>' + parseFloat(totalInteresValor).toFixed(
                                2) + ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El Capital mas el interes seria un total de: <b>' + parseFloat(totalGeneral)
                            .toFixed(2) + ' ' + data.Resultado.desc_corta + '</b>';
                        // }
                    }

                    console.log("interes", interes);
                    console.log("comision", comision);
                    console.log("capital", capital);

                    //$("#txtCodigo").val(contrato.codigo);
                    if (contrato.codigo != "") {
                        $("#txtCodigo").val(contrato.codigo);
                    } else {
                        $("#txtCodigo").val(codigoContratoT);
                    }

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");

                        var fechaFin3 = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaInteresInicial33 = moment('2020-03-22', 'YYYY/MM/DD');

                        var solodiaC = fechaInteresInicial33.diff(fechaFin3, 'days');
                        var solodiaC1 = fechaActual.diff(fechaInteresFinM, 'days');
                        console.log("solodiaC", solodiaC);
                        console.log("solodiaC11111", solodiaC1);

                        var diasMora = solodiaC + solodiaC1;

                        var interesDescuento = (parseFloat(interes) / 30) * diasMora;
                        var comisionDescuento = (parseFloat(comision) / 30) * diasMora;
                        //var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento);
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento) +
                            parseFloat(cutotaMoraDescuento);
                        totalGeneral = totalDescuento + parseFloat(capital);

                    } else {
                        console.log("intervalo NO ACEPATDO");
                        var interesDescuento = 0;
                        var comisionDescuento = 0;
                        var totalDescuento = 0;
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        totalGeneral = totalGeneral + cutotaMoraDescuento;
                        console.log("ffff", cutotaMoraDescuento);
                    }

                    console.log("totalGeneral", totalGeneral);

                    $("#txtCI").val(contrato.cliente.persona.nrodocumento);
                    $("#txtNombres").val(contrato.cliente.persona.nombres + ' ' + contrato.cliente.persona
                        .primerapellido + ' ' + contrato.cliente.persona.segundoapellido);
                    $("#txtDiasAtraso").val(valorDiasAtrasados);
                    $("#txtDiasTranscurridos").val(diasTranscurridos);
                    $("#txtAtrasoDiasDescuentoT").val(diasDecuento);
                    $("#txtCobroDiasDescuentoT").val(diaAtrasados1);

                    if(data.sw_amortizacion)
                    {
                        $('#mensajePagoTotal').css('display','block');
                        // AFECTAR LOS VALORES DE INTERES Y GASTOS
                        if (moneda_pago == 1) {
                            interes = parseFloat(interes) - parseFloat(data.valor_amortizacion_interes_bs);
                            comision = parseFloat(comision) - parseFloat(data.valor_amortizacion_gastos_bs);
                        }else{
                            interes = parseFloat(interes) - parseFloat(data.valor_amortizacion_interes_sus);
                            comision
                            comision = parseFloat(comision) - parseFloat(data.valor_amortizacion_gastos_sus);
                        }

                        if(interes < 0)
                        {
                            interes = 0;
                        }

                        if(comision < 0)
                        {
                            comision = 0;
                        }
                        totalGeneral = parseFloat(capital) + parseFloat(interes) + parseFloat(comision) + parseFloat(cutotaMoraDescuento);
                    }

                    if (moneda_pago == 1) {
                        $("#txtInteresFecha").val(parseFloat(interes).toFixed(2));
                        $("#txtInteresFecha2").val(parseFloat(interes / data.cambioMonedas.valor_bs).toFixed(
                        2));

                        $("#txtInteresFechaTD").val(parseFloat(interesDescuento).toFixed(2));
                        $("#txtInteresFechaTD2").val(parseFloat(interesDescuento / data.cambioMonedas.valor_bs)
                            .toFixed(2));

                        $("#txtGastosAdministrativos").val(parseFloat(comision).toFixed(2));
                        $("#txtGastosAdministrativos2").val(parseFloat(comision / data.cambioMonedas.valor_bs)
                            .toFixed(2));

                        $("#txtGastosAdministrativosTD").val(parseFloat(comisionDescuento).toFixed(2));
                        $("#txtGastosAdministrativosTD2").val(parseFloat(comisionDescuento / data.cambioMonedas
                            .valor_bs).toFixed(2));

                        $("#txtInteresMoratorio").val(parseFloat(cuotaMora - data.suma_moratorios_bs).toFixed(2));
                        $("#txtInteresMoratorio2").val(parseFloat((cuotaMora / data.cambioMonedas.valor_bs) - data.suma_moratorios_sus)
                            .toFixed(2));

                        $("#txtInteresMoratorioTD").val(parseFloat(cutotaMoraDescuento).toFixed(2));
                        $("#txtInteresMoratorioTD2").val(parseFloat(cutotaMoraDescuento / data.cambioMonedas
                            .valor_bs).toFixed(2));

                        $("#txtTotal").val(parseFloat(totalGeneral).toFixed(2));
                        $("#txtTotal2").val(parseFloat(totalGeneral / data.cambioMonedas.valor_bs).toFixed(2));

                        $("#txtCapitalPagoTotal").val(parseFloat(capital).toFixed(2));
                        $("#txtCapitalPagoTotal2").val(parseFloat(capital / data.cambioMonedas.valor_bs)
                            .toFixed(2));

                        $("#txtMontoTotal").val($("#txtTotal").val());
                        $("#txtMontoTotal2").val($("#txtTotal2").val());
                    } else {
                        $("#txtInteresFecha").val(parseFloat(interes * data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtInteresFecha2").val(parseFloat(interes).toFixed(2));

                        $("#txtInteresFechaTD").val(parseFloat(interesDescuento * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtInteresFechaTD2").val(parseFloat(interesDescuento).toFixed(2));

                        $("#txtGastosAdministrativos").val(parseFloat(comision * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtGastosAdministrativos2").val(parseFloat(comision).toFixed(2));

                        $("#txtGastosAdministrativosTD").val(parseFloat(comisionDescuento * data.cambioMonedas
                            .valor_bs).toFixed(2));
                        $("#txtGastosAdministrativosTD2").val(parseFloat(comisionDescuento).toFixed(2));

                        $("#txtInteresMoratorio").val(parseFloat((cuotaMora * data.cambioMonedas.valor_bs) - data.suma_moratorios_bs)
                            .toFixed(2));
                        $("#txtInteresMoratorio2").val(parseFloat(cuotaMora - data.suma_moratorios_sus).toFixed(2));

                        $("#txtInteresMoratorioTD").val(parseFloat(cutotaMoraDescuento * data.cambioMonedas
                            .valor_bs).toFixed(2));
                        $("#txtInteresMoratorioTD2").val(parseFloat(cutotaMoraDescuento).toFixed(2));

                        $("#txtTotal").val(parseFloat(totalGeneral * data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtTotal2").val(parseFloat(totalGeneral).toFixed(2));

                        $("#txtCapitalPagoTotal").val(parseFloat(capital * data.cambioMonedas.valor_bs).toFixed(
                            2));
                        $("#txtCapitalPagoTotal2").val(parseFloat(capital).toFixed(2));

                        $("#txtMontoTotal").val($('#txtTotal').val());
                        $("#txtMontoTotal2").val($("#txtTotal2").val());
                    }

                    $('#txtMoneda').val(data.Resultado.desc_corta);
                },
                beforeSend: function() {
                    $('.loader').addClass('loader-default  is-active');

                },
                complete: function() {
                    $('.loader').removeClass('loader-default  is-active');
                },
                error: function(error) {
                    swal({
                        title: "ERROR PAGO...",
                        text: "Los busqueda no se puede cargar",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: 'Aceptar',
                        type: "error"
                    });
                }
            });
        }

        function fnDatoPagoInteres(contrato) {
            console.log("contrato", contrato);
            document.getElementById('divMostrarPagoInteres').style.display = 'block';
            //$('.clsContratos').hide();
            if ($('#ddlTipoBusqueda').val() == 'CI') {
                $('.clsContratos').hide();
            }
            if ($('#ddlTipoBusqueda').val() == 'CO') {
                $('.clsCodigo').hide();
            }
            $('.clsDatoPagoInteres').show();
            globalContratoJson = contrato;
            var idContrato = contrato.id;
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            var fechaContratoCodigo = new Date(contrato.fecha_contrato);
            var codigoContratoI = contrato.sucural.nuevo_codigo + '' + fechaContratoCodigo.getFullYear().toString().substr(-
                2) + '' + contrato.codigo_num;
            console.log(codigoContratoI);
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    let txtBs = data.txtBs;
                    let txtSus = data.txtSus;
                    let cambioMonedas = data.cambioMonedas;

                    $('._txtBs').text(txtBs.desc_corta);
                    $('._txtSus').text(txtSus.desc_corta);

                    let moneda_pago = data.Resultado.moneda_id

                    var fecha = new Date();
                    var vDia;
                    var vMes;
                    if ((fecha.getMonth() + 1) < 10) {
                        vMes = "0" + (fecha.getMonth() + 1);
                    } else {
                        vMes = (fecha.getMonth() + 1);
                    }
                    if (fecha.getDate() < 10) {
                        vDia = "0" + fecha.getDate();
                    } else {
                        vDia = fecha.getDate();
                    }
                    if (fecha.getHours() < 10) {
                        hora = "0" + fecha.getHours();
                    } else {
                        hora = fecha.getHours();
                    }
                    if (fecha.getMinutes() < 10) {
                        minutos = "0" + fecha.getMinutes();
                    } else {
                        minutos = fecha.getMinutes();
                    }
                    //var fechaActual = moment(fecha.getFullYear() +"-" +vMes +"-"+ vDia);
                    var fechaActual = moment($("#txtFechaActualI").val());
                    if (data == "") {
                        console.log("datosss vacios");
                        var fechaFin = moment(contrato.fecha_fin).add(1, 'days').format('YYYY-MM-DD');
                        var interes = contrato.interes;
                        var comision = contrato.comision;
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        var capital = contrato.capital;
                        var fechaContrato = contrato.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }

                    } else {
                        console.log("con detalle", data.Resultado);
                        // var fechaFin = moment(data.Resultado.fecha_fin).add(1,'days').format('YYYY-MM-DD');          
                        var fechaFin = moment(data.Resultado.fecha_fin).format('YYYY-MM-DD');
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        // var capital = data.Resultado.capital;
                        var fechaContrato = data.Resultado.fecha_inio;
                        // var capital = data.Resultado.capital;
                        var capital = contrato.capital;
                        //var fechaContrato = data.Resultado.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }
                    }

                    $("#txtVenceElI").val(fechaFin);

                    var fechaInteresInicial = '2020-03-22';
                    var fechaInteresFin = '2020-05-11';
                    var fechaInteresFinM = moment('2020-05-11');
                    console.log(fechaInteresFinM.diff(fechaInteresInicial, 'days'),
                        ' días de diferencia para descuneto interes');
                    console.log("fecha Inicial Interes", fechaInteresInicial);
                    console.log("fecha Final Interes", fechaInteresFin);
                    console.log("fecha fin", fechaFin);
                    console.log("fecha actual", fechaActual);
                    console.log(fechaActual.diff(fechaFin, 'days'), ' días de diferencia');
                    console.log("dias del mes actual", moment(fechaActual, "YYYY-MM").daysInMonth());
                    console.log("total Interes", totalInteresValor);
                    //var diaActual = moment(fechaActual, "YYYY-MM").daysInMonth();
                    var diaActual = 30;

                    var diaAtrasados = fechaActual.diff(fechaFin, 'days');

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");
                        // var diasDecuento = diaAtrasados
                        var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    } else {
                        console.log("intervalo NO ACEPATDO");
                        if (fechaFin > fechaInteresFin) {
                            console.log("FECHA MAYOR");
                            var diasDecuento = 0;
                        } else {
                            console.log("FECHA MENOR");
                            var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        }
                        //var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    }

                    var fecha1 = new Date(fechaContrato);
                    var fecha2 = new Date(fechaActual);
                    var diasDif = fecha2.getTime() - fecha1.getTime();
                    //var diasTranscurridos = Math.round(diasDif/(1000 * 60 * 60 * 24)) + 1;
                    var fechaEmision = moment(fechaContrato, 'YYYY/MM/DD');
                    var fechaExpiracion = moment(fechaActual, 'YYYY/MM/DD');
                    //var diasTranscurridos = Math.round(diasDif/(1000 * 60 * 60 * 24)) + 1;
                    var diasTranscurridos = fechaExpiracion.diff(fechaEmision, 'days');
                    console.log("diasTranscurridos", diasTranscurridos);

                    console.log("diaAtrasados", diaAtrasados);
                    if (diaAtrasados > 0) {
                        var calc = diaAtrasados - diasDecuento;
                        if (calc > 0) {
                            var diaAtrasados1 = diaAtrasados - diasDecuento;
                        } else {
                            var diaAtrasados1 = 0;
                        }

                        // var diaAtrasados1 = diaAtrasados;
                        console.log("diaAtrasados1 valoo", diaAtrasados1);
                        console.log("totalInteresValor valoo", totalInteresValor);
                        console.log("diaActual valoo", diaActual);
                        var totalInteres = (parseFloat(totalInteresValor) / diaActual) * diaAtrasados1;
                        var cuotaMora = totalInteres;
                        console.log("Interes Calculado", totalInteres);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(totalInteresValor);
                        console.log("Total General", totalGeneral);
                        var mensaje = 'El contrato tiene <b>' + diaAtrasados + ' dias </b> de atraso,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de::<b>' + parseFloat(totalInteres).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El total del interes a pagar es de:<b>' + parseFloat(totalGeneral).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b>';
                        var valorDiasAtrasados = diaAtrasados;
                    } else {
                        var diaAtrasados1 = 0;
                        var fechaFin = moment(fechaContrato, 'YYYY/MM/DD');
                        console.log("fechaFin111 VALO", fechaFin);
                        var fechaActual = moment();
                        console.log("fechaActual111 VALO", fechaActual);
                        var diasRango = fechaActual.diff(fechaFin, 'days');
                        var valorDiasAtrasados = diaAtrasados;
                        console.log("diasRango VALO", diasRango);
                        var cuotaMora = 0;
                        // if (diasRango <= 15) {                   
                        //  var totalInteres = parseFloat(totalInteresValor) / 2;   
                        //  var totalGeneral = parseFloat(totalInteres);    
                        //  if (parseFloat(capital)<= 3499) {                       
                        //      var interes = ((capital/2) * 3)/100;
                        //      var comision = ((capital/2) * 7.4)/100;                     
                        //     }
                        //     else{                        
                        //          var interes = ((capital/2) * 3)/100;
                        //      var comision = ((capital/2) * 4.4)/100;                     
                        //     }                        
                        //  var mensaje = 'El contrato tiene <b>'+diasRango+' dias </b> habiles y es menor a 15 días,<br> ' +
                        //  'Tiene un capital de :<b>'+parseFloat(capital).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> con un interes de <b>'+parseFloat(totalInteresValor).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> <br>'+
                        //  'El Interes seria un total de: <b>'+totalInteres+' '+ data.Resultado.desc_corta+'</b><br>'+
                        //  'El total del interes a pagar es de:<b>'+totalGeneral+' '+ data.Resultado.desc_corta+'</b>'; 
                        // }
                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 6.04) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;

                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3.7) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }else{
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 2) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }

                        var totalInteres = parseFloat(totalInteresValor1);
                        var totalGeneral = parseFloat(totalInteres + interes);
                        var mensaje = 'El contrato tiene <b>' + diasRango + ' dias </b> habiles,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(2) + ' ' +
                            data.Resultado.desc_corta + '</b><br>' + 'El total del interes a pagar es de:<b>' +
                            parseFloat(totalGeneral).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b>';
                    }

                    console.log("interes", interes);
                    console.log("comision", comision);
                    if (contrato.codigo != "") {
                        $("#txtCodigoI").val(contrato.codigo);
                    } else {
                        $("#txtCodigoI").val(codigoContratoI);
                    }

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");

                        var fechaFin3 = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaInteresInicial33 = moment('2020-03-22', 'YYYY/MM/DD');

                        var solodiaC = fechaInteresInicial33.diff(fechaFin3, 'days');
                        var solodiaC1 = fechaActual.diff(fechaInteresFinM, 'days');
                        console.log("solodiaC", solodiaC);
                        console.log("solodiaC11111", solodiaC1);

                        var diasMora = solodiaC + solodiaC1;


                        var interesDescuento = (parseFloat(interes) / 30) * diasMora;
                        var comisionDescuento = (parseFloat(comision) / 30) * diasMora;

                        //var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento);
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        // cutotaMoraDescuento = cutotaMoraDescuento + interesDescuento1 + comisionDescuento1;
                        var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento) +
                            parseFloat(cutotaMoraDescuento);

                        totalGeneral = totalDescuento;

                    } else {
                        console.log("intervalo NO ACEPATDO");
                        var interesDescuento = 0;
                        var comisionDescuento = 0;
                        var totalDescuento = 0;
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        totalGeneral = totalGeneral + cutotaMoraDescuento;
                        console.log("ffff", cutotaMoraDescuento);
                    }
                    $("#txtCII").val(contrato.cliente.persona.nrodocumento);
                    $("#txtNombresI").val(contrato.cliente.persona.nombres + ' ' + contrato.cliente.persona
                        .primerapellido + ' ' + contrato.cliente.persona.segundoapellido);
                    $("#txtDiasAtrasoI").val(valorDiasAtrasados);
                    $("#txtAtrasoDiasDescuentoI").val(diasDecuento);
                    $("#txtCobroDiasDescuentoI").val(diaAtrasados1);
                    $("#txtDiasTranscurridosI").val(diasTranscurridos);
                    $("#txtTotalID").val(parseFloat(totalDescuento).toFixed(2));

                    if(data.sw_amortizacion)
                    {
                        $('#mensajePagoInteres').css('display','block');
                        // AFECTAR LOS VALORES DE INTERES Y GASTOS
                        if (moneda_pago == 1) {
                            interes = parseFloat(interes) - parseFloat(data.valor_amortizacion_interes_bs);
                            comision = parseFloat(comision) - parseFloat(data.valor_amortizacion_gastos_bs);
                        }else{
                            interes = parseFloat(interes) - parseFloat(data.valor_amortizacion_interes_sus);
                            comision
                            comision = parseFloat(comision) - parseFloat(data.valor_amortizacion_gastos_sus);
                        }

                        if(interes < 0)
                        {
                            interes = 0;
                        }

                        if(comision < 0)
                        {
                            comision = 0;
                        }
                        totalGeneral = parseFloat(interes) + parseFloat(comision) + parseFloat(cutotaMoraDescuento);
                    }

                    if (moneda_pago == 1) {
                        $("#txtInteresFechaI").val(parseFloat(interes).toFixed(2));
                        $("#txtInteresFechaI2").val(parseFloat(interes / data.cambioMonedas.valor_bs).toFixed(
                            2));

                        $("#txtInteresFechaID").val(parseFloat(interesDescuento).toFixed(2));
                        $("#txtInteresFechaID2").val(parseFloat(interesDescuento / data.cambioMonedas.valor_bs)
                            .toFixed(2));

                        $("#txtGastosAdministrativosI").val(parseFloat(comision).toFixed(2));
                        $("#txtGastosAdministrativosI2").val(parseFloat(comision / data.cambioMonedas.valor_bs)
                            .toFixed(2));

                        $("#txtGastosAdministrativosID").val(parseFloat(comisionDescuento).toFixed(2));
                        $("#txtGastosAdministrativosID2").val(parseFloat(comisionDescuento / data.cambioMonedas
                            .valor_bs).toFixed(2));

                        $("#txtInteresMoratorioI").val(parseFloat(cuotaMora - data.suma_moratorios_bs).toFixed(2));
                        $("#txtInteresMoratorioI2").val(parseFloat((cuotaMora / data.cambioMonedas.valor_bs) - data.suma_moratorios_sus)
                            .toFixed(2));

                        $("#txtInteresMoratorioID").val(parseFloat(cutotaMoraDescuento).toFixed(2));
                        $("#txtInteresMoratorioID2").val(parseFloat(cutotaMoraDescuento / data.cambioMonedas
                            .valor_bs).toFixed(2));

                        $("#txtTotalI").val(parseFloat(totalGeneral).toFixed(2));
                        $("#txtTotalI2").val(parseFloat(totalGeneral / data.cambioMonedas.valor_bs).toFixed(2));

                        $("#txtCapitalPagoTotalI").val(parseFloat(capital).toFixed(2));
                        $("#txtCapitalPagoTotalI2").val(parseFloat(capital / data.cambioMonedas.valor_bs)
                            .toFixed(2));

                        $("#txtMontoTotalI").val((parseFloat($("#txtCapitalPagoTotalI").val()) + parseFloat($("#txtInteresFechaI").val()) + parseFloat($('#txtGastosAdministrativosI').val()) + parseFloat($("#txtInteresMoratorioI").val())).toFixed(2));
                        $("#txtMontoTotalI2").val((parseFloat($("#txtCapitalPagoTotalI2").val()) + parseFloat($("#txtInteresFechaI2").val()) + parseFloat($('#txtGastosAdministrativosI2').val()) + parseFloat($("#txtInteresMoratorioI2").val())).toFixed(2));
                    } else {
                        $("#txtInteresFechaI").val(parseFloat(interes * data.cambioMonedas.valor_bs).toFixed(
                        2));
                        $("#txtInteresFechaI2").val(parseFloat(interes).toFixed(2));

                        $("#txtInteresFechaID").val(parseFloat(interesDescuento * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtInteresFechaID2").val(parseFloat(interesDescuento).toFixed(2));

                        $("#txtGastosAdministrativosI").val(parseFloat(comision * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtGastosAdministrativosI2").val(parseFloat(comision).toFixed(2));

                        $("#txtGastosAdministrativosID").val(parseFloat(comisionDescuento * data.cambioMonedas
                            .valor_bs).toFixed(2));
                        $("#txtGastosAdministrativosID2").val(parseFloat(comisionDescuento).toFixed(2));

                        $("#txtInteresMoratorioI").val(parseFloat((cuotaMora * data.cambioMonedas.valor_bs) - data.suma_moratorios_bs)
                            .toFixed(2));
                        $("#txtInteresMoratorioI2").val(parseFloat(cuotaMora - data.suma_moratorios_sus).toFixed(2));

                        $("#txtInteresMoratorioID").val(parseFloat(cutotaMoraDescuento * data.cambioMonedas
                            .valor_bs).toFixed(2));
                        $("#txtInteresMoratorioID2").val(parseFloat(cutotaMoraDescuento).toFixed(2));

                        $("#txtTotalI").val(parseFloat(totalGeneral * data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtTotalI2").val(parseFloat(totalGeneral).toFixed(2));

                        $("#txtCapitalPagoTotalI").val(parseFloat(capital * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtCapitalPagoTotalI2").val(parseFloat(capital).toFixed(2));

                        $("#txtMontoTotalI").val((parseFloat($("#txtCapitalPagoTotalI").val()) + parseFloat($("#txtInteresFechaI").val()) + parseFloat($('#txtGastosAdministrativosI').val()) + parseFloat($("#txtInteresMoratorioI").val())).toFixed(2));
                        $("#txtMontoTotalI2").val((parseFloat($("#txtCapitalPagoTotalI2").val()) + parseFloat($("#txtInteresFechaI2").val()) + parseFloat($('#txtGastosAdministrativosI2').val()) + parseFloat($("#txtInteresMoratorioI2").val())).toFixed(2));
                    }

                    $('#txtMonedaI').val(data.Resultado.desc_corta);
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

        function fnDatoPagoAmortizacion(contrato) {
            $("#txtAmortizacion").val('');
            document.getElementById('divMostrarPagoAmortizacion').style.display = 'block';
            // $('.clsContratos').hide();
            if ($('#ddlTipoBusqueda').val() == 'CI') {
                $('.clsContratos').hide();
            }
            if ($('#ddlTipoBusqueda').val() == 'CO') {
                $('.clsCodigo').hide();
            }
            $('.clsDatoPagoAmortizacion').show();
            globalContratoJson = contrato;
            var idContrato = contrato.id;
            var amortizacion = 0;
            var fechaContratoCodigo = new Date(contrato.fecha_contrato);
            var codigoContratoA = contrato.sucural.nuevo_codigo + '' + fechaContratoCodigo.getFullYear().toString().substr(-
                2) + '' + contrato.codigo_num;
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {

                    let txtBs = data.txtBs;
                    let txtSus = data.txtSus;
                    let cambioMonedas = data.cambioMonedas;
                    global_cambioMonedas = cambioMonedas;
                    $('._txtBs').text(txtBs.desc_corta);
                    $('._txtSus').text(txtSus.desc_corta);

                    let moneda_pago = data.Resultado.moneda_id
                    global_monedaContrato = moneda_pago;
                    var fechaActual = moment();
                    globalInteresAmor = data.Resultado.p_interes;
                    console.log("DATAAA", data);
                    if (data == "") {
                        console.log("datosss vacios");
                        var fechaFin = moment(contrato.fecha_fin).format('YYYY-MM-DD');
                        var interes = contrato.interes;
                        var comision = contrato.comision;
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        var capital = contrato.capital;
                        var fechaContrato = contrato.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }

                    } else {
                        console.log("con detalle", data.Resultado);
                        $("#txtIdContrato").val(data.Resultado.contrato_id);
                        $("#txtCapital").val(data.Resultado.capital);
                        $("#txtInteres").val(data.Resultado.interes);
                        $("#txtComisión").val(data.Resultado.comision);
                        var fechaFin = moment(data.Resultado.fecha_fin).format('YYYY-MM-DD');
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        // var capital = data.Resultado.capital;
                        var fechaContrato = data.Resultado.fecha_inio;
                        // var capital = data.Resultado.capital;
                        var capital = contrato.capital;
                        //var fechaContrato = data.Resultado.fecha_contrato;
                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }
                    }

                    var fecha1 = new Date(fechaContrato);
                    var fecha2 = new Date(fechaActual);
                    console.log("fecha1", fecha1);
                    console.log("fecha2", fecha2);

                    var fechaEmision = moment(fechaContrato, 'YYYY/MM/DD');
                    var fechaExpiracion = moment(fechaActual, 'YYYY/MM/DD');

                    //var diasDif = fecha2.getTime() - fecha1.getTime();
                    //var diasTranscurridos = Math.round(diasDif/(1000 * 60 * 60 * 24)) + 1;
                    var diasTranscurridos = fechaExpiracion.diff(fechaEmision, 'days');

                    $("#txtVenceElA").val(fechaFin);
                    var fecha = new Date();
                    var vDia;
                    var vMes;
                    if ((fecha.getMonth() + 1) < 10) {
                        vMes = "0" + (fecha.getMonth() + 1);
                    } else {
                        vMes = (fecha.getMonth() + 1);
                    }
                    if (fecha.getDate() < 10) {
                        vDia = "0" + fecha.getDate();
                    } else {
                        vDia = fecha.getDate();
                    }
                    if (fecha.getHours() < 10) {
                        hora = "0" + fecha.getHours();
                    } else {
                        hora = fecha.getHours();
                    }
                    if (fecha.getMinutes() < 10) {
                        minutos = "0" + fecha.getMinutes();
                    } else {
                        minutos = fecha.getMinutes();
                    }
                    //var fechaActual = moment(fecha.getFullYear() +"-" +vMes +"-"+ vDia);
                    var fechaActual = moment($("#txtFechaActualA").val());

                    // var fechaFin = moment(contrato.fecha_fin).format('YYYY-MM-DD');
                    // var interes = contrato.interes;  
                    // var comision = contrato.comision;
                    // var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                    // var capital = contrato.capital;
                    // var fechaContrato = contrato.fecha_inio;

                    var valor_comparacion1 = 3499;
                    var valor_comparacion2 = 10000;
                    var valor_comparacion3 = 15000;
                    if (data.Resultado.moneda_id == 2) {
                        valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                        valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                        valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                    }

                    if (parseFloat(capital) <= valor_comparacion1) {
                        var totalInteresValor = (capital * 9.04) / 100;
                    } else if (parseFloat(capital) < valor_comparacion2) {
                        var totalInteresValor = (capital * 6.7) / 100;
                    } else if (parseFloat(capital) < valor_comparacion3) {
                        var totalInteresValor = (capital * 6) / 100;
                    }else{
                        var totalInteresValor = (capital * 5) / 100;
                    }

                    var fechaInteresInicial = '2020-03-22';
                    var fechaInteresFin = '2020-05-11';
                    var fechaInteresFinM = moment('2020-05-11');
                    console.log(fechaInteresFinM.diff(fechaInteresInicial, 'days'),
                        ' días de diferencia para descuneto interes');
                    console.log("fecha Inicial Interes", fechaInteresInicial);
                    console.log("fecha Final Interes", fechaInteresFin);
                    console.log("fecha fin", fechaFin);
                    console.log("fecha actual", fechaActual);
                    console.log(fechaActual.diff(fechaFin, 'days'), ' días de diferencia');
                    console.log("dias del mes actual", moment(fechaActual, "YYYY-MM").daysInMonth());
                    console.log("total Interes", totalInteresValor);
                    //var diaActual = moment(fechaActual, "YYYY-MM").daysInMonth();
                    var diaActual = 30;
                    var diaAtrasados = fechaActual.diff(fechaFin, 'days');

                    console.log("diasTranscurridos", diasTranscurridos);
                    console.log("diaAtrasados", diaAtrasados);

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");
                        // var diasDecuento = diaAtrasados
                        var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    } else {
                        console.log("intervalo NO ACEPATDO");
                        if (fechaFin > fechaInteresFin) {
                            console.log("FECHA MAYOR");
                            var diasDecuento = 0;
                        } else {
                            console.log("FECHA MENOR");
                            var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        }
                        //var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    }

                    if (diaAtrasados > 0) {
                        // var diaAtrasados1 = diaAtrasados - diasDecuento;
                        var calc = diaAtrasados - diasDecuento;
                        if (calc > 0) {
                            var diaAtrasados1 = diaAtrasados - diasDecuento;
                        } else {
                            var diaAtrasados1 = 0;
                        }
                        console.log("diaAtrasados1 valoo", diaAtrasados1);
                        console.log("totalInteresValor valoo", totalInteresValor);
                        console.log("diaActual valoo", diaActual);
                        var totalInteres = (parseFloat(totalInteresValor) / diaActual) * diaAtrasados1;
                        var cuotaMora = totalInteres;
                        console.log("Interes Calculado", totalInteres);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(totalInteresValor) +
                            parseFloat(amortizacion);
                        console.log("Total General", totalGeneral);
                        var mensaje = 'El contrato tiene <b>' + diaAtrasados + ' dias </b> de atraso,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) + ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de::<b>' + parseFloat(totalInteres).toFixed(2) + ' ' +
                            data.Resultado.desc_corta + '</b><br>' + 'La Amortización de <b>' + parseFloat(
                                amortizacion).toFixed(2) + ' ' + data.Resultado.desc_corta +
                            '</b> mas el interes  seria un total de: <b>' + parseFloat(totalGeneral).toFixed(
                            2) + ' ' + data.Resultado.desc_corta + ' a pagar</b>';
                        var valorDiasAtrasados = diaAtrasados;
                    } else {
                        var diaAtrasados1 = 0;
                        var fechaFin = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaActual = moment();
                        console.log("fechaActual", fechaActual);
                        console.log("fechaFin", fechaFin);
                        var diasRango = fechaActual.diff(fechaFin, 'days');
                        // var diasRango = 30;
                        var valorDiasAtrasados = diaAtrasados;
                        console.log("diasRango", diasRango);
                        var cuotaMora = 0;
                        // if (diasRango <= 15) {                   
                        //  var totalInteres = parseFloat(totalInteresValor) / 2;   
                        //  var totalGeneral = parseFloat(totalInteres) + parseFloat(amortizacion);
                        //  if (parseFloat(capital)<= 3499) {                       
                        //      var interes = ((capital/2) * 3)/100;
                        //      var comision = ((capital/2) * 7.4)/100;                     
                        //     }
                        //     else{                        
                        //          var interes = ((capital/2) * 3)/100;
                        //      var comision = ((capital/2) * 4.4)/100;                     
                        //     }

                        //  var mensaje = 'El contrato tiene <b>'+diasRango+' dias </b> habiles y es menor a 15 días,<br> ' +
                        //  'Tiene un capital de :<b>'+parseFloat(capital).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> con un interes de <b>'+parseFloat(totalInteresValor).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> <br>'+
                        //  'El Interes seria un total de: <b>'+totalInteres+' '+ data.Resultado.desc_corta+'</b><br>'+
                        //  'La Amortización de <b>'+parseFloat(amortizacion).toFixed(2)+' '+ data.Resultado.desc_corta+'</b> mas el interes  seria un total de: <b>'+parseFloat(totalGeneral).toFixed(2)+' '+ data.Resultado.desc_corta+' a pagar</b>'; 
                        // }
                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 6.04) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3.7) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }else{
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 2) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }

                        var totalInteres = parseFloat(totalInteresValor1 + interes);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(amortizacion);
                        var mensaje = 'El contrato tiene <b>' + diasRango + ' dias </b> habiles,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'La Amortización de <b>' + parseFloat(amortizacion).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> mas el interes  seria un total de: <b>' +
                            parseFloat(totalGeneral).toFixed(2) + ' ' + data.Resultado.desc_corta +
                            ' a pagar</b>';
                    }


                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");

                    } else {
                        console.log("intervalo NO ACEPATDO");
                        var fff = ((((parseFloat(interes) + parseFloat(comision)) / 30) * diasDecuento) * 70) /
                            100;

                    }

                    //$("#txtCodigoA").val(contrato.codigo);
                    if (contrato.codigo != "") {
                        $("#txtCodigoA").val(contrato.codigo);
                    } else {
                        $("#txtCodigoA").val(codigoContratoA);
                    }
                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");

                        var fechaFin3 = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaInteresInicial33 = moment('2020-03-22', 'YYYY/MM/DD');

                        var solodiaC = fechaInteresInicial33.diff(fechaFin3, 'days');
                        var solodiaC1 = fechaActual.diff(fechaInteresFinM, 'days');
                        console.log("solodiaC", solodiaC);
                        console.log("solodiaC11111", solodiaC1);

                        var diasMora = solodiaC + solodiaC1;

                        var interesDescuento = (parseFloat(interes) / 30) * diasMora;
                        var comisionDescuento = (parseFloat(comision) / 30) * diasMora;
                        //var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento);
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento) +
                            parseFloat(cutotaMoraDescuento);

                        totalGeneral = totalDescuento;

                    } else {
                        console.log("intervalo NO ACEPATDO");
                        var interesDescuento = 0;
                        var comisionDescuento = 0;
                        var totalDescuento = 0;
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        totalGeneral = totalGeneral + cutotaMoraDescuento;
                        console.log("ffff", cutotaMoraDescuento);
                    }
                    $("#txtCIA").val(contrato.cliente.persona.nrodocumento);
                    $("#txtNombresA").val(contrato.cliente.persona.nombres + ' ' + contrato.cliente.persona
                        .primerapellido + ' ' + contrato.cliente.persona.segundoapellido);
                    $("#txtDiasAtrasoA").val(valorDiasAtrasados);
                    $("#txtAtrasoDiasDescuentoA").val(diasDecuento);
                    $("#txtCobroDiasDescuentoA").val(diaAtrasados1);
                    $("#txtDiasTranscurridosA").val(diasTranscurridos);
                    // $("#txtInteresMoratorioID").val(parseFloat(fff).toFixed(2));

                    if(data.sw_amortizacion)
                    {
                        $('#mensajePagoAmortizacion').css('display','block');
                        // AFECTAR LOS VALORES DE INTERES Y GASTOS
                        if (moneda_pago == 1) {
                            interes = parseFloat(interes) - parseFloat(data.valor_amortizacion_interes_bs);
                            comision = parseFloat(comision) - parseFloat(data.valor_amortizacion_gastos_bs);
                        }else{
                            interes = parseFloat(interes) - parseFloat(data.valor_amortizacion_interes_sus);
                            comision
                            comision = parseFloat(comision) - parseFloat(data.valor_amortizacion_gastos_sus);
                        }

                        if(interes < 0)
                        {
                            interes = 0;
                        }

                        if(comision < 0)
                        {
                            comision = 0;
                        }
                        totalGeneral = parseFloat(interes) + parseFloat(comision) + parseFloat(cutotaMoraDescuento);
                    }

                    if (moneda_pago == 1) {
                        $("#txtMontoTotalA").val(parseFloat(contrato.total_capital).toFixed(2));
                        $("#txtMontoTotalA2").val((parseFloat(contrato.total_capital) / data.cambioMonedas
                            .valor_bs).toFixed(2));

                        $("#txtInteresFechaA").val(parseFloat(interes).toFixed(2));
                        $("#txtInteresFechaA2").val(parseFloat(interes / data.cambioMonedas.valor_bs).toFixed(
                            2));

                        $("#txtInteresFechaAD").val(parseFloat(interesDescuento).toFixed(2));
                        $("#txtInteresFechaAD2").val(parseFloat(interesDescuento / data.cambioMonedas.valor_bs)
                            .toFixed(2));

                        $("#txtGastosAdministrativosA").val(parseFloat(comision).toFixed(2));
                        $("#txtGastosAdministrativosA2").val(parseFloat(comision / data.cambioMonedas.valor_bs)
                            .toFixed(2));

                        $("#txtGastosAdministrativosAD").val(parseFloat(comisionDescuento).toFixed(2));
                        $("#txtGastosAdministrativosAD2").val(parseFloat(comisionDescuento / data.cambioMonedas
                            .valor_bs).toFixed(2));

                        $("#txtInteresMoratorioA").val(parseFloat(cuotaMora - data.suma_moratorios_bs).toFixed(2));
                        $("#txtInteresMoratorioA2").val(parseFloat((cuotaMora / data.cambioMonedas.valor_bs) - data.suma_moratorios_sus)
                            .toFixed(2));

                        $("#txtInteresMoratorioAD").val(parseFloat(cutotaMoraDescuento).toFixed(2));
                        $("#txtInteresMoratorioAD2").val(parseFloat(cutotaMoraDescuento / data.cambioMonedas
                            .valor_bs).toFixed(2));

                        $("#txtTotalA").val(parseFloat(totalGeneral).toFixed(2));
                        $("#txtTotalA2").val(parseFloat(totalGeneral / data.cambioMonedas.valor_bs).toFixed(2));

                        $("#txtCapitalPagoTotalA").val(parseFloat(capital).toFixed(2));
                        $("#txtCapitalPagoTotalA2").val(parseFloat(capital / data.cambioMonedas.valor_bs)
                            .toFixed(2));
                    } else {
                        $("#txtMontoTotalA").val((parseFloat(contrato.total_capital) * data.cambioMonedas
                            .valor_bs).toFixed(2));
                        $("#txtMontoTotalA2").val(parseFloat(contrato.total_capital).toFixed(2));

                        $("#txtInteresFechaA").val(parseFloat(interes * data.cambioMonedas.valor_bs).toFixed(
                        2));
                        $("#txtInteresFechaA2").val(parseFloat(interes).toFixed(2));

                        $("#txtInteresFechaAD").val(parseFloat(interesDescuento * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtInteresFechaAD2").val(parseFloat(interesDescuento).toFixed(2));

                        $("#txtGastosAdministrativosA").val(parseFloat(comision * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtGastosAdministrativosA2").val(parseFloat(comision).toFixed(2));

                        $("#txtGastosAdministrativosAD").val(parseFloat(comisionDescuento * data.cambioMonedas
                            .valor_bs).toFixed(2));
                        $("#txtGastosAdministrativosAD2").val(parseFloat(comisionDescuento).toFixed(2));

                        $("#txtInteresMoratorioA").val(parseFloat((cuotaMora * data.cambioMonedas.valor_bs) - data.suma_moratorios_bs).toFixed(2));
                        $("#txtInteresMoratorioA2").val(parseFloat(cuotaMora - data.suma_moratorios_sus).toFixed(2));

                        $("#txtInteresMoratorioAD").val(parseFloat(cutotaMoraDescuento * data.cambioMonedas
                            .valor_bs).toFixed(2));
                        $("#txtInteresMoratorioAD2").val(parseFloat(cutotaMoraDescuento).toFixed(2));

                        $("#txtTotalA").val(parseFloat(totalGeneral * data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtTotalA2").val(parseFloat(totalGeneral).toFixed(2));

                        $("#txtCapitalPagoTotalA").val(parseFloat(capital * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtCapitalPagoTotalA2").val(parseFloat(capital).toFixed(2));
                    }
                    $('#txtMonedaA').val(data.Resultado.desc_corta);
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

        function fnDatoPagoAmortizacionInteres(contrato) {
            $("#txtAmortizacionInteres").val('');
            document.getElementById('divMostrarPagoAmortizacionInteres').style.display = 'block';
            // $('.clsContratos').hide();
            if ($('#ddlTipoBusqueda').val() == 'CI') {
                $('.clsContratos').hide();
            }
            if ($('#ddlTipoBusqueda').val() == 'CO') {
                $('.clsCodigo').hide();
            }
            $('.clsDatoPagoAmortizacionInteres').show();
            globalContratoJson = contrato;
            var idContrato = contrato.id;
            var amortizacion = 0;
            var fechaContratoCodigo = new Date(contrato.fecha_contrato);
            var codigoContratoA = contrato.sucural.nuevo_codigo + '' + fechaContratoCodigo.getFullYear().toString().substr(-
                2) + '' + contrato.codigo_num;
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {

                    let txtBs = data.txtBs;
                    let txtSus = data.txtSus;
                    let cambioMonedas = data.cambioMonedas;
                    global_cambioMonedas = cambioMonedas;
                    $('._txtBs').text(txtBs.desc_corta);
                    $('._txtSus').text(txtSus.desc_corta);

                    let moneda_pago = data.Resultado.moneda_id
                    global_monedaContrato = moneda_pago;
                    var fechaActual = moment();
                    globalInteresAmor = data.Resultado.p_interes;
                    console.log("DATAAA", data);
                    var p_interes = contrato.interes;
                    var interes = contrato.interes;
                    var comision = contrato.comision;
                    var fechaFin = moment(contrato.fecha_fin).format('YYYY-MM-DD');
                    var capital = contrato.capital;
                    var fechaContrato = contrato.fecha_contrato;
                    if (data != "") {
                        var fechaFin = moment(data.Resultado.fecha_fin).format('YYYY-MM-DD');
                        var fechaContrato = data.Resultado.fecha_inio;
                        var capital = contrato.capital;
                    }

                    var fechaEmision = moment(fechaContrato, 'YYYY/MM/DD');
                    var fechaExpiracion = moment(fechaActual, 'YYYY/MM/DD');
                    var diasTranscurridos = fechaExpiracion.diff(fechaEmision, 'days');

                    $("#txtVenceElAI").val(fechaFin);
                    var fecha = new Date();
                    var vDia;
                    var vMes;
                    if ((fecha.getMonth() + 1) < 10) {
                        vMes = "0" + (fecha.getMonth() + 1);
                    } else {
                        vMes = (fecha.getMonth() + 1);
                    }
                    if (fecha.getDate() < 10) {
                        vDia = "0" + fecha.getDate();
                    } else {
                        vDia = fecha.getDate();
                    }
                    if (fecha.getHours() < 10) {
                        hora = "0" + fecha.getHours();
                    } else {
                        hora = fecha.getHours();
                    }
                    if (fecha.getMinutes() < 10) {
                        minutos = "0" + fecha.getMinutes();
                    } else {
                        minutos = fecha.getMinutes();
                    }
                    var fechaActual = moment($("#txtFechaActualAI").val());

                    var fechaActual = moment($("#txtFechaActualI").val());
                    if (data == "") {
                        console.log("datosss vacios");
                        var fechaFin = moment(contrato.fecha_fin).add(1, 'days').format('YYYY-MM-DD');
                        var interes = contrato.interes;
                        var comision = contrato.comision;
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        var capital = contrato.capital;
                        var fechaContrato = contrato.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }

                    } else {
                        console.log("con detalle", data.Resultado);
                        // var fechaFin = moment(data.Resultado.fecha_fin).add(1,'days').format('YYYY-MM-DD');          
                        var fechaFin = moment(data.Resultado.fecha_fin).format('YYYY-MM-DD');
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        // var capital = data.Resultado.capital;
                        var fechaContrato = data.Resultado.fecha_inio;
                        // var capital = data.Resultado.capital;
                        var capital = contrato.capital;
                        //var fechaContrato = data.Resultado.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }
                    }

                    $("#txtVenceElAI").val(fechaFin);

                    var fechaInteresInicial = '2020-03-22';
                    var fechaInteresFin = '2020-05-11';
                    var fechaInteresFinM = moment('2020-05-11');
                    console.log(fechaInteresFinM.diff(fechaInteresInicial, 'days'),
                        ' días de diferencia para descuneto interes');
                    console.log("fecha Inicial Interes", fechaInteresInicial);
                    console.log("fecha Final Interes", fechaInteresFin);
                    console.log("fecha fin", fechaFin);
                    console.log("fecha actual", fechaActual);
                    console.log(fechaActual.diff(fechaFin, 'days'), ' días de diferencia');
                    console.log("dias del mes actual", moment(fechaActual, "YYYY-MM").daysInMonth());
                    console.log("total Interes", totalInteresValor);
                    //var diaActual = moment(fechaActual, "YYYY-MM").daysInMonth();
                    var diaActual = 30;

                    var diaAtrasados = fechaActual.diff(fechaFin, 'days');

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");
                        // var diasDecuento = diaAtrasados
                        var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    } else {
                        console.log("intervalo NO ACEPATDO");
                        if (fechaFin > fechaInteresFin) {
                            console.log("FECHA MAYOR");
                            var diasDecuento = 0;
                        } else {
                            console.log("FECHA MENOR");
                            var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        }
                        //var diasDecuento = fechaInteresFinM.diff(fechaInteresInicial, 'days');
                        console.log("diasDecuento", diasDecuento);
                    }

                    var fecha1 = new Date(fechaContrato);
                    var fecha2 = new Date(fechaActual);
                    var diasDif = fecha2.getTime() - fecha1.getTime();
                    //var diasTranscurridos = Math.round(diasDif/(1000 * 60 * 60 * 24)) + 1;
                    var fechaEmision = moment(fechaContrato, 'YYYY/MM/DD');
                    var fechaExpiracion = moment(fechaActual, 'YYYY/MM/DD');
                    //var diasTranscurridos = Math.round(diasDif/(1000 * 60 * 60 * 24)) + 1;
                    var diasTranscurridos = fechaExpiracion.diff(fechaEmision, 'days');
                    console.log("diasTranscurridos", diasTranscurridos);

                    console.log("diaAtrasados", diaAtrasados);
                    if (diaAtrasados > 0) {
                        var calc = diaAtrasados - diasDecuento;
                        if (calc > 0) {
                            var diaAtrasados1 = diaAtrasados - diasDecuento;
                        } else {
                            var diaAtrasados1 = 0;
                        }

                        // var diaAtrasados1 = diaAtrasados;
                        console.log("diaAtrasados1 valoo", diaAtrasados1);
                        console.log("totalInteresValor valoo", totalInteresValor);
                        console.log("diaActual valoo", diaActual);
                        var totalInteres = (parseFloat(totalInteresValor) / diaActual) * diaAtrasados1;
                        var cuotaMora = totalInteres;
                        console.log("Interes Calculado", totalInteres);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(totalInteresValor);
                        console.log("Total General", totalGeneral);
                        var mensaje = 'El contrato tiene <b>' + diaAtrasados + ' dias </b> de atraso,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de::<b>' + parseFloat(totalInteres).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El total del interes a pagar es de:<b>' + parseFloat(totalGeneral).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b>';
                        var valorDiasAtrasados = diaAtrasados;
                    } else {
                        var diaAtrasados1 = 0;
                        var fechaFin = moment(fechaContrato, 'YYYY/MM/DD');
                        console.log("fechaFin111 VALO", fechaFin);
                        var fechaActual = moment();
                        console.log("fechaActual111 VALO", fechaActual);
                        var diasRango = fechaActual.diff(fechaFin, 'days');
                        var valorDiasAtrasados = diaAtrasados;
                        console.log("diasRango VALO", diasRango);
                        var cuotaMora = 0;
                        // if (diasRango <= 15) {                   
                        //  var totalInteres = parseFloat(totalInteresValor) / 2;   
                        //  var totalGeneral = parseFloat(totalInteres);    
                        //  if (parseFloat(capital)<= 3499) {                       
                        //      var interes = ((capital/2) * 3)/100;
                        //      var comision = ((capital/2) * 7.4)/100;                     
                        //     }
                        //     else{                        
                        //          var interes = ((capital/2) * 3)/100;
                        //      var comision = ((capital/2) * 4.4)/100;                     
                        //     }                        
                        //  var mensaje = 'El contrato tiene <b>'+diasRango+' dias </b> habiles y es menor a 15 días,<br> ' +
                        //  'Tiene un capital de :<b>'+parseFloat(capital).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> con un interes de <b>'+parseFloat(totalInteresValor).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> <br>'+
                        //  'El Interes seria un total de: <b>'+totalInteres+' '+ data.Resultado.desc_corta+'</b><br>'+
                        //  'El total del interes a pagar es de:<b>'+totalGeneral+' '+ data.Resultado.desc_corta+'</b>'; 
                        // }
                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 6.04) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;

                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3.7) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }else{
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 2) / 100;
                            var totalInteresValor1 = (parseFloat(comision1) / diaActual) * diasRango;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }

                        var totalInteres = parseFloat(totalInteresValor1);
                        var totalGeneral = parseFloat(totalInteres + interes);
                        var mensaje = 'El contrato tiene <b>' + diasRango + ' dias </b> habiles,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(2) + ' ' +
                            data.Resultado.desc_corta + '</b><br>' + 'El total del interes a pagar es de:<b>' +
                            parseFloat(totalGeneral).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b>';
                    }

                    console.log("interes", interes);
                    console.log("comision", comision);

                    if (fechaInteresInicial <= fechaFin && fechaFin <= fechaInteresFin) {
                        console.log("intervalo ACEPATDO");

                        var fechaFin3 = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaInteresInicial33 = moment('2020-03-22', 'YYYY/MM/DD');

                        var solodiaC = fechaInteresInicial33.diff(fechaFin3, 'days');
                        var solodiaC1 = fechaActual.diff(fechaInteresFinM, 'days');
                        console.log("solodiaC", solodiaC);
                        console.log("solodiaC11111", solodiaC1);

                        var diasMora = solodiaC + solodiaC1;


                        var interesDescuento = (parseFloat(interes) / 30) * diasMora;
                        var comisionDescuento = (parseFloat(comision) / 30) * diasMora;

                        //var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento);
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        // cutotaMoraDescuento = cutotaMoraDescuento + interesDescuento1 + comisionDescuento1;
                        var totalDescuento = parseFloat(interesDescuento) + parseFloat(comisionDescuento) +
                            parseFloat(cutotaMoraDescuento);

                        totalGeneral = totalDescuento;

                    } else {
                        console.log("intervalo NO ACEPATDO");
                        var interesDescuento = 0;
                        var comisionDescuento = 0;
                        var totalDescuento = 0;
                        var cutotaMoraDescuento = ((((parseFloat(interes) + parseFloat(comision)) / 30) *
                            diasDecuento) * 70) / 100;
                        totalGeneral = totalGeneral + cutotaMoraDescuento;
                        console.log("ffff", cutotaMoraDescuento);
                    }

                    if(data.sw_amortizacion)
                    {
                        $('#mensajePagoAmortizacionInteres').css('display','block');
                        // AFECTAR LOS VALORES DE INTERES Y GASTOS
                        if (moneda_pago == 1) {
                            interes = parseFloat(interes) - parseFloat(data.valor_amortizacion_interes_bs);
                            comision = parseFloat(comision) - parseFloat(data.valor_amortizacion_gastos_bs);
                        }else{
                            interes = parseFloat(interes) - parseFloat(data.valor_amortizacion_interes_sus);
                            comision
                            comision = parseFloat(comision) - parseFloat(data.valor_amortizacion_gastos_sus);
                        }

                        if(interes < 0)
                        {
                            interes = 0;
                        }

                        if(comision < 0)
                        {
                            comision = 0;
                        }
                    }

                    if (moneda_pago == 1) {
                        $("#txtInteresFechaAI").attr('data-val',parseFloat(interes).toFixed(2));
                        $("#txtInteresFechaAI2").attr('data-val',parseFloat(interes / data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtInteresFechaAI").val(parseFloat(interes).toFixed(2));
                        $("#txtInteresFechaAI2").val(parseFloat(interes / data.cambioMonedas.valor_bs).toFixed(2));

                        $("#txtGastosAdministrativosAI").attr('data-val',parseFloat(comision).toFixed(2));
                        $("#txtGastosAdministrativosAI2").attr('data-val',parseFloat(comision / data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtGastosAdministrativosAI").val(parseFloat(comision).toFixed(2));
                        $("#txtGastosAdministrativosAI2").val(parseFloat(comision / data.cambioMonedas.valor_bs).toFixed(2));

                        $("#txtInteresMoratorioAI").attr('data-val',parseFloat(cuotaMora - data.suma_moratorios_bs).toFixed(2));
                        $("#txtInteresMoratorioAI2").attr('data-val',parseFloat((cuotaMora / data.cambioMonedas.valor_bs) - data.suma_moratorios_sus).toFixed(2));
                        $("#txtInteresMoratorioAI").val(parseFloat(cuotaMora - data.suma_moratorios_bs).toFixed(2));
                        $("#txtInteresMoratorioAI2").val(parseFloat((cuotaMora / data.cambioMonedas.valor_bs) - data.suma_moratorios_sus).toFixed(2));
                        
                        $("#txtTotalAI").val(parseFloat('0.00').toFixed(2));
                        $("#txtTotalAI2").val(parseFloat('0.00').toFixed(2));

                        $("#txtCapitalPagoTotalAI").val(parseFloat(capital).toFixed(2));
                        $("#txtCapitalPagoTotalAI2").val(parseFloat(capital / data.cambioMonedas.valor_bs).toFixed(2));

                        $("#txtMontoTotalAI").val((parseFloat($("#txtCapitalPagoTotalAI").val()) + parseFloat($("#txtInteresFechaAI").val()) + parseFloat($('#txtGastosAdministrativosAI').val()) + parseFloat($("#txtInteresMoratorioAI").val())).toFixed(2));
                        $("#txtMontoTotalAI2").val((parseFloat($("#txtCapitalPagoTotalAI2").val()) + parseFloat($("#txtInteresFechaAI2").val()) + parseFloat($('#txtGastosAdministrativosAI2').val()) + parseFloat($("#txtInteresMoratorioAI2").val())).toFixed(2));
                    } else {
                        $("#txtInteresFechaAI").attr('data-val',parseFloat(interes * data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtInteresFechaAI2").attr('data-val',parseFloat(interes).toFixed(2));
                        $("#txtInteresFechaAI").val(parseFloat(interes * data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtInteresFechaAI2").val(parseFloat(interes).toFixed(2));

                        $("#txtInteresMoratorioAI").attr('data-val',parseFloat((cuotaMora * data.cambioMonedas.valor_bs) - data.suma_moratorios_bs).toFixed(2));
                        $("#txtInteresMoratorioAI2").attr('data-val',parseFloat(cuotaMora - data.suma_moratorios_sus).toFixed(2));
                        $("#txtInteresMoratorioAI").val(parseFloat((cuotaMora * data.cambioMonedas.valor_bs) - data.suma_moratorios_bs).toFixed(2));
                        $("#txtInteresMoratorioAI2").val(parseFloat(cuotaMora - data.suma_moratorios_sus).toFixed(2));

                        $("#txtGastosAdministrativosAI").attr('data-val',parseFloat(comision * data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtGastosAdministrativosAI2").attr('data-val',parseFloat(comision).toFixed(2));
                        $("#txtGastosAdministrativosAI").val(parseFloat(comision * data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtGastosAdministrativosAI2").val(parseFloat(comision).toFixed(2));

                        $("#txtTotalAI").val(parseFloat('0.00').toFixed(2));
                        $("#txtTotalAI2").val(parseFloat('0.00').toFixed(2));

                        $("#txtCapitalPagoTotalAI").val(parseFloat(capital * data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtCapitalPagoTotalAI2").val(parseFloat(capital).toFixed(2));
                        
                        $("#txtMontoTotalAI").val((parseFloat($("#txtCapitalPagoTotalAI").val()) + parseFloat($("#txtInteresFechaAI").val()) + parseFloat($('#txtGastosAdministrativosAI').val()) + parseFloat($("#txtInteresMoratorioAI").val())).toFixed(2));
                        $("#txtMontoTotalAI2").val((parseFloat($("#txtCapitalPagoTotalAI2").val()) + parseFloat($("#txtInteresFechaAI2").val()) + parseFloat($('#txtGastosAdministrativosAI2').val()) + parseFloat($("#txtInteresMoratorioAI2").val())).toFixed(2));
                    }
                    $('#txtMonedaA').val(data.Resultado.desc_corta);
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

        function fnVolverContratos() {
            document.getElementById('divMostrarPagoTotal').style.display = 'none';
            document.getElementById('divMostrarPagoInteres').style.display = 'none';
            document.getElementById('divMostrarPagoAmortizacion').style.display = 'none';
            document.getElementById('divMostrarPagoAmortizacionInteres').style.display = 'none';
            document.getElementById('divMostrarRemate').style.display = 'none';
            $('.clsContratos').empty();
            // document.getElementById('divMostrarBusquedaCodigo').style.display = 'none';
            $('.clsPers').show();
        }

        function fnVolver() {
            // document.getElementById('divMostrarPagoTotal').style.display = 'none';
            // document.getElementById('divMostrarPagoInteres').style.display = 'none';
            // document.getElementById('divMostrarPagoAmortizacion').style.display = 'none';
            // document.getElementById('divMostrarRemate').style.display = 'none';          
            $('.clsClientes').show();
            $('.clsContratos').hide();

        }

        function fnDatoRemate(contrato) {
            document.getElementById('divMostrarRemate').style.display = 'block';
            document.getElementById('divMostrarPagoTotal').style.display = 'none';
            document.getElementById('divMostrarPagoInteres').style.display = 'none';
            $('.clsDatoRemate').show();
            // $('.clsContratos').hide();
            if ($('#ddlTipoBusqueda').val() == 'CI') {
                $('.clsContratos').hide();
            }
            if ($('#ddlTipoBusqueda').val() == 'CO') {
                $('.clsCodigo').hide();
            }
            $('.clsDatoPagoTotal').show();
            globalContratoJson = contrato;
            console.log("contrato", contrato);
            console.log("contrato", contrato.capital);
            var fechaContratoCodigo = new Date(contrato.fecha_contrato);
            var codigoContratoR = contrato.sucural.nuevo_codigo + '' + fechaContratoCodigo.getFullYear().toString().substr(-
                2) + '' + contrato.codigo_num;
            var idContrato = contrato.id;
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    let txtBs = data.txtBs;
                    let txtSus = data.txtSus;
                    let cambioMonedas = data.cambioMonedas;
                    global_cambioMonedas = cambioMonedas;
                    $('._txtBs').text(txtBs.desc_corta);
                    $('._txtSus').text(txtSus.desc_corta);

                    let moneda_pago = data.Resultado.moneda_id
                    global_monedaContrato = moneda_pago;

                    var fecha = new Date();
                    var vDia;
                    var vMes;
                    if ((fecha.getMonth() + 1) < 10) {
                        vMes = "0" + (fecha.getMonth() + 1);
                    } else {
                        vMes = (fecha.getMonth() + 1);
                    }
                    if (fecha.getDate() < 10) {
                        vDia = "0" + fecha.getDate();
                    } else {
                        vDia = fecha.getDate();
                    }
                    if (fecha.getHours() < 10) {
                        hora = "0" + fecha.getHours();
                    } else {
                        hora = fecha.getHours();
                    }
                    if (fecha.getMinutes() < 10) {
                        minutos = "0" + fecha.getMinutes();
                    } else {
                        minutos = fecha.getMinutes();
                    }
                    //var fechaActual = moment(fecha.getFullYear() +"-" +vMes +"-"+ vDia);
                    var fechaActual = moment($("#txtFechaActualR").val());
                    if (data == "") {
                        console.log("datosss vacios");
                        var fechaFin = moment(contrato.fecha_fin).format('YYYY-MM-DD');
                        var interes = contrato.interes;
                        var comision = contrato.comision;
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        var capital = contrato.capital;
                        var fechaContrato = contrato.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }

                    } else {
                        console.log("con detalle", data.Resultado);
                        var fechaFin = moment(data.Resultado.fecha_fin).format('YYYY-MM-DD');
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        // var capital = data.Resultado.capital;
                        var fechaContrato = data.Resultado.fecha_inio;
                        // var capital = data.Resultado.capital;
                        var capital = contrato.capital;
                        //var fechaContrato = data.Resultado.fecha_contrato;
                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }
                    }

                    console.log("fecha fin", fechaFin);
                    $("#txtVenceEl").val(fechaFin);
                    console.log("fecha actual", fechaActual);
                    console.log(fechaActual.diff(fechaFin, 'days'), ' días de diferencia');
                    console.log("dias del mes actual", moment(fechaActual, "YYYY-MM").daysInMonth());
                    console.log("total Interes", totalInteresValor);
                    //var diaActual = moment(fechaActual, "YYYY-MM").daysInMonth();
                    var diaActual = 30;
                    var diaAtrasados = fechaActual.diff(fechaFin, 'days');

                    var fecha1 = new Date(fechaContrato);
                    var fecha2 = new Date(fechaActual);
                    var diasDif = fecha2.getTime() - fecha1.getTime();
                    var fechaEmision = moment(fechaContrato, 'YYYY/MM/DD');
                    var fechaExpiracion = moment(fechaActual, 'YYYY/MM/DD');
                    //var diasTranscurridos = Math.round(diasDif/(1000 * 60 * 60 * 24)) + 1;
                    var diasTranscurridos = fechaExpiracion.diff(fechaEmision, 'days');
                    console.log("diasTranscurridos", diasTranscurridos);

                    console.log("diaAtrasados", diaAtrasados);
                    if (diaAtrasados > 0) {
                        var totalInteres = (parseFloat(totalInteresValor) / diaActual) * diaAtrasados;
                        var cuotaMora = totalInteres;
                        console.log("Interes Calculado", totalInteres);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(capital) + parseFloat(
                            totalInteresValor);
                        console.log("Total General", totalGeneral);
                        var mensaje = 'El contrato tiene <b>' + diaAtrasados + ' dias </b> de atraso,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) + ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'Por lo tanto el Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(
                                2) + ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El Capital mas el interes seria un total de: <b>' + parseFloat(totalGeneral)
                            .toFixed(2) + ' ' + data.Resultado.desc_corta + '</b>';
                        var valorDiasAtrasados = diaAtrasados;
                    } else {
                        var fechaFin = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaActual = moment();
                        var diasRango = fechaActual.diff(fechaFin, 'days');
                        var valorDiasAtrasados = diaAtrasados;
                        console.log("diasRango", diasRango);
                        var cuotaMora = 0;
                        // if (diasRango <= 15) {                   
                        //  var totalInteres = parseFloat(totalInteresValor) / 2;   
                        //  var totalGeneral = parseFloat(totalInteres) + parseFloat(capital);  
                        //  if (parseFloat(capital)<= 3499) {                       
                        //      var interes = ((capital/2) * 3)/100;
                        //      var comision = ((capital/2) * 7.4)/100;                     
                        //     }
                        //     else{                        
                        //          var interes = ((capital/2) * 3)/100;
                        //      var comision = ((capital/2) * 4.4)/100;                     
                        //     }                        
                        //  var mensaje = 'El contrato tiene <b>'+diasRango+' dias </b> habiles y es menor a 15 días,<br> ' +
                        //  'Tiene un capital de :<b>'+parseFloat(capital).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> con un interes de <b>'+parseFloat(totalInteresValor).toFixed(2) +' '+ data.Resultado.desc_corta+'</b> <br>'+
                        //  'El Interes seria un total de: <b>'+totalInteres+' '+ data.Resultado.desc_corta+'</b><br>'+
                        //  'El Capital mas el interes seria un total de: <b>'+totalGeneral+' '+ data.Resultado.desc_corta+'</b>'; 
                        // }
                        // else{
                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 6.04) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3.7) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }else{
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 2) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }

                        var totalInteres = parseFloat(totalInteresValor1);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(capital);
                        var mensaje = 'El contrato tiene <b>' + diasRango + ' dias </b> habiles,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) + ' ' + data.Resultado
                            .desc_corta + '</b> con un interes de <b>' + parseFloat(totalInteresValor).toFixed(
                                2) + ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El Capital mas el interes seria un total de: <b>' + parseFloat(totalGeneral)
                            .toFixed(2) + ' ' + data.Resultado.desc_corta + '</b>';
                        // }
                    }

                    console.log("interes", interes);
                    console.log("comision", comision);
                    console.log("capital", capital);

                    //$("#txtCodigoR").val(contrato.codigo);
                    if (contrato.codigo != "") {
                        $("#txtCodigoR").val(contrato.codigo);
                    } else {
                        $("#txtCodigoR").val(codigoContratoR);
                    }
                    $("#txtCIR").val(contrato.cliente.persona.nrodocumento);
                    $("#txtNombresR").val(contrato.cliente.persona.nombres + ' ' + contrato.cliente.persona
                        .primerapellido + ' ' + contrato.cliente.persona.segundoapellido);
                    $("#txtDiasAtrasoR").val(valorDiasAtrasados);
                    $("#txtDiasTranscurridosR").val(diasTranscurridos);
                    if (moneda_pago == 1) {
                        $("#txtMontoTotalR").val(parseFloat(contrato.total_capital).toFixed(2));
                        $("#txtMontoTotalR2").val((parseFloat(contrato.total_capital) / data.cambioMonedas
                            .valor_bs).toFixed(2));

                        $("#txtInteresFechaR").val(parseFloat(interes).toFixed(2));
                        $("#txtInteresFechaR2").val(parseFloat(interes / data.cambioMonedas.valor_bs).toFixed(
                            2));

                        $("#txtGastosAdministrativosR").val(parseFloat(comision).toFixed(2));
                        $("#txtGastosAdministrativosR2").val(parseFloat(comision / data.cambioMonedas.valor_bs)
                            .toFixed(2));

                        $("#txtInteresMoratorioR").val(parseFloat(cuotaMora).toFixed(2));
                        $("#txtInteresMoratorioR2").val(parseFloat(cuotaMora / data.cambioMonedas.valor_bs)
                            .toFixed(2));

                        $("#txtTotalR").val(parseFloat(totalGeneral).toFixed(2));
                        $("#txtTotalR2").val(parseFloat(totalGeneral / data.cambioMonedas.valor_bs).toFixed(2));

                        $("#txtCapitalPagoTotalR").val(parseFloat(capital).toFixed(2));
                        $("#txtCapitalPagoTotalR2").val(parseFloat(capital / data.cambioMonedas.valor_bs)
                            .toFixed(2));
                    } else {
                        $("#txtMontoTotalR").val((parseFloat(contrato.total_capital) * data.cambioMonedas
                            .valor_bs).toFixed(2));
                        $("#txtMontoTotalR2").val(parseFloat(contrato.total_capital).toFixed(2));

                        $("#txtInteresFechaR").val(parseFloat(interes * data.cambioMonedas.valor_bs).toFixed(
                        2));
                        $("#txtInteresFechaR2").val(parseFloat(interes).toFixed(2));

                        $("#txtGastosAdministrativosR").val(parseFloat(comision * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtGastosAdministrativosR2").val(parseFloat(comision).toFixed(2));

                        $("#txtInteresMoratorioR").val(parseFloat(cuotaMora * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtInteresMoratorioR2").val(parseFloat(cuotaMora).toFixed(2));

                        $("#txtTotalR").val(parseFloat(totalGeneral * data.cambioMonedas.valor_bs).toFixed(2));
                        $("#txtTotalR2").val(parseFloat(totalGeneral).toFixed(2));

                        $("#txtCapitalPagoTotalR").val(parseFloat(capital * data.cambioMonedas.valor_bs)
                            .toFixed(2));
                        $("#txtCapitalPagoTotalR2").val(parseFloat(capital).toFixed(2));
                    }
                    $('#txtMonedaR').val(data.Resultado.desc_corta);
                },
                beforeSend: function() {
                    $('.loader').addClass('loader-default  is-active');

                },
                complete: function() {
                    $('.loader').removeClass('loader-default  is-active');
                },
                error: function(error) {
                    swal({
                        title: "ERROR PAGO...",
                        text: "Los busqueda no se puede cargar",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: 'Aceptar',
                        type: "error"
                    });
                }
            });
        }

        /******************************** PAGAR PAGO TOTAL *****************************************/
        function fnPagarRemateContrato(contrato) {
            existe_pago_total = false;
            console.log("contrato", contrato);
            var contrato = globalContratoJson;
            var idContrato = contrato.id;
            var parametros = {
                'idContrato': idContrato
            };
            var route = "/BuscarPagosDetalleUltimo";
            $.ajax({
                url: route,
                data: parametros,
                method: 'GET',
                //dataType:'json',
                success: function(data) {
                    var fecha = new Date();
                    var vDia;
                    var vMes;
                    if ((fecha.getMonth() + 1) < 10) {
                        vMes = "0" + (fecha.getMonth() + 1);
                    } else {
                        vMes = (fecha.getMonth() + 1);
                    }
                    if (fecha.getDate() < 10) {
                        vDia = "0" + fecha.getDate();
                    } else {
                        vDia = fecha.getDate();
                    }
                    if (fecha.getHours() < 10) {
                        hora = "0" + fecha.getHours();
                    } else {
                        hora = fecha.getHours();
                    }
                    if (fecha.getMinutes() < 10) {
                        minutos = "0" + fecha.getMinutes();
                    } else {
                        minutos = fecha.getMinutes();
                    }
                    //var fechaActual = moment(fecha.getFullYear() +"-" +vMes +"-"+ vDia);
                    var fechaActual = moment($("#txtFechaActualR").val());
                    if (data == "") {
                        console.log("datosss vacios");
                        var fechaFin = moment(contrato.fecha_fin).format('YYYY-MM-DD');
                        var interes = contrato.interes;
                        var comision = contrato.comision;
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        var capital = contrato.capital;
                        var fechaContrato = contrato.fecha_contrato;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }

                    } else {
                        console.log("con detalle", data.Resultado);
                        var fechaFin = moment(data.Resultado.fecha_fin).format('YYYY-MM-DD');
                        //var totalInteresValor =  parseFloat(interes) +  parseFloat(comision);
                        // var capital = data.Resultado.capital;
                        var fechaContrato = data.Resultado.fecha_inio;
                        // var capital = data.Resultado.capital;
                        var capital = contrato.capital;
                        //var fechaContrato = data.Resultado.fecha_contrato;
                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor = (capital * 9.04) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 6.04) / 100;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor = (capital * 6.7) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3.7) / 100;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor = (capital * 6) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 3) / 100;
                        }else{
                            var totalInteresValor = (capital * 5) / 100;
                            var interes = (capital * data.Resultado.p_interes) / 100;
                            var comision = (capital * 2) / 100;
                        }
                    }

                    console.log("fecha fin", fechaFin);
                    console.log("fecha actual", fechaActual);
                    console.log(fechaActual.diff(fechaFin, 'days'), ' días de diferencia');
                    console.log("dias del mes actual", moment(fechaActual, "YYYY-MM").daysInMonth());
                    console.log("total Interes", totalInteresValor);
                    //var diaActual = moment(fechaActual, "YYYY-MM").daysInMonth();
                    var diaActual = 30;
                    var diaAtrasados = fechaActual.diff(fechaFin, 'days');
                    console.log("diaAtrasados", diaAtrasados);
                    if (diaAtrasados > 0) {
                        var totalInteres = (parseFloat(totalInteresValor) / diaActual) * diaAtrasados;
                        var cuotaMora = totalInteres;
                        console.log("Interes Calculado", totalInteres);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(capital) + parseFloat(
                            totalInteresValor);
                        console.log("Total General", totalGeneral);
                        var mensaje = 'El contrato tiene <b>' + diaAtrasados + ' dias </b> de atraso,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) + ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'Por lo tanto el Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(
                                2) + ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El Capital mas el interes seria un total de: <b>' + parseFloat(totalGeneral)
                            .toFixed(2) + ' ' + data.Resultado.desc_corta + '</b>';
                        var valorDiasAtrasados = diaAtrasados;
                    } else {
                        var fechaFin = moment(fechaContrato, 'YYYY/MM/DD');
                        var fechaActual = moment();
                        var diasRango = fechaActual.diff(fechaFin, 'days');
                        var valorDiasAtrasados = diaAtrasados;
                        console.log("diasRango", diasRango);
                        var cuotaMora = 0;

                        var valor_comparacion1 = 3499;
                        var valor_comparacion2 = 10000;
                        var valor_comparacion3 = 15000;
                        if (data.Resultado.moneda_id == 2) {
                            valor_comparacion1 = 3499 / data.cambioMonedas.valor_bs;
                            valor_comparacion2 = 10000 / data.cambioMonedas.valor_bs;
                            valor_comparacion3 = 15000 / data.cambioMonedas.valor_bs;
                        }

                        if (parseFloat(capital) <= valor_comparacion1) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 6.04) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion2) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3.7) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        } else if (parseFloat(capital) < valor_comparacion3) {
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 3) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }else{
                            var totalInteresValor1 = (parseFloat(totalInteresValor) / diaActual) * diasRango;
                            var interes1 = (capital * data.Resultado.p_interes) / 100;
                            var comision1 = (capital * 2) / 100;
                            var interes = (parseFloat(interes1) / diaActual) * diasRango;
                            var comision = (parseFloat(comision1) / diaActual) * diasRango;
                        }

                        var totalInteres = parseFloat(totalInteresValor1);
                        var totalGeneral = parseFloat(totalInteres) + parseFloat(capital);
                        var mensaje = 'El contrato tiene <b>' + diasRango + ' dias </b> habiles,<br> ' +
                            'Tiene un capital de :<b>' + parseFloat(capital).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b> con un interes de <b>' + parseFloat(
                                totalInteresValor).toFixed(2) + ' ' + data.Resultado.desc_corta + '</b> <br>' +
                            'El Interes seria un total de: <b>' + parseFloat(totalInteres).toFixed(2) +
                            ' ' + data.Resultado.desc_corta + '</b><br>' +
                            'El Capital mas el interes seria un total de: <b>' + parseFloat(totalGeneral)
                            .toFixed(2) + ' ' + data.Resultado.desc_corta + '</b>';
                    }

                    console.log("interes", interes);
                    console.log("comision", comision);
                    var route = "/PagoRemate";
                    //console.log("comision",comision);
                    Swal.fire({
                        title: "Esta seguro de rematar el Contrato?",
                        html: '',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: '#d33',
                        confirmButtonText: "Si, rematar!",
                        closeOnConfirm: false
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: route,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    'diasAtraso': valorDiasAtrasados,
                                    //'fecha_pago':moment().format("YYYY-MM-DD"),
                                    'fecha_pago': $("#txtFechaActualR").val(),
                                    'fecha_fin': contrato.fecha_fin,
                                    'fecha_Incio': contrato.fecha_contrato,
                                    'total_capital': contrato.total_capital,
                                    'capital': parseFloat(capital).toFixed(2),
                                    'interes': parseFloat(interes).toFixed(2),
                                    'comision': parseFloat(comision).toFixed(2),
                                    'cuotaMora': parseFloat(cuotaMora).toFixed(2),
                                    //'comision':0,
                                    'idContrato': contrato.id,
                                },
                                success: function(data) {
                                    console.log(data.Mensaje);
                                    resultado = data.Mensaje;

                                    /*resultado = 1  ROL ELIMINADO*/
                                    if (resultado == 1) {
                                        Swal.fire({
                                            title: "PAGO!",
                                            text: "Se pago correctamente!!",
                                            confirmButtonText: 'Aceptar',
                                            confirmButtonColor: "#66BB6A",
                                            type: "success"
                                        });
                                        global_idPago = data.idPago;
                                        fnBuscarContratos(globalIdPersona);
                                        fnImprimirRemate(data.idPago);
                                        fnVolverContratos();
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
                                    Swal.fire("Opss..!",
                                        "El La persona tiene registros en otras tablas!",
                                        "error")
                                }
                            });
                        }
                    })
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

        /*FUNCION IMPRIME PAGO TOTAL*/
        function fnImprimirRemate(id) {
            sw_global_reporte = true;
            console.log("id:::", id);
            $("#reporteModal").modal();
            var src = "/ImprimirReporteRemate/" + id;
            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);
            $("#dialog").show();
        };

        /**/
        function fnListadoPersonas(url) {
            var parametros = {
                'txtBuscarIdentifiacion': $("#txtBuscarIdentifiacion").val(),
                'txtBuscarNombres': $("#txtBuscarNombres").val(),
                'txtBuscarPaterno': $("#txtBuscarPaterno").val(),
                'txtBuscarMaterno': $("#txtBuscarMaterno").val(),
                'txtBuscarFechaNacimiento': $("#txtBuscarFechaNacimiento").val(),
            };
            console.log("funcionnnn", url);
            var route = url;
            $.ajax({
                type: 'GET',
                url: route,
                data: parametros,
                //dataType: 'json',
                success: function(data) {
                    console.log("data", data);
                    $('.clsClientes').html(data);
                },
                error: function(error) {
                    Swal.fire({
                        title: "PERSONA...",
                        text: "Los roles no se puede cargar",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: 'Aceptar',
                        type: "error"
                    });
                }
            });
        }

        $('#ddlTipoBusqueda').on('change', function() {
            $("#txtBuscarCodigo").val('');
            $("#txtBuscarPaterno").val('');
            $("#txtBuscarMaterno").val('');
            $("#txtBuscarNombres").val('');
            $("#txtBuscarIdentifiacion").val('');
            $("#txtBuscarFechaNacimiento").val('');
            valor = this.value;
            console.log('valor', valor);
            $('.clsClientes').hide();
            $('.clsCodigo').hide();
            $('.clsContratos').hide();
            $('.clsDatoPagoInteres').hide();
            $('.clsDatoRemate').hide();
            $('.clsDatoPagoAmortizacion').hide();
            $('.clsDatoPagoAmortizacionInteres').hide();
            $('.clsDatoPagoTotal').hide();



            if (valor == 'CI') {
                document.getElementById('divMostrarBusquedaCI').style.display = 'block';
                document.getElementById('divMostrarBusquedaCodigo').style.display = 'none';
            }

            if (valor == 'CO') {
                document.getElementById('divMostrarBusquedaCI').style.display = 'none';
                document.getElementById('divMostrarBusquedaCodigo').style.display = 'block';
            }

            if (valor == '') {
                document.getElementById('divMostrarBusquedaCI').style.display = 'none';
                document.getElementById('divMostrarBusquedaCodigo').style.display = 'none';
            }
        });

    </script>
@endsection