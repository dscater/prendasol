@extends('layout.principal')
@include('perfil.modals.modalCambiarContrasena')
@section('main-content')


      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
   
   
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">{{ $personas->persona->nombreCompleto() }}</h3>
            </div>
            <div class="panel-body">
              <div class="row">               
                {{-- <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="template/assets/images/placeholder.jpg" class="img-circle img-responsive"> </div>   --}}             
                
                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>Numero de Documento:</td>
                        <td>{{ $personas->persona->nrodocumento }}</td>
                      </tr>
                      <tr>
                        <td>Fecha Nacimiento:</td>
                        <td>{{ $personas->persona->fechanacimiento }}</td>
                      </tr>
                      <tr>
                        <td>Correo Electronico</td>
                        <td>{{ $personas->persona->correoelectronico }}</td>
                      </tr>
                      <tr>
                        <td>Direccción Trabajo</td>
                        <td>{{ $personas->persona->direcciontrabajo }}</td>
                      </tr>

                      <tr>
                        <td>Telefono Trabajo</td>
                        <td>{{ $personas->persona->telefonotrabajo }}</td>
                      </tr>
                     
                    </tbody>
                  </table>

                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCambiarContrasena"><i class="icon-new-tab position-left"></i>Cambiar Contraseña</button>
                </div>
              </div>
            </div>
              
            
          </div>
        </div>
      </div>
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>

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
            
            /******************** VALIDAR FROMULARIO PARA LA INSERCION ******************/
            $('#frmContrasena').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {                    
                    txtContrasena: {
                        message: 'Contraseña no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Contraseña es requerida'
                            },
                            callback: {
                                message: 'The password is not valid',
                                callback: function(value, validator, $field) {
                                    if (value === '') {
                                        return true;
                                    }

                                    // Check the password strength
                                    if (value.length < 5) {
                                        return {
                                            valid: false,
                                            message: 'Debe tener más de 5 caracteres de largo'
                                        };
                                    }

                                    // // The password doesn't contain any uppercase character
                                    // if (value === value.toLowerCase()) {
                                    //     return {
                                    //         valid: false,
                                    //         message: 'Debe contener al menos un carácter en mayúscula'
                                    //     }
                                    // }

                                    // // The password doesn't contain any uppercase character
                                    // if (value === value.toUpperCase()) {
                                    //     return {
                                    //         valid: false,
                                    //         message: 'Debe contener al menos un carácter en minúsculas'
                                    //     }
                                    // }

                                    // // The password doesn't contain any digit
                                    // if (value.search(/[0-9]/) < 0) {
                                    //     return {
                                    //         valid: false,
                                    //         message: 'Debe contener al menos un dígito'
                                    //     }
                                    // }

                                    return true;
                                }
                            }                             
                        }
                    },
                    txtContrasenaCopia: {
                        message: 'Contraseña no es valida',
                        validators: {
                            notEmpty: {
                                message: 'Contraseña es requerida'
                            },
                            callback: {
                                message: 'The password is not valid',
                                callback: function(value, validator, $field) {
                                    if (value === '') {
                                        return true;
                                    }

                                    // Check the password strength
                                    if (value.length < 5) {
                                        return {
                                            valid: false,
                                            message: 'Debe tener más de 5 caracteres de largo'
                                        };
                                    }

                                    // The password doesn't contain any uppercase character
                                    // if (value === value.toLowerCase()) {
                                    //     return {
                                    //         valid: false,
                                    //         message: 'Debe contener al menos un carácter en mayúscula'
                                    //     }
                                    // }

                                    // // The password doesn't contain any uppercase character
                                    // if (value === value.toUpperCase()) {
                                    //     return {
                                    //         valid: false,
                                    //         message: 'Debe contener al menos un carácter en minúsculas'
                                    //     }
                                    // }

                                    // // The password doesn't contain any digit
                                    // if (value.search(/[0-9]/) < 0) {
                                    //     return {
                                    //         valid: false,
                                    //         message: 'Debe contener al menos un dígito'
                                    //     }
                                    // }

                                    return true;
                                }
                            },  
                            identical: {
                                field: 'txtContrasena',
                                message: 'No coinciden las contraseñas'
                            }
                        }
                    }
                }
            });
        });

        /******************************** ACTUALIZA CAMBIO DE CONTRASEÑA ************************************/
        $("#btnCambiar").click(function(){
            console.log("sdsdsds");
            var value =0;
            var route="/Perfil/"+value+"";
            var validarContrasena = $("#frmContrasena").data('bootstrapValidator'); 
            validarContrasena.validate();
            if(validarContrasena.isValid())
            {
                $.ajax({
                    url: route,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'PUT',
                    dataType: 'json',
                    data: {
                        'txtContrasena':$("#txtContrasena").val()
                    },
                    success: function(data){                        
                        resultado = data.Mensaje;
                        /*resultado = 1  ROL GUARDADO*/
                        if (resultado == 1) {
                            $("#modalCambiarContrasena").modal('toggle');
                            Swal.fire({
                                title: "CONTRASEÑA!",
                                text: "Se actualizo correctamente!!",
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: "#66BB6A",
                                type: "success"
                            });
                            $("#txtContrasena").val('');
                            $("#txtContrasenaCopia").val('');
                        }                      
                        
                        /*resultado = 0  PROBLEMAS EN LA BASE DE DATOS*/
                        if (resultado == 0) {                                
                            swal({
                                title: "USUARIO...",
                                text: "hubo problemas al registrar en BD",
                                confirmButtonColor: "#EF5350",
                                confirmButtonText: 'Aceptar',
                                type: "error"
                            });
                        }

                        /*resultado = -1 SESION EXPIRADA*/
                        if (resultado == "-1") {                                
                            swal({
                                title: "USUARIO...",
                                text: "La sesión fue expirado cierre sesión e ingrese nuevamente al sistema",
                                confirmButtonColor: "#EF5350",
                                confirmButtonText: 'Aceptar',
                                type: "error"
                            });
                        }
                        
                         
                    },  error: function(result) {
                          console.log(result);
                         swal("Opss..!", "La Persona no se puedo actualizar intente de nuevo!", "error")
                    }
                });
            }
            
        });

        
    </script>
@endsection