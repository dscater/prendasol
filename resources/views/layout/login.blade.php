<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>PrendaSol | Inicio Sesión</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('template/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('template/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('template/bower_components/Ionicons/css/ionicons.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('template/dist/css/AdminLTE.css') }}">
<!-- iCheck -->
<link rel="stylesheet" href="{{ asset('template/plugins/iCheck/square/green.css') }}">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('template/bower_components/select2/dist/css/select2.min.css') }}">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
<div class="login-logo">
<a href="../../index2.html"><b>Prenda</b>Sol</a>
</div>
<!-- /.login-logo -->
<div class="login-box-body">
	<p class="login-box-msg">Inicia sesión para ingresar al sistema</p>

	<form action="{{ url('/IniciarSesion') }}" method="post">
		{{ csrf_field() }}
		<div class="form-group has-feedback">
			<input type="text" class="form-control" name="txtUsuario" placeholder="Usuario" required>
			<span class="glyphicon glyphicon-user form-control-feedback"></span>
		</div>
		<div class="form-group has-feedback">
			<input type="password" class="form-control" name="txtPassword" placeholder="Contraseña" required>
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		</div>

        <div class="form-group has-feedback">
            <select class="form-control select2" id="ddlSucursal" name="ddlSucursal" data-placeholder="Seleccionar Sucursal" required>
                <option></option>
                @if(!empty($sucursales))
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}"> {{ $sucursal->nombre }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group has-feedback">
            <select class="form-control select2" id="ddlCaja" name="ddlCaja" data-placeholder="Seleccionar Caja" required>
                <option></option>
                <option value="1">
                    1
                </option>
                <option value="2">
                    2
                </option>                
            </select>
        </div>
		<div class="row">
			<div class="col-xs-8">
				<div class="checkbox icheck">
				</div>
			</div>
			<!-- /.col -->
			<div class="col-xs-4">
				<button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
			</div>
			<!-- /.col -->
		</div>
		@if(Session::has('mensaje'))
			<div class="box-body">
	          	<div class="alert alert-danger alert-dismissible">
	                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                <h4><i class="icon fa fa-ban"></i> Alerta!</h4>
	                {{ Session::get('mensaje') }}
	                
	      		</div>              
	        </div>
		@endif
		
	</form>
</div>


<!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ asset('template/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('template/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('template/plugins/iCheck/icheck.min.js') }}"></script>
<!-- Select2 -->
  <script src="{{ asset('template/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script>

$(function () {
$('input').iCheck({
checkboxClass: 'icheckbox_square-blue',
radioClass: 'iradio_square-blue',
increaseArea: '20%' /* optional */
});
});
</script>

<script type="text/javascript"> 	
		var red = "";
		var establecimiento = "";
		$(document).ready(function() {

			$("#ddlSucursal").select2({
				
			});	

	        


	        
	    });


	    
	</script>
</body>
</html>
