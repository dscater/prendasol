@extends('layout.principal')
@include('reporte.modalReporteContratosCancelados')
@section('main-content')
    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        REPORTE RESUMEN DE PRÉSTAMOS
                    </h3>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Mes:</label>
                <select name="mes" id="mes" class="form-control">
                    @if (!empty($array_meses))
                        @foreach ($array_meses as $key => $mes)
                            <option value="{{ $key }}"> {{ $mes }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Año:</label>
                <select name="anio" id="anio" class="form-control">
                    @if (!empty($array_anios))
                        @foreach ($array_anios as $anio)
                            <option value="{{ $anio }}"> {{ $anio }}</option>
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
                            onclick="fnImprimirResumenPDF();">Generar Pdf</button><br>
                        <a href="{{ route('contrato.resumen_prestamos_excel') }}" class="btn btn-success"
                            data-dismiss="modal" style="margin-left:5px;" id="btnExportarExcel">Exportar EXCEL</a><br>
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
            $('#mes').val("{{ date('m') }}");
        });

        /********************** IMPRIMIR ************************/
        function fnImprimirResumenPDF() {
            let mes = $('#mes');
            let anio = $('#anio');
            var src = "/resumen_prestamos/resumen_prestamos_pdf?mes=" + mes.val() + "&anio=" + anio.val();
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            $('#reporteModalContratosCancelados').modal()
            object = object.replace(/{src}/g, src);
            $("#dialogCC").html(object);
            $("#dialogCC").show();
        }

        $('#btnExportarExcel').click(function(e) {
            e.preventDefault();
            let mes = $('#mes');
            let anio = $('#anio');
            let url = $(this).attr('href') + "?mes=" + mes.val() + '&anio=' + anio.val();
            window.location.href = url;
        });
    </script>
@endsection
