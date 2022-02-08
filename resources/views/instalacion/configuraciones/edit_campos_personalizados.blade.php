@extends('layouts.admin')

@section('style')
    <style>
        .col-form-label {
            font-weight: bold;
        }
        .campos h4{
            font-size: 20px;
            margin-top: 0;
        }
        .div-opciones h5{
            margin-top: 0;
            font-size: 15px;
        }
        .div-opcion{
            gap: 10px;
            margin-bottom: 10px;
        }
        .select2-container{
            width: 100% !important;
        }
        .select2-selection__clear{
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Configuraciones</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Campos personalizados</div>
                    </div>
                    <div class="card-body">
                        <form action="#" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="tipo">Tipo de campo</label>
                                <select class="form-control" name="tipo" id="tipo">
                                    <option value="text" {{ $campo->tipo == 'text' ? 'selected' : ''}}>Línea de texto</option>
                                    <option value="number" {{ $campo->tipo == 'number' ? 'selected' : ''}}>Número</option>
                                    <option value="textarea" {{ $campo->tipo == 'textarea' ? 'selected' : ''}}>Párrafo</option>
                                    <option value="checkbox" {{ $campo->tipo == 'checkbox' ? 'selected' : ''}}>Checkbox</option>
                                    <option value="select" {{ $campo->tipo == 'select' ? 'selected' : ''}}>Select</option>
                                </select>
                            </div>
                            <div class="from-group mt-2">
                                <label>Pistas a las que se le aplica</label>
                                    
                                <div class="border p-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="all" id="pistas" name="pistas" {{ $campo->all_pistas ? 'checked' : ''}}>
                                        <label class="form-check-label" for="pistas">
                                            Aplicar a todas las pistas
                                        </label>
                                    </div>
                                    <select @if ($campo->all_pistas) style="display:none" @endif class="form-control" name="pistas[]" id="pistas_select" data-init-plugin="select2" multiple required>
                                        @foreach (auth()->user()->instalacion->pistas as $item)
                                            <option value="{{ $item->id }}" @if (in_array($item->id, $campo->pistas->pluck('id')->toArray())) selected @endif>{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-4 mt-2">
                                <div class="border p-3">
                                    <div class="campos">
                                        <h4 style="text-transform:capitalize">{{ $campo->tipo }}</h4>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="required_field" name="required_field" {{ $campo->required ? 'checked' : ''}}>
                                            <label class="form-check-label" for="required_field">
                                                Campo requerido
                                            </label>
                                        </div>
                                        <div class="div-label">
                                            <input type="text" name="label" class="form-control" placeholder="Titulo del campo..." value="{{ $campo->label }}" required>
                                        </div>
                                        @if ($campo->tipo == 'select')
                                            <div class="div-opciones mt-2 border p-2">
                                                <h5>Opciones</h5>
                                                @foreach (unserialize($campo->opciones) as $item)
                                                    <div class="div-opcion d-flex">
                                                        <a href="#" class="text-danger btn"><i class="fas fa-times"></i></a><input type="text" name="opcion[]" class="form-control" placeholder="Título de la opción..." value="{{ $item }}" required>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <a href="#" class="mt-3 add-opcion btn btn-outline-primary mr-1"><i class="fas fa-plus mr-1"></i> Opción</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Editar" class="btn btn-primary btn-lg m-b-10 mt-3 mt-2">
                        </form>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('select#tipo').on('change', function () {
                $(this).parent().next().next().find('.campos').html(`
                    <h4>${$(this).find('option:selected').html()}</h4>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="required_field" name="required_field">
                        <label class="form-check-label" for="required_field">
                            Campo requerido
                        </label>
                    </div>
                    <div class="div-label">
                        <input type="text" name="label" class="form-control" placeholder="Titulo del campo..." required>
                    </div>
                    ${$(this).val() == 'select' ? `
                        <div class="div-opciones mt-2 border p-2">
                            <h5>Opciones</h5>
                            <div class="div-opcion d-flex">
                                <a href="#" class="text-danger btn"><i class="fas fa-times"></i></a><input type="text" name="opcion[]" class="form-control" placeholder="Título de la opción..." required>
                            </div>
                            <div class="div-opcion d-flex">
                                <a href="#" class="text-danger btn"><i class="fas fa-times"></i></a><input type="text" name="opcion[]" class="form-control" placeholder="Título de la opción..." required>
                            </div>
                        </div>
                        <a href="#" class="mt-3 add-opcion btn btn-outline-primary mr-1"><i class="fas fa-plus mr-1"></i> Opción</a>
                    ` : '' }
                `);
            });

            $('.campos').on('click', '.add-opcion', function (e) {
                e.preventDefault();
                $(this).prev().append(`
                <div class="div-opcion d-flex">
                    <a href="#" class="text-danger btn"><i class="fas fa-times"></i></a><input type="text" name="opcion[]" class="form-control" placeholder="Título de la opción..." required>
                </div>
                `);
            });

            $("#pistas_select").select2({
                placeholder: "Selecciona las pistas..."
            });

            $('#pistas').change(function (e) { 
                e.preventDefault();
                if ($(this).is(':checked')) {
                    $('.select2').hide();
                    $('#pistas_select').val(null).trigger('change').removeAttr('required');
                } else {
                    $('#pistas_select').attr('required', 'true');
                    $('.select2').show();
                }
            });
        });
    </script>
@endsection