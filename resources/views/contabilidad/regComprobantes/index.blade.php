@extends('layout.principal')
@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					SUBIR COMPROBANTES
				</h3>
			</div>
			</section>
		</div>		
	</div>
	<form id="frmContaDiario">
		<div class="row">			
	        <div class="col-md-6">
	            <div class="form-group">
	                <input type="file" id="fileComprobantes" name="file" style="display: none">
	                <input type="text" class="form-control" id="txtVerArchivoComprobantes" readonly>
	            </div>
	        </div>
	        <div class="col-md-2">
	            <div class="form-group">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id="btnElegirComprobantes">Elegir...</button>
                    </span>              
	            </div>
	        </div>
	    </div>
		<div class="row">
	        <div class="col-md-12">            
                <section class="clsComprobante">                	
                	@include('contabilidad.regComprobantes.modals.listadoComprobanteTemp')
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
	    });

		$('#btnElegirComprobantes, #txtVerArchivoComprobantes').on('click', function() {
			$('#fileComprobantes').trigger("click");
		});

		$('#fileComprobantes').change(function() {
			var file_name = this.value.replace(/\\/g, '/').replace(/.*\//, '');
			$('#txtVerArchivoComprobantes').val(file_name);
		});

		$('input[type=file]').on('change', fileUpload);

		function fileUpload(event){
	        files = event.target.files;
	        var file = files[0];
	        console.log("files",files);
	        console.log("fileeeee",file);        
	        //form data check the above bullet for what it is  
	        var data = new FormData();
	        data.append('file', file, file.name);
	        console.log("data",data);
	        var archivo = $("#fileComprobantes").val();
	        var extensiones = archivo.substring(archivo.lastIndexOf("."));
	        console.log("extensiones",extensiones);
	        if(extensiones == ".xlsx" || extensiones == ".xls")
	        {
	        	$('#tblListadoImportacionCompras').empty();
	            $.ajax({
	                data:data,
	                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	                url: 'StoreTemp',
	                type: "POST",
	                dataType: "html",
	                //data: formData,
	                cache: false,
	                contentType: false,
	                processData: false,            
	                success: function (data) {
	                    console.log(data);
	                   	if (data == "") {
			    			console.log("datosss vacios");
			    			$('.clsComprobante').empty();
			    			$('.clsMensajeAlerta').html('<span class="text-semibold">No existe registro de contabilidad!</span>');
			    			$('.clsMensajeAlerta').show();
			    		}else{
			    			$('.clsComprobante').html(data);  
			    			$('.clsMensajeAlerta').empty();
			    			$('.clsMensajeAlerta').hide();
			    		}

			    		$("#fileComprobantes").val('');
                        $("#txtVerArchivoComprobantes").val('');  
	                    
	                                      
	                },
	                beforeSend:function(){
	                    $('.loader').addClass('loader-default  is-active');
	                },
	                complete:function(){
	                    $('.loader').removeClass('loader-default  is-active');

	                },
	                error: function(){
	                    console.log(error);
	                }                   
	            }); 
	        }
	        
	        else{
	            //alert("El archivo de tipo " + extensiones + "no es válido");
	            swal({
		            title: "COMPROBANTES...",
		            text: "La extension debe ser xls o xlsx",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });
	            $("#fileComprobantes").val('');
	            $("#txtVerArchivoComprobantes").val('');
	        }
	    }

	    function fnGenerarComprobante(event){
	    	var route="/Comprobante";
			//console.log("comision",comision);
	        Swal.fire({   
	        	title: "Esta seguro de generar los comprobantes?", 
	          	html: '',
	          	type: "warning",   showCancelButton: true,
	          	confirmButtonColor: "#3085d6",
	          	cancelButtonColor: '#d33',   
	          	confirmButtonText: "Si, generar!",
	          	closeOnConfirm: false 
	        }).then((result) => {
				if (result.value) {
					$.ajax({
	                    url: route,
	                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	                    type: 'POST',
	                    dataType: 'json',
	                    data: {
			            	'1':"1"			            	
			            },
	                    success: function(data){
	                        console.log(data.Mensaje);
		                	resultado = data.Mensaje;

		                	/*resultado = 1  ROL ELIMINADO*/
		                	if (resultado == 1) {
	                            Swal.fire({
						            title: "COMPROBANTES!",
						            text: "Se genero correctamente!!",
						            confirmButtonText: 'Aceptar',
						            confirmButtonColor: "#66BB6A",
						            type: "success"
						        });
						        $('.clsComprobante').empty();
	                        }

		                	
	                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
	                        if (resultado == 0) {                                
	                            Swal.fire({
						            title: "COMPROBANTES...",
						            text: "hubo problemas al registrar en BD",
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }

	                        /*resultado = -1 SESION EXPIRADA*/
	                        if (resultado == "-1") {                                
	                            Swal.fire({
						            title: "COMPROBANTES...",
						            text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
						            confirmButtonColor: "#EF5350",
						            confirmButtonText: 'Aceptar',
						            type: "error"
						        });
	                        }
	                    },
	                    beforeSend:function(){
	                    $('.loader').addClass('loader-default  is-active');
		                },
		                complete:function(){
		                    $('.loader').removeClass('loader-default  is-active');

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

