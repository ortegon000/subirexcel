<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ImeisImportedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fileName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('no-replay@liberacionesporimei.com')
            ->subject('Imeis subidos con exito')
            ->markdown('imeis_imported_mail');
    }
}
