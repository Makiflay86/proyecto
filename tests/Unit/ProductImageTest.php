<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    // ─── Fillable ────────────────────────────────────────────────────────

    public function test_tiene_los_campos_fillable_correctos(): void
    {
        $fillable = ['product_id', 'path'];

        $this->assertEquals($fillable, (new ProductImage)->getFillable());
    }

    // ─── Relaciones ───────────────────────────────────────────────────────

    public function test_pertenece_a_un_producto(): void
    {
        $product = Product::factory()->create();
        $image   = ProductImage::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $image->product);
        $this->assertEquals($product->id, $image->product->id);
    }

    // ─── Cascada ──────────────────────────────────────────────────────────

    public function test_imagen_se_elimina_en_cascada_al_borrar_el_producto(): void
    {
        $product = Product::factory()->create();
        ProductImage::factory()->count(2)->create(['product_id' => $product->id]);

        $product->delete();

        $this->assertDatabaseMissing('product_images', ['product_id' => $product->id]);
    }

    // ─── Persistencia ─────────────────────────────────────────────────────

    public function test_se_puede_crear_una_imagen_de_producto(): void
    {
        $product = Product::factory()->create();

        $image = ProductImage::create([
            'product_id' => $product->id,
            'path'       => 'productos/foto-test.jpg',
        ]);

        $this->assertDatabaseHas('product_images', [
            'product_id' => $product->id,
            'path'       => 'productos/foto-test.jpg',
        ]);
    }

    public function test_un_producto_puede_tener_multiples_imagenes(): void
    {
        $product = Product::factory()->create();
        ProductImage::factory()->count(5)->create(['product_id' => $product->id]);

        $this->assertDatabaseCount('product_images', 5);
    }
}
