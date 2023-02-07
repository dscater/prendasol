<div id="modalSolicitud" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Solicitud de retiro de joya</h5>
			</div>
			<form id="frmSolicitud">
				<div class="modal-body">
                    <input type="hidden" id="_contrato_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Recoger en lugar*:</label>
                                <select name="_sucursal_id" id="_sucursal_id" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach($sucursales as $value)
                                    <option value="{{$value->id}}">{{$value->nombre}}</option>
                                    @endforeach
                                    <option value="RENOVACION">RENOVACIÓN</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observaciones*:</label>
                                <textarea name="_observaciones" id="_observaciones" cols="30" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="fnLimpiarControlesSolicitud();">Cancelar</button>
					{!!link_to('#',$title='Registrar', $attributes=['id'=>'btnRegistrarSolicitud','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>