<div id="modalDetalleContrato" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Detalle Contrato</h5>
			</div>			
			<div class="modal-body">
				<div class="row">            
			        <div class="col-md-12">            
		                <section class="clsContratoDetalle">                	
		                	@include('pagos.modals.listadoContratoDetalle')
		                </section>			            
			        </div>
			    </div>					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal" onclick="fnLimpiarControles();">Cerrar</button>
			</div>			
		</div>
	</div>
</div>