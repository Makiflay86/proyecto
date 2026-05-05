<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    // ─── Acceso ───────────────────────────────────────────────────────────

    public function test_index_redirige_si_no_autenticado(): void
    {
        $this->get('/categories')->assertRedirect('/login');
    }

    public function test_index_devuelve_403_si_no_es_admin(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/categories')
            ->assertForbidden();
    }

    public function test_index_accesible_para_admin(): void
    {
        $this->actingAs($this->admin())
            ->get('/categories')
            ->assertOk()
            ->assertViewIs('admin.categories.index');
    }

    // ─── create ───────────────────────────────────────────────────────────

    public function test_create_accesible_para_admin(): void
    {
        $this->actingAs($this->admin())
            ->get('/categories/create')
            ->assertOk()
            ->assertViewIs('admin.categories.create');
    }

    // ─── store ────────────────────────────────────────────────────────────

    public function test_store_crea_categoria_raiz(): void
    {
        $this->actingAs($this->admin())
            ->post('/categories', ['name' => 'Electrónica'])
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', ['name' => 'Electrónica']);
    }

    public function test_store_crea_subcategoria_con_padre(): void
    {
        $parent = Category::factory()->create();

        $this->actingAs($this->admin())->post('/categories', [
            'name'      => 'Móviles',
            'parent_id' => $parent->id,
        ]);

        $this->assertDatabaseHas('categories', [
            'name'      => 'Móviles',
            'parent_id' => $parent->id,
        ]);
    }

    public function test_store_falla_si_nombre_esta_vacio(): void
    {
        $this->actingAs($this->admin())
            ->post('/categories', ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_store_falla_si_nombre_duplicado_en_mismo_nivel(): void
    {
        Category::factory()->create(['name' => 'Motor', 'parent_id' => null]);

        $this->actingAs($this->admin())
            ->post('/categories', ['name' => 'Motor'])
            ->assertSessionHasErrors('name');
    }

    public function test_store_permite_mismo_nombre_en_distinto_nivel(): void
    {
        $parent = Category::factory()->create(['name' => 'Vehículos']);
        Category::factory()->create(['name' => 'Motor', 'parent_id' => null]);

        $this->actingAs($this->admin())
            ->post('/categories', ['name' => 'Motor', 'parent_id' => $parent->id])
            ->assertSessionDoesntHaveErrors('name');
    }

    public function test_store_guarda_imagen_si_se_sube(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post('/categories', [
            'name'  => 'Ropa',
            'image' => UploadedFile::fake()->image('cat.jpg'),
        ]);

        $category = Category::where('name', 'Ropa')->first();
        $this->assertNotNull($category->image);
        Storage::disk('public')->assertExists($category->image);
    }

    // ─── show ─────────────────────────────────────────────────────────────

    public function test_show_accesible_para_admin(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->get("/categories/{$category->id}")
            ->assertOk()
            ->assertViewIs('admin.categories.show');
    }

    // ─── edit ─────────────────────────────────────────────────────────────

    public function test_edit_accesible_para_admin(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->get("/categories/{$category->id}/edit")
            ->assertOk()
            ->assertViewIs('admin.categories.edit');
    }

    // ─── update ───────────────────────────────────────────────────────────

    public function test_update_cambia_el_nombre(): void
    {
        $category = Category::factory()->create(['name' => 'Viejo']);

        $this->actingAs($this->admin())
            ->put("/categories/{$category->id}", ['name' => 'Nuevo']);

        $this->assertDatabaseHas('categories', [
            'id'   => $category->id,
            'name' => 'Nuevo',
        ]);
    }

    public function test_update_falla_si_no_es_admin(): void
    {
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put("/categories/{$category->id}", ['name' => 'Hack'])
            ->assertForbidden();
    }

    // ─── destroy ──────────────────────────────────────────────────────────

    public function test_destroy_elimina_la_categoria(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->delete("/categories/{$category->id}")
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_destroy_elimina_las_subcategorias(): void
    {
        $parent = Category::factory()->create();
        $child  = Category::factory()->child($parent->id)->create();

        $this->actingAs($this->admin())->delete("/categories/{$parent->id}");

        $this->assertDatabaseMissing('categories', ['id' => $child->id]);
    }

    public function test_destroy_elimina_los_productos_de_la_categoria(): void
    {
        $category = Category::factory()->create();
        $product  = Product::factory()->create(['category_id' => $category->id]);

        $this->actingAs($this->admin())->delete("/categories/{$category->id}");

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_destroy_falla_si_no_es_admin(): void
    {
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete("/categories/{$category->id}")
            ->assertForbidden();
    }
}
