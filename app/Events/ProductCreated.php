<?php

namespace App\Events;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Evento que se dispara cuando se crea un nuevo producto.
 *
 * Lleva consigo el producto creado y el usuario que lo creó,
 * para que el Listener tenga toda la información necesaria sin
 * tener que volver a consultar la base de datos.
 *
 * Uso desde el controlador:
 *   ProductCreated::dispatch($product, $user);
 */
class ProductCreated
{
    // Dispatchable permite llamar al evento con ::dispatch() de forma estática
    use Dispatchable;

    /**
     * @param Product $product  El producto recién creado
     * @param User    $user     El usuario autenticado que lo creó
     */
    public function __construct(
        public readonly Product $product,
        public readonly User $user,
    ) {}
}
