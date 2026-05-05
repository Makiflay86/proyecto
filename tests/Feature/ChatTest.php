<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    // ─── index (mis-mensajes) ─────────────────────────────────────────────

    public function test_index_redirige_si_no_esta_autenticado(): void
    {
        $this->get('/mis-mensajes')->assertRedirect('/login');
    }

    public function test_index_accesible_para_usuario_autenticado(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/mis-mensajes')
            ->assertOk();
    }

    // ─── show (chat comprador) ────────────────────────────────────────────

    public function test_show_redirige_si_no_esta_autenticado(): void
    {
        $product = Product::factory()->create();

        $this->get("/chat/{$product->id}")->assertRedirect('/login');
    }

    public function test_show_accesible_para_usuario_autenticado(): void
    {
        $product = Product::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get("/chat/{$product->id}")
            ->assertOk();
    }

    // ─── showAsSeller (chat vendedor) ─────────────────────────────────────

    public function test_show_as_seller_redirige_si_no_esta_autenticado(): void
    {
        $seller  = User::factory()->create();
        $buyer   = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        $this->get("/chat/{$product->id}/vendedor/{$buyer->id}")->assertRedirect('/login');
    }

    public function test_show_as_seller_accesible_para_el_dueno(): void
    {
        $seller  = User::factory()->create();
        $buyer   = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        $this->actingAs($seller)
            ->get("/chat/{$product->id}/vendedor/{$buyer->id}")
            ->assertOk();
    }

    public function test_show_as_seller_devuelve_403_si_no_es_dueno_ni_admin(): void
    {
        $seller  = User::factory()->create();
        $buyer   = User::factory()->create();
        $other   = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        $this->actingAs($other)
            ->get("/chat/{$product->id}/vendedor/{$buyer->id}")
            ->assertForbidden();
    }

    public function test_show_as_seller_accesible_para_admin(): void
    {
        $seller  = User::factory()->create();
        $buyer   = User::factory()->create();
        $admin   = User::factory()->create(['is_admin' => true]);
        $product = Product::factory()->create(['user_id' => $seller->id]);

        $this->actingAs($admin)
            ->get("/chat/{$product->id}/vendedor/{$buyer->id}")
            ->assertOk();
    }

    // ─── showThread (solo admin) ──────────────────────────────────────────

    public function test_show_thread_devuelve_403_si_no_es_admin(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->get("/chat/{$product->id}/{$user->id}")
            ->assertForbidden();
    }

    public function test_show_thread_accesible_para_admin(): void
    {
        $admin   = User::factory()->create(['is_admin' => true]);
        $user    = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)
            ->get("/chat/{$product->id}/{$user->id}")
            ->assertOk();
    }

    // ─── API mensajes no leídos ───────────────────────────────────────────

    public function test_api_unread_redirige_si_no_esta_autenticado(): void
    {
        $this->get('/mensajes/no-leidos')->assertRedirect('/login');
    }

    public function test_api_unread_devuelve_json_con_count(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/mensajes/no-leidos');

        $response->assertOk()->assertJsonStructure(['count']);
    }

    public function test_api_unread_cuenta_mensajes_no_leidos(): void
    {
        $buyer   = User::factory()->create();
        $seller  = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        Message::create([
            'product_id'     => $product->id,
            'thread_user_id' => $buyer->id,
            'sender_id'      => $seller->id,
            'body'           => 'Hola',
            'read_at'        => null,
        ]);

        $this->actingAs($buyer)
            ->get('/mensajes/no-leidos')
            ->assertJson(['count' => 1]);
    }

    public function test_api_unread_devuelve_0_sin_mensajes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/mensajes/no-leidos')
            ->assertJson(['count' => 0]);
    }
}
