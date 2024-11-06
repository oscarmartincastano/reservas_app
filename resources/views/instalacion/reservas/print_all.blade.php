<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas del {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .reservas {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .reservas th, .reservas td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .reservas th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reservas del {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}</h1>
    </div>
    @foreach($reservas->groupBy(function($reserva) {
        return \Carbon\Carbon::parse($reserva->timestamp)->format('d/m/Y');
    }) as $fecha => $reservasPorFecha)
        <h2>Reservas del día {{ $fecha }}</h2>
        <table class="reservas">
            <thead>
                <tr>
                    <th>Sala</th>
                    <th>Nombre de la Reserva</th>
                    <th>Hora</th>
                    <th>Duración</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach($reservasPorFecha as $reserva)
                    <tr>
                       
                        <td>{{ $reserva->pista->nombre }}</td>
                        <td>{{ $reserva->user->name }} @if ($reserva->valor_nombre_reunion != "---") | {{$reserva->valor_nombre_reunion}} @endif</td>
                        <td>{{ \Carbon\Carbon::parse($reserva->timestamp)->format('H:i') }}</td>
                        <td>{{ $reserva->minutos_totales }} minutos</td>
                        <td>{{ $reserva->getComentariosAttribute() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
<script>
    window.print();
    setTimeout(function() {
        window.location.href = '/ceco/admin';
    }, 1000);
</script>