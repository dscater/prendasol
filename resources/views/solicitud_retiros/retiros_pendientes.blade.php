@extends('layout.principal')
@include('reporte.modalReporteSolicitudes')
@section('main-content')
    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        REPORTES DE SOLICITUDES DE RETIRO DE JOYAS
                    </h3>
                </div>
            </section>
        </div>
    </div>
    <form id="frmRepComprobantes">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Fecha Inicio:</label>
                    <div class="input-group input-append date" id="datePickerFI">
                        <input type="text" class="form-control" id="txtFechaInicio" name="txtFechaInicio"
                            placeholder="Elija una fecha" />
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Fecha Fin:</label>
                    <div class="input-group input-append date" id="datePickerFF">
                        <input type="text" class="form-control" id="txtFechaFin" name="txtFechaFin"
                            placeholder="Elija una fecha" />
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
                            onclick="fnImprimirSolicitudesPDF();">Imprimir PDF</button><br>
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
        $(document).ready(function() {
            $('.clsMensajeAlerta').hide();

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
                $('#frmRepComprobantes').bootstrapValidator('revalidateField', 'txtFechaInicio');
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
                $('#frmRepComprobantes').bootstrapValidator('revalidateField', 'txtFechaFin');
            });

            $('#frmRepComprobantes').bootstrapValidator({
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
                                message: 'Fecha de Fin es requerida'
                            },
                            //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                        }
                    }
                }
            });
        });


        /********************** IMPRIMIR ************************/
        function fnImprimirSolicitudesPDF() {
                let sucursal = $('#sucursal');
            var validarReporte = $("#frmRepComprobantes").data('bootstrapValidator');
            validarReporte.validate();
            if (validarReporte.isValid()) {
                $('#reporteModalSolicitudes').modal()
                var src = "/retiros_pendientes/reporte/pdf?fecha_ini=" + $('#txtFechaInicio').val() + "&fecha_fin=" + $(
                    '#txtFechaFin').val() + "&sucursal=" + sucursal.val();
                var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
                object += "</object>";
                object = object.replace(/{src}/g, src);
                $("#dialogS").html(object);
                $("#dialogS").show();
            }
        }
    </script>
@endsection
