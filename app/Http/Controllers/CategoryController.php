<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador que gestiona las operaciones CRUD sobre categorías.
 *
 * Rutas asociadas (todas requieren auth + verified):
 *   GET    /categories                  → index()   Lista las categorías raíz
 *   GET    /categories/create           → create()  Formulario de creación
 *   POST   /categories                  → store()   Guarda la nueva categoría
 *   GET    /categories/{category}       → show()    Detalle de una categoría
 *   GET    /categories/{category}/edit  → edit()    Formulario de edición
 *   PUT    /categories/{category}       → update()  Guarda los cambios
 *   DELETE /categories/{category}       → destroy() Elimina la categoría
 */
class CategoryController extends Controller
{
    /**
     * Lista solo las categorías raíz (las que no tienen padre, parent_id = null).
     *
     * Además de cargar las categorías, calculamos cuántos productos tiene
     * cada una incluyendo los de todas sus subcategorías (usando allDescendantIds()).
     *
     * El resultado $productCounts es un array asociativo [id_categoria => total_productos]
     * que pasamos a la vista para mostrar el contador en cada tarjeta.
     *
     * Se carga 'allChildren' en la consulta principal para que allDescendantIds()
     * no tenga que hacer consultas extra a la BD por cada categoría.
     */
    public function index()
    {
        // Cargamos las categorías raíz junto con toda su jerarquía de hijos
        $categories = Category::whereNull('parent_id')->with('allChildren')->orderBy('name')->get();

        // Para cada categoría raíz, contamos los productos de ella y todas sus subcategorías
        $productCounts = [];
        foreach ($categories as $category) {
            // allDescendantIds() devuelve [id_raiz, id_hijo1, id_nieto1, ...]
            // whereIn filtra los productos que pertenezcan a cualquiera de esos IDs
            $productCounts[$category->id] = Product::whereIn('category_id', $category->allDescendantIds())->count();
        }

        return view('categories.index', compact('categories', 'productCounts'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     * Carga las opciones de categorías padre para el <select> con indentación visual.
     */
    public function create()
    {
        $categoryOptions = Category::flatOptions();

        return view('categories.create', compact('categoryOptions'));
    }

    /**
     * Valida y guarda una nueva categoría en la base de datos.
     *
     * Validaciones:
     * - name: obligatorio, único en la tabla categories
     * - image: opcional, debe ser imagen (jpeg/png/jpg/webp) y máximo 2MB
     * - parent_id: opcional, debe existir en la tabla categories si se indica
     *
     * Si se sube imagen, se guarda en storage/app/public/categorias/
     * y se almacena la ruta relativa en la columna 'image'.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:categories,name',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = [
            'name'      => $request->name,
            'parent_id' => $request->parent_id ?: null, // Convierte "" a null
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categorias', 'public');
        }

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Muestra el detalle de una categoría con toda la información necesaria para la vista.
     *
     * Datos que calcula y pasa a la vista:
     *
     * - $totalProductCount:  total de productos en esta categoría Y todas sus subcategorías.
     * - $directProductCount: solo los productos asignados directamente a esta categoría
     *                        (sin contar los de las subcategorías).
     * - $childProductCounts: array [id_hijo => total_productos] para mostrar el contador
     *                        en cada tarjeta de subcategoría.
     * - $categoryPath:       array de IDs desde la raíz hasta esta categoría
     *                        (ej: [1, 5] para MOTOR > COCHE). Se usa para generar
     *                        el enlace a productos con el filtro pre-aplicado.
     *
     * Se carga 'children.allChildren' para que los hijos directos ya traigan
     * su propia jerarquía sin hacer consultas extra.
     */
    public function show(Category $category)
    {
        // Cargamos: el padre (para el breadcrumb), los hijos directos y
        // los descendientes de los hijos (para calcular sus conteos de productos)
        $category->load(['parent', 'children.allChildren']);

        // Total de productos incluyendo toda la jerarquía descendiente
        $totalProductCount  = Product::whereIn('category_id', $category->allDescendantIds())->count();

        // Solo los productos asignados directamente a esta categoría
        $directProductCount = $category->products()->count();

        // Conteo de productos por cada subcategoría directa (también incluyendo sus descendientes)
        $childProductCounts = [];
        foreach ($category->children as $child) {
            $childProductCounts[$child->id] = Product::whereIn('category_id', $child->allDescendantIds())->count();
        }

        // Construimos el "path" de IDs desde la raíz hasta la categoría actual.
        // Subimos por la cadena de padres usando array_unshift para ir añadiendo al inicio.
        // Ejemplo: estando en COCHE (padre: MOTOR), el resultado es [id_MOTOR, id_COCHE].
        // Este array se convierte en query string: ?path[]=1&path[]=5
        // y el componente Livewire ProductList lo usa para pre-aplicar el filtro.
        $categoryPath = [];
        $cat = $category;
        while ($cat) {
            array_unshift($categoryPath, $cat->id); // Añade al inicio del array
            $cat = $cat->parent;
        }

        return view('categories.show', compact(
            'category',
            'totalProductCount',
            'directProductCount',
            'childProductCounts',
            'categoryPath'
        ));
    }

    /**
     * Muestra el formulario para editar una categoría.
     * Excluye la propia categoría (y su árbol) de las opciones de padre
     * para evitar bucles circulares (una categoría no puede ser su propio padre).
     */
    public function edit(Category $category)
    {
        $categoryOptions = Category::flatOptions($category->id);

        return view('categories.edit', compact('category', 'categoryOptions'));
    }

    /**
     * Valida y actualiza los datos de una categoría existente.
     *
     * La validación de 'name' usa la regla unique con excepción del propio registro
     * para permitir guardar sin cambiar el nombre: unique:categories,name,{id}
     *
     * Opciones de imagen:
     * - Si se sube nueva imagen → borra la anterior del disco y guarda la nueva.
     * - Si se marca "remove_image" → borra la imagen actual sin subir ninguna.
     * - Si no se toca la imagen → se mantiene la que había.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'      => "required|string|max:255|unique:categories,name,{$category->id}",
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = [
            'name'      => $request->name,
            'parent_id' => $request->parent_id ?: null,
        ];

        if ($request->hasFile('image')) {
            // Borrar imagen anterior del storage si existía
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categorias', 'public');
        }

        // Opción de quitar la imagen actual sin subir una nueva
        if ($request->boolean('remove_image') && $category->image) {
            Storage::disk('public')->delete($category->image);
            $data['image'] = null;
        }

        $category->update($data);

        return redirect()->route('categories.show', $category)
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Elimina una categoría y limpia todos sus archivos del disco.
     *
     * La base de datos tiene ON DELETE CASCADE, así que al borrar la categoría
     * se borran automáticamente sus subcategorías y los productos asociados.
     * Pero los archivos físicos (imágenes) no se borran solos con cascade,
     * por eso los eliminamos manualmente antes de borrar el registro.
     *
     * Pasos:
     * 1. Recopilamos los IDs de esta categoría y todos sus descendientes.
     * 2. Borramos del disco las imágenes de los productos afectados.
     * 3. Borramos del disco las imágenes de las categorías afectadas.
     * 4. Llamamos a $category->delete() → la BD borra en cascada todo lo demás.
     */
    public function destroy(Category $category)
    {
        $category->load('allChildren');
        $allIds = array_merge([$category->id], $this->collectIds($category->allChildren));

        // Eliminar archivos de imagen de los productos afectados
        Product::whereIn('category_id', $allIds)->with('images')->get()->each(function ($product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
            }
        });

        // Eliminar archivos de imagen de las categorías afectadas
        Category::whereIn('id', $allIds)->whereNotNull('image')->get()->each(function ($cat) {
            Storage::disk('public')->delete($cat->image);
        });

        $category->delete(); // La BD borra en cascada: subcategorías + productos

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    /**
     * Método privado auxiliar para recopilar recursivamente todos los IDs
     * de una colección de categorías y sus descendientes.
     *
     * Se usa en destroy() para saber qué categorías se van a borrar
     * y limpiar sus imágenes del disco antes de que la BD haga el cascade.
     */
    private function collectIds($children): array
    {
        $ids = [];
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->collectIds($child->allChildren));
        }
        return $ids;
    }
}
