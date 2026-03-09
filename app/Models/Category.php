<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo que representa una categoría.
 *
 * Tabla en BD: categories
 * Columnas:    id, name, image, created_at, updated_at
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    /** Relación: una categoría tiene muchos productos. */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
