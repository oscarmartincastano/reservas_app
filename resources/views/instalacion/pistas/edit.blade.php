@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">
        
            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Editar "{{ $pista->nombre }}"</h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Información</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('edit_pista', ['id' => $pista->id, 'slug_instalacion' => $pista->instalacion->slug]) }}" method="post" role="form" class="form-horizontal">
                            @csrf
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Nombre</label>
                                <input value="{{ $pista->nombre }}" name="nombre" type="text" placeholder="Nombre..." class="form-control col-md-10" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Tipo</label>
                                <select class="form-control col-md-10" name="tipo">
                                    <option {{ $pista->nombre == 'Tenis' }} value="Tenis">Tenis</option>
                                    <option {{ $pista->nombre == 'Pádel' }} value="Pádel">Pádel</option>
                                    <option {{ $pista->nombre == 'Fútbol' }} value="Fútbol">Fútbol</option>
                                    <option {{ $pista->nombre == 'Ping pong' }} value="Ping pong">Ping pong</option>
                                    <option {{ $pista->nombre == 'Sala' }} value="Sala">Sala</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Horario para alquiler</label>
                                <div class="col-md-10">
                                    <div>
                                        @foreach (unserialize($pista->horario) as $index =>  $horario)
                                            <div class="ml-1 my-1 border" style="padding: 14px" data-index="{{ $index }}">
                                                @if ($loop->index != 0)
                                                    <a href="#" class="btn btn-danger delete-horario" style="position: absolute;right:1%;">X</a>
                                                @endif
                                                <strong class="mb-2">Horario <span>{{ $loop->index+1 }}</span></strong>
                                                <div class="row my-1" style="gap: 18px">
                                                    <div class="form-check primary"><input type="checkbox" name="horario[{{ $index }}][dias][]" id="Lunes[0]" value="1" @if (in_array("1", $horario['dias'])) checked @endif> <label for="Lunes[0]">
                                                        Lunes</label> </div>
                                                    <div class="form-check primary"><input type="checkbox" name="horario[{{ $index }}][dias][]" id="Martes[0]" value="2" @if (in_array("2", $horario['dias'])) checked @endif> <label for="Martes[0]">
                                                        Martes</label> </div>
                                                    <div class="form-check primary"><input type="checkbox" name="horario[{{ $index }}][dias][]" id="Miércoles[0]" value="3" @if (in_array("3", $horario['dias'])) checked @endif> <label for="Miércoles[0]">
                                                        Miércoles</label> </div>
                                                    <div class="form-check primary"><input type="checkbox" name="horario[{{ $index }}][dias][]" id="Jueves[0]" value="4" @if (in_array("4", $horario['dias'])) checked @endif> <label for="Jueves[0]">
                                                        Jueves</label> </div>
                                                    <div class="form-check primary"><input type="checkbox" name="horario[{{ $index }}][dias][]" id="Viernes[0]" value="5" @if (in_array("5", $horario['dias'])) checked @endif> <label for="Viernes[0]">
                                                        Viernes</label> </div>
                                                    <div class="form-check primary"><input type="checkbox" name="horario[{{ $index }}][dias][]" id="Sábado[0]" value="6" @if (in_array("6", $horario['dias'])) checked @endif> <label for="Sábado[0]">
                                                        Sábado</label> </div>
                                                    <div class="form-check primary"><input type="checkbox" name="horario[{{ $index }}][dias][]" id="Domingo[0]" value="7" @if (in_array("7", $horario['dias'])) checked @endif> <label for="Domingo[0]">
                                                        Domingo</label> </div>
                                                </div>
                                                <div>
                                                    @foreach ($horario['intervalo'] as $intindice => $intervalo)
                                                        <div class="row my-2 p-2 border interval-horario align-items-center" style="gap: 18px" data-index="{{ $intindice }}">
                                                            <div><label class="mb-0">Hora inicio: </label><input
                                                                    class="form-control" type="time" name="horario[{{ $index }}][intervalo][{{ $intindice }}][hinicio]"
                                                                    id="hora_inicio" value="{{ $intervalo['hinicio'] }}"></div>
                                                            <div><label class="mb-0">Hora Fin: </label><input
                                                                    class="form-control" type="time" name="horario[{{ $index }}][intervalo][{{ $intindice }}][hfin]"
                                                                    id="hora_fin" value="{{ $intervalo['hfin'] }}"></div>
                                                            <div>
                                                                <label class="mb-0">Secuencia:</label>
                                                                <select class="form-control" name="horario[{{ $index }}][intervalo][{{ $intindice }}][secuencia]" id="">
                                                                    @for ($i = 1; $i < 9; $i++)
                                                                        <option value="{{ $i * 15 }}" @if($intervalo['secuencia'] == $i * 15) selected @endif>{{ $i * 15 }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @if ($loop->index != 0)
                                                                <div>
                                                                    <a href="#" class="btn btn-danger btn-borrar-intervalo">X</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                    <div class="mt-2">
                                                        <a href="#" class="btn btn-success add-int-horario">Añadir intervalo horario</a>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        <a href="#" class="add-dia btn btn-success mt-1 add-dia-reserva">Añadir días de reserva</a>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-md-2 control-label">Precio por tramo (€)</label>
                                <input name="precio_por_tramo" type="number" step=".01" placeholder="Precio..."
                                    class="form-control col-md-10">
                            </div> --}}
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Permite cancelación</label>
                                <select class="form-control col-md-10" name="allow_cancel" id="allow_cancel">
                                    <option value="1" @if($pista->allow_cancel) selected @endif>Sí</option>
                                    <option value="0" @if(!$pista->allow_cancel) selected @endif>No</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Antelación de la reserva</label>
                                <select class="form-control col-md-10" name="atenlacion_reserva" id="">
                                    @for ($i = 1; $i < 30; $i++)
                                        <option value="{{ $i }}" @if($pista->atenlacion_reserva == $i) selected @endif>{{ $i }} días</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Permite reservar varios tramos</label>
                                <select class="form-control col-md-10" name="allow_more_res" id="">
                                    <option value="0" @if($pista->allow_more_res) selected @endif>No</option>
                                    <option value="1" @if(!$pista->allow_more_res) selected @endif>Sí</option>
                                </select>
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
            $('form').on('click', '.add-int-horario', function(e) {
                e.preventDefault();
                $(this).parent().prev().after(`<div class="row my-2 p-2 border interval-horario align-items-center" style="gap: 18px" data-index="${$(this).parent().prev().data('index') + 1}">
                                                    <div><label class="mb-0">Hora inicio: </label><input
                                                            class="form-control" type="time" name="horario[${$(this).parent().parent().parent().data('index')}][intervalo][${$(this).parent().prev().data('index') + 1}][hinicio]"
                                                            id="hora_inicio"></div>
                                                    <div><label class="mb-0">Hora Fin: </label><input
                                                            class="form-control" type="time" name="horario[${$(this).parent().parent().parent().data('index')}][intervalo][${$(this).parent().prev().data('index') + 1}][hfin]"
                                                            id="hora_fin"></div>
                                                    <div>
                                                        <label class="mb-0">Secuencia:</label>
                                                        <select class="form-control" name="horario[${$(this).parent().parent().parent().data('index')}][intervalo][${$(this).parent().prev().data('index') + 1}][secuencia]" id="">
                                                            @for ($i = 1; $i < 9; $i++)
                                                                <option value="{{ $i * 15 }}">{{ $i * 15 }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    
                                                    <div>
                                                        <a href="#" class="btn btn-danger btn-borrar-intervalo">X</a>
                                                    </div>
                                                </div>`);
            });

            $('.add-dia-reserva').click(function(e) {
                e.preventDefault();
                $(this).prev().after(`<div class="ml-1 my-1 border" style="padding: 14px" data-index="${$(this).prev().data('index') + 1}">
                                            <strong class="mb-2">Horario <span>${parseInt($(this).prev().find('span').html()) + 1}</span></strong>
                                            <a href="#" class="btn btn-danger delete-horario" style="position: absolute;right:1%;">X</a>
                                            <div class="d-flex my-1" style="gap: 18px">
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Lunes[${$(this).prev().data('index') + 1}]" value="1"> <label for="Lunes[${$(this).prev().data('index') + 1}]">
                                                    Lunes</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Martes[${$(this).prev().data('index') + 1}]" value="2"> <label for="Martes[${$(this).prev().data('index') + 1}]">
                                                    Martes</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Miércoles[${$(this).prev().data('index') + 1}]" value="3"> <label for="Miércoles[${$(this).prev().data('index') + 1}]">
                                                    Miércoles</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Jueves[${$(this).prev().data('index') + 1}]" value="4"> <label for="Jueves[${$(this).prev().data('index') + 1}]">
                                                    Jueves</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Viernes[${$(this).prev().data('index') + 1}]" value="5"> <label for="Viernes[${$(this).prev().data('index') + 1}]">
                                                    Viernes</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Sábado[${$(this).prev().data('index') + 1}]" value="6"> <label for="Sábado[${$(this).prev().data('index') + 1}]">
                                                    Sábado</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Domingo[${$(this).prev().data('index') + 1}]" value="7"> <label for="Domingo[${$(this).prev().data('index') + 1}]">
                                                    Domingo</label> </div>
                                            </div>
                                            <div>
                                                <div class="row my-2 p-2 border interval-horario" style="gap: 18px" data-index="0">
                                                    <div><label class="mb-0">Hora inicio: </label><input
                                                            class="form-control" type="time" name="horario[${$(this).prev().data('index') + 1}][intervalo][0][hinicio]"
                                                            id="hora_inicio"></div>
                                                    <div><label class="mb-0">Hora Fin: </label><input
                                                            class="form-control" type="time" name="horario[${$(this).prev().data('index') + 1}][intervalo][0][hfin]"
                                                            id="hora_fin"></div>
                                                    <div>
                                                        <label class="mb-0">Secuencia:</label>
                                                        <select class="form-control" name="horario[${$(this).prev().data('index') + 1}][intervalo][0][secuencia]" id="">
                                                            @for ($i = 1; $i < 9; $i++)
                                                                <option value="{{ $i * 15 }}">{{ $i * 15 }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <a href="#" class="btn btn-success add-int-horario">Añadir intervalo horario</a>
                                                </div>

                                            </div>
                                        </div>`);
            });

            $('form').on('click', '.btn-borrar-intervalo', function(e) {
                e.preventDefault();
                $(this).parent().parent().remove();
            });

            $('form').on('click', '.delete-horario', function(e) {
                e.preventDefault();
                $(this).parent().remove();
            });
        });
    </script>
@endsection