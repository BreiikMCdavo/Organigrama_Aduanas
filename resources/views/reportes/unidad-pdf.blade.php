<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle ?? 'Reporte por unidad' }}</title>
    <style>
        @page {
            margin: 12mm 8mm;
        }

        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 8px;
            color: #263238;
        }

        .header {
            background: #0a1628;
            color: #fff;
            padding: 12px 14px;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 15px;
            text-align: center;
        }

        .subtitle {
            margin-top: 4px;
            text-align: center;
            font-size: 9px;
        }

        .info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info td {
            width: 33%;
            background: #f5f7fa;
            border-left: 3px solid #1a3a6b;
            padding: 7px 8px;
            font-size: 9px;
        }

        .info strong {
            display: block;
            color: #0a1628;
            font-size: 11px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        .data th {
            background: #1a3a6b;
            color: #fff;
            padding: 5px 4px;
            text-align: left;
            font-size: 7px;
        }

        .data td {
            border-bottom: 1px solid #dde3ea;
            padding: 4px;
            font-size: 7px;
            vertical-align: top;
        }

        .data tr:nth-child(even) td {
            background: #f8fafc;
        }

        .footer {
            margin-top: 12px;
            border-top: 1px solid #d8dee8;
            padding-top: 8px;
            text-align: center;
            color: #687386;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $reportTitle ?? 'Reporte por unidad' }}</h1>
        <div class="subtitle">
            Gerencia Regional La Paz - Aduana Nacional de Bolivia
            @if(($reportParts ?? 1) > 1)
                <br>Parte {{ $reportPart }} de {{ $reportParts }}
            @endif
        </div>
    </div>

    <table class="info">
        <tr>
            <td><span>Total registros</span><strong>{{ $reportTotal ?? $servidores->count() }}</strong></td>
            <td><span>Fecha</span><strong>{{ now()->format('d/m/Y H:i') }}</strong></td>
            <td><span>Parte</span><strong>{{ $reportPart ?? 1 }} / {{ $reportParts ?? 1 }}</strong></td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No.</th>
                <th>TIPO</th>
                <th>SUB-UNIDAD</th>
                <th>No. ITEM / CONTRATO</th>
                <th>NOMBRE COMPLETO</th>
                <th>CARGO</th>
                <th>COD. FUNC.</th>
                <th>ESC. SALARIAL</th>
                <th>DESIGNACION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servidores as $i => $s)
                <tr>
                    <td>{{ ($reportOffset ?? 0) + $i + 1 }}</td>
                    <td>{{ $s->tipo === 'item' ? 'ITEM' : 'CONSULTORIA' }}</td>
                    <td>{{ $s->sub_unidad ?? '-' }}</td>
                    <td>{{ $s->tipo === 'item' ? ($s->numero_item ?? '-') : ($s->contrato_numero ?? '-') }}</td>
                    <td>{{ trim(($s->nombre ?? '') . ' ' . ($s->apellido_paterno ?? '') . ' ' . ($s->apellido_materno ?? '')) ?: '-' }}</td>
                    <td>{{ $s->tipo === 'item' ? ($s->cargo ?? '-') : ($s->cargo_consultoria ?? '-') }}</td>
                    <td>{{ $s->cod_funcionario ?? '-' }}</td>
                    <td>{{ $s->escala_salarial ? $s->escala_salarial . ' Bs.' : '-' }}</td>
                    <td>{{ $s->designacion ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Reporte generado por el Sistema de Gestion de Servidores Publicos
    </div>
</body>
</html>
