<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa una categoría.
 *
 * Tabla en BD: categories
 * Columnas:    id, name, created_at, updated_at
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];
}
