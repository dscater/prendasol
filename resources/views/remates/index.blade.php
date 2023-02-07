@extends('layout.principal')
@include('reporte.modalReporte')
@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					REMATES DE CONTRATO
				</h3>
			</div>
			</section>
		</div>		
	</div>
	<form id="frmRemates">
		{{-- <div class="row">			
	        <div class="col-md-3">
	            <div class="form-group">
	                <label>Opciones:</label>								
	                <select class="form-control" id="ddlOpcion" name="ddlOpcion" Value="">
                        <option></option>
                        <option value="1">
                            de 2 a 3 meses
                        </option>
                        <option value="2">
                            de 4 a 8 meses
                        </option>
                        <option value="3">
                            de 9 a 12 meses
                        </option> 
                        <option value="4">
                            Mayor a 1 año
                        </option>                        
                    </select>             
	            </div>
	        </div>
		</div>
		 --}}
		 <div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<select name="filtro" id="filtro" class="form-control">
						<option value="diario">Diario</option>
						<option value="fecha">Por fechas</option>
					</select>
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
		 <div class="row" id="valoresReporte">
		 </div>

		<div class="row">
	        <div class="col-md-12">            
                <section class="clsRemates">                	
                	@include('remates.modals.listadoRemates')
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
        let valoresReporte = $('#valoresReporte');
		$(document).ready(function() {
            filtro();
            $('#filtro').change(filtro);
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
	    });

		function iniciaFechas()
        {
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
                $('#frmRemates').bootstrapValidator('revalidateField', 'txtFechaInicio');
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
                $('#frmRemates').bootstrapValidator('revalidateField', 'txtFechaFin');
            });
    	
        }

		function filtro()
        {
            let filtro = $('#filtro').val();
            if(filtro == 'diario')
            {
                valoresReporte.html(`
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Inicio:</label>
                        <div class="input-group input-append date" id="datePickerFI">
                            <input type="text" class="form-control" id="txtFechaInicio" name="txtFechaInicio"
                                placeholder="Ingrese Fecha de Inicio" />
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>`);

                $('#frmRemates').bootstrapValidator({
                    message: 'This value is not valid',
                    feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        txtFechaInicio: {
                            message: 'Fecha no es valida',
                            validators: {
                                notEmpty: {
                                    message: 'Fecha de Inicio es requerida'
                                },
                                //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                            }
                        },
                    }
                });
            }
            else{
                valoresReporte.html(`
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Inicio:</label>
                        <div class="input-group input-append date" id="datePickerFI">
                            <input type="text" class="form-control" id="txtFechaInicio" name="txtFechaInicio"
                                placeholder="Ingrese Fecha de Inicio" />
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Fin:</label>
                        <div class="input-group input-append date" id="datePickerFF">
                            <input type="text" class="form-control" id="txtFechaFin" name="txtFechaFin"
                                placeholder="Ingrese Fecha Fin" />
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>`);

                $('#frmRemates').bootstrapValidator({
                    message: 'This value is not valid',
                    feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        txtFechaInicio: {
                            message: 'Fecha no es valida',
                            validators: {
                                notEmpty: {
                                    message: 'Fecha de Inicio es requerida'
                                },
                                //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                            }
                        },
                        txtFechaFin: {
                            message: 'Fecha no es valida',
                            validators: {
                                notEmpty: {
                                    message: 'Fecha Fin es requerida'
                                },
                                //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
                            }
                        }
                    }
                });
            }
            iniciaFechas();
        }

	    /******************************** BUSCA DATOS POR FECHAS *****************************************/
	    $("#btnBuscar").click(function(){	  
	     	var validarRemates = $("#frmRemates").data('bootstrapValidator'); 
	     	console.log(validarRemates);
	        validarRemates.validate();
	        if(validarRemates.isValid())
	        {
	        	var parametros = {
	        		'filtro':$("#filtro").val(),
	        		'fecha_ini':$("#txtFechaInicio").val(),
	        		'fecha_fin':$("#txtFechaFin").val(),
	        	};
		    	var route ="/BuscarRemates";
		    	$.ajax({
	                url:route,
	                data:parametros,
	                method:'GET',
	                //dataType:'json',
	                success:function(data){
	                	console.log("datoss",data);
			    		if (data == "") {
			    			console.log("datosss vacios");
			    			$('.clsRemates').empty();
			    			$('.clsMensajeAlerta').html('<span class="text-semibold">No existe registro de remates de esa intervalo de mes!</span>');
			    			$('.clsMensajeAlerta').show();
			    		}else{
			    			$('.clsRemates').html(data);  
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
	    /********************** IMPRIMIR ************************/
	    function fnImprimirRemate(){
	    	var filtro = $("#filtro").val();
	    	var fecha_ini = $("#txtFechaInicio").val();
	    	var fecha_fin = $("#txtFechaFin").val();
	    	var src = `/ImprimirRemate/${filtro}/${fecha_ini}/${fecha_fin}`;
	    	$("#reporteModal").modal();
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);            
            $("#dialog").show();
	    }
	</script>
@endsection

