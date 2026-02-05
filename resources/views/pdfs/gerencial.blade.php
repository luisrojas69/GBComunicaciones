<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte Gerencial Consolidado</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style>
        /* ========= Configuración de página ========= */
        @page {
            size: A4 portrait;
            margin: 15mm 12mm;
        }

        /* ========= Reset & Base (SIN BOX-SIZING para compatibilidad) ========= */
        * {
            margin: 0;
            padding: 0;
        }

        html, body {
            font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #212529;
            line-height: 1.5;
            font-size: 9pt;
            background: #fff;
        }

        /* ========= Header Profesional (FIXED con Table Layout) ========= */
        .report-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 24mm;
            background: #2c3e50; /* Fallback sólido */
            background: -webkit-gradient(linear, left top, right bottom, from(#2c3e50), to(#34495e));
            background: -webkit-linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            -webkit-box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 1000;
        }

        .header-content {
            display: table;
            width: 100%;
            height: 100%;
            padding: 0 12mm;
            table-layout: fixed;
        }

        .header-left, .header-center, .header-right {
            display: table-cell;
            vertical-align: middle;
        }

        .header-left {
            width: 25%;
        }

        .header-center {
            width: 50%;
            text-align: center;
        }

        .header-right {
            width: 25%;
            text-align: right;
        }

        .logo-container img {
            max-height: 16mm;
            max-width: 100%;
            -webkit-filter: brightness(0) invert(1);
            filter: brightness(0) invert(1);
        }

        .header-title {
            color: #fff;
            font-size: 13pt;
            font-weight: 700;
            margin: 0 0 1mm 0;
            letter-spacing: -0.3px;
        }

        .header-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 8pt;
            font-weight: 400;
        }

        .header-meta {
            color: rgba(255,255,255,0.85);
            font-size: 7.5pt;
            line-height: 1.4;
        }

        .page-number {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 2px 8px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            font-weight: 600;
            margin-top: 2mm;
        }

        /* ========= Footer Profesional ========= */
        .report-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 12mm;
            background: #f1f3f5;
            border-top: 2px solid #3498db;
            z-index: 1000;
        }

        .footer-content {
            display: table;
            width: 100%;
            height: 100%;
            padding: 0 12mm;
        }

        .footer-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            color: #6c757d;
            font-size: 7.5pt;
        }

        /* ========= Contenido Principal ========= */
        .report-content {
            margin-top: 28mm;
            margin-bottom: 16mm;
            padding: 0 2mm;
        }

        /* ========= Section Title ========= */
        .section-title {
            font-size: 11pt;
            font-weight: 700;
            color: #2c3e50;
            margin: 6mm 0 4mm 0;
            padding-bottom: 2mm;
            border-bottom: 2px solid #3498db;
            position: relative;
        }

        .section-title::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 40px;
            height: 2px;
            background: #2c3e50;
        }

        /* ========= Grid System (FLOAT BASED) ========= */
        .row {
            display: block;
            width: 100%;
            margin-bottom: 4mm;
            page-break-inside: avoid;
        }

        .row::after {
            content: '';
            display: table;
            clear: both;
        }

        .col-6 {
            float: left;
            width: 48%;
            margin-left: 1%;
            margin-right: 1%;
        }

        .col-12 {
            float: left;
            width: 98%;
            margin-left: 1%;
            margin-right: 1%;
        }

        /* ========= Cards Modernos ========= */
        .card {
            background: #fff;
            border: 1px solid #dee2e6;
            -webkit-border-radius: 6px;
            border-radius: 6px;
            -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 4mm;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .card-header {
            background: #f8f9fa; /* Fallback sólido */
            background: -webkit-gradient(linear, left top, right top, from(#f8f9fa), to(#fff));
            background: -webkit-linear-gradient(left, #f8f9fa, #fff);
            padding: 3.5mm 4mm;
            border-bottom: 1px solid #dee2e6;
            font-weight: 700;
            font-size: 9.5pt;
            color: #2c3e50;
        }

        /* Table para simular Flexbox en header */
        .card-header-wrapper {
            display: table;
            width: 100%;
        }

        .card-header-title {
            display: table-cell;
            vertical-align: middle;
            width: 70%;
        }

        .card-header-badge {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 30%;
        }

        .card-icon {
            font-size: 11pt;
            margin-right: 2mm;
        }

        .card-body {
            padding: 4mm;
        }

        /* ========= KPI Box (TABLE LAYOUT en lugar de FLEX) ========= */
        .kpi-container {
            display: table;
            width: 100%;
            padding: 3mm 0;
        }

        .kpi-icon-cell {
            display: table-cell;
            vertical-align: middle;
            width: 18mm;
        }

        .kpi-icon {
            display: block;
            width: 14mm;
            height: 14mm;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            background: #c0392b; /* Fallback sólido */
            background: -webkit-gradient(linear, left top, right bottom, from(#c0392b), to(#e74c3c));
            background: -webkit-linear-gradient(135deg, #c0392b, #e74c3c);
            text-align: center;
            line-height: 14mm;
            font-size: 16pt;
            -webkit-box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
        }

        .kpi-content-cell {
            display: table-cell;
            vertical-align: middle;
            padding-left: 4mm;
        }

        .kpi-label {
            font-size: 8pt;
            color: #6c757d;
            margin-bottom: 1mm;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 600;
        }

        .kpi-value {
            font-size: 18pt;
            font-weight: 700;
            color: #e74c3c;
            line-height: 1;
        }

        /* ========= Badges Profesionales ========= */
        .badge {
            display: inline-block;
            padding: 1.5mm 3mm;
            font-size: 7pt;
            font-weight: 600;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-primary {
            background: #3498db;
            color: #fff;
        }

        .badge-success {
            background: #27ae60;
            color: #fff;
        }

        .badge-warning {
            background: #f39c12;
            color: #212529;
        }

        .badge-danger {
            background: #e74c3c;
            color: #fff;
        }

        .badge-info {
            background: #16a085;
            color: #fff;
        }

        /* ========= Tablas Mejoradas ========= */
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-top: 2mm;
        }

        .table thead th {
            background: #e9ecef; /* Fallback sólido */
            background: -webkit-gradient(linear, left top, left bottom, from(#f1f3f5), to(#e9ecef));
            background: -webkit-linear-gradient(top, #f1f3f5, #e9ecef);
            color: #2c3e50;
            font-weight: 700;
            padding: 2.5mm 3mm;
            text-align: left;
            border: 1px solid #dee2e6;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .table tbody td {
            padding: 2.5mm 3mm;
            border: 1px solid #dee2e6;
            background: #fff;
        }

        .table-striped tbody tr:nth-child(even) td {
            background: #f8f9fa;
        }

        /* ========= Highlights ========= */
        .highlight-row td {
            background: #fffbf0 !important;
            border-left: 3px solid #f39c12 !important;
        }

        .danger-row td {
            background: #fff5f5 !important;
            border-left: 3px solid #e74c3c !important;
        }

        /* ========= Progress Bar Moderno ========= */
        .progress-container {
            margin-top: 4mm;
            text-align: center;
        }

        .progress-label {
            font-size: 8.5pt;
            color: #495057;
            margin-bottom: 2mm;
            font-weight: 600;
        }

        .progress-bar-wrapper {
            width: 85%;
            margin: 0 auto;
            background: #e9ecef;
            -webkit-border-radius: 10px;
            border-radius: 10px;
            height: 8mm;
            overflow: hidden;
            -webkit-box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
            position: relative;
        }

        .progress-bar {
            height: 100%;
            text-align: center;
            line-height: 8mm;
            font-weight: 700;
            font-size: 9pt;
            color: #fff;
            -webkit-border-radius: 10px;
            border-radius: 10px;
            position: relative;
            float: left;
        }

        .progress-success {
            background: #27ae60; /* Fallback sólido */
            background: -webkit-gradient(linear, left top, right bottom, from(#2ecc71), to(#27ae60));
            background: -webkit-linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .progress-warning {
            background: #f39c12; /* Fallback sólido */
            background: -webkit-gradient(linear, left top, right bottom, from(#f1c40f), to(#f39c12));
            background: -webkit-linear-gradient(135deg, #f1c40f, #f39c12);
            color: #212529;
        }

        .progress-danger {
            background: #e74c3c; /* Fallback sólido */
            background: -webkit-gradient(linear, left top, right bottom, from(#e74c3c), to(#c0392b));
            background: -webkit-linear-gradient(135deg, #e74c3c, #c0392b);
        }

        /* ========= Métricas Grid (TABLE LAYOUT) ========= */
        .metrics-grid {
            display: table;
            width: 90%;
            margin: 0 auto 4mm;
            border-collapse: collapse;
        }

        .metric-row {
            display: table-row;
        }

        .metric-label, .metric-value, .metric-badge {
            display: table-cell;
            padding: 2.5mm 3mm;
            border-bottom: 1px solid #e9ecef;
        }

        .metric-label {
            font-weight: 600;
            color: #495057;
            width: 60%;
        }

        .metric-value {
            text-align: right;
            font-weight: 700;
            width: 20%;
        }

        .metric-badge {
            text-align: center;
            width: 20%;
        }

        /* ========= Status Messages ========= */
        .status-message {
            padding: 3mm;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            font-size: 8.5pt;
            font-weight: 600;
            margin: 2mm 0;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
            border-left: 3px solid #27ae60;
        }

        .status-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 3px solid #f39c12;
        }

        .status-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 3px solid #e74c3c;
        }

        /* ========= Avatares ========= */
        .avatar-circle {
            width: 10mm;
            height: 10mm;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            border: 2px solid #dee2e6;
            -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.15);
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }

        .avatar-large {
            width: 14mm;
            height: 14mm;
        }

        /* ========= Utilities ========= */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: 700; }
        .fw-semibold { font-weight: 600; }
        .text-muted { color: #6c757d; }
        .text-primary { color: #2c3e50; }
        .text-success { color: #27ae60; }
        .text-danger { color: #e74c3c; }
        .text-warning { color: #f39c12; }
        
        .mb-2 { margin-bottom: 2mm; }
        .mb-3 { margin-bottom: 3mm; }
        .mb-4 { margin-bottom: 4mm; }
        .mt-2 { margin-top: 2mm; }
        .mt-3 { margin-top: 3mm; }

        /* ========= Specific Tweaks ========= */
        .top-rank {
            display: inline-block;
            width: 6mm;
            height: 6mm;
            line-height: 6mm;
            text-align: center;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            font-weight: 700;
            font-size: 7.5pt;
        }

        .rank-1 {
            background: #FFD700; /* Fallback sólido */
            background: -webkit-gradient(linear, left top, right bottom, from(#FFD700), to(#FFA500));
            background: -webkit-linear-gradient(135deg, #FFD700, #FFA500);
            color: #fff;
        }

        .rank-2 {
            background: #C0C0C0; /* Fallback sólido */
            background: -webkit-gradient(linear, left top, right bottom, from(#C0C0C0), to(#A8A8A8));
            background: -webkit-linear-gradient(135deg, #C0C0C0, #A8A8A8);
            color: #fff;
        }

        .rank-3 {
            background: #CD7F32; /* Fallback sólido */
            background: -webkit-gradient(linear, left top, right bottom, from(#CD7F32), to(#B8732F));
            background: -webkit-linear-gradient(135deg, #CD7F32, #B8732F);
            color: #fff;
        }

        .deficit-alert {
            background: #e74c3c;
            color: #fff;
            padding: 1mm 2mm;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            font-weight: 700;
            font-size: 8pt;
        }

        /* ========= Clearfix Helper ========= */
        .clearfix::after {
            content: '';
            display: table;
            clear: both;
        }
    </style>
</head>
<body>

    {{-- ========= HEADER ========= --}}
    <header class="report-header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo-container">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo Empresa">
                </div>
            </div>
            <div class="header-center">
                <h1 class="header-title">Reporte Gerencial Consolidado</h1>
                <p class="header-subtitle">
                    Período: <strong>{{ $data['periodo'] ?? 'No Definido' }}</strong> - Generado el: <strong>{{ date('Y-m-d H:i:s') }}</strong>
                </p>
            </div>
            <div class="header-right">
                <div class="header-meta">
                    {{ date('d/m/Y') }}<br>
                    {{ date('h:i A') }}
                </div>
                <div class="page-number page-counter">
                    Pág. <span class="page"></span>/<span class="pages"></span>
                </div>
            </div>
        </div>
    </header>

    {{-- ========= FOOTER ========= --}}
    <footer class="report-footer">
        <div class="footer-content">
            <div class="footer-text">
                <strong>Confidencial</strong> — Generado por el Sistema de Reportes Gerenciales
            </div>
        </div>
    </footer>

    {{-- ========= CONTENIDO ========= --}}
    <main class="report-content">
        <br><br><br><br><br><br>
        

        <h2 class="section-title">📊 Indicadores Clave de Rendimiento</h2>

        {{-- ROW 1: Nómina y Ventas Top --}}
        <div class="row clearfix">
            {{-- Nómina --}}
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-wrapper">
                            <div class="card-header-title">
                                <span class="card-icon">💰</span>
                                <span>Módulo Nómina</span>
                            </div>
                            <div class="card-header-badge">
                                <span class="badge badge-danger">Gasto Total</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="kpi-container">
                            <div class="kpi-icon-cell">
                                <span class="kpi-icon">$</span>
                            </div>
                            <div class="kpi-content-cell">
                                <div class="kpi-label">Costo Total Asignaciones</div>
                                <div class="kpi-value">Bs. {{ number_format($data['costo_nomina'] ?? 0, 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ventas Top --}}
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-wrapper">
                            <div class="card-header-title">
                                <span class="card-icon">📈</span>
                                <span>Top Productos</span>
                            </div>
                            <div class="card-header-badge">
                                <span class="badge badge-primary">Mejores Ventas</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (!empty($data['ventas_top']) && $data['ventas_top']->count() > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 12%;">Rank</th>
                                        <th style="width: 53%;">Descripción</th>
                                        <th class="text-right" style="width: 15%;">Cantidad </th>
                                        <th class="text-right" style="width: 20%;">Monto (Bs.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['ventas_top'] as $i => $v)
                                        <tr @if($i < 3) class="highlight-row" @endif>
                                            <td class="text-center">
                                                @if($i == 0)
                                                    <span class="top-rank rank-1">1</span>
                                                @elseif($i == 1)
                                                    <span class="top-rank rank-2">2</span>
                                                @elseif($i == 2)
                                                    <span class="top-rank rank-3">3</span>
                                                @else
                                                    <span class="fw-semibold">{{ $i + 1 }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $v->art_des }}</td>
                                            <td class="text-right fw-bold text-primary">{{ number_format($v->CantidadVendida, 0, ',', '.') }}</td>
                                            <td class="text-right fw-bold text-primary">{{ number_format($v->MontoTotal, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="status-message status-warning">
                                ℹ️ No se registraron ventas en el período seleccionado
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 2: Requisiciones --}}
        <div class="row clearfix">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-wrapper">
                            <div class="card-header-title">
                                <span class="card-icon">⚙️</span>
                                <span>Cumplimiento de Requisiciones</span>
                            </div>
                            <div class="card-header-badge">
                                <span class="badge badge-info">Eficiencia Compras</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $requisiciones = $data['requisiciones'];
                            $totalAprobadas = $requisiciones['total_aprobadas'] ?? 0;
                            $parcial = $requisiciones['cumplimiento']['detalle']['Parcialmente Procesada'] ?? 0;
                            $sinProcesar = $requisiciones['cumplimiento']['detalle']['Sin Procesar'] ?? 0;
                            $procesadas = $requisiciones['cumplimiento']['procesadas'] ?? 0;
                            $completadas = $procesadas - $parcial;
                            $porcentaje = $requisiciones['cumplimiento']['porcentaje_ponderado'] ?? 0;
                            
                            if ($porcentaje >= 70) {
                                $claseProgreso = 'progress-success';
                            } elseif ($porcentaje >= 50) {
                                $claseProgreso = 'progress-warning';
                            } else {
                                $claseProgreso = 'progress-danger';
                            }
                        @endphp

                        <div class="metrics-grid">
                            <div class="metric-row">
                                <div class="metric-label">Total Requisiciones Aprobadas</div>
                                <div class="metric-value text-primary">{{ number_format($totalAprobadas, 0, ',', '.') }}</div>
                                <div class="metric-badge">—</div>
                            </div>
                            <div class="metric-row">
                                <div class="metric-label">Procesadas Completamente</div>
                                <div class="metric-value text-success">{{ number_format($completadas, 0, ',', '.') }}</div>
                                <div class="metric-badge"><span class="badge badge-success">Cumplido</span></div>
                            </div>
                            <div class="metric-row">
                                <div class="metric-label">Parcialmente Procesadas</div>
                                <div class="metric-value text-warning">{{ number_format($parcial, 0, ',', '.') }}</div>
                                <div class="metric-badge"><span class="badge badge-warning">Pendiente</span></div>
                            </div>
                            <div class="metric-row">
                                <div class="metric-label">Sin Procesar</div>
                                <div class="metric-value text-danger">{{ number_format($sinProcesar, 0, ',', '.') }}</div>
                                <div class="metric-badge"><span class="badge badge-danger">Bloqueado</span></div>
                            </div>
                        </div>

                        <div class="progress-container">
                            <div class="progress-label">Porcentaje de Cumplimiento Ponderado</div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-bar {{ $claseProgreso }}" style="width: {{ $porcentaje }}%;">
                                    {{ number_format($porcentaje, 1, ',', '.') }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3: Inventario Crítico --}}
        <div class="row clearfix">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-wrapper">
                            <div class="card-header-title">
                                <span class="card-icon">🚨</span>
                                <span>Inventario Crítico</span>
                            </div>
                            <div class="card-header-badge">
                                <span class="badge badge-danger">Riesgo Stock</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (!empty($data['criticos']) && $data['criticos']->count() > 0)
                            <div class="status-message status-danger mb-3">
                                ⚠️ <strong>{{ $data['criticos']->count() }}</strong> artículos requieren reposición urgente
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Código</th>
                                        <th style="width: 45%;">Descripción</th>
                                        <th class="text-right" style="width: 13%;">Stock Actual</th>
                                        <th class="text-right" style="width: 13%;">Stock Mín.</th>
                                        <th class="text-right" style="width: 14%;">Déficit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['criticos'] as $c)
                                        <tr class="danger-row">
                                            <td class="fw-semibold">{{ trim($c->co_art) }}</td>
                                            <td>{{ $c->art_des }}</td>
                                            <td class="text-right text-danger fw-bold">{{ $c->StockActual }}</td>
                                            <td class="text-right">{{ $c->stock_min }}</td>
                                            <td class="text-right">
                                                <span class="deficit-alert">-{{ $c->stock_min - $c->StockActual }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="status-message status-success">
                                ✅ Todos los artículos críticos mantienen stock suficiente
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <h2 class="section-title">👥 Gestión de Recursos Humanos</h2>

        {{-- ROW 4: Vacaciones y Cumpleaños --}}
        <div class="row clearfix">
            {{-- Vacaciones --}}
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-wrapper">
                            <div class="card-header-title">
                                <span class="card-icon">🏖️</span>
                                <span>Personal en Vacaciones</span>
                            </div>
                            <div class="card-header-badge">
                                <span class="badge badge-warning">Próximos 30 días</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (!empty($data['personal_vacaciones']) && $data['personal_vacaciones']->count() > 0)
                            <div class="status-message status-warning mb-3">
                                📅 <strong>{{ $data['personal_vacaciones']->count() }}</strong> colaboradores programados
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 22%;">Cédula</th>
                                        <th style="width: 43%;">Nombre</th>
                                        <th style="width: 22%;">Inicio</th>
                                        <th class="text-right" style="width: 13%;">Días</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['personal_vacaciones'] as $v)
                                        <tr>
                                            <td class="fw-semibold">{{ trim($v->ci) }}</td>
                                            <td>{{ $v->nombre_completo }}</td>
                                            <td>{{ $v->desde }}</td>
                                            <td class="text-right fw-bold text-primary">{{ $v->dias }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="status-message status-success">
                                ✅ Sin personal clave programado de vacaciones
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Cumpleaños --}}
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-wrapper">
                            <div class="card-header-title">
                                <span class="card-icon">🎂</span>
                                <span>Cumpleaños de la Semana</span>
                            </div>
                            <div class="card-header-badge">
                                <span class="badge badge-info">Clima Laboral</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (!empty($data['cumpleaneros_semana']) && $data['cumpleaneros_semana']->count() > 0)
                            <div class="status-message status-success mb-3">
                                🎉 <strong>{{ $data['cumpleaneros_semana']->count() }}</strong> colaboradores celebran esta semana
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;"></th>
                                        <th style="width: 40%;">Nombre</th>
                                        <th style="width: 25%;">Departamento</th>
                                        <th style="width: 20%;">Día</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['cumpleaneros_semana'] as $c)
                                        @php
                                            $avatar = (trim($c->sexo) === 'F') 
                                                ? public_path('images/avatar_mujer.svg') 
                                                : public_path('images/logo.png');
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <img src="{{ $avatar }}" alt="Avatar" class="avatar-circle">
                                            </td>
                                            <td class="fw-semibold">{{ $c->nombre_completo }}</td>
                                            <td class="text-muted">{{ $c->departamento ?? 'N/A' }}</td>
                                            <td class="fw-semibold text-primary">{{ $c->dia_semana }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="status-message status-success">
                                📆 Sin cumpleaños registrados para esta semana
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </main>

</body>
</html>