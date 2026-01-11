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
            // Adiciona apenas a coluna que falta
            if (!Schema::hasColumn('testes', 'ar_comprimido_foto_problema')) {
                $table->string('ar_comprimido_foto_problema')->nullable();
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
            ]);
        });
    }
};
