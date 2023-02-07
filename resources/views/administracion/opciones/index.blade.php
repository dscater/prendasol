@extends('layout.inicio')
@include('administracion.opciones.modals.modalCreate')
@include('administracion.opciones.modals.modalUpdate')
@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					OPCIONES
					<small>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateOpciones"><i class="icon-new-tab position-left"></i>Nuevo</button>
					</small>
				</h3>
			</div>
			</section>
		</div>		
	</div>
	<div class="row">        
        <div class="col-sm-5 form-group">
            <div class="input-group">
                <input class="form-control" id="txtBuscarOpcion" placeholder="Buscar Opción" name="txtBuscarOpcion"type="text" onkeydown="if (event.keyCode == 13) fnBuscarOpciones()"/>
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-warning"  onclick="fnBuscarOpciones()">Buscar</button>
                </div>
            </div>
        </div>
    </div>
	<div class="row">            
        <div class="col-md-12">            
                <section class="clsOpciones">
                	@include('administracion.opciones.modals.listadoOpciones')                    
                </section>
        </div>
    </div>

	<script type="text/javascript">    
		$(document).ready(function() {	
			/*/******************** ANULAR ENTER EN FORMULARIOS ******************/
			$('form').keypress(function(e){   
				if(e == 13){
					return false;
				}
			});

			/*/******************** ANULAR ENTER EN FORMULARIOS ******************/
			$('input').keypress(function(e){
				if(e.which == 13){
					return false;
				}
			});
	    	
	    	/******************** VALIDAR FROMULARIO PARA LA INSERCION ******************/
	        $('#frmOpcion').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                txtOpcion: {
	                    message: 'Opción no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Opción es requerida'
	                        },
	                        stringLength: {
	                            min: 4,
	                            max: 20,
	                              message: 'Opción requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z]+$)/,
	                            message: 'Opción de la persona solo puede ser alfabetico'
	                        }
	                    }
	                },
	                txtUrl: {
	                    message: 'URL no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'URL es requerida'
	                        },
	                        // stringLength: {
	                        //     min: 4,
	                        //     max: 20,
	                        //       message: 'URL requiere mas de 4 letras y un limite de 20'
	                        // },
	                        // regexp: {
	                        //     regexp: /(\s*[a-zA-Z]+$)/,
	                        //     message: 'URL de la persona solo puede ser alfabetico'
	                        // }
	                    }
	                },
	                ddlModulo: {
	                    message: 'Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Módulo es requerida'
	                        }	                        
	                    }
	                }
	            }
	        });

	        /******************** VALIDAR FORMULARIO PARA LA ACTUALIZACION ******************/
	        $('#frmOpcionA').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                txtRolA: {
	                    message: 'El Rol no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'El Rol es requerida'
	                        },
	                        stringLength: {
	                            min: 4,
	                            max: 20,
	                              message: 'El Rol requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z]+$)/,
	                            message: 'El Rol de la persona solo puede ser alfabetico'
	                        }
	                    }
	                }
	            }
	        });


	        /******************** FUNCION PARA LA PAGINACIÓN AL HACER CLICK MEDIANTE AJAX ******************/
	        $('body').on('click', '.pagination a', function(e) {
		        e.preventDefault();

		        //$('#load a').css('color', '#dfecf6');
		        //$('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

		        var url = $(this).attr('href');  
		        fnListadoOpciones(url);
		        window.history.pushState("", "", url);
		    });
	    });


		/******************************** REGISTRA OPCIONES *****************************************/
	    $("#btnRegistrar").click(function(){	    	
	        var route="/Opcion";
	     	//var token =$("#token").val();
	     	var validarOpcion = $("#frmOpcion").data('bootstrapValidator'); 
	     	console.log(validarOpcion);
	        validarOpcion.validate();
	        if(validarOpcion.isValid())
	        {
	        	$.ajax({
		            url: route,
		            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		            type: 'POST',
		            data: {
		            	'ddlModulo':$("#ddlModulo").val(),
		            	'txtOpcion':$("#txtOpcion").val(),
		            	'txtUrl':$("#txtUrl").val()
		            },
	                success: function(data){
	                	console.log(data.Mensaje);
	                	resultado = data.Mensaje;

	                	/*resultado = 1  ROL GUARDADO*/
	                	if (resultado == 1) {
                        	$("#modalCreateOpciones").modal('toggle');
                            swal({
					            title: "OPCIÓN!",
					            text: "Se registro correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoOpciones('/Opcion');
                        }

	                	/*resultado = 2  EXISTE EL ROL*/
	                	if (resultado == 2) {
                            swal({
					            title: "OPCIÓN...",
					            text: 'La Opción: <b>'+  $("#txtOpcion").val() +'</b> ya existe en la base de datos',
					            html: true,
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
                        
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {                                
                            swal({
					            title: "OPCIÓN...",
					            text: "hubo problemas al registrar en BD",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            swal({
					            title: "OPCIÓN...",
					            text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
	                },
	                error: function(result) {
                        //swal("Opss..!", "Succedio un problema al registrar inserte bien los datos!", "error");
	                }
		        });
	        }	        
	    });

	    /******************************** ACTUALIZA DATOS DE OPCION *****************************************/
	    $("#btnActualizar").click(function(){
	        var value =$("#id").val();
	        var route="/Opcion/"+value+"";
	        var validarOpcion = $("#frmOpcionA").data('bootstrapValidator'); 
	        validarOpcion.validate();
	        //$('#myCreate').html('<div><img src="../img/ajax-loader.gif"/></div>');
	        if(validarOpcion.isValid())
	        {
	            $.ajax({
	                url: route,
	                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	                type: 'PUT',
	                dataType: 'json',
	                data: {
	                    'ddlModulo':$("#ddlModuloA").val(),
		            	'txtOpcion':$("#txtOpcionA").val(),
		            	'txtUrl':$("#txtUrlA").val()
	                },
	                success: function(data){	                    
	                    resultado = data.Mensaje;
	                	/*resultado = 1  OPCION GUARDADO*/
	                	if (resultado == 1) {
                        	$("#modalUpdateOpciones").modal('toggle');
                            swal({
					            title: "OPCIÓN!",
					            text: "Se actualizo correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoOpciones('/Opcion');
                        }

	                	/*resultado = 2  EXISTE LA OPCION*/
	                	if (resultado == 2) {
                            swal({
					            title: "OPCIÓN...",
					            text: 'La opción: <b>'+  $("#txtOpcionA").val() +'</b> ya existe en la base de datos',
					            html: true,
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
                        
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {                                
                            swal({
					            title: "OPCIÓN...",
					            text: "hubo problemas al registrar en BD",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            swal({
					            title: "OPCIÓN...",
					            text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
	                    
	                     
	                },  error: function(result) {
	                      console.log(result);
	                     swal("Opss..!", "La Persona no se puedo actualizar intente de nuevo!", "error")
	                }
	            });
	        }
	        
	    });

	    /******************************** LISTADO DE OPCIONES *****************************************/
	    function fnListadoOpciones(url) {
	    	var route =url;	    	
	    	$.get(route, function(data){
	    		$('.clsOpciones').html(data);  
	    	}).fail(function () {
	    		swal({
		            title: "OPCION...",
		            text: "Los roles no se puede cargar",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });	            
	        });
	    }

	    /******************************** BUSCA OPCIONES *****************************************/
	    function fnBuscarOpciones() {
	    	var parametros = {'txtBuscarOpcion':$("#txtBuscarOpcion").val()};
	    	var route ="/BuscarOpciones";
	    	//console.log("content",content);
	    	
	    	// var route =url;
	    	$.get(route,parametros, function(data){
	    		//console.log("data",data);
	    		$('.clsOpciones').html(data);  
	    	}).fail(function () {
	    		swal({
		            title: "OPCIONES...",
		            text: "Los roles no se puede cargar",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });	            
	        });
	        
	    }
 
	    /******************************** OBTIENE DATOS DE OPCIONES *****************************************/
	    function fnEditarOpcion(datos) {
	    	console.log("datos",datos.modulo_id);
	    	$("#ddlModuloA").val(datos.modulo_id).change();		    
		    $("#txtOpcionA").val(datos.opcion);
		    $("#txtUrlA").val(datos.url);
	    	$("#id").val(datos.id);
	    }

	    /******************************** LIMPIA CONTROLES  *****************************************/
	    function fnLimpiarControles(){
	    	$("#ddlModuloA").val('').change();	
	    	$("#ddlModulo").val('').change();	
		    $("#txtOpcion").val('');
		    $("#txtUrl").val('');
		    $("#ddlModuloA").val('');
		    $("#txtOpcionA").val('');
		    $("#txtUrlA").val(''),
	    	$("#id").val('');
	    	$('#frmOpcionA').bootstrapValidator('resetForm', true);
	    	$('#frmOpcion').bootstrapValidator('resetForm', true);

	    }	    

	    /******************************** ELIMINA DATOS OPCION *****************************************/
	    function fnEliminarOpcion(id){
	        var route="/Opcion/"+id+"";	        
	        swal({   title: "Esta seguro de eliminar la Opción?", 
	          text: "Presione Si para eliminar el registro de la base de datos!", 
	          type: "warning",   showCancelButton: true,
	          confirmButtonColor: "#DD6B55",   
	          confirmButtonText: "Si, Eliminar!",
	          closeOnConfirm: false 
	        }, function(){  
	           $.ajax({
                    url: route,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'DELETE',
                    dataType: 'json',	                 
                    success: function(data){
                        console.log(data.Mensaje);
	                	resultado = data.Mensaje;

	                	/*resultado = 1  OPCION ELIMINADO*/
	                	if (resultado == 1) {
                            swal({
					            title: "ROL!",
					            text: "Se elimino correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoOpciones('/Opcion');
                        }
                        
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {                                
                            swal({
					            title: "OPCION...",
					            text: "hubo problemas al registrar en BD",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            swal({
					            title: "OPCION...",
					            text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
                    },
                        error: function(result) {
                            swal("Opss..!", "El La persona tiene registros en otras tablas!", "error")
                    }
                });
	        });
	    }

	    /******************************** HABILITAR OPCION *****************************************/
	    function fnHabilitarOpcion(id){
	        var route="/HabilitarOpcion/"+id+"";	        
	        swal({   title: "Esta seguro de habilitar la Opción?", 
	          text: "Presione Si para habilitar el registro de la base de datos!", 
	          type: "warning",   showCancelButton: true,
	          confirmButtonColor: "#DD6B55",   
	          confirmButtonText: "Si, Habilitar!",
	          closeOnConfirm: false,
	          cancelButtonText: 'Cancelar', 
	        }, function(){  
	           $.ajax({
                    url: route,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'DELETE',
                    dataType: 'json',	                 
                    success: function(data){
                        console.log(data.Mensaje);
	                	resultado = data.Mensaje;

	                	/*resultado = 1  USUARIO ELIMINADO*/
	                	if (resultado == 1) {
                            swal({
					            title: "OPCIÓN!",
					            text: "Se habilito correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoOpciones('/Opcion');
                        }
                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            swal({
					            title: "USUARIO...",
					            text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
                    },
                        error: function(result) {
                            swal("Opss..!", "El La persona tiene registros en otras tablas!", "error")
                    }
                });
	        });
	    }
	</script>
@endsection

