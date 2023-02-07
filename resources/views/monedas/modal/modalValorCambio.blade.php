<div id="modalValorCambio" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Actualizar Valor Cambio</h5>
			</div>
			<form id="frmMoneda">                
				<div class="modal-body">
                    <input id="id" type="hidden">
					<div class="row">
                        <div class="col-md-6">
                            <label>Valor Dolares:</label>
                            {{Form::number('valor_sus',$cambio->valor_sus,['class'=>'form-control','required','id'=>'txtSus','step'=>'0.01'])}}
                        </div>
                        <div class="col-md-6">
                            <label>Valor Bolivianos:</label>
                            {{Form::number('valor_bs',$cambio->valor_bs,['class'=>'form-control','required','id'=>'txtBs','step'=>'0.01'])}}
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="fnLimpiarControlesCambio();">Cancelar</button>
					{!!link_to('#',$title='Actualizar', $attributes=['id'=>'btnActualizarCambio','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>