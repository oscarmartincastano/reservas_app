@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Editar reserva periódica</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Información</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update_reserva_periodica', ['slug_instalacion' => request()->slug_instalacion]) }}"
                            method="post" role="form" id="editForm" class="form-horizontal">
                            @csrf
                            <input type="hidden" name="id" value="{{ $reserva_periodica->id }}">
                            <div class="form-group">
                                <label for="espacio">Espacio</label>
                                <select required class="full-width form-control" name="espacio" id="select_espacio">
                                    @foreach (auth()->user()->instalacion->pistas as $item)
                                        {{-- <option value="{{ $item->id }}">{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->tipo . '.' : '' }} {{ $item->nombre }}</option> --}}
                                        @if ($item->id == $reserva_periodica->id_pista)
                                            <option value="{{ $item->id }}" selected>{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->tipo . '.' : '' }} {{ $item->nombre }}</option>
                                        @else
                                            <option value="{{ $item->id }}">{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->tipo . '.' : '' }} {{ $item->nombre }}</option>
                                        @endif

                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="espacio">Usuario</label>
                                <select required class="full-width select-user" name="user_id" id="user_id">
                                    @foreach (auth()->user()->instalacion->users_validos as $item)
                                        @if ($item->id != auth()->user()->id)
                                            @if ($item->id == $reserva_periodica->id_user)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }} ({{ $item->email }})</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->email }})</option>
                                            @endif

                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha inicio</label>
                                <input type="date" class="form-control" placeholder="Fecha inicio" name="fecha_inicio" value="{{ date('Y-m-d', strtotime($reserva_periodica->fecha_inicio)) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_fin">Fecha fin</label>
                                <input type="date" class="form-control" placeholder="Fecha fin" name="fecha_fin" required value="{{ date('Y-m-d', strtotime($reserva_periodica->fecha_fin)) }}">
                            </div>
                            <div class="form-group">
                                <label for="tipo">Días</label>
                                <select required class="full-width select2 select-desactivacion" data-init-plugin="select2" name="dias[]" id="desactivaciones[]" multiple>
                                    <option></option>
                                    <option value="1" {{ in_array(1, unserialize($reserva_periodica->dias)) ? 'selected' : '' }}>Lunes</option>
                                    <option value="2" {{ in_array(2, unserialize($reserva_periodica->dias)) ? 'selected' : '' }}>Martes</option>
                                    <option value="3"{{ in_array(3, unserialize($reserva_periodica->dias)) ? 'selected' : '' }}>Miércoles</option>
                                    <option value="4" {{ in_array(4, unserialize($reserva_periodica->dias)) ? 'selected' : '' }}>Jueves</option>
                                    <option value="5" {{ in_array(5, unserialize($reserva_periodica->dias)) ? 'selected' : '' }}>Viernes</option>
                                    <option value="6" {{ in_array(6, unserialize($reserva_periodica->dias)) ? 'selected' : '' }}>Sábado</option>
                                    <option value="0" {{ in_array(0, unserialize($reserva_periodica->dias)) ? 'selected' : '' }}>Domingo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="hora_inicio">Hora inicio</label>
                                <input type="time" class="form-control" placeholder="Hora inicio" name="hora_inicio" required value="{{ $reserva_periodica->hora_inicio }}">
                            </div>
                            <div class="form-group">
                                <label for="hora_fin">Hora fin</label>
                                <input type="time" class="form-control" placeholder="Hora fin" name="hora_fin" required value="{{ $reserva_periodica->hora_fin }}">
                            </div>
                            @if (auth()->user()->id_instalacion == 2)
                            <div class="border p-2">
                                @foreach (\App\Models\Pista::find(24)->all_campos_personalizados as $item)
                                    <div class="form-group">
                                        <label>{{ $item->label }}:</label>
                                            @if ($item->tipo == 'textarea')
                                                <textarea class="form-control" name="campo_adicional[{{ $item->id }}]" rows="3" {{ $item->required ? 'required' : '' }}></textarea>
                                            @elseif($item->tipo == 'select')
                                                <select class="form-control" name="campo_adicional[{{ $item->id }}]">
                                                    @foreach (unserialize($item->opciones) as $option)
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="{{ $item->tipo }}" name="campo_adicional[{{ $item->id }}]" class="form-control" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }}>
                                            @endif
                                        </div>
                                @endforeach
                            </div>
                            @endif
                            
                            <button class="btn btn-primary btn-lg m-b-10 mt-3 editButton" type="submit">Editar</button>
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
            
            $(".editButton").click(function (e) { 
                $('.editButton').attr('disabled', true);
                $('.editButton').text('Editando...');
                $('#editForm').submit();
            });

         
            $(".select2").select2({
                placeholder: "Selecciona días..."
            });

            $(".select-user").select2();

            $("#select_espacio").select2();
        });
    </script>
@endsection