<?php

namespace App\Http\Controllers;

use App\Models\Parada;
use App\Models\Area;
use App\Models\Equipamento;
use App\Models\Teste;
use Illuminate\Http\Request;

class ParadaController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function dashboard()
    {
        $totalParadas = Parada::count();
        $paradasAtivas = Parada::where('status', 'em_andamento')->count();
        $paradasConcluidas = Parada::where('status', 'concluida')->count();
        $totalEquipamentos = Equipamento::count();
        
        $paradasAtivasLista = Parada::where('status', 'em_andamento')
                                   ->orderBy('created_at', 'desc')
                                   ->limit(5)
                                   ->get();
        
        $ultimasAtividades = Parada::orderBy('created_at', 'desc')
                                  ->limit(5)
                                  ->get();

        return view('dashboard', compact(
            'totalParadas', 
            'paradasAtivas', 
            'paradasConcluidas', 
            'totalEquipamentos',
            'paradasAtivasLista',
            'ultimasAtividades'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paradas = Parada::where('status', 'em_andamento')
                         ->orderBy('created_at', 'desc')
                         ->get();
        return view('paradas.index', compact('paradas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paradas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'macro' => 'required|string|max:50|unique:paradas,macro',
                'nome' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'data_inicio' => 'required|date',
                'duracao_prevista_horas' => 'nullable|integer|min:1|max:720',
                'equipe_responsavel' => 'nullable|string',
                'tipo' => 'required|in:preventiva,corretiva,emergencial,programada',
            ]);

            $parada = Parada::create($validated);

            // Redirecionar para seleção de áreas e equipamentos
            return redirect()->route('paradas.select-equipment', $parada)->with('success', 'Parada criada! Agora selecione as áreas e equipamentos.');
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao criar parada: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show equipment selection for parada
     */
    public function selectEquipment(Parada $parada)
    {
        $areas = Area::with(['equipamentosAtivos'])->get();
        
        // Obter equipamentos já selecionados (se houver)
        $equipamentosSelecionados = $parada->testes()->pluck('equipamento_id')->toArray();
        
        return view('paradas.select-equipment', compact('parada', 'areas', 'equipamentosSelecionados'));
    }

    /**
     * Debug equipment selection
     */
    public function debugEquipment(Parada $parada)
    {
        $areas = Area::with(['equipamentosAtivos'])->get();
        $equipamentosSelecionados = $parada->testes()->pluck('equipamento_id')->toArray();
        
        return view('paradas.debug-equipment', compact('parada', 'areas', 'equipamentosSelecionados'));
    }

    /**
     * Store selected equipment for parada
     */
    public function storeEquipment(Request $request, Parada $parada)
    {
        // Debug: verificar o que está sendo enviado
        \Log::info('Equipamentos recebidos:', $request->equipamentos ?? []);
        
        $request->validate([
            'equipamentos' => 'required|array|min:1',
            'equipamentos.*' => 'exists:equipamentos,id'
        ]);

        // Remover testes existentes se houver
        Teste::where('parada_id', $parada->id)->delete();

        // Criar testes APENAS para os equipamentos selecionados
        foreach ($request->equipamentos as $equipamentoId) {
            Teste::create([
                'parada_id' => $parada->id,
                'equipamento_id' => $equipamentoId,
                'status' => 'pendente'
            ]);
        }

        return redirect()->route('paradas.show', $parada)
                        ->with('success', 'Equipamentos selecionados (' . count($request->equipamentos) . ') adicionados à parada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Parada $parada)
    {
        // Buscar apenas as áreas que têm equipamentos nesta parada
        $equipamentosIds = $parada->testes()->pluck('equipamento_id');
        
        // Se não há equipamentos selecionados, mostrar mensagem apropriada
        if ($equipamentosIds->isEmpty()) {
            return view('paradas.show_v2', compact('parada'))
                   ->with('semEquipamentos', true);
        }
        
        // Buscar áreas que contêm os equipamentos selecionados para esta parada
        $areas = Area::whereHas('equipamentos', function($query) use ($equipamentosIds) {
            $query->whereIn('id', $equipamentosIds);
        })->with(['equipamentos' => function($query) use ($parada, $equipamentosIds) {
            // Buscar apenas os equipamentos que fazem parte desta parada
            $query->whereIn('id', $equipamentosIds)
                  ->with(['testes' => function($testQuery) use ($parada) {
                      $testQuery->where('parada_id', $parada->id);
                  }]);
        }])->get();

        $percentualGeral = $parada->getPercentualCompleto();
        $percentualPorAreaCollection = $parada->getPercentualPorArea();
        
        // Converter Collection em array associativo para facilitar acesso no template
        $percentualPorArea = $percentualPorAreaCollection->pluck('percentual', 'id')->toArray();

        return view('paradas.show_v2', compact('parada', 'areas', 'percentualGeral', 'percentualPorArea'));
    }

    /**
     * Display the specified resource (layout v2).
     */
    public function showV2(Parada $parada)
    {
        $equipamentosIds = $parada->testes()->pluck('equipamento_id');

        if ($equipamentosIds->isEmpty()) {
            return view('paradas.show_v2', compact('parada'))
                   ->with('semEquipamentos', true);
        }

        $areas = Area::whereHas('equipamentos', function($query) use ($equipamentosIds) {
            $query->whereIn('id', $equipamentosIds);
        })->with(['equipamentos' => function($query) use ($parada, $equipamentosIds) {
            $query->whereIn('id', $equipamentosIds)
                  ->with(['testes' => function($testQuery) use ($parada) {
                      $testQuery->where('parada_id', $parada->id);
                  }]);
        }])->get();

        $percentualGeral = $parada->getPercentualCompleto();
        $percentualPorAreaCollection = $parada->getPercentualPorArea();
        $percentualPorArea = $percentualPorAreaCollection->pluck('percentual', 'id')->toArray();

        return view('paradas.show_v2', compact('parada', 'areas', 'percentualGeral', 'percentualPorArea'));
    }

    /**
     * Get updated progress for parada
     */
    public function progresso(Parada $parada)
    {
        $percentualGeral = $parada->getPercentualCompleto();
        $percentualPorArea = $parada->getPercentualPorArea();
        
        // Contagens para exibição (equipamentos resolvidos e pendentes)
        $totalTestes = $parada->total_testes;
        $testesOk = $parada->testes_ok;
        $testesPendentes = max(0, $totalTestes - $testesOk);

        return response()->json([
            'success' => true,
            'percentual' => $percentualGeral,
            'areas' => $percentualPorArea,
            'total_testes' => $totalTestes,
            'testes_ok' => $testesOk,
            'testes_pendentes' => $testesPendentes,
        ])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }

    /**
     * Debug method to check calculations
     */
    public function debug(Parada $parada)
    {
        $testes = $parada->testes()->with('equipamento.area')->get();
        $percentualPorArea = $parada->getPercentualPorArea();
        
        $debug = [
            'parada' => [
                'id' => $parada->id,
                'nome' => $parada->nome,
                'percentual_geral' => $parada->getPercentualCompleto()
            ],
            'testes' => $testes->map(function($teste) {
                return [
                    'area' => $teste->equipamento->area->nome,
                    'equipamento' => $teste->equipamento->nome,
                    'tag' => $teste->equipamento->tag,
                    'status' => $teste->status
                ];
            }),
            'percentuais_por_area' => $percentualPorArea
        ];
        
        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Print version of parada report
     */
    public function print(Parada $parada)
    {
        // Usar a mesma lógica da página de relatório atualizada
        // Forçar atualização dos dados da parada
        $parada->refresh();
        
        // Recarregar relacionamentos com dados atuais
        $parada->load(['testes' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }, 'testes.equipamento.area']);
        
        // Limpar cache de atributos calculados
        $parada->unsetRelation('testes');
        $parada->load(['testes' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }, 'testes.equipamento.area']);

        return view('paradas.print', compact('parada'));
    }

    /**
     * Display pendências report - only equipment with incomplete status
     */
    public function pendencias(Parada $parada)
    {
        // Forçar atualização dos dados da parada
        $parada->refresh();
        
        // Recarregar relacionamentos com dados atuais
        $parada->load(['testes' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }, 'testes.equipamento.area']);
        
        // Limpar cache de atributos calculados
        $parada->unsetRelation('testes');
        $parada->load(['testes' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }, 'testes.equipamento.area']);

        // Filtrar apenas equipamentos que NÃO estão com status COMPLETO
        $testesPendentes = $parada->testes->filter(function($teste) {
            // Usar a mesma lógica do relatório principal
            $checklistItems = ['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'];
            
            $hasProblema = false;
            $hasOk = false;
            $itensOkOuNA = 0;
            
            // Contar itens OK, N/A e problemas
            foreach($checklistItems as $item) {
                $status = $teste->{$item . '_status'};
                if($status === 'ok') {
                    $hasOk = true;
                    $itensOkOuNA++;
                } elseif($status === 'nao_aplica') {
                    $itensOkOuNA++;
                } elseif($status === 'problema') {
                    $hasProblema = true;
                }
            }
            
            // Contar total de itens que têm algum status definido
            $totalItensComStatus = 0;
            foreach($checklistItems as $item) {
                if($teste->{$item . '_status'}) {
                    $totalItensComStatus++;
                }
            }
            
            $statusGeral = 'PENDENTE';
            if($hasProblema) {
                $statusGeral = 'PROBLEMA';
            } elseif($totalItensComStatus > 0 && $itensOkOuNA === $totalItensComStatus) {
                // Se todos os itens com status estão OK ou N/A = COMPLETO
                $statusGeral = 'COMPLETO';
            } elseif($hasOk) {
                $statusGeral = 'EM ANDAMENTO';
            }
            
            // Retornar apenas os que NÃO são COMPLETO
            return $statusGeral !== 'COMPLETO';
        });

        return view('paradas.pendencias', compact('parada', 'testesPendentes'));
    }

    /**
     * Print version of pendencias report
     */
    public function pendenciasPrint(Parada $parada)
    {
        // Usar a mesma lógica do método pendencias
        // Forçar atualização dos dados da parada
        $parada->refresh();
        
        // Recarregar relacionamentos com dados atuais
        $parada->load(['testes' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }, 'testes.equipamento.area']);
        
        // Limpar cache de atributos calculados
        $parada->unsetRelation('testes');
        $parada->load(['testes' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }, 'testes.equipamento.area']);

        // Filtrar apenas equipamentos que NÃO estão com status COMPLETO
        $testesPendentes = $parada->testes->filter(function($teste) {
            // Usar a mesma lógica do relatório principal
            $checklistItems = ['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'];
            
            $hasProblema = false;
            $hasOk = false;
            $itensOkOuNA = 0;
            
            // Contar itens OK, N/A e problemas
            foreach($checklistItems as $item) {
                $status = $teste->{$item . '_status'};
                if($status === 'ok') {
                    $hasOk = true;
                    $itensOkOuNA++;
                } elseif($status === 'nao_aplica') {
                    $itensOkOuNA++;
                } elseif($status === 'problema') {
                    $hasProblema = true;
                }
            }
            
            // Contar total de itens que têm algum status definido
            $totalItensComStatus = 0;
            foreach($checklistItems as $item) {
                if($teste->{$item . '_status'}) {
                    $totalItensComStatus++;
                }
            }
            
            $statusGeral = 'PENDENTE';
            if($hasProblema) {
                $statusGeral = 'PROBLEMA';
            } elseif($totalItensComStatus > 0 && $itensOkOuNA === $totalItensComStatus) {
                // Se todos os itens com status estão OK ou N/A = COMPLETO
                $statusGeral = 'COMPLETO';
            } elseif($hasOk) {
                $statusGeral = 'EM ANDAMENTO';
            }
            
            // Retornar apenas os que NÃO são COMPLETO
            return $statusGeral !== 'COMPLETO';
        });

        return view('paradas.pendencias-print', compact('parada', 'testesPendentes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parada $parada)
    {
        return view('paradas.edit', compact('parada'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parada $parada)
    {
        $validated = $request->validate([
            'macro' => 'required|string|max:50|unique:paradas,macro,' . $parada->id,
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'duracao_prevista_horas' => 'nullable|integer|min:1|max:720',
            'equipe_responsavel' => 'nullable|string',
            'tipo' => 'required|in:preventiva,corretiva,emergencial,programada',
            'status' => 'required|in:em_andamento,concluida,cancelada',
        ]);

        $parada->update($validated);

        return redirect()->route('paradas.show', $parada)->with('success', 'Parada atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parada $parada)
    {
        $parada->delete();
        
        return redirect()->route('paradas.index')->with('success', 'Parada removida com sucesso!');
    }

    /**
     * Finalizar parada
     */
    public function finalizar(Parada $parada)
    {
        try {
            // Verificar se a parada já está finalizada
            if ($parada->status === 'concluida') {
                $message = 'Esta parada já foi finalizada.';
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }
                
                return redirect()->route('paradas.show', $parada)->with('error', $message);
            }

            // Verificar se todos os testes estão completos
            $percentualCompleto = $parada->getPercentualCompleto();
            if ($percentualCompleto < 100) {
                $message = "Não é possível finalizar a parada. Progresso atual: {$percentualCompleto}%. Complete todos os checklists antes de finalizar.";
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }
                
                return redirect()->route('paradas.show', $parada)->with('error', $message);
            }

            // Finalizar a parada
            $parada->update([
                'status' => 'concluida',
                'data_fim' => now()
            ]);

            $message = 'Parada finalizada com sucesso!';

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->route('paradas.show', $parada)->with('success', $message);

        } catch (\Exception $e) {
            $message = 'Erro interno: ' . $e->getMessage();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            
            return redirect()->route('paradas.show', $parada)->with('error', $message);
        }
    }

    /**
     * Histórico de paradas
     */
    public function historico()
    {
        $query = Parada::with(['testes.equipamento.area']);
        
        // Aplicar filtros se fornecidos
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        if (request('tipo')) {
            $query->where('tipo', request('tipo'));
        }
        
        $paradas = $query->orderBy('created_at', 'desc')->paginate(15);

        $estatisticas = [
            'total' => Parada::count(),
            'concluidas' => Parada::where('status', 'concluida')->count(),
            'em_andamento' => Parada::where('status', 'em_andamento')->count(),
            'canceladas' => Parada::where('status', 'cancelada')->count(),
            'preventivas' => Parada::where('tipo', 'preventiva')->count(),
            'corretivas' => Parada::where('tipo', 'corretiva')->count(),
            'emergenciais' => Parada::where('tipo', 'emergencial')->count(),
        ];

        return view('paradas.historico', compact('paradas', 'estatisticas'));
    }

    /**
     * Relatório detalhado da parada
     */
    public function relatorio(Parada $parada)
    {
        // Forçar atualização dos dados da parada
        $parada->refresh();
        
        // Recarregar relacionamentos com dados atuais
        $parada->load(['testes' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }, 'testes.equipamento.area']);
        
        // Limpar cache de atributos calculados
        $parada->unsetRelation('testes');
        $parada->load(['testes' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }, 'testes.equipamento.area']);
        
        return view('paradas.relatorio', compact('parada'));
    }

    /**
     * Reabrir uma parada finalizada (Apenas Administradores)
     */
    public function reabrir(Parada $parada)
    {
        try {
            // Verificar se o usuário é administrador
            if (session('user.perfil') !== 'admin') {
                $message = 'Apenas administradores podem reabrir paradas finalizadas.';
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 403);
                }
                
                return redirect()->route('paradas.show', $parada)->with('error', $message);
            }

            // Verificar se a parada está realmente finalizada
            if ($parada->status !== 'concluida') {
                $message = 'Esta parada não está finalizada, portanto não pode ser reaberta.';
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }
                
                return redirect()->route('paradas.show', $parada)->with('error', $message);
            }

            // Reabrir a parada
            $parada->update([
                'status' => 'em_andamento',
                'data_fim' => null
            ]);

            // Log da ação para auditoria
            \Illuminate\Support\Facades\Log::info('Parada reaberta por administrador', [
                'parada_id' => $parada->id,
                'parada_nome' => $parada->nome,
                'admin_user' => session('user.name'),
                'admin_id' => session('user.id'),
                'timestamp' => now()
            ]);

            $message = 'Parada reaberta com sucesso!';

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->route('paradas.show', $parada)->with('success', $message);

        } catch (\Exception $e) {
            $message = 'Erro interno: ' . $e->getMessage();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            
            return redirect()->route('paradas.show', $parada)->with('error', $message);
        }
    }
}
