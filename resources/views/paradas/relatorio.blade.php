@extends('layouts.app')

@section('title', 'Relatório da Parada - ' . $parada->nome)

@php
    // Os dados já vêm atualizados do controller
@endphp

@section('content')
<div class="container-fluid px-3">
    <!-- Cabeçalho da Página -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 d-print-none">
        <div>
            <h1 class="h2 mb-2">
                <i class="fas fa-clipboard-list text-primary me-2"></i>
                Relatório da Parada
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('paradas.show', $parada) }}">{{ $parada->nome }}</a></li>
                    <li class="breadcrumb-item active">Relatório</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('paradas.pendencias', $parada) }}" class="btn btn-danger">
                <i class="fas fa-exclamation-triangle me-1"></i>Relatório de Pendências
            </a>
            <button onclick="window.open('{{ route('paradas.print', $parada) }}', '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes'); return false;" class="btn btn-primary">
                <i class="fas fa-print me-1"></i>Versão para Impressão
            </button>
            <!--<button onclick="window.print()" class="btn btn-outline-primary">
                <i class="fas fa-print me-1"></i>Imprimir Página
            </button> -->
            <a href="{{ route('paradas.historico') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Voltar ao Histórico
            </a>
        </div>
    </div>

    <!-- Card Principal da Parada -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-info-circle me-2"></i>{{ $parada->nome }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-medium text-muted" style="width: 40%;">Tipo:</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($parada->tipo) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium text-muted">Status:</td>
                            <td>
                                @if($parada->status == 'em_andamento')
                                    <span class="badge bg-warning">Em Andamento</span>
                                @elseif($parada->status == 'concluida')
                                    <span class="badge bg-success">Concluída</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($parada->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium text-muted">Data Início:</td>
                            <td>{{ $parada->data_inicio->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-medium text-muted" style="width: 40%;">Duração:</td>
                            <td>{{ $parada->duracao_prevista_horas ? $parada->duracao_prevista_horas . ' horas' : 'Não definida' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium text-muted">Equipe:</td>
                            <td>{{ $parada->equipe_responsavel ?? 'Não informada' }}</td>
                        </tr>
                        @if($parada->descricao)
                        <tr>
                            <td class="fw-medium text-muted">Descrição:</td>
                            <td>{{ $parada->descricao }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @php
                // Buscar todos os testes da parada - dados sempre atuais
                $todosOsTestes = $parada->testes()->with('equipamento')->get();
                $totalEquipamentos = $todosOsTestes->count();
                
                // Calcular status baseado no checklist de cada equipamento
                $equipamentosCompletos = 0;
                $equipamentosComProblema = 0;
                $equipamentosPendentes = 0;
                $equipamentosEmAndamento = 0;
                
                foreach($todosOsTestes as $teste) {
                    $checklistItems = ['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'];
                    $hasProblema = false;
                    $hasOk = false;
                    $itensComStatus = 0;
                    $itensResolvidosOuNA = 0; // Itens OK ou N/A
                    
                    foreach($checklistItems as $item) {
                        $status = $teste->{$item . '_status'};
                        if($status) {
                            $itensComStatus++;
                            if($status === 'problema') {
                                $hasProblema = true;
                            } elseif($status === 'ok') {
                                $hasOk = true;
                                $itensResolvidosOuNA++;
                            } elseif($status === 'nao_aplica') {
                                // N/A conta como resolvido
                                $itensResolvidosOuNA++;
                            }
                        }
                    }
                    
                    // Definir status do equipamento
                    if($hasProblema) {
                        $equipamentosComProblema++;
                    } elseif($itensComStatus > 0 && $itensResolvidosOuNA === $itensComStatus) {
                        // Se todos os itens com status estão OK ou N/A = COMPLETO
                        $equipamentosCompletos++;
                    } elseif($hasOk) {
                        $equipamentosEmAndamento++;
                    } else {
                        $equipamentosPendentes++;
                    }
                }
                
                // Calcular percentual de conclusão (apenas equipamentos realmente completos)
                $percentualConclusao = $totalEquipamentos > 0 ? round(($equipamentosCompletos / $totalEquipamentos) * 100, 1) : 0;
                
                // Dados sempre atuais via controller
            @endphp

            <!-- Resumo Estatístico -->
            <div class="row mt-3 pt-3 border-top">
                <div class="col-6 col-lg-3 text-center mb-3 mb-lg-0">
                    <div class="fs-3 fw-bold text-primary">{{ $totalEquipamentos }}</div>
                    <small class="text-muted">Total Equipamentos</small>
                </div>
                <div class="col-6 col-lg-3 text-center mb-3 mb-lg-0">
                    <div class="fs-3 fw-bold text-success">{{ $equipamentosCompletos }}</div>
                    <small class="text-muted">Completos</small>
                </div>
                <div class="col-6 col-lg-3 text-center mb-3 mb-lg-0">
                    <div class="fs-3 fw-bold text-danger">{{ $equipamentosComProblema }}</div>
                    <small class="text-muted">Com Problemas</small>
                </div>
                <div class="col-6 col-lg-3 text-center mb-3 mb-lg-0">
                    <div class="fs-3 fw-bold text-warning">{{ $equipamentosEmAndamento }}</div>
                    <small class="text-muted">Em Andamento</small>
                </div>
            </div>

            <!-- Detalhes Adicionais -->
            @if($equipamentosPendentes > 0)
            <div class="row mt-2">
                <div class="col-12 text-center">
                    <small class="text-muted">
                        <strong>{{ $equipamentosPendentes }}</strong> equipamentos ainda não iniciados
                    </small>
                </div>
            </div>
            @endif

            <!-- Barra de Progresso -->
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-medium">Progresso Geral:</span>
                    <span class="fw-bold">{{ $percentualConclusao }}%</span>
                </div>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar 
                        @if($percentualConclusao >= 100) bg-success
                        @elseif($percentualConclusao >= 75) bg-info
                        @elseif($percentualConclusao >= 50) bg-warning
                        @else bg-danger
                        @endif" 
                        role="progressbar" 
                        style="width: {{ $percentualConclusao }}%">
                        {{ $percentualConclusao }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        // Agrupar testes por área usando os dados já carregados
        $areasTestes = $parada->testes->groupBy('equipamento.area.nome');
    @endphp

    <!-- Detalhamento por Área -->
    @foreach($areasTestes as $nomeArea => $testesArea)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-industry me-2 text-primary"></i>{{ $nomeArea }}
                    </h6>
                    <span class="badge bg-primary">{{ $testesArea->count() }} equipamentos</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 18%;">Equipamento</th>
                                <th style="width: 8%;">Tag</th>
                                <th style="width: 10%;">Status Geral</th>
                                <th style="width: 20%;">Itens do Checklist</th>
                                <th style="width: 15%;">Status dos Itens</th>
                                <th style="width: 12%;">Testado Por</th>
                                <th style="width: 9%;">Data/Hora</th>
                                <th style="width: 8%;">Obs/Problemas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($testesArea as $teste)
                                @php
                                    $equipamento = $teste->equipamento;
                                @endphp
                                <tr>
                                    <td class="fw-medium">{{ $equipamento->nome }}</td>
                                    <td>
                                        <code class="small">{{ $equipamento->tag }}</code>
                                    </td>
                                    <td>
                                        @if($teste)
                                            @php
                                                // Calcular status geral baseado no checklist
                                                $checklistItems = ['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'];
                                                $hasProblema = false;
                                                $hasOk = false;
                                                $itensAplicaveis = 0; // Itens que se aplicam (não são N/A)
                                                $itensOkOuNA = 0; // Itens OK ou N/A
                                                
                                                foreach($checklistItems as $item) {
                                                    $status = $teste->{$item . '_status'};
                                                    if($status) {
                                                        if($status === 'nao_aplica') {
                                                            // N/A conta como "resolvido" mas não aplicável
                                                            $itensOkOuNA++;
                                                        } elseif($status === 'ok') {
                                                            $itensAplicaveis++;
                                                            $itensOkOuNA++;
                                                            $hasOk = true;
                                                        } elseif($status === 'problema') {
                                                            $itensAplicaveis++;
                                                            $hasProblema = true;
                                                        } else {
                                                            $itensAplicaveis++; // Pendente conta como aplicável
                                                        }
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
                                            @endphp
                                            
                                            @if($statusGeral === 'COMPLETO')
                                                <strong class="text-success">COMPLETO</strong>
                                            @elseif($statusGeral === 'PROBLEMA')
                                                <strong class="text-danger">PROBLEMA</strong>
                                            @elseif($statusGeral === 'EM ANDAMENTO')
                                                <strong class="text-warning">EM ANDAMENTO</strong>
                                            @else
                                                <strong class="text-secondary">PENDENTE</strong>
                                            @endif
                                        @else
                                            <strong class="text-muted">NÃO TESTADO</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if($teste)
                                            @php
                                                $checklistItems = [
                                                    'ar_comprimido' => 'Ar Comprimido',
                                                    'protecoes_eletricas' => 'Proteções Elétricas',
                                                    'protecoes_mecanicas' => 'Proteções Mecânicas',
                                                    'chave_remoto' => 'Chave Remoto',
                                                    'inspecionado' => 'Inspeção Visual'
                                                ];
                                            @endphp

                                            @foreach($checklistItems as $item => $label)
                                                @if($teste->{$item . '_status'})
                                                    {{ $label }}<br>
                                                @endif
                                            @endforeach

                                            @if($teste->foto_verificada)
                                                Foto Verificada<br>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($teste)
                                            @foreach($checklistItems as $item => $label)
                                                @php $status = $teste->{$item . '_status'}; @endphp
                                                @if($status)
                                                    @if($status === 'ok')
                                                        <span class="text-success">✓ OK</span>
                                                    @elseif($status === 'problema')
                                                        <span class="text-danger">✗ PROBLEMA</span>
                                                    @elseif($status === 'nao_aplica')
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                    <br>
                                                @endif
                                            @endforeach

                                            @if($teste->foto_verificada)
                                                <span class="text-info">✓ OK</span><br>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $teste->testado_por ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <small>
                                            @if($teste && $teste->updated_at)
                                                {{ $teste->updated_at->format('d/m H:i') }}
                                            @else
                                                -
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($teste)
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
                                            
                                            @if($temProblemas)
                                                @foreach($problemas as $problema)
                                                    <small>• {{ $problema }}</small><br>
                                                @endforeach
                                            @endif
                                            
                                            @if($teste->problema_descricao)
                                                <small><strong>Geral:</strong> {{ $teste->problema_descricao }}</small><br>
                                            @endif

                                            @if($teste->observacoes)
                                                <small><strong>Obs:</strong> {{ $teste->observacoes }}</small>
                                            @endif
                                            
                                            @if(!$temProblemas && !$teste->problema_descricao && !$teste->observacoes)
                                                -
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Resumo de Problemas -->
    @php
        $problemasIdentificados = \App\Models\Teste::where('parada_id', $parada->id)
            ->where('status', 'problema')
            ->with('equipamento.area')
            ->get();
    @endphp

    @if($problemasIdentificados->count() > 0)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Resumo de Problemas Identificados ({{ $problemasIdentificados->count() }})
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Área</th>
                            <th>Equipamento</th>
                            <th>Tag</th>
                            <th>Problema</th>
                            <th>Identificado Por</th>
                            <th>Data/Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($problemasIdentificados as $problema)
                        <tr>
                            <td><strong>{{ $problema->equipamento->area->nome }}</strong></td>
                            <td>{{ $problema->equipamento->nome }}</td>
                            <td><code>{{ $problema->equipamento->tag }}</code></td>
                            <td>{{ $problema->problema_descricao }}</td>
                            <td>{{ $problema->testado_por ?? 'Não informado' }}</td>
                            <td>{{ $problema->updated_at->format('d/m H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

@push('styles')
<style>
    /* Estilos Mobile Responsivos */
    @media (max-width: 767.98px) {
        .main-content {
            margin-top: 120px !important;
            padding: 15px 10px !important;
        }
        
        .container-fluid {
            padding: 0 5px !important;
        }
        
        .card {
            margin-bottom: 1rem !important;
        }
        
        .card-header h6 {
            font-size: 0.9rem !important;
        }
        
        .table-responsive {
            font-size: 0.8rem !important;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem !important;
            font-size: 0.75rem !important;
        }
        
        .badge {
            font-size: 0.65rem !important;
        }
        
        .btn {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
        }
        
        /* Esconder algumas colunas em mobile */
        .table th:nth-child(6),
        .table td:nth-child(6),
        .table th:nth-child(7),
        .table td:nth-child(7) {
            display: none;
        }
    }
    
    @media (max-width: 575.98px) {
        .main-content {
            margin-top: 140px !important;
            padding: 10px 5px !important;
        }
        
        /* Mostrar apenas colunas essenciais em telas muito pequenas */
        .table th:nth-child(4),
        .table td:nth-child(4),
        .table th:nth-child(5),
        .table td:nth-child(5),
        .table th:nth-child(8),
        .table td:nth-child(8) {
            display: none;
        }
    }

    /* Estilos para impressão */
    @media print {
        .d-print-none { display: none !important; }
        .card { page-break-inside: avoid; margin-bottom: 1rem; }
        .table { font-size: 11pt; }
        .table td, .table th { padding: 8pt 4pt; }
        .checklist-simple { font-size: 10pt; line-height: 1.3; }
        .checklist-simple strong { font-weight: bold; }
        .checklist-simple small { font-size: 9pt; }
        body { font-size: 11pt; color: #000; }
        .container-fluid { max-width: none; }
        .main-content { margin-top: 0 !important; }
    }

    /* Estilos customizados */
    .checklist-simple {
        font-size: 0.85rem;
        line-height: 1.4;
    }
    
    .checklist-simple strong {
        font-weight: 600;
    }
    
    .checklist-simple small {
        color: #666;
        line-height: 1.3;
    }
    
    code {
        background: #f8f9fa;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
        font-size: 0.875em;
    }
</style>
@endpush
@endsection