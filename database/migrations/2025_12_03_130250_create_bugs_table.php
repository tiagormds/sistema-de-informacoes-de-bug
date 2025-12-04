<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Ex: "Erro de permissão no Docker"
            $table->foreignId('platform_id')->constrained();
            $table->text('error_message')->nullable(); // O log do erro (pra você pesquisar depois)
            $table->text('solution'); // A parte mais importante: como resolveu!
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pra saber que o bug é seu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
