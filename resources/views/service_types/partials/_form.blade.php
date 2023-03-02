<form
    @isset($serviceType)
    action="{{ route('serviceTypes.update', ['slug_instalacion' => request()->slug_instalacion, 'id' => $serviceType->id]) }}"
    @else
    action="{{ route('serviceTypes.store', ['slug_instalacion' => request()->slug_instalacion]) }}"
@endisset
    method="post" role="form" class="form-horizontal">
    @csrf

    <div class="form-group row">
        <label class="col-md-2 control-label">Nombre</label>
        <input name="name" type="text" placeholder="Nombre..." class="form-control col-md-10"
            value="{{ old('name', isset($serviceType) ? $serviceType->name : '') }}" required>
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">IVA</label>
        <input name="iva" type="number" placeholder="%IVA..." class="form-control col-md-10" min="0"
            max="100" step="0.01" value="{{ old('iva', isset($serviceType) ? $serviceType->iva : '') }}"
            required>
        @error('naivae')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>


    @isset($serviceType)
        @method('PUT')
        <button class="btn btn-warning btn-lg m-b-10 mt-3" type="submit">Actualizar</button>
    @else
        @method('POST')
        <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Añadir</button>
    @endisset
</form>
