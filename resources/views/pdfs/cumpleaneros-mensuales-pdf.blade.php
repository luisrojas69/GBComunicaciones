<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cartelera de Cumpleañeros del Mes</title>
    
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
            padding: 30px; /* Margen interno para la impresión */
            box-sizing: border-box;
            border: 10px solid #557A4C; /* Borde Verde Oliva */
            border-radius: 15px;
            min-height: 98vh; /* Asegura que ocupe la mayor parte de la página */
            margin: 5px auto; /* Pequeño margen para evitar el desbordamiento */
        }

        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* ----------------------- */
        /* ENCABEZADO Y TÍTULOS */
        /* ----------------------- */
        .header-cartelera {
            background-color: #557A4C; 
            color: white;
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .header-cartelera h1 {
            font-size: 35px;
            margin: 0;
            text-transform: uppercase;
        }
        .header-cartelera h2 {
            font-size: 50px;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        
        /* ----------------------- */
        /* LISTA DE CUMPLEAÑEROS */
        /* ----------------------- */
        .list-container {
            display: block; /* Aseguramos que sea un bloque para Dompdf */
        }
        .birthday-item {
            width: 95%;
            display: block; /* Cambiado de flex */
            padding: 18px;
            margin-bottom: 12px;
            background-color: #f9f9f9;
            border-radius: 10px;
            overflow: hidden; /* Limpia el float */
        }
        .birthday-item:last-child {
            border-bottom: none;
        }

        /* Cambia el círculo del día */
        .day {
            background-color: #FBB03B;
            color: #333;
            border-radius: 35px; /* Mitad de width/height para ser círculo */
            width: 70px;
            height: 70px;
            display: inline-block; /* Cambiado de flex */
            vertical-align: middle;
            margin-right: 25px;
            text-align: center; /* Centra el número */
        }

        .day-number {
            font-size: 26px;
            font-weight: bold;
            display: block;
            padding-top: 18px; /* Ajuste manual para centrar verticalmente */
        }

        .day-label {
            font-size: 12px;
            text-transform: uppercase;
        }

        .details {
            display: inline-block; /* Cambiado de flex */
            vertical-align: middle;
            width: 75%; /* Ajusta según el espacio disponible */
            text-align: left;
        }
        .details .name {
            font-size: 30px;
            font-weight: bold;
            color: #2F4F4F; /* Gris oscuro para el nombre */
            margin: 0;
        }
        .details .info {
            font-size: 20px;
            color: #555;
            margin: 5px 0 0;
        }
        
        /* ----------------------- */
        /* PIE DE PÁGINA */
        /* ----------------------- */
        .footer-cartelera {
            text-align: center;
            margin-top: 50px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
            font-size: 20px;
            color: #6c757d;
        }
        .logo-footer {
            width: 180px; 
            margin-top: 20px;
            display: block; 
            margin-left: auto; 
            margin-right: auto;
        }

        .title-with-icons {
            text-align: center;
            display: block;
        }

        .title-icon {
            width: 40px;
            height: 40px;
            vertical-align: middle;
        }

        .icon-inline {
            width: 24px;
            height: 24px;
            vertical-align: middle;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="cartelera-container">
        <div class="header-cartelera">
            <h1 class="title-with-icons">
                <img src="{{ public_path('images/confetti.png') }}" class="title-icon">
                Cartelera de Cumpleañeros
                <img src="{{ public_path('images/confetti.png') }}" class="title-icon">
            </h1>
            <h2>{{ $hoy->isoFormat('MMMM [del] YYYY') }}</h2>
        </div>

        <div class="list-container">
            @foreach ($cumpleanerosMes as $empleado)
                <div class="birthday-item">
                    <div class="day">
                        <span class="day-number">{{ $empleado->fecha_nac->format('d') }}</span>
                    </div>
                    
                    <div class="details">
                        <p class="name">
                            <img src="{{ public_path('images/cake.png') }}" class="icon-inline" style="width:24px; vertical-align:middle;">
                            {{ $empleado->nombre_completo }}
                        </p>
                        <p class="info">
                            ¡A celebrarlo! En nombre de Granja Boraure.
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="footer-cartelera">
            <p>¡Gracias por ser parte de la familia Granja Boraure C.A.</p>
            <img src="{{ public_path('images/logo.png') }}" alt="Logo Granja Boraure" class="logo-footer">
        </div>
    </div>
</body>
</html>