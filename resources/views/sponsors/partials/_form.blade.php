{{-- all errors --}}

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<form enctype="multipart/form-data" method="post"
    @isset($sponsor)
    action="{{ route('sponsors.update', ['slug_instalacion' => $instalacion->slug, 'id' => $sponsor->id]) }}"
    @else
        action="{{ route('sponsors.store', [
            'slug_instalacion' => $instalacion->slug,
        ]) }}"
    @endisset>

    @csrf
    <div class="form-group row">
        <label class="col-md-2 control-label">Nombre</label>
        <input name="name" type="text" placeholder="Nombre..." class="form-control col-md-10"
            value="{{ old('name') ?? (isset($sponsor) ? $sponsor->name : '') }}" required>
    </div>
    @error('name')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="form-group row">
        <label class="col-md-2 control-label">Página web</label>
        <input name="website" type="text" placeholder="Página web..." class="form-control col-md-10"
            value="{{ old('website') ?? (isset($sponsor) ? $sponsor->website : '') }}">
    </div>
    @error('website')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="form-group row">
        <label class="col-md-2 control-label">Logo</label>
        <input name="logo" type="file" placeholder="Logo..." class="form-control col-md-10" accept="image/*"
            @isset($sponsor)
        @else
            required
            @endisset>
    </div>
    @error('logo')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @isset($sponsor)
        @method('PUT')
        <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Actualizar</button>
    @else
        <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Añadir</button>
    @endisset
</form>
