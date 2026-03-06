<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    // ─── index ────────────────────────────────────────────────────────────

    public function test_index_redirige_si_no_esta_autenticado(): void
    {
        $this->get('/products')->assertRedirect('/login');
    }

    public function test_index_devuelve_vista_correcta(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/products')
            ->assertStatus(200)
            ->assertViewIs('products.index');
    }

    public function test_index_pasa_los_productos_a_la_vista(): void
    {
        $user = User::factory()->create();
        Product::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/products');

        $response->assertViewHas('products');
        $this->assertCount(3, $response->viewData('products'));
    }

    public function test_index_carga_las_imagenes_de_cada_producto(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create();
        \App\Models\ProductImage::factory()->count(2)->create(['product_id' => $product->id]);

        $response = $this->actingAs($user)->get('/products');

        $primerProducto = $response->viewData('products')->first();
        $this->assertCount(2, $primerProducto->images);
    }

    // ─── create ───────────────────────────────────────────────────────────

    public function test_create_redirige_si_no_esta_autenticado(): void
    {
        $this->get('/products/create')->assertRedirect('/login');
    }

    public function test_create_muestra_el_formulario(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/products/create')
            ->assertStatus(200)
            ->assertViewIs('products.create');
    }

    // ─── store - Autenticación ────────────────────────────────────────────

    public function test_store_redirige_si_no_esta_autenticado(): void
    {
        $this->post('/products', [])->assertRedirect('/login');
    }

    // ─── store - Validaciones de campos requeridos ────────────────────────

    public function test_store_falla_si_todos_los_campos_estan_vacios(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/products', [])
            ->assertSessionHasErrors(['nombre', 'descripcion', 'precio', 'categoria']);
    }

    public function test_store_falla_si_nombre_esta_vacio(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['nombre' => '']))
            ->assertSessionHasErrors('nombre');
    }

    public function test_store_falla_si_nombre_supera_255_caracteres(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['nombre' => str_repeat('a', 256)]))
            ->assertSessionHasErrors('nombre');
    }

    public function test_store_falla_si_descripcion_esta_vacia(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['descripcion' => '']))
            ->assertSessionHasErrors('descripcion');
    }

    public function test_store_falla_si_precio_esta_vacio(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['precio' => '']))
            ->assertSessionHasErrors('precio');
    }

    public function test_store_falla_si_precio_no_es_numerico(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['precio' => 'no-es-numero']))
            ->assertSessionHasErrors('precio');
    }

    public function test_store_falla_si_precio_es_negativo(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['precio' => -1]))
            ->assertSessionHasErrors('precio');
    }

    public function test_store_acepta_precio_cero(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['precio' => 0]))
            ->assertSessionDoesntHaveErrors('precio');
    }

    public function test_store_falla_si_categoria_esta_vacia(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['categoria' => '']))
            ->assertSessionHasErrors('categoria');
    }

    // ─── store - Validaciones de imágenes ─────────────────────────────────

    public function test_store_falla_si_imagen_no_es_tipo_valido(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $pdf = UploadedFile::fake()->create('documento.pdf', 100, 'application/pdf');

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['images' => [$pdf]]))
            ->assertSessionHasErrors('images.0');
    }

    public function test_store_falla_si_imagen_supera_2mb(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $imagenGrande = UploadedFile::fake()->image('grande.jpg')->size(3000); // 3 MB

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['images' => [$imagenGrande]]))
            ->assertSessionHasErrors('images.0');
    }

    // ─── store - Éxito ────────────────────────────────────────────────────

    public function test_store_crea_producto_sin_imagenes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/products', $this->datosValidos())
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', ['nombre' => 'Laptop Gaming']);
    }

    public function test_store_crea_producto_con_imagenes(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $datos = $this->datosValidos([
            'images' => [
                UploadedFile::fake()->image('foto1.jpg'),
                UploadedFile::fake()->image('foto2.png'),
            ],
        ]);

        $this->actingAs($user)
            ->post('/products', $datos)
            ->assertRedirect(route('products.index'));

        $product = Product::where('nombre', 'Laptop Gaming')->first();

        $this->assertNotNull($product);
        $this->assertCount(2, $product->images);
        Storage::disk('public')->assertExists($product->images[0]->path);
        Storage::disk('public')->assertExists($product->images[1]->path);
    }

    public function test_store_no_crea_el_producto_si_falla_al_guardar_imagen(): void
    {
        // El controlador usa DB::transaction, así que un fallo no deja producto huérfano.
        // Simulamos un archivo inválido para que la validación lo rechace.
        Storage::fake('public');
        $user = User::factory()->create();

        $archivoInvalido = UploadedFile::fake()->create('malware.exe', 500, 'application/octet-stream');

        $this->actingAs($user)
            ->post('/products', $this->datosValidos(['images' => [$archivoInvalido]]))
            ->assertSessionHasErrors('images.0');

        $this->assertDatabaseMissing('products', ['nombre' => 'Laptop Gaming']);
    }

    // ─── Helper ───────────────────────────────────────────────────────────

    private function datosValidos(array $overrides = []): array
    {
        return array_merge([
            'nombre'      => 'Laptop Gaming',
            'descripcion' => 'Una laptop potente para gaming',
            'precio'      => 1500.00,
            'categoria'   => 'electronica',
        ], $overrides);
    }
}
