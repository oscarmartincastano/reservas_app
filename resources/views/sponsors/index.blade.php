@extends('layouts.admin')

@section('style')
    <style>
        .clickable {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">{{ auth()->user()->instalacion->nombre }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de Patrocinadores</div>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('sponsors.create', ['slug_instalacion' => $instalacion->slug]) }}"
                            class="text-white btn btn-primary">Añadir nuevo</a>
                        <table class="table table-hover" id="table-users">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sponsors as $sponsor)
                                    <tr class="clickable"
                                        data-href="{{ route('sponsors.show', [
                                            'slug_instalacion' => $instalacion->slug,
                                            'id' => $sponsor->id,
                                        ]) }}">
                                        <td>
                                            <img src="{{ $sponsor->logo }}" alt="logo {{ $sponsor->name }}"
                                                class="img-fluid" width="100px">
                                        </td>
                                        <td>{{ $sponsor->name }}</td>
                                        <td>{{ $sponsor->website }}</td>
                                        <td>
                                            <div class="d-flex" style="gap:5px">

                                                <a href="{{ route('sponsors.show', [
                                                    'slug_instalacion' => $instalacion->slug,
                                                    'id' => $sponsor->id,
                                                ]) }}"
                                                    class="btn btn-success"><i data-feather="eye"></i></a>

                                                <a href="{{ route('sponsors.edit', [
                                                    'slug_instalacion' => $instalacion->slug,
                                                    'id' => $sponsor->id,
                                                ]) }}"
                                                    class="btn btn-primary"><i data-feather="edit"></i></a>

                                                <form
                                                    action="{{ route('sponsors.destroy', [
                                                        'slug_instalacion' => $instalacion->slug,
                                                        'id' => $sponsor->id,
                                                    ]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"><i
                                                            data-feather="trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <p class="small no-margin"></p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table-users').DataTable({
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [
                    [3, "asc"]
                ]
            });

            $('.clickable').click(function(e) {
                if (e.target.tagName == 'TD') {
                    window.location = $(this).data("href");
                }
            });
        });
    </script>
@endsection
