@extends('layouts.admin')

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
                        <div class="card-title">Instalación</div>
                    </div>
                    <div class="card-body">

                        <table class="table table-condensed table-hover">
                        
                            <tbody>
                                <tr>
                                    <th>Nombre</th>
                                    <td>{{ $instalacion->nombre }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/edit/nombre" class="btn btn-primary"><i data-feather="edit"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Logo</th>
                                    <td></td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/edit/logo" class="btn btn-primary"><i data-feather="edit"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Dirección</th>
                                    <td>{{ $instalacion->direccion }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/edit/direccion" class="btn btn-primary"><i data-feather="edit"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Teléfono</th>
                                    <td>{{ $instalacion->tlfno }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/edit/tlfno" class="btn btn-primary"><i data-feather="edit"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>{{ $instalacion->slug }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/edit/slug" class="btn btn-primary"><i data-feather="edit"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>
        
    </div>
</div>
@endsection