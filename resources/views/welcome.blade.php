<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="{{ asset('template/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('template/bower_components/font-awesome/css/font-awesome.min.css') }}">

        <!-- Styles -->
        <style>
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">            
                <div class="col-md-8">       
                    <video id="video" autoplay="autoplay" style="width:100%; max-width:100%; border:solid 1px black;"></video>
                    <span class="info">Camara</span>
                </div>
                <div class="col-md-8">
                    <canvas id="canvas" style="width:100%; height:auto; border:solid 1px black; display:flex; just-content:center; align-items:center;"></canvas>
                    <span class="img_actual">Imagen Tomada Recientemente</span>
                </div>
                <div class="col-md-8 text-center">
                    <br>
                    <button type="button" id="tomar" class="btn btn-success">Tomar Foto</button>
                </div>
            </div>
        </div>

        <!-- jQuery 3 -->
        <script src="{{ asset('template/bower_components/jquery/dist/jquery.min.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset('template/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
        $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{ asset('template/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

        <!-- CAMARA -->
        <script src="{{ asset('template/dist/js/camara.js') }}"></script>

        <script>
				turnOnCamera();
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
        </script>

    </body>
</html>
