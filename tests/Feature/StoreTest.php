<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    // ─── index (catálogo público) ─────────────────────────────────────────

    public function test_index_accesible_sin_autenticacion(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_index_accesible_con_usuario_autenticado(): void
    {
        $this->actingAs(User::factory()->create())->get('/')->assertOk();
    }

    // ─── show (detalle de producto) ───────────────────────────────────────

    public function test_show_devuelve_200_para_producto_activo(): void
    {
        $product = Product::factory()->create(['estado' => 'activo']);

        $this->get("/producto/{$product->id}")->assertOk();
    }

    public function test_show_devuelve_200_para_producto_reservado(): void
    {
        $product = Product::factory()->reservado()->create();

        $this->get("/producto/{$product->id}")->assertOk();
    }

    public function test_show_devuelve_200_para_producto_vendido(): void
    {
        $product = Product::factory()->vendido()->create();

        $this->get("/producto/{$product->id}")->assertOk();
    }

    public function test_show_devuelve_404_para_producto_inactivo(): void
    {
        $product = Product::factory()->inactivo()->create();

        $this->get("/producto/{$product->id}")->assertNotFound();
    }

    public function test_show_pasa_el_producto_a_la_vista(): void
    {
        $product = Product::factory()->create();

        $this->get("/producto/{$product->id}")
            ->assertViewHas('product', fn ($p) => $p->id === $product->id);
    }

    // ─── favorites ────────────────────────────────────────────────────────

    public function test_favorites_redirige_si_no_esta_autenticado(): void
    {
        $this->get('/mis-favoritos')->assertRedirect('/login');
    }

    public function test_favorites_accesible_con_usuario_autenticado(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/mis-favoritos')
            ->assertOk();
    }

    public function test_favorites_muestra_solo_los_productos_del_usuario(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create();
        $other   = Product::factory()->create();

        $user->likedProducts()->attach($product->id);

        $response = $this->actingAs($user)->get('/mis-favoritos');

        $products = $response->viewData('products');
        $this->assertCount(1, $products);
        $this->assertEquals($product->id, $products->first()->id);
    }

    public function test_favorites_vacio_si_no_hay_likes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/mis-favoritos');

        $this->assertCount(0, $response->viewData('products'));
    }

    // ─── Perfil público de usuario ────────────────────────────────────────

    public function test_perfil_usuario_accesible_sin_autenticacion(): void
    {
        $user = User::factory()->create();

        $this->get("/usuarios/{$user->id}")->assertOk();
    }

    public function test_perfil_usuario_muestra_sus_productos(): void
    {
        $user = User::factory()->create();
        Product::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->get("/usuarios/{$user->id}");

        $this->assertCount(3, $response->viewData('products'));
    }
}
