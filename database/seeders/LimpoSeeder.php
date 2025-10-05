<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LimpoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar áreas
        $area1 = \App\Models\Area::create([
            'nome' => 'Produção Principal',
            'descricao' => 'Área principal de produção industrial'
        ]);

        $area2 = \App\Models\Area::create([
            'nome' => 'Utilidades',
            'descricao' => 'Sistemas auxiliares e utilidades'
        ]);

        $area3 = \App\Models\Area::create([
            'nome' => 'Armazenamento',
            'descricao' => 'Tanques e silos de armazenamento'
        ]);

        // Criar equipamentos para Produção Principal
        \App\Models\Equipamento::create([
            'tag' => 'P-001',
            'nome' => 'Bomba Centrífuga Principal',
            'descricao' => 'Bomba principal do processo de produção',
            'area_id' => $area1->id,
            'ativo' => true
        ]);

        \App\Models\Equipamento::create([
            'tag' => 'R-001',
            'nome' => 'Reator Principal',
            'descricao' => 'Reator químico principal',
            'area_id' => $area1->id,
            'ativo' => true
        ]);

        \App\Models\Equipamento::create([
            'tag' => 'C-001',
            'nome' => 'Compressor de Ar',
            'descricao' => 'Compressor para ar comprimido',
            'area_id' => $area1->id,
            'ativo' => true
        ]);

        // Criar equipamentos para Utilidades
        \App\Models\Equipamento::create([
            'tag' => 'P-101',
            'nome' => 'Bomba de Água Gelada',
            'descricao' => 'Sistema de refrigeração',
            'area_id' => $area2->id,
            'ativo' => true
        ]);

        \App\Models\Equipamento::create([
            'tag' => 'F-101',
            'nome' => 'Filtro de Água',
            'descricao' => 'Sistema de filtragem de água',
            'area_id' => $area2->id,
            'ativo' => true
        ]);

        \App\Models\Equipamento::create([
            'tag' => 'G-101',
            'nome' => 'Gerador de Emergência',
            'descricao' => 'Gerador diesel de emergência',
            'area_id' => $area2->id,
            'ativo' => true
        ]);

        // Criar equipamentos para Armazenamento
        \App\Models\Equipamento::create([
            'tag' => 'T-201',
            'nome' => 'Tanque de Matéria Prima',
            'descricao' => 'Armazenamento de matéria prima',
            'area_id' => $area3->id,
            'ativo' => true
        ]);

        \App\Models\Equipamento::create([
            'tag' => 'T-202',
            'nome' => 'Tanque de Produto Final',
            'descricao' => 'Armazenamento de produto acabado',
            'area_id' => $area3->id,
            'ativo' => true
        ]);

        \App\Models\Equipamento::create([
            'tag' => 'S-201',
            'nome' => 'Silo de Grãos',
            'descricao' => 'Armazenamento de grãos secos',
            'area_id' => $area3->id,
            'ativo' => true
        ]);

        // NÃO criar nenhuma parada nem testes automaticamente!
        
        $this->command->info('Base limpa criada com sucesso!');
        $this->command->info('- 3 Áreas criadas');
        $this->command->info('- 9 Equipamentos criados');
        $this->command->info('- 0 Paradas (para testar criação manual)');
        $this->command->info('- 0 Testes (serão criados conforme seleção)');
    }
}