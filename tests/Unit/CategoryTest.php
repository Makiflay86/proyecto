<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    // ─── Fillable ────────────────────────────────────────────────────────

    public function test_tiene_los_campos_fillable_correctos(): void
    {
        $this->assertEquals(['name', 'image', 'parent_id'], (new Category)->getFillable());
    }

    // ─── Mutador de nombre ────────────────────────────────────────────────

    public function test_nombre_se_guarda_en_title_case(): void
    {
        $cat = Category::factory()->create(['name' => 'ELECTRONICA DE CONSUMO']);

        $this->assertEquals('Electronica De Consumo', $cat->name);
    }

    public function test_nombre_en_minusculas_se_convierte_a_title_case(): void
    {
        $cat = Category::factory()->create(['name' => 'ropa deportiva']);

        $this->assertEquals('Ropa Deportiva', $cat->name);
    }

    // ─── Relaciones ───────────────────────────────────────────────────────

    public function test_una_categoria_puede_tener_padre(): void
    {
        $parent = Category::factory()->create();
        $child  = Category::factory()->child($parent->id)->create();

        $this->assertEquals($parent->id, $child->parent->id);
    }

    public function test_una_categoria_raiz_no_tiene_padre(): void
    {
        $cat = Category::factory()->create();

        $this->assertNull($cat->parent);
    }

    public function test_una_categoria_puede_tener_hijos(): void
    {
        $parent = Category::factory()->create();
        Category::factory()->count(3)->child($parent->id)->create();

        $this->assertCount(3, $parent->children);
    }

    public function test_una_categoria_tiene_productos(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);

        $this->assertCount(2, $category->products);
    }

    // ─── allDescendantIds ─────────────────────────────────────────────────

    public function test_all_descendant_ids_incluye_la_propia_categoria(): void
    {
        $cat = Category::factory()->create();

        $this->assertContains($cat->id, $cat->allDescendantIds());
    }

    public function test_all_descendant_ids_incluye_hijos_y_nietos(): void
    {
        $root  = Category::factory()->create();
        $child = Category::factory()->child($root->id)->create();
        $grand = Category::factory()->child($child->id)->create();

        $ids = $root->allDescendantIds();

        $this->assertContains($root->id, $ids);
        $this->assertContains($child->id, $ids);
        $this->assertContains($grand->id, $ids);
    }

    // ─── breadcrumb ───────────────────────────────────────────────────────

    public function test_breadcrumb_de_categoria_raiz_es_solo_su_nombre(): void
    {
        $cat = Category::factory()->create(['name' => 'Motor']);
        $cat->load('parent');

        $this->assertEquals('Motor', $cat->breadcrumb);
    }

    public function test_breadcrumb_incluye_la_cadena_de_padres(): void
    {
        $root  = Category::factory()->create(['name' => 'Motor']);
        $child = Category::factory()->child($root->id)->create(['name' => 'Coche']);
        $child->load('parent.parent');

        $this->assertEquals('Motor › Coche', $child->breadcrumb);
    }

    // ─── flatOptions ─────────────────────────────────────────────────────

    public function test_flat_options_devuelve_array_con_las_categorias(): void
    {
        Category::factory()->count(3)->create();

        $options = Category::flatOptions();

        $this->assertCount(3, $options);
        $this->assertArrayHasKey('id', $options[0]);
        $this->assertArrayHasKey('label', $options[0]);
        $this->assertArrayHasKey('depth', $options[0]);
    }

    public function test_flat_options_excluye_la_categoria_indicada(): void
    {
        $a = Category::factory()->create();
        $b = Category::factory()->create();

        $options = Category::flatOptions($a->id);

        $ids = array_column($options, 'id');
        $this->assertNotContains($a->id, $ids);
        $this->assertContains($b->id, $ids);
    }

    public function test_hijos_tienen_depth_mayor_que_el_padre(): void
    {
        $parent = Category::factory()->create();
        Category::factory()->child($parent->id)->create();

        $options = Category::flatOptions();
        $depths  = array_column($options, 'depth');

        $this->assertContains(0, $depths);
        $this->assertContains(1, $depths);
    }

    // ─── Persistencia ─────────────────────────────────────────────────────

    public function test_se_puede_crear_categoria_en_base_de_datos(): void
    {
        Category::create(['name' => 'Tecnología']);

        $this->assertDatabaseHas('categories', ['name' => 'Tecnología']);
    }

    public function test_se_puede_eliminar_una_categoria(): void
    {
        $cat = Category::factory()->create();
        $cat->delete();

        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }
}
