<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    // ─── Fillable ────────────────────────────────────────────────────────

    public function test_tiene_los_campos_fillable_correctos(): void
    {
        $fillable = ['nombre', 'descripcion', 'precio', 'categoria', 'estado'];

        $this->assertEquals($fillable, (new Product)->getFillable());
    }

    // ─── Casts ───────────────────────────────────────────────────────────

    public function test_precio_se_castea_a_decimal_con_dos_decimales(): void
    {
        $product = Product::factory()->create(['precio' => 10.5]);

        // El cast 'decimal:2' devuelve string en PHP
        $this->assertEquals('10.50', $product->precio);
    }

    // ─── Estado por defecto ───────────────────────────────────────────────

    public function test_estado_por_defecto_es_activo(): void
    {
        $product = Product::factory()->create();

        $this->assertEquals('activo', $product->estado);
    }

    // ─── Relaciones ───────────────────────────────────────────────────────

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

    // ─── Scope activos ────────────────────────────────────────────────────

    public function test_scope_activos_retorna_solo_productos_activos(): void
    {
        Product::factory()->count(3)->create(['estado' => 'activo']);
        Product::factory()->count(2)->inactivo()->create();

        $activos = Product::activos()->get();

        $this->assertCount(3, $activos);
        $activos->each(fn ($p) => $this->assertEquals('activo', $p->estado));
    }

    public function test_scope_activos_retorna_vacio_si_no_hay_activos(): void
    {
        Product::factory()->count(2)->inactivo()->create();

        $this->assertCount(0, Product::activos()->get());
    }

    // ─── Persistencia ─────────────────────────────────────────────────────

    public function test_se_puede_crear_un_producto_en_base_de_datos(): void
    {
        Product::create([
            'nombre'      => 'Laptop Pro',
            'descripcion' => 'Una laptop potente',
            'precio'      => 999.99,
            'categoria'   => 'electronica',
            'estado'      => 'activo',
        ]);

        $this->assertDatabaseHas('products', [
            'nombre'    => 'Laptop Pro',
            'categoria' => 'electronica',
        ]);
    }

    public function test_se_puede_eliminar_un_producto(): void
    {
        $product = Product::factory()->create();

        $product->delete();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
