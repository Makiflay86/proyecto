<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Modelo que representa una categoría.
 *
 * Las categorías forman una jerarquía en árbol gracias al campo "parent_id",
 * que apunta a la propia tabla (auto-referencia). Esto permite tener categorías
 * raíz (MOTOR) con subcategorías (COCHE, MOTO) y estas a su vez con más niveles.
 *
 * Tabla en BD: categories
 * Columnas:    id, parent_id (FK a sí misma), name, image, created_at, updated_at
 */
class Category extends Model
{
    use HasFactory;

    // Solo estos campos se pueden asignar de forma masiva (protección contra mass-assignment)
    protected $fillable = ['name', 'image', 'parent_id'];

    /**
     * Mutador: normaliza el nombre antes de guardarlo en la BD.
     * Convierte la primera letra de cada palabra a mayúscula sin importar
     * cómo lo escriba el usuario (todo mayús, todo minús, mezclado...).
     * Ejemplos: "motor" → "Motor" | "COCHE DEPORTIVO" → "Coche Deportivo"
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Str::title(mb_strtolower($value)),
        );
    }

    /**
     * Relación: esta categoría pertenece a otra categoría padre.
     * Ejemplo: COCHE pertenece a MOTOR.
     * Si parent_id es null, es una categoría raíz.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relación: esta categoría tiene subcategorías directas (hijos inmediatos).
     * Ejemplo: MOTOR tiene como hijos [COCHE, MOTO].
     * Se ordenan alfabéticamente.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name');
    }

    /**
     * Devuelve un array con el ID de esta categoría y los IDs de TODOS
     * sus descendientes (hijos, nietos, bisnietos...) sin importar la profundidad.
     *
     * Esto es necesario para contar o filtrar productos que pertenezcan
     * a una categoría o a cualquiera de sus subcategorías.
     *
     * Ejemplo: si MOTOR tiene hijos [COCHE, MOTO] y COCHE tiene [SEDAN, SUV],
     * llamar a allDescendantIds() sobre MOTOR devuelve [MOTOR, COCHE, MOTO, SEDAN, SUV].
     *
     * Usa loadMissing para evitar hacer consultas repetidas si la relación
     * ya estaba cargada (eager loading).
     *
     * Es un método recursivo: se llama a sí mismo en cada hijo.
     */
    public function allDescendantIds(): array
    {
        $this->loadMissing('allChildren');
        $ids = [$this->id]; // Empezamos con el ID de esta categoría
        foreach ($this->allChildren as $child) {
            // Añadimos recursivamente los IDs del hijo y de sus descendientes
            $ids = array_merge($ids, $child->allDescendantIds());
        }
        return $ids;
    }

    /**
     * Relación recursiva: carga todos los descendientes en profundidad.
     * La clave está en el ->with('allChildren') dentro de sí mismo:
     * Laravel cargará los hijos de los hijos de los hijos... automáticamente.
     *
     * Esto es un patrón de "eager loading recursivo" en Laravel.
     */
    public function allChildren(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->orderBy('name')
            ->with('allChildren'); // Se llama a sí misma → carga toda la jerarquía
    }

    /**
     * Relación: esta categoría tiene muchos productos.
     * Solo devuelve los productos asignados DIRECTAMENTE a esta categoría,
     * no los de sus subcategorías.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Genera un array plano de todas las categorías para usar en un <select>.
     * Cada elemento tiene: id, name, depth (nivel de profundidad) y label (con guiones de indentación).
     *
     * Ejemplo de labels resultantes:
     *   MOTOR          (depth 0)
     *   — COCHE        (depth 1)
     *   —— SEDAN       (depth 2)
     *   — MOTO         (depth 1)
     *
     * El parámetro $excludeId sirve para excluir una categoría y todo su árbol
     * al editar, evitando que una categoría pueda ser su propio padre (bucle circular).
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
     * Accede a la categoría raíz de esta categoría subiendo por la cadena de padres.
     * Ejemplo: si estamos en SEDAN → COCHE → MOTOR, devuelve MOTOR.
     *
     * IMPORTANTE: requiere que la relación 'parent' esté cargada con eager loading
     * antes de llamar a este atributo, si no hará N consultas a la base de datos.
     *
     * Se accede como propiedad: $category->root
     */
    public function getRootAttribute(): self
    {
        $cat = $this;
        while ($cat->parent !== null) {
            $cat = $cat->parent; // Subimos un nivel
        }
        return $cat; // Cuando parent es null, estamos en la raíz
    }

    /**
     * Genera el texto de migas de pan (breadcrumb) de la categoría actual.
     * Ejemplo: "MOTOR › COCHE › SEDAN"
     *
     * Construye el array de nombres yendo hacia arriba por los padres
     * y luego los une con el separador ›.
     *
     * IMPORTANTE: igual que getRootAttribute, necesita la cadena de 'parent'
     * cargada con eager loading.
     *
     * Se accede como propiedad: $category->breadcrumb
     */
    public function getBreadcrumbAttribute(): string
    {
        $names = [$this->name];
        $cat   = $this;
        while ($cat->relationLoaded('parent') && $cat->parent !== null) {
            array_unshift($names, $cat->parent->name); // Añade al inicio del array
            $cat = $cat->parent;
        }
        return implode(' › ', $names);
    }

    /**
     * Método auxiliar privado para flatOptions().
     * Añade la categoría al array de opciones y luego repite el proceso
     * recursivamente con sus hijos, incrementando el nivel de profundidad.
     *
     * Se pasa $options por referencia (&$options) para ir acumulando
     * los resultados en el mismo array sin necesidad de devolverlo.
     */
    private static function appendOption(self $cat, array &$options, int $depth, ?int $excludeId): void
    {
        if ($cat->id === $excludeId) {
            return; // Excluimos esta categoría y todo su árbol
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
