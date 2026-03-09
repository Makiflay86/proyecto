<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Convierte el campo de texto `categoria` en una foreign key `category_id`
 * que apunta a la tabla `categories` con ON DELETE CASCADE.
 *
 * Pasos:
 *  1. Elimina los índices que incluyen `categoria` (evita error al borrar la columna).
 *  2. Añade `category_id` nullable (para poder hacer el UPDATE antes de activar la FK).
 *  3. Rellena `category_id` haciendo JOIN con `categories` por nombre.
 *  4. Elimina la columna `categoria`.
 *  5. Recrea el índice compuesto con `category_id` en lugar de `categoria`.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Eliminar índices que incluían `categoria`
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_categoria_index');
            $table->dropIndex('products_estado_categoria_index');
        });

        // 2. Añadir category_id como nullable (sin constraint aún para poder poblarla)
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('id');
        });

        // 3. Poblar category_id uniendo por nombre de categoría
        DB::statement('
            UPDATE products p
            INNER JOIN categories c ON c.name = p.categoria
            SET p.category_id = c.id
        ');

        // 4. Eliminar la columna de texto y añadir la FK con cascade
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('categoria');

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->cascadeOnDelete();
        });

        // 5. Recrear índices útiles con la nueva columna
        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id',             'products_category_id_index');
            $table->index(['estado', 'category_id'], 'products_estado_category_index');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_estado_category_index');
            $table->dropIndex('products_category_id_index');
            $table->dropForeign(['category_id']);
        });

        // Restaurar columna de texto y rellenarla desde categories
        Schema::table('products', function (Blueprint $table) {
            $table->string('categoria')->nullable()->after('category_id');
        });

        DB::statement('
            UPDATE products p
            LEFT JOIN categories c ON c.id = p.category_id
            SET p.categoria = c.name
        ');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category_id');
            $table->index('categoria',              'products_categoria_index');
            $table->index(['estado', 'categoria'],  'products_estado_categoria_index');
        });
    }
};
