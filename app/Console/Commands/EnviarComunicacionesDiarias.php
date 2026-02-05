<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Empleado;
use App\Models\Feriado;
use Illuminate\Support\Facades\DB;
use App\Mail\ComunicacionesDiarias;
use App\Mail\CumpleanerosDiarios;
use App\Mail\EfemeridesDiarias;
use Illuminate\Support\Facades\Mail;
use PDF; // Importar el Facade de Snappy
// use Illuminate\Support\Facades\File; // Ya no es necesario
use Illuminate\Support\Facades\Storage; // Para manejar el almacenamiento de archivos


class EnviarComunicacionesDiarias extends Command
{
    protected $signature = 'app:comunicaciones-diarias';
    protected $description = 'Envía las comunicaciones diarias (cumpleaños de hoy y efemérides).';

    public function handle()
    {
        $this->info('Iniciando Proceso de Comunicaciones Diarias...');

        $today = now();
        $mailingList = 'lrojas@granjaboraure.com'; // ⚠️ REEMPLAZAR

        // --- 1. Cumpleaños del Día ---
        $cumpleanerosHoy = Empleado::where('status', 'A')
            ->whereRaw('MONTH(fecha_nac) = ?', [$today->month])
            ->whereRaw('DAY(fecha_nac) = ?', [$today->day])
            ->get();

        if ($cumpleanerosHoy->isNotEmpty()) {
            $this->info('Generando reporte y correo para ' . $cumpleanerosHoy->count() . ' cumpleañero(s) de hoy.');

            // 1. Generar el contenido BINARIO del PDF con Snappy
            $pdfContent = PDF::loadView('pdfs.cumpleaneros-diarios-pdf', [
                'cumpleaneros' => $cumpleanerosHoy, 
                'hoy' => $today
            ])
            // Opcional: Para evitar el error de ProtocolUnknownError, se recomienda habilitar 
            // el acceso a archivos locales si la plantilla usa public_path()
            ->setOptions(['enable-local-file-access' => true])
            ->output(); // <-- Obtener el contenido binario (string)
            
            // 2. Definir rutas de almacenamiento
            $pdfFileName = 'cumpleaneros_diarios_' . $today->format('Ymd') . '.pdf';
            $tempPath = 'public/temp_pdfs/'; // Directorio temporal
            $fullPathInStorage = $tempPath . $pdfFileName;
            
            // 3. Guardar el archivo en el storage temporal
            // Storage::put() guarda el archivo y crea el directorio si no existe.
            Storage::put($fullPathInStorage, $pdfContent); 
            
            // 4. Obtener la ruta ABSOLUTA y correcta para adjuntar
            $pdfPath = Storage::path($fullPathInStorage);
            
            // Enviar Correo de Cumpleaños
            try {
                // Pasar el path ABSOLUTO al Mailable
                Mail::to($mailingList)->send(new CumpleanerosDiarios($cumpleanerosHoy, $pdfPath));
                $this->info('Correo de Cumpleaños Diario enviado exitosamente.');
            } catch (\Exception $e) {
                $this->error('Error al enviar correo de cumpleaños: ' . $e->getMessage());
            } finally {
                // 5. Limpieza (borrar el PDF temporal usando Storage)
                Storage::delete($fullPathInStorage);
            }
        } else {
            $this->info('No hay cumpleaños que celebrar hoy.');
        }

        // --- 2. Efemérides / Feriados del Día (Esta sección NO cambia) ---
        $feriadosHoy = Feriado::whereDate('fecha', $today->toDateString())->get();

        if ($feriadosHoy->isNotEmpty()) {
            $this->info('Enviando correo de efemérides con ' . $feriadosHoy->count() . ' evento(s).');
            // Enviar Correo de Efemérides
            try {
                Mail::to($mailingList)->send(new EfemeridesDiarias($feriadosHoy));
                $this->info('Correo de Efemérides Diario enviado exitosamente.');
            } catch (\Exception $e) {
                $this->error('Error al enviar correo de efemérides: ' . $e->getMessage());
            }
        } else {
            $this->info('No hay efemérides ni feriados hoy.');
        }

        $this->info('Proceso de comunicaciones diarias finalizado.');
        return 0;
    }
}