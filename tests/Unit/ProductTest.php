<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    // ─── Fillable ────────────────────────────────────────────────────────

    public function test_tiene_los_campos_fillable_correctos(): void
    {
        $fillable = ['user_id', 'category_id', 'nombre', 'descripcion', 'precio', 'estado'];

        $this->assertEquals($fillable, (new Product)->getFillable());
    }

    // ─── Casts ───────────────────────────────────────────────────────────

    public function test_precio_se_castea_a_decimal_con_dos_decimales(): void
    {
        $product = Product::factory()->create(['precio' => 10.5]);

        $this->assertEquals('10.50', $product->precio);
    }

    // ─── Estado por defecto ───────────────────────────────────────────────

    public function test_estado_por_defecto_es_activo(): void
    {
        $product = Product::factory()->create();

        $this->assertEquals('activo', $product->estado);
    }

    // ─── Métodos de estado ────────────────────────────────────────────────

    public function test_is_sold_devuelve_true_si_estado_es_vendido(): void
    {
        $product = Product::factory()->vendido()->create();

        $this->assertTrue($product->isSold());
    }

    public function test_is_sold_devuelve_false_si_estado_no_es_vendido(): void
    {
        $product = Product::factory()->create();

        $this->assertFalse($product->isSold());
    }

    public function test_is_reserved_devuelve_true_si_estado_es_reservado(): void
    {
        $product = Product::factory()->reservado()->create();

        $this->assertTrue($product->isReserved());
    }

    public function test_is_reserved_devuelve_false_si_estado_no_es_reservado(): void
    {
        $product = Product::factory()->create();

        $this->assertFalse($product->isReserved());
    }

    // ─── Relaciones ───────────────────────────────────────────────────────

    public function test_un_producto_pertenece_a_un_usuario(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $product->user);
        $this->assertEquals($user->id, $product->user->id);
    }

    public function test_un_producto_pertenece_a_una_categoria(): void
    {
        $category = Category::factory()->create();
        $product  = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_un_producto_tiene_muchas_imagenes(): void
    {
        $product = Product::factory()->create();
        ProductImage::factory()->count(3)->create(['product_id' => $product->id]);

        $this->assertCount(3, $product->images);
        $this->assertInstanceOf(ProductImage::class, $product->images->first());
    }

    public function test_un_producto_sin_imagenes_devuelve_coleccion_vacia(): void
    {
        $product = Product::factory()->create();

        $this->assertCount(0, $product->images);
    }

    // ─── Persistencia ─────────────────────────────────────────────────────

    public function test_se_puede_crear_un_producto_en_base_de_datos(): void
    {
        $user     = User::factory()->create();
        $category = Category::factory()->create();

        Product::create([
            'user_id'     => $user->id,
            'category_id' => $category->id,
            'nombre'      => 'Laptop Pro',
            'descripcion' => 'Una laptop potente',
            'precio'      => 999.99,
            'estado'      => 'activo',
        ]);

        $this->assertDatabaseHas('products', ['nombre' => 'Laptop Pro']);
    }

    public function test_se_puede_eliminar_un_producto(): void
    {
        $product = Product::factory()->create();

        $product->delete();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_al_eliminar_producto_se_eliminan_sus_imagenes(): void
    {
        $product = Product::factory()->create();
        ProductImage::factory()->count(2)->create(['product_id' => $product->id]);

        $product->delete();

        $this->assertDatabaseMissing('product_images', ['product_id' => $product->id]);
    }

    public function test_al_eliminar_usuario_el_user_id_queda_en_null(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $user->delete();

        $this->assertNull($product->fresh()->user_id);
    }
}
