<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Reporte Consolidado de Comunicaciones</title>
    
    <style>
        /* Estilos generales para el PDF */
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            color: #333; 
            background-color: #ffffff; 
        }
        /* Contenedor principal que simula un borde de cartelera */
        .cartelera-container {
            width: 100%;
            padding: 20px; /* Margen interno para la impresión */
            box-sizing: border-box;
            border: 10px solid #557A4C; /* Borde Verde Oliva */
            border-radius: 15px;
            min-height: 98vh; 
            margin: 5px auto; 
        }
        
        /* ----------------------- */
        /* ENCABEZADO Y TÍTULOS */
        /* ----------------------- */
        .header-cartelera {
            background-color: #557A4C; 
            color: white;
            padding: 25px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .header-cartelera h1 {
            font-size: 30px;
            margin: 0;
            text-transform: uppercase;
        }
        .header-cartelera h2 {
            font-size: 45px;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        
        /* ----------------------- */
        /* SECCIONES Y LISTAS */
        /* ----------------------- */
        .section-header {
            font-size: 28px;
            color: #557A4C;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 3px solid #FBB03B; /* Línea de acento Dorado */
            padding-bottom: 5px;
            text-align: left;
        }
        .section-box {
            background-color: #f8fff7; /* Fondo verde muy sutil */
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            page-break-inside: avoid; /* Evitar que las secciones se corten */
        }

        .list-item {
            display: flex;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px dashed #eee;
        }
        .list-item:last-child {
            border-bottom: none;
        }

        .date-badge {
            background-color: #FBB03B;
            color: #333;
            font-size: 18px;
            font-weight: bold;
            padding: 5px 12px;
            border-radius: 5px;
            margin-right: 15px;
            min-width: 80px;
            text-align: center;
        }
        .item-details {
            text-align: left;
        }
        .item-details .name {
            font-size: 22px;
            font-weight: bold;
            color: #2F4F4F;
            margin: 0;
        }
        .item-details .info {
            font-size: 16px;
            color: #555;
            margin: 2px 0 0;
        }
        
        /* ----------------------- */
        /* PIE DE PÁGINA */
        /* ----------------------- */
        .footer-cartelera {
            text-align: center;
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
            font-size: 18px;
            color: #6c757d;
        }
        .logo-footer {
            width: 150px; 
            margin-top: 15px;
            display: block; 
            margin-left: auto; 
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="cartelera-container">
        <div class="header-cartelera">
            <h1>📰 Reporte de Comunicaciones Consolidado</h1>
            <h2>{{ $hoy->isoFormat('D [de] MMMM [del] YYYY') }}</h2>
        </div>
        
        @if ($cumpleanerosHoy->isNotEmpty())
            <h3 class="section-header">🎂 Cumpleaños de Hoy</h3>
            <div class="section-box">
                @foreach ($cumpleanerosHoy as $empleado)
                    <div class="list-item">
                        <div class="date-badge" style="background-color: #557A4C; color: white;">HOY</div>
                        <div class="item-details">
                            <p class="name">{{ $empleado->nombre_completo }}</p>
                            <p class="info">¡Todo el equipo te desea un día maravilloso!</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($aniversariosHoy->isNotEmpty())
            <h3 class="section-header">🏆 Aniversarios Laborales</h3>
            <div class="section-box">
                @foreach ($aniversariosHoy as $empleado)
                    <div class="list-item">
                        <div class="date-badge">{{ $hoy->year - $empleado->fecha_ing->year }} AÑOS</div>
                        <div class="item-details">
                            <p class="name">{{ $empleado->nombre_completo }}</p>
                            <p class="info">¡Gracias por tu dedicación desde {{ $empleado->fecha_ing->format('Y') }}!</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        @if ($feriadosHoy->isNotEmpty())
            <h3 class="section-header">📅 Efemérides y Días Especiales</h3>
            <div class="section-box">
                @foreach ($feriadosHoy as $feriado)
                    <div class="list-item">
                        <div class="date-badge" style="background-color: #eee;">DÍA</div>
                        <div class="item-details">
                            <p class="name">{{ $feriado->descripcion }}</p>
                            <p class="info">Motivo de reflexión o celebración.</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($cumpleanerosMes->isNotEmpty())
            <h3 class="section-header">🎈 Próximos Cumpleaños ({{ $hoy->isoFormat('MMMM') }})</h3>
            <div class="section-box">
                @foreach ($cumpleanerosMes as $empleado)
                    <div class="list-item">
                        <div class="date-badge">{{ $empleado->fecha_nac->format('d') }}</div>
                        <div class="item-details">
                            <p class="name">{{ $empleado->nombre_completo }}</p>
                            <p class="info">¡Marca la fecha en tu calendario!</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($cumpleanerosHoy->isEmpty() && $aniversariosHoy->isEmpty() && $feriadosHoy->isEmpty() && $cumpleanerosMes->isEmpty())
             <div style="text-align: center; padding: 50px; background-color: #f0f0f0; border-radius: 5px;">
                <p style="font-size: 22px; color: #555; margin: 0;">
                    No hay eventos especiales que celebrar o anunciar hoy.
                </p>
            </div>
        @endif


        <div class="footer-cartelera">
            <p>Reporte generado por el Equipo de RRHH - Granja Boraure C.A.</p>
            <img src="{{ public_path('images/logo.png') }}" alt="Logo Granja Boraure" class="logo-footer">
        </div>
    </div>
</body>
</html>