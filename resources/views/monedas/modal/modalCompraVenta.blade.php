<div id="modalUpdateCompraVenta" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Actualizar Valores de Compra y Venta</h5>
            </div>
            <form id="frmMoneda">
                <div class="modal-body">
                    <input id="id" type="hidden">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Venta Dólar:</label>
                            {{ Form::number('venta_sus', $compra_venta->venta_sus, ['class' => 'form-control', 'required', 'id' => 'txtVentaSus', 'step' => '0.01']) }}
                        </div>
                        <div class="col-md-6">
                            <label>Venta Bolivianos:</label>
                            {{ Form::number('venta_bs', $compra_venta->venta_bs, ['class' => 'form-control', 'required', 'id' => 'txtVentaBs', 'step' => '0.01']) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Compra Dólar:</label>
                            {{ Form::number('compra_sus', $compra_venta->compra_sus, ['class' => 'form-control', 'required', 'id' => 'txtCompraSus', 'step' => '0.01']) }}
                        </div>
                        <div class="col-md-6">
                            <label>Compra Bolivianos:</label>
                            {{ Form::number('compra_bs', $compra_venta->compra_bs, ['class' => 'form-control', 'required', 'id' => 'txtCompraBs', 'step' => '0.01']) }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    {!! link_to('#', $title = 'Actualizar', $attributes = ['id' => 'btnActualizaVentaCompra', 'class' => 'btn btn-primary'], $secure = null) !!}
                </div>
            </form>
        </div>
    </div>
</div>
