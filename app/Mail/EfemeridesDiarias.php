<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection; // Importar Collection

class EfemeridesDiarias extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $feriadosHoy
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = '📢 Efemérides del Día - ' . now()->isoFormat('dddd D [de] MMMM');
        if ($this->feriadosHoy->isNotEmpty()) {
            $subject = '🗓️ Día Especial: ' . $this->feriadosHoy->first()->descripcion;
        }
        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.efemerides-diarias',
            with: [
                'feriadosHoy' => $this->feriadosHoy,
                'hoy' => now(),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}