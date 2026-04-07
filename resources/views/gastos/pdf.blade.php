<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gastos Presupuestarios 2026</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; word-wrap: break-word; }
        th { background-color: #f8fafc; color: #475569; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        h1 { text-align: center; color: #1e293b; margin-bottom: 5px; }
        h2 { text-align: center; color: #64748b; font-size: 14px; margin-bottom: 20px; font-weight: normal; }
        .logo { display: block; margin: 0 auto 10px; max-height: 60px; }
        .text-right { text-align: right; }
        .variacion-positiva { color: #059669; font-weight: bold; }
        .variacion-negativa { color: #dc2626; font-weight: bold; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 20px; }
    </style>
</head>
<body>

    <img src="{{ public_path('images/logo.png') }}" alt="Logo Ayuntamiento" class="logo">

    <h1>Gastos Presupuestarios 2026</h1>
    <h2>Ayuntamiento de Almussafes - Informe de Gestión</h2>

    <table>
        <thead>
            <tr>
                <th width="12%">Prog.</th>
                <th width="12%">Econ.</th>
                <th width="26%">Aplicación Presupuestaria</th>
                <th width="14%" class="text-right">2024 (€)</th>
                <th width="14%" class="text-right">2025 (€)</th>
                <th width="14%" class="text-right">2026 (€)</th>
                <th width="8%" class="text-right">Var.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gastos as $gasto)
            <tr>
                <td>{{ $gasto->CODI_PROG }}</td>
                <td>{{ $gasto->CODI_ECON }}</td>
                <td style="font-size: 9px;">{{ $gasto->APLICACION_PRESUPUESTARIA }}</td>
                <td class="text-right">{{ number_format($gasto->CR_INIC_2024, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($gasto->CR_INIC_2025, 2, ',', '.') }}</td>
                <td class="text-right" style="background-color: #f1f5f9; font-weight: bold;">
                    {{ number_format($gasto->CR_INIC_2026, 2, ',', '.') }}
                </td>
                <td class="text-right">
                    @if($gasto->VARIACION !== null && $gasto->VARIACION !== '')
                        @if(floatval($gasto->VARIACION) < 0)
                            <span class="variacion-negativa">{{ $gasto->VARIACION }}%</span>
                        @else
                            <span class="variacion-positiva">+{{ $gasto->VARIACION }}%</span>
                        @endif
                    @else
                        —
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($graficoPrograma || $graficoEconomico)
    <div style="page-break-inside: avoid;">
        <table width="100%" style="margin-top: 20px; border: none;">
            <tr>
                @if($graficoPrograma)
                <td width="50%" style="vertical-align: top; text-align: center; border: none;">
                    <h3 style="font-size: 12px; margin-bottom: 10px;">Distribución por Programa (2026)</h3>
                    <img src="{{ $graficoPrograma }}" style="width: 90%; max-width: 300px;">
                </td>
                @endif

                @if($graficoEconomico)
                <td width="50%" style="vertical-align: top; text-align: center; border: none;">
                    <h3 style="font-size: 12px; margin-bottom: 10px;">Distribución Económica (2026)</h3>
                    <img src="{{ $graficoEconomico }}" style="width: 90%; max-width: 300px;">
                </td>
                @endif
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        Documento generado automáticamente el {{ date('d/m/Y H:i') }} <br>
        Sistema de Gestión Presupuestaria - Ejercicio 2026
    </div>

</body>
</html>