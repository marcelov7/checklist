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
            // Status individuais para cada item do checklist (pendente, ok, nao_ok, nao_aplica)
            $table->enum('foto_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente')->after('foto_path');
            $table->enum('ar_comprimido_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente')->after('foto_status');
            $table->enum('protecoes_eletricas_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente')->after('ar_comprimido_status');
            $table->enum('protecoes_mecanicas_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente')->after('protecoes_eletricas_status');
            $table->enum('chave_remoto_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente')->after('protecoes_mecanicas_status');
            $table->enum('inspecionado_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente')->after('chave_remoto_status');
            
            // Campos para problemas específicos de cada item
            $table->text('foto_problema')->nullable()->after('inspecionado_status');
            $table->text('ar_comprimido_problema')->nullable()->after('foto_problema');
            $table->text('protecoes_eletricas_problema')->nullable()->after('ar_comprimido_problema');
            $table->text('protecoes_mecanicas_problema')->nullable()->after('protecoes_eletricas_problema');
            $table->text('chave_remoto_problema')->nullable()->after('protecoes_mecanicas_problema');
            $table->text('inspecionado_problema')->nullable()->after('chave_remoto_problema');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testes', function (Blueprint $table) {
            $table->dropColumn([
                'foto_status',
                'ar_comprimido_status', 
                'protecoes_eletricas_status',
                'protecoes_mecanicas_status',
                'chave_remoto_status',
                'inspecionado_status',
                'foto_problema',
                'ar_comprimido_problema',
                'protecoes_eletricas_problema', 
                'protecoes_mecanicas_problema',
                'chave_remoto_problema',
                'inspecionado_problema'
            ]);
        });
    }
};
