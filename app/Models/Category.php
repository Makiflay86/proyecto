<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo que representa una categoría.
 *
 * Tabla en BD: categories
 * Columnas:    id, parent_id, name, image, created_at, updated_at
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'parent_id'];

    /** Relación: una categoría pertenece a una categoría padre. */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /** Relación: una categoría tiene muchas subcategorías directas. */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name');
    }

    /** Returns IDs of this category and all its descendants. */
    public function allDescendantIds(): array
    {
        $this->loadMissing('allChildren');
        $ids = [$this->id];
        foreach ($this->allChildren as $child) {
            $ids = array_merge($ids, $child->allDescendantIds());
        }
        return $ids;
    }

    /** Recursive: loads all descendants. */
    public function allChildren(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->orderBy('name')
            ->with('allChildren');
    }

    /** Relación: una categoría tiene muchos productos. */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Returns a flat ordered array for <select> options with indentation prefixes.
     * Each item: ['id' => int, 'name' => string, 'depth' => int, 'label' => string]
     * label = str_repeat('— ', depth) . name
     * Excludes $excludeId subtree (used in edit to prevent circular parent).
     */
    public static function flatOptions(?int $excludeId = null): array
    {
        $roots = static::with('allChildren')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $options = [];
        foreach ($roots as $root) {
            static::appendOption($root, $options, 0, $excludeId);
        }
        return $options;
    }

    /**
     * Devuelve la categoría raíz (nivel 1) de esta categoría.
     * Requiere que la cadena de 'parent' esté eager-loaded.
     */
    public function getRootAttribute(): self
    {
        $cat = $this;
        while ($cat->parent !== null) {
            $cat = $cat->parent;
        }
        return $cat;
    }

    /**
     * Devuelve el breadcrumb completo: "Ropa › Camiseta › Mujer".
     * Requiere que la cadena de 'parent' esté eager-loaded.
     */
    public function getBreadcrumbAttribute(): string
    {
        $names = [$this->name];
        $cat   = $this;
        while ($cat->relationLoaded('parent') && $cat->parent !== null) {
            array_unshift($names, $cat->parent->name);
            $cat = $cat->parent;
        }
        return implode(' › ', $names);
    }

    private static function appendOption(self $cat, array &$options, int $depth, ?int $excludeId): void
    {
        if ($cat->id === $excludeId) {
            return;
        }
        $options[] = [
            'id'    => $cat->id,
            'name'  => $cat->name,
            'depth' => $depth,
            'label' => ($depth > 0 ? str_repeat('— ', $depth) : '') . $cat->name,
        ];
        foreach ($cat->children as $child) {
            static::appendOption($child, $options, $depth + 1, $excludeId);
        }
    }
}
