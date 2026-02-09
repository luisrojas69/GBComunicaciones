<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Gerencial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #557A4C; /* Color corporativo oscuro */
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #e9e9e9;
            color: #777777;
            text-align: center;
            padding: 15px;
            font-size: 12px;
        }
        .message-box {
            background-color: #f9f9f9;
            border-left: 5px solid #4BD528; /* Línea de destaque */
            padding: 15px;
            margin-top: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Sistema de Indicadores Gerenciales</h2>
        </div>
        <div class="content">
            <p>Estimado equipo gerencial,</p>
            
            {{-- El mensaje que pasamos desde el Mailable --}}
            <div class="message-box">
                <p><strong>{{ $mensaje ?? 'Reporte automático adjunto.' }}</strong></p>
                <p>Favor revisar el archivo PDF adjunto a este correo, donde se consolida la información clave de los módulos Administrativo y Nómina.</p>
            </div>

            <p>Los indicadores incluidos son:</p>
            <ul>
                <li>Costo de Nómina y Personal en Vacaciones.</li>
                <li>Estado de Requisiciones Aprobadas.</li>
                <li>Artículos Críticos en Inventario.</li>
                <li>Ranking de Ventas por Producto.</li>
                <li>Personal de Vacaciones.</li>
                <li>Cumpleañeros de la Semana.</li>
            </ul>

        </div>
        <div class="footer">
            <p style="color: #666;">Este es un mensaje generado automáticamente. Por favor, no responda a este correo.</p>
            <hr>
            <p style="color: #888; font-size: 12px;">
                El reporte adjunto ha sido generado automáticamente sin supervision activa, por lo que algunos indicadores podrían presentar variaciones o contener errores, La información contenida es de carácter informativo y puede estar sujeta a revisiones. Para soporte técnico o aclaratorias, contacte al Departamento de Sistemas. (Ing. Luis Rojas)
            </p>
        </div>
    </div>
</body>
</html>