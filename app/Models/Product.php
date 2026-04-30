<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo que representa un producto de la tienda.
 *
 * Tabla en BD: products
 * Columnas:    id, category_id, nombre, descripcion, precio, estado, created_at, updated_at
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'nombre',
        'descripcion',
        'precio',
        'estado',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    /** Relación: un producto pertenece a una categoría. */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Relación: un producto fue creado por un usuario. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Relación: un producto puede tener muchas imágenes. */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /** Scope: devuelve solo productos con estado 'activo'. */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'product_likes')->withPivot('created_at');
    }

    /** Comprueba si el producto está vendido. */
    public function isSold(): bool
    {
        return $this->estado === 'vendido';
    }

    /** Comprueba si el producto está reservado. */
    public function isReserved(): bool
    {
        return $this->estado === 'reservado';
    }
}
