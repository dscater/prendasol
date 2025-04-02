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
                        <label>Contratos:</label>
                        <select name="tipo" id="" class="form-control select_sw">
                            <option value="">- Seleccione -</option>
                            <option value="NUEVOS" {{ $ia->tipo == 'NUEVOS' ? 'selected' : '' }}>NUEVOS</option>
                            <option value="ANTIGUOS" {{ $ia->tipo == 'ANTIGUOS' ? 'selected' : '' }}>ANTIGUOS</option>
                        </select>
                    </div>

                    {{-- <div class="col-md-12 form-group contenedor_fecha {{ $ia->tipo != 'ANTIGUOS' ? 'oculto' : '' }}">
                        <label>Fecha: <i style="font-size: 1.1rem;display:block;">Se aplicaran a todos los contratos
                                cuya fecha sea
                                menor o igual</i></label>
                        <input type="date" name="fecha" class="form-control" value="{{ $ia->fecha }}">
                    </div> --}}
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
