<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Mail\ProductCreatedMail;
use Illuminate\Support\Facades\Mail;

/**
 * Listener que reacciona al evento ProductCreated.
 *
 * Su única responsabilidad es enviar el email de confirmación
 * al usuario que creó el producto.
 *
 * Se ejecuta automáticamente cuando se dispara ProductCreated::dispatch().
 */
class SendProductCreatedEmail
{
    /**
     * Maneja el evento recibido.
     * Laravel inyecta el evento automáticamente como parámetro.
     *
     * @param ProductCreated $event  Contiene el producto y el usuario
     */
    public function handle(ProductCreated $event): void
    {
        // Envía el email al usuario que creó el producto
        Mail::to($event->user->email)
            ->send(new ProductCreatedMail($event->product, $event->user));
    }
}
