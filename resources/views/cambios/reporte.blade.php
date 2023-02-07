@extends('layout.principal')
@include('reporte.modalReporteContratosCancelados')
@section('main-content')
    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        REPORTES DE CAMBIO DE DÓLARES
                    </h3>
                </div>
            </section>
        </div>
    </div>

    <form id="formCambioDolares">

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Fecha Final:</label>
                    <div class="input-group input-append date" id="datePickerFI">
                        <input type="text" class="form-control" id="txtFechaIni" name="txtFechaIni"
                            placeholder="Ingrese Fecha" />
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Fecha Final:</label>
                    <div class="input-group input-append date" id="datePickerFF">
                        <input type="text" class="form-control" id="txtFechaFin" name="txtFechaFin"
                            placeholder="Ingrese Fecha" />
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Sucursal:</label>
                    <select name="sucursal" id="sucursal" class="form-control">
                        <option value=""></option>
                        @if (!empty($sucursales))
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}"> {{ $sucursal->nombre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <div class="input-group-btn" style="display:flex;">
                        <button class="btn btn-success" data-dismiss="modal" type="button"
                            onclick="fnImprimirCambios();">Generar Pdf</button><br>
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
        $(document).ready(function() {
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
                $('#formCambioDolares').bootstrapValidator('revalidateField', 'txtFechaIni');
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
                $('#formCambioDolares').bootstrapValidator('revalidateField', 'txtFechaFin');
            });

            $('#formCambioDolares').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    sucursal: {
                        message: 'Fecha no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Sucursal es requerida'
                            },
                        }
                    },
                    txtFechaIni: {
                        message: 'Fecha no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Fecha Fin es requerida'
                            },
                        }
                    },
                    txtFechaFin: {
                        message: 'Fecha no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Fecha Inicio es requerida'
                            },
                        }
                    }
                }
            });
            $('.clsMensajeAlerta').hide();
        });

        /********************** IMPRIMIR ************************/
        function fnImprimirCambios() {
            var validarForm = $("#formCambioDolares").data('bootstrapValidator');
            validarForm.validate();

            if (validarForm.isValid()) {
                let sucursal = $('#sucursal');
                let txtFechaIni = $('#txtFechaIni');
                let txtFechaFin = $('#txtFechaFin');
                validarForm.validate();
                $('#reporteModalContratosCancelados').modal()
                var src = "/cambio_dolares/reporte_pdf?fecha_ini=" + txtFechaIni.val() + "&fecha_fin=" + txtFechaFin.val() +
                    " & sucursal= " + sucursal.val();
                var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
                object += "</object>";
                object = object.replace(/{src}/g, src);
                $("#dialogCC").html(object);
                $("#dialogCC").show();
                // $('#formCambioDolares').bootstrapValidator('resetForm', true);
            }
        }

    </script>
@endsection
