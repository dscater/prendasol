<div id="modal_camara" class="modal fade" style="z-index:5000;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tomar Foto de la Prenda</h5>
			</div>			
			<div class="modal-body">
				<div class="row">            
			        <div class="col-md-12">       
						<video id="video" autoplay="autoplay" style="width:100%; max-width:100%; border:solid 1px black;"></video>
						<span class="info">Camara</span>
			        </div>
					<div class="col-md-12">
						<canvas id="canvas" style="width:100%; height:auto; border:solid 1px black; display:flex; just-content:center; align-items:center;"></canvas>
						<span class="img_actual">Imagen Tomada Recientemente</span>
					</div>
					<div class="col-md-12 text-center">
						<br>
						<button type="button" id="tomar" class="btn btn-success">Tomar Foto</button>
					</div>
			    </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link float-left" id="cancelar_camara">Cancelar</button>
				<button type="button" class="btn btn-primary" id="guardar">Guardar</button>
			</div>			
		</div>
	</div>
</div>