<h1>Nueva reserva {{ $user->instalacion->nombre }}</h1>

<div>
    <ul>
        <li><strong>Usuario: </strong>{{ $user->name }}</li>
        <li><strong>Espacio: </strong>{{ $reserva->pista->tipo }}. {{ $reserva->pista->nombre }}</li>
        <li><strong>Fecha: </strong>{{ date('d/m/Y', $reserva->timestamp) }}</li>
        <li><strong>Horario: </strong>{{ date('H:i', $reserva->timestamp) }} - {{ date('H:i', strtotime(date('H:i', $reserva->timestamp) . " +{$reserva->minutos_totales} minutes")) }}</li>
        <li><strong>Tiempo total: </strong>{{ $reserva->minutos_totales }} minutos</li>
        @foreach ($reserva->valores_campos_personalizados as $element)
            <li><strong>{{ $element->campo->label }}: </strong>{{ $element->valor }}</li>
        @endforeach
    </ul>
</div>
