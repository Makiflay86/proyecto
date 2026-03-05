<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'nombre', 
        'descripcion', 
        'precio', 
        'categoria', 
        'estado'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    // Uso: $productos = Product::activos()->get();
}
