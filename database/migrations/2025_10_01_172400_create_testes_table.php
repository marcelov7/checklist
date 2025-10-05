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
        Schema::create('testes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parada_id')->constrained('paradas')->onDelete('cascade');
            $table->foreignId('equipamento_id')->constrained('equipamentos')->onDelete('cascade');
            $table->enum('status', ['pendente', 'ok', 'problema'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->text('problema_descricao')->nullable();
            $table->datetime('data_teste')->nullable();
            $table->string('testado_por')->nullable();
            $table->timestamps();

            $table->unique(['parada_id', 'equipamento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testes');
    }
};
