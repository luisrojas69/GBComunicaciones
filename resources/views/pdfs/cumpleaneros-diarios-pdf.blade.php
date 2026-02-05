<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>¡Feliz Cumpleaños!</title>
    
    <style type="text/css">
        /* Estilos generales */
        body { 
            font-family: Arial, sans-serif; 
            /* CLAVE 1: Mantenemos el margen en 0 y aplicaremos un padding sutil al body 
               para forzar el borde a caber en la zona imprimible del PDF. */
            margin: 0; 
            padding: 5px; /* Pequeño margen de seguridad para la hoja */
            background-color: #f7f7f7; 
        }

        /* Contenedor principal de la tarjeta (SOLUCIÓN DE DESBORDAMIENTO) */
        .birthday-card {
            /* CLAVE 2: Reducimos el ancho al 97% del BODY */
            width: 97%; 
            margin: 0 auto; /* Centramos en el BODY */
            
            height: auto; /* Dejamos que el contenido defina la altura */
            text-align: center;
            box-sizing: border-box;
            position: relative;
            
            /* Borde Verde */
            border: 10px solid #557A4C; 
            border-radius: 10px; 
            
            /* Fondo interno */
            background: linear-gradient(180deg, #ffffff 0%, #f0fbf0 100%); 
        }
        
        /* Control de salto de página */
        .page-breaker {
            page-break-after: always;
        }

        /* Contenido: AJUSTE DE PADDING INTERNO FINAL */
        .content {
            padding: 20px 50px 15px 50px; /* Padding interno suficiente */
            box-sizing: border-box;
            background-color: transparent;
        }

        /* Títulos */
        .title-small {
            font-size: 16px;
            color: #557A4C; 
            margin-bottom: 5px;
            font-weight: bold;
        }
        .title-large {
            font-size: 45px; 
            color: #557A4C;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 5px;
        }

        /* Nombre del Empleado */
        .employee-name {
            font-size: 28px; 
            color: #2F4F4F; 
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 25px; 
            text-transform: uppercase;
        }
        
        /* Contenedor de Cupcake */
        .cupcake-image-container {
            margin: 20px 0;
        }

        /* Mensaje */
        .message-box {
            margin: 30px auto 30px auto; 
            max-width: 500px; 
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        
        /* Logo de la Empresa: TAMAÑO AUMENTADO */
        .logo-container {
            margin-top: 30px;
            text-align: center;
        }
        .logo {
            width: 200px; 
            max-width: 100%;
        }

    </style>
</head>
<body>

@foreach ($cumpleaneros as $index => $empleado)

    <div class="birthday-card">

        <div class="content">
            <div class="title-small">Hoy es un día especial.!!!</div>

            <h1 class="title-large">Feliz Cumpleaños</h1>

            <h2 class="employee-name">{{ $empleado->nombre_completo }}</h2>

            <div class="cupcake-image-container">
                <img src="{{ public_path('images/image-1.png') }}" alt="Cupcake de Cumpleaños" style="width: 50%; max-width: 350px; display: block; margin: 0 auto;"/>
            </div>

            <div class="message-box">
                <p style="text-align: center; margin: 0px;">
                    En nombre de todo el equipo de <strong>GRANJA BORAURE C.A.</strong>, queremos detenernos un momento para celebrar tu vida, tu trayectoria y, especialmente, el impacto positivo que generas cada día en nuestro ambiente de trabajo.
                </p>
            </div>

            <div style="font-size: 18px; line-height: 100%; text-align: center; padding: 10px 0;">
                <strong>¡Feliz Cumpleaños!</strong>
            </div>

            <div class="logo-container">
                <div style="font-size: 14px; margin-bottom: 5px;">Somos Granja Boraure</div>
                <img src="{{ public_path('images/logo.png') }}" alt="Logo Granja Boraure" class="logo"/>
            </div>
            
        </div>
    </div>
    
    @if (!$loop->last)
        <div class="page-breaker"></div>
    @endif
    
@endforeach

</body>
</html>