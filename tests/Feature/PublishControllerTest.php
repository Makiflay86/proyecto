<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublishControllerTest extends TestCase
{
    use RefreshDatabase;

    private function datosValidos(array $overrides = []): array
    {
        return array_merge([
            'nombre'      => 'Bicicleta de montaña',
            'descripcion' => 'En perfecto estado, poco uso',
            'precio'      => 350.00,
            'category_id' => Category::factory()->create()->id,
        ], $overrides);
    }

    // ─── create ───────────────────────────────────────────────────────────

    public function test_create_redirige_si_no_esta_autenticado(): void
    {
        $this->get('/publicar')->assertRedirect('/login');
    }

    public function test_create_accesible_para_usuario_normal(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/publicar')
            ->assertOk();
    }

    // ─── store - Acceso ───────────────────────────────────────────────────

    public function test_store_redirige_si_no_esta_autenticado(): void
    {
        $this->post('/publicar', [])->assertRedirect('/login');
    }

    // ─── store - Validaciones ─────────────────────────────────────────────

    public function test_store_falla_si_nombre_esta_vacio(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/publicar', $this->datosValidos(['nombre' => '']))
            ->assertSessionHasErrors('nombre');
    }

    public function test_store_falla_si_nombre_supera_255_caracteres(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/publicar', $this->datosValidos(['nombre' => str_repeat('a', 256)]))
            ->assertSessionHasErrors('nombre');
    }

    public function test_store_falla_si_descripcion_esta_vacia(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/publicar', $this->datosValidos(['descripcion' => '']))
            ->assertSessionHasErrors('descripcion');
    }

    public function test_store_falla_si_precio_esta_vacio(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/publicar', $this->datosValidos(['precio' => '']))
            ->assertSessionHasErrors('precio');
    }

    public function test_store_falla_si_precio_es_negativo(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/publicar', $this->datosValidos(['precio' => -5]))
            ->assertSessionHasErrors('precio');
    }

    public function test_store_acepta_precio_cero(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/publicar', $this->datosValidos(['precio' => 0]))
            ->assertSessionDoesntHaveErrors('precio');
    }

    public function test_store_falla_si_category_id_no_existe(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/publicar', $this->datosValidos(['category_id' => 99999]))
            ->assertSessionHasErrors('category_id');
    }

    public function test_store_falla_si_imagen_no_es_tipo_valido(): void
    {
        Storage::fake('public');

        $pdf = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $this->actingAs(User::factory()->create())
            ->post('/publicar', $this->datosValidos(['images' => [$pdf]]))
            ->assertSessionHasErrors('images.0');
    }

    public function test_store_falla_si_imagen_supera_2mb(): void
    {
        Storage::fake('public');

        $grande = UploadedFile::fake()->image('foto.jpg')->size(3000);

        $this->actingAs(User::factory()->create())
            ->post('/publicar', $this->datosValidos(['images' => [$grande]]))
            ->assertSessionHasErrors('images.0');
    }

    // ─── store - Éxito ────────────────────────────────────────────────────

    public function test_store_crea_el_producto_en_base_de_datos(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/publicar', $this->datosValidos())
            ->assertRedirect(route('profile.store'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', ['nombre' => 'Bicicleta de montaña']);
    }

    public function test_store_asocia_el_producto_al_usuario_autenticado(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/publicar', $this->datosValidos());

        $this->assertDatabaseHas('products', [
            'nombre'  => 'Bicicleta de montaña',
            'user_id' => $user->id,
        ]);
    }

    public function test_store_crea_el_producto_en_estado_activo(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/publicar', $this->datosValidos());

        $this->assertDatabaseHas('products', [
            'nombre' => 'Bicicleta de montaña',
            'estado' => 'activo',
        ]);
    }

    public function test_store_guarda_imagenes_en_storage(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->post('/publicar', $this->datosValidos([
            'images' => [
                UploadedFile::fake()->image('foto1.jpg'),
                UploadedFile::fake()->image('foto2.png'),
            ],
        ]));

        $product = Product::where('nombre', 'Bicicleta de montaña')->first();
        $this->assertCount(2, $product->images);
    }

    // ─── Estados del producto ─────────────────────────────────────────────

    public function test_marcar_como_vendido_cambia_el_estado(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id, 'estado' => 'activo']);

        $this->actingAs($user)
            ->patch("/producto/{$product->id}/vendido")
            ->assertRedirect();

        $this->assertEquals('vendido', $product->fresh()->estado);
    }

    public function test_marcar_como_vendido_falla_si_no_es_el_dueno(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
            ->patch("/producto/{$product->id}/vendido")
            ->assertForbidden();
    }

    public function test_reservar_producto_cambia_el_estado(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->patch("/producto/{$product->id}/reservar");

        $this->assertEquals('reservado', $product->fresh()->estado);
    }

    public function test_reservar_falla_si_no_es_el_dueno(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
            ->patch("/producto/{$product->id}/reservar")
            ->assertForbidden();
    }

    public function test_quitar_reserva_vuelve_a_activo(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->reservado()->create(['user_id' => $user->id]);

        $this->actingAs($user)->patch("/producto/{$product->id}/quitar-reserva");

        $this->assertEquals('activo', $product->fresh()->estado);
    }

    public function test_quitar_reserva_falla_si_no_es_el_dueno(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $product = Product::factory()->reservado()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
            ->patch("/producto/{$product->id}/quitar-reserva")
            ->assertForbidden();
    }

    public function test_reactivar_producto_vendido_vuelve_a_activo(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->vendido()->create(['user_id' => $user->id]);

        $this->actingAs($user)->patch("/producto/{$product->id}/reactivar");

        $this->assertEquals('activo', $product->fresh()->estado);
    }

    public function test_reactivar_falla_si_no_es_el_dueno(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $product = Product::factory()->vendido()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
            ->patch("/producto/{$product->id}/reactivar")
            ->assertForbidden();
    }

    public function test_estados_requieren_autenticacion(): void
    {
        $product = Product::factory()->create();

        $this->patch("/producto/{$product->id}/vendido")->assertRedirect('/login');
        $this->patch("/producto/{$product->id}/reservar")->assertRedirect('/login');
        $this->patch("/producto/{$product->id}/quitar-reserva")->assertRedirect('/login');
        $this->patch("/producto/{$product->id}/reactivar")->assertRedirect('/login');
    }
}
