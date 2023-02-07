@extends('layout.principal')
@include('reporte.modalReporte')
@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					HISTORIAL DE PAGOS					
				</h3>
			</div>
			</section>
		</div>		
	</div>
	<form id="frmReporteHistorialPagos">
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
	                    <button class="btn btn-default" data-dismiss="modal" style="background:#A5A5B2" type="button" onclick="fnExportarExcelHistorialPagos();">Exportar Excel</button><br>
						{{-- <a href="#" class="btn btn-success">Exportar Excel</a> --}}
	                </div>
	            </div>
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
		var indexItems = 0;  
		$(document).ready(function() {
			$('[data-mask]').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
			$('.clsMensajeAlerta').hide();
			/*/*******************();* ANULAR ENTER EN FORMULARIOS ******************/
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
		        fnListadoRegistrosContables(url);
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
		        $('#frmReporteHistorialPagos').bootstrapValidator('revalidateField', 'txtFechaInicio');
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
		        $('#frmReporteHistorialPagos').bootstrapValidator('revalidateField', 'txtFechaFin');
		    });
		    /******************** VALIDAR FROMULARIO PARA BUSCAR POR FECHAS ******************/
	        $('#frmReporteHistorialPagos').bootstrapValidator({
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

	    });

	    /*EXPORTAR EXCEL LIBRO DIARIO*/
	    function fnExportarExcelHistorialPagos(){
	    	var validarHistorialPagos = $("#frmReporteHistorialPagos").data('bootstrapValidator'); 	     	
	        validarHistorialPagos.validate();
	        if(validarHistorialPagos.isValid())
	        {
	        	var fechaInicio = $("#txtFechaInicio").val();
		    	var fechaFin = $("#txtFechaFin").val();
	        	$.ajax({
					type: 'GET',
					url: "ExportarExcelHistorialPagos/"+fechaInicio+"/"+fechaFin,
					//data:parametros,
					// async: false,
					// contentType: "application/x-www-form-urlencoded;charset=UTF-8; charset=iso-8859-1;application/json;",
					//dataType: 'json',
					success: function (data) {
						console.log(data);
				        var valor = jQuery.parseJSON(data);
				        //console.log(valor.file);
				        var link = document.createElement("a");
				        link.download = "HistorialPagos"+fechaInicio+"_"+fechaFin+".xlsx";
				        //var uri = 'data:application/vnd.ms-excel;base64' + data.file;
				        link.href = valor.file;
				        document.body.appendChild(link);
				        link.click();
				        document.body.removeChild(link);
					},
					beforeSend:function(){
	                    $('.loader').addClass('loader-default  is-active');

	                },
	                complete:function(){
	                    $('.loader').removeClass('loader-default  is-active');
	                },error:function(error){ 
						swal({
				            title: "PERSONA...",
				            text: "Los busqueda no se puede cargar",
				            confirmButtonColor: "#EF5350",
				            confirmButtonText: 'Aceptar',
				            type: "error"
				        });	 
					}
				});	        
	        }
	    }

		
	</script>
@endsection

