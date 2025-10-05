<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DadosExemploSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar Áreas
        $area1 = \App\Models\Area::create([
            'nome' => 'Área de Produção',
            'descricao' => 'Área principal de produção com equipamentos críticos',
            'ativo' => true
        ]);

        $area2 = \App\Models\Area::create([
            'nome' => 'Área de Utilidades',
            'descricao' => 'Área com equipamentos de apoio como bombas, compressores, etc.',
            'ativo' => true
        ]);

        $area3 = \App\Models\Area::create([
            'nome' => 'Área de Tratamento',
            'descricao' => 'Área de tratamento de água e efluentes',
            'ativo' => true
        ]);

        // Criar Equipamentos para Área de Produção
        $equipamentos_producao = [
            ['nome' => 'Bomba Centrífuga Principal', 'tag' => 'BOMB-001', 'descricao' => 'Bomba principal do processo'],
            ['nome' => 'Motor Elétrico 1', 'tag' => 'MOT-001', 'descricao' => 'Motor de 50HP para bomba principal'],
            ['nome' => 'Válvula de Controle 1', 'tag' => 'VAL-001', 'descricao' => 'Válvula pneumática de controle de vazão'],
            ['nome' => 'Sensor de Pressão 1', 'tag' => 'PT-001', 'descricao' => 'Transmissor de pressão na linha principal'],
        ];

        foreach ($equipamentos_producao as $eq) {
            \App\Models\Equipamento::create(array_merge($eq, ['area_id' => $area1->id, 'ativo' => true]));
        }

        // Criar Equipamentos para Área de Utilidades
        $equipamentos_utilidades = [
            ['nome' => 'Compressor de Ar', 'tag' => 'COMP-001', 'descricao' => 'Compressor de ar comprimido'],
            ['nome' => 'Bomba de Água de Resfriamento', 'tag' => 'BOMB-002', 'descricao' => 'Sistema de água de resfriamento'],
            ['nome' => 'Trocador de Calor', 'tag' => 'TC-001', 'descricao' => 'Trocador de calor principal'],
        ];

        foreach ($equipamentos_utilidades as $eq) {
            \App\Models\Equipamento::create(array_merge($eq, ['area_id' => $area2->id, 'ativo' => true]));
        }

        // Criar Equipamentos para Área de Tratamento
        $equipamentos_tratamento = [
            ['nome' => 'Bomba Dosadora de Químicos', 'tag' => 'BOMB-003', 'descricao' => 'Dosagem de produtos químicos'],
            ['nome' => 'Filtro de Água', 'tag' => 'FIL-001', 'descricao' => 'Sistema de filtração'],
            ['nome' => 'Medidor de pH', 'tag' => 'pH-001', 'descricao' => 'Controle de pH do processo'],
        ];

        foreach ($equipamentos_tratamento as $eq) {
            \App\Models\Equipamento::create(array_merge($eq, ['area_id' => $area3->id, 'ativo' => true]));
        }

        // Criar Paradas de Exemplo
        $parada1 = \App\Models\Parada::create([
            'macro' => 'PREV-OUT-2025',
            'nome' => 'Parada de Manutenção Preventiva - Outubro 2025',
            'descricao' => 'Parada programada para manutenção preventiva de todos os equipamentos críticos',
            'equipe_responsavel' => 'João Silva (Coordenador), Maria Santos (Técnica Elétrica), Pedro Costa (Técnico Mecânico)',
            'data_inicio' => now()->subHours(2),
            'duracao_prevista_horas' => 12,
            'status' => 'em_andamento',
            'tipo' => 'preventiva'
        ]);

        // Parada concluída para histórico
        $parada2 = \App\Models\Parada::create([
            'macro' => 'CORR-SET-2025',
            'nome' => 'Manutenção Corretiva - Bomba Principal',
            'descricao' => 'Correção de vazamento na bomba centrífuga principal',
            'equipe_responsavel' => 'Carlos Oliveira (Técnico), Ana Paula (Auxiliar)',
            'data_inicio' => now()->subDays(15),
            'data_fim' => now()->subDays(15)->addHours(6),
            'duracao_prevista_horas' => 8,
            'status' => 'concluida',
            'tipo' => 'corretiva'
        ]);

        // Parada emergencial para histórico
        $parada3 = \App\Models\Parada::create([
            'macro' => 'EMER-SET-2025',
            'nome' => 'Parada Emergencial - Falha no Sistema',
            'descricao' => 'Parada não programada devido a falha no sistema elétrico',
            'equipe_responsavel' => 'Equipe de Plantão, Eletricista de Emergência',
            'data_inicio' => now()->subDays(30),
            'data_fim' => now()->subDays(30)->addHours(4),
            'duracao_prevista_horas' => 2,
            'status' => 'concluida',
            'tipo' => 'emergencial'
        ]);

        // Criar testes para a parada atual (parada1)
        $equipamentos = \App\Models\Equipamento::where('ativo', true)->get();
        foreach ($equipamentos as $equipamento) {
            $status = collect(['pendente', 'ok', 'problema'])->random();
            
            \App\Models\Teste::create([
                'parada_id' => $parada1->id,
                'equipamento_id' => $equipamento->id,
                'status' => $status,
                'observacoes' => $status != 'pendente' ? 'Teste realizado conforme procedimento' : null,
                'problema_descricao' => $status == 'problema' ? 'Vazamento detectado na conexão principal' : null,
                'data_teste' => $status != 'pendente' ? now()->subMinutes(rand(10, 120)) : null,
                'testado_por' => $status != 'pendente' ? 'João Silva' : null,
            ]);
        }

        // Criar testes para a parada2 (concluída)
        foreach ($equipamentos as $equipamento) {
            $status = collect(['ok', 'problema'], [80, 20])->random(); // 80% ok, 20% problema
            
            \App\Models\Teste::create([
                'parada_id' => $parada2->id,
                'equipamento_id' => $equipamento->id,
                'status' => $status,
                'observacoes' => 'Teste concluído na parada de setembro',
                'problema_descricao' => $status == 'problema' ? 'Problema corrigido durante a manutenção' : null,
                'data_teste' => now()->subDays(15)->addMinutes(rand(30, 360)),
                'testado_por' => 'Carlos Oliveira',
            ]);
        }

        // Criar testes para a parada3 (emergencial concluída)
        foreach ($equipamentos->take(3) as $equipamento) { // Apenas alguns equipamentos testados na emergencial
            \App\Models\Teste::create([
                'parada_id' => $parada3->id,
                'equipamento_id' => $equipamento->id,
                'status' => 'ok',
                'observacoes' => 'Verificação rápida durante emergência',
                'data_teste' => now()->subDays(30)->addMinutes(rand(15, 120)),
                'testado_por' => 'Equipe de Plantão',
            ]);
        }

        $this->command->info('Dados de exemplo criados com sucesso!');
        $this->command->info('- 3 Áreas criadas');
        $this->command->info('- 10 Equipamentos criados');
        $this->command->info('- 3 Paradas criadas (1 em andamento + 2 histórico)');
        $this->command->info('- Testes gerados para todas as paradas');
        $this->command->info('- Histórico completo disponível');
    }
}
