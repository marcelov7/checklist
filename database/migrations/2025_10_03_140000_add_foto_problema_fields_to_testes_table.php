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
            // Campos para fotos dos problemas específicos de cada item
            $table->string('ar_comprimido_foto_problema')->nullable()->after('ar_comprimido_problema');
            $table->string('protecoes_eletricas_foto_problema')->nullable()->after('protecoes_eletricas_problema');
            $table->string('protecoes_mecanicas_foto_problema')->nullable()->after('protecoes_mecanicas_problema');
            $table->string('chave_remoto_foto_problema')->nullable()->after('chave_remoto_problema');
            $table->string('inspecionado_foto_problema')->nullable()->after('inspecionado_problema');
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
                'inspecionado_foto_problema'
            ]);
        });
    }
};