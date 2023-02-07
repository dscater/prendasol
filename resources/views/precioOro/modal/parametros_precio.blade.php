<div id="modalUpdatePrecioParametros" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Actualizar Precio</h5>
            </div>
            <form id="frmPrecioParametros">
                <div class="modal-body">
                    <input id="idP" type="hidden">
                    <div class="row">
                        <div class="col-md-6">
                            <label>10 Klts:</label>
                            {{ Form::text('dies', $valor_oro->dies, ['class' => 'form-control', 'required', 'id' => 'txtDiesP']) }}
                        </div>
                        <div class="col-md-6">
                            <label>14 Klts:</label>
                            {{ Form::text('catorce', $valor_oro->catorce, ['class' => 'form-control', 'required', 'id' => 'txtCatorceP']) }}
                        </div>
                        <div class="col-md-6">
                            <label>18 Klts:</label>
                            {{ Form::text('diesiocho', $valor_oro->diesiocho, ['class' => 'form-control', 'required', 'id' => 'txtDiesiochoP']) }}
                        </div>
                        <div class="col-md-6">
                            <label>24 Klts:</label>
                            {{ Form::text('veinticuatro', $valor_oro->veinticuatro, ['class' => 'form-control', 'required', 'id' => 'txtVeinticuatroP']) }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    {!! link_to('#', $title = 'Actualizar', $attributes = ['id' => 'btnActualizarParametros', 'class' => 'btn
                    btn-primary'], $secure = null) !!}
                </div>
            </form>
        </div>
    </div>
</div>
