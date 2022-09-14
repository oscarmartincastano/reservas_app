@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Editar reserva #{{ $reserva->id }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">#{{ $reserva->id }}</div>
                    </div>
                    <div class="card-body">
                        <form method="post" role="form" >
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Cliente:</label>
                                <input type="text" class="form-control" value="{{ $reserva->user->name ?? '' }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fecha:</label>
                                <input type="text" class="form-control" value="{{ date('d/m/Y', strtotime($reserva->fecha)) }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Horas:</label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::createFromTimestamp($fecha)->format('H:i') }} a {{ \Carbon\Carbon::createFromTimestamp($fecha)->addMinutes($reserva->minutos_totales)->format('H:i') }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Espacio</label>
                                <select name="id_pista" id="id_pista" class="form-control">
                                    @foreach ($pistas as $item)
                                        <option value="{{ $item->id }}" @if($item->id == $reserva->id_pista) selected @endif>{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->tipo . ' - ' : '' }} {{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Duración:</label>
                                <select class="form-control full-width" name="tarifa" id="tarifa">
                                    @if ($reserva->pista->allow_more_res)
                                        @for ($i = 1; $i < $number+1; $i++)
                                            <option @if($reserva->tarifa == $i) selected @endif
                                                data-hfin="{{ date('H:i', strtotime(date('H:i', $fecha) . ' +' . $secuencia * $i . ' minutes')) }}"
                                                value="{{ $i }}">RESERVA {{floor(($secuencia*$i)/60) ? floor(($secuencia*$i)/60) . ' HORAS ' : ''  }} {{(($secuencia*$i)%60) ? (($secuencia*$i)%60)  . ' MINUTOS' : '' }}</option>
                                        @endfor
                                    @else
                                        <option @if($reserva->tarifa == 1) selected @endif
                                            data-hfin="{{ date('H:i', strtotime(date('H:i', $fecha) . ' +' . $secuencia  . ' minutes')) }}"
                                            value="1">{{floor(($secuencia)/60) ? floor(($secuencia)/60) . ' HORAS ' : ''  }} {{(($secuencia)%60) ? (($secuencia)%60)  . ' MINUTOS' : '' }}</option>
                                    @endif
                                </select>
                            </div>
                            @if ($reserva->observaciones)
                                <div class="form-group">
                                    <label class="">Observaciones:</label>
                                    <div class="">
                                        <textarea class="form-control" name="observaciones" rows="3">{{ $reserva->observaciones }}</textarea>
                                    </div>
                                </div>
                            @endif
                            
                            @foreach ($reserva->valores_campos_pers as $item)
                                <div class="form-group">
                                    <label class="">{{ $item->campo->label }}:</label>
                                    <div class="">
                                        @if ($item->tipo == 'textarea')
                                            <textarea class="form-control" name="campo_adicional[{{ $item->campo->id }}]" rows="3" {{ $item->campo->required ? 'required' : '' }}>{{ $item->valor }}</textarea>
                                        @elseif($item->tipo == 'select')
                                            <select class="form-control" name="campo_adicional[{{ $item->campo->id }}]">
                                                @foreach (unserialize($item->campo->opciones) as $option)
                                                    <option {{ $item->valor == $option ? 'selected' : '' }} value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input value="{{ $item->valor }}" type="{{ $item->campo->tipo }}" name="campo_adicional[{{ $item->campo->id }}]" class="form-control" placeholder="{{ $item->campo->label }}" {{ $item->campo->required ? 'required' : '' }}>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            <input type="hidden" name="secuencia" value="{{ $secuencia }}">
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
    $(document).ready(function () {
        $('#id_pista').select2();
    });
</script>
@endsection