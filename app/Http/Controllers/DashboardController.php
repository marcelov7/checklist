<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parada;
use App\Models\Area;
use App\Models\Equipamento;
use App\Models\Teste;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal
     */
    public function index()
    {
        // Estatísticas gerais
        $totalParadas = Parada::count();
        $paradasAtivas = Parada::where('status', 'em_andamento')->count();
        $paradasConcluidas = Parada::where('status', 'concluida')->count();
        $totalEquipamentos = Equipamento::count();
        $totalAreas = Area::count();
        $totalUsuarios = User::count();
        $usuariosAtivos = User::where('status', 'ativo')->count();
        
        // Paradas recentes (últimas 5) com cálculo de progresso dinâmico
        $paradasRecentes = Parada::with(['testes' => function($query) {
                $query->orderBy('updated_at', 'desc');
            }, 'testes.equipamento.area'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($parada) {
                $parada->progresso_percentual = $this->calcularProgressoDinamico($parada);
                return $parada;
            });

        // Estatísticas por área
        $estatisticasPorArea = Area::withCount([
            'equipamentos',
            'equipamentos as equipamentos_em_parada' => function ($query) {
                $query->whereHas('testes', function ($subQuery) {
                    $subQuery->whereHas('parada', function ($paradaQuery) {
                        $paradaQuery->where('status', 'em_andamento');
                    });
                });
            }
        ])->get();

        // Paradas ativas (lista completa para compatibilidade)
        $paradasAtivasLista = Parada::whereIn('status', ['em_andamento', 'concluida'])
            ->orderBy('created_at', 'desc')
            ->with(['testes' => function($query) {
                $query->orderBy('updated_at', 'desc');
            }, 'testes.equipamento.area'])
            ->get()
            ->map(function ($parada) {
                $parada->progresso_percentual = $this->calcularProgressoDinamico($parada);
                return $parada;
            });

        // Últimas atividades (últimas paradas criadas)
        $ultimasAtividades = Parada::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Testes pendentes
        $testesPendentes = Teste::where('status', 'pendente')
            ->whereHas('parada', function ($query) {
                $query->where('status', 'em_andamento');
            })
            ->with(['equipamento.area', 'parada'])
            ->limit(10)
            ->get();

        // Progresso geral do sistema usando lógica dinâmica
        $paradasParaProgresso = Parada::whereIn('status', ['em_andamento', 'concluida'])
            ->with(['testes' => function($query) {
                $query->orderBy('updated_at', 'desc');
            }])
            ->get();

        $totalEquipamentosGeral = 0;
        $equipamentosCompletoGeral = 0;

        foreach($paradasParaProgresso as $parada) {
            $totalEquipamentosGeral += $parada->testes->count();
            
            foreach($parada->testes as $teste) {
                if($this->verificarEquipamentoCompleto($teste)) {
                    $equipamentosCompletoGeral++;
                }
            }
        }

        $progressoGeral = $totalEquipamentosGeral > 0 ? round(($equipamentosCompletoGeral / $totalEquipamentosGeral) * 100, 1) : 0;

        return view('dashboard', compact(
            'totalParadas',
            'paradasAtivas', 
            'paradasConcluidas',
            'totalEquipamentos',
            'totalAreas',
            'totalUsuarios',
            'usuariosAtivos',
            'paradasRecentes',
            'paradasAtivasLista',
            'ultimasAtividades',
            'estatisticasPorArea',
            'testesPendentes',
            'progressoGeral'
        ));
    }

    /**
     * Calcula o progresso dinâmico de uma parada baseado no status real dos itens do checklist
     */
    private function calcularProgressoDinamico($parada)
    {
        $totalEquipamentos = $parada->testes->count();
        
        if ($totalEquipamentos === 0) {
            return 0;
        }

        $equipamentosCompletos = 0;

        foreach($parada->testes as $teste) {
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
}
