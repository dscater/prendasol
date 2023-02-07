@extends('layout.principal')
@include('contabilidad.registrosContables.modals.modalCreate')
@section('main-content')
	<div class="row">
		{{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token"> --}}
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					REGISTROS CONTABLES
					<small>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateRegistroContable"><i class="icon-new-tab position-left"></i>Nuevo</button>
					</small>
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
                <section class="clsRegsitrosContables">                	
                	@include('contabilidad.registrosContables.modals.listadoRegistrosContables')
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
	        $('#frmRegsitroContable').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                ddlTipoComprobante: {
	                    message: 'Tipo de Comprobante no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Tipo de Comprobante es requerida'
	                        },
	                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
	                    }
	                },
	                txtFecha: {
	                    message: 'Fecha no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Fecha es requerida'
	                        },
	                        //date: {format: "dd-mm-yyyy", message: 'La fecha no es valido'}
	                    }
	                }
	            }
	        });

	        /* OBTIENE DATO DE LA CUENTA*/
			$(document).on('change', '.ddlCuenta', function () {
				var num = $(this).parent().parent().attr('index');
				var elemento = $("#ddlCuenta_" + num).val();
				var elemento1 = $("#ddlCuenta_" + num +" option:selected").text();
				var campo = elemento1.split('->');
				console.log(num);
				console.log(elemento);
				console.log(campo[1]);	
				$("#cuenta_" + num).val(campo[1]);				
			});
	    });

	    /*FUNCION CARGA CUENTAS*/
		function fnCargarComboCuentas(indexItems) {
		    //$('#ddlCuenta_'+indexItems)[0].options.length = 0;
		    var route = "/CargarCuentas";
		    $.ajax({
		        url: route,
		        type: 'GET',
		        dataType: 'json',
		        success: function (data) {
		            console.log(data.data);
		            //var valor = jQuery.parseJSON(data);
		            var htmlCuenta = '';
		            htmlCuenta += '<option value="">-- Seleccione --</option>';
		            $.each(data.data, function (ind, elem) {
		                //console.log(elem);
		                htmlCuenta += '<option value=' + elem.id + '>' + elem.cod_deno + ' ->' + elem.descripcion + '</option>';
		            });
		            $('#ddlCuenta_' + indexItems).append(htmlCuenta);
		        },
		        error: function (result) {
		            swal("Error!", "Ocupación no se pudo Cerrar!", "error")
		        }
		    });
		};

		/*AGREGAMOS DETALLE DEL COMPROBANTE*/
		function agregar_fila_cuenta() {
			fnCargarComboCuentas(indexItems);
			console.log("indexItems", indexItems);
			if (indexItems == 0) {
				var itemColumn = $('<tr id="items_' + indexItems + '" index="' + indexItems + '" class="items_colums" >'
					+ '<td><input type="hidden" class="form-control form-control-sm m-input--solid" id="codigo_' + indexItems + '" onkeyup="loadXMLDoc(' + indexItems + ')"><select class="form-control ddlCuenta" id="ddlCuenta_' + indexItems + '" name="ddlCuenta" placeholder="Seleccione" required></select><input type="hidden" id="txtDiarioId_' + indexItems + '" name="custId" value="0" class="txtDiarioId"></td>'
					+ '<td><div id="cuenta2_' + indexItems + '"><input type="input" class="form-control form-control-sm m-input  m-input--solid" id="cuenta_' + indexItems + '"><input type="input" class="form-control form-control-sm m-input--solid" id="codigo2_' + indexItems + '" style="display: none;" ></div></td>'
					+ '<td><textarea class="form-control form-control-sm m-input  m-input--solid" id="glosa_' + indexItems + '" ></textarea></td>'
					+ '<td><input type="input" class="form-control debes form-control-sm m-input  m-input--solid " id="debe_' + indexItems + '" name="debes"></td>'
					+ '<td><input type="input" class="form-control habers form-control-sm m-input  m-input--solid " id="haber_' + indexItems + '" name="habers"></td>'
					+ '<td><a href="javascript:void(0)" class="eliminarCuenta btn btn-danger m-btn m-btn--icon btn-sm m-btn--icon-only  m-btn--pill m-btn--air" ><i class="la la-times"></i></a></td></tr>');
				//    $('.item_name', itemColumn).autocomplete(autocomplete_action);
				$('#detalle_cuentas').append(itemColumn);
				$(".ddlCuenta").select2({
					placeholder: "Seleccione Cuenta",
					//width: '250px',
					dropdownAutoWidth: true,
					allowClear: !0,					
					escapeMarkup: function (markup) {
						return markup;
					},
				});
				//document.getElementById("codigo_" + indexItems + "").focus();
				indexItems++;
			}
			else {
				if (indexItems > 0) {
					indexAnteriorD = indexItems;
					indexAnteriorD--;
					console.log("indexAnteriorD", indexAnteriorD);
				}
				if (indexAnteriorD >= 0) {
					var debe = $("#debe_" + indexAnteriorD).val();
					var haber = $("#haber_" + indexAnteriorD).val();
					//var codigo=$("#codigo_"+indexAnteriorD).val();
					var codigo = $("#ddlCuenta_" + indexAnteriorD).val();
					var actividad = $("#actividad_" + indexAnteriorD).text();

					var n = Number(0);
					// if (codigo === "") {
					// 	Swal.fire({
				 //            title: "Codigo...",		            
				 //            //html: 'El Contrato  <b>'+  data.Mensaje +'</b> y se registro correctamente los datos',//text: "La sesión 
				 //            html: 'Ingreso Codigo',//text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
				 //            confirmButtonColor: "#EF5350",
				 //            confirmButtonText: 'Aceptar',
				 //            type: "warning"
				 //        });
					// 	//toastr.warning("Debe introducir por lo menos un digito", "Codigo!");
					// 	//document.getElementById("codigo_" + indexAnteriorD + "").focus();
					// 	return false
					// } else {
					// 	if (variable_ingreso === 1) {
					// 	} else {
					// 		variable_ingreso = 1;
					// 	}
					// }
					// if (actividad === "T") {
					// 	//toastr.warning("La cuenta debe ser de Movimiento", "Cuenta!");
					// 	//document.getElementById("codigo_" + indexAnteriorD + "").focus();
					// 	//return false;
					// }

					// if (debe === "") {
					// 	if (haber === "") {
					// 		toastr.warning("El Debe o Haber debe tener un monto", "Debe o Haber!");
					// 		return false;
					// 	}
					// }
					var itemColumn = $('<tr id="items_' + indexItems + '" index="' + indexItems + '" class="items_colums" >'
						//+'<td><input type="input" class="form-control form-control-sm m-input--solid" id="codigo_'+indexItems+'" onkeyup="loadXMLDoc('+indexItems+')" onkeypress="return solo_numeros(event);"></td>'
						+ '<td><input type="hidden" class="form-control form-control-sm m-input--solid" id="codigo_' + indexItems + '" onkeyup="loadXMLDoc(' + indexItems + ')"><select class="form-control ddlCuenta" id="ddlCuenta_' + indexItems + '" name="ddlCuenta" placeholder="Seleccione" required></select><input type="hidden" id="txtDiarioId_' + indexItems + '" name="custId" value="0" class="txtDiarioId"></td>'
						+ '<td><div id="cuenta2_' + indexItems + '"><input type="input" class="form-control form-control-sm m-input  m-input--solid" id="cuenta_' + indexItems + '"><span class="m-form__help" id="codigo2_' + indexItems + '" ></span></td>'
						+ '<td><textarea class="form-control form-control-sm m-input  m-input--solid" id="glosa_' + indexItems + '" ></textarea></td>'
						+ '<td><input type="input" class="form-control debes form-control-sm m-input  m-input--solid" id="debe_' + indexItems + '" name="debes"></td>'
						+ '<td><input type="input" class="form-control habers form-control-sm m-input  m-input--solid" id="haber_' + indexItems + '" name="habers"></td>'
						+ '<td><a href="#" class="eliminarCuenta btn btn-danger m-btn m-btn--icon btn-sm m-btn--icon-only  m-btn--pill m-btn--air" ><i class="la la-times"></i></a></td></tr>');
					//    $('.item_name', itemColumn).autocomplete(autocomplete_action);
					$('#detalle_cuentas').append(itemColumn);
					//document.getElementById("codigo_" + indexItems + "").focus();
					indexItems++;
					$(".ddlCuenta").select2({
						placeholder: "Seleccione Cuenta",
						//width: '250px',
						dropdownAutoWidth: true,
						allowClear: !0,						
						escapeMarkup: function (markup) {
							return markup;
						},
					});
				}
			}		    
		}

		$(document).on('blur', '.debes', function () {
			var index = $(this).parent().parent().attr('index');
			console.log(index);
			//$(this).parent('td').html( lastSpan + $(this).val() +"</span>" );
			fnActualizarTotaldebe();
		});
		function fnActualizarTotaldebe() {
			var totaldebe = 0;
			$('.debes').each(function () {
				//console.log($(this).val())
				totaldebe += +$(this).val();
			});
			$('#total_debe').html(Number(totaldebe).toFixed(2));
		}
		//Actualiza sumatoria de datos del haber
		$(document).on('blur', '.habers', function () {
			var index = $(this).parent().parent().attr('index');
			console.log(index);
			//$(this).parent('td').html( lastSpan + $(this).val() +"</span>" );
			fnActualizarTotalhaber();
		});
		function fnActualizarTotalhaber() {
			var totalhaber = 0;
			$('.habers').each(function () {
				//console.log($(this).val())
				totalhaber += +$(this).val();
				var dato = $(this).text();
			});
			$('#total_haber').html(Number(totalhaber).toFixed(2));
		}

		//Elimina una fila
		$(document).on('click', '.eliminarCuenta', function () {
			var fila = $(this).closest('tr');
			var txtIngresoId = fila.find('.txtDiarioId');
			var valor = txtIngresoId.val();
			console.log("id", valor);
			if (valor == 0) {
				$('#items_' + $(this).parent().parent().attr('index')).remove();
				fnActualizarTotaldebe();
				fnActualizarTotalhaber();
			}
			// else {
			// 	var eliminar = $('#items_' + $(this).parent().parent().attr('index'));
			// 	swal({
			// 		title: "Esta seguro de eliminar la Cuenta?",
			// 		text: "Presione Si para eliminar el registro de la base de datos!",
			// 		type: "warning",
			// 		showCancelButton: true,
			// 		confirmButtonColor: "#DD6B55",
			// 		confirmButtonText: "Si, Eliminar!",
			// 		//closeOnConfirm: false,
			// 		cancelButtonText: 'Cancelar',
			// 	}).then((result) => {
			// 		if (result.value) {
			// 			$.ajax({
			// 				data: {
			// 					id: valor
			// 				},
			// 				url: 'Database/Contabilidad/Ingreso/eliminar_cuenta.php',
			// 				type: 'post',
			// 				dataType: 'json',
			// 				success: function (data) {
			// 					swal({
			// 						title: "CUENTA!",
			// 						text: "Se elimino correctamente!!",
			// 						confirmButtonText: 'Aceptar',
			// 						confirmButtonColor: "#66BB6A",
			// 						type: "success"
			// 					}).then(function () {
			// 						eliminar.remove();
			// 						fnActualizarTotaldebe();
			// 						fnActualizarTotalhaber();
			// 					});
			// 				},
			// 				error: function (result) {
			// 					swal("Opss..!", "El La persona tiene registros en otras tablas!", "error")
			// 				}
			// 			})
			// 		}
			// 	});
			// }
		});

		$("#btnRegistrar").click(function(){
	        var route="/RegistroContable";
	     	var validarRegistroContable = $("#frmRegsitroContable").data('bootstrapValidator'); 
	     	console.log(validarRegistroContable);
	        validarRegistroContable.validate();
	        if(validarRegistroContable.isValid())
	        {
	        	if (indexItems == 0) {
	        		Swal.fire({
			            title: "REGISTRO CONTABLE...",
			            html: 'Debe introducir cuentas para los comprobantes',
			            confirmButtonColor: "#EF5350",
			            confirmButtonText: 'Aceptar',
			            type: "warning"
			        });
	        	}
	        	else{
	        		var totaldebe = $("#total_debe").text();
					var totalhaber = $("#total_haber").text();
					if (totaldebe == totalhaber) {						
			        	var cuenta = [];
			            var glosa = [];
			            var debe = [];
			            var haber = [];			            
			        	$('.items_colums').each(function () {
			                var index = $(this).attr('index');	                             
			                cuenta.push($('#ddlCuenta_' + index).val());
			                glosa.push($('#glosa_' + index).val());
			                debe.push($('#debe_' + index).val());
			                haber.push($('#haber_' + index).val());
			                
			            });
			        	$.ajax({
				            url: route,
				            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				            type: 'POST',
				            data: {
				            	'ddlTipoComprobante'	:$("#ddlTipoComprobante").val(),
				            	'txtFecha'				:$("#txtFecha").val(),
				            	'cuenta'				:cuenta,
				            	'glosa'					:glosa,
				            	'debe'					:debe,
				            	'haber'					:haber
				            },
			                success: function(data){
			                	console.log(data);
			                	resultado = data.Mensaje;

			                	/*resultado = 1  USUARIO GUARDADO*/
			                	if (data.Mensaje) {
		                        	$("#modalCreateRegistroContable").modal('toggle');
		                            Swal.fire({
							            title: "REGISTRO CONTABLE!",
							            text: "Se registro correctamente!!",
							            confirmButtonText: 'Aceptar',
							            confirmButtonColor: "#66BB6A",
							            type: "success"
							        });
							        // fnBuscarContratos($("#txtIdPersonaOculto").val());
							        // fnLimpiarControles();
							        // fnImprimirContrato(data.idContrato);
							        // fnListadoUsuarios('/Usuario');
							        fnListadoRegistrosContables('/RegistroContable')
		                        }

			                	/*resultado = 2  EXISTE EL USUARIO*/
			                	if (resultado == 2) {
		                            Swal.fire({
							            title: "USUARIO...",
							            text: 'El USUARIO: <b>'+  $("#txtUsuario").val() +'</b> ya existe en la base de datos',
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
		                        //Swal.fire("Opss..!", "Succedio un problema al registrar inserte bien los datos!", "error");
			                }
				        });

					}
					else{
					 	Swal.fire({
				            title: "REGISTRO CONTABLE...",
				            html: 'Las sumas totales deben ser iguales',//text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
				            confirmButtonColor: "#EF5350",
				            confirmButtonText: 'Aceptar',
				            type: "warning"
				        });
					}
	        	}
	        }	        
	    });

		/******************************** LISTADO DE PERSONAS *****************************************/
	    function fnListadoRegistrosContables(url) {
	    	var parametros = {
	    		// 'txtBuscarIdentifiacion':$("#txtBuscarIdentifiacion").val(),
	    		// 'txtBuscarNombres':$("#txtBuscarNombres").val(),
	    		// 'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
	    		// 'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
	    		// 'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
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
					$('.clsRegsitrosContables').html(data);  
				},error:function(error){ 
					Swal.fire({
			            title: "REGISTRO CONTABLE...",
			            text: "Los roles no se puede cargar",
			            confirmButtonColor: "#EF5350",
			            confirmButtonText: 'Aceptar',
			            type: "error"
			        });	 
				}
			 }); 


	    }

		
	</script>
@endsection

