@extends('layout.principal')
@include('administracion.usuarioSucursal.modals.modalCreate')
@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					USUARIOS SUCURSALES
					<small>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateUsuarioSucursal"><i class="icon-new-tab position-left"></i>Nuevo</button>
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
                	@include('administracion.usuarioSucursal.modals.listadoUsuarios')                    
                </section>
            
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
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
	        $('#frmUsuarioSucursal').bootstrapValidator({
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
	                ddlSucursal: {
	                    message: 'Sucursal no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Sucursal es requerida'
	                        }
	                    }
	                },
					
					ddlCaja: {
	                    message: 'Caja no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Caja es requerida'
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
		    
	    });

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
				'sw' : true
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

	    /************** NOMBRE DE LA PERSONA PARA CONSTRUIR EL USUARIO **************/	    
	    $(document.body).on("change","#ddlPersona",function(){		        
	        valor = this.value; 
	        console.log("valorrr",this.value);
	        if (valor != '') {
	        	var route ="/ListadoSucursalesNoAsignados/"+valor;	    	
		    	$.get(route, function(data){
		    		console.log(data);
		    		$('.ddlSucursal').empty();
		    		$('.ddlSucursal').append("<option value=''></option>"); 
		    		$.each(data.Mensaje, function(key, element) {
		    			//console.log("valorrr",element.id);
		                $('.ddlSucursal').append("<option value='" + element.id +"'>" + element.nombre + "</option>");
		            });
		    		//$("#txtUsuario").val(data);
		    		//$('#frmUsuario').bootstrapValidator('revalidateField', 'txtUsuario');
		    	});
	        }	        
	    });

	    /******************************** REGISTRA USUARIO *****************************************/
	    $("#btnRegistrar").click(function(){	    	
	        var route="/UsuarioSucursal";
	     	//var token =$("#token").val();
	     	var validarUsuarioSucursal = $("#frmUsuarioSucursal").data('bootstrapValidator'); 
	     	console.log(validarUsuarioSucursal);
	        validarUsuarioSucursal.validate();
	        if(validarUsuarioSucursal.isValid())
	        {
	        	$.ajax({
		            url: route,
		            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		            type: 'POST',
		            data: {
		            	'ddlPersona':$("#ddlPersona").val(),
		            	'ddlSucursal':$("#ddlSucursal").val(),
		            	'ddlCaja':$("#ddlCaja").val()
		            },
	                success: function(data){
	                	console.log(data.Mensaje);
	                	resultado = data.Mensaje;

	                	/*resultado = 1  USUARIO GUARDADO*/
	                	if (resultado == 1) {
                        	$("#modalCreateUsuarioSucursal").modal('toggle');
                            Swal.fire({
					            title: "USUARIO SUCURSAL!",
					            text: "Se registro correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        //fnLimpiarControles();
					        fnListadoUsuarios('/UsuarioSucursal');
					        //$('.clsPersonas').html(data.personas); 
					        //fnPersonasNoHabilitadas();   
					        //document.getElementById('divMostrarDepartamento').style.display = 'none'; 	

                        }

	                	/*resultado = 2  EXISTE EL USUARIO*/
	                	if (resultado == 2) {
                            Swal.fire({
					            title: "USUARIO...",
					            text: 'La asignacion de sucursal y/o caja ya existe en la base de datos',
					            //html: true,
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

	    function fnEliminarUsuarioSucursal(id){
	        var route="/UsuarioSucursal/"+id+"";	        
	        Swal.fire({   title: "Esta seguro de eliminar el Usuario de esa Sucursal?", 
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
						            title: "USUARIO SUCURSAL!",
						            text: "Se elimino correctamente!!",
						            confirmButtonText: 'Aceptar',
						            confirmButtonColor: "#66BB6A",
						            type: "success"
						        });
						        //fnLimpiarControles();
						        fnListadoUsuarios('/UsuarioSucursal');
	                        }

		                	
	                        /*resultado = -1 SESION EXPIRADA*/
	                        if (resultado == "-1") {                                
	                            Swal.fire({
						            title: "USUARIO SUCURSAL...",
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

	    function fnHabilitarUsuarioSucursal(id){
	        var route="/HabilitarUsuarioSucursal/"+id+"";	        
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
						        //fnLimpiarControles();
						        fnListadoUsuarios('/UsuarioSucursal');
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

