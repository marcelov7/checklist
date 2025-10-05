<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('paradas', function (Blueprint $table) {
            $table->string('macro', 50)->nullable()->after('id');
            $table->integer('duracao_prevista_horas')->nullable()->after('data_inicio');
            $table->text('equipe_responsavel')->nullable()->after('descricao');
            $table->string('tipo', 20)->default('programada')->after('status');
        });
        
        // Após adicionar a coluna, vamos popular com valores temporários
        DB::table('paradas')->whereNull('macro')->update([
            'macro' => DB::raw('CONCAT("PAR", id, "-", strftime("%Y", created_at))')
        ]);
        
        // Agora tornar a coluna NOT NULL
        Schema::table('paradas', function (Blueprint $table) {
            $table->string('macro', 50)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paradas', function (Blueprint $table) {
            $table->dropColumn(['macro', 'duracao_prevista_horas', 'equipe_responsavel', 'tipo']);
        });
    }
};
