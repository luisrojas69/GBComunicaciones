<?php

// app/Console/Commands/EnviarReporteGerencial.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReporteGerencialMail;
use App\Services\ReporteGerencialService; // <-- Asegúrate de importar el Service
// ... (imports y use statements)
use PDF; // Importar el Facade de Snappy
use Illuminate\Support\Facades\Storage; // Para manejar el almacenamiento de archivos

class EnviarReporteGerencial extends Command
{
    protected $signature = 'report:send-gerencial';
    protected $description = 'Envía el reporte gerencial consolidado en PDF al tren gerencial.';
    
    protected $reporteService;

    // 💡 Inyectamos el Servicio para obtener los datos
    public function __construct(ReporteGerencialService $reporteService)
    {
        parent::__construct();
        $this->reporteService = $reporteService;
    }

    public function handle()
    {
        $this->info('Iniciando generación y envío del Reporte Gerencial...');
        
        // 1. Definir Período
        $fechaFin = Carbon::now()->subDay()->format('Y-m-d');
        $fechaInicio = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'); 
        
        // 2. Obtener los Datos
        $reporteData = $this->reporteService->generarDatosReporte($fechaInicio, $fechaFin);
        
        // --- NUEVA LÓGICA DE GENERACIÓN Y ALMACENAMIENTO DEL PDF ---
       $pdf = PDF::loadView('pdfs.gerencial_fontawesome', ['data' => $reporteData])
            ->setPaper('A4', 'portrait')
            ->setOption('enable-local-file-access', true)
            ->setOption('enable-javascript', true)
            ->setOption('no-stop-slow-scripts', true)
            ->setOption('javascript-delay', 1000)
            ->setOption('encoding', 'UTF-8')
            ->output(); 
          
        $tempFileName = 'temp_reporte_' . time() . '.pdf';

        // 1. Guardar el archivo en el storage temporal
        // Esto se guarda en storage/app/public/temp_pdfs/
        Storage::put('public/temp_pdfs/' . $tempFileName, $pdf);

        // 2. Obtener la ruta ABSOLUTA y correcta usando Storage::path()
        // Esto garantiza que el path sea el mismo que Storage::put() usó
        $pdfPath = Storage::path('public/temp_pdfs/' . $tempFileName); 
        // -----------------------------------------------------------

        // 3. Destinatarios
        $destinatarios = 'lrojas@granjaboraure.com'; // ⚠️ REEMPLAZAR
        //$destinatarios = ['gerencia.general@tudominio.com', 'gerencia.admin@tudominio.com'];

        try {
            // 4. Enviar el Mailable (usando la ruta ABSOLUTA)
            $mailable = new ReporteGerencialMail($reporteData, $pdfPath);
            Mail::to($destinatarios)->send($mailable);
            
            $this->info('✅ Reporte Gerencial enviado con éxito.');
        } catch (\Exception $e) {
        // ...
        }

        // 5. LIMPIEZA: Eliminar el archivo temporal
        Storage::delete('public/temp_pdfs/' . $tempFileName);

        return 0;
    }
}
