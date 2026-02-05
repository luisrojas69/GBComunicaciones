<!DOCTYPE html>
<html lang="es">
<html>
<head>
    <title>Reporte Gerencial Consolidado</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* ... Estilos CSS permanecen iguales ... */
        body { font-family: 'Arial', sans-serif; font-size: 10pt; margin: 0; padding: 0; }
        .header { background-color: #003366; color: white; padding: 15px; text-align: center; }
        .header h1 { margin: 0; font-size: 18pt; }
        .section { margin-top: 20px; padding: 10px; border-bottom: 2px solid #ddd; }
        .section h2 { color: #003366; border-left: 5px solid #ffcc00; padding-left: 10px; font-size: 14pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-size: 9pt; }
        .highlight { background-color: #fffacd; font-weight: bold; }
        .status-ok { color: green; font-weight: bold; }
        .status-alert { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte Gerencial Consolidado (Indicadores Profit Plus)</h1>
        <p>Período: {{ $data['periodo'] ?? 'No Definido' }} | Generado el: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    {{-- ======================================================================= --}}
    {{-- 1. NÓMINA --}}
    {{-- ======================================================================= --}}
    <div class="section">
        <h2><img src="{{ public_path('images/favicon.ico') }}" alt="Logo Granja Boraure" class="logo-footer"> - Módulo Nómina y Personal</h2>
        
        <h3>Costo Total de Nómina (Asignación)</h3>
        <p style="font-size: 16pt;" class="status-alert">
            Monto Total: {{ number_format($data['costo_nomina'] ?? 0, 2, ',', '.') }} Bs.
        </p>

        <h3>Personal en Vacaciones (Próximos 30 días)</h3>
        {{-- USAMOS ->count() porque el service debe devolver una Collection --}}
        @if (!empty($data['personal_vacaciones']) && $data['personal_vacaciones']->count() > 0)
            <table>
                <thead>
                    <tr><th>Cédula</th><th>Nombre</th><th>Inicio</th><th>Fin</th><th>Días</th></tr>
                </thead>
                <tbody>
                    @foreach ($data['personal_vacaciones'] as $v)
                        <tr>
                            {{-- USAMOS ->propiedad --}}
                            <td>{{ trim($v->ci) }}</td>
                            <td>{{ $v->nombre_completo }}</td>
                            <td>{{ $v->desde }}</td>
                            <td>{{ $v->hasta }}</td>
                            <td>{{ $v->dias }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="status-ok">✔️ No hay personal clave programado de vacaciones en el próximo mes.</p>
        @endif
    </div>

    {{-- ======================================================================= --}}
    {{-- 2. INVENTARIO CRÍTICO --}}
    {{-- ======================================================================= --}}
    <div class="section">
        <h2>📦 Módulo Inventario: Artículos Críticos</h2>
        {{-- USAMOS ->count() --}}
        @if (!empty($data['criticos']) && $data['criticos']->count() > 0)
            <p class="status-alert">❌ **ATENCIÓN:** Se requieren **{{ $data['criticos']->count() }}** artículos críticos. </p>
            <table>
                <thead>
                    <tr><th>Código</th><th>Descripción</th><th>Stock Actual</th><th>Stock Mínimo</th><th>Déficit</th></tr>
                </thead>
                <tbody>
                    @foreach ($data['criticos'] as $c)
                        <tr class="status-alert">
                            {{-- USAMOS ->propiedad (con los alias definidos en el Service) --}}
                            <td>{{ trim($c->co_art) }}</td>
                            <td>{{ $c->art_des }}</td>
                            <td style="text-align: right;">{{ $c->StockActual }}</td>
                            <td style="text-align: right;">{{ $c->stock_min }}</td>
                            <td style="text-align: right;">{{ $c->stock_min - $c->StockActual }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="status-ok">✔️ **Inventario Crítico:** Todos los artículos marcados cuentan con stock suficiente.</p>
        @endif
    </div>

    {{-- ======================================================================= --}}
    {{-- 3. VENTAS Y REQUISICIONES --}}
    {{-- ======================================================================= --}}
    <div class="section">
        <h2>📊 Módulo Administrativo</h2>

        {{-- Ventas Top --}}
        {{-- USAMOS ->count() --}}
        <h3>📈 Top {{ $data['ventas_top']->count() }} Productos por Monto de Venta</h3>
        @if (!empty($data['ventas_top']) && $data['ventas_top']->count() > 0)
            <table>
                <thead>
                    <tr><th>Rank</th><th>Código</th><th>Descripción</th><th style="text-align: right;">Cantidad</th><th style="text-align: right;">Monto Total</th></tr>
                </thead>
                <tbody>
                    @foreach ($data['ventas_top'] as $i => $v)
                        <tr @if ($i < 3) class="highlight" @endif>
                            <td>{{ $i + 1 }}</td>
                            {{-- USAMOS ->propiedad --}}
                            <td>{{ trim($v->co_art) }}</td>
                            <td>{{ $v->art_des }}</td>
                            <td style="text-align: right;">{{ number_format($v->CantidadVendida, 0, ',', '.') }}</td>
                            <td style="text-align: right;">{{ number_format($v->MontoTotal, 2, ',', '.') }} Bs.</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No se registraron ventas para el período seleccionado.</p>
        @endif

        <hr style="border: none; border-top: 1px dashed #ccc; margin: 15px 0;">

        {{-- Requisiciones --}}
        <h3>Estado de Cumplimiento de Requisiciones Aprobadas</h3>

        @php
            // La data viene del Service
            $requisiciones = $data['requisiciones'];

            $totalAprobadas = $requisiciones['total_aprobadas'] ?? 0;
            $procesadas = $requisiciones['cumplimiento']['procesadas'] ?? 0;
            $porcentaje = $requisiciones['cumplimiento']['porcentaje_ponderado'] ?? 0;

            // Accedemos a los subtotales individuales del 'detalle' si existen
            $detalle = $requisiciones['cumplimiento']['detalle'] ?? [];
            $parcial = $detalle['Parcialmente Procesada'] ?? 0;
            $sinProcesar = $detalle['Sin Procesar'] ?? 0;
            
            $estadoClase = $porcentaje < 80 ? 'status-alert' : 'status-ok'; // Usamos 80% como umbral de alerta
        @endphp

        <table style="width: 60%; margin-left: auto; margin-right: auto; text-align: right;">
            <thead>
                <tr>
                    <th style="text-align: left; background-color: #003366; color: white;">Métrica</th>
                    <th style="text-align: right; background-color: #003366; color: white;">Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr class="highlight">
                    <td style="text-align: left; font-weight: bold;">Total Requisiciones Aprobadas</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($totalAprobadas, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Total Procesadas (Completamente)</td>
                    <td style="text-align: right;">{{ number_format(($procesadas - $parcial), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Total Parcialmente Procesadas</td>
                    <td style="text-align: right;">{{ number_format($parcial, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Total Sin Procesar</td>
                    <td style="text-align: right;">{{ number_format($sinProcesar, 0, ',', '.') }}</td>
                </tr>
                <tr class="{{ $estadoClase }}" style="border-top: 2px solid #003366;">
                    <td style="text-align: left; font-weight: bold;">% de Cumplimiento (Procesadas)</td>
                    <td style="text-align: right; font-weight: bold; font-size: 11pt;">{{ number_format($porcentaje, 1, ',', '.') }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ======================================================================= --}}
    {{-- 4. CUMPLEAÑEROS (INDICADOR DE CLIMA) --}}
    {{-- ======================================================================= --}}
    <div class="section" style="border-top: 3px solid #ffcc00; border-bottom: none;">
        <h2>🎂 Indicador de Gestión Humana: Cumpleañeros de la Semana</h2>
        
        {{-- USAMOS ->count() porque el service debe devolver una Collection --}}
        @if (!empty($data['cumpleaneros_semana']) && $data['cumpleaneros_semana']->count() > 0)
            <p>El equipo celebra a **{{ $data['cumpleaneros_semana']->count() }}** colaboradores esta semana:</p>
            
            <table style="width: 70%;">
                <thead>
                    <tr>
                        <th style="width: 30%;">Nombre Completo</th>
                        <th style="width: 20%;">Departamento</th>
                        <th style="width: 20%;">Fecha Nac.</th>
                        <th style="width: 15%;">Día</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['cumpleaneros_semana'] as $c)
                        <tr>
                            {{-- Asumiendo estas propiedades de tu NominaService --}}
                            <td>{{ $c->nombre_completo }}</td> 
                            <td>{{ $c->departamento ?? 'N/A' }}</td>
                            <td>{{ $c->fecha_nac }}</td>
                            <td>{{ $c->dia_semana }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="status-ok">🥳 ¡Felicitaciones! No hay cumpleaños registrados para esta semana. </p>
        @endif
    </div>

</body>
</html>
