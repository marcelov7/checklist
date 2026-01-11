@extends('layouts.app')

@section('content')
<div class="container-fluid px-3">
    <!-- Cabeçalho da Página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 d-print-none">
        <div class="mb-3 mb-lg-0">
            <h1 class="h2 mb-2">
                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                Relatório de Pendências
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('paradas.show', $parada) }}">{{ $parada->nome }}</a></li>
                    <li class="breadcrumb-item active">Pendências</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-lg-auto">
            <button onclick="window.open('{{ route('paradas.pendencias-print', $parada) }}', '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes'); return false;" class="btn btn-danger flex-fill flex-sm-grow-0">
                <i class="fas fa-print me-1"></i>
                <span class="d-none d-sm-inline">Versão para </span>Impressão
            </button>
            <!-- <button onclick="window.print()" class="btn btn-outline-danger flex-fill flex-sm-grow-0 d-none d-lg-inline-block">
                <i class="fas fa-print me-1"></i>Imprimir Página
            </button>  -->
            <a href="{{ route('paradas.relatorio', $parada) }}" class="btn btn-outline-primary flex-fill flex-sm-grow-0">
                <i class="fas fa-chart-bar me-1"></i>
                <span class="d-none d-sm-inline">Relatório </span>Completo
            </a>
            <a href="{{ route('paradas.historico') }}" class="btn btn-outline-secondary flex-fill flex-sm-grow-0">
                <i class="fas fa-arrow-left me-1"></i>
                <span class="d-none d-sm-inline">Voltar ao </span>Histórico
            </a>
        </div>
    </div>

    <!-- Card Principal da Parada -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ $parada->nome }} - Pendências
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Tipo:</strong> 
                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $parada->tipo)) }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $parada->status === 'em_andamento' ? 'warning' : ($parada->status === 'concluida' ? 'success' : 'secondary') }}">
                        {{ ucfirst(str_replace('_', ' ', $parada->status)) }}
                    </span>
                </div>
                <div class="col-md-3">
                    <strong>Data Início:</strong> {{ $parada->data_inicio ? \Carbon\Carbon::parse($parada->data_inicio)->format('d/m/Y H:i') : 'Não informada' }}
                </div>
                <div class="col-md-3">
                    <strong>Total Pendências:</strong> 
                    <span class="badge bg-danger fs-6">{{ $testesPendentes->count() }}</span>
                </div>
            </div>

            <!-- Contadores -->
            @php
                $totalEquipamentosPendentes = $testesPendentes->count();
                $equipamentosComProblema = $testesPendentes->filter(function($teste) {
                    return in_array('problema', [
                        $teste->ar_comprimido_status,
                        $teste->protecoes_eletricas_status, 
                        $teste->protecoes_mecanicas_status,
                        $teste->chave_remoto_status,
                        $teste->inspecao_visual_status
                    ]);
                })->count();
                $equipamentosSemTeste = $totalEquipamentosPendentes - $equipamentosComProblema;
            @endphp

            <div class="row text-center mt-4">
                <div class="col-4">
                    <div class="border rounded p-3 bg-danger text-white h-100">
                        <div class="h2 mb-1">{{ $equipamentosComProblema }}</div>
                        <small>Com Problemas</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="border rounded p-3 bg-warning text-dark h-100">
                        <div class="h2 mb-1">{{ $equipamentosSemTeste }}</div>
                        <small>Não Testados</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="border rounded p-3 bg-primary text-white h-100">
                        <div class="h2 mb-1">{{ $totalEquipamentosPendentes }}</div>
                        <small>Total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($testesPendentes->isEmpty())
        <!-- Quando não há pendências -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                <h3 class="text-success mt-3">Parabéns! Nenhuma Pendência Encontrada</h3>
                <p class="text-muted">Todos os equipamentos desta parada estão com status COMPLETO.</p>
                <a href="{{ route('paradas.relatorio', $parada) }}" class="btn btn-success">
                    <i class="fas fa-chart-bar me-1"></i>Ver Relatório Completo
                </a>
            </div>
        </div>
    @else
        <!-- Equipamentos com pendências agrupados por área -->
        @foreach($testesPendentes->groupBy('equipamento.area.nome') as $nomeArea => $testesArea)
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-industry me-2"></i>
                        {{ $nomeArea }} 
                        <span class="badge bg-danger ms-2">{{ $testesArea->count() }} equipamento(s) pendente(s)</span>
                    </h5>
                </div>
                <!-- Versão Desktop - Tabela -->
                <div class="card-body p-0 d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Equipamento</th>
                                    <th>Tag</th>
                                    <th class="text-center">Status Geral</th>
                                    <th>Itens do Checklist</th>
                                    <th>Status dos Itens</th>
                                    <th class="text-center">Testado Por</th>
                                    <th class="text-center">Data/Hora</th>
                                    <th>Obs/Problemas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($testesArea as $teste)
                                    @php
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
                                        
                                        $statusClass = $statusGeral === 'COMPLETO' ? 'success' : 
                                                      ($statusGeral === 'PROBLEMA' ? 'danger' : 
                                                      ($statusGeral === 'EM ANDAMENTO' ? 'warning' : 'secondary'));

                                        $nomeItens = [
                                            'ar_comprimido_status' => 'Ar Comprimido',
                                            'protecoes_eletricas_status' => 'Proteções Elétricas', 
                                            'protecoes_mecanicas_status' => 'Proteções Mecânicas',
                                            'chave_remoto_status' => 'Chave Remoto',
                                            'inspecao_visual_status' => 'Inspeção Visual'
                                        ];
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $teste->equipamento->nome ?? 'N/A' }}</strong></td>
                                        <td>
                                            <code class="bg-light px-2 py-1">{{ $teste->equipamento->tag ?? 'N/A' }}</code>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $statusClass }} fs-6">{{ $statusGeral }}</span>
                                        </td>
                                        <td>
                                            <div class="checklist-simple">
                                                @foreach($checklistItems as $item)
                                                    @php
                                                        $nomeItem = match($item) {
                                                            'ar_comprimido' => 'Ar Comprimido',
                                                            'protecoes_eletricas' => 'Proteções Elétricas',
                                                            'protecoes_mecanicas' => 'Proteções Mecânicas',
                                                            'chave_remoto' => 'Chave Remoto',
                                                            'inspecionado' => 'Inspeção Visual',
                                                            default => ucwords(str_replace('_', ' ', $item))
                                                        };
                                                    @endphp
                                                    <div class="mb-1">{{ $nomeItem }}</div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div class="checklist-simple">
                                                @foreach($checklistItems as $item)
                                                    @php $status = $teste->{$item . '_status'}; @endphp
                                                    <div class="mb-1">
                                                        @if($status === 'ok')
                                                            <span class="text-success"><i class="fas fa-check"></i> OK</span>
                                                        @elseif($status === 'problema')
                                                            <span class="text-danger"><i class="fas fa-times"></i> PROBLEMA</span>
                                                        @elseif($status === 'nao_aplica')
                                                            <span class="text-info"><i class="fas fa-minus"></i> N/A</span>
                                                        @else
                                                            <span class="text-warning"><i class="fas fa-clock"></i> PENDENTE</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ $teste->testado_por ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $teste->updated_at ? $teste->updated_at->format('d/m H:i') : '-' }}
                                        </td>
                                        <td>
                                            @if($teste->observacoes || $teste->problema)
                                                <div class="text-muted small">
                                                    @if($teste->problema)
                                                        <div class="text-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            {{ $teste->problema }}
                                                        </div>
                                                    @endif
                                                    @if($teste->observacoes)
                                                        <div class="mt-1">{{ $teste->observacoes }}</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Versão Mobile - Cards -->
                <div class="card-body p-2 d-lg-none">
                    @php $uniqueTestes = collect($testesArea)->unique('equipamento_id'); @endphp
                    @foreach($uniqueTestes as $teste)
                        @php
                            $checklistItems = ['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'];
                            
                            $hasProblema = false;
                            $hasOk = false;
                            $itensOkOuNA = 0;
                            
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
                                $statusGeral = 'COMPLETO';
                            } elseif($hasOk) {
                                $statusGeral = 'EM ANDAMENTO';
                            }

                            $checklistLabels = [
                                'ar_comprimido' => 'Ar Comprimido',
                                'protecoes_eletricas' => 'Proteções Elétricas',
                                'protecoes_mecanicas' => 'Proteções Mecânicas',
                                'chave_remoto' => 'Chave Remoto',
                                'inspecionado' => 'Inspeção Visual'
                            ];
                        @endphp
                        
                        <div class="card mb-2 border-start border-3 @if($statusGeral === 'COMPLETO') border-success @elseif($statusGeral === 'PROBLEMA') border-danger @elseif($statusGeral === 'EM ANDAMENTO') border-warning @else border-secondary @endif">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <h6 class="card-title mb-1 fw-bold">{{ $teste->equipamento->nome ?? 'N/A' }}</h6>
                                        <p class="card-text mb-2">
                                            <small class="text-muted">Tag: <code>{{ $teste->equipamento->tag ?? 'N/A' }}</code></small>
                                        </p>
                                    </div>
                                    <div class="col-4 text-end">
                                        @if($statusGeral === 'COMPLETO')
                                            <span class="badge bg-success">COMPLETO</span>
                                        @elseif($statusGeral === 'PROBLEMA')
                                            <span class="badge bg-danger">PROBLEMA</span>
                                        @elseif($statusGeral === 'EM ANDAMENTO')
                                            <span class="badge bg-warning text-dark">EM ANDAMENTO</span>
                                        @else
                                            <span class="badge bg-secondary">PENDENTE</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Checklist Items - Mostrar apenas Problemas e Pendentes -->
                                @php
                                    $itensProblematicos = [];
                                    foreach($checklistLabels as $item => $label) {
                                        $status = $teste->{$item . '_status'};
                                        if($status === 'problema' || (!$status || $status === 'pendente')) {
                                            $itensProblematicos[$item] = ['label' => $label, 'status' => $status];
                                        }
                                    }
                                @endphp
                                
                                @if(!empty($itensProblematicos))
                                    <div class="mt-3">
                                        <small class="fw-medium text-muted d-block mb-2">ITENS QUE PRECISAM DE ATENÇÃO:</small>
                                        <div class="mt-2">
                                            @foreach($itensProblematicos as $item => $dados)
                                                <div class="d-flex justify-content-between align-items-center py-2">
                                                    <span class="text-dark">{{ $dados['label'] }}</span>
                                                    @if($dados['status'] === 'problema')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times me-1"></i>PROBLEMA
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-clock me-1"></i>PENDENTE
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Problemas -->
                                @php
                                    $temProblemas = false;
                                    $problemas = [];
                                    foreach(['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'] as $item) {
                                        $problema = $teste->{$item . '_problema'};
                                        if($problema) {
                                            $problemas[] = $problema;
                                            $temProblemas = true;
                                        }
                                    }
                                @endphp
                                
                                @if($temProblemas || $teste->problema_descricao)
                                    <div class="mt-3 p-2 bg-danger bg-opacity-10 rounded">
                                        <small class="fw-medium text-danger d-block mb-1">PROBLEMAS IDENTIFICADOS:</small>
                                        <div class="mt-1">
                                            @foreach($problemas as $problema)
                                                <small class="d-block text-danger">• {{ $problema }}</small>
                                            @endforeach
                                            @if($teste->problema_descricao)
                                                <small class="d-block text-danger"><strong>Geral:</strong> {{ $teste->problema_descricao }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Informações Adicionais -->
                                <div class="mt-3 pt-2 border-top">
                                    <div class="row text-muted">
                                        <div class="col-6">
                                            <small><strong>Testado por:</strong><br>{{ $teste->testado_por ?? 'Não informado' }}</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small><strong>Data/Hora:</strong><br>
                                                @if($teste && $teste->updated_at)
                                                    {{ $teste->updated_at->format('d/m/Y H:i') }}
                                                @else
                                                    Não testado
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    
                                    @if($teste->observacoes)
                                        <div class="mt-2">
                                            <small><strong>Observações:</strong> {{ $teste->observacoes }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Seção de Imagens dos Problemas Pendentes -->
                @php
                    $imagensProblemasPendentes = false;
                    $imagensPorEquipamento = [];
                    
                    foreach($testesArea as $teste) {
                        $imagensEquipamento = [];
                        foreach($checklistItems as $item) {
                            $fotoProblema = $teste->{$item . '_foto_problema'};
                            $status = $teste->{$item . '_status'};
                            
                            // Mostrar apenas fotos de problemas que ainda não foram resolvidos
                            if($fotoProblema && $status === 'problema') {
                                $imagensEquipamento[$item] = [
                                    'nome' => match($item) {
                                        'ar_comprimido' => 'Ar Comprimido',
                                        'protecoes_eletricas' => 'Proteções Elétricas',
                                        'protecoes_mecanicas' => 'Proteções Mecânicas',
                                        'chave_remoto' => 'Chave Remoto',
                                        'inspecionado' => 'Inspeção Visual',
                                        default => ucwords(str_replace('_', ' ', $item))
                                    },
                                    'foto_problema' => $fotoProblema,
                                    'problema_descricao' => $teste->{$item . '_problema'}
                                ];
                                $imagensProblemasPendentes = true;
                            }
                        }
                        
                        if(!empty($imagensEquipamento)) {
                            $imagensPorEquipamento[$teste->equipamento->nome] = [
                                'equipamento' => $teste->equipamento,
                                'imagens' => $imagensEquipamento
                            ];
                        }
                    }
                @endphp

                @if($imagensProblemasPendentes)
                    <div class="card-footer bg-light">
                        <h6 class="mb-3">
                            <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                            Problemas que Precisam de Resolução - {{ $nomeArea }}
                        </h6>
                        
                        @foreach($imagensPorEquipamento as $nomeEquipamento => $dadosEquipamento)
                            <div class="mb-4">
                                <h6 class="text-danger mb-2">
                                    <i class="fas fa-cog me-1"></i>{{ $nomeEquipamento }} 
                                    <small class="text-muted">({{ $dadosEquipamento['equipamento']->tag }})</small>
                                </h6>
                                
                                <div class="row g-3">
                                    @foreach($dadosEquipamento['imagens'] as $item => $dados)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card border border-danger shadow-sm">
                                                <div class="card-header bg-danger text-white py-2">
                                                    <small class="fw-bold">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $dados['nome'] }}
                                                    </small>
                                                </div>
                                                <div class="card-body p-2">
                                                    <div class="mb-2">
                                                        <small class="text-danger fw-bold d-block mb-1">
                                                            Problema Identificado:
                                                        </small>
                                                        @if($dados['problema_descricao'])
                                                            <small class="d-block text-muted mb-2">{{ $dados['problema_descricao'] }}</small>
                                                        @endif
                                                        <img src="{{ Storage::url($dados['foto_problema']) }}" 
                                                             class="img-fluid rounded cursor-pointer" 
                                                             style="max-height: 150px; width: 100%; object-fit: cover;"
                                                             onclick="mostrarFoto('{{ Storage::url($dados['foto_problema']) }}', 'Problema: {{ $dados['nome'] }}')"
                                                             alt="Problema: {{ $dados['nome'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach


    @endif
