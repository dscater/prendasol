@extends('layout.principal')
@include('reporte.modalReporte')
@include('reporte.modalReporeteComprobante')
@include('reporte.modalReporteBoleta')
@section('main-content')

<script>

	/****************/
			// PRUEBA
			/****************/
            /*COMPROBANTE*/
            function fnImprimirContratoComprobante(id) {
                $("#reporteModalComprobante").modal();
                var src = "/ImprimirComprobante/" + id;
                console.log(src);
                var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
                object += "</object>";
                object = object.replace(/{src}/g, src);
                $("#dialogC").html(object);
                $("#dialogC").show();
            }


            /* PRUEBA BOLETA */
		function fnImprimirBoleta(id){
			$("#reporteModalBoleta").modal();
        	var src = "/ImprimirBoleta/"+id;
        	console.log(src);
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogBoleta").html(object);            
            $("#dialogBoleta").show();  
		}
</script>

    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token">
        --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        REIMPRESIONES DE CONTRATO
                    </h3>
                </div>
            </section>
        </div>
    </div>
    <form id="frmReimpresiones">
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
            <div class="col-md-3">
                <div class="form-group">
                    <label>Sucursales:</label>
                    <select class="form-control select2" id="ddlSucursal" name="ddlSucursal"
                        data-placeholder="Seleccionar Sucursal" required>
                        <option></option>
                        @if (!empty($sucursales))
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}"> {{ $sucursal->nombre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <div class="input-group-btn">
                        <br>
                        {!! link_to('#', $title = 'Buscar', $attributes = ['id' => 'btnBuscar', 'class' => 'btn
                        btn-primary'], $secure = null) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <section class="clsReimpresiones">
                    @include('reimpresiones.modals.listadoReimpresiones')
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
    </form>
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.clsMensajeAlerta').hide();
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

                //$('#load a').css('color', '#dfecf6');
                //$('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

                var url = $(this).attr('href');
                fnListadoModuloes(url);
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
                $('#frmReimpresiones').bootstrapValidator('revalidateField', 'txtFechaInicio');
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
                $('#frmReimpresiones').bootstrapValidator('revalidateField', 'txtFechaFin');
            });
            /******************** VALIDAR FROMULARIO PARA BUSCAR POR FECHAS ******************/
            $('#frmReimpresiones').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    txtFechaInicio: {
                        message: 'Fecha de Inicio no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Fecha de Inicio es requerida'
                            },
                            //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                        }
                    },
                    txtFechaFin: {
                        message: 'Fecha Fin no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Fecha Fin es requerida'
                            },
                            //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                        }
                    },
                    ddlSucursal: {
                        message: 'Sucursal no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Sucursal es requerida'
                            },
                            //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                        }
                    }

                }
            });
        });

        /******************************** BUSCA DATOS POR FECHAS *****************************************/
        $("#btnBuscar").click(function() {
            var validarReimpresiones = $("#frmReimpresiones").data('bootstrapValidator');
            console.log(validarReimpresiones);
            validarReimpresiones.validate();
            if (validarReimpresiones.isValid()) {
                var parametros = {
                    'txtFechaInicio': $("#txtFechaInicio").val(),
                    'txtFechaFin': $("#txtFechaFin").val(),
                    'ddlSucursal': $("#ddlSucursal").val(),
                };
                var route = "/BuscarReimpresiones";
                $.ajax({
                    url: route,
                    data: parametros,
                    method: 'GET',
                    //dataType:'json',
                    success: function(data) {
                        console.log("datoss", data);
                        if (data == "") {
                            console.log("datosss vacios");
                            $('.clsReimpresiones').empty();
                            $('.clsMensajeAlerta').html(
                                '<span class="text-semibold">No existe registro de contabilidad!</span>'
                                );
                            $('.clsMensajeAlerta').show();
                        } else {
                            $('.clsReimpresiones').html(data);
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
                            title: "SOAP...",
                            text: "La busqueda no se puede cargar",
                            confirmButtonColor: "#EF5350",
                            confirmButtonText: 'Aceptar',
                            type: "error"
                        });
                    }
                });
            }
        });
        /********************** IMPRIMIR ************************/
        function fnImprimirContratos(dato) {
            console.log(dato);
            var estado = dato.estado;
            if (estado == "DESEMBOLSO") {
                var id = dato.contrato_id;
                // var src = "/ReImprimirReporteContrato/" + id;
                var src = "/ImprimirComprobante/" + id;
            }
            if (estado == "AMORTIZACIÓN") {
                var id = dato.id;
                var src = "/ReImprimirReporteAmortizacion/" + id;            
                // var src = "/imprimirCambioPago/" + id;            
            }

            if (estado == "AMORTIZACIÓN INTERES") {
                var id = dato.id;
                var src = "/imprimirCambioPago/" + id;
            }

            if (estado == "INTERES") {
                var id = dato.id;
                var src = "/ReImprimirReporteInteres/" + id;
            }

            if (estado == "PAGO TOTAL") {
                var id = dato.id;
                // var src = "/ReImprimirReportePagoTotal/" + id;
                var src = "/ImprimirReportePagoTotal/" + id;
            }

            if (estado == "REMATE") {
                var id = dato.id;
                var src = "/ReImprimirReporteRemate/" + id;
            }

            $("#reporteModal").modal();

            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);
            $("#dialog").show();
        }

        function fnIprimirCambioMoneda(dato){
            var id = dato.contrato_id;
            var src = "/imprimirContratoCambioMoneda/" + id;
            $("#reporteModal").modal();

            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);
            $("#dialog").show();
        }

        function fnImprimirContratos2(dato){
            var id = dato.contrato_id;
            var src = "/ImprimirComprobante2/" + id;
            $("#reporteModal").modal();

            console.log(src);
            console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);
            $("#dialog").show();
        }

    </script>
@endsection
