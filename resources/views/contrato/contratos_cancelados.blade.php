@extends('layout.principal')
@include('reporte.modalReporteContratosCancelados')
@section('main-content')
    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        REPORTES DE CONTRATOS CANCELADOS
                    </h3>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <select name="filtro" id="filtro" class="form-control">
                    <option value="diario">Diario</option>
                    <option value="fecha">Por fechas</option>
                </select>
            </div>
        </div>
    </div>

    <form id="frmRepContratosCancelados">
        <div class="row" id="valoresReporte">
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <div class="input-group-btn" style="display:flex;">
                        <button class="btn btn-success" data-dismiss="modal" type="button"
                            onclick="fnImprimirSolicitudesPDF();">Imprimir PDF</button><br>
                        <a href="{{ route('contrato.contratos_cancelados_excel') }}" class="btn btn-success" data-dismiss="modal" style="margin-left:5px;" id="btnExportarExcel">Exportar EXCEL</a><br>
                    </div>
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
        let valoresReporte = $('#valoresReporte');
        $(document).ready(function() {
            $('.clsMensajeAlerta').hide();
            filtro();
            $('#filtro').change(filtro);

            $('#btnExportarExcel').click(function(e){
                e.preventDefault();
                var validarReporteContratosCancelados = $("#frmRepContratosCancelados").data('bootstrapValidator'); 	     	
                validarReporteContratosCancelados.validate();
                if(validarReporteContratosCancelados.isValid())
                {
                    let txtFechaInicio = $('#txtFechaInicio');
                    let txtFechaFin = $('#txtFechaFin');
                    let filtro = $('#filtro');
                    validarReporteContratosCancelados.validate();   
                    if(txtFechaInicio.val() != '' && txtFechaFin != '')
                    {
                        let url = $(this).attr('href');
                        url += `?filtro=${filtro.val()}&fecha_ini=${txtFechaInicio.val()}&fecha_fin=${txtFechaFin.val()}`;
                        console.log(url);
                        window.location.href = url;
                    }
                    $('#frmRepContratosCancelados').bootstrapValidator('resetForm', true);
                }
            });

        });

        function iniciaFechas()
        {
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
                $('#frmRepContratosCancelados').bootstrapValidator('revalidateField', 'txtFechaInicio');
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
                $('#frmRepContratosCancelados').bootstrapValidator('revalidateField', 'txtFechaFin');
            });
    	
        }

        function filtro()
        {
            let filtro = $('#filtro').val();
            if(filtro == 'diario')
            {
                valoresReporte.html(`
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Inicio:</label>
                        <div class="input-group input-append date" id="datePickerFI">
                            <input type="text" class="form-control" id="txtFechaInicio" name="txtFechaInicio"
                                placeholder="Ingrese Fecha de Inicio" />
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>`);

                $('#frmRepContratosCancelados').bootstrapValidator({
                    message: 'This value is not valid',
                    feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        txtFechaInicio: {
                            message: 'Fecha no es valida',
                            validators: {
                                notEmpty: {
                                    message: 'Fecha de Inicio es requerida'
                                },
                                //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                            }
                        },
                    }
                });
            }
            else{
                valoresReporte.html(`
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
                </div>`);

                $('#frmRepContratosCancelados').bootstrapValidator({
                    message: 'This value is not valid',
                    feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
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
                        }
                    }
                });
            }
            iniciaFechas();
        }

        /********************** IMPRIMIR ************************/
        function fnImprimirSolicitudesPDF() {
            var validarReporteContratosCancelados = $("#frmRepContratosCancelados").data('bootstrapValidator'); 	     	
	        validarReporteContratosCancelados.validate();
	        if(validarReporteContratosCancelados.isValid())
	        {
                let txtFechaInicio = $('#txtFechaInicio');
                let txtFechaFin = $('#txtFechaFin');
                let filtro = $('#filtro');
                validarReporteContratosCancelados.validate();   
                if(txtFechaInicio.val() != '' && txtFechaFin != '')
                {
                    $('#reporteModalContratosCancelados').modal()
                    var src = "/contratos_cancelados/contratos_cancelados_pdf?filtro="+filtro.val()+"&fecha_ini="+txtFechaInicio.val()+"&fecha_fin="+txtFechaFin.val();
                    var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
                    object += "</object>";
                    object = object.replace(/{src}/g, src);
                    $("#dialogCC").html(object);
                    $("#dialogCC").show();
                    $('#frmRepContratosCancelados').bootstrapValidator('resetForm', true);
                }
            }
        }

    </script>
@endsection
