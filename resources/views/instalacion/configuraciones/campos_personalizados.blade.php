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
                            <div class="form-group mb-4">
                                <div class="border p-3">
                                    <div class="campos">
                                        
                                    </div>
                                    <div class="mt-4">
                                        <a href="#" data-tipo="Línea de texto" id="text" class="add-campo btn btn-outline-primary mr-1"><i class="fas fa-plus mr-1"></i> Línea de texto</a>
                                        <a href="#" data-tipo="Número" id="number" class="add-campo btn btn-outline-primary mr-1"><i class="fas fa-plus mr-1"></i> Número</a>
                                        <a href="#" data-tipo="Párrafo" id="textarea" class="add-campo btn btn-outline-primary mr-1"><i class="fas fa-plus mr-1"></i> Párrafo</a>
                                        <a href="#" data-tipo="Checkbox" id="checkbox" class="add-campo btn btn-outline-primary mr-1"><i class="fas fa-plus mr-1"></i> Checkbox</a>
                                        <a href="#" data-tipo="Select" id="select" class="add-campo btn btn-outline-primary mr-1"><i class="fas fa-plus mr-1"></i> Select</a>
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
            $('.add-campo').click(function (e) { 
                e.preventDefault();
                $(this).parent().prev().append(`
                <div class="border p-3 mt-2">
                    <h4>${$(this).data('tipo')}</h4>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="required_field" name="required_field">
                        <label class="form-check-label" for="required_field">
                            Campo requerido
                        </label>
                    </div>
                    <div class="div-label">
                        <input type="text" class="form-control" placeholder="Titulo del campo...">
                    </div>
                    <div class="div-opciones mt-2 border p-2 ${$(this).attr('id') != 'select' ? 'd-none' : '' }">
                        <h5>Opciones</h5>
                        <div class="div-opcion d-flex">
                            <a href="#" class="text-danger btn"><i class="fas fa-times"></i></a><input type="text" class="form-control" placeholder="Título de la opción...">
                        </div>
                        <div class="div-opcion d-flex">
                            <a href="#" class="text-danger btn"><i class="fas fa-times"></i></a><input type="text" class="form-control" placeholder="Título de la opción...">
                        </div>
                    </div>
                    ${$(this).attr('id') == 'select' ? '<a href="#" class="mt-3 add-opcion btn btn-outline-primary mr-1"><i class="fas fa-plus mr-1"></i> Opción</a>' : ''}
                </div>
                `);
            });

            $('.campos').on('click', '.add-opcion', function (e) {
                e.preventDefault();
                $(this).prev().append(`
                <div class="div-opcion d-flex">
                    <a href="#" class="text-danger btn"><i class="fas fa-times"></i></a><input type="text" class="form-control" placeholder="Título de la opción...">
                </div>
                `);
            });
        });
    </script>
@endsection