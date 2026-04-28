<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // Siempre el comprador (el usuario no-admin que inició el hilo)
            $table->foreignId('thread_user_id')->constrained('users')->cascadeOnDelete();
            // Quien envió este mensaje concreto (puede ser comprador o admin)
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['product_id', 'thread_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
