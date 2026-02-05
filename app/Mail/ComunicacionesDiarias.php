<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class ComunicacionesDiarias extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public array $data,
        public string $pdfPath
    ) {
        // Al usar 'public' en el constructor, Laravel asigna automáticamente las propiedades.
    }

    /**
     * Get the message envelope.
     */
   public function envelope(): Envelope
    {
        $subject = '📢 Comunicaciones Internas - ' . now()->isoFormat('dddd D [de] MMMM');
        if ($this->data['cumpleanerosHoy']->isNotEmpty() || $this->data['aniversariosHoy']->isNotEmpty()) {
            $subject = '🥳 ¡Hoy Celebramos! - ' . now()->isoFormat('dddd D [de] MMMM');
        } elseif ($this->data['feriadosHoy']->isNotEmpty()) {
            $subject = '🗓️ Día Especial - ' . now()->isoFormat('dddd D [de] MMMM');
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.comunicaciones-diarias',
            with: $this->data, // Pasamos directamente todo el array $data
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
       $fileName = 'Reporte_Comunicaciones_' . date('Ymd') . '.pdf';

        return [
            Attachment::fromPath($this->pdfPath)
                ->as($fileName)
                ->withMime('application/pdf'),
        ];
    }
}
