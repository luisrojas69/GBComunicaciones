<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empleado;
use Illuminate\Support\Facades\Mail;
use App\Mail\CumpleanerosMensuales;

use PDF; // <-- Facade de Snappy (asumiendo que está configurado el alias)
use Illuminate\Support\Facades\Storage; // <-- Necesario para manejo de archivos temporales

// Ya no es necesario importar File si usaremos solo Storage
// use Illuminate\Support\Facades\File; 

class EnviarCumpleanerosMensual extends Command
{
    protected $signature = 'app:cumpleaneros-mensual';
    protected $description = 'Envía el reporte mensual de los próximos cumpleaños.';

    public function handle()
    {
        $this->info('Iniciando generación y envío del Reporte Mensual de Cumpleaños...');

        $today = now();
        $mailingList = 'lrojas@granjaboraure.com'; // ⚠️ REEMPLAZAR

        $cumpleanerosMes = Empleado::where('status', 'A')
            ->whereRaw('MONTH(fecha_nac) = ?', [$today->month])
           // ->whereRaw('DAY(fecha_nac) != ?', [$today->day])
            ->orderByRaw('DAY(fecha_nac)') 
            ->get();

        if ($cumpleanerosMes->isEmpty()) {
            $this->info('No hay próximos cumpleaños en el mes. Tarea finalizada.');
            return 0;
        }

        // --- 1. Generar el PDF para Cartelera (USANDO SNAPPY) ---
        $data = [
            'cumpleanerosMes' => $cumpleanerosMes,
            'hoy' => $today,
        ];
        
        // Generar el contenido BINARIO del PDF con Snappy
        $pdfContent = PDF::loadView('pdfs.cumpleaneros-mensuales-pdf', $data)
                      ->setPaper('A4', 'portrait')
                      ->setOption('enable-local-file-access', true)
                      ->setOption('encoding', 'UTF-8')
                      ->output(); // <-- Snappy devuelve el contenido binario (string)


        $pdfFileName = 'cartelera_mensual_' . $today->format('Ym') . '.pdf';
        
        // Directorio temporal consistente con tu otro comando
        $tempPath = 'public/temp_pdfs/'; 
        $fullPathInStorage = $tempPath . $pdfFileName;

        // 1. Guardar el archivo en el storage temporal
        Storage::put($fullPathInStorage, $pdfContent); 

        // 2. Obtener la ruta ABSOLUTA y correcta para adjuntar en el Mailable
        $pdfPath = Storage::path($fullPathInStorage); 
        // -----------------------------------------------------------

        // --- 2. Enviar Correo Mensual con Adjunto ---
        try {
            // Pasar el path del PDF al Mailable
            Mail::to($mailingList)->send(new CumpleanerosMensuales($cumpleanerosMes, $pdfPath));
            $this->info('Correo de Cumpleaños Mensual (con PDF adjunto) enviado exitosamente.');
        } catch (\Exception $e) {
            $this->error('Error al enviar el correo mensual: ' . $e->getMessage());
        } finally {
            // --- 3. Limpiar (borrar el PDF temporal usando Storage) ---
            Storage::delete($fullPathInStorage);
        }

        return 0;
    }
}