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
                    @if ($formato == "excel")
                        <th>Día</th>                        
                    @endif
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
                        @if ($formato == "excel")
                            <td class="formatoFecha">{{ \Carbon\Carbon::parse($reserva->timestamp)->format('d/m/Y') }}</td>
                        @endif
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
@if ($formato == "pdf")
    <script>
        window.print();
        setTimeout(function() {
            window.location.href = '/ceco/admin';
        }, 1000);
    </script>
@elseif ($formato == "excel")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    {{-- cdn jquert --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            function formatFecha(fecha) {
                let partes = fecha.split('/');
                if (partes.length === 3) {
                    let dia = partes[0].padStart(2, '0');  // Asegurar dos dígitos en el día
                    let mes = partes[1].padStart(2, '0');  // Asegurar dos dígitos en el mes
                    let año = partes[2];
                    return `${dia}/${mes}/${año}`;
                }
                return fecha; // Si no es una fecha válida, devolver el valor original
            }

            var wb = XLSX.utils.book_new(); // Crear un nuevo libro de Excel
            var ws_data = []; // Datos para la hoja
            var isFirstTable = true; // Bandera para controlar la cabecera

            // Iterar sobre todas las tablas en el documento
            $('table').each(function (index, table) {
                var table_data = XLSX.utils.sheet_to_json(XLSX.utils.table_to_sheet(table), { header: 1 });

                if (!isFirstTable) {
                    table_data.shift(); // Eliminar la cabecera si no es la primera tabla
                } else {
                    isFirstTable = false; // Marcar que ya se procesó la primera tabla
                }

                // **Forzar que las fechas sean texto y tengan formato dd/mm/yyyy**
                table_data.forEach(function (row, rowIndex) {
                    row.forEach(function (cell, cellIndex) {
                        if (typeof cell === 'string' && /^\d{1,2}\/\d{1,2}\/\d{4}$/.test(cell)) {
                            row[cellIndex] = formatFecha(cell); // Asegurar el formato con ceros
                        } else if (typeof cell === 'number' && cell > 40000) { 
                            // Si detectamos un número que parece ser una fecha en formato de serie de Excel
                            var fecha_excel = new Date((cell - 25569) * 86400 * 1000); // Convertir de número a fecha
                            row[cellIndex] = formatFecha(fecha_excel.toLocaleDateString("es-ES")); // Aplicar formato con ceros
                        }
                    });
                });

                ws_data = ws_data.concat(table_data); // Agregar datos de la tabla
            });

            var ws = XLSX.utils.aoa_to_sheet(ws_data); // Convertir datos a una hoja

            // 🔹 **Aplicar formato de texto a las celdas de fecha**
            Object.keys(ws).forEach(cell => {
                if (ws[cell].t === 'n' && ws[cell].v > 40000) {
                    ws[cell].t = 's'; // Forzar tipo texto en la celda
                    ws[cell].v = formatFecha(new Date((ws[cell].v - 25569) * 86400 * 1000).toLocaleDateString("es-ES"));
                }
            });

            XLSX.utils.book_append_sheet(wb, ws, 'Reservas'); // Agregar la hoja al libro

            // Guardar el archivo Excel
            XLSX.writeFile(wb, 'reservas_{{ \Carbon\Carbon::parse($fecha_inicio)->format('d-m-Y') }}_{{ \Carbon\Carbon::parse($fecha_fin)->format('d-m-Y') }}.xlsx');
        });


        // setTimeout(function() {
        //     window.location.href = '/ceco/admin';
        // }, 100);
    </script>
@endif