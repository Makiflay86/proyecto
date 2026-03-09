<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Añade índices a la tabla products para optimizar las consultas más frecuentes:
 *
 *  - estado        → filtros por activo/inactivo (Dashboard stats, products index)
 *  - categoria     → agrupaciones y filtros por categoría
 *  - created_at    → ordenación por fecha (latest())
 *  - [estado, categoria] → índice compuesto para filtros combinados
 *  - FULLTEXT [nombre, descripcion] → búsqueda de texto completo (ProductSearch)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Índice simple en estado — usado en CASE WHEN y WHERE estado = 'activo'
            $table->index('estado', 'products_estado_index');

            // Índice simple en categoria — usado en GROUP BY categoria y COUNT(DISTINCT categoria)
            $table->index('categoria', 'products_categoria_index');

            // Índice simple en created_at — usado en ORDER BY created_at DESC (latest())
            $table->index('created_at', 'products_created_at_index');

            // Índice compuesto — cubre consultas que filtran por estado Y categoria simultáneamente
            $table->index(['estado', 'categoria'], 'products_estado_categoria_index');

            // Índice FULLTEXT — habilita búsqueda eficiente de texto en nombre y descripcion
            // Usado por el componente Livewire ProductSearch (LIKE %query%)
            $table->fullText(['nombre', 'descripcion'], 'products_fulltext_search');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_estado_index');
            $table->dropIndex('products_categoria_index');
            $table->dropIndex('products_created_at_index');
            $table->dropIndex('products_estado_categoria_index');
            $table->dropFullTextIndex('products_fulltext_search');
        });
    }
};
