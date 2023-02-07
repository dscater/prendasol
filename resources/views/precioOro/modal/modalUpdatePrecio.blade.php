<div id="modalUpdatePrecio" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Actualizar Precio</h5>
            </div>
            <form id="frmPrecio">
                <div class="modal-body">
                    <input id="id" type="hidden">
                    <div class="row">
                        <div class="col-md-6">
                            <label>10 Klts:</label>
                            {{ Form::text('dies', null, ['class' => 'form-control', 'required', 'id' => 'txtDies']) }}
                        </div>
                        <div class="col-md-6">
                            <label>14 Klts:</label>
                            {{ Form::text('catorce', null, ['class' => 'form-control', 'required', 'id' => 'txtCatorce']) }}
                        </div>
                        <div class="col-md-6">
                            <label>18 Klts:</label>
                            {{ Form::text('diesiocho', null, ['class' => 'form-control', 'required', 'id' => 'txtDiesiocho']) }}
                        </div>
                        <div class="col-md-6">
                            <label>24 Klts:</label>
                            {{ Form::text('veinticuatro', null, ['class' => 'form-control', 'required', 'id' => 'txtVeinticuatro']) }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal"
                        onclick="fnLimpiarControles();">Cancelar</button>
                    {!! link_to('#', $title = 'Actualizar', $attributes = ['id' => 'btnActualizar', 'class' => 'btn
                    btn-primary'], $secure = null) !!}
                </div>
            </form>
        </div>
    </div>
</div>