</div>

<style>
    .checklist-simple {
        font-size: 0.85rem;
        line-height: 1.3;
    }
    
    .checklist-simple strong {
        font-weight: 600;
    }

    /* Estilos Mobile Responsivos */
    @media (max-width: 991.98px) {
        .main-content {
            margin-top: 100px !important;
            padding: 15px 10px !important;
        }
        
        .container-fluid {
            padding: 0 10px !important;
        }
        
        /* Header compacto em mobile */
        .card-header .d-flex {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .card-header .d-flex > div:first-child {
            text-align: center !important;
        }
        
        /* Melhorar botões do cabeçalho em mobile */
        .d-flex.gap-2 {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .btn {
            font-size: 0.875rem !important;
            padding: 0.5rem 0.75rem !important;
        }
        
        /* Cards de área em mobile */
        .card {
            margin-bottom: 0.75rem !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
        
        .card-header {
            padding: 0.75rem !important;
        }
        
        .card-header h5, .card-header h6 {
            font-size: 1rem !important;
        }
        
        /* Cards de equipamentos pendentes - Similar ao relatório */
        .card-body .card {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            margin-bottom: 0.5rem !important;
        }
        
        .card-title {
            font-size: 1rem !important;
            margin-bottom: 0.5rem !important;
        }
        
        .badge {
            font-size: 0.75rem !important;
            padding: 0.375em 0.5em !important;
        }
        
        /* Espaçamento dos itens do checklist */
        .d-flex.justify-content-between {
            padding: 0.375rem 0 !important;
            border-bottom: 1px solid rgba(0,0,0,0.1) !important;
        }
        
        .d-flex.justify-content-between:last-child {
            border-bottom: none !important;
        }
        
        /* Cards de resumo mais compactos em mobile */
        .row.g-3 {
            margin-top: 0.5rem !important;
        }
        
        .card-body.py-3 {
            padding: 0.75rem !important;
        }
    }
    
    @media (max-width: 767.98px) {
        .main-content {
            margin-top: 120px !important;
            padding: 10px 5px !important;
        }
        
        .container-fluid {
            padding: 0 5px !important;
        }
        
        /* Cabeçalho mais compacto */
        .d-flex.flex-column.flex-lg-row {
            text-align: center;
        }
        
        .d-flex.gap-2 {
            margin-top: 0.75rem;
        }
        
        .btn {
            font-size: 0.8rem !important;
            padding: 0.4rem 0.6rem !important;
        }
        
        /* Cards ainda menores - igual ao relatório */
        .card {
            margin-bottom: 0.5rem !important;
        }
        
        .card-header {
            padding: 0.5rem !important;
        }
        
        .card-body {
            padding: 0.5rem !important;
        }
        
        .card-body .card {
            margin-bottom: 0.4rem !important;
        }
        
        .card-body .card .card-body {
            padding: 0.6rem !important;
        }
        
        /* Badges menores */
        .badge {
            font-size: 0.7rem !important;
            padding: 0.25em 0.4em !important;
        }
        
        /* Texto menor nos cards */
        .card-title {
            font-size: 0.9rem !important;
            margin-bottom: 0.25rem !important;
        }
        
        small {
            font-size: 0.75rem !important;
        }
        
        /* Ajuste dos itens do checklist */
        .d-flex.justify-content-between {
            padding: 0.2rem 0 !important;
            margin-bottom: 0.2rem !important;
        }
        
        /* Header do card principal mais compacto */
        .card-header .fs-4 {
            font-size: 1.2rem !important;
        }
        
        .card-header h5 {
            font-size: 1rem !important;
        }
        
        .card-header small {
            font-size: 0.7rem !important;
        }
        
        /* Cards de resumo em coluna em mobile */
        .row.g-3 .col-md-4 {
            margin-bottom: 0.5rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .main-content {
            margin-top: 140px !important;
            padding: 5px !important;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
        
        /* Cards ultra compactos */
        .card {
            margin-bottom: 0.5rem !important;
        }
        
        .card-header {
            padding: 0.4rem !important;
        }
        
        .card-header h5, .card-header h6 {
            font-size: 0.9rem !important;
        }
        
        .card-body .card .card-body {
            padding: 0.5rem !important;
        }
        
        /* Reorganizar informações em telas muito pequenas */
        .row {
            margin: 0 !important;
        }
        
        .col-8, .col-4, .col-6 {
            padding: 0 0.25rem !important;
        }
        
        .card-title {
            font-size: 0.85rem !important;
            margin-bottom: 0.25rem !important;
        }
        
        .badge {
            font-size: 0.65rem !important;
            padding: 0.2em 0.3em !important;
        }
        
        small {
            font-size: 0.7rem !important;
        }
        
        /* Espaçamento entre itens do checklist */
        .d-flex.justify-content-between {
            margin-bottom: 0.25rem !important;
        }
        
        /* Botões mais compactos */
        .btn {
            font-size: 0.75rem !important;
            padding: 0.3rem 0.5rem !important;
        }
        
        /* Cards de pendências ainda mais compactos */
        .border-bottom {
            padding-bottom: 0.1rem !important;
            margin-bottom: 0.1rem !important;
        }
    }

    @media print {
        .btn, .breadcrumb, nav, .d-print-none {
            display: none !important;
        }
        
        .d-lg-none { display: none !important; }
        .d-none.d-lg-block { display: block !important; }
        
        .card {
            border: 1px solid #000 !important;
            break-inside: avoid;
        }
        
        .card-header {
            background: #f0f0f0 !important;
            color: #000 !important;
        }

        .table th {
            background: #f0f0f0 !important;
            color: #000 !important;
        }

        .text-success { color: #28a745 !important; }
        .text-danger { color: #dc3545 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-info { color: #17a2b8 !important; }
        
        .badge {
            border: 1px solid #000 !important;
        }
        
        /* Ocultar seção de imagens na impressão */
        .card-footer {
            display: none !important;
        }
        
        .main-content { margin-top: 0 !important; }
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    
    .cursor-pointer:hover {
        opacity: 0.8;
        transform: scale(1.02);
        transition: all 0.2s ease;
    }

    /* CORREÇÃO: Garantir visibilidade de todos os títulos e textos */
    .card-title,
    .card-title.fw-bold,
    h6.card-title,
    h6.card-title.mb-1,
    h6.card-title.mb-1.fw-bold {
        color: #212529 !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    /* Garantir que texto dos equipamentos seja sempre visível */
    .card-body .card-title,
    .card-body h6,
    .card-body .text-muted,
    .card-body small {
        color: #212529 !important;
    }
    
    .card-body .text-muted,
    .card-body small.text-muted {
        color: #6c757d !important;
    }
    
    /* Forçar visibilidade em todos os cards */
    .card .card-body * {
        color: inherit !important;
    }
    
    .card .card-body h1,
    .card .card-body h2, 
    .card .card-body h3,
    .card .card-body h4,
    .card .card-body h5,
    .card .card-body h6 {
        color: #212529 !important;
    }

    /* Melhorias específicas para cards mobile */
    .border-start.border-3 {
        border-left-width: 4px !important;
    }
    
    .bg-opacity-10 {
        background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
    }
    
    .bg-opacity-25 {
        background-color: rgba(var(--bs-success-rgb), 0.25) !important;
    }

    /* Animações suaves */
    .card {
        transition: box-shadow 0.15s ease-in-out;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Melhor visualização do status nos cards mobile */
    .border-danger {
        border-color: #dc3545 !important;
    }
    
    .border-warning {
        border-color: #ffc107 !important;
    }
    
    .border-success {
        border-color: #198754 !important;
    }
    
    .border-secondary {
        border-color: #6c757d !important;
    }
    
    /* SOLUÇÃO DEFINITIVA: Títulos sempre visíveis em mobile */
    @media (max-width: 991.98px) {
        .card .card-body .card-title,
        .card .card-body h6.card-title,
        .card .card-body h6.card-title.mb-1.fw-bold {
            color: #212529 !important;
            text-shadow: none !important;
            background: transparent !important;
        }
        
        .d-lg-none .card-title,
        .d-lg-none h6 {
            color: #212529 !important;
        }
        
        /* Garantir contraste adequado em todos os elementos */
        .card-body .row .col-8 h6,
        .card-body .row .col-8 .card-title {
            color: #212529 !important;
            font-weight: 600 !important;
        }
        
        .card-body small,
        .card-body .text-muted {
            color: #6c757d !important;
        }
    }
    
    /* Override para qualquer CSS conflitante */
    body .card .card-body h6.card-title {
        color: #212529 !important;
    }

    /* Estilos específicos para badges nos cards de pendências */
    .card-body .badge {
        font-weight: 600;
        letter-spacing: 0.025em;
    }

    /* Melhor espaçamento dos itens de checklist */
    .d-flex.justify-content-between span {
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Destaque para seção de problemas */
    .bg-danger.bg-opacity-10 {
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    /* Melhor alinhamento do conteúdo */
    .card-body .row .col-8 h6 {
        line-height: 1.3;
    }

    .card-body .row .col-4 .badge {
        white-space: nowrap;
    }
</style>

<!-- Modal para visualizar fotos -->
<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalTitle">Visualizar Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fotoModalImage" src="" class="img-fluid" alt="Foto" style="max-height: 70vh;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarFoto(url, titulo) {
    document.getElementById('fotoModalImage').src = url;
    document.getElementById('fotoModalTitle').textContent = titulo;
    const modal = new bootstrap.Modal(document.getElementById('fotoModal'));
    modal.show();
}
</script>

@endsection