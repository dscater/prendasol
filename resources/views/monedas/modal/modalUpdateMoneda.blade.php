<div id="modalUpdateMoneda" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Actualizar Moneda</h5>
			</div>
			<form id="frmMoneda">                
				<div class="modal-body">
                    <input id="id" type="hidden">
					<div class="row">
                        <div class="col-md-6">
                            <label>Moneda:</label>
                            {{Form::text('moneda',null,['class'=>'form-control','required','id'=>'txtMoneda'])}}
                        </div>
                        <div class="col-md-6">
                            <label>Moneda:</label>
                            {{Form::text('desc_corta',null,['class'=>'form-control','required','id'=>'txtMonedaDesc'])}}
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="fnLimpiarControles();">Cancelar</button>
					{!!link_to('#',$title='Actualizar', $attributes=['id'=>'btnActualizar','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>