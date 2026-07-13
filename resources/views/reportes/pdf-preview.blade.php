<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            padding: 18px;
            font-family: "DejaVu Sans", Arial, sans-serif;
            color: #253044;
            background: #e9eef5;
        }

        .sheet {
            min-height: calc(100vh - 36px);
            background: #fff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.14);
        }

        .header {
            background: #0a1628;
            color: #fff;
            padding: 18px 22px;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 18px;
        }

        .subtitle {
            margin-top: 6px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 12px;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: #dbe3ee;
        }

        .summary div {
            background: #f8fafc;
            padding: 12px;
            text-align: center;
            font-size: 12px;
        }

        .summary strong {
            display: block;
            color: #0a3267;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #1a3a6b;
            color: #fff;
            padding: 8px 7px;
            text-align: left;
            font-size: 11px;
        }

        td {
            border-bottom: 1px solid #e6edf5;
            padding: 7px;
            font-size: 11px;
            vertical-align: top;
        }

        tr:nth-child(even) td {
            background: #f8fafc;
        }

        .note {
            padding: 12px 16px;
            color: #64748b;
            font-size: 11px;
            border-top: 1px solid #e6edf5;
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            <h1>{{ $title }}</h1>
            <div class="subtitle">Vista previa rapida del reporte PDF</div>
        </div>

        <div class="summary">
            <div>Total registros <strong>{{ $total }}</strong></div>
            <div>Parte <strong>{{ $part }} / {{ $parts }}</strong></div>
            <div>Muestra <strong>{{ count($rows) }}</strong></div>
        </div>

        <table>
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell === '' || $cell === null ? '-' : $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="note">
            Esta vista carga solo una muestra para abrir rapido. El boton de descarga genera el PDF completo de la parte seleccionada.
        </div>
    </div>
</body>
</html>
