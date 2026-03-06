<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo que representa una imagen asociada a un producto.
 *
 * Tabla en BD: product_images
 * Columnas:    id, product_id, path, created_at, updated_at
 *
 * La columna 'path' guarda la ruta relativa dentro del disco 'public',
 * por ejemplo: "productos/abc123.jpg"
 * Para obtener la URL completa usar: Storage::url($image->path)
 */
class ProductImage extends Model
{
    // HasFactory permite crear imágenes falsas en tests con ProductImage::factory()
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente.
     * 'product_id' vincula la imagen a su producto.
     * 'path' es la ruta del archivo guardado en storage/app/public.
     */
    protected $fillable = [
        'product_id',
        'path',
    ];

    /**
     * Relación inversa: una imagen pertenece a un único producto.
     * Uso: $imagen->product  →  devuelve el Product al que pertenece
     *
     * Nota: si el producto se elimina, esta imagen también se borra
     * gracias al onDelete('cascade') definido en la migración.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
