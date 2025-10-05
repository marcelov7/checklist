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
        Schema::table('testes', function (Blueprint $table) {
            // Campos para resolução de problemas do checklist
            $table->text('ar_comprimido_resolucao')->nullable();
            $table->string('ar_comprimido_foto_resolucao')->nullable();
            
            $table->text('protecoes_eletricas_resolucao')->nullable();
            $table->string('protecoes_eletricas_foto_resolucao')->nullable();
            
            $table->text('protecoes_mecanicas_resolucao')->nullable();
            $table->string('protecoes_mecanicas_foto_resolucao')->nullable();
            
            $table->text('chave_remoto_resolucao')->nullable();
            $table->string('chave_remoto_foto_resolucao')->nullable();
            
            $table->text('inspecionado_resolucao')->nullable();
            $table->string('inspecionado_foto_resolucao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testes', function (Blueprint $table) {
            $table->dropColumn([
                'ar_comprimido_resolucao',
                'ar_comprimido_foto_resolucao',
                'protecoes_eletricas_resolucao', 
                'protecoes_eletricas_foto_resolucao',
                'protecoes_mecanicas_resolucao',
                'protecoes_mecanicas_foto_resolucao',
                'chave_remoto_resolucao',
                'chave_remoto_foto_resolucao',
                'inspecionado_resolucao',
                'inspecionado_foto_resolucao'
            ]);
        });
    }
};
