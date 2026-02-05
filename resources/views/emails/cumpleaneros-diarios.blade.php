<style>
    /* ---------------------------------------------------- */
    /* ESTILOS INLINE PARA MÁXIMA COMPATIBILIDAD CON EMAIL */
    /* ---------------------------------------------------- */
    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        font-family: Arial, sans-serif;
    }
    .header {
        text-align: center;
        padding: 20px 0;
        background-color: #557A4C; /* Verde Oliva del PDF */
        color: #ffffff;
        border-radius: 8px 8px 0 0;
    }
    .header h1 {
        margin: 0;
        font-size: 28px;
    }
    .header p {
        margin: 5px 0 0 0;
        font-size: 14px;
        opacity: 0.8;
    }
    .content {
        padding: 30px;
        background-color: #f8fcf8; /* Fondo muy claro */
        border: 1px solid #e0e0e0;
        border-top: none;
        border-radius: 0 0 8px 8px;
    }
    .section {
        background-color: #F0FBF0; /* Fondo más claro para la tarjeta */
        border: 1px solid #557A4C;
        border-radius: 8px;
        text-align: center;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.05);
    }
    .section-title {
        color: #557A4C;
        font-size: 22px;
        margin-top: 0;
        margin-bottom: 10px;
    }
    .button {
        display: inline-block;
        background-color: #2F4F4F; /* Gris oscuro para el botón */
        color: #ffffff !important;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }
    .footer {
            background-color: #e9e9e9;
            color: #777777;
            text-align: center;
            padding: 15px;
            font-size: 12px;
        }
    .logo {
        width: 150px; /* Tamaño similar al PDF */
        max-width: 100%;
        height: auto;
    }
</style>

<div class="container">
    <div class="header">
        <h1>🥳 ¡HOY ES DÍA DE FIESTA!</h1>
        <p>{{ $hoy->isoFormat('D [de] MMMM [del] YYYY') }}</p>
    </div>
    
    <div class="content">
        <p style="font-size: 16px; color: #333;">Querido equipo,</p>
        <p style="font-size: 16px; color: #333; margin-bottom: 30px;">
            Hoy celebramos el cumpleaños de **{{ $cumpleanerosHoy->count() }} compañero(s)**. ¡No olvides unirte a las felicitaciones y desearles un día espectacular!
        </p>

        @foreach ($cumpleanerosHoy as $empleado)
            <div class="section">
                <img src="{{ asset('images/image-1.png') }}" alt="Cupcake de Cumpleaños" style="width: 100px; height: auto; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;">
                
                <h3 class="section-title">🎂 ¡Feliz Día, {{ $empleado->nombre_completo }}! 🎂</h3>
                
                <p style="color: #555;">
                    Te deseamos un día lleno de alegría y éxito. Gracias por el compromiso y la **energía** que aportas a nuestro equipo.
                </p>
            </div>
        @endforeach

        <p style="text-align: center; font-style: italic; margin-top: 30px;">
            Adjunto encontrarás una tarjeta de felicitación en formato PDF para cada uno de nuestros cumpleañeros, con un diseño similar a este.
        </p>

        <p style="text-align: center; margin-top: 30px;">
            <a href="#" class="button">¡Enviar un Mensaje Rápido!</a>
        </p>
    </div>

    <div class="footer">
        <p>Este es un mensaje generado automáticamente. Por favor, no responder a este correo.</p>
    </div>
</div>