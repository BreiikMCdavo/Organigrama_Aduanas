<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #243044;
            background: #f8fafc;
        }

        .header {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #0a1628;
            color: #fff;
            padding: 14px 18px;
        }

        h1 {
            margin: 0;
            font-size: 17px;
        }

        .meta {
            margin-top: 4px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th {
            position: sticky;
            top: 54px;
            background: #1a3a6b;
            color: #fff;
            font-size: 11px;
            padding: 8px 7px;
            text-align: left;
            white-space: nowrap;
        }

        td {
            border-bottom: 1px solid #e6edf5;
            font-size: 11px;
            padding: 7px;
            vertical-align: top;
        }

        tr:nth-child(even) td {
            background: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="meta">
            Vista previa: primeras {{ min($limit, $total) }} filas de {{ $total }} registros. La descarga incluye todo.
        </div>
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
</body>
</html>
