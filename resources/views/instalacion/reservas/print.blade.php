<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .ticket {
            width: 300px;
            padding: 20px;
            border: 1px solid #000;
            margin: 0 auto;
        }
        .ticket h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .ticket p {
            margin: 5px 0;
        }
        .ticket .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        @php
            $tiempo = \Carbon\Carbon::parse($reserva->timestamp)->format('d/m/Y');
            $nombre_user = $reserva->user->name;
            $minutos = $reserva->minutos_totales;
            $nombre_reunion = $reserva->valor_nombre_reunion;
            $horas = \Carbon\Carbon::createFromTimestamp($reserva->timestamp)->format('H:i').  ' - ' .
             \Carbon\Carbon::createFromTimestamp($reserva->timestamp)->addMinutes($reserva->minutos_totales)->format('H:i') ;
             $observaciones = $reserva->observacione ?? 'Ninguna';
        @endphp
        <h1>Reserva</h1>
        <p><strong>Fecha:</strong> {{ $tiempo }}</p>
        <p><strong>Usuario:</strong> {{ $nombre_user }}</p>
        <p><strong>Horas:</strong> {{ $horas }}</p>
        <p><strong>Nombre de la reunión:</strong> {{ $nombre_reunion }}</p>
        <p><strong>Minutos:</strong> {{ $minutos }}</p>
        <p><strong>Observaciones:</strong> {{ $observaciones }}</p>
        <div class="footer">
            <p>¡Gracias por su reserva!</p>
        </div>
    </div>
</body>
</html>
<script>
    window.print();
</script>