@extends('layout.principal')
@include('reporte.modalReporteContratosCancelados')
@section('main-content')
    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        REPORTES DE CONTRATOS VIGENTES
                    </h3>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Fecha:</label>
                <select name="fecha" id="fecha" class="form-control">
                    <option value="todos">Todos</option>
                    <option value="fecha">Por fecha</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Sucursal:</label>
                <select name="sucursal" id="sucursal" class="form-control">
                    <option value="todos">Todos</option>
                    @if (!empty($sucursales))
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}"> {{ $sucursal->nombre }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>

    <form id="frmRepContratosVigentes">
        <div class="row" id="valoresReporte">
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <div class="input-group-btn" style="display:flex;">
                        <button class="btn btn-success" data-dismiss="modal" type="button"
                            onclick="fnImprimirVigentesPDF();">Generar Pdf</button><br>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-primary no-border clsMensajeAlerta">
                    &nbsp;
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        let valoresReporte = $('#valoresReporte');
        $(document).ready(function() {
            $('.clsMensajeAlerta').hide();
            fecha();
            $('#fecha').change(fecha);
        });

        function iniciaFechas() {
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
                $('#frmRepContratosVigentes').bootstrapValidator('revalidateField', 'txtFechaFinal');
            });
        }

        function fecha() {
            let fecha = $('#fecha').val();
            if (fecha == 'fecha') {
                valoresReporte.html(`<div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Fecha Final:</label>
                                                                                <div class="input-group input-append date" id="datePickerFI">
                                                                                    <input type="text" class="form-control" id="txtFechaFinal" name="txtFechaFinal"
                                                                                        placeholder="Ingrese Fecha" />
                                                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>`);
                let fields = {
                    sucursal: {
                        message: 'Fecha no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Sucursal es requerida'
                            },
                        }
                    },
                    txtFechaFinal: {
                        message: 'Fecha no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Fecha de Inicio es requerida'
                            },
                        }
                    }
                };
                iniciaFechas();
                validaFormulario(fields);
            } else {
                valoresReporte.html(``);
                let fields = {
                    sucursal: {
                        message: 'Fecha no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Sucursal es requerida'
                            },
                        }
                    },
                };
                validaFormulario(fields);
                iniciaFechas();
            }
        }

        function validaFormulario(fields) {
            if ($("#frmRepContratosVigentes").data('bootstrapValidator')) {
                $('#frmRepContratosVigentes').bootstrapValidator('resetForm', true);
                $("#frmRepContratosVigentes").data('bootstrapValidator').destroy();
                $('#frmRepContratosVigentes').data('bootstrapValidator', null);
            }
            $('#frmRepContratosVigentes').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields
            });
        }

        /********************** IMPRIMIR ************************/
        function fnImprimirVigentesPDF() {
            var validarReporteContratosCancelados = $("#frmRepContratosVigentes").data('bootstrapValidator');
            validarReporteContratosCancelados.validate();
            if (validarReporteContratosCancelados.isValid()) {
                let sucursal = $('#sucursal');
                let fecha = $('#fecha');
                let txtFechaFinal = $('#txtFechaFinal');
                validarReporteContratosCancelados.validate();
                $('#reporteModalContratosCancelados').modal()
                let url_add = '';
                if (txtFechaFinal.val() != '') {
                    url_add = '&fecha_fin=' + txtFechaFinal.val();
                }
                var src = "/contratos_vigentes/contratos_vigentes_pdf?fecha=" + fecha.val() + "&sucursal=" + sucursal
                    .val() + url_add;
                var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
                object += "</object>";
                object = object.replace(/{src}/g, src);
                $("#dialogCC").html(object);
                $("#dialogCC").show();
            }
        }
    </script>
@endsection
