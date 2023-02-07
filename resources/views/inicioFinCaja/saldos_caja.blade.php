@extends('layout.principal')
@include('reporte.modalReporteContratosCancelados')
@section('main-content')
    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        REPORTE DE SALDOS EN CAJA
                    </h3>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Fecha Final:</label>
                <div class="input-group input-append date" id="datePickerFI">
                    <input type="text" class="form-control" id="txtFecha" name="txtFecha" placeholder="Ingrese Fecha"
                        value="{{ date('d-m-Y') }}" />
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-green">
                                <th>SUCURSAL</th>
                                <th>CAJA</th>
                                <th>FECHA</th>
                                <th>USUARIO</th>
                                <th>INICIO DE CAJA</th>
                                <th>PRESTAMO DE CAJA</th>
                                <th>SALDO ACTUAL</th>
                            </tr>
                        </thead>
                        <tbody id="contenedorReg">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="urlGetSaldos" value="{{ route('cajas.get_saldos_caja') }}">

    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        let contenedorReg = $('#contenedorReg');
        $(document).ready(function() {
            iniciaFechas();
            obtieneRegistros();
            $("#txtFecha").change(obtieneRegistros);
        });

        function obtieneRegistros() {
            $.ajax({
                type: "GET",
                url: $('#urlGetSaldos').val(),
                data: {
                    fecha: $('#txtFecha').val()
                },
                dataType: "json",
                success: function(response) {
                    contenedorReg.html(response.html);
                }
            });
        }

        function iniciaFechas() {
            $('#datePickerFI').datepicker({
                format: "dd-mm-yyyy",
                language: "es",
                //autoclose: true,
                orientation: "auto left",
                forceParse: true,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true
            }).on('changeDate', function(e) {
                $('#frmRepContratosVigentes').bootstrapValidator('revalidateField', 'txtFecha');
            });
        }

    </script>
@endsection
