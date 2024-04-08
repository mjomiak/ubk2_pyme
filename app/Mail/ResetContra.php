<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ResetContra extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $idUsuario;
    public $pass;

    public function __construct($idUsuario, $pass)
    {
        $this->idUsuario = $idUsuario;
        $this->pass = $pass;
    }


    public function build()
    {

        $empresa=DB::table('sys_params')->where('clave','empresa')->value('valor');
        $url = env('APP_URL');
        $user=DB::table('users')->where('id',$this->idUsuario)->first();
        return $this->view('mailable.resetContra',compact('user'))->with('pass', $this->pass)
        ->with('url', $url)
        ->with('empresa',$empresa)
            ->subject('Asunto del Correo');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Reset de Contraseña',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    /*
    public function content()
    {
        return new Content(
            view: 'view.name',
        );
    }
    */

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
