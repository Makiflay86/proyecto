<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo que representa un producto de la tienda.
 *
 * Tabla en BD: products
 * Columnas:    id, nombre, descripcion, precio, categoria, estado, created_at, updated_at
 */
class Product extends Model
{
    // HasFactory permite crear productos falsos en tests con Product::factory()
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente (ej: Product::create([...]))
     * Cualquier campo que NO esté aquí será ignorado al guardar, por seguridad.
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'categoria',
        'estado',
    ];

    /**
     * Conversión automática de tipos al leer de la BD.
     * 'decimal:2' hace que el precio siempre tenga exactamente 2 decimales.
     */
    protected $casts = [
        'precio' => 'decimal:2',
    ];

    /**
     * Relación: un producto puede tener muchas imágenes.
     * Uso: $producto->images  →  devuelve una colección de ProductImage
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Scope (filtro reutilizable) que devuelve solo productos con estado 'activo'.
     * Uso: Product::activos()->get()
     * Equivale a: Product::where('estado', 'activo')->get()
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}
