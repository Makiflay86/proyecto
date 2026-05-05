<?php

namespace Tests\Unit;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // ─── isOnline ─────────────────────────────────────────────────────────

    public function test_is_online_devuelve_true_si_last_seen_hace_menos_de_30s(): void
    {
        $user = User::factory()->create(['last_seen_at' => now()->subSeconds(10)]);

        $this->assertTrue($user->isOnline());
    }

    public function test_is_online_devuelve_false_si_last_seen_hace_mas_de_30s(): void
    {
        $user = User::factory()->create(['last_seen_at' => now()->subSeconds(60)]);

        $this->assertFalse($user->isOnline());
    }

    public function test_is_online_devuelve_false_si_last_seen_es_null(): void
    {
        $user = User::factory()->create(['last_seen_at' => null]);

        $this->assertFalse($user->isOnline());
    }

    // ─── Relaciones ───────────────────────────────────────────────────────

    public function test_un_usuario_tiene_muchos_productos(): void
    {
        $user = User::factory()->create();
        Product::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->products);
    }

    public function test_un_usuario_sin_productos_devuelve_coleccion_vacia(): void
    {
        $user = User::factory()->create();

        $this->assertCount(0, $user->products);
    }

    // ─── unreadThreadsCount ───────────────────────────────────────────────

    public function test_unread_threads_count_devuelve_0_sin_mensajes(): void
    {
        $user = User::factory()->create();

        $this->assertEquals(0, $user->unreadThreadsCount());
    }

    public function test_unread_threads_count_como_comprador(): void
    {
        $buyer  = User::factory()->create();
        $seller = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        // Vendedor envía un mensaje al comprador (no leído)
        Message::create([
            'product_id'     => $product->id,
            'thread_user_id' => $buyer->id,
            'sender_id'      => $seller->id,
            'body'           => 'Hola comprador',
            'read_at'        => null,
        ]);

        $this->assertEquals(1, $buyer->unreadThreadsCount());
    }

    public function test_unread_threads_count_no_cuenta_mensajes_propios(): void
    {
        $buyer  = User::factory()->create();
        $seller = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        // El propio comprador envía — no debe contarse como no leído para él
        Message::create([
            'product_id'     => $product->id,
            'thread_user_id' => $buyer->id,
            'sender_id'      => $buyer->id,
            'body'           => 'Hola vendedor',
            'read_at'        => null,
        ]);

        $this->assertEquals(0, $buyer->unreadThreadsCount());
    }

    public function test_unread_threads_count_no_cuenta_mensajes_leidos(): void
    {
        $buyer   = User::factory()->create();
        $seller  = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        // read_at no está en $fillable, hay que asignarlo directamente
        $msg = new Message([
            'product_id'     => $product->id,
            'thread_user_id' => $buyer->id,
            'sender_id'      => $seller->id,
            'body'           => 'Mensaje leído',
        ]);
        $msg->read_at = now();
        $msg->save();

        $this->assertEquals(0, $buyer->unreadThreadsCount());
    }

    // ─── Likes ────────────────────────────────────────────────────────────

    public function test_un_usuario_puede_dar_like_a_productos(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create();

        $user->likedProducts()->attach($product->id);

        $this->assertCount(1, $user->likedProducts);
        $this->assertEquals($product->id, $user->likedProducts->first()->id);
    }
}
