@extends('layout.principal')
{{-- @include('administracion.usuario.modals.modalCreate') --}}
@include('monedas.modal.modalUpdateMoneda')

@section('main-content')
    <script>
        function fnEditarMoneda(datos) {
            $("#id").val(datos.id);
            $("#txtMoneda").val(datos.moneda);
            $("#txtMonedaDesc").val(datos.desc_corta);
        }

        /******************************** LIMPIA CONTRAOLES  *****************************************/
        function fnLimpiarControles() {
            $("#id").val('');
            $("#txtMoneda").val('');
            $("#txtMonedaDesc").val('');
            $('#frmUsuario').bootstrapValidator('frmMoneda', true);
        }
    </script>

    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        MONEDAS
                        <small>
                            <a href="#" id="btnCambioMonedas" data-toggle="modal" data-target="#modalValorCambio"
                                class="btn btn-primary">Valor de
                                Cambio</a>
                        </small>
                    </h3>
                </div>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section class="clsMonedas" id="contMonedas">
                @include('monedas.modal.listaMonedas')
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        VALORES DE COMPRA Y VENTA
                        <small>
                            <a href="#" id="btnCompraVenta" data-toggle="modal" data-target="#modalUpdateCompraVenta"
                                class="btn btn-primary">Actualizar Valores</a>
                        </small>
                    </h3>
                </div>
            </section>
        </div>
    </div>
    <div clas="row">
        <div class="col-md-12">
            <section class="clsMonedas" id="contCompraVenta">
                @include('monedas.modal.listaCompraVenta')
            </section>
        </div>
    </div>


    @include('monedas.modal.modalValorCambio')
    @include('monedas.modal.modalCompraVenta')
    <input type="hidden" id="_valorSus" value="{{ $cambio->valor_sus }}">
    <input type="hidden" id="_valorBs" value="{{ $cambio->valor_bs }}">

    <input type="hidden" id="urlListaMonedas" value="{{ route('monedas.index') }}">
    <input type="hidden" id="urlUpdateMoneda" value="{{ route('monedas.update') }}">
    <input type="hidden" id="urlActualizaCambio" value="{{ route('monedas.actualizaCambio') }}">
    <input type="hidden" id="urlListaCompraVenta" value="{{ route('compra_ventas.index') }}">
    <input type="hidden" id="urlActualizaCompraVenta" value="{{ route('compra_ventas.update') }}">

    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        let _valorSus = $('#_valorSus');
        let _valorBs = $('#_valorBs');
        $(document).ready(function() {
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

            $('#btnCambioMonedas').click(function() {
                let txtSus = $('#txtSus');
                let txtBs = $('#txtBs');
                txtSus.val(_valorSus.val());
                txtBs.val(_valorBs.val());
            });

            $('#btnActualizarCambio').click(function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "PUT",
                    url: $('#urlActualizaCambio').val(),
                    data: {
                        valor_sus: $('#txtSus').val(),
                        valor_bs: $('#txtBs').val(),
                    },
                    dataType: 'json',
                    success: function(response) {
                        _valorSus.val($('#txtSus').val());
                        _valorBs.val($('#txtBs').val());
                        $('#modalValorCambio').modal('toggle');
                        Swal.fire({
                            title: "MONEDA...",
                            text: "Registro actualizado con éxito",
                            confirmButtonColor: "#66BB6A",
                            // EF5350 |color error
                            confirmButtonText: 'Aceptar',
                            type: "success"
                        });
                    }
                });
            });

            function fnListadoMonedas(url) {
                var route = url;
                $.ajax({
                    type: 'GET',
                    url: route,
                    //dataType: 'json',
                    success: function(data) {
                        $('#contMonedas').html(data.html);
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "MONEDA...",
                            text: "No se pudo cargar los registros",
                            confirmButtonColor: "#66BB6A",
                            confirmButtonText: 'Aceptar',
                            type: "error"
                        });
                    }
                });
            }

            function fnListadoCompraVenta(url) {
                var route = url;
                $.ajax({
                    type: 'GET',
                    url: route,
                    //dataType: 'json',
                    success: function(data) {
                        $('#contCompraVenta').html(data.html);
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "COMPRA VENTA...",
                            text: "No se pudo cargar los registros",
                            confirmButtonColor: "#66BB6A",
                            confirmButtonText: 'Aceptar',
                            type: "error"
                        });
                    }
                });
            }

            $('#btnActualizaVentaCompra').click(function() {
                $.ajax({
                    url: $('#urlActualizaCompraVenta').val(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'PUT',
                    dataType: 'json',
                    data: {
                        venta_sus: $('#txtVentaSus').val(),
                        venta_bs: $('#txtVentaBs').val(),
                        compra_sus: $('#txtCompraSus').val(),
                        compra_bs: $('#txtCompraBs').val(),
                    },
                    success: function(data) {
                        resultado = data.Mensaje;
                        $("#modalUpdateCompraVenta").modal('toggle');
                        $('#txtVentaSus').val(parseFloat(data.compra_venta.venta_sus).toFixed(2));
                        $('#txtVentaBs').val(parseFloat(data.compra_venta.venta_bs).toFixed(2));
                        $('#txtCompraSus').val(parseFloat(data.compra_venta.compra_sus).toFixed(2));
                        $('#txtCompraBs').val(parseFloat(data.compra_venta.compra_bs).toFixed(2));
                        Swal.fire({
                            title: "MONEDA!",
                            text: "Se actualizo correctamente!!",
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        });
                        fnListadoCompraVenta($('#urlListaCompraVenta').val());
                    },
                    error: function(result) {
                        console.log(result);
                        Swal.fire("Opss..!",
                            "La Moneda no se puedo actualizar intente de nuevo!",
                            "error")
                    }
                });
            });

            /******************** VALIDAR FROMULARIO PARA LA ACTUALIZACION ******************/
            $('#frmMoneda').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    moneda: {
                        message: 'Moneda no valida',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    desc_corta: {
                        message: 'Descripción no valida',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            },
                            stringLength: {
                                min: 2,
                                max: 20,
                                message: 'La descripción corta debe tener minimo 2 caracteres'
                            }
                        }
                    },
                }
            });



            $("#btnActualizar").click(function() {
                var value = $("#id").val();
                var route = $('#urlUpdateMoneda').val();
                var validaMoneda = $("#frmMoneda").data('bootstrapValidator');
                validaMoneda.validate();
                //$('#myCreate').html('<div><img src="../img/ajax-loader.gif"/></div>');
                if (validaMoneda.isValid()) {
                    $.ajax({
                        url: route,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'PUT',
                        dataType: 'json',
                        data: {
                            'id': value,
                            'moneda': $("#txtMoneda").val(),
                            'desc_corta': $("#txtMonedaDesc").val(),
                        },
                        success: function(data) {
                            resultado = data.Mensaje;
                            $("#modalUpdateMoneda").modal('toggle');
                            Swal.fire({
                                title: "MONEDA!",
                                text: "Se actualizo correctamente!!",
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: "#66BB6A",
                                type: "success"
                            });
                            fnLimpiarControles();
                            fnListadoMonedas($('#urlListaMonedas').val());
                        },
                        error: function(result) {
                            console.log(result);
                            Swal.fire("Opss..!",
                                "La Moneda no se puedo actualizar intente de nuevo!",
                                "error")
                        }
                    });
                }

            });


        });
    </script>
@endsection
