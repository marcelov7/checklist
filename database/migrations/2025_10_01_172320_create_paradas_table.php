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
        Schema::create('paradas', function (Blueprint $table) {
            $table->id();
            $table->string('macro', 50)->unique();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->text('equipe_responsavel')->nullable();
            $table->datetime('data_inicio');
            $table->integer('duracao_prevista_horas')->nullable();
            $table->datetime('data_fim')->nullable();
            $table->enum('status', ['em_andamento', 'concluida', 'cancelada'])->default('em_andamento');
            $table->enum('tipo', ['preventiva', 'corretiva', 'emergencial', 'programada'])->default('programada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paradas');
    }
};
