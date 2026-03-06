<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable que representa el email de confirmación de producto creado.
 *
 * Un Mailable es la clase que define el contenido y configuración
 * de un email. La vista asociada está en:
 *   resources/views/emails/product-created.blade.php
 *
 * Para previsualizar el email en el navegador, se puede retornar
 * directamente desde una ruta temporal:
 *   return new ProductCreatedMail($product, $user);
 */
class ProductCreatedMail extends Mailable
{
    // Queueable permite que este email se envíe en cola (async) en el futuro
    // SerializesModels serializa correctamente los modelos Eloquent al encolar
    use Queueable, SerializesModels;

    /**
     * @param Product $product  El producto recién creado
     * @param User    $user     El usuario destinatario del email
     */
    public function __construct(
        public readonly Product $product,
        public readonly User $user,
    ) {}

    /**
     * Define el asunto y remitente del email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Producto \"{$this->product->nombre}\" creado correctamente",
        );
    }

    /**
     * Define la vista y los datos que se pasan a la plantilla del email.
     * Las propiedades públicas ($product, $user) están disponibles
     * automáticamente en la vista sin necesidad de pasarlas explícitamente.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.product-created',
        );
    }
}
