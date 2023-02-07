@extends('layout.principal')
@include('formEgreso.modals.modalCreate')
@include('reporte.modalReporte')
@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					EGRESO A CAJA
					@if($datoValidarCaja)
					@if($datoValidarCaja->estado_id == 1)
					<small>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateEgresoCaja"><i class="icon-new-tab position-left"></i>Nuevo</button>
					</small>
					@endif
					@else
					<small>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateEgresoCaja"><i class="icon-new-tab position-left"></i>Nuevo</button>
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
            <section class="clsEgreso">
            	{{-- @include('formEgreso.modals.listadoEgreso') --}}
            </section>            
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
	<script type="text/javascript">    
		let page = 1;
		$(document).ready(function() {
			fnListadoEgresoCaja('/EgresoCaja');
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
	        $('#frmEgresoCaja').bootstrapValidator({
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
		        var url = $(this).attr('href').split('?');  
				console.log('/EgresoCaja?'+url[1]);
		        fnListadoEgresoCaja('/EgresoCaja?'+url[1]);
		    });
	    });

		// funcion para enumrar filas
		function enumerar(){
			let filas = $(document).find('table').children('tbody').children('tr');
			let conteo = 1;
			if(page == null || page == ''){
				conteo = 1;
			}else{
				conteo = (20 * (parseInt(page) - 1)) + 1;
			} 
			filas.each(function(){
				$(this).children('td').eq(0).text(conteo);
				conteo++;
			});
		}

		/******************************** REGISTRA Modulo *****************************************/
	    $("#btnRegistrar").click(function(){
	        var route="/EgresoCaja";
	     	//var token =$("#token").val();
	     	var validarFormulario = $("#frmEgresoCaja").data('bootstrapValidator'); 	     	
	        validarFormulario.validate();
			$('#btnRegistrar').prop('disabled',true);
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
                        	$("#modalCreateEgresoCaja").modal('toggle');
                            Swal.fire({
					            title: "EGRESO CAJA !",
					            text: "Se registro correctamente!!",
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnLimpiarControles();
					        fnListadoEgresoCaja('/EgresoCaja?page=1');
					        fnImprimirEgreso(data.idContaDiario,data.idInicioCaja);
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
						$('#btnRegistrar').prop('disabled',false);

	                },
	                error: function(result) {
	                }
		        }).fail(function(e){
					Swal.fire({
						title: "Error...",
					            text: "Algo salió mal, verifique que la caja seleccionada este abierta.",
					            confirmButtonColor: "#EF5350",
					            confirmButtonText: 'Aceptar',
					            type: "error"
					        });
						$('#btnRegistrar').prop('disabled',false);

				});
	        }else{
				$('#btnRegistrar').prop('disabled',false);
			}       
	    });

	 

	    /******************************** LISTADO DE MODULOS *****************************************/
	    function fnListadoEgresoCaja(url) {
	    	var route =url;	    	
	    	$.get(route, function(data){
	    		$('.clsEgreso').html(data.html);
				console.log(data.page);
				page = data.page;
				enumerar();
	    	}).fail(function () {
	    		Swal.fire({
		            title: "Modulo...",
		            text: "Los Moduloes no se puede cargar",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });	            
	        });
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
	    	$('#frmEgresoCaja').bootstrapValidator('resetForm', true);
	    	//$('#frmModuloA').bootstrapValidator('resetForm', true);
	    }

	    /*FUNCION IMPRIME INGRESO*/
	    function fnImprimirEgreso(id1,id2){  
			console.log("id:::",id1);
			$("#reporteModal").modal();
        	var src = "/ImprimirReporteEgreso/"+id1+"/"+id2;
        	console.log(src);
        	console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);            
            $("#dialog").show();     
	    };


	    /*REIMPRIMIR EGRESO*/
	    function fnReimprimirEgreso(id){  
			console.log("id:::",id);
			$("#reporteModal").modal();
        	var src = "/ImprimirReporteEgresoReimpresion/"+id;
        	console.log(src);
        	console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);            
            $("#dialog").show();     
	    };	    

	    
	</script>
@endsection

