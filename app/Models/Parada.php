<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Parada extends Model
{
    protected $fillable = [
        'macro',
        'nome',
        'descricao',
        'data_inicio',
        'data_fim',
        'duracao_prevista_horas',
        'equipe_responsavel',
        'status',
        'tipo'
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
    ];

    public function testes()
    {
        return $this->hasMany(Teste::class);
    }

    public function getPercentualCompleto()
    {
        $testes = $this->testes()->get();
        
        if ($testes->count() === 0) {
            return 0;
        }
        
        // Somar o progresso de todos os checklists dos equipamentos
        $progressoTotal = $testes->sum('checklist_progress');
        
        // Calcular a média geral
        $percentualGeral = round($progressoTotal / $testes->count(), 1);
        
        return $percentualGeral;
    }

    public function calcularProgressoPercentual()
    {
        $totalEquipamentos = $this->testes->count();
        
        if ($totalEquipamentos === 0) {
            return 0;
        }

        $equipamentosCompletos = 0;

        foreach($this->testes as $teste) {
            if($this->verificarEquipamentoCompleto($teste)) {
                $equipamentosCompletos++;
            }
        }

        return round(($equipamentosCompletos / $totalEquipamentos) * 100, 1);
    }

    /**
     * Verifica se um equipamento está completo baseado no status dos itens do checklist
     */
    private function verificarEquipamentoCompleto($teste)
    {
        // Usar a mesma lógica dos relatórios
        $checklistItems = ['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'];
        
        $itensOkOuNA = 0;
        $totalItensComStatus = 0;
        
        // Contar itens OK, N/A e com status definido
        foreach($checklistItems as $item) {
            $status = $teste->{$item . '_status'};
            if($status) {
                $totalItensComStatus++;
                if($status === 'ok' || $status === 'nao_aplica') {
                    $itensOkOuNA++;
                }
            }
        }
        
        // Equipamento completo se todos os itens com status estão OK ou N/A
        return $totalItensComStatus > 0 && $itensOkOuNA === $totalItensComStatus;
    }

    public function getPercentualPorArea()
    {
        // Método mais direto usando Collections
        $testesAgrupados = $this->testes()
            ->with('equipamento.area')
            ->get()
            ->groupBy('equipamento.area.nome');

        $resultado = collect();

        foreach ($testesAgrupados as $nomeArea => $testesArea) {
            $totalTestes = $testesArea->count();
            $testesOk = $testesArea->where('status', 'ok')->count();
            $testesProblema = $testesArea->where('status', 'problema')->count();
            $testesPendentes = $testesArea->where('status', 'pendente')->count();
            
            // Calcular percentual baseado no progresso dos checklists
            $progressoTotal = $testesArea->sum('checklist_progress');
            $percentual = $totalTestes > 0 ? round($progressoTotal / $totalTestes, 1) : 0;
            
            $area = $testesArea->first()->equipamento->area;
            
            $resultado->push((object) [
                'id' => $area->id,
                'nome' => $nomeArea,
                'total_testes' => $totalTestes,
                'testes_ok' => $testesOk,
                'testes_problema' => $testesProblema,
                'testes_pendentes' => $testesPendentes,
                'percentual' => $percentual
            ]);
        }

        return $resultado->sortBy('nome');
    }

    public function getDuracaoRealAttribute()
    {
        if (!$this->data_fim) return null;
        
        return round($this->data_inicio->diffInHours($this->data_fim), 2);
    }

    public function getDuracaoAtualAttribute()
    {
        return round($this->data_inicio->diffInHours(now()), 2);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'em_andamento' => 'primary',
            'concluida' => 'success',
            'cancelada' => 'secondary',
            default => 'secondary'
        };
    }

    public function getTipoLabelAttribute()
    {
        return match($this->tipo) {
            'preventiva' => 'Preventiva',
            'corretiva' => 'Corretiva',
            'emergencial' => 'Emergencial',
            'programada' => 'Programada',
            default => 'Programada'
        };
    }

    public function getTotalTestesAttribute()
    {
        return $this->testes()->count();
    }

    public function getTestesOkAttribute()
    {
        return $this->testes()->where('status', 'ok')->count();
    }

    public function getTestesProblemaAttribute()
    {
        return $this->testes()->where('status', 'problema')->count();
    }

    public function getTestesPendentesAttribute()
    {
        return $this->testes()->where('status', 'pendente')->count();
    }

    public function scopeRecentes($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
