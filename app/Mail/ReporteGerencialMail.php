<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ReporteGerencialMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array $reporteData - Todos los datos de los indicadores.
     * @param string $pdfPath - La ruta temporal del archivo PDF.
     */
    public function __construct(
        public array $reporteData,
        public string $pdfPath
    ) {
    }

    /**
     * Obtiene la envolvente del mensaje.
     */
    public function envelope(): Envelope
    {
        $periodo = $this->reporteData['periodo'] ?? Carbon::now()->format('Y-m-d');

        return new Envelope(
            subject: '📊 Reporte Gerencial Consolidado - ' . $periodo,
        );
    }

    /**
     * Obtiene la definición del contenido del mensaje.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reporte_base', // La vista del cuerpo del correo (no el PDF)
            with: [
                'mensaje' => 'Adjunto encontrará el reporte gerencial (Claude - Gemini) consolidado del período ' . $this->reporteData['periodo'] . '.',
            ],
        );
    }

    /**
     * Obtiene el array de adjuntos del mensaje.
     */
    public function attachments(): array
    {
        $fechaReporte = Carbon::now()->format('Ymd');
        $fileName = "Reporte_Gerencial_{$fechaReporte}.pdf";

        return [
            // Adjuntamos el PDF desde la ruta que nos fue pasada en el constructor
            Attachment::fromPath($this->pdfPath)
                ->as($fileName)
                ->withMime('application/pdf'),
        ];
    }
}