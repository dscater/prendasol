<div id="modalPlazoPago" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="titulo_modal"></h5>
            </div>
            <form id="frmPlazoPago">
                <div class="modal-body">
                    <input type="hidden" name="txtId" id="txtId">
                    <input type="hidden" name="txtIdContrato" id="txtIdContrato">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Código Contrato:</label>
                                <input type="text" name="txtCodigoContrato" id="txtCodigoContrato"
                                    class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción:</label>
                                <textarea name="txtDescripcion" id="txtDescripcion" rows="2" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Fecha próximo pago:</label>
                                <input type="text" id="txtFechaProximoPago" name="txtFechaProximoPago"
                                    placeholder="Fecha" class="form-control" data-mask required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal"
                        onclick="fnLimpiarControles()">Cancelar</button>
                    <button class="btn btn-success" id="btnEnviarFormulario">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
