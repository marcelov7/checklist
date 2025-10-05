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
        // Para SQLite, precisamos recriar a tabela para alterar os valores ENUM
        // Primeiro, vamos fazer backup dos dados
        $backupData = DB::table('testes')->get()->toArray();
        
        // Remover as constraints existentes
        Schema::table('testes', function (Blueprint $table) {
            $table->dropColumn([
                'ar_comprimido_status',
                'protecoes_eletricas_status', 
                'protecoes_mecanicas_status',
                'chave_remoto_status',
                'inspecionado_status'
            ]);
        });
        
        // Recriar as colunas com os valores corretos
        Schema::table('testes', function (Blueprint $table) {
            $table->enum('ar_comprimido_status', ['pendente', 'ok', 'problema', 'nao_aplica'])->default('pendente');
            $table->enum('protecoes_eletricas_status', ['pendente', 'ok', 'problema', 'nao_aplica'])->default('pendente');
            $table->enum('protecoes_mecanicas_status', ['pendente', 'ok', 'problema', 'nao_aplica'])->default('pendente');
            $table->enum('chave_remoto_status', ['pendente', 'ok', 'problema', 'nao_aplica'])->default('pendente');
            $table->enum('inspecionado_status', ['pendente', 'ok', 'problema', 'nao_aplica'])->default('pendente');
        });
        
        // Restaurar os dados, convertendo 'nao_ok' para 'problema'
        foreach ($backupData as $row) {
            $updateData = [];
            $statusFields = ['ar_comprimido_status', 'protecoes_eletricas_status', 'protecoes_mecanicas_status', 'chave_remoto_status', 'inspecionado_status'];
            
            foreach ($statusFields as $field) {
                if (isset($row->$field)) {
                    $updateData[$field] = $row->$field === 'nao_ok' ? 'problema' : $row->$field;
                }
            }
            
            if (!empty($updateData)) {
                DB::table('testes')->where('id', $row->id)->update($updateData);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Converter 'problema' de volta para 'nao_ok'
        $statusFields = ['ar_comprimido_status', 'protecoes_eletricas_status', 'protecoes_mecanicas_status', 'chave_remoto_status', 'inspecionado_status'];
        
        foreach ($statusFields as $field) {
            DB::table('testes')->where($field, 'problema')->update([$field => 'nao_ok']);
        }
        
        // Remover as colunas
        Schema::table('testes', function (Blueprint $table) {
            $table->dropColumn($statusFields);
        });
        
        // Recriar com os valores antigos
        Schema::table('testes', function (Blueprint $table) {
            $table->enum('ar_comprimido_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente');
            $table->enum('protecoes_eletricas_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente');
            $table->enum('protecoes_mecanicas_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente');
            $table->enum('chave_remoto_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente');
            $table->enum('inspecionado_status', ['pendente', 'ok', 'nao_ok', 'nao_aplica'])->default('pendente');
        });
    }
};
