<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection; // Importar Collection

class CumpleanerosDiarios extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $cumpleanerosHoy,
        public string $pdfPath
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🥳 ¡Feliz Cumpleaños! - ' . now()->isoFormat('dddd D [de] MMMM'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cumpleaneros-diarios',
            with: [
                'cumpleanerosHoy' => $this->cumpleanerosHoy,
                'hoy' => now(),
            ],
        );
    }

    public function attachments(): array
    {
        $fileName = 'Cumpleaneros_Hoy_' . date('Ymd') . '.pdf';

        return [
            Attachment::fromPath($this->pdfPath)
                ->as($fileName)
                ->withMime('application/pdf'),
        ];
    }
}