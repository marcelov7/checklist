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
        // Em SQLite, os campos TEXT já aceitam qualquer valor string
        // Incluindo 'nao_aplica'. Não há necessidade de alterar a estrutura.
        // Esta migração serve apenas para registrar que o status 'nao_aplica' foi adicionado
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Resetar todos os valores 'nao_aplica' para 'pendente'
        $statusFields = [
            'ar_comprimido_status',
            'protecoes_eletricas_status',
            'protecoes_mecanicas_status', 
            'chave_remoto_status',
            'inspecionado_status'
        ];

        foreach ($statusFields as $field) {
            DB::statement("UPDATE testes SET $field = 'pendente' WHERE $field = 'nao_aplica'");
        }
    }
};
