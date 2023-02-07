@extends('layout.principal')
@include('administracion.persona.modals.modalCreate')
@include('administracion.persona.modals.modalUpdate')

@section('main-content')
	<div class="row">
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					PERSONAS
					<small>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-keyboard="false"  data-target="#modalCreatePersona"><i class="icon-new-tab position-left"></i>Nuevo</button>
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
			{{-- <div class="input-group input-append date" id="datePickerBuscarFN">
                <input type="text" class="form-control" id="txtBuscarFechaNacimiento" name="txtBuscarFechaNacimiento" placeholder="Fecha Nacimiento" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div> --}} 
            <div class="input-group">
              	<div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
              	</div>
                <input type="text" id="txtBuscarFechaNacimiento" name="txtBuscarFechaNacimiento" class="form-control" data-mask>
            </div> 
		</div>
		<div class="col-md-2 form-group">
			<label>.</label>
			<p><button type="button" class="btn btn-warning btn-xs" onclick="fnBuscarPersonas()"><i class="icon-search4 position-left"></i>Buscar</button></p>
		</div>		
	</div>

	<div class="row">            
        <div class="col-md-12">            
            <section class="clsPersonas">
            	@include('administracion.persona.modals.listado')                    
            </section>            
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">    
	    $(document).ready(function() {	
			//alert("dasdads");
			$("#txtCI").numeric(false);
			$("#txtCIA").numeric(false);
			$("#txtTelefonoDomicilio").numeric(false);
			$("#txtTelefonoDomicilioA").numeric(false);
			$("#txtTelefonoTrabajo").numeric(false);
			$("#txtTelefonoTrabajoA").numeric(false);
			$("#txtCelular").numeric(false);
			$("#txtBuscarIdentifiacion").numeric(false);
			$('[data-mask]').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
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

		 	$('#datePickerFN').datepicker({
		        format: "dd-mm-yyyy",
		        language: "es",
		        //autoclose: true,
		        orientation: "auto left",
			    forceParse: false,
			    autoclose: true,
			    todayHighlight: true,
			    toggleActive: true
		    }).on('changeDate', function(e) {
		    	console.log("fsdfdsfsdf");
		        $('#frmPersona').bootstrapValidator('revalidateField', 'txtFechaNacimiento');
		    });

		    $('#datePickerFNA').datepicker({
		        format: "dd-mm-yyyy",
		        language: "es",
		        autoclose: true
		    }).on('changeDate', function(e) {
		        $('#frmPersonaA').bootstrapValidator('revalidateField', 'txtFechaNacimientoA');
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

		    /******************** VALIDAR FROMULARIO PARA LA INSERCION ******************/
	        $('#frmPersona').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                txtNombres: {
	                    message: 'Nombres no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Nombres es requerida'
	                        },
	                        stringLength: {
	                            min: 3,
	                            max: 20,
	                              message: 'El Nombre requiere mas de 3 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z]+$)/,
	                            message: 'El Nombre de la persona solo puede ser alfabetico'
	                        }
	                    }
	                },
	                txtPaterno: {
	                    message: 'Primer Apellido no es valida',
	                    validators: {
	                        // notEmpty: {
	                        //     message: 'Primer Apellido es requerida'
	                        // },
	                        stringLength: {
	                            min: 3,
	                            max: 20,
	                              message: 'Primer Apellido requiere mas de 3 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z-]+$)/,
	                            message: 'Primer Apellido de la persona solo puede ser alfabetico'
	                        }
	                    }
	                },
	                txtMaterno: {
	                    message: 'Segundo Apellido no es valida',
	                    validators: {
	                        // notEmpty: {
	                        //     message: 'Segundo Apellido es requerida'
	                        // },
	                        stringLength: {
	                            min: 4,
	                            max: 20,
	                              message: 'Segundo Apellido requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z]+$)/,
	                            message: 'Segundo Apellido de la persona solo puede ser alfabetico'
	                        }
	                    }
	                },
	                txtCI: {
	                    message: 'Número de documento no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Número de documento es requerida'
	                        },
	                        stringLength: {
	                            min: 4,
	                            max: 20,
	                              message: 'Número de documento requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /^[0-9]+$/, 
                            	message: 'Solo puede ingresar numeros enteros'
	                        }
	                    }
	                },
	                // ddlEstadocivil: {
	                //     message: 'Estado Civil no es valida',
	                //     validators: {
	                //         notEmpty: {
	                //             message: 'Estado Civil es requerida'
	                //         }	                        
	                //     }
	                // },
	                ddlExpedido: {
	                    message: 'Expedición no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Expedición es requerida'
	                        }	                        
	                    }
	                },

	                txtFechaNacimiento: {
	                    message: 'Fecha de Nacimiento no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Fecha de Nacimiento es requerida'
	                        },
	                        date: {
		                        format: 'DD-MM-YYYY',
		                        message: 'La fecha no es valido (01-01-2018)'
		                    }
	                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
	                    }
	                },

	                
	                txtCorreo: {                                                        
	                    validators: {
	                    	// notEmpty: {
	                     //        message: 'Correo es requerido'
	                     //    },
	                     //    stringLength: {
	                     //        min: 4,
	                     //        max: 50,
	                     //        message: 'El correo requiere mas de 4 caracteres y un limite de 50'
	                     //    },
	                        // notEmpty: {
	                        //     message: 'Correo es requerido'
	                        // },
	                 
	                        emailAddress: {
	                            message: 'Entrada no es una dirección de correo electrónico válida'
	                        }
	                    }
	                },
	                txtTelefonoDomicilio: {
	                    message: 'Número de documento no es valida',
	                    validators: {	                        
	                        regexp: {
	                            regexp: /^[0-9]+$/, 
                            	message: 'Solo puede ingresar numeros enteros'
	                        }
	                    }
	                },

	                txtTelefonoTrabajo: {
	                    message: 'Número de documento no es valida',
	                    validators: {	                        
	                        regexp: {
	                            regexp: /^[0-9]+$/, 
                            	message: 'Solo puede ingresar numeros enteros'
	                        }
	                    }
	                },
	                rdoSexo: {
		                validators: {
		                    notEmpty: {
		                    	message: 'Sexo es requerido'
		                    }
		                }
		            }
	                
	            }
	        });

			/******************** VALIDAR FROMULARIO PARA LA ACTUALIZACION ******************/
	        $('#frmPersonaA').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                txtNombresA: {
	                    message: 'Nombres no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Nombres es requerida'
	                        },
	                        stringLength: {
	                            min: 3,
	                            max: 20,
	                              message: 'El Nombre requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z]+$)/,
	                            message: 'El Nombre de la persona solo puede ser alfabetico'
	                        }
	                    }
	                },
	                txtPaternoA: {
	                    message: 'El Paterno no es valida',
	                    validators: {
	                        // notEmpty: {
	                        //     message: 'Paterno es requerida'
	                        // },
	                        stringLength: {
	                            min: 3,
	                            max: 20,
	                              message: 'Paterno requiere mas de 3 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z-]+$)/,
	                            message: 'Paterno de la persona solo puede ser alfabetico'
	                        }
	                    }
	                },
	                txtMaternoA: {
	                    message: 'El Materno no es valida',
	                    validators: {
	                        // notEmpty: {
	                        //     message: 'Materno es requerida'
	                        // },
	                        stringLength: {
	                            min: 4,
	                            max: 20,
	                              message: 'Materno requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z]+$)/,
	                            message: 'Materno de la persona solo puede ser alfabetico'
	                        }
	                    }
	                },
	                txtCIA: {
	                    message: 'Número de documento no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Número de documento es requerida'
	                        },
	                        stringLength: {
	                            min: 4,
	                            max: 20,
	                              message: 'Número de documento requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /^[0-9]+$/, 
                            	message: 'Solo puede ingresar numeros enteros'
	                        }
	                    }
	                },
	                ddlExpedidoA: {
	                    message: 'Expedición no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Expedición es requerida'
	                        }	                        
	                    }
	                },

	                
	                // ddlEstadocivilA: {
	                //     message: 'Estado Civil no es valida',
	                //     validators: {
	                //         notEmpty: {
	                //             message: 'Estado Civil es requerida'
	                //         }	                        
	                //     }
	                // },

	                txtFechaNacimientoA: {
	                    message: 'Fecha de Nacimiento no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Fecha de Nacimiento es requerida'
	                        },
	                        date: {
		                        format: 'DD-MM-YYYY',
		                        message: 'La fecha no es valido (01-01-2018)'
		                    }
	                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
	                    }
	                },
	                txtCorreoA: {                                                        
	                    validators: {
	                    	// notEmpty: {
	                     //        message: 'Correo es requerido'
	                     //    },
	                     //    stringLength: {
	                     //        min: 4,
	                     //        max: 50,
	                     //        message: 'El correo requiere mas de 4 caracteres y un limite de 50'
	                     //    },
	                        // notEmpty: {
	                        //     message: 'Correo es requerido'
	                        // },
	                 
	                        emailAddress: {
	                            message: 'Entrada no es una dirección de correo electrónico válida'
	                        }
	                    }
	                },
	                rdoSexoA: {
		                validators: {
		                    notEmpty: {
		                    	message: 'Sexo es requerido'
		                    }
		                }
		            },
	                txtTelefonoDomicilioA: {
	                    message: 'Número de documento no es valida',
	                    validators: {	                        
	                        regexp: {
	                            regexp: /^[0-9]+$/, 
                            	message: 'Solo puede ingresar numeros enteros'
	                        }
	                    }
	                },

	                txtTelefonoTrabajoA: {
	                    message: 'Número de documento no es valida',
	                    validators: {	                        
	                        regexp: {
	                            regexp: /^[0-9]+$/, 
                            	message: 'Solo puede ingresar numeros enteros'
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
		        fnListadoPersonas(url);
		        window.history.pushState("", "", url);
		    });				
		});
		
		$("#btnRegistrar").click(function(){	    	
	        var route="/Persona";
	     	//var token =$("#token").val();
	     	var validarPersona = $("#frmPersona").data('bootstrapValidator'); 
	     	//console.log(validarPersona);
	        validarPersona.validate();
	        if(validarPersona.isValid())
	        {
	        	$.ajax({
		            url: route,
		            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		            type: 'POST',
		            data: {
		            	'txtNombres':$("#txtNombres").val(),
		            	'txtPaterno':$("#txtPaterno").val(),
		            	'txtComplemento' :$("#txtComplemento").val(),
		            	'txtMaterno':$("#txtMaterno").val(),
		            	'txtCI':$("#txtCI").val(),
		            	'ddlExpedido':$("#ddlExpedido").val(),
		            	//'rdoSexo':$("#rdoSexo").val(),
		            	'rdoSexo':$('input[name=rdoSexo]:checked').val(),		            	
		            	'txtFechaNacimiento':$("#txtFechaNacimiento").val(),
		            	'ddlEstadocivil':$("#ddlEstadocivil").val(),
		            	'txtCorreo':$("#txtCorreo").val(),
		            	'txtTelefonoDomicilio':$("#txtTelefonoDomicilio").val(),
		            	'txtTelefonoTrabajo':$("#txtTelefonoTrabajo").val(),
		            	'txtDireccionTrabajo':$("#txtDireccionTrabajo").val(),
		            	'txtCelular':$("#txtCelular").val(),
		            	'txtDomicilio':$("#txtDomicilio").val(),
		            	'tipodocumento_genericoid':1,
		            	'muninacimiento_id':1,
		            	'nacionalidad_genericoid':1,
		            	'nivelestudio_genericoid':1,
		            	'idiomamaterno_genericoid':1,
		            	'idioma_genericoid':1,
		            	'autopertenencia_genericoid':1,
		            	'fotografia':'fot.jpg'
		            },
	                success: function(data){
	                	console.log(data.Mensaje);
	                	resultado = data.Mensaje;

	                	/*resultado = 1  ROL GUARDADO*/
	                	if (resultado == 1) {
                        	$("#modalCreatePersona").modal('toggle');
                            Swal.fire({
					            title: "PERSONA!",
					            text: "Se registro correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoPersonas('/Persona');
                        }

	                	/*resultado = 2  EXISTE EL ROL*/
	                	if (resultado == 2) {
                            Swal.fire({					            
					            type: 'error',
								title: 'PERSONA...',
								html: 'EL CI: <b>'+  $("#txtCI").val() +'</b> ya existe en la base de datos',
								confirmButtonText: 'Aceptar',
								//footer: '<a href>Why do I have this issue?</a>'
					        });
                        }
                        
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {                                
                            Swal.fire({
					            title: "PERSONA...",
					            text: "hubo problemas al registrar en BD",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            Swal.fire({
					            title: "PERSONA...",
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

	    /******************************** ACTUALIZA DATOS DE PERSONAS *****************************************/
	    $("#btnActualizar").click(function(){
	        var value =$("#id").val();
	        var route="/Persona/"+value+"";
	        var validarPersona = $("#frmPersonaA").data('bootstrapValidator'); 
	        validarPersona.validate();
	        //$('#myCreate').html('<div><img src="../img/ajax-loader.gif"/></div>');
	        if(validarPersona.isValid())
	        {
	            $.ajax({
	                url: route,
	                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	                type: 'PUT',
	                dataType: 'json',
	                data: {
	                    'txtNombres':$("#txtNombresA").val(),
		            	'txtPaterno':$("#txtPaternoA").val(),
		            	'txtMaterno':$("#txtMaternoA").val(),
		            	'txtCI':$("#txtCIA").val(),
		            	'txtComplemento' :$("#txtComplementoA").val(),
		            	'ddlExpedido':$("#ddlExpedidoA").val(),
		            	'rdoSexo':$('input[name=rdoSexoA]:checked').val(),
		            	'txtFechaNacimiento':$("#txtFechaNacimientoA").val(),
		            	'ddlEstadocivil':$("#ddlEstadocivilA").val(),
		            	'txtCorreo':$("#txtCorreoA").val(),
		            	'txtTelefonoDomicilio':$("#txtTelefonoDomicilioA").val(),
		            	'txtTelefonoTrabajo':$("#txtTelefonoTrabajoA").val(),
		            	'txtDireccionTrabajo':$("#txtDireccionTrabajoA").val(),
		            	'txtCelular':$("#txtCelularA").val(),
		            	'txtDomicilio':$("#txtDomicilioA").val(),
		            	'tipodocumento_genericoid':1,
		            	'muninacimiento_id':1,
		            	'nacionalidad_genericoid':1,
		            	'nivelestudio_genericoid':1,
		            	'idiomamaterno_genericoid':1,
		            	'idioma_genericoid':1,
		            	'autopertenencia_genericoid':1,
		            	'fotografia':'fot.jpg'
	                },
	                success: function(data){	                    
	                    resultado = data.Mensaje;
	                	/*resultado = 1  PERSONA GUARDADO*/
	                	if (resultado == 1) {
                        	$("#modalUpdatePersona").modal('toggle');
                            Swal.fire({
					            title: "PERSONA!",
					            text: "Se actualizo correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoPersonas('/Persona');
                        }

	                	/*resultado = 2  EXISTE EL ROL*/
	                	if (resultado == 2) {
                            Swal.fire({
					            title: "PERSONA...",
					            text: 'EL CI: <b>'+  $("#txtCI").val() +'</b> ya existe en la base de datos',
					            html: true,
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
                        
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {                                
                            Swal.fire({
					            title: "PERSONA...",
					            text: "hubo problemas al registrar en BD",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            Swal.fire({
					            title: "PERSONA...",
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

	    /******************************** LISTADO DE PERSONAS *****************************************/
	    function fnListadoPersonas(url) {
	    	var parametros = {
	    		'txtBuscarIdentifiacion':$("#txtBuscarIdentifiacion").val(),
	    		'txtBuscarNombres':$("#txtBuscarNombres").val(),
	    		'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
	    		'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
	    		'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
	    	};
	    	console.log("funcionnnn",url);
	    	var route =url;	
	    	$.ajax({
				type: 'GET',
				url: route,
				data:parametros,
				//dataType: 'json',
				success: function (data) {
					console.log("data",data);
					$('.clsPersonas').html(data);  
				},error:function(error){ 
					Swal.fire({
			            title: "PERSONA...",
			            text: "Los roles no se puede cargar",
			            confirmButtonColor: "#EF5350",
			            confirmButtonText: 'Aceptar',
			            type: "error"
			        });	 
				}
			 }); 


	    }

	    /******************************** BUSCA PERSONAS *****************************************/
	    function fnBuscarPersonas() {
	    	//console.log("valor",valor);
	    	//var parametros = {'txtBuscarPersona':$("#txtBuscarPersona").val()};
	    	var parametros = {
	    		'txtBuscarIdentifiacion':$("#txtBuscarIdentifiacion").val(),
	    		'txtBuscarNombres':$("#txtBuscarNombres").val(),
	    		'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
	    		'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
	    		'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
	    	};
	    	var route ="/BuscarPersonas";	    	
            $.ajax({
				type: 'GET',
				url: route,
				data:parametros,
				async: false,
				contentType: "application/x-www-form-urlencoded;charset=UTF-8; charset=iso-8859-1;application/json;",
				//dataType: 'json',
				success: function (data) {
					console.log("data",data);
					$('.clsPersonas').html(data);  
				},error:function(error){ 
					Swal.fire({
			            title: "ROL...",
			            text: "Los roles no se puede cargar",
			            confirmButtonColor: "#EF5350",
			            confirmButtonText: 'Aceptar',
			            type: "error"
			        });
				}
			 });	        
	    }
 
	    /******************************** OBTIENE DATOS DE ROLES *****************************************/
	    function fnEditarPersona(datos) {
	    	console.log("datos",datos.sexo_genericoid);
	    	$("#txtNombresA").val(datos.nombres);
	    	$("#txtPaternoA").val(datos.primerapellido);
	    	$("#txtComplementoA").val(datos.complemento);
	    	$("#txtMaternoA").val(datos.segundoapellido);
	    	$("#txtCIA").val(datos.nrodocumento);
	    	$("#ddlExpedidoA").val(datos.expedido_id);	    	
	    	$("input[name=rdoSexoA][value=" + datos.sexo_genericoid + "]").attr('checked', 'checked');
	    	var fechanacimientoA = datos.fechanacimiento.split('-')
	    	$("#txtFechaNacimientoA").val(fechanacimientoA[2] +'-'+ fechanacimientoA[1] +'-'+ fechanacimientoA[0]);
	    	$("#ddlEstadocivilA").val(datos.estadocivil_genericoid);
	    	$("#txtCorreoA").val(datos.correoelectronico);
	    	$("#txtTelefonoDomicilioA").val(datos.telefonodomicilio);
	    	$("#txtTelefonoTrabajoA").val(datos.telefonotrabajo);
	    	$("#txtDireccionTrabajoA").val(datos.direcciontrabajo);
	    	$("#txtCelularA").val(datos.celular);
	    	$("#txtDomicilioA").val(datos.domicilio);
	    	$("#id").val(datos.id);
	    }

	    /******************************** LIMPIA CONTRAOLES  *****************************************/
	    function fnLimpiarControles(){
	    	$("#txtNombres").val('');
	    	$("#txtPaterno").val('');
	    	$("#txtMaterno").val('');
	    	$("#txtCI").val('');
	    	$("#ddlExpedido").val('');
	    	$("#txtComplemento").val('');
	    	//$('input:radio[name=rdoSexo]').attr('checked',false);
	    	//$('input:radio[name=rdoSexoA]').attr('checked',false);

	    	$("#txtFechaNacimiento").val('');
	    	$("#ddlEstadocivil").val('').change();;
	    	$("#txtCorreo").val('');
	    	$("#txtTelefonoDomicilio").val('');
	    	$("#txtTelefonoTrabajo").val('');
	    	$("#txtDireccionTrabajo").val('');
	    	$("#txtNombresA").val('');
	    	$("#txtPaternoA").val('');
	    	$("#txtMaternoA").val('');
	    	$("#txtCIA").val('');
	    	$("#ddlExpedidoA").val('');
	    	$("#txtCelular").val('');
	    	//$("#rdoSexoA").val('');
	    	$("#txtFechaNacimientoA").val('');
	    	$("#ddlEstadocivilA").val('').change();;
	    	$("#txtCorreoA").val('');
	    	$("#txtTelefonoDomicilioA").val('');
	    	$("#txtTelefonoTrabajoA").val('');
	    	$("#txtDireccionTrabajoA").val('');
	    	$("#id").val('');	    	
	    	$('#frmPersona').bootstrapValidator('resetForm', true);
	    	//$('#frmPersonaA').bootstrapValidator('resetForm', true);
	    }	    

	    /******************************** ELIMINA DATOS PERSONA ***************************************/
	    function fnEliminarPersona(id){
	        var route="/Persona/"+id+"";	        
	        Swal.fire({   
	        	title: "Esta seguro de eliminar la Persona?", 
	          	text: "Presione Si para eliminar el registro de la base de datos!", 
	          	type: "warning",   showCancelButton: true,
	          	confirmButtonColor: "#3085d6",
	          	cancelButtonColor: '#d33',   
	          	confirmButtonText: "Si, Eliminar!",
	          	closeOnConfirm: false 
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

		                	/*resultado = 1  ROL ELIMINADO*/
		                	if (resultado == 1) {
	                            Swal.fire({
						            title: "PERSONA!",
						            text: "Se elimino correctamente!!",
						            confirmButtonText: 'Aceptar',
						            confirmButtonColor: "#66BB6A",
						            type: "success"
						        });
						        fnLimpiarControles();
						        fnListadoPersonas('/Persona');
	                        }

		                	
	                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
	                        if (resultado == 0) {                                
	                            Swal.fire({
						            title: "PERSONA...",
						            text: "hubo problemas al registrar en BD",
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }

	                        /*resultado = -1 SESION EXPIRADA*/
	                        if (resultado == "-1") {                                
	                            Swal.fire({
						            title: "PERSONA...",
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

	    /******************************** HABILITAR DATOS PERSONA *****************************************/
	    function fnHabilitarPersona(id){
	        var route="/HabilitarPersona/"+id+"";	        
	        Swal.fire({   title: "Esta seguro de habilitar la Persona?", 
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
						            title: "PERSONA!",
						            text: "Se habilito correctamente!!",
						            confirmButtonText: 'Aceptar',
						            confirmButtonColor: "#66BB6A",
						            type: "success"
						        });
						        fnLimpiarControles();
						        fnListadoPersonas('/Persona');
	                        }	                	

	                        /*resultado = -1 SESION EXPIRADA*/
	                        if (resultado == "-1") {                                
	                            Swal.fire({
						            title: "PERSONA...",
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
	        });
	    }
</script>

	
@endsection



