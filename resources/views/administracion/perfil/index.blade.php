@extends('layout.inicio')

@section('main-content')	
		<div class="row">
			<div class="col-md-12">
				<section class="content-header">
				<div class="header_title">
					<h3>
						PERFIL					
					</h3>
				</div>
				</section>
			</div>		
		</div>	
		<div class="row">            
	        <div class="col-md-12">
	        	<div class="form-group">
					<label>Rol:</label>
	                <select id="ddlRol" name="ddlRol" data-placeholder="Elige Rol" class="select">
	                    <option></option>
	                    @if(!empty($roles))
					  		@foreach($roles as $rol)
						    	<option value="{{ $rol->id }}"> {{ $rol->rol }}</option>
					  		@endforeach
						@endif
	                </select>
	            </div>
	        </div>
	    </div>

	    <div class="row">            
	        <div class="col-sm-6">
	            <section class="clsAsignado">
	            	@include('administracion.perfil.modal.listadoAsignado')                    
	            </section>            
	        </div>
	        <div class="col-sm-6">
	        	<section class="clsAsignadoPefil">
	            	@include('administracion.perfil.modal.listadoPerfilAsignado')                    
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
	    });

	    $('#ddlRol').on('change', function() {
	        console.log("valorrr",this.value);
	        valor = this.value; 
	        fnListadoOpciones(valor);	
	        fnListadoOpcionesPerfil(valor)        ;
	    });

	    /******************************** LISTADO DE OPCIONES NO ASIGNADAS ***********************************/
	    function fnListadoOpciones(id) {	    	
	    	var route ="/ListadoOpciones/"+id;	    	
	    	$.get(route, function(data){
	    		console.log(data);
	    		$('.clsAsignado').html(data);	    		
	    	}).fail(function () {
	    		swal({
		            title: "Perfil...",
		            text: "Perfil no se puede cargar",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });	            
	        });
	    }

	    /*************************** LISTADO DE OPCIONES PERFIL ASIGNADAS *****************************/
	    function fnListadoOpcionesPerfil(id) {	    	
	    	var route ="/ListadoOpcionesPerfil/"+id;	    	
	    	$.get(route, function(data){
	    		console.log("perfll",data);
	    		$('.clsAsignadoPefil').html(data);	    		
	    	}).fail(function () {
	    		swal({
		            title: "Perfil...",
		            text: "Perfil no se puede cargar",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });	            
	        });
	    }

	    /******************************** REGISTRA PERFIL *****************************************/
	    $("#btnAsignar").click(function(){	
	    	console.log("sasas");    	
	        var route="/Perfil";
        	$.ajax({
	            url: route,
	            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            type: 'POST',
	            data: {
	            	'asignar':$("#asignar").val()
	            },
                success: function(data){
                	console.log("mesnaje",data.Mensaje);
                	resultado = data.Mensaje;

                	/*resultado = 1  PERFIL GUARDADO*/
                	if (resultado == 1) {                    	
                        swal({
				            title: "Perfil!",
				            text: "Se registro correctamente!!",
				            confirmButtonText: 'Aceptar',
				            confirmButtonColor: "#66BB6A",
				            type: "success"
				        });				        
				        //fnListadoOpciones($("#ddlRol").val());	
	        			//fnListadoOpcionesPerfil($("#ddlRol").val())   
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
	        	        
	    });
	</script>
@endsection

