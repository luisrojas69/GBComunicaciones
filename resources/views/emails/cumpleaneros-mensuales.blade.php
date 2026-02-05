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
        font-size: 24px;
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
        background-color: #F0FBF0; /* Fondo más claro (Verde muy sutil) */
        border: 2px solid #557A4C; /* Borde principal */
        border-radius: 8px;
        padding: 15px 25px;
        margin-top: 25px;
        margin-bottom: 25px;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.05);
    }
    .section-title {
        color: #557A4C;
        font-size: 20px;
        text-align: center;
        margin-top: 0;
        margin-bottom: 15px;
        font-weight: bold;
    }
    ul {
        list-style-type: none; /* Quitamos los puntos de lista */
        padding: 0;
        text-align: left;
    }
    li {
        padding: 8px 0;
        border-bottom: 1px dashed #e0e0e0;
        font-size: 16px;
        color: #333;
    }
    li:last-child {
        border-bottom: none;
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
        <h1>🗓️ Cumpleaños del Mes de {{ $hoy->isoFormat('MMMM') }}</h1>
        <p>Planifica tus felicitaciones con antelación.</p>
    </div>
    
    <div class="content">
        <p style="font-size: 16px; color: #333;">¡Hola a todos!</p>
        <p style="font-size: 16px; color: #333; margin-bottom: 30px;">
            Aquí está la lista de compañeros que celebrarán su cumpleaños durante el mes de **{{ $hoy->isoFormat('MMMM') }}**. ¡Prepárense para celebrar y mostrar nuestro aprecio!
        </p>

        <div class="section">
            <h3 class="section-title">🎉 Próximos Festejos 🎉</h3>
            <ul>
                @foreach ($cumpleanerosMes as $empleado)
                    <li>
                        <span style="color: #557A4C; font-weight: bold;">
                            {{ $empleado->fecha_nac->format('d') }} de {{ $hoy->isoFormat('MMMM') }}:
                        </span> 
                        {{ $empleado->nombre_completo }}
                    </li>
                @endforeach
            </ul>
        </div>
        
        <p style="text-align: center; font-style: italic; margin-top: 30px;">
            Gracias por mantener un ambiente de celebración y compañerismo en **GRANJA BORAURE C.A.**
        </p>
    </div>

    <div class="footer">
        <p style="font-size: 14px; margin-bottom: 5px; color: #555;">Somos Granja Boraure</p>
        <img src="{{ asset('images/logo.png') }}" alt="Logo Granja Boraure" class="logo">
    </div>
</div>