@extends('layout.principal')
@include('contrato.modals.modalDetalleContrato')
{{-- @include('reporte.modalReporte') --}}
@include('reporte.modalReporteDesembolso')
@include('reporte.modalReporteContrato')
@include('contrato.modals.modalCreateContrato')
@include('reporte.modalReporeteComprobante')
@include('reporte.modalReporteComprobante2')
@include('reporte.modalReporteBoleta')
@include('reporte.modalContratoCambio')
@include('contrato.modals.modal_camara')
@include('contrato.modals.modal_img')

@section('main-content')
	<input type="hidden" name="txtIdContrato" id="txtIdContrato">               
	<div class="row">
		<div class="col-md-12">
			<section class="content-header">
			<div class="header_title">
				<h3>
					CLIENTES				
				</h3>
			</div>
			</section>
		</div>		
	</div>

	<div class="row">
		<div class="col-md-2 form-group">
			<label>Numero Identificación: </label>
			<div class="input-group">
				<span class="input-group-addon"><i class="icon-list-numbered"></i></span>
				@if(session('personaNroDoc'))
				<input type="text" class="form-control" id="txtBuscarIdentifiacion" value="{{session('personaNroDoc')}}" placeholder="Identificación">
				@else
				<input type="text" class="form-control" id="txtBuscarIdentifiacion" placeholder="Identificación">
				@endif
			</div>
		</div>
		<div class="col-md-2 form-group">
			<label>Primer Apellido: </label>
			<div class="input-group">
				<span class="input-group-addon"><i class="icon-user"></i></span>
				<input type="text" class="form-control" id="txtBuscarPaterno" placeholder="Primer Apellido">
			</div>
		</div>
		<div class="col-md-2 form-group">
			<label>Segundo Apellido: </label>
			<div class="input-group">
				<span class="input-group-addon"><i class="icon-user"></i></span>
				<input type="text" class="form-control" id="txtBuscarMaterno" placeholder="Segundo Apellido">
			</div>
		</div>
		<div class="col-md-2 form-group">
			<label>Nombres: </label>
			<div class="input-group">
				<span class="input-group-addon"><i class="icon-users"></i></span>
				<input type="text" class="form-control" id="txtBuscarNombres" placeholder="Nombres">
			</div>
		</div>
		<div class="col-md-2 form-group">
			<label>Fecha Nacimiento: </label>			
            <div class="input-group">
              	<div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
              	</div>
                <input type="text" id="txtBuscarFechaNacimiento" name="txtBuscarFechaNacimiento" class="form-control" data-mask>
            </div> 
		</div>
		@if($datoValidarCaja)
		@if($datoValidarCaja->estado_id == 1)
		<div class="col-md-2 form-group">
			<label>&nbsp;</label>
			<p><button type="button" class="btn btn-warning btn-xs" onclick="fnBuscarPersonas()"><i class="icon-search4 position-left"></i>Buscar</button></p>
		</div>		
		@endif
		@else
		<div class="col-md-2 form-group">
			<label>&nbsp;</label>
			<p><button type="button" class="btn btn-warning btn-xs" onclick="fnBuscarPersonas()"><i class="icon-search4 position-left"></i>Buscar</button></p>
		</div>		
		@endif
	</div>

    @if($datoValidarCaja)
	@if($datoValidarCaja->estado_id == 2)
	<div class="alert alert-danger">
		<button class="close" data-dismiss="alert">&times;</button>
		Nose pueden realizar mas contratos porque la caja <b>{{session::get('CAJA')}}</b> de la sucursal <b>{{session::get('ID_SUCURSAL')}}</b> ya cerro.
	</div>
	@endif
	@endif

	<div class="row">            
        <div class="col-md-12">            
            <section class="clsClientes">
            	@include('contrato.modals.listadoClientes')                    
            </section>            
        </div>
    </div>

    <div class="row">            
        <div class="col-md-12">            
            <section class="clsContratos">                	
            	@include('contrato.modals.listadoContrato')
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
	
	<input type="hidden" id="txtValorBs" value="{{$cambio->valor_bs}}">
	<input type="hidden" id="txtValorSus" value="{{$cambio->valor_sus}}">
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">    
    	var indexItemsC=0;
    	var indexAnterior = -1;
    	var precio10klts;
    	var precio14klts;
    	var precio18klts;
    	var precio24klts;
    	var maxPrecio10Klts = 0;
    	var maxPrecio14Klts = 0;
    	var maxPrecio18Klts = 0;
    	var maxPrecio24Klts = 0;
    	var maxPrecioKlts = 0;
    	var totalTasacion10klts = 0;
    	var totalTasacion14klts = 0;
    	var totalTasacion18klts = 0;
    	var totalTasacion24klts = 0;
    	var totalTasacionGeneral = 0;
    	var statSend = false;

		let moneda_actual = 'bs';//para saber a que moneda convertir
		let valor_bs = $('#txtValorBs').val();
		let valor_sus = $('#txtValorSus').val();

		console.log(valor_bs + " / "+ valor_sus);

	    $(document).ready(function() {
	    	fnObtienePrecioOro();	    	
			//alert("dasdads");
			// $("#txtCI").numeric(false);
			// $("#txtCIA").numeric(false);
			// $("#txtTelefonoDomicilio").numeric(false);
			// $("#txtTelefonoDomicilioA").numeric(false);
			// $("#txtTelefonoTrabajo").numeric(false);
			// $("#txtTelefonoTrabajoA").numeric(false);
			// $("#txtCelular").numeric(false);
			// $("#txtBuscarIdentifiacion").numeric(false);
			// $('[data-mask]').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
			$('[data-mask]').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
			/*/******************** ANULAR ENTER EN FORMULARIOS ******************/
			$('form').keypress(function(e){   
				if(e == 13){
					return false;
				}
			});

			// ACCIONES CAMARA
			$(document).on('click','.ver_detalle a',function(){
				let url = $(this).attr('data-foto');
				$('#contenedorImagenDetalle').attr('src',url);
				$('#contenedorImagenDetalle').attr('src',url);
				$('#modal_img').modal('show');
			});

			let index_actual = null;

			let info_imagen = null;
			$('#tomar').click(function(){
				info_imagen = tomarFoto();
			});

			$("#cancelar_camara").click(function () {
				$('#modal_camara').modal('hide');
				setTimeout(function(){
					$('body').addClass("modal-open");
				},500);	
			});

			$('#guardar').click(function() {
				$('#foto_'+index_actual).val(info_imagen);
				$('#imagen_tomada'+index_actual).attr('src',info_imagen);
				$('#modal_camara').modal('hide');
				turnOffCamera();
				setTimeout(function(){
					$('body').addClass("modal-open");
				},500);
			});

			$('#detalle_contratos').on('click','.items_colums td.opciones a.camara',function(){
				turnOnCamera();
				let fila = $(this).closest('tr');
				let index = fila.attr('index');
				index_actual = index;
				let foto_actual = $('#foto_'+index).val()
				cxt.clearRect(0, 0, 720,640);
				if(foto_actual != ''){
					let img = new Image();
					img.src = foto_actual.trim();
					let imagen_tomada = document.getElementById("imagen_tomada"+index);
					cxt.drawImage(imagen_tomada, 0, 0, 720, 640);
				}
				$('#modal_camara').modal('show');
			});

			// FIN ACCIONES CAMARA

			/*/******************** ANULAR ENTER EN FORMULARIOS ******************/
			$('input').keypress(function(e){
				if(e.which == 13){
					return false;
				}
			});    

	        /******************** FUNCION PARA LA PAGINACIÓN AL HACER CLICK MEDIANTE AJAX ******************/
	        $('body').on('click', '.pagination a', function(e) {
		        e.preventDefault();
		        var url = $(this).attr('href');  
		        fnListadoPersonas(url);
		        window.history.pushState("", "", url);
		    });	

		    /******************** VALIDAR FORMULARIO CONTRATO PARA LA INSERCION ******************/
	        $('#frmContrato').bootstrapValidator({
	            message: 'This value is not valid',
	            feedbackIcons: {
	                valid: 'glyphicon glyphicon-ok',
	                invalid: 'glyphicon glyphicon-remove',
	                validating: 'glyphicon glyphicon-refresh'
	            },
	            fields: {
	                txtCliente: {
	                	message: 'Nombres no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Cliente es requerida'
	                        }
	                    }
	                },
	                txtCodigoCliente: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Codigo de cliente es requerida'
	                        }
	                    }
	                },
	                txtFechaContrato: {
	                	message: 'Fecha Contrato no es valida',
	                    validators: {
	                        notEmpty: {
	                            message: 'Fecha Contrato es requerida'
	                        }
	                    }
	                },
	                // txtCodigoCredito: {
	                //     validators: {
	                //         notEmpty: {
	                //             message: 'Codigo de credito es requerida'
	                //         }
	                //     }
	                // },
	                txtMoneda: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Moneda es requerida'
	                        }
	                    }
	                },
	                txtGarantia: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Valor Garantia es requerida'
	                        }
	                    }
	                },
	                txtFondo: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Fondo es requerida'
	                        }
	                    }
	                },
	                txtPesoBruto: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Peso Bruto es requerida'
	                        }
	                    }
	                },
	                txtTipoContrato: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Tipo Contrato es requerida'
	                        }
	                    }
	                },
	                txtPesoNeto: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Peso Neto es requerida'
	                        }
	                    }
	                },
	                txtCreditoMax: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Credito Max es requerida'
	                        }
	                    }
	                },
	                txtMontoCredito: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Monto Crédito es requerida'
	                        }
	                    }
	                },
	                txtTipoPP: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Tipo PP es requerida'
	                        }
	                    }
	                },
	                txtCapitaBs: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Capital Bs. es requerida'
	                        }
	                    }
	                },
	                txtInteresConv: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Interes Conv es requerida'
	                        }
	                    }
	                },
	                txtTipoInteres: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Tipo Interes es requerida'
	                        }
	                    }
	                },
	                txtIntereses: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Intreses es requerida'
	                        }
	                    }
	                },
	                txtNroCuotas: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Nro. Cuotas es requerida'
	                        }
	                    }
	                },
	                txtGastosAdm: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Gastos Adm. es requerida'
	                        }
	                    }
	                },
	                txtFormaPago: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Forma Pago es requerida'
	                        }
	                    }
	                },
	                txtTotales: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Totales es requerida'
	                        }
	                    }
	                },
	                txtCreditoPrestar: {
	                    validators: {
	                        notEmpty: {
	                            message: 'Credito a prestar es requerida'
	                        }
	                    }
	                }
	            }
	        });
			
			/*OBTENER FECHA ACTUAL */
	        var fecha= new Date();
	        var vDia; 
	        var vMes;
	        if ((fecha.getMonth()+1) < 10) { 
	            vMes = "0" + (fecha.getMonth()+1); 
	        }
	        else { 
	            vMes = (fecha.getMonth()+1); 
	        }
	        if (fecha.getDate() < 10) { 
	            vDia = "0" + fecha.getDate();
	        }
	        else{ 
	            vDia = fecha.getDate(); 
	        }	        
	        //document.getElementById("txtFechaContrato").value = vDia +"-"+  vMes + "-" + fecha.getFullYear();	        
		});

		/******************************** BUSCA PERSONAS *****************************************/
	    function fnBuscarPersonas() {
	    	//fnImprimirContrato(1706);
	    	$('.clsContratos').hide();
	    	//console.log("valor",valor);
	    	//var parametros = {'txtBuscarPersona':$("#txtBuscarPersona").val()};
	    	var parametros = {
	    		'txtBuscarIdentifiacion':$("#txtBuscarIdentifiacion").val(),
	    		'txtBuscarNombres':$("#txtBuscarNombres").val(),
	    		'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
	    		'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
	    		'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
	    	};
	    	var route ="BuscarClientes";//URL PARA BUSCAR CLIENTES
            $.ajax({
				type: 'GET',
				url: route,
				data:parametros,
				async: false,
				contentType: "application/x-www-form-urlencoded;charset=UTF-8; charset=iso-8859-1;application/json;",
				//dataType: 'json',
				success: function (data) {
					console.log("data",data);
					$('.clsClientes').show();
					$('.clsClientes').html(data);  
				},
				beforeSend:function(){
                    $('.loader').addClass('loader-default  is-active');

                },
                complete:function(){
                    $('.loader').removeClass('loader-default  is-active');
                },error:function(error){ 
					swal({
			            title: "PERSONAS...",
			            text: "La busqueda no se puede cargar",
			            confirmButtonColor: "#EF5350",
			            confirmButtonText: 'Aceptar',
			            type: "error"
			        });	 
				} 
			 });	        
	    }

	    /******************************** LISTADO DE PERSONAS *****************************************/
	    function fnListadoPersonas(url) {
	    	var parametros = {
	    		'txtBuscarIdentifiacion':$("#txtBuscarIdentifiacion").val(),
	    		'txtBuscarNombres':$("#txtBuscarNombres").val(),
	    		'txtBuscarPaterno':$("#txtBuscarPaterno").val(),
	    		'txtBuscarMaterno':$("#txtBuscarMaterno").val(),
	    		'txtBuscarFechaNacimiento':$("#txtBuscarFechaNacimiento").val(),
	    	};
	    	console.log("funcionnnn",url);
	    	var route =url;	
	    	$.ajax({
				type: 'GET',
				url: route,
				data:parametros,
				success: function (data) {
					//console.log("data",data);
					$('.clsClientes').html(data);  
				},error:function(error){ 
					Swal.fire({
			            title: "PERSONA...",
			            text: "Personas no se puede cargar",
			            confirmButtonColor: "#EF5350",
			            confirmButtonText: 'Aceptar',
			            type: "error"
			        });	 
				}
			 });
	    }

	    /*/******************************** LISTADO DE CONTRATOS *****************************************/
	    function fnBuscarContratos(id) {
	    	//var parametros = {'ddlPersona':$("#ddlPersona").val()};
	    	$('.clsClientes').hide();
	    	var parametros = {'idPersona':id};
	    	var route ="BuscarContratos";
	    	$.ajax({
                url:route,
                data:parametros,
                method:'GET',
                //dataType:'json',
                success:function(data){
                	if (data == "") {
		    			console.log("datosss vacios");
		    			$('.clsContratos').empty();
		    			$('.clsMensajeAlerta').html('<span class="text-semibold">No existe registro de vacunacion del paciente!</span>');
		    			$('.clsMensajeAlerta').show();
		    		}else{
		    			$('.clsContratos').html(data);  
		    			$('.clsContratos').show();
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
			            title: "PERSONA...",
			            text: "Los busqueda no se puede cargar",
			            confirmButtonColor: "#EF5350",
			            confirmButtonText: 'Aceptar',
			            type: "error"
			        });	 
				}                
            });	   
	    	       
	    }
	    
	    /*/******************************** LISTADO DETALE DE CONTRATOS *****************************************/
	    function fnDetalleContratos(id) {	    	
	    	var parametros = {'idContrato':id};
	    	var route ="/BuscarContratosDetalle";
	    	$.ajax({
                url:route,
                data:parametros,
                method:'GET',
                //dataType:'json',
                success:function(data){
                	if (data == "") {
		    			console.log("datosss vacios");
		    			$('.clsContratos').empty();
		    			$('.clsMensajeAlerta').html('<span class="text-semibold">No existe registro de vacunacion del paciente!</span>');
		    			$('.clsMensajeAlerta').show();
		    		}else{
		    			$('.clsContratoDetalle').html(data);  
		    			$('.clsContratos').show();
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
			            title: "PERSONA VACUNADA...",
			            text: "Los busqueda no se puede cargar",
			            confirmButtonColor: "#EF5350",
			            confirmButtonText: 'Aceptar',
			            type: "error"
			        });	 
				}                
            });    
	    }

	    /******************************** FUNCION VOLVER A LA BUSQUEDA *****************************************/
	    function fnVolver() {
	    	$('.clsClientes').show();
	    	$('.clsContratos').hide();
	    }
	    
	    /******************************** FUNCION DATOS CLIENTE *****************************************/
	    function fnDatosCliente() {

			$.ajax({
				type: "GET",
				url: "/ObtieneCategoria",
				data: {
					id_cliente : $('#txtIdClienteOculto').val()
				},
				dataType: "json",
				success: function (response) {
					$('#txtInteres').val(response.porcentaje)
				}
			});

	    	var nombreCliente = $("#txtNombreClienteOculto").val();
	    	$("#txtCliente").val(nombreCliente);
	    	var codigoCliente = $("#txtCodigoClienteOculto").val();
	    	$("#txtCodigoCliente").val(codigoCliente);
	    }

	    /******************************** FUNCION AGREGAR DINAMICAMENTE DETALLE DEL CONTRAT0 **********************************/
	    function agregar_fila(){
	        console.log("indexItemsC877",indexItemsC);
	        if (indexItemsC == 0) {
	            var itemColumn = $('<tr id="items_' + indexItemsC + '" index="' + indexItemsC + '" class="items_colums" >'
	            	+'<td><input type="input" class="form-control cantidad form-control-sm m-input  m-input--solid " id="txtCantidad_'+indexItemsC+'" name="catidad"></td>'
	                +'<td><textarea class="form-control form-control-sm m-input  m-input--solid" id="txtDescripcion_'+indexItemsC+'" ></textarea></td>'
	                +'<td><input type="input" class="form-control pesoBruto form-control-sm m-input  m-input--solid " id="txtPesoBruto_'+indexItemsC+'" name="pesoBruto"></td>'
	                +'<td><input type="input" class="form-control 10klts form-control-sm m-input  m-input--solid " id="txt10klts_'+indexItemsC+'" name="10klts"></td>'
	                +'<td><input type="input" class="form-control 14klts form-control-sm m-input  m-input--solid " id="txt14klts_'+indexItemsC+'" name="14klts"></td>'
	                +'<td><input type="input" class="form-control 18klts form-control-sm m-input  m-input--solid " id="txt18klts_'+indexItemsC+'" name="18klts"></td>'
	                +'<td><input type="input" class="form-control 24klts form-control-sm m-input  m-input--solid " id="txt24klts_'+indexItemsC+'" name="24klts"></td>'
	                +'<td class="opciones" style="text-align:center;"><a href="javascript:void(0)" class="eliminarCuenta btn btn-danger m-btn m-btn--icon btn-sm m-btn--icon-only  m-btn--pill m-btn--air" ><i class="fa fa-fw fa-trash"></i></a><a class="btn btn-primary m-btn m-btn--icon btn-sm camara" style="margin-top:2px;"><i class="fa fa-camera"></i></a><input type="hidden" id="foto_'+indexItemsC+'"/><img src="" style="display:none;" id="imagen_tomada'+indexItemsC+'"></td></tr>');
	            $('#detalle_contratos').append(itemColumn);	   
	            indexItemsC++;
			}
	        else{
	        	var itemColumn = $('<tr id="items_' + indexItemsC + '" index="' + indexItemsC + '" class="items_colums" >'
	            	+'<td><input type="input" class="form-control cantidad form-control-sm m-input  m-input--solid " id="txtCantidad_'+indexItemsC+'" name="catidad"></td>'
	                +'<td><textarea class="form-control form-control-sm m-input  m-input--solid" id="txtDescripcion_'+indexItemsC+'" ></textarea></td>'
	                +'<td><input type="input" class="form-control pesoBruto form-control-sm m-input  m-input--solid " id="txtPesoBruto_'+indexItemsC+'" name="pesoBruto"></td>'
	                +'<td><input type="input" class="form-control 10klts form-control-sm m-input  m-input--solid " id="txt10klts_'+indexItemsC+'" name="10klts"></td>'
	                +'<td><input type="input" class="form-control 14klts form-control-sm m-input  m-input--solid " id="txt14klts_'+indexItemsC+'" name="14klts"></td>'
	                +'<td><input type="input" class="form-control 18klts form-control-sm m-input  m-input--solid " id="txt18klts_'+indexItemsC+'" name="18klts"></td>'
	                +'<td><input type="input" class="form-control 24klts form-control-sm m-input  m-input--solid " id="txt24klts_'+indexItemsC+'" name="24klts"></td>'
	                +'<td class="opciones" style="text-align:center;"><a href="javascript:void(0)" class="eliminarCuenta btn btn-danger m-btn m-btn--icon btn-sm m-btn--icon-only  m-btn--pill m-btn--air" ><i class="fa fa-fw fa-trash"></i></a><a class="btn btn-primary m-btn m-btn--icon btn-sm camara" style="margin-top:2px;"><i class="fa fa-camera"></i></a><input type="hidden" id="foto_'+indexItemsC+'"/><img src="" style="display:none;" id="imagen_tomada'+indexItemsC+'"></td></tr>');
	            $('#detalle_contratos').append(itemColumn);	   
	            indexItemsC++;
			}			
	    }

	    /*FUNCION ACTUALIZAR EL TOTAL PESO BRUTO*/
		function fnActualizarTotalPesoBruto() {
	        var totalPesoBruto = 0;
	        $('.pesoBruto').each(function () {
	            //console.log($(this).val())
	            totalPesoBruto += +$(this).val();
	        });
	        //console.log(total);
	        //$('#total_cost').html( Number( Math.ceil(total) ).toFixed(2) );
	        $('#total_peso_bruto').html(Number(totalPesoBruto).toFixed(2));
	        $("#txtPesoBruto").val(Number(totalPesoBruto).toFixed(2));
	    }

		/*CALCULA LA SUMA DEL PESO BRUTO*/
		$(document).on('blur', '.pesoBruto', function(){        
	        var index = $(this).parent().parent().attr('index');
	        console.log(index);
	        //$(this).parent('td').html( lastSpan + $(this).val() +"</span>" );
	        fnActualizarTotalPesoBruto();
	    });

	    /*FUNCION ACTUALIZAR EL TOTAL DE CANTIDAD*/
		function fnActualizarTotalCantidad() {
	        var totalCantidad = 0;
	        $('.cantidad').each(function () {
	            totalCantidad += +$(this).val();
	        });
	        $('#total_cantidad').html(Number(totalCantidad).toFixed(2));
	    }

		/*CALCULA LA SUMA DE CANTIDAD*/
		$(document).on('blur', '.cantidad', function(){        
	        var index = $(this).parent().parent().attr('index');
	        console.log(index);
	        //$(this).parent('td').html( lastSpan + $(this).val() +"</span>" );
	        fnActualizarTotalCantidad();
	    });

	    /*CALCULA LA SUMA DE 10 KLTS*/
		$(document).on('blur', '.10klts', function(){        
	        var index = $(this).parent().parent().attr('index');
	        fnActualizarTotal10klts();
	    });

	    /*FUNCION ACTUALIZAR EL TOTAL DE 10 klts*/
		async function fnActualizarTotal10klts() {
	        var total10klts = 0;
	        $('.10klts').each(function () {
	            total10klts += +$(this).val();
	        });
	        $('#total_10_klts').html(Number(total10klts).toFixed(2));
	        maxPrecio10Klts = total10klts * precio10klts;
	        
	        maxPrecioKlts = parseFloat(maxPrecio10Klts) + parseFloat(maxPrecio14Klts) + parseFloat(maxPrecio18Klts) + parseFloat(maxPrecio24Klts);
	        console.log("maxPrecio10Klts",maxPrecio10Klts);
	        console.log("total10klts",total10klts);
	        console.log("precio10klts",precio10klts);
	        var totalKilates = 0;
	        totalTasacion10klts = (total10klts *25)+maxPrecio10Klts;
	        //console.log("10klts",$('#total_10_klts').html());
	        //console.log("14klts",$('#total_14_klts').html());
	        totalKilates = parseFloat($('#total_10_klts').html()) + parseFloat($('#total_14_klts').html()) + parseFloat($('#total_18_klts').html()) + parseFloat($('#total_24_klts').html());	        
	        $('#txtPesoNeto').val(Number(totalKilates).toFixed(2));
	        $('#txtCreditoMax').val(Number(maxPrecioKlts).toFixed(2));
	        $('#txtCreditoPrestar').val(Number(maxPrecioKlts).toFixed(2));

			// generar monto maximo de credito
			await generarMontoCreditoMax(maxPrecioKlts);

	        fnRevalidarFormulario();
	    }

	    /*CALCULA LA SUMA DE 14 KLTS*/
		$(document).on('blur', '.14klts', function(){        
	        var index = $(this).parent().parent().attr('index');
	        fnActualizarTotal14klts();
	    });

	    /*FUNCION ACTUALIZAR EL TOTAL DE 14 klts*/
		async function fnActualizarTotal14klts() {
	        var total14klts = 0;
	        $('.14klts').each(function () {
	            total14klts += +$(this).val();
	        });
	        $('#total_14_klts').html(Number(total14klts).toFixed(2));
	        maxPrecio14Klts = total14klts * precio14klts;
	        maxPrecioKlts = parseFloat(maxPrecio10Klts) + parseFloat(maxPrecio14Klts) + parseFloat(maxPrecio18Klts) + parseFloat(maxPrecio24Klts);
	        totalTasacion14klts = (total14klts *25)+maxPrecio14Klts;
	        var totalKilates = 0;
	        totalKilates = parseFloat($('#total_10_klts').html()) + parseFloat($('#total_14_klts').html()) + parseFloat($('#total_18_klts').html()) + parseFloat($('#total_24_klts').html());	        
	        $('#txtPesoNeto').val(Number(totalKilates).toFixed(2));
	        $('#txtCreditoMax').val(Number(maxPrecioKlts).toFixed(2));
	        $('#txtCreditoPrestar').val(Number(maxPrecioKlts).toFixed(2));

			// generar monto maximo de credito
			await generarMontoCreditoMax(maxPrecioKlts);

	        fnRevalidarFormulario();
	    }

	    /*CALCULA LA SUMA DE 18 KLTS*/
		$(document).on('blur', '.18klts', function(){        
	        var index = $(this).parent().parent().attr('index');
	        fnActualizarTotal18klts();
	    });

	    /*FUNCION ACTUALIZAR EL TOTAL DE 18 klts*/
		async function fnActualizarTotal18klts() {
	        var total18klts = 0;

	        $('.18klts').each(function () {
	            total18klts += +$(this).val();
	        });
	        $('#total_18_klts').html(Number(total18klts).toFixed(2));
	        maxPrecio18Klts = total18klts * precio18klts;
	        maxPrecioKlts = parseFloat(maxPrecio10Klts) + parseFloat(maxPrecio14Klts) + parseFloat(maxPrecio18Klts) + parseFloat(maxPrecio24Klts);
	        totalTasacion18klts = (total18klts *25)+maxPrecio18Klts;
	        var totalKilates = 0;
	        totalKilates = parseFloat($('#total_10_klts').html()) + parseFloat($('#total_14_klts').html()) + parseFloat($('#total_18_klts').html()) + parseFloat($('#total_24_klts').html());	        
	        $('#txtPesoNeto').val(Number(totalKilates).toFixed(2));
	        $('#txtCreditoMax').val(Number(maxPrecioKlts).toFixed(2));
	        $('#txtCreditoPrestar').val(Number(maxPrecioKlts).toFixed(2));

			// generar monto maximo de credito
			await generarMontoCreditoMax(maxPrecioKlts);
			
	        fnRevalidarFormulario();
	    }

	    /*CALCULA LA SUMA DE 24 KLTS*/
		$(document).on('blur', '.24klts', function(){        
	        var index = $(this).parent().parent().attr('index');
	        fnActualizarTotal24klts();
	    });

	    /*FUNCION ACTUALIZAR EL TOTAL DE 18 klts*/
		async function fnActualizarTotal24klts() {
	        var total24klts = 0;
	        $('.24klts').each(function () {
	            total24klts += +$(this).val();
	        });
	        $('#total_24_klts').html(Number(total24klts).toFixed(2));
	        maxPrecio24Klts = total24klts * precio24klts;
	        maxPrecioKlts = parseFloat(maxPrecio10Klts) + parseFloat(maxPrecio14Klts) + parseFloat(maxPrecio18Klts) + parseFloat(maxPrecio24Klts);
	        totalTasacion24klts = (total24klts *25)+maxPrecio24Klts;
	        var totalKilates = 0;
	        totalKilates = parseFloat($('#total_10_klts').html()) + parseFloat($('#total_14_klts').html()) + parseFloat($('#total_18_klts').html()) + parseFloat($('#total_24_klts').html());	        
	        $('#txtPesoNeto').val(Number(totalKilates).toFixed(2));
	        $('#txtCreditoMax').val(Number(maxPrecioKlts).toFixed(2));
	        $('#txtCreditoPrestar').val(Number(maxPrecioKlts).toFixed(2));

			
			// generar monto maximo de credito
			await generarMontoCreditoMax(maxPrecioKlts);
			
	        fnRevalidarFormulario();
	    }


	    /*/******************************** FUNCION OBTIENE PRECIO ORO *****************************************/
	    function fnObtienePrecioOro() {
	    	var fecha= new Date();
	        var vDia; 
	        var vMes;
	        if ((fecha.getMonth()+1) < 10) { 
	            vMes = "0" + (fecha.getMonth()+1); 
	        }
	        else { 
	            vMes = (fecha.getMonth()+1); 
	        }
	        if (fecha.getDate() < 10) { 
	            vDia = "0" + fecha.getDate();
	        }
	        else{ 
	            vDia = fecha.getDate(); 
	        }	        
	        //var fechaActual = vDia +"-"+  vMes + "-" + fecha.getFullYear();
	        var fechaActual = fecha.getFullYear() +"-"+  vMes + "-" + vDia;
	    	var parametros = {'fechaActual':fechaActual};
	    	var route ="ObtnerPrecioOro";
	    	$.ajax({
                url:route,
                data:parametros,
                method:'GET',
                //dataType:'json',
                success:function(data){
                	console.log("PRECIO ORO",data.Resultado);                	
                	if (data != "") {
                		var valor = data.Resultado;
                		console.log("valorr",valor.dies); 
                		precio10klts = valor.dies;
                		precio14klts = valor.catorce;
                		precio18klts = valor.diesiocho;
                		precio24klts = valor.veinticuatro;
						
						// CONVERTIR
						if(moneda_actual == 'sus')
						{
							// convertir a Sus
							precio10klts = (parseFloat(valor.dies) / parseFloat(valor_bs)).toFixed(2);
							precio14klts = (parseFloat(valor.catorce) / parseFloat(valor_bs)).toFixed(2);
							precio18klts = (parseFloat(valor.diesiocho) / parseFloat(valor_bs)).toFixed(2);
							precio24klts = (parseFloat(valor.veinticuatro) / parseFloat(valor_bs)).toFixed(2);
							moneda_actual = 'sus';
						}
						else{
							precio10klts = valor.dies;
							precio14klts = valor.catorce;
							precio18klts = valor.diesiocho;
							precio24klts = valor.veinticuatro;
							moneda_actual = 'bs';
						}
		    		}else{
		    			
		    		} 
                }                               
            });    
	    }

	    /******************************** REGISTRA CONTRATOS Y DETALLES *****************************************/
	    $("#btnRegistrar").click(async function(){
	        
	     	var validarContrato = $("#frmContrato").data('bootstrapValidator'); 
	     	//console.log(validarContrato);	     	
	        validarContrato.validate();
	        if(validarContrato.isValid())
	        {
	        	//$(this).attr('disabled');
	        	var valorPrestar = $("#txtCreditoPrestar").val();

				// CALCULAR MONTO DE COMISION
				var nuevoComision = await calcularMontoComisionGarantia(parseFloat(valorPrestar), $('#txtMoneda').val())
				var nuevoInteres = (valorPrestar * parseFloat('#txtInteres').val())/100;

	        	console.log("totalTasacion10klts",totalTasacion10klts);
	        	console.log("totalTasacion14klts",totalTasacion14klts);
	        	console.log("totalTasacion18klts",totalTasacion18klts);
	        	console.log("totalTasacion24klts",totalTasacion24klts);
	        	totalTasacionGeneral = totalTasacion10klts + totalTasacion14klts + totalTasacion18klts + totalTasacion24klts;
	        	console.log("totalTasacionGeneral",totalTasacionGeneral);
	        	var cantidad = [];
	            var descripción = [];
	            var pesoBruto = [];
	            var diezKlts = [];
	            var catorceKlts = [];
	            var dieciochoKlts = []; 
	            var veintiCuatroKlts = []; 
	        	$('.items_colums').each(function () {
	                var index = $(this).attr('index');	                             
	                cantidad.push($('#txtCantidad_' + index).val());
	                descripción.push($('#txtDescripcion_' + index).val());
	                pesoBruto.push($('#txtPesoBruto_' + index).val());
	                diezKlts.push($('#txt10klts_' + index).val());
	                catorceKlts.push($('#txt14klts_' + index).val());
	                dieciochoKlts.push($('#txt18klts_' + index).val());
	                veintiCuatroKlts.push($('#txt24klts_' + index).val());
	            });
	            var route="/Contrato";
	        	$.ajax({
		            url: route,
		            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		            type: 'POST',
		            data: {
		            	'txtFechaContrato'	:$("#txtFechaContrato").val(),
		            	'txtCodigoCredito'	:$("#txtCodigoCredito").val(),
		            	'txtMoneda'			:$("#txtMoneda").val(),
		            	'txtGarantia'		:$("#txtGarantia").val(),
		            	'txtFondo'			:$("#txtFondo").val(),
		            	'txtPesoBruto'		:$("#txtPesoBruto").val(),
		            	'txtTipoContrato'	:$("#txtTipoContrato").val(),
		            	'txtPesoNeto'		:$("#txtPesoNeto").val(),
		            	'txtCreditoMax'		:$("#txtCreditoMax").val(),
		            	'txtCreditoPrestar'	:$("#txtCreditoPrestar").val(),
		            	'txtMontoCredito'	:$("#txtMontoCredito").val(),
		            	'txtTipoPP'			:$("#txtTipoPP").val(),
		            	'txtCapitaBs'		:$("#txtCapitaBs").val(),
		            	'txtInteresConv'	:$("#txtInteresConv").val(),
		            	'txtTipoInteres'	:$("#txtTipoInteres").val(),
		            	'txtIntereses'		:$("#txtIntereses").val(),
		            	'txtNroCuotas'		:$("#txtNroCuotas").val(),
		            	'txtGastosAdm'		:$("#txtGastosAdm").val(),
		            	'txtFormaPago'		:$("#txtFormaPago").val(),
		            	'txtTotales'		:$("#txtTotales").val(),
		            	'interes'			:nuevoInteres,
		            	'comision'			:nuevoComision,
		            	'txtIdClienteOculto':$("#txtIdClienteOculto").val(),
		            	'cantidad'			:cantidad,
		            	'descripción'		:descripción,
		            	'pesoBruto'			:pesoBruto,
		            	'diezKlts'			:diezKlts,
		            	'catorceKlts'		:catorceKlts,
		            	'dieciochoKlts'		:dieciochoKlts,
		            	'veintiCuatroKlts'	:veintiCuatroKlts,
						'p_interes'		: $('txtInteres').val(),
		            	'totalTasacionGeneral': totalTasacionGeneral,
		            },
	                success: function(data){
	                	console.log(data.Mensaje);
	                	resultado = data.Mensaje;

	                	/*resultado = 1  USUARIO GUARDADO*/
	                	if (data.Mensaje) {
                        	$("#modalCreateContrato").modal('toggle');
                            Swal.fire({
					            title: "CONTRATO!",
					            //text: "Se registro correctamente!!",
					            html: 'Se genero el codigo  <b>'+  data.Mensaje +'</b> y se registro correctamente los datos',
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnBuscarContratos($("#txtIdPersonaOculto").val());
					        fnLimpiarControles();
					        //fnImprimirContratoDesembolso(data.idContrato)
					        fnImprimirContrato(data.idContrato);
					        
					        // fnListadoUsuarios('/Usuario');
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
	    });

		/******************************* LIMPIAR CONTROLES *******************************************************/
		function fnLimpiarControles(){
			$("#txtCliente").val('');
			$("#txtCodigoCliente").val('');
			$("#txtGarantia").val('');
			$("#txtPesoBruto").val('');
			$("#txtPesoNeto").val('');
			$("#txtCreditoMax").val('');
			$("#detalle_contratos").empty();
			$('#total_cantidad').html('');
			$('#total_peso_bruto').html('');
			$('#total_10_klts').html('');
			$('#total_14_klts').html('');
			$('#total_18_klts').html('');
			$('#total_24_klts').html('');
			fnRevalidarFormulario();
		}

		function fnRevalidarFormulario(){
			$('#frmContrato').bootstrapValidator('revalidateField', 'txtGarantia');
			$('#frmContrato').bootstrapValidator('revalidateField', 'txtPesoBruto');
			$('#frmContrato').bootstrapValidator('revalidateField', 'txtPesoNeto');
			$('#frmContrato').bootstrapValidator('revalidateField', 'txtCreditoMax');
		}

		/******************************* CALCULA EL VALOR A PRESTAR *******************************************************/
	    async function fnCreditoPrestar(valor){
			console.log("valor",valor);

			valorMaximo = $("#txtCreditoMax").val();
			console.log("valorMaximo",valorMaximo);
			if (parseFloat(valor) <= parseFloat(valorMaximo)) {

				// CALCULAR MONTO DE GARANTIA
				var valorGarantia = await calcularMontoComisionGarantia(parseFloat(valor), $('#txtMoneda').val())
				$('#txtGarantia').val(Number(valorGarantia).toFixed(2));

			}
			else{
				$("#txtCreditoPrestar").val('');
				$('#frmContrato').bootstrapValidator('revalidateField', 'txtCreditoPrestar');
				Swal.fire({
		            title: "Contrato...",		            
		            //html: 'El Contrato  <b>'+  data.Mensaje +'</b> y se registro correctamente los datos',//text: "La sesión 
		            html: 'Su Prestamo no puede mayor a <b>'+  valorMaximo +'</b>',//text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
		            confirmButtonColor: "#EF5350",
		            confirmButtonText: 'Aceptar',
		            type: "error"
		        });
			}
		}

		/*FUNCION IMPRIME RENDICION DE CUENTAS*/
	    function fnImprimirContrato(id){  
			console.log("id:::",id);
			$('#txtIdContrato').val(id);
			$("#reporteModalContrato").modal();
        	var src = "/ImprimirReporteContrato/"+id;
        	console.log(src);
        	console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialog").html(object);            
            $("#dialog").show();     
	    }

	    /*DESEMBOLSO*/
	    function fnImprimirContratoDesembolso(id){  
			console.log("id:::",id);
			$("#reporteModalDesembolso").modal();
        	var src = "/ImprimirDesembolso/"+id;
        	console.log(src);
        	console.log("entrooo")
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogD").html(object);            
            $("#dialogD").show();     
	    }

		 /*COMPROBANTE*/
		function fnImprimirContratoComprobante(id){  
			$('#txtIdContrato').val(id);
			$("#reporteModalComprobante").modal();
        	var src = "/ImprimirComprobante/"+id;
        	console.log(src);
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogC").html(object);            
            $("#dialogC").show();     
	    }

		/* COMPROBANTE 2 */
		function fnImprimirContratoComprobante2(id){  
			$("#reporteModalComprobante2").modal();
        	var src = "/ImprimirComprobante2/"+id;
        	console.log(src);
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogC2").html(object);            
            $("#dialogC2").show();     
	    }

		/* BOLETA */
		function fnImprimirBoleta(id){
			$("#reporteModalBoleta").modal();
        	var src = "/ImprimirBoleta/"+id;
        	console.log(src);
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogBoleta").html(object);            
            $("#dialogBoleta").show();  
		}

		/* CAMBIO MONEDA*/
		function fnImprimirCambioContrato(id){
			$("#reporteModalContratoCambio").modal();
        	var src = "/imprimirContratoCambioMoneda/"+id;
        	console.log(src);
            var object = "<object data=\"{src}\" type=\"application/pdf\" width=\"850px\" height=\"600px\">";            
            object += "</object>";
            object = object.replace(/{src}/g, src);
            $("#dialogCCambio").html(object);            
            $("#dialogCCambio").show();  
		}

	    function fnCerrarVentana(){  
			var id = $('#txtIdContrato').val();
			console.log("idddd",id);
			// fnImprimirContratoDesembolso(id);     
			fnImprimirContratoComprobante(id);
	    };

		function fnCerrarVentanaDesembolso()
		{
			var id = $('#txtIdContrato').val();
			fnImprimirContratoComprobante(id);   
		}

		function fnCerrarVentanaComprobante()
		{
			var id = $('#txtIdContrato').val();
			fnImprimirContratoComprobante2(id);   
		}

		function fnCerrarVentanaComprobante2(){
			var id = $('#txtIdContrato').val();
			// fnImprimirBoleta(id);   
			fnImprimirCambioContrato(id);   
		}

		function fnCerrarVentanaBoleta()
		{
			var id = $('#txtIdContrato').val();
			fnImprimirCambioContrato(id);   
		}

	    async function fnRegistrarContrato(id){
	    	
	    	/******************************** REGISTRA CONTRATOS Y DETALLES *****************************************/
	        var route="/Contrato";
	     	var validarContrato = $("#frmContrato").data('bootstrapValidator'); 
	     	console.log(validarContrato);
	     	var valorPrestar = $("#txtCreditoPrestar").val();

			// CALCULAR MONTO DE COMISION
			var nuevoComision = await calcularMontoComisionGarantia(parseFloat(valorPrestar), $('#txtMoneda').val())
			var nuevoInteres = ((valorPrestar * parseFloat($('#txtInteres').val()))/100).toFixed(2);

			let moneda_id = $('#txtMoneda').val();

	        validarContrato.validate();
	        if(validarContrato.isValid())
	        {
	        	// var ElementoRemover= document.getElementById(id);
	        	// ElementoRemover.removeAttributeNode;
	        	// padre = ElementoRemover.parentNode;
				// padre.removeChild(ElementoRemover);
            	console.log("El formulario ya se esta enviando...");
		        //return false;
		        //document.getElementById(id).disabled=true;
	        	console.log("totalTasacion10klts",totalTasacion10klts);
	        	console.log("totalTasacion14klts",totalTasacion14klts);
	        	console.log("totalTasacion18klts",totalTasacion18klts);
	        	console.log("totalTasacion24klts",totalTasacion24klts);
	        	totalTasacionGeneral = totalTasacion10klts + totalTasacion14klts + totalTasacion18klts + totalTasacion24klts;
	        	console.log("totalTasacionGeneral",totalTasacionGeneral);
	        	var cantidad = [];
	            var descripción = [];
	            var pesoBruto = [];
	            var diezKlts = [];
	            var catorceKlts = [];
	            var dieciochoKlts = []; 
	            var veintiCuatroKlts = []; 
	            var fotos = [];
	        	$('.items_colums').each(function () {
	                var index = $(this).attr('index');	                             
	                cantidad.push($('#txtCantidad_' + index).val());
	                descripción.push($('#txtDescripcion_' + index).val());
	                pesoBruto.push($('#txtPesoBruto_' + index).val());
	                diezKlts.push($('#txt10klts_' + index).val());
	                catorceKlts.push($('#txt14klts_' + index).val());
	                dieciochoKlts.push($('#txt18klts_' + index).val());
	                veintiCuatroKlts.push($('#txt24klts_' + index).val());
					fotos.push($('#foto_'+index).val());
	            });
	        	$.ajax({
		            url: route,
		            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		            type: 'POST',
		            data: {
		            	'txtFechaContrato'	:$("#txtFechaContrato").val(),
		            	'txtCodigoCredito'	:$("#txtCodigoCredito").val(),
		            	'txtMoneda'			:moneda_id,
		            	'txtGarantia'		:$("#txtGarantia").val(),
		            	'txtFondo'			:$("#txtFondo").val(),
		            	'txtPesoBruto'		:$("#txtPesoBruto").val(),
		            	'txtTipoContrato'	:$("#txtTipoContrato").val(),
		            	'txtPesoNeto'		:$("#txtPesoNeto").val(),
		            	'txtCreditoMax'		:$("#txtCreditoMax").val(),
		            	'txtCreditoPrestar'	:$("#txtCreditoPrestar").val(),
		            	'txtMontoCredito'	:$("#txtMontoCredito").val(),
		            	'txtTipoPP'			:$("#txtTipoPP").val(),
		            	'txtCapitaBs'		:$("#txtCapitaBs").val(),
		            	'txtInteresConv'	:$("#txtInteresConv").val(),
		            	'txtTipoInteres'	:$("#txtTipoInteres").val(),
		            	'txtIntereses'		:$("#txtIntereses").val(),
		            	'txtNroCuotas'		:$("#txtNroCuotas").val(),
		            	'txtGastosAdm'		:$("#txtGastosAdm").val(),
		            	'txtFormaPago'		:$("#txtFormaPago").val(),
		            	'txtTotales'		:$("#txtTotales").val(),
		            	'interes'			:nuevoInteres,
		            	'comision'			:nuevoComision,
		            	'txtIdClienteOculto':$("#txtIdClienteOculto").val(),
		            	'cantidad'			:cantidad,
		            	'descripción'		:descripción,
		            	'pesoBruto'			:pesoBruto,
		            	'diezKlts'			:diezKlts,
		            	'catorceKlts'		:catorceKlts,
		            	'dieciochoKlts'		:dieciochoKlts,
		            	'veintiCuatroKlts'	:veintiCuatroKlts,
		            	'totalTasacionGeneral': totalTasacionGeneral,
						'p_interes'			:$('#txtInteres').val(),
						'fotos' : fotos
		            },
	                success: function(data){
	                	console.log(data.Mensaje);
	                	resultado = data.Mensaje;

	                	/*resultado = 1  USUARIO GUARDADO*/
	                	if (data.Mensaje) {
                        	$("#modalCreateContrato").modal('toggle');
                            Swal.fire({
					            title: "CONTRATO!",
					            //text: "Se registro correctamente!!",
					            html: 'Se genero el codigo  <b>'+  data.Mensaje +'</b> y se registro correctamente los datos',
					            confirmButtonText: 'Aceptar',
					            confirmButtonColor: "#66BB6A",
					            type: "success"
					        });
					        fnBuscarContratos($("#txtIdPersonaOculto").val());
					        fnLimpiarControles();
					        //fnImprimirContratoDesembolso(data.idContrato)
					        // fnImprimirContrato(data.idContrato);
					        fnImprimirContratoComprobante(data.idContrato);
					        //document.getElementById(id).disabled=false;
					        // fnListadoUsuarios('/Usuario');
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
	    }
		/***************************************************************/
		// CONTROLAR EL CAMBIO DE MONEDA
		/***************************************************************/
		$('#txtMoneda').change(function(){
			let _id = $(this).val();
			if(_id == 1)
			{
				moneda_actual = 'bs';
			}
			else{
				moneda_actual = 'sus';
			}
			fnObtienePrecioOro();
			detectaCambio();
		});

		function detectaCambio()
		{
			$.ajax({
				type: "GET",
				url: '/Monedas/valor',
				data:{
					id : $('txtMoneda').val()
				},
				dataType: "json",
				success: function (response) {
					console.log(response);
					valor_bs = response.bs;
					valor_sus = response.sus;
					// REALIZAR LOS CALCULOS
					calcularNuevosMontos();
				}
			});
		}

		function calcularNuevosMontos()
		{
			let txtGarantia = $('#txtGarantia');
			let txtCreditoMax = $('#txtCreditoMax');
			let txtCreditoPrestar = $('#txtCreditoPrestar');

			if(txtGarantia.val() != '' && txtCreditoMax.val() != '' && txtCreditoPrestar.val() != '')
			{
				// CONVERTIR
				if(moneda_actual == 'sus')
				{
					// convertir a Sus
					txtGarantia.val((parseFloat(txtGarantia.val()) / parseFloat(valor_bs)).toFixed(2))
					txtCreditoMax.val((parseFloat(txtCreditoMax.val()) / parseFloat(valor_bs)).toFixed(2))
					txtCreditoPrestar.val((parseFloat(txtCreditoPrestar.val()) / parseFloat(valor_bs)).toFixed(2))
					moneda_actual = 'sus';
				}
				else{
					// convertir a Bs
					txtGarantia.val((parseFloat(txtGarantia.val()) * parseFloat(valor_bs)).toFixed(2))
					txtCreditoMax.val((parseFloat(txtCreditoMax.val()) * parseFloat(valor_bs)).toFixed(2))
					txtCreditoPrestar.val((parseFloat(txtCreditoPrestar.val()) * parseFloat(valor_bs)).toFixed(2))
					moneda_actual = 'bs';
				}
			}
		}

		/* obtener valores de interes + gastos administravos
		*  y calcular los montos
		*/
		async function generarMontoCreditoMax(maxPrecioKlts){
			var interesAdministrable = await obtenerMontoComision(parseFloat(maxPrecioKlts),$('#txtMoneda').val());
			var totalInteres = parseFloat($("#txtInteres").val()??0) + interesAdministrable.porcentaje;
			var valorGarantia = (maxPrecioKlts * totalInteres)/100;
			$('#txtGarantia').val(Number(valorGarantia).toFixed(2));
		}

		/*
		* Calcular el monto de comision e interes que se prestara
		*/
		async function calcularMontoComisionGarantia(monto, moneda){
			var interesAdministrable = await obtenerMontoComision(parseFloat(monto),$('#txtMoneda').val());
			var resultado = (monto * parseFloat(interesAdministrable.porcentaje))/100;
			return resultado;
		}

		// obtener valores de intereses
		function obtenerMontoComision(monto,moneda) {
			return new Promise((resolve, reject) => {
				$.ajax({
					type: "GET",
					url: '{{ route("comision.contrato")}}',
					data: { monto: monto,moneda:moneda },
					dataType: "json",
					success: function (response) {
						resolve(response.interes_administrable);
					},
					error: function (error) {
						reject(error);
					}
				});
			});
		}
</script>
@endsection



