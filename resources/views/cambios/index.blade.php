@extends('layout.principal')

@section('main-content')
<style>
    #modalStoreCambio label{
        color:white!important;
    }
</style>
    <script>
        function fnLimpiarControles() {
            $('#txtFecha').val('');
            $('#txtCliente').val('');
            $('#txtNit').val('');
            $('#txtMonto').val('');
            $('#txtEquivalencia').val('');
            $('#formCambio').bootstrapValidator('resetForm', true);
            $('#sucursal_id').val($('#sucursalId').val());
            $('#txtSucursal').val($('#sucursalNombre').val());
            $('#txtEquivalencia').val('0.00');
        }
    </script>
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        <small>
                            <a href="#" id="btnCambioMonedas" class="btn btn-primary">NUEVA VENTA</a>
                            <a href="#" id="btnCambioMonedasCompra" class="btn btn-success">NUEVA COMPRA</a>
                        </small>
                    </h3>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>CAMBIO DE DÓLARES</h3>
            <section class="clsMonedas" id="contLista">
                @include('cambios.parcial.lista')
            </section>
        </div>
    </div>
    @include('reporte.modalReporte')
    @include('cambios.parcial.nuevo')
    <input type="hidden" id="urlLista" value="{{ route('cambios.index') }}">
    <input type="hidden" id="urlStoreCambio" value="{{ route('cambios.store') }}">
    <input type="hidden" id="venta_sus" value="{{ $compra_venta->venta_sus }}">
    <input type="hidden" id="venta_bs" value="{{ $compra_venta->venta_bs }}">
    <input type="hidden" id="compra_sus" value="{{ $compra_venta->compra_sus }}">
    <input type="hidden" id="compra_bs" value="{{ $compra_venta->compra_bs }}">
    <input type="hidden" id="sucursalId" value="{{ $sucursal->id }}">
    <input type="hidden" id="sucursalNombre" value="{{ $sucursal->nombre }}">

    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        let modo_cambio = 'Bolivianos a Dólares';//VENTA
        // Dólares a Bolivianos//COMPRA
        $(document).ready(function() {
            $('#mensaje_tipo').hide();

            $('#btnCambioMonedas').click(function(){
                modo_cambio = 'Bolivianos a Dólares';
                $('#modalStoreCambio').removeClass('modal-success');
                $('#modalStoreCambio').addClass('modal-primary');
                $('#titulo').text('NUEVA VENTA');
                $('#modalStoreCambio').modal('show');
            });
            $('#btnCambioMonedasCompra').click(function(){
                modo_cambio = 'Dólares a Bolivianos';
                $('#modalStoreCambio').removeClass('modal-primary');
                $('#modalStoreCambio').addClass('modal-success');
                $('#titulo').text('NUEVA COMPRA');
                $('#modalStoreCambio').modal('show');
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
                $('#formCambio').bootstrapValidator('revalidateField', 'txtFecha');
            });

            $(document).on('change keyup', '#txtMonto', function() {
                let valor = $(this).val();
                let equi = 0;
                if (valor != '') {
                    $('#mensaje_tipo').show();
                    if (modo_cambio == 'Dólares a Bolivianos') {
                        equi = parseFloat(valor) * parseFloat($('#compra_bs').val());
                    } else {
                        equi = parseFloat(valor) / parseFloat($('#venta_bs').val());
                    }
                } else {
                    $('#mensaje_tipo').hide();
                }
                $('#txtEquivalencia').val(equi.toFixed(2));
            });

            $('#formCambio').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    txtFecha: {
                        message: 'Fecha no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Fecha es requerida'
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
                    txtCliente: {
                        message: 'Valor no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            },
                        }
                    },
                    txtNit: {
                        message: 'Valor no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            },
                        }
                    },
                    txtMonto: {
                        message: 'Valor no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            },
                        },

                    },
                    txtEquivalencia: {
                        message: 'Valor no valido',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            },
                        }
                    },
                }
            });

            $('#btnRegistrar').click(function() {
                var validarFormulario = $("#formCambio").data('bootstrapValidator');
                validarFormulario.validate();
                if (validarFormulario.isValid()) {
                    let data = $('#formCambio').serialize();
                    data+='&txtModoCambio='+modo_cambio;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: $('#urlStoreCambio').val(),
                        data: data,
                        dataType: "json",
                        success: function(response) {
                            listar();
                            $('#modalStoreCambio').modal('hide');
                            fnLimpiarControles();
                        }
                    });
                }
            });
        });

        function fnImprimirCambio(elemento) {
            var route = elemento.attr('data-url');
            $("#reporteModal").modal();

            //console.log(src);
            console.log("entrooo");
            var src = route;
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);
            $("#dialog").show();
        }

        function listar() {
            $.ajax({
                type: "GET",
                url: $('#urlLista').val(),
                dataType: "json",
                success: function(response) {
                    $('#contLista').html(response)
                }
            });
        }
    </script>
@endsection
