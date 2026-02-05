@echo off
REM -- Ejecuta el Scheduler de Laravel cada minuto --

REM 1. Define la ruta al ejecutable de PHP
set PHP_EXECUTABLE=C:\xampp\php\php.exe

REM 2. Define la ruta a tu proyecto Laravel
set PROJECT_PATH=C:\xampp\htdocs\GBComunicaciones

REM 3. Cambia al directorio del proyecto
cd /d "%PROJECT_PATH%"

REM 4. Ejecuta el comando del Scheduler
"%PHP_EXECUTABLE%" artisan schedule:run