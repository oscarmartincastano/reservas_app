<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reserva;
use App\Models\Instalacion;

class Pista extends Model
{
    use HasFactory;

    protected $table = 'pistas';

    protected $fillable = [
        'id_instalacion',
        'nombre',
        'tipo',
        'horario',
        'allow_cancel',
        'atenlacion_reserva',
        'allow_more_res',
    ];

    protected $appends = ['horario_deserialized'/* , 'string_horario' */];

    public function instalacion()
    {
        return $this->hasOne(Instalacion::class, 'id', 'id_instalacion');
    }

    public function getHorarioDeserializedAttribute() {
        return $this->horarioDeserializado();
    }

    public function horarioDeserializado() {
        $horario = unserialize($this->horario);
        return $horario;
    }

    /* public function getStringHorarioAttribute() {
        return $this->stringHorario();
    }

    public function stringHorario() {
        @foreach ($item->horario_deserialized as $horario)
            @if (count($horario['dias']) == 7)
                <strong>Todos los días:</strong>
            @else
                @if (checkConsec($horario['dias']))
                    @foreach ($horario['dias'] as $index => $dia)
                        @switch($dia)
                            @case(1)
                                @php $horario['dias'][$index] = 'lunes' @endphp
                                @break
                            @case(2)
                                @php $horario['dias'][$index] = 'martes' @endphp
                                @break
                            @case(3)
                                @php $horario['dias'][$index] = 'miércoles' @endphp
                                @break
                            @case(4)
                                @php $horario['dias'][$index] = 'jueves' @endphp
                                @break
                            @case(5)
                                @php $horario['dias'][$index] = 'viernes' @endphp
                                @break
                            @case(6)
                                @php $horario['dias'][$index] = 'sábado' @endphp
                                @break
                            @case(7)
                                @php $horario['dias'][$index] = 'domingo' @endphp
                                @break
                            @default
                        @endswitch
                    @endforeach
                    <strong>@if (count($horario['dias']) > 1) {{ ucfirst($horario['dias'][0]) }} a {{ $horario['dias'][count($horario['dias']) - 1] }} @else {{ ucfirst($horario['dias'][0]) }} @endif</strong></div>
                @else
                    @foreach ($horario['dias'] as $index => $dia)
                        @switch($dia)
                            @case(1)
                                @php $horario['dias'][$index] = 'lunes' @endphp
                                @break
                            @case(2)
                                @php $horario['dias'][$index] = 'martes' @endphp
                                @break
                            @case(3)
                                @php $horario['dias'][$index] = 'miércoles' @endphp
                                @break
                            @case(4)
                                @php $horario['dias'][$index] = 'jueves' @endphp
                                @break
                            @case(5)
                                @php $horario['dias'][$index] = 'viernes' @endphp
                                @break
                            @case(6)
                                @php $horario['dias'][$index] = 'sábado' @endphp
                                @break
                            @case(7)
                                @php $horario['dias'][$index] = 'domingo' @endphp
                                @break
                            @default
                        @endswitch
                    @endforeach
                    <strong>
                        @foreach ($horario['dias'] as $index => $dia)
                           @if ($index == 0){{ ucfirst($dia) }}@else{{ $dia }}@endif<i></i>@if ($index != count($horario['dias']) - 1), @endif
                        @endforeach
                    </strong>
                @endif
            @endif
                @foreach ($horario['intervalo'] as $int)
                    <li style="margin-left: 10px">{{ $int['hinicio'] }}h -{{ $int['hfin'] }}h cada {{ $int['secuencia'] }} min.</li>
                @endforeach
        @endforeach
    } */
}
