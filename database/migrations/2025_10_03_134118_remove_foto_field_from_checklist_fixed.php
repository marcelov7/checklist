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
            // Remover campos antigos se existirem
            if (Schema::hasColumn('testes', 'foto_verificada')) {
                $table->dropColumn('foto_verificada');
            }
            if (Schema::hasColumn('testes', 'foto_status_old')) {
                $table->dropColumn('foto_status_old');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testes', function (Blueprint $table) {
            // Restaurar campos removidos se necessário
            $table->boolean('foto_verificada')->default(false)->nullable();
            $table->string('foto_status_old')->nullable();
        });
    }
};