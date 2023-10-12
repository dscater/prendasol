@extends('layout.principal')
@include('contabilidad.contaInicioCaja.modals.modalUpdate')
@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					REPORTE CIERRE DE CAJAS
				</h3>
			</div>
			</section>
		</div>		
	</div>
	<form id="frmContaDiario">
		<div class="row">			
	        <div class="col-md-3">
	            <div class="form-group">
	                <label>Fecha Inicio:</label>								
	                <div class="input-group input-append date" id="datePickerFI">
	                    <input type="text" class="form-control" id="txtFechaInicio" name="txtFechaInicio" placeholder="Ingrese Fecha de Inicio" />
	                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
	                </div>               
	            </div>
	        </div>
	        <div class="col-md-3">
	            <div class="form-group">                
	                <label>Fecha Fin:</label>								
	                <div class="input-group input-append date" id="datePickerFF">
	                    <input type="text" class="form-control" id="txtFechaFin" name="txtFechaFin" placeholder="Ingrese Fecha Fin" />
	                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
	                </div> 
	            </div>
	        </div>
	        <div class="col-md-3">
	            <div class="input-group">                
	                <div class="input-group-btn">
	                	<br>
	                    {!!link_to('#',$title='Buscar', $attributes=['id'=>'btnBuscar','class'=>'btn btn-primary'], $secure=null)!!}
	                </div>
	            </div>
	        </div>
	    </div>
		<div class="row">
	        <div class="col-md-12">            
                <section class="clsContaInicioCaja">                	
                	@include('contabilidad.contaInicioCaja.modals.listadoContaInicioCaja')
                </section>	            
	        </div>
	    </div>	    

	    <div class="row">            
	        <div class="col-md-12">            
	           	<div class="alert alert-primary no-border clsMensajeAlerta">				
					.
			    </div>            
	        </div>
	    </div>
	</form>
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
	<script type="text/javascript">    
		$(document).ready(function() {
			$('.clsMensajeAlerta').hide();
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


	        /******************** FUNCION PARA LA PAGINACIÓN AL HACER CLICK MEDIANTE AJAX ******************/
	        $('body').on('click', '.pagination a', function(e) {
		        e.preventDefault();

		        //$('#load a').css('color', '#dfecf6');
		        //$('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

		        var url = $(this).attr('href');  
		        fnListadoModuloes(url);
		        window.history.pushState("", "", url);
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
		        $('#frmContaDiario').bootstrapValidator('revalidateField', 'txtFechaInicio');
		    });

		    $('#datePickerFF').datepicker({
		        format: "dd-mm-yyyy",
		        language: "es",
		        //autoclose: true,
		        orientation: "auto left",
			    forceParse: false,
			    autoclose: true,
			    todayHighlight: true,
			    toggleActive: true
		    }).on('changeDate', function(e) {
		        $('#frmContaDiario').bootstrapValidator('revalidateField', 'txtFechaFin');
		    });
		    /******************** VALIDAR FROMULARIO PARA BUSCAR POR FECHAS ******************/
	        $('#frmContaDiario').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                txtFechaInicio: {
	                    message: 'Fecha de Inicio no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Fecha de Inicio es requerida'
	                        },
	                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
	                    }
	                },
	                txtFechaFin: {
	                    message: 'Fecha Fin no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Fecha Fin es requerida'
	                        },
	                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
	                    }
	                }
	            }
	        });
	    });

	    /******************************** BUSCA DATOS POR FECHAS *****************************************/
	    $("#btnBuscar").click(function(){	  
	     	var validarContaDiario = $("#frmContaDiario").data('bootstrapValidator'); 
	     	console.log(validarContaDiario);
	        validarContaDiario.validate();
	        if(validarContaDiario.isValid())
	        {
	        	var parametros = {
	        		'txtFechaInicio':$("#txtFechaInicio").val(),
	        		'txtFechaFin':$("#txtFechaFin").val()
	        	};
		    	var route ="/BuscarContaInicioCaja";
		    	$.ajax({
	                url:route,
	                data:parametros,
	                method:'GET',
	                //dataType:'json',
	                success:function(data){
	                	console.log("datoss",data);
			    		if (data == "") {
			    			console.log("datosss vacios");
			    			$('.clsContaInicioCaja').empty();
			    			$('.clsMensajeAlerta').html('<span class="text-semibold">No existe registro de contabilidad!</span>');
			    			$('.clsMensajeAlerta').show();
			    		}else{
			    			$('.clsContaInicioCaja').html(data);  
			    			$('.clsMensajeAlerta').empty();
			    			$('.clsMensajeAlerta').hide();
			    		}  
	                },
	                beforeSend:function(){
	                    $('.loader').addClass('loader-default  is-active');

	                },
	                complete:function(){
	                    $('.loader').removeClass('loader-default  is-active');
	                },error:function(error){ 
						swal({
				            title: "SOAP...",
				            text: "La busqueda no se puede cargar",
				            confirmButtonColor: "#EF5350",
				            confirmButtonText: 'Aceptar',
				            type: "error"
				        });	 
					}                
	            });
	        }	        
	    });

	    /******************************** OBTIENE DATOS DE ROLES *****************************************/
	    function fnEditarContaInicioCaja(dato) {
	    	console.log("datos",dato);
	    	$("#txtCI").val(dato.contrato.cliente.persona.nrodocumento);
	    	$("#txtNombreCompleto").val(dato.contrato.cliente.persona.nombres +' '+ dato.contrato.cliente.persona.primerapellido +' '+ dato.contrato.cliente.persona.segundoapellido);
	    	if (dato.contrato.codigo) {
	    		$("#txtContrato").val(dato.contrato.codigo);
	    	}else{
	    		$("#txtContrato").val(dato.contrato.codigo_num);
	    	}

	    	$("#txtSucursal").val(dato.sucursal.nombre);
	    	$("#txtFecha").val(dato.created_at);
	    	$("#txtGlosa").val(dato.tipo_de_movimiento);
	    	$("#txtDebe").val(dato.ingreso_bs);
	    	$("#txtHaber").val(dato.egreso_bs);
	    	$("#id").val(dato.id);
	    }

	     /******************************** ACTUALIZA DATOS DE PERSONAS *****************************************/
	    $("#btnActualizar").click(function(){
	        var value =$("#id").val();
	        var route="/ContaInicioFinCaja/"+value+"";	        
            $.ajax({
                url: route,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'PUT',
                dataType: 'json',
                data: {
                    'txtDebe':$("#txtDebe").val(),
                    'txtHaber':$("#txtHaber").val()	            	
                },
                success: function(data){	                    
                    resultado = data.Mensaje;
                	/*resultado = 1  PERSONA GUARDADO*/
                	if (resultado == 1) {
                    	$("#modalUpdateContaInicioCaja").modal('toggle');
                        Swal.fire({
				            title: "CONTA INICIO FIN CAJA!",
				            text: "Se actualizo correctamente!!",
				            confirmButtonText: 'Aceptar',
				            confirmButtonColor: "#66BB6A",
				            type: "success"
				        });				        
				        fnListadoInicioFinCaja('/BuscarContaInicioCaja');
                    }                	
                     
                },  
                beforeSend:function(){
	                    $('.loader').addClass('loader-default  is-active');

	                },
	                complete:function(){
	                    $('.loader').removeClass('loader-default  is-active');
	                },error:function(error){ 
						swal({
				            title: "SOAP...",
				            text: "La busqueda no se puede cargar",
				            confirmButtonColor: "#EF5350",
				            confirmButtonText: 'Aceptar',
				            type: "error"
				        });	 
					}
            });	        
	    });

	    /******************************** LISTADO DE PERSONAS *****************************************/
	    function fnListadoInicioFinCaja(url) {
	    	var parametros = {
	    		'txtFechaInicio':$("#txtFechaInicio").val(),
	        	'txtFechaFin':$("#txtFechaFin").val()
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
					$('.clsContaInicioCaja').html(data);  
				},beforeSend:function(){
	                    $('.loader').addClass('loader-default  is-active');

	                },
	                complete:function(){
	                    $('.loader').removeClass('loader-default  is-active');
	                },error:function(error){ 
						swal({
				            title: "SOAP...",
				            text: "La busqueda no se puede cargar",
				            confirmButtonColor: "#EF5350",
				            confirmButtonText: 'Aceptar',
				            type: "error"
				        });	 
					}
			 }); 
	    }
	    /******************************** ELIMINA DATOS PERSONA ***************************************/
	    function fnEliminarContaDiario(id){
	        var route="/ContaInicioFinCaja/"+id+"";	        
	        Swal.fire({   
	        	title: "Esta seguro de eliminar la cuenta contable?", 
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
						            title: "CONTA INICIO FIN CAJA!",
						            text: "Se elimino correctamente!!",
						            confirmButtonText: 'Aceptar',
						            confirmButtonColor: "#66BB6A",
						            type: "success"
						        });
						        fnListadoInicioFinCaja('/BuscarContaInicioCaja');
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

