@extends('layout.principal')
@include('categoria_clientes.modals.nueva_categoria')
@include('categoria_clientes.modals.update_categoria')

@section('main-content')
    <script>
        function fnEditarCategoria(datos) {
            $("#id").val(datos.id);
            $("#e_nombre").val(datos.nombre);
            $("#e_numero_contratos").val(datos.numero_contratos);
            $("#e_porcentaje").val(datos.porcentaje);
        }

        function fnEliminarCategoria(id)
        {
            var route="/categoria_clientes/destroy/"+id+"";	        
	        Swal.fire({   title: "Esta seguro de eliminar este registro?", 
	          text: "Presione Si para eliminar el registro de la base de datos!", 
	          type: "warning",   showCancelButton: true,
	          confirmButtonColor: "#DD6B55",   
	          confirmButtonText: "Si, Eliminar!",
	          closeOnConfirm: false,
	          cancelButtonText: 'Cancelar', 
	        }).then((result) => {
	        	if (result.value) {
	        		$.ajax({
	                    url: route,
	                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	                    type: 'DELETE',
	                    dataType: 'json',	                 
	                    success: function(data){

                            Swal.fire({
                                title: "CATEGORÍA!",
                                text: "Se elimino correctamente!!",
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: "#66BB6A",
                                type: "success"
                            });
                            fnLimpiarControles();
                            fnListarCategorias('/categoria_clientes');
	                    },
                        error: function(result) {
                            Swal.fire("Opss..!", "No se pudo eliminar el registro!", "error")
	                    }
	                });

	        	}
			})
        }

        /******************************** LIMPIA CONTRAOLES  *****************************************/
        function fnLimpiarControles() {
            $("#nombre").val('');
            $("#numero_contratos").val('');
            $("#porcentaje").val('');
            $('#frmCategorias').bootstrapValidator('frmCategorias', true);

            $("#id").val('');
        }
    </script>

    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token">
        --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        CATEGORÍA CLIENTES
                        <small>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateCategoria"><i class="icon-new-tab position-left"></i>Nuevo</button>
                        </small>
                    </h3>
                </div>
            </section>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-md-3 form-group">
            <label>Fecha: </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="date" class="form-control" value="{{ Carbon\Carbon::now('America/La_Paz')->format('Y-m-d') }}"
                    id="txtBuscaFecha" placeholder="Fecha">
            </div>
        </div>
        <div class="col-md-2 form-group">
            <label>&nbsp;</label>
            <p>
                <button type="button" class="btn btn-warning btn-xs" onclick="fnBuscarPersonas()"><i
                        class="icon-search4 position-left"></i>Buscar</button>
            </p>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-md-12">
            <section class="clsPrecios" id="contPrecios">
                @include('categoria_clientes.modals.lista_categorias')
            </section>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
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

            function fnListarCategorias(url) {
                var parametros = {
                    'fecha': $("#txtBuscaFecha").val(),
                };
                var route = url;
                $.ajax({
                    type: 'GET',
                    url: route,
                    data: parametros,
                    //dataType: 'json',
                    success: function(data) {
                        $('#contPrecios').html(data.html);
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "PRECIO...",
                            text: "No se pudo cargar los registros",
                            confirmButtonColor: "#66BB6A",
                            confirmButtonText: 'Aceptar',
                            type: "error"
                        });
                    }
                });
            }

            /******************** VALIDAR FROMULARIO PARA LA ACTUALIZACION ******************/
            $('#frmCategorias').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    nombre: {
                        message: 'Información no valida',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    numero_contratos: {
                        message: 'Información no valida',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    porcentaje: {
                        message: 'Información no valida',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                }
            });

            $('#frmCategoriasEdit').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    e_nombre: {
                        message: 'Información no valida',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    e_numero_contratos: {
                        message: 'Información no valida',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                    e_porcentaje: {
                        message: 'Información no valida',
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            }
                        }
                    },
                }
            });


            $('#btnRegistrar').click(function(){
                var validaForm = $("#frmCategorias").data('bootstrapValidator');
                validaForm.validate();
                //$('#myCreate').html('<div><img src="../img/ajax-loader.gif"/></div>');
                if (validaForm.isValid()) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "/categoria_clientes/store",
                        data: {
                            nombre: $("#nombre").val(),
                            numero_contratos: $("#numero_contratos").val(),
                            porcentaje: $("#porcentaje").val(),
                        },
                        dataType: "json",
                        success: function (response) {
                            fnListarCategorias('/categoria_clientes');
                            $('#modalCreateCategoria').modal('toggle');
                            Swal.fire({
                                title: "CATEGORÍA...",
                                text: "Registro éxitoso",
                                confirmButtonColor: "#66BB6A",
                                // EF5350 |color error
                                confirmButtonText: 'Aceptar',
                                type: "success"
                            });
                        }
                    });
                }
            });

            $("#btnActualizar").click(function() {
                let url = '/categoria_clientes/update/' + $('#id').val();
                var validaForm = $("#frmCategoriasEdit").data('bootstrapValidator');
                validaForm.validate();
                //$('#myCreate').html('<div><img src="../img/ajax-loader.gif"/></div>');
                if (validaForm.isValid()) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "PUT",
                        url: url,
                        data: {
                            nombre: $("#e_nombre").val(),
                            numero_contratos: $("#e_numero_contratos").val(),
                            porcentaje: $("#e_porcentaje").val(),
                        },
                        dataType: 'json',
                        success: function(response) {
                            fnListarCategorias('/categoria_clientes');
                            $('#modalEditCategoria').modal('toggle');
                            Swal.fire({
                                title: "CATEGORÍA...",
                                text: "Registro actualizado con éxito",
                                confirmButtonColor: "#66BB6A",
                                // EF5350 |color error
                                confirmButtonText: 'Aceptar',
                                type: "success"
                            });
                        }
                    });
                }

            });
        });

    </script>
@endsection
