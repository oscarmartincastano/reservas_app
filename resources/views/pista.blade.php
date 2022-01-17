@extends('layouts.userview')

@section('pagename', 'Tenis')

@section('content')

<section class="hero is-medium">
    <div class="hero-body has-text-centered" style="padding-top: 20px;padding-bottom: 3rem">
        <h1 class="title is-2">Tenis</h1>
        
    </div>
</section>

<div class="container is-max-desktop">
    <div class="columns">
        <div class="column is-full">
            <div class="div-reservas">
                <div class="pistas">
                    @foreach ($pistas as $index => $pista)
                        <div class="@if($pista->id == $pista_selected->id) active @endif"><a class=" select-pista" data-id_pista="{{ $pista->id }}" href="/{{ request()->deporte }}/{{ $pista->id }}">{{ $pista->nombre }}</a></div>
                    @endforeach
                </div>
                <div class="calendario-horarios">
                    <div class="navigator"> 
                        <div><a class="button" href="#"><</a> <a class="button" href="#">Hoy</a> <a class="button" href="#">></a> </div>
                        <div style="text-transform: capitalize">{{ \Carbon\Carbon::parse(iterator_to_array($period)[0])->formatLocalized('%B %Y')  }}</div> 
                    </div>
                </div>
                <div class="thead">
                    {{-- <div>
                        <div class="th">Hora</div>
                        <div class="th">6:00 - 7:00</div>
                        <div class="th">7:00 - 8:00</div>
                        <div class="th">8:00 - 9:00</div>
                        <div class="th">9:00 - 10:00</div>
                        <div class="th">10:00 - 11:00</div>
                        <div class="th">11:00 - 12:00</div>
                        <div class="th">12:00 - 13:00</div>
                        <div class="th">13:00 - 14:00</div>
                        <div class="th">14:00 - 15:00</div>
                        <div class="th">15:00 - 16:00</div>
                        <div class="th">16:00 - 17:00</div>
                        <div class="th">17:00 - 18:00</div>
                        <div class="th">18:00 - 19:00</div>
                        <div class="th">19:00 - 20:00</div>
                        <div class="th">20:00 - 21:00</div>
                        <div class="th">21:00 - 22:00</div>
                        <div class="th">22:00 - 23:00</div>
                    </div> --}}
                    @foreach ($period as $fecha)
                        <div class="th" style="text-transform: capitalize">
                            <div>{{ \Carbon\Carbon::parse($fecha)->formatLocalized('%A') }}<br>{{ $fecha->format('d/m') }}</div>
                            @foreach ($pista_selected->horario_deserialized as $item)
                                @if (in_array($fecha->format('w'), $item['dias']) || ( $fecha->format('w') == 0 && in_array(7, $item['dias']) ))
                                    @foreach ($item['intervalo'] as $index => $intervalo)
                                        @if ($index == 0)
                                            {{-- @php
                                                $a = new \DateTime('6:00');
                                                $b = new \DateTime($intervalo['hinicio']);
                                                $interval = $a->diff($b);
                                                $dif_start = $interval->format("%h") * 60;
                                                $dif_start += $interval->format("%i");
                                                $dif_start = $dif_start / 60;
                                                if (!is_int($dif_start)) {
                                                    $decimal = $dif_start - floor($dif_start); 
                                                }
                                            @endphp --}}
                                            {{-- {{ dd($dif_start) }} --}}
                                            {{-- <div class="empty" style="height:{{  str_replace(',', '.', $dif_start*6) }}rem"><a href="#"></a></div> --}}

                                            {{-- @if (!is_int($dif_start))
                                                @for ($i = 0; $i < ceil($dif_start); $i++)
                                                    @if ($i == ceil($dif_start) - 1)
                                                        <div class="empty" style="height:{{  str_replace(',', '.', ($decimal*6)) }}rem"><a href="#"></a></div>
                                                    @else
                                                        <div class="empty"><a href="#"></a></div>
                                                    @endif
                                                @endfor
                                            @else
                                                @for ($i = 0; $i < round($dif_start); $i++)
                                                    <div class="empty"><a href="#"></a></div>
                                                @endfor
                                            @endif --}}

                                            {{-- @if (is_int($dif_start))
                                                @for ($i = 0; $i < $dif_start; $i++)
                                                    <div class="empty"><a href="#"></a></div>
                                                @endfor
                                            @endif --}}
                                            
                                            @php
                                                $a = new \DateTime($intervalo['hfin']);
                                                $b = new \DateTime($intervalo['hinicio']);
                                                $interval = $a->diff($b);
                                                $dif = $interval->format("%h") * 60;
                                                $dif += $interval->format("%i");
                                                $dif = $dif / $intervalo['secuencia'];
                                            @endphp
                                            
                                            @if (is_int($dif))
                                                @for ($i = 0; $i < $dif; $i++)
                                                    <div style="height:{{  str_replace(',', '.', $intervalo['secuencia']/10) }}rem">
                                                        @php
                                                            if ($i == 0) {
                                                                $hora = new \DateTime($fecha->format('d-m-Y') . ' ' . $intervalo['hinicio']);
                                                                $string_hora = $intervalo['hinicio'] . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                                                            } else {
                                                                $string_hora = $hora->format('H:i') . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                                                            }

                                                            $date_now = new \DateTime();
                                                            $date_horario = new \DateTime($hora->format('d-m-Y H:i:s'));
                                                            $resta = $intervalo['secuencia'] + $pista_selected->tiempo_limite_reserva;
                                                            $date_horario->modify("-{$resta} minutes");
                                                        @endphp

                                                        @if ($hora->format('d') == date('d') && $date_horario < $date_now)
                                                            <a href="#" class="btn-no-disponible">
                                                                {{ $string_hora }}<br>
                                                                (NO DISPONIBLE)
                                                            </a>
                                                        @else
                                                            <a href="/{{ request()->deporte }}/{{ $pista_selected->id }}/{{ \Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->subMinutes($intervalo['secuencia'])->timestamp }}" class="btn-reservar">
                                                                {{ $string_hora }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endfor
                                            @endif
                                        @else
                                            {{-- @php
                                                $a = new \DateTime($item['intervalo'][$index - 1]['hfin']);
                                                $b = new \DateTime($intervalo['hinicio']);
                                                $interval = $a->diff($b);
                                                $dif_start = $interval->format("%h") * 60;
                                                $dif_start += $interval->format("%i");
                                                $dif_start = $dif_start / 60;
                                                if (!is_int($dif_start)) {
                                                    $decimal = $dif_start - floor($dif_start); 
                                                }
                                            @endphp

                                            @if (!is_int($dif_start))
                                                @for ($i = 0; $i < ceil($dif_start); $i++)
                                                    @if ($i == 0)
                                                        <div class="empty" style="height:{{  str_replace(',', '.', ($decimal*6)) }}rem"><a href="#"></a></div>
                                                    @else
                                                        <div class="empty"><a href="#"></a></div>
                                                    @endif
                                                @endfor
                                            @else
                                                @for ($i = 0; $i < round($dif_start); $i++)
                                                    <div class="empty"><a href="#"></a></div>
                                                @endfor
                                            @endif --}}
                                            
                                            @php
                                                $a = new \DateTime($intervalo['hfin']);
                                                $b = new \DateTime($intervalo['hinicio']);
                                                $interval = $a->diff($b);
                                                $dif = $interval->format("%h") * 60;
                                                $dif += $interval->format("%i");
                                                $dif = $dif / $intervalo['secuencia'];
                                            @endphp
                                            @if (is_int($dif))
                                                @for ($i = 0; $i < $dif; $i++)
                                                    <div style="height:{{  str_replace(',', '.', $intervalo['secuencia']/10) }}rem">
                                                        @php
                                                            if ($i == 0) {
                                                                $hora = new \DateTime($fecha->format('d-m-Y') . ' ' . $intervalo['hinicio']);
                                                                $string_hora = $intervalo['hinicio'] . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                                                            } else {
                                                                $string_hora = $hora->format('H:i') . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                                                            }
                                                            
                                                            $date_now = new \DateTime();
                                                            $date_horario = new \DateTime($hora->format('d-m-Y H:i:s'));
                                                            $resta = $intervalo['secuencia'] + $pista_selected->tiempo_limite_reserva;
                                                            $date_horario->modify("-{$resta} minutes");
                                                        @endphp

                                                        @if ($hora->format('d') == date('d') && $date_horario < $date_now)
                                                            <a href="#" class="btn-no-disponible">
                                                                {{ $string_hora }}<br>
                                                                (NO DISPONIBLE)
                                                            </a>
                                                        @else
                                                            <a href="/{{ request()->deporte }}/{{ $pista_selected->id }}/{{ \Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->subMinutes($intervalo['secuencia'])->timestamp }}" class="btn-reservar">
                                                                {{ $string_hora }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endfor
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection