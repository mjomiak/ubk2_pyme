<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Session\Events\SessionExpired;
use Illuminate\Support\Facades\Log;

class SessionExpiredSubscriber implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Session\Events\SessionExpired  $event
     * @return void
     */
    public function handle(SessionExpired $event)
    {
        // Acciones a realizar cuando una sesión ha expirado
        // Puedes registrar en el registro o realizar cualquier otra acción.

        // Ejemplo: Registro de información
        Log::channel('act')->info('La sesión del usuario ha expirado: ' . $event->sessionId);
    }
}
