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
                $query->select([
                    'id',
                    'parada_id',
                    'ar_comprimido_status',
                    'protecoes_eletricas_status',
                    'protecoes_mecanicas_status',
                    'chave_remoto_status',
                    'inspecionado_status'
                ]);
            }])
            ->get();

        $totalEquipamentosGeral = 0;
        $equipamentosCompletoGeral = 0;

        foreach($paradasParaProgresso as $parada) {
            \Log::info("Calculando progresso para parada " . $parada->id);
            $progressoParada = $this->calcularProgressoDinamico($parada);
            \Log::info("Progresso da parada: " . $progressoParada . "%");
            $equipamentosCompletoGeral += ($progressoParada * $parada->testes->count()) / 100;
            $totalEquipamentosGeral += $parada->testes->count();
        }

        $progressoGeral = $totalEquipamentosGeral > 0 ? round(($equipamentosCompletoGeral / $totalEquipamentosGeral) * 100, 1) : 0;
        \Log::info("Progresso geral calculado: " . $progressoGeral . "%");

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

        $progressoTotal = 0;

        foreach($parada->testes as $teste) {
            // Log para debug
            \Log::info("Teste ID: " . $teste->id . ", Progress: " . $teste->checklist_progress);
            $progressoTotal += intval($teste->checklist_progress);
        }

        $progresso = round($progressoTotal / $totalEquipamentos, 1);
        \Log::info("Progresso da parada " . $parada->id . ": " . $progresso . "%");
        
        return $progresso;
    }

    private function verificarEquipamentoCompleto($teste)
    {
        // Log para debug
        \Log::info("Verificando equipamento completo - Teste ID: " . $teste->id . ", Progress: " . $teste->checklist_progress);
        return intval($teste->checklist_progress) === 100;
    }
}
