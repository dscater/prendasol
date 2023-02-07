<div class="table-responsive">
	@if(isset($perfilAsignados))
		<table class="table">
			<thead>
				<tr>
	                <td colspan="4">
	                    <strong>ASIGNADOS</strong>
	                </td>
	            </tr>
				<tr>
					<th>#</th>
					<th>Módulo</th>
					<th>Opción</th>
				</tr>
			</thead>
			<tbody>				
				@foreach ($perfilAsignados as $perfilAsignado)
					<tr>
						<td><input type="checkbox" name="ckkDesasignar" value="{{ $perfilAsignado->id }}"></td>
						<td>{{ $perfilAsignado->modulo }}</td>
						<td>{{ $perfilAsignado->opcion }}</td>
					</tr>
				@endforeach
				<tr>
                    <td colspan="4">
                        {!!link_to('#',$title='DESASIGNAR', $attributes=['id'=>'ckkDesasignar','class'=>'btn bg-green waves-effect pull-right'], $secure=null)!!}
                    </td>
                </tr>
			</tbody>
		</table>
	@endif
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


	/******************************** DESAIGNA PERFIL *****************************************/
    $("#ckkDesasignar").click(function(){	
    	var selected = [];    
        $(":checkbox[name=ckkDesasignar]").each(function(){
            if (this.checked) {
                //selected += $(this).val()+', ';
                selected.push($(this).val());
            }
        }); 
    	console.log("sasas",selected); 
    	if (selected.length) {
    		var route="/DesAsignarOpciones";
	    	$.ajax({
	            url: route,
	            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            type: 'POST',
	            data: {
	            	'ckkDesasignar':selected,
	            	'ddlRol':$("#ddlRol").val()
	            },
	            success: function(data){
	            	console.log("mesnaje",data.Mensaje);
	            	resultado = data.Mensaje;

	            	/*resultado = 1  PERFIL GUARDADO*/
	            	if (resultado == 1) {                    	
	                    swal({
				            title: "Perfil(es) Des Asignado!",
				            text: "Se registro correctamente!!",
				            confirmButtonText: 'Aceptar',
				            confirmButtonColor: "#66BB6A",
				            type: "success"
				        });				        
				        fnListadoOpciones($("#ddlRol").val());	
	        			fnListadoOpcionesPerfil($("#ddlRol").val())   
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
    	else{
    		swal({
	            title: "Perfil...",
	            text: "Elija opciones para pòder desasignar",
	            confirmButtonColor: "#EF5350",
	            confirmButtonText: 'Aceptar',
	            type: "error"
	        });
    	}	
    });

   
</script>