@extends('layout.principal')
@include('administracion.usuario.modals.modalCreate')
@include('administracion.usuario.modals.modalUpdate')

@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					USUARIOS
					<small>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateUsuario"><i class="icon-new-tab position-left"></i>Nuevo</button>
					</small>
				</h3>
			</div>
			</section>
		</div>		
	</div>
	<div class="row">
		<div class="col-md-2 form-group">
			<label>Numero Identificación: </label>
			<div class="input-group">
				<span class="input-group-addon"><i class="icon-list-numbered"></i></span>
				<input type="text" class="form-control" id="txtBuscarIdentifiacion" placeholder="Identificación">
			</div>
		</div>
		<div class="col-md-2 form-group">
			<label>Primer Apellido: </label>
			<div class="input-group">
				<span class="input-group-addon"><i class="icon-user"></i></span>
				<input type="text" class="form-control" id="txtBuscarPaterno" placeholder="Primer Apellido">
			</div>
		</div>
		<div class="col-md-2 form-group">
			<label>Segundo Apellido: </label>
			<div class="input-group">
				<span class="input-group-addon"><i class="icon-user"></i></span>
				<input type="text" class="form-control" id="txtBuscarMaterno" placeholder="Segundo Apellido">
			</div>
		</div>
		<div class="col-md-2 form-group">
			<label>Nombres: </label>
			<div class="input-group">
				<span class="input-group-addon"><i class="icon-users"></i></span>
				<input type="text" class="form-control" id="txtBuscarNombres" placeholder="Nombres">
			</div>
		</div>
		<div class="col-md-2 form-group">
			<label>Fecha Nacimiento: </label>
			<div class="input-group input-append date" id="datePickerBuscarFN">
                <input type="text" class="form-control" id="txtBuscarFechaNacimiento" name="txtBuscarFechaNacimiento" placeholder="Fecha Nacimiento" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>  
		</div>
		<div class="col-md-2 form-group">
			<label>.</label>
			<p><button type="button" class="btn btn-warning btn-xs" onclick="fnBuscarUsuarios()"><i class="icon-search4 position-left"></i>Buscar</button></p>
		</div>
	</div>	
	<div class="row">            
        <div class="col-md-12">            
                <section class="clsUsuarios">
                	@include('administracion.usuario.modals.listadoUsuarios')                    
                </section>
            
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
	<script type="text/javascript"> 	
		var red = "";
		var establecimiento = "";
		$(document).ready(function() {

			$("#ddlPersona").select2({
				ajax: { 
					url: "/BuscarPersonasNoRegistradas",
					type: "get",
					dataType: 'json',
					delay: 250,
					data: function (params) {
						return {
							searchTerm: params.term // search term
						};
					},
					processResults: function (response) {
						console.log(response);
						var arr = [];
						$.each(response, function (index, value) {
							console.log(value);
				            arr.push({
				                id: value.id,
				                text: value.primerapellido +' '+ value.segundoapellido +' '+ value.nombres
				            })
				        })
						return {			
							results: arr
						};
					},
					cache: true
				},
				minimumInputLength: 3,
				language: {
					inputTooShort: function() {
						return 'Por favor, introduzca 3 o más caracteres';
					},
					noResults: function(){
			           return "No se obtuvo ningun resultado";
			       	}
				}
			});	

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
	        $('#frmUsuario').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                ddlPersona: {
	                    message: 'Persona no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Persona es requerida'
	                        }
	                    }
	                },
	                txtUsuario: {
	                    message: 'Usuario no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Usuario es requerida'
	                        },
	                        stringLength: {
	                            min: 4,
	                            max: 20,
	                              message: 'Usuario requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z]+$)/,
	                            message: 'Usuario solo puede ser alfabetico'
	                        }
	                    }
	                },
	                txtContrasena: {
	                    message: 'Contraseña no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Contraseña es requerida'
	                        },
	                        callback: {
		                        message: 'The password is not valid',
		                        callback: function(value, validator, $field) {
		                            if (value === '') {
		                                return true;
		                            }

		                            // Check the password strength
		                            if (value.length < 7) {
		                                return {
		                                    valid: false,
		                                    message: 'Debe tener más de 7 caracteres de largo'
		                                };
		                            }

		                            // The password doesn't contain any uppercase character
		                            if (value === value.toLowerCase()) {
		                                return {
		                                    valid: false,
		                                    message: 'Debe contener al menos un carácter en mayúscula'
		                                }
		                            }

		                            // The password doesn't contain any uppercase character
		                            if (value === value.toUpperCase()) {
		                                return {
		                                    valid: false,
		                                    message: 'Debe contener al menos un carácter en minúsculas'
		                                }
		                            }

		                            // The password doesn't contain any digit
		                            if (value.search(/[0-9]/) < 0) {
		                                return {
		                                    valid: false,
		                                    message: 'Debe contener al menos un dígito'
		                                }
		                            }

		                            return true;
		                        }
		                    }
	                    }
	                },
	                txtContrasenaCopia: {
	                    message: 'Contraseña no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Contraseña es requerida'
	                        },
	                        callback: {
		                        message: 'The password is not valid',
		                        callback: function(value, validator, $field) {
		                            if (value === '') {
		                                return true;
		                            }

		                            // Check the password strength
		                            if (value.length < 7) {
		                                return {
		                                    valid: false,
		                                    message: 'Debe tener más de 7 caracteres de largo'
		                                };
		                            }

		                            // The password doesn't contain any uppercase character
		                            if (value === value.toLowerCase()) {
		                                return {
		                                    valid: false,
		                                    message: 'Debe contener al menos un carácter en mayúscula'
		                                }
		                            }

		                            // The password doesn't contain any uppercase character
		                            if (value === value.toUpperCase()) {
		                                return {
		                                    valid: false,
		                                    message: 'Debe contener al menos un carácter en minúsculas'
		                                }
		                            }

		                            // The password doesn't contain any digit
		                            if (value.search(/[0-9]/) < 0) {
		                                return {
		                                    valid: false,
		                                    message: 'Debe contener al menos un dígito'
		                                }
		                            }

		                            return true;
		                        }
		                    },
	                        identical: {
		                        field: 'txtContrasena',
		                        message: 'No coinciden las contraseñas'
		                    }
	                    }
	                },
	                ddlDepartamento: {
	                    message: 'Departamento no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Departamento es requerida'
	                        }
	                    }
	                },
	                ddlRed: {
	                    message: 'Area no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Area es requerida'
	                        }
	                    }
	                },
	                ddlEstablecimiento: {
	                    message: 'Establecimiento no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Establecimiento es requerida'
	                        }
	                    }
	                },
	                rdoTipoDestino:{
	                	message: 'Tipo de Destino no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Tipo de Destino es requerida'
	                        }
	                    }
	                },
	                ddlRol: {
	                    message: 'El Rol no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'El Rol es requerida'
	                        }
	                    }
	                }
	            }
	        });

	        /******************** VALIDAR FROMULARIO PARA LA ACTUALIZACION ******************/
	        $('#frmUsuarioA').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                ddlPersonaA: {
	                    message: 'Persona no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Persona es requerida'
	                        }
	                    }
	                },
	                txtUsuarioA: {
	                    message: 'Usuario no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Usuario es requerida'
	                        },
	                        stringLength: {
	                            min: 4,
	                            max: 20,
	                              message: 'Usuario requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z]+$)/,
	                            message: 'Usuario solo puede ser alfabetico'
	                        }
	                    }
	                },
	                txtContrasenaA: {
	                    message: 'Contraseña no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Contraseña es requerida'
	                        },
	                        stringLength: {
	                            min: 4,
	                            max: 10,
	                              message: 'Contraseña requiere mas de 4 letras y un limite de 10'
	                        }	                        
	                    }
	                },
	                txtContrasenaCopiaA: {
	                    message: 'Contraseña no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Contraseña es requerida'
	                        },
	                        stringLength: {
	                            min: 4,
	                            max: 10,
	                              message: 'Contraseña requiere mas de 4 letras y un limite de 10'
	                        },
	                        identical: {
		                        field: 'txtContrasena',
		                        message: 'No coinciden las contraseñas'
		                    }
	                    }
	                },
	                ddlDepartamentoA: {
	                    message: 'Departamento no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Departamento es requerida'
	                        }
	                    }
	                },
	                ddlRedA: {
	                    message: 'Area no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Area es requerida'
	                        }
	                    }
	                },
	                ddlEstablecimientoA: {
	                    message: 'Establecimiento no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Establecimiento es requerida'
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
		        fnListadoUsuarios(url);
		        window.history.pushState("", "", url);
		    });

		    $('#datePickerBuscarFN').datepicker({
		        format: "dd-mm-yyyy",
		        language: "es",
		        //autoclose: true,
		        orientation: "auto left",
			    forceParse: false,
			    autoclose: true,
			    todayHighlight: true,
			    toggleActive: true
		    });

		    /******************************** NOMBRE DE LA PERSONA PARA CONSTRUIR EL USUARIO *****************************************/
		    //$('#ddlPersona').on('change', function() {
		    //$('#ddlPersona').on('select2:select', function (e) {
		    $(document.body).on("change","#ddlPersona",function(){		        
		        valor = this.value; 
		        console.log("valorrr",this.value);
		        if (valor != '') {
		        	var route ="/GenerarUsuario/"+valor;	    	
			    	$.get(route, function(data){
			    		console.log(data);
			    		$("#txtUsuario").val(data);
			    		$('#frmUsuario').bootstrapValidator('revalidateField', 'txtUsuario');
			    	});
		        }	        
		    });
	    });

		


		/******************************** REGISTRA USUARIO *****************************************/
	    $("#btnRegistrar").click(function(){	
	    	
	        var route="/Usuario";
	     	//var token =$("#token").val();
	     	var validarUsuario = $("#frmUsuario").data('bootstrapValidator'); 
	     	console.log(validarUsuario);
	        validarUsuario.validate();
	        if(validarUsuario.isValid())
	        {
	        	$.ajax({
		            url: route,
		            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		            type: 'POST',
		            data: {
		            	'ddlPersona':$("#ddlPersona").val(),
		            	'txtUsuario':$("#txtUsuario").val(),
		            	//'txtContrasena':$("#txtContrasena").val(),
		            	//'rdoTipoDestino':$('input[name=rdoTipoDestino]:checked').val(),
		            	//'ddlEstablecimiento':$("#ddlEstablecimiento").val(),
		            	//'ddlDepartamento':$("#ddlDepartamento").val(),
		            	//'ddlRed':$("#ddlRed").val(),
		            	'ddlRol':$("#ddlRol").val()
		            },
	                success: function(data){
	                	console.log(data.Mensaje);
	                	resultado = data.Mensaje;

	                	/*resultado = 1  USUARIO GUARDADO*/
	                	if (resultado == 1) {
                        	$("#modalCreateUsuario").modal('toggle');
                            Swal.fire({
					            title: "USUARIO!",
					            text: "Se registro correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoUsuarios('/Usuario');
					        //$('.clsPersonas').html(data.personas); 
					        fnPersonasNoHabilitadas();   
					        //document.getElementById('divMostrarDepartamento').style.display = 'none'; 	

                        }

	                	/*resultado = 2  EXISTE EL USUARIO*/
	                	if (resultado == 2) {
                            Swal.fire({
					            title: "USUARIO...",
					            text: 'El USUARIO: <b>'+  $("#txtUsuario").val() +'</b> ya existe en la base de datos',
					            html: true,
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
                        
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {                                
                            Swal.fire({
					            title: "USUARIO...",
					            text: "hubo problemas al registrar en BD",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            Swal.fire({
					            title: "USUARIO...",
					            text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
	                },
	                error: function(result) {
                        //Swal.fire("Opss..!", "Succedio un problema al registrar inserte bien los datos!", "error");
	                }
		        });
	        }	        
	    });

	    /******************************** ACTUALIZA DATOS DE ROLES *****************************************/
	    $("#btnActualizar").click(function(){
	        var value =$("#id").val();
	        var route="/Usuario/"+value+"";
	        var validarUsuario = $("#frmUsuarioA").data('bootstrapValidator'); 
	        validarUsuario.validate();
	        //$('#myCreate').html('<div><img src="../img/ajax-loader.gif"/></div>');
	        if(validarUsuario.isValid())
	        {
	            $.ajax({
	                url: route,
	                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	                type: 'PUT',
	                dataType: 'json',
	                data: {	                    		            	
		            	'rdoTipoDestino':$('input[name=rdoTipoDestinoA]:checked').val(),
		            	'ddlEstablecimiento':$("#ddlEstablecimientoA").val(),
		            	'ddlDepartamento':$("#ddlDepartamentoA").val(),
		            	'ddlRed':$("#ddlRedA").val(),
		            	'ddlRol':$("#ddlRolA").val()
	                },
	                success: function(data){	                    
	                    resultado = data.Mensaje;
	                	/*resultado = 1  USUARIO GUARDADO*/
	                	if (resultado == 1) {
                        	$("#modalUpdateUsuario").modal('toggle');
                            Swal.fire({
					            title: "USUARIO!",
					            text: "Se actualizo correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoUsuarios('/Usuario');
                        }

	                	/*resultado = 2  EXISTE EL ROL*/
	                	if (resultado == 2) {
                            Swal.fire({
					            title: "USUARIO...",
					            text: 'El Rol: <b>'+  $("#txtRol").val() +'</b> ya existe en la base de datos',
					            html: true,
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
                        
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {                                
                            Swal.fire({
					            title: "USUARIO...",
					            text: "hubo problemas al registrar en BD",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            Swal.fire({
					            title: "USUARIO...",
					            text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
	                    
	                     
	                },  error: function(result) {
	                      console.log(result);
	                     Swal.fire("Opss..!", "La Persona no se puedo actualizar intente de nuevo!", "error")
	                }
	            });
	        }
	        
	    });

	    /******************************** LISTADO DE ROLES *****************************************/
	    function fnListadoUsuarios(url) {
	    	console.log("funcionnnn");
	    	var route =url;	    	
	    	$.get(route, function(data){
	    		console.log("data",data.html);
	    		$('.clsUsuarios').html(data.html);  
	    	}).fail(function () {
	    		Swal.fire({
		            title: "ROL...",
		            text: "Los roles no se puede cargar",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });	            
	        });
	    }

	    /******************************** BUSCA ROLES *****************************************/
	    function fnBuscarUsuarios() {
	    	//console.log("valor",valor);
	    	//var parametros = {'txtBuscarUsuario':$("#txtBuscarUsuario").val()};
	    	var parametros = {
	    		'txtBuscarIdentifiacion':$("#txtBuscarIdentifiacion").val(),
	    		'txtBuscarNombres':$("#txtBuscarNombres").val(),
	    		'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
	    		'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
	    		'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
	    	};
	    	var route ="/BuscarUsuarios";
	    	//console.log("content",content);
	    	
	    	// var route =url;
	    	$.get(route,parametros, function(data){
	    		console.log("data",data.html);
	    		$('.clsUsuarios').html(data.html);  
	    	}).fail(function () {
	    		Swal.fire({
		            title: "ROL...",
		            text: "Los roles no se puede cargar",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });	            
	        });
	        
	    }
 
	    /******************************** OBTIENE DATOS DE USUARIOS *****************************************/
	    function fnEditarUsuario(datos) {
	    	console.log("datos",);
	    	$("#ddlPersonaA").val(datos.persona_id).change();
	    	$("#txtUsuarioA").val(datos.usuario);
	    	$("#txtPersonaA").val(datos.persona.nombres + ' ' + datos.persona.primerapellido + ' ' + datos.persona.segundoapellido);
	    	$("#ddlRolA").val(datos.usuario_rol.rol_id).change();
	    	$("input[name=rdoTipoDestinoA][value=" + datos.tipo_destino + "]").attr('checked', 'checked');
	    	$("#id").val(datos.id);
	    	fnTipoDestino(datos.tipo_destino);
	    	fnDatoEstablecimento(datos.centro_salud_id,datos.tipo_destino);
	    }

	    /******************************** LIMPIA CONTRAOLES  *****************************************/
	    function fnLimpiarControles(){
	    	$("#ddlPersona").val('').change();
	    	$("#txtUsuario").val('');
	    	$("#txtContrasena").val('');
	    	$("#ddlPersonaA").val('').change();
	    	$("#txtUsuarioA").val('');
	    	$("#txtContrasenaA").val('');
	    	$("#id").val('');
	    	$("#ddlRol").val('').change();
	    	$("#ddlRolA").val('').change();
	    	$("#ddlDepartamento").val('').change();
	    	$("#ddlRed").val('').change();
	    	$("#ddlEstablecimiento").val('').change();
	    	$("#ddlRed").html("");
            $("#ddlEstablecimiento").html("");
	    	$('#frmUsuario').bootstrapValidator('resetForm', true);
	    	$('#frmUsuarioA').bootstrapValidator('resetForm', true);
	    }	    

	    /******************************** ELIMINA DATOS USUARIO *****************************************/
	    function fnEliminarUsuario(id){
	        var route="/Usuario/"+id+"";	        
	        Swal.fire({   title: "Esta seguro de eliminar el Usuario?", 
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
	                        console.log(data.Mensaje);
		                	resultado = data.Mensaje;

		                	/*resultado = 1  USUARIO ELIMINADO*/
		                	if (resultado == 1) {
	                            Swal.fire({
						            title: "USUARIO!",
						            text: "Se elimino correctamente!!",
						            confirmButtonText: 'Aceptar',
						            confirmButtonColor: "#66BB6A",
						            type: "success"
						        });
						        fnLimpiarControles();
						        fnListadoUsuarios('/Usuario');
	                        }

		                	/*resultado = 2  EXISTE EL USUARIO*/
		                	if (resultado == 2) {
	                            Swal.fire({
						            title: "USUARIO...",
						            text: 'El Rol: <b>'+  $("#txtRol").val() +'</b> ya existe en la base de datos',
						            html: true,
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }
	                        
	                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
	                        if (resultado == 0) {                                
	                            Swal.fire({
						            title: "USUARIO...",
						            text: "hubo problemas al registrar en BD",
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }

	                        /*resultado = -1 SESION EXPIRADA*/
	                        if (resultado == "-1") {                                
	                            Swal.fire({
						            title: "USUARIO...",
						            text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }
	                    },
	                        error: function(result) {
	                            Swal.fire("Opss..!", "El La persona tiene registros en otras tablas!", "error")
	                    }
	                });

	        	}
			})

	        
	    }

	    /******************************** ELIMINA DATOS USUARIO *****************************************/
	    function fnResetearUsuario(id){
	        var route="/ResetearUsuario/"+id+"";	        
	        Swal.fire({   title: "Esta seguro de resetear la contraseña del Usuario?", 
	          text: "Presione Si para resetear la contraseña de la base de datos!", 
	          type: "warning",   showCancelButton: true,
	          confirmButtonColor: "#DD6B55",   
	          confirmButtonText: "Si, Resetear!",
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
	                        console.log(data.Mensaje);
		                	resultado = data.Mensaje;

		                	/*resultado = 1  USUARIO ELIMINADO*/
		                	if (resultado == 1) {
	                            Swal.fire({
						            title: "USUARIO!",
						            text: "Se reseteo la contraseña correctamente!!",
						            confirmButtonText: 'Aceptar',
						            confirmButtonColor: "#66BB6A",
						            type: "success"
						        });
						        fnLimpiarControles();
						        fnListadoUsuarios('/Usuario');
	                        }

		                	/*resultado = 2  EXISTE EL USUARIO*/
		                	if (resultado == 2) {
	                            Swal.fire({
						            title: "USUARIO...",
						            text: 'El Rol: <b>'+  $("#txtRol").val() +'</b> ya existe en la base de datos',
						            html: true,
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }
	                        
	                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
	                        if (resultado == 0) {                                
	                            Swal.fire({
						            title: "USUARIO...",
						            text: "hubo problemas al registrar en BD",
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }

	                        /*resultado = -1 SESION EXPIRADA*/
	                        if (resultado == "-1") {                                
	                            Swal.fire({
						            title: "USUARIO...",
						            text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }
	                    },
	                        error: function(result) {
	                            Swal.fire("Opss..!", "El La persona tiene registros en otras tablas!", "error")
	                    }
	                });
	        	}
			})
	    }

	   
	    

	    function fnPersonasNoHabilitadas(){
	    	var route ="/PersonasNoHabilitadas";	    	
	    	$.get(route, function(data){
	    		console.log("dataqqqqq",data);
	    		var htmlPersonas = '';
                htmlPersonas += '<option value="">-- Seleccione --</option>';
                $.each(data, function (ind, elem) {
                    htmlPersonas += '<option value='+elem.id+'>'+ elem.nombres +' '+ elem.primerapellido +' '+ elem.segundoapellido + '</option>';
                });
                $("#ddlPersona").html("");
                $('#ddlPersona').append(htmlPersonas);
	    	});
	    }

	    /******************************** HABILITAR DATOS USUARIO *****************************************/
	    function fnHabilitarUsuario(id){
	        var route="/HabilitarUsuario/"+id+"";	        
	        Swal.fire({   title: "Esta seguro de habilitar el Usuario?", 
	          text: "Presione Si para habilitar el registro de la base de datos!", 
	          type: "warning",   showCancelButton: true,
	          confirmButtonColor: "#DD6B55",   
	          confirmButtonText: "Si, Habilitar!",
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
	                        console.log(data.Mensaje);
		                	resultado = data.Mensaje;

		                	/*resultado = 1  USUARIO ELIMINADO*/
		                	if (resultado == 1) {
	                            Swal.fire({
						            title: "USUARIO!",
						            text: "Se habilito correctamente!!",
						            confirmButtonText: 'Aceptar',
						            confirmButtonColor: "#66BB6A",
						            type: "success"
						        });
						        fnLimpiarControles();
						        fnListadoUsuarios('/Usuario');
	                        }

		                	/*resultado = 2  EXISTE EL USUARIO*/
		                	if (resultado == 2) {
	                            Swal.fire({
						            title: "USUARIO...",
						            text: 'El Rol: <b>'+  $("#txtRol").val() +'</b> ya existe en la base de datos',
						            html: true,
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }
	                        
	                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
	                        if (resultado == 0) {                                
	                            Swal.fire({
						            title: "USUARIO...",
						            text: "hubo problemas al registrar en BD",
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }

	                        /*resultado = -1 SESION EXPIRADA*/
	                        if (resultado == "-1") {                                
	                            Swal.fire({
						            title: "USUARIO...",
						            text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }
	                    },
	                        error: function(result) {
	                            Swal.fire("Opss..!", "El La persona tiene registros en otras tablas!", "error")
	                    }
	                });
	        	}
			})
	    }

	    
	</script>
@endsection

