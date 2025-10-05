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
            // Campos de checklist específicos
            $table->boolean('foto_verificada')->default(false)->after('status');
            $table->boolean('ar_comprimido_ok')->default(false)->after('foto_verificada');
            $table->boolean('protecoes_eletricas_ok')->default(false)->after('ar_comprimido_ok');
            $table->boolean('protecoes_mecanicas_ok')->default(false)->after('protecoes_eletricas_ok');
            $table->boolean('chave_remoto_testada')->default(false)->after('protecoes_mecanicas_ok');
            $table->boolean('inspecionado')->default(false)->after('chave_remoto_testada');
            
            // Campo para armazenar foto
            $table->string('foto_path')->nullable()->after('inspecionado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testes', function (Blueprint $table) {
            $table->dropColumn([
                'foto_verificada',
                'ar_comprimido_ok',
                'protecoes_eletricas_ok',
                'protecoes_mecanicas_ok',
                'chave_remoto_testada',
                'inspecionado',
                'foto_path'
            ]);
        });
    }
};
