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
        background-color: #557A4C; /* Verde Oliva */
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
        background-color: #f8fff7; /* Fondo verde muy pálido para efemérides */
        border: 1px solid #557A4C;
        border-left: 5px solid #557A4C; /* Borde izquierdo resaltante */
        border-radius: 4px;
        text-align: left;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.05);
    }
    .section-title {
        color: #557A4C;
        font-size: 18px;
        margin-top: 0;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .footer {
        text-align: center;
        padding-top: 20px;
    }
    .logo {
        width: 150px;
        max-width: 100%;
        height: auto;
    }
</style>

<div class="container">
    <div class="header">
        <h1>⭐ Efemérides de Hoy</h1>
        <p>{{ $hoy->isoFormat('D [de] MMMM [del] YYYY') }}</p>
    </div>
    
    <div class="content">
        @if ($feriadosHoy->isNotEmpty())
            <p style="font-size: 16px; color: #333;">
                ¡Tenemos eventos especiales hoy! Aquí están las efemérides y feriados que conmemoramos en esta fecha:
            </p>
            
            @foreach ($feriadosHoy as $feriado)
                <div class="section">
                    <h3 class="section-title">✨ {{ $feriado->descripcion }}</h3>
                    <p style="color: #555; margin: 0;">
                        Tomemos un momento para reflexionar sobre este día especial.
                    </p>
                </div>
            @endforeach
            
        @else
            <div style="text-align: center; padding: 20px; background-color: #f0f0f0; border-radius: 5px;">
                <p style="font-size: 16px; color: #555; margin: 0;">
                    El sistema detectó que no hay eventos especiales o feriados importantes que conmemorar en la base de datos hoy.
                </p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p style="font-size: 14px; margin-bottom: 5px; color: #555;">Somos Granja Boraure</p>
        <img src="{{ asset('images/logo.png') }}" alt="Logo Granja Boraure" class="logo">
    </div>
</div>