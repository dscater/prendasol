@extends('layout.inicio')
@include('administracion.modulo.modals.modalCreate')
@include('administracion.modulo.modals.modalUpdate')
@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					MODULOS
					<small>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateModulo"><i class="icon-new-tab position-left"></i>Nuevo</button>
					</small>
				</h3>
			</div>
			</section>
		</div>		
	</div>
	<div class="row">        
        <div class="col-sm-5 form-group">
            <div class="input-group">
                <input class="form-control" id="txtBuscarModulo" placeholder="Buscar Módulo" name="txtBuscarModulo"type="text" onkeydown="if (event.keyCode == 13) fnBuscarModulos()"/>
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-warning"  onclick="fnBuscarModulos()">Buscar</button>
                </div>
            </div>
        </div>
    </div>
	<div class="row">            
        <div class="col-md-12">            
                <section class="clsModulos">
                	@include('administracion.modulo.modals.listadoModulo')                    
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
	        $('#frmModulo').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                txtModulo: {
	                    message: 'El Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'El Módulo es requerida'
	                        },
	                        stringLength: {
	                            min: 4,
	                            max: 20,
	                              message: 'El Módulo requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z]+$)/,
	                            message: 'El Módulo de la persona solo puede ser alfabetico'
	                        }
	                    }
	                }
	            }
	        });

	        /******************** VALIDAR FROMULARIO PARA LA ACTUALIZACION ******************/
	        $('#frmModuloA').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                txtModuloA: {
	                    message: 'El Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'El Módulo es requerida'
	                        },
	                        stringLength: {
	                            min: 4,
	                            max: 20,
	                              message: 'El Módulo requiere mas de 4 letras y un limite de 20'
	                        },
	                        regexp: {
	                            regexp: /(\s*[a-zA-Z]+$)/,
	                            message: 'El Modulo de la persona solo puede ser alfabetico'
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
		        fnListadoModuloes(url);
		        window.history.pushState("", "", url);
		    });
	    });


		/******************************** REGISTRA Modulo *****************************************/
	    $("#btnRegistrar").click(function(){	    	
	        var route="/Modulo";
	     	//var token =$("#token").val();
	     	var validarModulo = $("#frmModulo").data('bootstrapValidator'); 
	     	console.log(validarModulo);
	        validarModulo.validate();
	        if(validarModulo.isValid())
	        {
	        	$.ajax({
		            url: route,
		            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		            type: 'POST',
		            data: {'txtModulo':$("#txtModulo").val()},
		                success: function(data){
		                	console.log("mesnaje",data.Mensaje);
		                	resultado = data.Mensaje;

		                	/*resultado = 1  Modulo GUARDADO*/
		                	if (resultado == 1) {
                            	$("#modalCreateModulo").modal('toggle');
                                swal({
						            title: "Modulo!",
						            text: "Se registro correctamente!!",
						            confirmButtonText: 'Aceptar',
						            confirmButtonColor: "#66BB6A",
						            type: "success"
						        });
						        fnLimpiarControles();
						        fnListadoModulos('/Modulo');
                            }

		                	/*resultado = 2  EXISTE EL Modulo*/
		                	if (resultado == 2) {
                                swal({
						            title: "Modulo...",
						            text: 'El Modulo: <b>'+  $("#txtModulo").val() +'</b> ya existe en la base de datos',
						            html: true,
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
                            }
                            
                            /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                            if (resultado == 0) {                                
                                swal({
						            title: "Modulo...",
						            text: "hubo problemas al registrar en BD",
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
                            }

                            /*resultado = -1 SESION EXPIRADA*/
                            if (resultado == "-1") {                                
                                swal({
						            title: "Modulo...",
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

	    /******************************** ACTUALIZA DATOS DE MODULO *****************************************/
	    $("#btnActualizar").click(function(){
	        var value =$("#id").val();
	        var route="/Modulo/"+value+"";
	        var validarModulo = $("#frmModuloA").data('bootstrapValidator'); 
	        validarModulo.validate();
	        //$('#myCreate').html('<div><img src="../img/ajax-loader.gif"/></div>');
	        if(validarModulo.isValid())
	        {
	            $.ajax({
	                url: route,
	                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	                type: 'PUT',
	                dataType: 'json',
	                data: {
	                    'txtModulo':$('#txtModuloA').val()
	                },
	                success: function(data){	                    
	                    resultado = data.Mensaje;
	                	/*resultado = 1  Modulo GUARDADO*/
	                	if (resultado == 1) {
                        	$("#modalUpdateModulo").modal('toggle');
                            swal({
					            title: "Modulo!",
					            text: "Se actualizo correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoModulos('/Modulo');
                        }

	                	/*resultado = 2  EXISTE EL Modulo*/
	                	if (resultado == 2) {
                            swal({
					            title: "Modulo...",
					            text: 'El Modulo: <b>'+  $("#txtModuloA").val() +'</b> ya existe en la base de datos',
					            html: true,
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
                        
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {                                
                            swal({
					            title: "Modulo...",
					            text: "hubo problemas al registrar en BD",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            swal({
					            title: "Modulo...",
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

	    /******************************** LISTADO DE MODULOS *****************************************/
	    function fnListadoModulos(url) {
	    	console.log("funcionnnn");
	    	var route =url;	    	
	    	$.get(route, function(data){
	    		$('.clsModulos').html(data);  
	    	}).fail(function () {
	    		swal({
		            title: "Modulo...",
		            text: "Los Moduloes no se puede cargar",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });	            
	        });
	        // $.ajax({
	        //     url : url  
	        // }).done(function (data) {
	        //     $('.clsModulos').html(data);  
	        // }).fail(function () {
	        //     alert('Articles could not be loaded.');
	        // });
	    }

	    /******************************** BUSCA MODULOS *****************************************/
	    function fnBuscarModulos() {
	    	//console.log("valor",valor);
	    	var parametros = {'txtBuscarModulo':$("#txtBuscarModulo").val()};
	    	var route ="/BuscarModulos";
	    	//console.log("content",content);
	    	
	    	// var route =url;
	    	$.get(route,parametros, function(data){
	    		//console.log("data",data);
	    		$('.clsModulos').html(data);  
	    	}).fail(function () {
	    		swal({
		            title: "Modulo...",
		            text: "Los Moduloes no se puede cargar",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });	            
	        });
	        
	    }
 
	    /******************************** OBTIENE DATOS DE MODULO *****************************************/
	    function fnEditarModulo(datos) {
	    	console.log("datos",datos);
	    	$("#txtModuloA").val(datos.modulo);
	    	$("#id").val(datos.id);
	    }

	    /******************************** LIMPIA CONTROLES  *****************************************/
	    function fnLimpiarControles(){
	    	$("#txtModulo").val('');
	    	$("#txtModuloA").val('');
	    	$("#id").val('');
	    	$('#frmModulo').bootstrapValidator('resetForm', true);
	    	$('#frmModuloA').bootstrapValidator('resetForm', true);
	    }	    

	    /******************************** ELIMINA DATOS MODULO *****************************************/
	    function fnEliminarModulo(id){
	        var route="/Modulo/"+id+"";	        
	        swal({   title: "Esta seguro de eliminar el Módulo?", 
	          text: "Presione Si para eliminar el registro de la base de datos!", 
	          type: "warning",   showCancelButton: true,
	          confirmButtonColor: "#DD6B55",   
	          confirmButtonText: "Si, Eliminar!",
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

	                	/*resultado = 1  Modulo ELIMINADO*/
	                	if (resultado == 1) {
                            swal({
					            title: "Modulo!",
					            text: "Se elimino correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoModulos('/Modulo');
                        }

	                	/*resultado = 2  EXISTE EL Modulo*/
	                	if (resultado == 2) {
                            swal({
					            title: "Modulo...",
					            text: 'El Modulo: <b>'+  $("#txtModulo").val() +'</b> ya existe en la base de datos',
					            html: true,
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }
                        
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {                                
                            swal({
					            title: "Modulo...",
					            text: "hubo problemas al registrar en BD",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            swal({
					            title: "Modulo...",
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

	    /******************************** HABILITAR MODULO *****************************************/
	    function fnHabilitarModulo(id){
	        var route="/HabilitarModulo/"+id+"";	        
	        swal({   title: "Esta seguro de habilitar el Módulo?", 
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
					            title: "MODULO!",
					            text: "Se habilito correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoModulos('/Modulo');
                        }
                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            swal({
					            title: "MODULO...",
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

