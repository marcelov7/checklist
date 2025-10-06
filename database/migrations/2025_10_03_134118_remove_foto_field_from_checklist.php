<?php

use     public funct    public function down(): void
    {
        Schema::table('testes', function (Blueprint $table) {
            // Restaurar campos removidos
            $table->boolean('foto_verificada')->default(false)->after('foto_path');
            $table->enum('foto_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente')->after('foto_verificada');
            $table->text('foto_problema')->nullable()->after('foto_status');
        });
    }(): void
    {
        Schema::table('testes', function (Blueprint $table) {
            // Remover campos relacionados à foto do checklist
            $table->dropColumn(['foto_verificada', 'foto_status', 'foto_problema']);
        });
    }nate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('checklist', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklist', function (Blueprint $table) {
            //
        });
    }
};
