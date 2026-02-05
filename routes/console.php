<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\EnviarComunicacionesDiarias; // Importa tu comando diario
use App\Console\Commands\EnviarCumpleanerosMensual; // Importa tu comando mensual


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// 1. Tarea Diaria (Cumpleaños de hoy, Aniversarios y Efemérides)
Schedule::command(EnviarComunicacionesDiarias::class)
    ->dailyAt('08:00') // Ejecutar todos los días a las 8:00 AM
    ->timezone('America/Caracas') // ⚠️ OPCIONAL: Asegura la hora correcta
    ->withoutOverlapping(); // Evita que se ejecute dos veces a la vez

// 2. Tarea Mensual (Reporte para cartelera y cumpleaños del mes)
Schedule::command(EnviarCumpleanerosMensual::class)
    ->monthlyOn(1, '08:00') // Ejecutar el primer día de cada mes a las 8:00 AM
    ->timezone('America/Caracas') // ⚠️ OPCIONAL: Asegura la hora correcta
    ->withoutOverlapping(); // Evita ejecuciones duplicadas


    // Aquí ya debes tener tus comandos de cumpleañeros y efemérides.
    // Añade el nuevo comando para el reporte gerencial:
    Schedule::command(EnviarReporteGerencial::class)
             ->weekly() // Ejecutar semanalmente
             ->mondays() // Específicamente, los lunes
             ->at('08:00'); // A las 8:00 AM
