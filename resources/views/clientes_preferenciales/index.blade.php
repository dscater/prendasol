@extends('layout.principal')

@section('main-content')

    <div class="row">
        {{-- <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="token">
        --}}
        <div class="col-md-12">
            <section class="content-header">
                <div class="header_title">
                    <h3>
                        CLIENTES PREFERENCIALES
                    </h3>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 form-group">
            <label>Categoría: </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                <select name="categoria" id="categoria" class="form-control">
                    <option value="todos">TODOS</option>
                    @foreach ($categorias as $value)
                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2 form-group">
            <label>&nbsp;</label>
            <p>
                <button type="button" class="btn btn-warning btn-xs" onclick="fnBuscarPersonas()"><i
                        class="icon-search4 position-left"></i>Buscar</button>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section class="clsPrecios" id="contPreferenciales">
            </section>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            fnListarPreferenciales('/obtiene_preferenciales');

            $('#categoria').change(function() {
                fnListarPreferenciales('/obtiene_preferenciales');
            });

            function fnListarPreferenciales(url) {
                $('#contPreferenciales').html('Cargando...<br>Esto puede tardar unos minutos')
                var parametros = {
                    'categoria': $("#categoria").val(),
                };
                var route = url;
                $.ajax({
                    type: 'GET',
                    url: route,
                    data: parametros,
                    //dataType: 'json',
                    success: function(data) {
                        $('#contPreferenciales').html(data.html);
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "CLIENTES...",
                            text: "No se pudo cargar los registros",
                            confirmButtonColor: "#66BB6A",
                            confirmButtonText: 'Aceptar',
                            type: "error"
                        });
                    }
                });
            }
        });

        $(document).on('click', '.page-item a.page-link', function(e) {
            e.preventDefault();
            let array_page = $(this).attr('href').split('?');
            let url = $(this).attr('href');
            var parametros = {
                'categoria': $("#categoria").val(),
            };
            $('#contPreferenciales').html('Cargando...<br>Esto puede tardar unos minutos')
            $.ajax({
                type: "GET",
                url: url,
                data: parametros,
                dataType: "json",
                success: function(data) {
                    $('#contPreferenciales').html(data.html);
                }
            });
        });

    </script>
@endsection
