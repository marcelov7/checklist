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
            // Verificar e adicionar campos de foto de problema se não existirem
            if (!Schema::hasColumn('testes', 'ar_comprimido_foto_problema')) {
                $table->string('ar_comprimido_foto_problema')->nullable();
            }
            if (!Schema::hasColumn('testes', 'protecoes_eletricas_foto_problema')) {
                $table->string('protecoes_eletricas_foto_problema')->nullable();
            }
            if (!Schema::hasColumn('testes', 'protecoes_mecanicas_foto_problema')) {
                $table->string('protecoes_mecanicas_foto_problema')->nullable();
            }
            if (!Schema::hasColumn('testes', 'chave_remoto_foto_problema')) {
                $table->string('chave_remoto_foto_problema')->nullable();
            }
            if (!Schema::hasColumn('testes', 'inspecionado_foto_problema')) {
                $table->string('inspecionado_foto_problema')->nullable();
            }
            
            // Verificar e adicionar campos de foto de resolução se não existirem
            if (!Schema::hasColumn('testes', 'ar_comprimido_foto_resolucao')) {
                $table->string('ar_comprimido_foto_resolucao')->nullable();
            }
            if (!Schema::hasColumn('testes', 'protecoes_eletricas_foto_resolucao')) {
                $table->string('protecoes_eletricas_foto_resolucao')->nullable();
            }
            if (!Schema::hasColumn('testes', 'protecoes_mecanicas_foto_resolucao')) {
                $table->string('protecoes_mecanicas_foto_resolucao')->nullable();
            }
            if (!Schema::hasColumn('testes', 'chave_remoto_foto_resolucao')) {
                $table->string('chave_remoto_foto_resolucao')->nullable();
            }
            if (!Schema::hasColumn('testes', 'inspecionado_foto_resolucao')) {
                $table->string('inspecionado_foto_resolucao')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testes', function (Blueprint $table) {
            $table->dropColumn([
                'ar_comprimido_foto_problema',
                'protecoes_eletricas_foto_problema',
                'protecoes_mecanicas_foto_problema',
                'chave_remoto_foto_problema',
                'inspecionado_foto_problema',
                'ar_comprimido_foto_resolucao',
                'protecoes_eletricas_foto_resolucao',
                'protecoes_mecanicas_foto_resolucao',
                'chave_remoto_foto_resolucao',
                'inspecionado_foto_resolucao'
            ]);
        });
    }
};
