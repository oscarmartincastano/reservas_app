<form
    action="
@isset($bank)
    {{ route('banks.update', ['slug_instalacion' => request()->slug_instalacion, 'id' => $bank->id]) }}
@else
    {{ route('banks.store', ['slug_instalacion' => request()->slug_instalacion]) }}

@endisset
"
    method="post" role="form" class="form-horizontal">
    @csrf
    <div class="form-group row">
        <label class="col-md-2 control-label">Nombre</label>
        <input name="name" type="text" placeholder="Nombre..." class="form-control col-md-10"
            @isset($bank)
            value="{{ $bank->name }}"
            @else
            value="{{ old('name') }}"
        @endisset
            required>
    </div>

    @isset($bank)
        @method('PUT')
        <button class="btn btn-warning btn-lg m-b-10 mt-3" type="submit">
            <i class="fa fa-refresh"></i>
            Actualizar
        </button>
    @else
        <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">
            <i class="fa fa-plus"></i>
            Añadir
        </button>
    @endisset
</form>
