@extends('layout.principal')
@include('reporte.modalReporte')
@section('main-content')
    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        OPCIONES
                    </h3>
                </div>
            </section>
        </div>
    </div>
    <form id="frmReporteContable">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Sucursal:</label>
                    <select name="txtSucursal" id="txtSucursal" class="form-control" placeholder="Seleccionar Sucursal">
                        <option value=""></option>
                        @if (!empty($sucursales))
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}"> {{ $sucursal->nombre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Caja:</label>
                    <select name="txtCaja" id="txtCaja" class="form-control" placeholder="Seleccionar Caja">
                        <option value=""></option>
                        <option value="1">
                            1
                        </option>
                        <option value="2">
                            2
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Fecha Inicio:</label>
                    <div class="input-group input-append date" id="datePickerFI">
                        <input type="text" class="form-control" id="txtFechaInicio" name="txtFechaInicio"
                            placeholder="Ingrese Fecha de Inicio" />
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Fecha Fin:</label>
                    <div class="input-group input-append date" id="datePickerFF">
                        <input type="text" class="form-control" id="txtFechaFin" name="txtFechaFin"
                            placeholder="Ingrese Fecha Fin" />
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Libro Diario</td>
                                <td><a href="#" onClick="fnImprimirLibroDiario();" data-popup="tooltip" title="Imprimir"><i
                                            class="fa fa-fw fa-file-pdf-o"></i></a><a href="#"
                                        onClick="fnExportarExcelLibroDiario();" data-popup="tooltip" title="Excel"><i
                                            class="fa fa-fw fa-file-excel-o"></i></a></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Suma y Saldos</td>
                                <td><a href="#" onClick="fnImprimirSumaySaldos();" data-popup="tooltip" title="Imprimir"><i
                                            class="fa fa-fw fa-file-pdf-o"></i></a></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Balance General </td>
                                <td><a href="#" onClick="fnImprimirBalanceGeneral();" data-popup="tooltip"
                                        title="Imprimir"><i class="fa fa-fw fa-file-pdf-o"></i></a></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Estado de Resultados</td>
                                <td><a href="#" onClick="fnImprimirEstadoResultados();" data-popup="tooltip"
                                        title="Imprimir"><i class="fa fa-fw fa-file-pdf-o"></i></a></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Libro Mayor</td>
                                <td><a href="#" onClick="fnImprimirLibroMayor();" data-popup="tooltip" title="Imprimir"><i
                                            class="fa fa-fw fa-file-pdf-o"></i></a><a href="#"
                                        onClick="fnExportarExcelLibroMayor();" data-popup="tooltip" title="Excel"><i
                                            class="fa fa-fw fa-file-excel-o"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-primary no-border clsMensajeAlerta">
                    .
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        var indexItems = 0;

        $(document).ready(function() {
            $('[data-mask]').inputmask('dd-mm-yyyy', {
                'placeholder': 'dd-mm-yyyy'
            });
            $('.clsMensajeAlerta').hide();
            /*/*******************();* ANULAR ENTER EN FORMULARIOS ******************/
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

                //$('#load a').css('color', '#dfecf6');
                //$('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

                var url = $(this).attr('href');
                fnListadoRegistrosContables(url);
                window.history.pushState("", "", url);
            });
            $('#datePickerFI').datepicker({
                format: "dd-mm-yyyy",
                language: "es",
                //autoclose: true,
                orientation: "auto left",
                forceParse: false,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true
            }).on('changeDate', function(e) {
                $('#frmReporteContable').bootstrapValidator('revalidateField', 'txtFechaInicio');
            });

            $('#datePickerFF').datepicker({
                format: "dd-mm-yyyy",
                language: "es",
                //autoclose: true,
                orientation: "auto left",
                forceParse: false,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true
            }).on('changeDate', function(e) {
                $('#frmReporteContable').bootstrapValidator('revalidateField', 'txtFechaFin');
            });
            /******************** VALIDAR FROMULARIO PARA BUSCAR POR FECHAS ******************/
            // $('#frmReporteContable').bootstrapValidator({
            //     message: 'This value is not valid',
            //     feedbackIcons: {
            //         valid: 'glyphicon glyphicon-ok',
            //         invalid: 'glyphicon glyphicon-remove',
            //         validating: 'glyphicon glyphicon-refresh'
            //     },
            //     fields
            // });


        });

        function validaFormulario(fields) {
            if ($("#frmReporteContable").data('bootstrapValidator')) {
                $("#frmReporteContable").data('bootstrapValidator').destroy();
                $('#frmReporteContable').data('bootstrapValidator', null);
            }
            $('#frmReporteContable').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields
            });
        }


        /******************************** LISTADO DE PERSONAS *****************************************/
        function fnImprimirLibroDiario() {
            var route = "/RegistroContable";

            let fields = {
                txtFechaInicio: {
                    message: 'Fecha no es valida',
                    validators: {
                        notEmpty: {
                            message: 'Fecha de Inicio es requerida'
                        },
                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                    }
                },
                txtFechaFin: {
                    message: 'Fecha no es valida',
                    validators: {
                        notEmpty: {
                            message: 'Fecha Fin es requerida'
                        },
                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                    }
                },
                txtSucursal: {
                    message: 'Valor no valido',
                    validators: {
                        notEmpty: {
                            message: 'Sucursal es requerida'
                        },
                    }
                },
                txtCaja: {
                    message: 'Valor no valido',
                    validators: {
                        notEmpty: {
                            message: 'Caja es requerida'
                        },
                    }
                }
            };
            validaFormulario(fields);

            var validarReporteContable = $("#frmReporteContable").data('bootstrapValidator');
            validarReporteContable.validate();

            if (validarReporteContable.isValid()) {
                var parametros = {
                    'txtFechaInicio': $("#txtFechaInicio").val(),
                    'txtFechaFin': $("#txtFechaFin").val(),
                    'txtSucursal': $("#txtSucursal").val(),
                    'txtCaja': $("#txtCaja").val(),
                    // 'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
                    // 'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
                    // 'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
                };
                var fechaInicio = $("#txtFechaInicio").val();
                var fechaFin = $("#txtFechaFin").val();
                var txtSucursal = $("#txtSucursal").val();
                var txtCaja = $("#txtCaja").val();
                $("#reporteModal").modal();

                //console.log(src);
                console.log("entrooo");
                var src = "/ImprmirLibroDiario/" + fechaInicio + "/" + fechaFin + "?sucursal=" + txtSucursal + "&caja=" +
                    txtCaja;
                var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
                object += "</object>";
                object = object.replace(/{src}/g, src);
                $("#dialog").html(object);
                $("#dialog").show();
            }
        }


        /******************************** LISTADO DE PERSONAS *****************************************/
        function fnImprimirSumaySaldos() {
            let fields = {
                txtFechaFin: {
                    message: 'Fecha no es valida',
                    validators: {
                        notEmpty: {
                            message: 'Fecha Fin es requerida'
                        },
                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                    }
                }
            }
            validaFormulario(fields);
            var validarReporteContable = $("#frmReporteContable").data('bootstrapValidator');
            validarReporteContable.validate();
            if (validarReporteContable.isValid()) {
                var parametros = {
                    'txtFechaInicio': $("#txtFechaInicio").val(),
                    'txtFechaFin': $("#txtFechaFin").val(),
                    // 'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
                    // 'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
                    // 'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
                };
                var fechaInicio = $("#txtFechaInicio").val();
                var fechaFin = $("#txtFechaFin").val();
                var txtSucursal = $("#txtSucursal").val();
                var txtCaja = $("#txtCaja").val();
                $("#reporteModal").modal();

                //console.log(src);
                console.log("entrooo");
                var src = "/ImprmirSumaySaldos/" + fechaFin + "?sucursal=" + txtSucursal + "&caja=" +
                    txtCaja;
                var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
                object += "</object>";
                object = object.replace(/{src}/g, src);
                $("#dialog").html(object);
                $("#dialog").show();
            }
        }

        /******************************** LISTADO DE PERSONAS *****************************************/
        function fnImprimirLibroMayor() {
            //var route="/ImprmirSumaySaldos";
            let fields = {
                txtFechaFin: {
                    message: 'Fecha no es valida',
                    validators: {
                        notEmpty: {
                            message: 'Fecha Fin es requerida'
                        },
                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                    }
                }
            }
            validaFormulario(fields);
            var validarReporteContable = $("#frmReporteContable").data('bootstrapValidator');
            validarReporteContable.validate();
            if (validarReporteContable.isValid()) {
                var parametros = {
                    'txtFechaInicio': $("#txtFechaInicio").val(),
                    'txtFechaFin': $("#txtFechaFin").val(),
                    // 'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
                    // 'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
                    // 'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
                };
                var fechaInicio = $("#txtFechaInicio").val();
                var fechaFin = $("#txtFechaFin").val();
                $("#reporteModal").modal();

                //console.log(src);
                console.log("entrooo");
                var src = "/ImprmirLibroMayor/" + fechaFin;
                var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
                object += "</object>";
                object = object.replace(/{src}/g, src);
                $("#dialog").html(object);
                $("#dialog").show();
            }
        }

        /*EXPORTAR EXCEL LIBRO DIARIO*/
        function fnExportarExcelLibroDiario() {
            let fields = {
                txtFechaInicio: {
                    message: 'Fecha no es valida',
                    validators: {
                        notEmpty: {
                            message: 'Fecha de Inicio es requerida'
                        },
                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                    }
                },
                txtFechaFin: {
                    message: 'Fecha no es valida',
                    validators: {
                        notEmpty: {
                            message: 'Fecha Fin es requerida'
                        },
                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                    }
                },
                txtSucursal: {
                    message: 'Valor no valido',
                    validators: {
                        notEmpty: {
                            message: 'Sucursal es requerida'
                        },
                    }
                },
                txtCaja: {
                    message: 'Valor no valido',
                    validators: {
                        notEmpty: {
                            message: 'Caja es requerida'
                        },
                    }
                }
            };
            validaFormulario(fields);

            var validarReporteContable = $("#frmReporteContable").data('bootstrapValidator');
            validarReporteContable.validate();
            if (validarReporteContable.isValid()) {
                var fechaInicio = $("#txtFechaInicio").val();
                var fechaFin = $("#txtFechaFin").val();
                var txtSucursal = $("#txtSucursal").val();
                var txtCaja = $("#txtCaja").val();
                $.ajax({
                    type: 'GET',
                    url: "ImprmirLibroDiarioExcel/" + fechaInicio + "/" + fechaFin + "?sucursal=" + txtSucursal +
                        "&caja=" +
                        txtCaja,
                    //data:parametros,
                    // async: false,
                    // contentType: "application/x-www-form-urlencoded;charset=UTF-8; charset=iso-8859-1;application/json;",
                    //dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        var valor = jQuery.parseJSON(data);
                        //console.log(valor.file);
                        var link = document.createElement("a");
                        link.download = "Libro_diario_" + fechaInicio + "_" + fechaFin + ".xlsx";
                        //var uri = 'data:application/vnd.ms-excel;base64' + data.file;
                        link.href = valor.file;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    },
                    beforeSend: function() {
                        $('.loader').addClass('loader-default  is-active');

                    },
                    complete: function() {
                        $('.loader').removeClass('loader-default  is-active');
                    },
                    error: function(error) {
                        swal({
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

        /*EXPORTAR EXCEL LIBRO DIARIO*/
        function fnExportarExcelLibroMayor() {
            let fields = {
                txtFechaFin: {
                    message: 'Fecha no es valida',
                    validators: {
                        notEmpty: {
                            message: 'Fecha Fin es requerida'
                        },
                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                    }
                }
            }
            validaFormulario(fields);
            var validarReporteContable = $("#frmReporteContable").data('bootstrapValidator');
            validarReporteContable.validate();
            if (validarReporteContable.isValid()) {
                var fechaInicio = $("#txtFechaInicio").val();
                var fechaFin = $("#txtFechaFin").val();
                $.ajax({
                    type: 'GET',
                    url: "ImprmirLibroMayorExcel/" + fechaFin,
                    //data:parametros,
                    // async: false,
                    // contentType: "application/x-www-form-urlencoded;charset=UTF-8; charset=iso-8859-1;application/json;",
                    //dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        var valor = jQuery.parseJSON(data);
                        //console.log(valor.file);
                        var link = document.createElement("a");
                        link.download = "Libro_mayor_" + fechaInicio + "_" + fechaFin + ".xlsx";
                        //var uri = 'data:application/vnd.ms-excel;base64' + data.file;
                        link.href = valor.file;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    },
                    beforeSend: function() {
                        $('.loader').addClass('loader-default  is-active');

                    },
                    complete: function() {
                        $('.loader').removeClass('loader-default  is-active');
                    },
                    error: function(error) {
                        swal({
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


        /******************************** LISTADO DE PERSONAS *****************************************/
        function fnImprimirEstadoResultados() {
            let fields = {
                txtFechaInicio: {
                    message: 'Fecha no es valida',
                    validators: {
                        notEmpty: {
                            message: 'Fecha Fin es requerida'
                        },
                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                    }
                },
                txtFechaFin: {
                    message: 'Fecha no es valida',
                    validators: {
                        notEmpty: {
                            message: 'Fecha Fin es requerida'
                        },
                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                    }
                }
            }
            validaFormulario(fields);
            //var route="/ImprmirSumaySaldos";
            var validarReporteContable = $("#frmReporteContable").data('bootstrapValidator');
            validarReporteContable.validate();
            if (validarReporteContable.isValid()) {
                var parametros = {
                    'txtFechaInicio': $("#txtFechaInicio").val(),
                    'txtFechaFin': $("#txtFechaFin").val(),
                    // 'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
                    // 'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
                    // 'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
                };
                var fechaInicio = $("#txtFechaInicio").val();
                var fechaFin = $("#txtFechaFin").val();
                $("#reporteModal").modal();

                //console.log(src);
                console.log("entrooo");
                var src = "/ImprimirEstadoResultados/" + fechaInicio + "/" + fechaFin;
                var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
                object += "</object>";
                object = object.replace(/{src}/g, src);
                $("#dialog").html(object);
                $("#dialog").show();
            }
        }

        function fnImprimirBalanceGeneral() {
            //var route="/ImprmirSumaySaldos";
            let fields = {
                txtFechaFin: {
                    message: 'Fecha no es valida',
                    validators: {
                        notEmpty: {
                            message: 'Fecha Fin es requerida'
                        },
                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                    }
                }
            }
            validaFormulario(fields);
            var validarReporteContable = $("#frmReporteContable").data('bootstrapValidator');
            validarReporteContable.validate();
            if (validarReporteContable.isValid()) {
                var parametros = {
                    'txtFechaInicio': $("#txtFechaInicio").val(),
                    'txtFechaFin': $("#txtFechaFin").val(),
                    // 'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
                    // 'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
                    // 'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
                };
                var fechaInicio = $("#txtFechaInicio").val();
                var fechaFin = $("#txtFechaFin").val();
                $("#reporteModal").modal();

                //console.log(src);
                console.log("entrooo");
                var src = "/ImprimirBalanceGeneral/" + fechaFin;
                var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
                object += "</object>";
                object = object.replace(/{src}/g, src);
                $("#dialog").html(object);
                $("#dialog").show();
            }
        }

    </script>
@endsection
