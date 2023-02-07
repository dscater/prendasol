<div id="modalStoreCambio" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="titulo">Nuevo Registro</h5>
            </div>
            <form id="formCambio">
                <div class="modal-body">
                    <input id="id" type="hidden">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sucursal:</label>
                                <input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}" id="sucursal_id">
                                <input type="text" name="txtSucursal" id="txtSucursal" value="{{ $sucursal->nombre }}"
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha:</label>
                                <div class="input-group input-append date" id="datePickerFI">
                                    <input type="text" class="form-control" id="txtFecha" name="txtFecha"
                                        placeholder="Ingrese Fecha" />
                                    <span class="input-group-addon add-on"><span
                                            class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Señor(es):</label>
                                {{ Form::text('txtCliente', null, ['class' => 'form-control', 'required', 'id' => 'txtCliente']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>CI/NIT:</label>
                                {{ Form::text('txtNit', null, ['class' => 'form-control', 'required', 'id' => 'txtNit']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Monto:</label>
                                {{ Form::number('txtMonto', null, ['class' => 'form-control', 'required', 'id' => 'txtMonto', 'step' => '0.01', 'min' => '0']) }}
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <label>Modo de Cambio:</label>
                            {{ Form::select('txtModoCambio',
                                [
                                    'Dólares a Bolivianos' => 'Dólares a Bolivianos',
                                    'Bolivianos a Dólares' => 'Bolivianos a Dólares',
                                ],
                                null,['class' => 'form-control', 'required', 'id' => 'txtModoCambio']
                            ) }}
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Equivalencia:</label>
                                {{ Form::text('txtEquivalencia', '0.00', ['class' => 'form-control', 'required', 'id' => 'txtEquivalencia', 'readonly']) }}
                            </div>
                        </div>
                        {{-- <div class="col-md-12">
                            <div id="mensaje_tipo">Equivalencia obtenida por el precio de Compra</div>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"
                        onclick="fnLimpiarControles();">Cancelar</button>
                    {!! link_to('#', $title = 'Registrar', $attributes = ['id' => 'btnRegistrar', 'class' => 'btn btn-primary'], $secure = null) !!}
                </div>
            </form>
        </div>
    </div>
</div>
