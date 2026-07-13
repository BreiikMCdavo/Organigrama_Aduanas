<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Consultoría - Servidores Públicos</title>
    <style>
        @page {
            margin: 15mm 10mm;
            @bottom-center {
                content: "Página " counter(page) " de " counter(pages);
                font-size: 9px;
                color: #666;
            }
        }
        body {
            font-family: "DejaVu Sans", "Segoe UI", sans-serif;
            font-size: 9px;
            color: #333;
        }
        .header {
            background: #0a1628;
            color: white;
            padding: 18px 20px;
            margin-bottom: 18px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-align: center;
        }
        .header .subtitle {
            text-align: center;
            font-size: 11px;
            margin-top: 6px;
            opacity: 0.85;
        }
        .info-cards {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }
        .info-cards td {
            width: 33%;
            background: #f5f7fa;
            padding: 10px 12px;
            border-left: 3px solid #1a3a6b;
            font-size: 10px;
        }
        .info-cards td h3 {
            margin: 0 0 4px 0;
            font-size: 10px;
            color: #555;
        }
        .info-cards td .value {
            font-size: 13px;
            font-weight: 700;
            color: #0a1628;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }
        thead {
            display: table-header-group;
        }
        tr {
            page-break-inside: avoid;
        }
        th {
            background: #0a1628;
            color: white;
            padding: 7px 6px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            text-align: left;
        }
        td {
            border-bottom: 1px solid #e0e0e0;
            padding: 5px 6px;
            font-size: 8.5px;
        }
        tr:nth-child(even) td {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #888;
            padding: 12px;
            border-top: 1px solid #ddd;
        }
        .footer .total {
            font-size: 12px;
            font-weight: 700;
            color: #0a1628;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>REPORTE DE SERVIDORES DE CONSULTORÍA</h1>
        <div class="subtitle">
            Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
            Sistema de Gestión de Servidores Públicos
        </div>
    </div>

    <table class="info-cards">
        <tr>
            <td><h3>TOTAL DE SERVIDORES</h3><div class="value">{{ $reportTotal ?? $servidores->count() }}</div></td>
            <td><h3>FECHA DE GENERACIÓN</h3><div class="value" style="font-size:11px;">{{ now()->format('d/m/Y H:i:s') }}</div></td>
            <td><h3>TIPO DE REPORTE</h3><div class="value" style="font-size:11px;">CONSULTORÍA</div></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>UNIDAD</th>
                <th>SUB-UNIDAD</th>
                <th>CONTRATO</th>
                <th>NOMBRE COMPLETO</th>
                <th>CARGO</th>
                <th>COD. FUNC.</th>
                <th>ESC. SALARIAL</th>
                <th>DESIGNACIÓN</th>
                <th>F. INICIO</th>
                <th>F. FIN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servidores as $i => $s)
            <tr>
                <td>{{ ($reportOffset ?? 0) + $i + 1 }}</td>
                <td>{{ $s->unidad ?? '—' }}</td>
                <td>{{ $s->sub_unidad ?? '—' }}</td>
                <td><strong>{{ $s->contrato_numero ?? '—' }}</strong></td>
                <td><strong>{{ $s->nombre }} {{ $s->apellido_paterno }} {{ $s->apellido_materno }}</strong></td>
                <td>{{ $s->cargo_consultoria ?? '—' }}</td>
                <td>{{ $s->cod_funcionario ?? '—' }}</td>
                <td>{{ $s->escala_salarial ? $s->escala_salarial.' Bs.' : '—' }}</td>
                <td>{{ $s->designacion ?? '—' }}</td>
                <td>{{ $s->fecha_inicio_contrato ? \Carbon\Carbon::parse($s->fecha_inicio_contrato)->format('d/m/Y') : '—' }}</td>
                <td>{{ $s->fecha_fin_contrato ? \Carbon\Carbon::parse($s->fecha_fin_contrato)->format('d/m/Y') : '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="total">TOTAL DE SERVIDORES DE CONSULTORÍA: {{ $reportTotal ?? $servidores->count() }}</div>
        Reporte generado automáticamente por el Sistema de Gestión de Servidores Públicos<br>
        Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
        &copy; {{ now()->year }} Todos los derechos reservados
    </div>

</body>
</html>
