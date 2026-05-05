<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Tests del panel admin: rutas /products (AdminProductController).
 * Todas las rutas requieren auth + is_admin.
 */
class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function datosValidos(array $overrides = []): array
    {
        $category = Category::factory()->create();

        return array_merge([
            'nombre'      => 'Laptop Gaming',
            'descripcion' => 'Una laptop potente para gaming',
            'precio'      => 1500.00,
            'category_id' => $category->id,
        ], $overrides);
    }

    // ─── Acceso sin auth ──────────────────────────────────────────────────

    public function test_index_redirige_si_no_esta_autenticado(): void
    {
        $this->get('/products')->assertRedirect('/login');
    }

    public function test_index_devuelve_403_si_no_es_admin(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/products')->assertForbidden();
    }

    // ─── index ────────────────────────────────────────────────────────────

    public function test_index_devuelve_vista_correcta_para_admin(): void
    {
        $this->actingAs($this->admin())
            ->get('/products')
            ->assertOk()
            ->assertViewIs('admin.products.index');
    }

    // ─── create ───────────────────────────────────────────────────────────

    public function test_create_redirige_si_no_esta_autenticado(): void
    {
        $this->get('/products/create')->assertRedirect('/login');
    }

    public function test_create_devuelve_403_si_no_es_admin(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/products/create')
            ->assertForbidden();
    }

    public function test_create_muestra_formulario_al_admin(): void
    {
        $this->actingAs($this->admin())
            ->get('/products/create')
            ->assertOk()
            ->assertViewIs('admin.products.create');
    }

    // ─── store - Acceso ───────────────────────────────────────────────────

    public function test_store_redirige_si_no_esta_autenticado(): void
    {
        $this->post('/products', [])->assertRedirect('/login');
    }

    public function test_store_devuelve_403_si_no_es_admin(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/products', $this->datosValidos())
            ->assertForbidden();
    }

    // ─── store - Validaciones ─────────────────────────────────────────────

    public function test_store_falla_si_nombre_esta_vacio(): void
    {
        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos(['nombre' => '']))
            ->assertSessionHasErrors('nombre');
    }

    public function test_store_falla_si_nombre_supera_255_caracteres(): void
    {
        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos(['nombre' => str_repeat('a', 256)]))
            ->assertSessionHasErrors('nombre');
    }

    public function test_store_falla_si_descripcion_esta_vacia(): void
    {
        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos(['descripcion' => '']))
            ->assertSessionHasErrors('descripcion');
    }

    public function test_store_falla_si_precio_esta_vacio(): void
    {
        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos(['precio' => '']))
            ->assertSessionHasErrors('precio');
    }

    public function test_store_falla_si_precio_no_es_numerico(): void
    {
        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos(['precio' => 'abc']))
            ->assertSessionHasErrors('precio');
    }

    public function test_store_falla_si_precio_es_negativo(): void
    {
        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos(['precio' => -1]))
            ->assertSessionHasErrors('precio');
    }

    public function test_store_acepta_precio_cero(): void
    {
        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos(['precio' => 0]))
            ->assertSessionDoesntHaveErrors('precio');
    }

    public function test_store_falla_si_category_id_no_existe(): void
    {
        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos(['category_id' => 99999]))
            ->assertSessionHasErrors('category_id');
    }

    public function test_store_falla_si_imagen_no_es_tipo_valido(): void
    {
        Storage::fake('public');

        $pdf = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos(['images' => [$pdf]]))
            ->assertSessionHasErrors('images.0');
    }

    public function test_store_falla_si_imagen_supera_2mb(): void
    {
        Storage::fake('public');

        $grande = UploadedFile::fake()->image('foto.jpg')->size(3000);

        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos(['images' => [$grande]]))
            ->assertSessionHasErrors('images.0');
    }

    // ─── store - Éxito ────────────────────────────────────────────────────

    public function test_store_crea_producto_sin_imagenes(): void
    {
        $this->actingAs($this->admin())
            ->post('/products', $this->datosValidos())
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', ['nombre' => 'Laptop Gaming']);
    }

    public function test_store_crea_producto_con_imagenes(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $datos = $this->datosValidos([
            'images' => [
                UploadedFile::fake()->image('foto1.jpg'),
                UploadedFile::fake()->image('foto2.png'),
            ],
        ]);

        $this->actingAs($admin)->post('/products', $datos)->assertRedirect(route('products.index'));

        $product = Product::where('nombre', 'Laptop Gaming')->first();
        $this->assertNotNull($product);
        $this->assertCount(2, $product->images);
    }

    public function test_store_asocia_el_producto_al_usuario_admin(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/products', $this->datosValidos());

        $this->assertDatabaseHas('products', [
            'nombre'  => 'Laptop Gaming',
            'user_id' => $admin->id,
        ]);
    }

    // ─── show ─────────────────────────────────────────────────────────────

    public function test_show_devuelve_vista_correcta(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin())
            ->get("/products/{$product->id}")
            ->assertOk()
            ->assertViewIs('admin.products.show');
    }

    // ─── edit ─────────────────────────────────────────────────────────────

    public function test_edit_muestra_formulario(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin())
            ->get("/products/{$product->id}/edit")
            ->assertOk()
            ->assertViewIs('admin.products.edit');
    }

    // ─── update ───────────────────────────────────────────────────────────

    public function test_update_actualiza_el_producto(): void
    {
        $product  = Product::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($this->admin())->put("/products/{$product->id}", [
            'nombre'      => 'Nombre Actualizado',
            'descripcion' => 'Nueva descripción',
            'precio'      => 200,
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id'     => $product->id,
            'nombre' => 'Nombre Actualizado',
        ]);
    }

    public function test_update_falla_si_no_es_admin(): void
    {
        $product  = Product::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put("/products/{$product->id}", [
                'nombre'      => 'Hackeado',
                'descripcion' => 'x',
                'precio'      => 1,
                'category_id' => $category->id,
            ])
            ->assertForbidden();
    }

    // ─── destroy ──────────────────────────────────────────────────────────

    public function test_destroy_elimina_el_producto(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin())
            ->delete("/products/{$product->id}")
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_destroy_elimina_las_imagenes_del_storage(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create();
        $image   = ProductImage::factory()->create([
            'product_id' => $product->id,
            'path'       => 'productos/test.jpg',
        ]);
        Storage::disk('public')->put('productos/test.jpg', 'fake');

        $this->actingAs($this->admin())->delete("/products/{$product->id}");

        Storage::disk('public')->assertMissing('productos/test.jpg');
    }

    public function test_destroy_falla_si_no_es_admin(): void
    {
        $product = Product::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete("/products/{$product->id}")
            ->assertForbidden();
    }
}
