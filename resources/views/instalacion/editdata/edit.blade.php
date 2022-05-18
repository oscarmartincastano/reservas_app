@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Editar información</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">{{ request()->tipo == 'tlfno' ? 'Teléfono' : request()->tipo }}</div>
                    </div>
                    <div class="card-body">
                        <form action="#" method="post" role="form" id="form-normas" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label>{{ request()->tipo == 'tlfno' ? 'Teléfono' : ucfirst(request()->tipo) }}</label>
                                @if (request()->tipo == 'logo')
                                    <input name="{{ request()->tipo }}" type="file" placeholder="Logo..." class="form-control" required>
                                @elseif(request()->tipo == 'html_normas')
                                    <div class="form-group">
                                        <div class="quill-wrapper">
                                            <div id="quill">
                                                {!! $instalacion[request()->tipo] ?? '' !!}
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="html_normas" class="html_normas">
                                @else
                                    <input value="{{ $instalacion[request()->tipo] }}" name="{{ request()->tipo }}" type="text" placeholder="{{ request()->tipo == 'tlfno' ? 'Teléfono' : ucfirst(request()->tipo) }}..." class="form-control" required>
                                @endif
                            </div>
                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Editar</button>
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
        $(document).ready(function() {
            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],

                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                [{ 'indent': '-1' }, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],

                [{ 'size': ['small', false, 'large', 'huge'] }]
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': [] }],
                [{ 'align': [] }],

                ['clean']
            ];

            var quill = new Quill('#quill', {
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Html de normas...',
                theme: 'snow'
            });

            if ($('.html_normas').length) {
                $('#form-normas').submit(function (e) { 
                    $('.html_normas').val(quill.root.innerHTML);
                });
            }
        });
    </script>
@endsection