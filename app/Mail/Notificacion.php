<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class Notificacion extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $pdfPath;
    public $valor;
    public $urlPdf;
    public $tipoDocumento;
    public $direccionDocumentos;
    

    public function __construct($name,$valor,$pdfPath,$urlPdf,$tipoDocumento,$direccionDocumentos)
    {
        $this->name=$name;        
        $this->valor=$valor;
        $this->pdfPath=$pdfPath;
        $this->urlPdf=$urlPdf;
        $this->tipoDocumento=$tipoDocumento;
        $this->direccionDocumentos=$direccionDocumentos;        
    }
    public function envelope(): Envelope
    {
        return new Envelope(            
            subject: 'Notificacion Envio de '.$this->tipoDocumento,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {                
            return new Content(
                view: 'mail.emailRetencion'            
            );                                
    }


    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->direccionDocumentos.$this->pdfPath.'.xml')
            ->withMime('application/xml'),
        ];
    }
}
