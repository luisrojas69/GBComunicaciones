<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        /* ---------------------------------------------------- */
        /* ESTILOS INLINE PARA MÁXIMA COMPATIBILIDAD CON EMAIL */
        /* ---------------------------------------------------- */
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f7f7f7; 
            margin: 0; 
            padding: 0; 
        }
        .container { 
            max-width: 600px; 
            margin: 20px auto; 
            background-color: #ffffff; 
            border-radius: 8px; 
            overflow: hidden; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        
        /* COLOR PRIMARIO: VERDE OLIVA DE GRANJA BORAURE (#557A4C) */
        .header { 
            background-color: #557A4C; 
            color: #ffffff; 
            padding: 20px; 
            text-align: center; 
        }
        .header img { 
            max-width: 150px; 
            margin-bottom: 10px; 
            display: block; 
            margin-left: auto; 
            margin-right: auto;
        } 
        .header h1 { 
            margin: 0; 
            font-size: 24px; 
        }
        .header p {
            margin: 5px 0 0 0; 
            font-size: 14px; 
            opacity: 0.9;
        }

        .content { 
            padding: 25px 30px; 
            color: #333333; 
            line-height: 1.6; 
        }
        
        /* Estilos de Sección Unificados */
        .section { 
            background-color: #f8fff7; /* Fondo verde muy sutil */
            border-left: 5px solid #557A4C; 
            padding: 15px; 
            margin-bottom: 15px; 
            border-radius: 4px; 
            border-top: 1px solid #e0e0e0;
        }
        .section-title { 
            font-size: 18px; 
            color: #557A4C; 
            margin-top: 0; 
            margin-bottom: 10px; 
            display: flex; 
            align-items: center; 
            font-weight: bold;
        }
        
        /* Listas y Listas de Items */
        ul { 
            list-style: none; 
            padding: 0; 
            margin: 0; 
        }
        li { 
            margin-bottom: 5px; 
            padding-left: 25px; 
            position: relative;
            font-size: 15px; 
        }
        li::before { 
            content: '🌿'; /* Icono de lista acorde a la marca agrícola */
            position: absolute; 
            left: 0; 
            top: 0; 
        } 
        
        /* Botón de Intranet */
        .button { 
            display: inline-block; 
            background-color: #2F4F4F; /* Gris oscuro para mayor contraste */
            color: #ffffff !important; 
            padding: 10px 20px; 
            border-radius: 5px; 
            text-decoration: none; 
            margin-top: 15px;
            font-weight: bold;
        }

        .footer { 
            background-color: #e9e9e9; 
            color: #777777; 
            padding: 15px; 
            text-align: center; 
            font-size: 12px; 
            border-top: 1px solid #e0e0e0; 
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Granja Boraure" style="width: 150px; height: auto;">
            
            <h1>Comunicaciones Internas de Hoy</h1>
            <p>{{ $hoy->isoFormat('dddd D [de] MMMM [del] YYYY') }}</p>
        </div>
        
        <div class="content">
            <p>¡Hola a todos!</p>
            <p style="margin-bottom: 20px;">El equipo de RRHH les trae las últimas novedades y celebraciones de nuestra gran familia agrícola.</p>

            @if ($feriadosHoy->isNotEmpty())
                <div class="section">
                    <h3 class="section-title">📅 Día Especial:</h3>
                    <ul>
                        @foreach ($feriadosHoy as $feriado)
                            <li style="list-style: none; padding-left: 0;">
                                <strong style="color: #455a3f;">{{ $feriado->descripcion }}</strong> - ¡Que tengas un día reflexivo/celebratorio!
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($cumpleanerosHoy->isNotEmpty())
                <div class="section">
                    <h3 class="section-title">🎂 ¡Feliz Cumpleaños a nuestros Compañeros de Hoy!</h3>
                    <ul>
                        @foreach ($cumpleanerosHoy as $empleado)
                            <li>{{ $empleado->nombre_completo }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($aniversariosHoy->isNotEmpty())
                <div class="section">
                    <h3 class="section-title">🏆 🎉 ¡Aniversarios Laborales! 🎉</h3>
                    <ul>
                        @foreach ($aniversariosHoy as $empleado)
                            <li>
                                <strong>{{ $empleado->nombre_completo }}</strong> - Celebramos {{ $hoy->year - $empleado->fecha_ing->year }} Años
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($cumpleanerosMes->isNotEmpty())
                <div class="section" style="border-left-color: #2F4F4F;">
                    <h3 class="section-title" style="color: #2F4F4F;">🎈 Próximos Cumpleaños del Mes de {{ $hoy->isoFormat('MMMM') }}:</h3>
                    <ul>
                        @foreach ($cumpleanerosMes as $empleado)
                            <li>{{ $empleado->nombre_completo }} ({{ $empleado->fecha_nac->format('d') }})</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($cumpleanerosHoy->isEmpty() && $aniversariosHoy->isEmpty() && $feriadosHoy->isEmpty() && $cumpleanerosMes->isEmpty())
                <p style="text-align: center; padding: 15px; background-color: #f0f0f0; border-radius: 4px;">
                    No hay eventos especiales que celebrar hoy, ¡pero siempre hay motivos para ser un gran equipo!
                </p>
            @endif

            <p style="text-align: center; margin-top: 30px;">
                <a href="https://tuempresa.com/intranet" class="button">Visita nuestra Intranet</a>
            </p>
        </div>
        
        <div class="footer">
            <p>Este es un mensaje automático de Granja Boraure C.A. Por favor, no respondas a este correo.</p>
            <p>&copy; {{ date('Y') }} Granja Boraure C.A. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>