<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection; // Importar Collection


class CumpleanerosMensuales extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $cumpleanerosMes,
        public string $pdfPath // <-- NUEVO: Path del PDF a adjuntar
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🗓️ Próximos Cumpleaños del Mes de ' . now()->isoFormat('MMMM'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cumpleaneros-mensuales',
            with: [
                'cumpleanerosMes' => $this->cumpleanerosMes,
                'hoy' => now(),
            ],
        );
    }

    public function attachments(): array
    {
        $fileName = 'Cartelera_Cumpleaneros_' . now()->isoFormat('MMMM') . '.pdf';

        return [
            // Adjuntar el PDF generado
            Attachment::fromPath($this->pdfPath)
                ->as($fileName)
                ->withMime('application/pdf'),
        ];
    }
}