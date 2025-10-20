<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gastos Presupuestarios 2025</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #eee; }
        h2 { text-align: center; margin-top: 20px; margin-bottom: 10px; }
        .grafico { text-align: center; margin-bottom: 30px; }
        img { max-width: 400px; height: auto; }
        .logo { display: block; margin: 0 auto 20px; max-height: 80px; }
    </style>
</head>
<body>

    <!-- Logo Ayuntamiento -->
    <img src="{{ public_path('images/logo.png') }}" alt="Logo Ayuntamiento" class="logo">

    <h1 style="text-align:center;">Gastos Presupuestarios 2025</h1>

    <table>
        <thead>
            <tr>
                <th>Programa</th>
                <th>Económico</th>
                <th>Aplicación Presupuestaria</th>
                <th>Crédito 2024 (€)</th>
                <th>Crédito 2025 (€)</th>
                <th>Variación (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gastos as $gasto)
            <tr>
                <td>{{ $gasto->CODI_PROG }}</td>
                <td>{{ $gasto->CODI_ECON }}</td>
                <td>{{ $gasto->APLICACION_PRESUPUESTARIA }}</td>
                <td style="text-align:right;">{{ number_format($gasto->CR_INIC_2024, 2, ',', '.') }}</td>
                <td style="text-align:right;">{{ number_format($gasto->CR_INIC_2025, 2, ',', '.') }}</td>
                <td style="text-align:right;">
                    @if($gasto->VARIACION)
                        @if(strpos($gasto->VARIACION,'-') !== false)
                            <span style="color:red;">{{ $gasto->VARIACION }}</span>
                        @else
                            <span style="color:green;">+{{ $gasto->VARIACION }}</span>
                        @endif
                    @else
                        —
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($graficoPrograma)
    <div class="grafico">
        <h2>Presupuesto por Código de Programa</h2>
        <img src="{{ $graficoPrograma }}" alt="Gráfico Programa">
    </div>
    @endif

    @if($graficoEconomico)
    <div class="grafico">
        <h2>Presupuesto por Código Económico</h2>
        <img src="{{ $graficoEconomico }}" alt="Gráfico Económico">
    </div>
    @endif

</body>
</html>
