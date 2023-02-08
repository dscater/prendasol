@extends('layout.principal')
@include('formIngreso.modals.modalCreate')
@include('reporte.modalReporte')
@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					INGRESO A CAJA
					@if($datoValidarCaja)
						@if($datoValidarCaja->estado_id == 1)
						<small>
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateIngresoCaja"><i class="icon-new-tab position-left"></i>Nuevo</button>
						</small>
						@else
							@if(Session::get('ID_ROL') == 1)
							<small>
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateIngresoCaja"><i class="icon-new-tab position-left"></i>Registrar capitalización {{$resFechaProximo}}</button>
							</small>
							@endif
						@endif
					@else
					<small>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateIngresoCaja"><i class="icon-new-tab position-left"></i>Nuevo</button>
					</small>
					@endif
				</h3>
			</div>
			</section>
		</div>		
	</div>	
	@if($datoValidarCaja)
	@if($datoValidarCaja->estado_id == 2)
	<div class="row">            
        <div class="col-md-12">            
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				Nose pueden realizar mas registros porque la caja <b>{{session::get('CAJA')}}</b> de la sucursal <b>{{session::get('ID_SUCURSAL')}}</b> ya cerro.
			</div>
		</div>
	</div>
	@endif
	@endif
	<div class="row">            
        <div class="col-md-12">            
            <section class="clsIngreso">
            	@include('formIngreso.modals.listadoIngreso')                    
            </section>            
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
	<script type="text/javascript">    
		$(document).ready(function() {
			$("#txtMonto").numeric();
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
	    	
	    	/******************** VALIDAR FROMULARIO PARA LA INSERCION ******************/
	        $('#frmIngresoCaja').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                ddlSucursal: {
	                    message: 'El Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Sucursal es requerida'
	                        },
	                        
	                    }
	                },
	                ddlCaja: {
	                    message: 'El Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Caja es requerida'
	                        },
	                        
	                    }
	                },
	                txtFecha: {
	                    message: 'El Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Fecha es requerida'
	                        },
	                        
	                    }
	                },
	                ddlTipoMovimiento: {
	                    message: 'El Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Tipo de Movimiento es requerida'
	                        },
	                        
	                    }
	                },
	                txtMonto: {
	                    message: 'El Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Monto es requerida'
	                        },
	                        
	                    }
	                },
	                txtCI: {
	                    message: 'El Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'CI es requerida'
	                        },
	                        
	                    }
	                },
	                txtCliente: {
	                    message: 'El Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Cliente es requerida'
	                        },
	                        
	                    }
	                },
	                txtGlosa: {
	                    message: 'El Módulo no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Glosa es requerida'
	                        },
	                        
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
	        var route="/IngresoCaja";
	     	//var token =$("#token").val();
	     	var validarFormulario = $("#frmIngresoCaja").data('bootstrapValidator'); 	     	
	        validarFormulario.validate();
	        if(validarFormulario.isValid())
	        {
	        	$.ajax({
		            url: route,
		            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		            type: 'POST',
		            data: {
		            	'ddlSucursal':$("#ddlSucursal").val(),
		            	'ddlCaja':$("#ddlCaja").val(),
		            	'txtFecha':$("#txtFecha").val(),
		            	'txtMonto':$("#txtMonto").val(),
		            	'ddlTipoMovimiento':$("#ddlTipoMovimiento").val(),
		            	'txtCI':$("#txtCI").val(),
		            	'txtCliente':$("#txtMonto").val(),
		            	'txtGlosa':$("#txtGlosa").val()
		            },
	                success: function(data){
	                	console.log("mesnaje",data.Mensaje);
	                	resultado = data.Mensaje;

	                	/*resultado = 1  Modulo GUARDADO*/
	                	if (resultado == 1) {
                        	$("#modalCreateIngresoCaja").modal('toggle');
                            Swal.fire({
					            title: "INGRESO CAJA!",
					            text: "Se registro correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoIngresoCaja('/IngresoCaja');
					        fnImprimirIngreso(data.idContaDiario,data.idInicioCaja);
                        }

	                	/*resultado = 2  EXISTE EL Modulo*/
	                	if (resultado == 2) {
                            Swal.fire({					            
					            type: 'error',
								title: 'INICIO FIN CAJA...',
								html: 'La Sucursal y la caja ya existe para esa fecha',
								confirmButtonText: 'Aceptar',
								//footer: '<a href>Why do I have this issue?</a>'
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

	 

	    /******************************** LISTADO DE MODULOS *****************************************/
	    function fnListadoIngresoCaja(url) {
	    	console.log("funcionnnn");
	    	var route =url;	    	
	    	$.get(route, function(data){
	    		$('.clsIngreso').html(data);  
	    	}).fail(function () {
	    		Swal.fire({
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

	  

	    /******************************** LIMPIA CONTROLES  *****************************************/
	    function fnLimpiarControles(){
	    	$("#ddlSucursal").val('');
        	$("#ddlCaja").val('');
        	$("#txtFecha").val('');
        	$("#txtMonto").val('');
        	$("#ddlTipoMovimiento").val('')
        	$("#txtCI").val('');
        	$("#txtMonto").val('');
        	$("#txtGlosa").val('');
	    	$('#frmIngresoCaja').bootstrapValidator('resetForm', true);
	    }

	    /*FUNCION IMPRIME INGRESO*/
	    function fnImprimirIngreso(id1,id2){  
			console.log("id:::",id1);
			$("#reporteModal").modal();
        	var src = "/ImprimirReporteIngreso/"+id1+"/"+id2;
        	console.log(src);
        	console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);            
            $("#dialog").show();     
	    };

	    /*FUNCION REIMPRIME INGRESO*/
	    function fnReImprimirIngreso(id){  
			console.log("id:::",id);
			$("#reporteModal").modal();
        	var src = "/ReImprimirReporteIngreso/"+id;
        	console.log(src);
        	console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);            
            $("#dialog").show();     
	    };	    

		function limpiarControles(){
		}	    
	</script>
@endsection

