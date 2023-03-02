<form
    @isset($supplier)
    action="{{ route('suppliers.update', ['slug_instalacion' => request()->slug_instalacion, 'id' => $supplier->id]) }}"
    @else
    action="{{ route('suppliers.store', ['slug_instalacion' => request()->slug_instalacion]) }}"
@endisset
    method="post" role="form" class="form-horizontal">
    @csrf

    <div class="form-group row">
        <label class="col-md-2 control-label">Nombre</label>
        <input name="name" type="text" placeholder="Nombre..." class="form-control col-md-10"
            value="{{ old('name', isset($supplier) ? $supplier->name : '') }}" required>
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Dirección</label>
        <input name="address" type="text" placeholder="Dirección..." class="form-control col-md-10"
            value="{{ old('address', isset($supplier) ? $supplier->address : '') }}">
        @error('address')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Teléfono</label>
        <input name="phone" type="text" placeholder="Teléfono..." class="form-control col-md-10"
            value="{{ old('phone', isset($supplier) ? $supplier->phone : '') }}">
        @error('phone')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Correo electrónico</label>
        <input name="email" type="email" placeholder="Correo electrónico..." class="form-control col-md-10"
            value="{{ old('email', isset($supplier) ? $supplier->email : '') }}">
        @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">CIF</label>
        <input name="cif" type="text" placeholder="CIF..." class="form-control col-md-10"
            value="{{ old('cif', isset($supplier) ? $supplier->cif : '') }}">
        @error('cif')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Código postal</label>
        <input name="cp" type="text" placeholder="Código postal..." class="form-control col-md-10"
            value="{{ old('cp', isset($supplier) ? $supplier->cp : '') }}">
        @error('cp')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Localidad</label>
        <input name="city" type="text" placeholder="Localidad..." class="form-control col-md-10"
            value="{{ old('city', isset($supplier) ? $supplier->city : '') }}">
        @error('city')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Provincia</label>
        <input name="province" type="text" placeholder="Provincia..." class="form-control col-md-10"
            value="{{ old('province', isset($supplier) ? $supplier->province : '') }}">
        @error('province')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Notas</label>
        <textarea name="notes" id="" cols="30" rows="10">
@isset($supplier)
{{ $supplier->notes }}
@endisset
</textarea>
        @error('notes')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>



    @isset($supplier)
        @method('PUT')
        <button class="btn btn-warning btn-lg m-b-10 mt-3" type="submit">Actualizar</button>
    @else
        @method('POST')
        <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Añadir</button>
    @endisset
</form>
