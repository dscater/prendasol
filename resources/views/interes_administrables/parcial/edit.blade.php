<div class="modal" id="modalEdit{{ $ia->id }}">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('interes_administrables.update', $ia->id) }}" method="post">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Editar registro</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" value="put" name="_method">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Monto desde:</label>
                        <input type="number" name="monto1" value="{{ $ia->monto1 }}" min="0" step="0.01"
                            class="form-control">
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Monto hasta:</label>
                        <input type="number" name="monto2" value="{{ $ia->monto2 }}" min="0" step="0.01"
                            class="form-control">
                    </div>
                    <div class="col-md-12 form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Fecha inicio:</label>
                                <input type="date" name="fecha_ini" class="form-control" value="{{ $ia->fecha_ini }}">
                            </div>
                            <div class="col-md-6">
                                <label>Fecha fin:</label>
                                <input type="date" name="fecha_fin" class="form-control" value="{{ $ia->fecha_fin }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Porcentaje:</label>
                        <input type="number" name="porcentaje" value="{{ $ia->porcentaje }}" min="0"
                            step="0.01" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                <button id="btnEdit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
