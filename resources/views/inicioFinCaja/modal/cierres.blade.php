<div id="modal_cierres" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable" style="width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Lista de cierres</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 contenedor_lista_cierres" id="table_data_cierres">
                        @include('inicioFinCaja.parcial.listaCierres')
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
