@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header com informações da parada -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('paradas.show', $parada) }}">{{ $parada->nome }}</a></li>
                    <li class="breadcrumb-item active">Pendências</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.open('{{ route('paradas.pendencias-print', $parada) }}', '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes'); return false;" class="btn btn-danger">
                <i class="fas fa-print me-1"></i>Versão para Impressão
            </button>
            <button onclick="window.print()" class="btn btn-outline-danger">
                <i class="fas fa-print me-1"></i>Imprimir Página
            </button>
            <a href="{{ route('paradas.relatorio', $parada) }}" class="btn btn-outline-primary">
                <i class="fas fa-chart-bar me-1"></i>Relatório Completo
            </a>
            <a href="{{ route('paradas.historico') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Voltar ao Histórico
            </a>
        </div>
    </div>

    <!-- Card com informações básicas -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Relatório de Pendências - Parada {{ $parada->nome }}
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Tipo:</strong> {{ ucfirst(str_replace('_', ' ', $parada->tipo)) }}
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
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-industry me-2"></i>
                        {{ $nomeArea }} 
                        <span class="badge bg-danger ms-2">{{ $testesArea->count() }} equipamento(s) pendente(s)</span>
                    </h5>
                </div>
                <div class="card-body p-0">
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
                
                <!-- Seção de Imagens dos Problemas -->
                @php
                    $imagensEncontradas = false;
                    $imagensPorEquipamento = [];
                    
                    foreach($testesArea as $teste) {
                        $imagensEquipamento = [];
                        foreach($checklistItems as $item) {
                            $fotoProblema = $teste->{$item . '_foto_problema'};
                            $fotoResolucao = $teste->{$item . '_foto_resolucao'};
                            $status = $teste->{$item . '_status'};
                            
                            if($fotoProblema || $fotoResolucao) {
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
                                    'foto_resolucao' => $fotoResolucao,
                                    'status' => $status
                                ];
                                $imagensEncontradas = true;
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

                @if($imagensEncontradas)
                    <div class="card-footer bg-light">
                        <h6 class="mb-3">
                            <i class="fas fa-camera me-2"></i>Imagens dos Problemas - {{ $nomeArea }}
                        </h6>
                        
                        @foreach($imagensPorEquipamento as $nomeEquipamento => $dadosEquipamento)
                            <div class="mb-4">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-cog me-1"></i>{{ $nomeEquipamento }} 
                                    <small class="text-muted">({{ $dadosEquipamento['equipamento']->tag }})</small>
                                </h6>
                                
                                <div class="row g-3">
                                    @foreach($dadosEquipamento['imagens'] as $item => $dados)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-{{ $dados['status'] === 'problema' ? 'danger' : 'success' }} text-white py-2">
                                                    <small class="fw-bold">{{ $dados['nome'] }}</small>
                                                </div>
                                                <div class="card-body p-2">
                                                    @if($dados['foto_problema'])
                                                        <div class="mb-2">
                                                            <small class="text-danger fw-bold d-block mb-1">
                                                                <i class="fas fa-exclamation-triangle me-1"></i>Problema:
                                                            </small>
                                                            <img src="{{ Storage::url($dados['foto_problema']) }}" 
                                                                 class="img-fluid rounded cursor-pointer" 
                                                                 style="max-height: 120px; width: 100%; object-fit: cover;"
                                                                 onclick="mostrarFoto('{{ Storage::url($dados['foto_problema']) }}', 'Foto do Problema - {{ $dados['nome'] }}')"
                                                                 alt="Foto do Problema">
                                                        </div>
                                                    @endif
                                                    
                                                    @if($dados['foto_resolucao'])
                                                        <div>
                                                            <small class="text-success fw-bold d-block mb-1">
                                                                <i class="fas fa-check-circle me-1"></i>Resolução:
                                                            </small>
                                                            <img src="{{ Storage::url($dados['foto_resolucao']) }}" 
                                                                 class="img-fluid rounded cursor-pointer" 
                                                                 style="max-height: 120px; width: 100%; object-fit: cover;"
                                                                 onclick="mostrarFoto('{{ Storage::url($dados['foto_resolucao']) }}', 'Foto da Resolução - {{ $dados['nome'] }}')"
                                                                 alt="Foto da Resolução">
                                                        </div>
                                                    @endif
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

        <!-- Resumo das pendências -->
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Resumo das Pendências
                </h5>
            </div>
            <div class="card-body">
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
                
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-danger text-white">
                            <h3 class="mb-1">{{ $equipamentosComProblema }}</h3>
                            <small>Com Problemas</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-warning text-dark">
                            <h3 class="mb-1">{{ $equipamentosSemTeste }}</h3>
                            <small>Sem Teste/Pendente</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-primary text-white">
                            <h3 class="mb-1">{{ $totalEquipamentosPendentes }}</h3>
                            <small>Total de Pendências</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    @media print {
        .btn, .breadcrumb, nav {
            display: none !important;
        }
        
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
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    
    .cursor-pointer:hover {
        opacity: 0.8;
        transform: scale(1.02);
        transition: all 0.2s ease;
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