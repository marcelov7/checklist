@extends('layouts.app')

@section('title', $parada->nome)

@section('content')
<!-- Espaçamento adicional para mobile evitar sobreposição com navbar -->
<div class="d-sm-none" style="padding-top: 90px;"></div>

<div class="d-flex justify-content-between align-items-start mb-4">
    <div class="flex-grow-1 me-3">
        <div class="d-flex flex-wrap align-items-center mb-2">
            <span class="badge bg-dark me-2 mb-1 fs-6">{{ $parada->macro }}</span>
            <span class="badge bg-info me-2 mb-1">{{ $parada->tipo_label }}</span>
            <span class="badge bg-{{ $parada->status == 'em_andamento' ? 'primary' : ($parada->status == 'concluida' ? 'success' : 'secondary') }} me-2 mb-1">
                {{ ucfirst(str_replace('_', ' ', $parada->status)) }}
            </span>
        </div>
        <h1 class="h3 h1-md"><i class="fas fa-clipboard-check"></i> {{ $parada->nome }}</h1>
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">
                    <i class="fas fa-calendar"></i> Início: {{ $parada->data_inicio->format('d/m/Y H:i') }}
                </small>
                @if($parada->data_fim)
                    <br>
                    <small class="text-success">
                        <i class="fas fa-calendar-check"></i> Fim: {{ $parada->data_fim->format('d/m/Y H:i') }}
                    </small>
                @endif
                @if($parada->duracao_prevista_horas)
                    <br>
                    <small class="text-info">
                        <i class="fas fa-clock"></i> Duração prevista: {{ number_format($parada->duracao_prevista_horas, 2, ',', '.') }}h
                    </small>
                @endif
                @if($parada->status == 'em_andamento')
                    <br>
                    <small class="text-primary">
                        <i class="fas fa-play"></i> {{ $parada->duracao_atual }}h em andamento
                    </small>
                @elseif($parada->duracao_real)
                    <br>
                    <small class="text-warning">
                        <i class="fas fa-stopwatch"></i> Duração real: {{ $parada->duracao_real }}h
                    </small>
                @endif
            </div>
            <div class="col-md-6">
                @if($parada->equipe_responsavel)
                    <small class="text-muted">
                        <i class="fas fa-users"></i> <strong>Equipe:</strong><br>
                        {{ $parada->equipe_responsavel }}
                    </small>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Botões no canto superior direito -->
    <div class="d-flex gap-2 flex-shrink-0 flex-column flex-sm-row">
        @if($parada->status == 'em_andamento')
            <button type="button" class="btn btn-success d-none d-sm-block" onclick="finalizarParada()">
                <i class="fas fa-check me-2"></i><span class="d-none d-md-inline">Finalizar Parada</span>
            </button>
        @endif
        <a href="{{ route('paradas.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i><span class="d-none d-lg-inline">Voltar</span>
        </a>
    </div>
</div>

@if($parada->descricao)
    <div class="alert alert-info">
        <strong>Descrição:</strong> {{ $parada->descricao }}
    </div>
@endif

@if(isset($semEquipamentos) && $semEquipamentos)
<!-- Mensagem quando não há equipamentos -->
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">Nenhum equipamento selecionado</h5>
                    <p class="mb-0">Esta parada ainda não possui equipamentos associados. Clique no botão abaixo para selecionar os equipamentos que farão parte desta parada.</p>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('paradas.select-equipment', $parada) }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> Selecionar Equipamentos
                </a>
            </div>
        </div>
    </div>
</div>
@else

<!-- Progress Summary -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card progress-card text-center">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="mb-0">{{ $percentualGeral }}%</h4>
                        <small class="text-muted">Progresso Geral</small>
                    </div>
                    <div class="col-4">
                        <svg class="progress-ring" viewBox="0 0 36 36">
                            <path class="bg-light" stroke="currentColor" stroke-width="3" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-primary" stroke="currentColor" stroke-width="3" fill="none"
                                  stroke-dasharray="{{ $percentualGeral }}, 100"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Progresso por Área</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($percentualPorArea as $area)
                        <div class="col-md-4 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-truncate">{{ $area->nome }}</span>
                                <span class="badge bg-{{ $area->percentual == 100 ? 'success' : ($area->percentual > 0 ? 'primary' : 'secondary') }}">
                                    {{ $area->percentual }}%
                                </span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $area->percentual == 100 ? 'success' : 'primary' }}" 
                                     style="width: {{ $area->percentual }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Areas and Equipment Checklist -->
<div class="row">
    @foreach($areas as $area)
        <div class="col-md-6 mb-4">
            <div class="card area-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-map-marked-alt"></i> {{ $area->nome }}</h5>
                        @php
                            $areaProgress = $percentualPorArea->firstWhere('id', $area->id);
                            $areaPercentual = $areaProgress ? $areaProgress->percentual : 0;
                        @endphp
                        <span class="badge bg-{{ $areaPercentual == 100 ? 'success' : ($areaPercentual > 0 ? 'primary' : 'secondary') }}">
                            {{ $areaPercentual }}%
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($area->equipamentos as $equipamento)
                        @php
                            $teste = $equipamento->testes->first();
                        @endphp
                        <div class="equipamento-row p-3 border-bottom" data-equipamento-id="{{ $equipamento->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $equipamento->nome }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-tag"></i> {{ $equipamento->tag }}
                                    </small>
                                    @if($equipamento->descricao)
                                        <br><small class="text-muted">{{ $equipamento->descricao }}</small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $teste->status_color ?? 'secondary' }} status-badge">
                                        {{ $teste->status_label ?? 'Pendente' }}
                                    </span>
                                    @if($teste)
                                    <span class="badge bg-info ms-1" title="Progresso do Checklist">
                                        <i class="fas fa-clipboard-check"></i> {{ $teste->checklist_progress }}%
                                    </span>
                                    @endif
                                    <br>
                                    <div class="btn-group mt-1" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary" 
                                                onclick="abrirModalTeste({{ $teste->id ?? 0 }}, {{ $equipamento->id }}, '{{ $equipamento->nome }}', '{{ $equipamento->tag }}')"
                                                {{ $parada->status != 'em_andamento' ? 'disabled' : '' }}>
                                            <i class="fas fa-edit"></i> Testar
                                        </button>
                                        @if($teste && $teste->status == 'pendente')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success" 
                                                onclick="atualizacaoRapida({{ $teste->id }}, {{ $equipamento->id }}, 'ok')"
                                                {{ $parada->status != 'em_andamento' ? 'disabled' : '' }}
                                                title="Marcar como OK">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @elseif($teste && $teste->status == 'problema')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-warning" 
                                                onclick="abrirModalResolucao({{ $teste->id }}, {{ $equipamento->id }}, '{{ $equipamento->nome }}', '{{ $equipamento->tag }}')"
                                                {{ $parada->status != 'em_andamento' ? 'disabled' : '' }}
                                                title="Resolver Problema">
                                            <i class="fas fa-wrench"></i> Resolver
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Checklist de Verificação Avançado -->
                            @if($teste)
                            <div class="mt-3 p-3 bg-light rounded checklist-section">
                                <div class="d-flex justify-content-between align-items-center mb-3" 
                                     data-bs-toggle="collapse" 
                                     data-bs-target="#checklist-{{ $equipamento->id }}-{{ $teste->id }}" 
                                     aria-expanded="false" 
                                     aria-controls="checklist-{{ $equipamento->id }}-{{ $teste->id }}"
                                     style="cursor: pointer;">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clipboard-check me-2"></i>
                                        Checklist de Verificação
                                        <i class="fas fa-chevron-down ms-2 collapse-icon transition-all"></i>
                                    </h6>
                                    <small class="text-muted">Clique para expandir/recolher</small>
                                </div>
                                
                                <div class="collapse" id="checklist-{{ $equipamento->id }}-{{ $teste->id }}">
                                
                                @foreach($teste->checklist_items as $key => $item)
                                <div class="checklist-item-container mb-3" id="container_{{ $key }}_{{ $equipamento->id }}">
                                    <div class="d-flex align-items-center justify-content-between p-2 border rounded 
                                                @if($item['status'] == 'ok') bg-success text-white
                                                @elseif($item['status'] == 'nao_aplica') bg-secondary text-white text-decoration-line-through
                                                @elseif($item['status'] == 'nao_ok') bg-danger text-white
                                                @else bg-white @endif">
                                        <div class="flex-grow-1">
                                            <i class="{{ $item['icon'] }} me-2"></i>
                                            <span class="@if(in_array($item['status'], ['ok', 'nao_aplica'])) text-decoration-line-through @endif">
                                                {{ $item['label'] }}
                                            </span>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <button type="button" 
                                                    class="btn btn-sm {{ $item['status'] == 'ok' ? 'btn-light' : 'btn-outline-success' }} checklist-btn"
                                                    data-item="{{ $key }}" 
                                                    data-teste-id="{{ $teste->id }}" 
                                                    data-equipamento-id="{{ $equipamento->id }}"
                                                    data-action="ok"
                                                    {{ $parada->status != 'em_andamento' ? 'disabled' : '' }}>
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm {{ $item['status'] == 'nao_ok' ? 'btn-light' : 'btn-outline-danger' }} checklist-btn"
                                                    data-item="{{ $key }}" 
                                                    data-teste-id="{{ $teste->id }}" 
                                                    data-equipamento-id="{{ $equipamento->id }}"
                                                    data-action="nao_ok"
                                                    {{ $parada->status != 'em_andamento' ? 'disabled' : '' }}>
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm {{ $item['status'] == 'nao_aplica' ? 'btn-light' : 'btn-outline-secondary' }} checklist-btn"
                                                    data-item="{{ $key }}" 
                                                    data-teste-id="{{ $teste->id }}" 
                                                    data-equipamento-id="{{ $equipamento->id }}"
                                                    data-action="nao_aplica"
                                                    {{ $parada->status != 'em_andamento' ? 'disabled' : '' }}>
                                                N/A
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Área de problema/resolução -->
                                    <div class="problema-area mt-2" 
                                         id="problema-area-{{ $key }}-{{ $teste->id }}"
                                         style="display: {{ $item['status'] == 'nao_ok' ? 'block' : 'none' }};">
                                        
                                        @php
                                            $problemaResolvido = !empty($item['resolucao']);
                                        @endphp
                                        
                                        @if($problemaResolvido)
                                        <!-- Visualização do problema resolvido -->
                                        <div class="p-3 bg-light border border-success rounded">
                                            <h6 class="text-success mb-2">
                                                <i class="fas fa-check-circle me-1"></i>Problema Resolvido
                                            </h6>
                                            
                                            @if(!empty($item['problema']))
                                            <div class="mb-2">
                                                <strong class="text-muted">Problema identificado:</strong>
                                                <p class="mb-1 text-muted">{{ $item['problema'] }}</p>
                                            </div>
                                            @endif
                                            
                                            <div class="mb-2">
                                                <strong class="text-success">Resolução:</strong>
                                                <p class="mb-1">{{ $item['resolucao'] }}</p>
                                            </div>
                                            
                                            @if(!empty($item['foto_resolucao']))
                                            <div class="mt-2">
                                                <small class="text-success">
                                                    <i class="fas fa-camera me-1"></i>Foto da resolução anexada
                                                </small>
                                                <br>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success mt-1"
                                                        onclick="abrirModalFoto('{{ Storage::url($item['foto_resolucao']) }}', 'Foto da Resolução - {{ $key }}', '{{ addslashes($item['resolucao'] ?? '') }}')">
                                                    <i class="fas fa-eye me-1"></i>Ver Foto
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                        @else
                                        <!-- Interface para registrar/resolver problema -->
                                        <div class="p-3 bg-white border border-danger rounded">
                                            <h6 class="text-danger mb-3">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Problema Identificado
                                            </h6>
                                            
                                            <!-- Descrição do problema -->
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Descrição do Problema:</label>
                                                <textarea class="form-control problema-text" 
                                                          rows="3" 
                                                          placeholder="Descreva detalhadamente o problema encontrado..."
                                                          data-item="{{ $key }}" 
                                                          data-teste-id="{{ $teste->id }}"
                                                          id="desc_{{ $key }}_{{ $teste->id }}">{{ $item['problema'] }}</textarea>
                                            </div>
                                            
                                            <!-- Campo de foto do problema -->
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">
                                                    <i class="fas fa-camera me-1"></i>Foto do Problema (Opcional):
                                                </label>
                                                <input type="file" 
                                                       class="form-control problema-foto" 
                                                       accept="image/*"
                                                       data-item="{{ $key }}" 
                                                       data-teste-id="{{ $teste->id }}"
                                                       id="foto_problema_{{ $key }}_{{ $teste->id }}">
                                                <div class="form-text">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Máximo: 1 arquivo, 2MB. Formatos: JPG, PNG, GIF
                                                </div>
                                            </div>
                                            
                                            <!-- Botão para salvar problema -->
                                            <div class="mb-3">
                                                <button type="button" 
                                                        class="btn btn-warning btn-sm salvar-problema-btn"
                                                        data-item="{{ $key }}" 
                                                        data-teste-id="{{ $teste->id }}"
                                                        {{ $parada->status != 'em_andamento' ? 'disabled' : '' }}>
                                                    <i class="fas fa-save me-1"></i>Salvar Problema
                                                </button>
                                            </div>
                                            
                                            <hr class="my-3">
                                            
                                            <!-- Área de resolução -->
                                            <h6 class="text-success mb-3">
                                                <i class="fas fa-tools me-1"></i>Resolução do Problema
                                            </h6>
                                            
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Descrição da Resolução:</label>
                                                <textarea class="form-control resolucao-text" 
                                                          rows="3" 
                                                          placeholder="Descreva como o problema foi resolvido..."
                                                          data-item="{{ $key }}" 
                                                          data-teste-id="{{ $teste->id }}"
                                                          id="resolucao_{{ $key }}_{{ $teste->id }}"></textarea>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">
                                                    <i class="fas fa-camera me-1"></i>Foto da Resolução (Opcional):
                                                </label>
                                                <input type="file" 
                                                       class="form-control resolucao-foto" 
                                                       accept="image/*"
                                                       data-item="{{ $key }}" 
                                                       data-teste-id="{{ $teste->id }}"
                                                       id="foto_resolucao_{{ $key }}_{{ $teste->id }}">
                                                <div class="form-text">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Máximo: 1 arquivo, 2MB. Formatos: JPG, PNG, GIF
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <button type="button" 
                                                        class="btn btn-success btn-sm resolver-problema-btn flex-fill"
                                                        data-item="{{ $key }}" 
                                                        data-teste-id="{{ $teste->id }}" 
                                                        data-equipamento-id="{{ $equipamento->id }}"
                                                        {{ $parada->status != 'em_andamento' ? 'disabled' : '' }}>
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Problema Resolvido
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                
                                <!-- Progress bar do checklist -->
                                <div class="mt-3">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar 
                                                    @if($teste->checklist_progress == 100) bg-success
                                                    @elseif($teste->checklist_progress >= 75) bg-info
                                                    @elseif($teste->checklist_progress >= 50) bg-warning
                                                    @else bg-danger @endif" 
                                             role="progressbar" 
                                             style="width: {{ $teste->checklist_progress }}%" 
                                             id="progress_{{ $equipamento->id }}">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">Checklist: {{ $teste->checklist_progress }}% completo</small>
                                        <small class="text-muted" id="progress_text_{{ $equipamento->id }}">
                                            @if($teste->checklist_progress == 100)
                                                <i class="fas fa-check-circle text-success"></i> Completo
                                            @else
                                                {{ 6 - floor(($teste->checklist_progress / 100) * 6) }} itens restantes
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                </div> <!-- Fim do collapse do checklist -->
                                
                                <!-- Seção de Histórico de Problemas e Resoluções -->
                                @php
                                    $problemasEncontrados = collect($teste->checklist_items)->filter(function($item) {
                                        return !empty($item['problema']) || !empty($item['resolucao']);
                                    });
                                @endphp
                                
                                @if($problemasEncontrados->count() > 0)
                                <div class="mt-4 p-3 bg-light rounded historico-problemas" id="historico_problemas_{{ $teste->id }}">
                                    <h6 class="mb-3 text-primary">
                                        <i class="fas fa-history me-2"></i>Histórico de Problemas e Resoluções
                                    </h6>
                                    
                                    @foreach($problemasEncontrados as $key => $item)
                                    <div class="problema-historico mb-3 p-3 border rounded" id="historico_{{ $key }}_{{ $teste->id }}">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="{{ $item['icon'] }} me-2 text-primary"></i>
                                            <strong>{{ $item['label'] }}</strong>
                                            @if(!empty($item['resolucao']))
                                                <span class="badge bg-success ms-2">
                                                    <i class="fas fa-check-circle me-1"></i>Resolvido
                                                </span>
                                            @else
                                                <span class="badge bg-warning ms-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Pendente
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if(!empty($item['problema']))
                                        <div class="problema-info mb-2">
                                            <strong class="text-danger">Problema Identificado:</strong>
                                            <p class="mb-1">{{ $item['problema'] }}</p>
                                            @if(!empty($item['foto_problema_path']))
                                            <div class="mt-2">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger btn-photo"
                                                        onclick="abrirModalFoto('{{ Storage::url($item['foto_problema_path']) }}', 'Foto do Problema - {{ $key }}', '{{ addslashes($item['problema'] ?? '') }}')">
                                                    <i class="fas fa-camera me-1"></i>Ver Foto do Problema
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                        
                                        @if(!empty($item['resolucao']))
                                        <div class="resolucao-info mb-2">
                                            <strong class="text-success">Resolução:</strong>
                                            <p class="mb-1">{{ $item['resolucao'] }}</p>
                                            @if(!empty($item['foto_resolucao_path']))
                                            <div class="mt-2">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success btn-photo"
                                                        onclick="abrirModalFoto('{{ Storage::url($item['foto_resolucao_path']) }}', 'Foto da Resolução - {{ $key }}', '{{ addslashes($item['resolucao'] ?? '') }}')">
                                                    <i class="fas fa-camera me-1"></i>Ver Foto da Resolução
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endif
                            
                            @if($teste && ($teste->observacoes || $teste->problema_descricao))
                                <div class="mt-2 p-2 bg-light rounded">
                                    @if($teste->observacoes)
                                        <small><strong>Obs:</strong> {{ $teste->observacoes }}</small><br>
                                    @endif
                                    @if($teste->problema_descricao)
                                        <small class="text-danger"><strong>Problema:</strong> {{ $teste->problema_descricao }}</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted">
                            <i class="fas fa-exclamation-triangle"></i> Nenhum equipamento ativo nesta área
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal de Teste -->
<div class="modal fade" id="testeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Teste do Equipamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="testeForm">
                <div class="modal-body">
                    <div id="equipamentoInfo" class="mb-3 p-3 bg-light rounded">
                        <!-- Informações do equipamento serão carregadas via JS -->
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status do Teste *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pendente">Pendente</option>
                            <option value="ok">OK - Teste aprovado</option>
                            <option value="problema">Problema encontrado</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3" 
                                  placeholder="Observações gerais sobre o teste..."></textarea>
                    </div>
                    
                    <div class="mb-3" id="problemaDiv" style="display: none;">
                        <label for="problema_descricao" class="form-label">Descrição do Problema *</label>
                        <textarea class="form-control" id="problema_descricao" name="problema_descricao" rows="3" 
                                  placeholder="Descreva detalhadamente o problema encontrado..."></textarea>
                    </div>
                    
                    <div class="mb-3" id="fotoDiv" style="display: none;">
                        <label for="foto" class="form-label">Foto do Problema</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        <div class="form-text">Anexe uma foto que documenta o problema encontrado (opcional)</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="testado_por" class="form-label">Testado por</label>
                        <input type="text" class="form-control" id="testado_por" name="testado_por" 
                               placeholder="Nome do responsável pelo teste">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Salvar Teste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Resolução de Problemas -->
<div class="modal fade" id="resolucaoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-wrench"></i> Resolver Problema do Equipamento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="resolucaoForm">
                <div class="modal-body">
                    <div id="equipamentoResolucaoInfo" class="mb-3 p-3 bg-light rounded">
                        <!-- Informações do equipamento serão carregadas via JS -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Problema Atual:</strong>
                        <p id="problemaAtual" class="mb-0 mt-2"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resolucao_descricao" class="form-label">Descrição da Resolução *</label>
                        <textarea class="form-control" id="resolucao_descricao" name="resolucao_descricao" rows="4" 
                                  placeholder="Descreva como o problema foi resolvido (peças trocadas, ajustes realizados, etc.)" required></textarea>
                        <small class="form-text text-muted">Esta informação será registrada no histórico do equipamento</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resolvido_por" class="form-label">Resolvido por *</label>
                        <input type="text" class="form-control" id="resolvido_por" name="resolvido_por" 
                               placeholder="Nome do técnico responsável pela resolução" required>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmar_resolucao" required>
                        <label class="form-check-label" for="confirmar_resolucao">
                            Confirmo que o problema foi resolvido e o equipamento está funcionando corretamente
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check-circle"></i> Marcar como Resolvido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmação para Finalizar -->
<div class="modal fade" id="finalizarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0" style="padding-bottom: 0;">
                <h5 class="modal-title fw-bold text-success">
                    <i class="fas fa-check-circle me-2"></i>Finalizar Parada
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding-top: 1.5rem;">
                <div class="text-center mb-3">
                    <i class="fas fa-question-circle text-warning" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center fs-5 mb-3" style="line-height: 1.4;">Tem certeza que deseja finalizar esta parada?</p>
                <p class="text-muted text-center small">Após finalizada, não será mais possível realizar alterações nos testes.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('paradas.finalizar', $parada) }}" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check"></i> Finalizar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let testeAtual = null;

// Função para carregar histórico inicial
function carregarHistoricoInicial() {
    console.log('=== CARREGANDO HISTÓRICO INICIAL ===');
    console.log('Dados do teste (checklist):', @json($teste->checklistItems));
    
    const itensResolvidos = [
        @foreach($teste->checklistItems as $item => $dados)
            @if(($dados['status'] === 'ok' && !empty($dados['resolucao'])) || !empty($dados['problema']))
                {
                    item: '{{ $item }}',
                    problema: {!! json_encode($dados['problema'] ?? '') !!},
                    resolucao: {!! json_encode($dados['resolucao'] ?? '') !!},
                    foto_problema_path: {!! json_encode($dados['foto_problema_path'] ?? '') !!},
                    foto_resolucao_path: {!! json_encode($dados['foto_resolucao_path'] ?? '') !!}
                },
            @endif
        @endforeach
    ];
    
    console.log('Itens encontrados para histórico:', itensResolvidos);
    
    // Adicionar cada item resolvido ao histórico com delay
    itensResolvidos.forEach(function(itemData, index) {
        setTimeout(function() {
            console.log('Adicionando item ao histórico:', itemData);
            console.log('=== CHAMADA 3: Carregamento inicial ===');
            atualizarHistoricoProblemas({{ $teste->id }}, itemData.item, itemData);
            
            // Atualizar contador após o último item
            if (index === itensResolvidos.length - 1) {
                setTimeout(function() {
                    atualizarContadorHistorico({{ $teste->id }});
                    // Garantir que o histórico fique sempre expandido após carregamento inicial
                    const $historicoFinal = $('#historico_content_' + {{ $teste->id }});
                    $historicoFinal.show();
                    console.log('Histórico forçado a ficar expandido após carregamento inicial');
                }, 100);
            }
        }, index * 10);
    });
}

// Função para renovar CSRF token


function abrirModalTeste(testeId, equipamentoId, equipamentoNome, equipamentoTag) {
    testeAtual = {
        id: testeId,
        equipamento_id: equipamentoId
    };
    
    // Atualizar informações do equipamento
    document.getElementById('equipamentoInfo').innerHTML = `
        <h6 class="mb-1">${equipamentoNome}</h6>
        <small class="text-muted"><i class="fas fa-tag"></i> ${equipamentoTag}</small>
    `;
    
    // Limpar formulário
    document.getElementById('testeForm').reset();
    document.getElementById('problemaDiv').style.display = 'none';
    
    // Se o teste existe, carregar dados
    if (testeId > 0) {
        carregarDadosTeste(testeId);
    }
    
    // Mostrar modal
    new bootstrap.Modal(document.getElementById('testeModal')).show();
}

function carregarDadosTeste(testeId) {
    fetch(`/testes/${testeId}`)
        .then(response => response.json())
        .then(data => {
            const teste = data.teste;
            document.getElementById('status').value = teste.status;
            document.getElementById('observacoes').value = teste.observacoes || '';
            document.getElementById('problema_descricao').value = teste.problema_descricao || '';
            document.getElementById('testado_por').value = teste.testado_por || '';
            
            if (teste.status === 'problema') {
                document.getElementById('problemaDiv').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar teste:', error);
        });
}

// Mostrar/ocultar campo de problema e foto
document.getElementById('status').addEventListener('change', function() {
    const problemaDiv = document.getElementById('problemaDiv');
    const fotoDiv = document.getElementById('fotoDiv');
    
    if (this.value === 'problema') {
        problemaDiv.style.display = 'block';
        fotoDiv.style.display = 'block';
        document.getElementById('problema_descricao').required = true;
    } else {
        problemaDiv.style.display = 'none';
        fotoDiv.style.display = 'none';
        document.getElementById('problema_descricao').required = false;
    }
});

// Submeter formulário de teste
document.getElementById('testeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Adicionar dados necessários
    formData.append('equipamento_id', testeAtual.equipamento_id);
    formData.append('parada_id', {{ $parada->id }});
    
    const url = testeAtual.id > 0 ? `/testes/${testeAtual.id}` : '/testes';
    const method = testeAtual.id > 0 ? 'PATCH' : 'POST';
    
    // Se for PATCH, adicionar método override
    if (method === 'PATCH') {
        formData.append('_method', 'PATCH');
    }

    // Garantir token CSRF válido
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('CSRF Token:', csrfToken);
    console.log('URL:', url);
    console.log('Method:', method);
    
    if (!csrfToken) {
        alert('Erro de segurança. Recarregue a página e tente novamente.');
        location.reload();
        return;
    }
    
    // Log dos dados sendo enviados
    const formDataEntries = {};
    for (let [key, value] of formData.entries()) {
        formDataEntries[key] = value;
    }
    console.log('Form Data:', formDataEntries);
    
    // Usar jQuery que já tem CSRF configurado no layout
    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.success) {
                // Fechar modal
                bootstrap.Modal.getInstance(document.getElementById('testeModal')).hide();
                
                // Mostrar mensagem de sucesso
                mostrarNotificacao('Teste salvo com sucesso!', 'success');
                
                // Recarregar página para garantir que tudo está atualizado
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert('Erro ao salvar teste: ' + (data.message || 'Erro desconhecido'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro AJAX:', xhr.responseText);
            if (xhr.status === 419) {
                alert('Sessão expirada. A página será recarregada.');
                location.reload();
            } else {
                alert('Erro ao salvar teste. Verifique os dados e tente novamente.');
            }
        }
    });
});

function finalizarParada() {
    new bootstrap.Modal(document.getElementById('finalizarModal')).show();
}

// Função simplificada para atualização rápida
function atualizacaoRapida(testeId, equipamentoId, novoStatus) {
    if (!confirm('Tem certeza que deseja marcar este equipamento como OK?')) {
        return;
    }
    
    // Usar jQuery que já tem CSRF configurado
    $.ajax({
        url: `/testes/${testeId}`,
        method: 'PATCH',
        data: {
            status: novoStatus,
            observacoes: 'Marcado como OK via atualização rápida',
            testado_por: 'Sistema'
        },
        success: function(data) {
            if (data.success) {
                mostrarNotificacao('Teste atualizado com sucesso!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert('Erro ao atualizar teste: ' + (data.message || 'Erro desconhecido'));
            }
        },
        error: function(xhr) {
            console.error('Erro AJAX:', xhr.responseText);
            alert('Erro ao atualizar teste. Tente novamente.');
        }
    });
}

// Função principal para atualizar status do checklist
function atualizarStatusChecklist(testeId, equipamentoId, item, action, $btn) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{{ route("testes.atualizarChecklistStatus", ":id") }}'.replace(':id', testeId),
        method: 'PATCH',
        data: {
            item: item,
            status: action
        },
        success: function(response) {
            console.log('Resposta do servidor recebida:', response);
            if (response.success) {
                mostrarNotificacao('Status atualizado com sucesso!', 'success');
                
                console.log('Chamando atualizarInterfaceItem com:', { testeId, equipamentoId, item, action, progress: response.progress });
                // Atualizar interface instantaneamente
                atualizarInterfaceItem(testeId, equipamentoId, item, action, response.progress);
            } else {
                mostrarNotificacao('Erro: ' + (response.message || 'Erro desconhecido'), 'error');
            }
        },
        error: function(xhr) {
            console.error('Erro AJAX:', xhr.responseText);
            mostrarNotificacao('Erro ao atualizar status. Tente novamente.', 'error');
        },
        complete: function() {
            // Reabilitar botão
            $btn.prop('disabled', false);
        }
    });
}

// Função para atualizar a interface visual do item
function atualizarInterfaceItem(testeId, equipamentoId, item, action, progress) {
    console.log('=== ATUALIZANDO INTERFACE ===');
    console.log('Dados:', { testeId, equipamentoId, item, action });
    
    // Buscar todos os botões do item de forma simples
    const $buttons = $('button[data-item="' + item + '"][data-teste-id="' + testeId + '"]');
    
    console.log('Botões encontrados:', $buttons.length);
    
    if ($buttons.length === 0) {
        console.error('❌ Nenhum botão encontrado!');
        return;
    }
    
    // Resetar todos os botões
    $buttons.each(function() {
        const $btn = $(this);
        const btnAction = $btn.data('action');
        
        // Remover classes ativas
        $btn.removeClass('btn-light btn-success btn-danger btn-secondary active text-white');
        
        // Restaurar classe original
        if (btnAction === 'ok') {
            $btn.addClass('btn-outline-success');
        } else if (btnAction === 'nao_ok') {
            $btn.addClass('btn-outline-danger');
        } else if (btnAction === 'nao_aplica') {
            $btn.addClass('btn-outline-secondary');
        }
    });
    
    // Ativar botão correto
    const $activeBtn = $buttons.filter('[data-action="' + action + '"]');
    
    if (action === 'ok') {
        $activeBtn.removeClass('btn-outline-success').addClass('btn-success active');
    } else if (action === 'nao_ok') {
        $activeBtn.removeClass('btn-outline-danger').addClass('btn-danger active');
    } else if (action === 'nao_aplica') {
        $activeBtn.removeClass('btn-outline-secondary').addClass('btn-secondary active');
    }
    
    console.log('Botão ativado:', $activeBtn.length);
    
    // Atualizar container do item
    const $itemRow = $activeBtn.closest('.d-flex');
    const $itemText = $itemRow.find('span').first();
    
    // Resetar estilos
    $itemRow.removeClass('bg-success bg-danger bg-secondary text-white');
    $itemText.removeClass('text-decoration-line-through');
    
    // Aplicar novo estilo
    if (action === 'ok') {
        $itemRow.addClass('bg-success text-white');
        $itemText.addClass('text-decoration-line-through');
    } else if (action === 'nao_ok') {
        $itemRow.addClass('bg-danger text-white');
    } else if (action === 'nao_aplica') {
        $itemRow.addClass('bg-secondary text-white');
        $itemText.addClass('text-decoration-line-through');
    }
    
    // Área de problema
    const $problemaArea = $('#problema-area-' + item + '-' + testeId);
    if (action === 'nao_ok') {
        $problemaArea.slideDown(300);
    } else {
        $problemaArea.slideUp(300);
    }
    
    // Atualizar progresso se fornecido
    if (progress !== undefined) {
        atualizarBarraProgresso(testeId, progress);
        console.log('Progresso atualizado:', progress + '%');
    }
    
    console.log('=== INTERFACE ATUALIZADA ===');
}

// Função para atualizar barra de progresso
function atualizarBarraProgresso(equipamentoId, progress) {
    const $progressBar = $('#progress_' + equipamentoId);
    const $progressText = $('#progress_text_' + equipamentoId);
    
    if ($progressBar.length) {
        // Animar mudança da barra
        $progressBar.css('width', progress + '%');
        
        // Atualizar texto
        $progressText.text(progress + '% completo');
        
        // Atualizar cor da barra
        $progressBar.removeClass('bg-danger bg-warning bg-success');
        if (progress < 50) {
            $progressBar.addClass('bg-danger');
        } else if (progress < 100) {
            $progressBar.addClass('bg-warning');
        } else {
            $progressBar.addClass('bg-success');
            mostrarNotificacao('🎉 Checklist 100% completo!', 'success');
        }
    }
}

// Função para atualizar histórico de problemas
function atualizarHistoricoProblemas(testeId, item, dados) {
    console.log('=== atualizarHistoricoProblemas ===');
    console.log('testeId:', testeId);
    console.log('item:', item);
    console.log('dados recebidos:', dados);
    
    // Prevenção contra chamadas duplicadas muito próximas
    const chaveUnica = `${testeId}_${item}`;
    if (window.ultimasAtualizacoes && window.ultimasAtualizacoes[chaveUnica]) {
        const tempoDecorrido = Date.now() - window.ultimasAtualizacoes[chaveUnica];
        if (tempoDecorrido < 500) {
            console.log('Prevenindo atualização duplicada muito rápida para:', chaveUnica, 'tempo:', tempoDecorrido);
            return;
        }
    }
    
    if (!window.ultimasAtualizacoes) {
        window.ultimasAtualizacoes = {};
    }
    window.ultimasAtualizacoes[chaveUnica] = Date.now();
    let $historicoContainer = $('#historico_problemas_' + testeId);
    
    // Se não existe o container de histórico, criar
    if ($historicoContainer.length === 0) {
        const checklistContainer = $('.checklist-section').parent();
        checklistContainer.append(`
            <div class="mt-4 p-3 bg-light rounded historico-problemas" id="historico_problemas_${testeId}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-history me-2"></i>Histórico de Problemas e Resoluções
                    </h6>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary me-2" id="contador_historico_${testeId}">0 itens</span>
                    </div>
                </div>
                <div class="historico-content" id="historico_content_${testeId}" style="display: block;">
                    <!-- Itens do histórico serão adicionados aqui -->
                </div>
            </div>
        `);
        $historicoContainer = $('#historico_problemas_' + testeId);
    }
    
    // Buscar ou criar item específico no histórico
    let $itemHistorico = $('#historico_' + item + '_' + testeId);
    
    // Obter informações do item (ícone e label)
    const $itemContainer = $('#container_' + item + '_' + testeId);
    const itemIcon = $itemContainer.find('i').first().attr('class') || 'fas fa-cog';
    const itemLabel = $itemContainer.find('span').first().text().trim() || item.replace('_', ' ');
    
    if ($itemHistorico.length === 0) {
        // Determinar o status do badge baseado na presença de resolução
        const badgeClass = dados.resolucao ? 'bg-success' : 'bg-warning';
        const badgeText = dados.resolucao ? 'Resolvido' : 'Aguardando Resolução';
        const badgeIcon = dados.resolucao ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
        
        // Criar novo item no histórico
        const $historicoContent = $('#historico_content_' + testeId);
        $historicoContent.append(`
            <div class="problema-historico mb-3 p-3 border rounded" id="historico_${item}_${testeId}">
                <div class="d-flex align-items-center mb-2">
                    <i class="${itemIcon} me-2 text-primary"></i>
                    <strong>${itemLabel}</strong>
                    <span class="badge ${badgeClass} ms-2">
                        <i class="${badgeIcon} me-1"></i>${badgeText}
                    </span>
                </div>
                ${dados.problema ? `
                <div class="problema-info mb-2">
                    <strong class="text-danger">Problema Identificado:</strong>
                    <p class="mb-1">${dados.problema}</p>
                    ${gerarBotaoFoto(dados.foto_problema_path, 'Ver Foto do Problema', dados.problema, 'danger')}
                </div>` : ''}
                ${dados.resolucao ? `
                <div class="resolucao-info mb-2">
                    <strong class="text-success">Resolução:</strong>
                    <p class="mb-1">${dados.resolucao}</p>
                    ${dados.foto_resolucao_path ? `
                    <div class="mt-2">
                        <button type="button" 
                                class="btn btn-sm btn-outline-success btn-photo"
                                onclick="abrirModalFoto('\$\{dados.foto_resolucao_path}', 'Foto da Resolução', '${dados.resolucao}')">
                            <i class="fas fa-camera me-1"></i>Ver Foto da Resolução
                        </button>
                    </div>` : ''}
                </div>` : ''}
            </div>
        `);
    } else {
        // Atualizar item existente
        console.log('=== ATUALIZANDO ITEM EXISTENTE ===');
        console.log('Item existente HTML antes:', $itemHistorico.html());
        
        if (dados.resolucao) {
            console.log('Item tem resolução, atualizando para resolvido');
            // Se tem resolução, atualizar badge para resolvido
            $itemHistorico.find('.badge').removeClass('bg-warning').addClass('bg-success')
                .html('<i class="fas fa-check-circle me-1"></i>Resolvido');
            
            // Atualizar ou adicionar seção de resolução
            let $resolucaoInfo = $itemHistorico.find('.resolucao-info');
            if ($resolucaoInfo.length === 0) {
                $itemHistorico.append(`
                    <div class="resolucao-info mb-2">
                        <strong class="text-success">Resolução:</strong>
                        <p class="mb-1">${dados.resolucao}</p>
                        ${gerarBotaoFoto(dados.foto_resolucao_path, 'Ver Foto da Resolução', dados.resolucao, 'success')}
                    </div>
                `);
            } else {
                $resolucaoInfo.html(`
                    <strong class="text-success">Resolução:</strong>
                    <p class="mb-1">${dados.resolucao}</p>
                    ${dados.foto_resolucao_path ? `
                    <div class="mt-2">
                        <button type="button" 
                                class="btn btn-sm btn-outline-success btn-photo"
                                onclick="abrirModalFoto('${dados.foto_resolucao_path}', 'Foto da Resolução', '${dados.resolucao}')">
                            <i class="fas fa-camera me-1"></i>Ver Foto da Resolução
                        </button>
                    </div>` : ''}
                `);
            }
        } else {
            // Se não tem resolução, apenas atualizar informações do problema
            let $problemaInfo = $itemHistorico.find('.problema-info');
            if ($problemaInfo.length === 0 && dados.problema) {
                // Adicionar seção de problema se não existe
                const $badge = $itemHistorico.find('.badge').parent();
                $badge.after(`
                    <div class="problema-info mb-2">
                        <strong class="text-danger">Problema Identificado:</strong>
                        <p class="mb-1">${dados.problema}</p>
                        ${dados.foto_problema_path ? `
                        <div class="mt-2">
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger btn-photo me-2"
                                    onclick="abrirModalFoto('\$\{dados.foto_problema_path}', 'Foto do Problema', '${dados.problema}')">
                                <i class="fas fa-camera me-1"></i>Ver Foto do Problema
                            </button>
                        </div>` : ''}
                    </div>
                `);
            }
        }
    }
    
    // Animar aparição do novo item e atualizar contador
    $itemHistorico = $('#historico_' + item + '_' + testeId);
    
    // Debug para investigar o comportamento de aparecer/sumir
    console.log('Item histórico encontrado:', $itemHistorico.length);
    console.log('HTML do item:', $itemHistorico.html());
    
    // Garantir que o histórico esteja sempre visível
    const $historicoContent = $('#historico_content_' + testeId);
    $historicoContent.show();
    
    // Verificar se já está visível antes de animar
    if ($itemHistorico.is(':visible') && $itemHistorico.html().trim() !== '') {
        console.log('Item já está visível e com conteúdo, não animando novamente');
    } else {
        console.log('Mostrando item novo');
        $itemHistorico.show();
    }
    
    // Atualizar contador de itens
    atualizarContadorHistorico(testeId);
    
    console.log('=== FINALIZANDO atualizarHistoricoProblemas ===');
    console.log('Item final HTML:', $('#historico_' + item + '_' + testeId).html());
    
    // Garantir que o histórico permaneça sempre visível
    const $historicoFinal = $('#historico_content_' + testeId);
    if (!$historicoFinal.is(':visible')) {
        $historicoFinal.show();
        console.log('Forçando histórico a permanecer visível');
    }
}

// Função para salvar descrição do problema com foto
// Função para salvar problema completo (texto + foto)
function salvarProblemaCompleto(testeId, item, callback = null) {
    const formData = new FormData();
    const problemaText = document.getElementById('desc_' + item + '_' + testeId).value;
    const fotoInput = document.getElementById('foto_problema_' + item + '_' + testeId);
    
    // Validação básica
    if (!problemaText || problemaText.trim() === '') {
        mostrarNotificacao('⚠️ Por favor, descreva o problema encontrado', 'warning');
        return;
    }
    
    console.log('=== SALVANDO PROBLEMA ===');
    console.log('TesteId:', testeId);
    console.log('Item:', item);
    console.log('Problema:', problemaText);
    console.log('Foto input encontrado:', !!fotoInput);
    
    // Adicionar campos ao FormData
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('_method', 'PATCH');
    formData.append('item', item);
    formData.append('problema', problemaText);
    
    if (fotoInput && fotoInput.files[0]) {
        console.log('Anexando foto:', fotoInput.files[0].name);
        formData.append('foto', fotoInput.files[0]);
    } else {
        console.log('Nenhuma foto selecionada');
    }
    
    // Debug do FormData
    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ':', pair[1]);
    }

    $.ajax({
        url: '{{ route("testes.salvarProblema", ":id") }}'.replace(':id', testeId),
        method: 'POST',  // Usar POST com _method=PATCH para FormData
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function(xhr, settings) {
            console.log('Enviando requisição para:', settings.url);
            console.log('Method:', settings.type);
        },
        success: function(response) {
            console.log('Resposta do servidor:', response);
            if (response.success) {
                // Obter dados atualizados do teste
                const testeAtualizado = response.teste_atualizado;
                let fotoProblemaPath = response.foto_problema_path;
                
                // Se não encontrou no response, tentar obter dos dados do teste
                if (!fotoProblemaPath && testeAtualizado) {
                    fotoProblemaPath = testeAtualizado[item + '_foto_problema'];
                }
                
                // Armazenar caminho da foto do problema no localStorage para uso posterior
                if (fotoProblemaPath) {
                    localStorage.setItem('foto_problema_' + item + '_' + testeId, fotoProblemaPath);
                    console.log('Foto do problema salva:', fotoProblemaPath);
                }
                
                // Adicionar problema ao histórico imediatamente
                const problemData = {
                    problema: problemaText,
                    resolucao: null, // Ainda não resolvido
                    foto_problema_path: fotoProblemaPath || null,
                    foto_resolucao_path: null
                };
                
                console.log('Adicionando problema ao histórico:', problemData);
                console.log('*** FOTO PROBLEMA PATH ***:', fotoProblemaPath);
                console.log('=== CHAMADA 1: Após salvar problema ===');
                atualizarHistoricoProblemas(testeId, item, problemData);
                
                // ALTERNATIVA: Forçar exibição do botão de foto diretamente
                if (fotoProblemaPath) {
                    setTimeout(() => {
                        const $itemHistorico = $('#historico_' + item + '_' + testeId);
                        if ($itemHistorico.length > 0) {
                            let $problemaDiv = $itemHistorico.find('.problema-info');
                            if ($problemaDiv.length > 0) {
                                // Verificar se já existe botão de foto
                                if ($problemaDiv.find('.btn-photo').length === 0) {
                                    const caminhoNormalizado = fotoProblemaPath.startsWith('/') ? fotoProblemaPath : '/storage/' + fotoProblemaPath;
                                    $problemaDiv.append(`
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-photo" 
                                                    onclick="abrirModalFoto('${caminhoNormalizado}', 'Ver Foto do Problema', '${problemaText}')">
                                                <i class="fas fa-camera me-1"></i>Ver Foto do Problema
                                            </button>
                                        </div>
                                    `);
                                    console.log('*** BOTÃO DE FOTO FORÇADO ***:', caminhoNormalizado);
                                }
                            }
                        }
                    }, 300);
                }
                
                // Garantir que o histórico permaneça expandido após adicionar problema
                setTimeout(() => {
                    const $hist = $('#historico_content_' + testeId);
                    $hist.show();
                    console.log('Histórico forçado a permanecer visível após salvar problema');
                }, 200);
                
                mostrarNotificacao('✅ Problema documentado com sucesso!', 'success');
                if (callback) callback();
            } else {
                mostrarNotificacao('❌ ' + (response.message || 'Erro desconhecido'), 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('=== ERRO AJAX ===');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Text:', xhr.responseText);
            console.error('Status Code:', xhr.status);
            
            let mensagem = 'Erro ao salvar problema';
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    mensagem = response.message;
                } else if (response.errors) {
                    mensagem = Object.values(response.errors).flat().join(', ');
                }
            } catch (e) {
                console.error('Erro ao processar resposta:', e);
                mensagem += ' (Status: ' + xhr.status + ')';
            }
            
            mostrarNotificacao('❌ ' + mensagem, 'error');
            
            // Chamar callback de erro se fornecido
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    });
}

// Função para alternar visibilidade do histórico
function toggleHistorico(testeId) {
    // Função desabilitada - histórico agora fica sempre expandido
    console.log('toggleHistorico desabilitado - histórico sempre visível');
    const $historicoContent = $('#historico_content_' + testeId);
    $historicoContent.show();
}

// Função para atualizar contador de itens no histórico
function atualizarContadorHistorico(testeId) {
    const $historicoContent = $('#historico_content_' + testeId);
    const totalItens = $historicoContent.find('.problema-historico').length;
    const itensResolvidos = $historicoContent.find('.badge.bg-success').length;
    const itensPendentes = totalItens - itensResolvidos;
    
    let textoContador = '';
    if (totalItens === 0) {
        textoContador = '0 itens';
    } else if (itensPendentes === 0) {
        textoContador = `${totalItens} resolvido${totalItens > 1 ? 's' : ''}`;
    } else if (itensResolvidos === 0) {
        textoContador = `${totalItens} pendente${totalItens > 1 ? 's' : ''}`;
    } else {
        textoContador = `${itensResolvidos} resolvido${itensResolvidos > 1 ? 's' : ''}, ${itensPendentes} pendente${itensPendentes > 1 ? 's' : ''}`;
    }
    
    $('#contador_historico_' + testeId).text(textoContador);
    
    // Adicionar classe visual baseada no status
    const $contador = $('#contador_historico_' + testeId);
    $contador.removeClass('bg-secondary bg-success bg-warning');
    
    if (totalItens === 0) {
        $contador.addClass('bg-secondary');
    } else if (itensPendentes === 0) {
        $contador.addClass('bg-success');
    } else {
        $contador.addClass('bg-warning');
    }
}

// Função para resolver problema completo
function resolverProblemaCompleto(testeId, equipamentoId, item, resolucaoTexto) {
    console.log('Resolvendo problema:', { testeId, equipamentoId, item, resolucaoTexto });
    
    const formData = new FormData();
    const fotoInput = document.getElementById('foto_resolucao_' + item + '_' + testeId);
    
    formData.append('item', item);
    formData.append('resolucao', resolucaoTexto);
    
    console.log('Dados sendo enviados:');
    console.log('- item:', item);
    console.log('- resolucao:', resolucaoTexto);
    
    if (fotoInput && fotoInput.files[0]) {
        console.log('Foto anexada:', fotoInput.files[0].name);
        formData.append('foto_resolucao', fotoInput.files[0]);
    } else {
        console.log('Nenhuma foto selecionada');
    }
    
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken);
    console.log('URL da requisição:', '{{ route("testes.resolverProblema", ":id") }}'.replace(':id', testeId));
    
    // Adicionar CSRF token ao FormData também
    formData.append('_token', csrfToken);
    formData.append('_method', 'PATCH');

    $.ajax({
        url: '{{ route("testes.resolverProblema", ":id") }}'.replace(':id', testeId),
        method: 'POST', // Usar POST com _method=PATCH para FormData
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        beforeSend: function(xhr, settings) {
            console.log('=== INICIANDO REQUISIÇÃO ===');
            console.log('URL:', settings.url);
            console.log('Method:', settings.type);
            console.log('Dados do FormData:');
            for (let pair of formData.entries()) {
                console.log('  ' + pair[0] + ':', pair[1]);
            }
            console.log('================================');
        },
        success: function(response) {
            console.log('Resposta recebida:', response);
            
            if (response.success) {
                mostrarNotificacao('🎉 Problema resolvido com sucesso!', 'success');
                
                // Obter dados do problema para o histórico
                const $container = $('#container_' + item + '_' + testeId);
                
                // Buscar a descrição do problema salva
                const problemaDescricao = $container.find('textarea[name="descricao"]').val() || 
                                         $container.find('#desc_' + item + '_' + testeId).val() || '';
                
                // Buscar informações da foto do problema (salva anteriormente)
                const fotoProblemaPath = localStorage.getItem('foto_problema_' + item + '_' + testeId) || null;
                
                console.log('=== DADOS PARA HISTÓRICO ===');
                console.log('Container encontrado:', $container.length > 0);
                console.log('Problema descrição:', problemaDescricao);
                console.log('Resolução texto:', resolucaoTexto);
                console.log('Foto problema path:', fotoProblemaPath);
                console.log('Foto resolução path:', response.foto_resolucao_path);
                
                // Obter dados atualizados do teste
                const testeAtualizado = response.teste_atualizado;
                let fotoResolucaoPath = response.foto_resolucao_path;
                
                // Se não encontrou no response, tentar obter dos dados do teste
                if (!fotoResolucaoPath && testeAtualizado) {
                    fotoResolucaoPath = testeAtualizado[item + '_foto_resolucao'];
                }
                
                const problemData = {
                    problema: problemaDescricao,
                    resolucao: resolucaoTexto,
                    foto_problema_path: fotoProblemaPath,
                    foto_resolucao_path: fotoResolucaoPath || null
                };
                
                // Atualizar histórico de problemas
                console.log('Chamando atualizarHistoricoProblemas com:', { testeId, item, problemData });
                console.log('=== CHAMADA 2: Após resolver problema ===');
                atualizarHistoricoProblemas(testeId, item, problemData);
                
                // ALTERNATIVA: Forçar exibição dos botões de foto diretamente
                setTimeout(() => {
                    const $itemHistorico = $('#historico_' + item + '_' + testeId);
                    if ($itemHistorico.length > 0) {
                        // Forçar botão da foto do problema se existir
                        if (fotoProblemaPath) {
                            let $problemaDiv = $itemHistorico.find('.problema-info');
                            if ($problemaDiv.length > 0 && $problemaDiv.find('.btn-photo').length === 0) {
                                const caminhoNormalizadoProblema = fotoProblemaPath.startsWith('/') ? fotoProblemaPath : '/storage/' + fotoProblemaPath;
                                $problemaDiv.append(`
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-photo" 
                                                onclick="abrirModalFoto('${caminhoNormalizadoProblema}', 'Ver Foto do Problema', '${problemaDescricao}')">
                                            <i class="fas fa-camera me-1"></i>Ver Foto do Problema
                                        </button>
                                    </div>
                                `);
                                console.log('*** BOTÃO PROBLEMA FORÇADO NA RESOLUÇÃO ***:', caminhoNormalizadoProblema);
                            }
                        }
                        
                        // Forçar botão da foto da resolução se existir
                        if (fotoResolucaoPath) {
                            let $resolucaoDiv = $itemHistorico.find('.resolucao-info');
                            if ($resolucaoDiv.length > 0 && $resolucaoDiv.find('.btn-photo').length === 0) {
                                const caminhoNormalizadoResolucao = fotoResolucaoPath.startsWith('/') ? fotoResolucaoPath : '/storage/' + fotoResolucaoPath;
                                $resolucaoDiv.append(`
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-success btn-photo" 
                                                onclick="abrirModalFoto('${caminhoNormalizadoResolucao}', 'Ver Foto da Resolução', '${resolucaoTexto}')">
                                            <i class="fas fa-camera me-1"></i>Ver Foto da Resolução
                                        </button>
                                    </div>
                                `);
                                console.log('*** BOTÃO RESOLUÇÃO FORÇADO ***:', caminhoNormalizadoResolucao);
                            }
                        }
                    }
                    
                    // Garantir que o histórico permaneça expandido após resolver problema
                    const $hist = $('#historico_content_' + testeId);
                    $hist.show();
                    console.log('Histórico forçado a permanecer visível após resolver problema');
                }, 300);
                
                // Atualizar interface para mostrar como OK (resolvido)
                atualizarInterfaceItem(testeId, testeId, item, 'ok', response.progress);
                
                // Esconder área de problema atual
                const $problemaArea = $('#problema-area-' + item + '-' + testeId);
                $problemaArea.slideUp(300);
            } else {
                console.log('Erro na resposta:', response);
                mostrarNotificacao('Erro: ' + (response.message || 'Erro desconhecido'), 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro AJAX completo:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
            
            let mensagemErro = 'Erro ao resolver problema.';
            
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    mensagemErro = response.message;
                }
            } catch (e) {
                console.log('Não foi possível fazer parse da resposta de erro');
            }
            
            mostrarNotificacao(mensagemErro, 'error');
        },
        complete: function() {
            // Reabilitar botão
            $('.resolver-problema-btn[data-item="' + item + '"][data-teste-id="' + testeId + '"]')
                .prop('disabled', false).text('Problema Resolvido');
        }
    });
}

// Sistema de Checklist Interativo - Versão Corrigida
$(document).ready(function() {
    
    // Carregar histórico inicial de problemas resolvidos
    carregarHistoricoInicial();
    
    // Event handler principal para botões do checklist
    $(document).on('click', '.checklist-btn', function(e) {
        e.preventDefault();
        
        console.log('=== BOTÃO CLICADO ===');
        console.log('Elemento clicado:', this);
        
        const $btn = $(this);
        const testeId = $btn.data('teste-id');
        const equipamentoId = $btn.data('equipamento-id');
        const item = $btn.data('item');
        const action = $btn.data('action');
        
        console.log('Dados extraídos:', { testeId, equipamentoId, item, action });
        
        // Desabilitar botão durante a requisição
        $btn.prop('disabled', true);
        
        // Chamar função de atualização
        atualizarStatusChecklist(testeId, equipamentoId, item, action, $btn);
    });
    
    // Botão Salvar Problema
    $(document).on('click', '.salvar-problema-btn', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const testeId = $btn.data('teste-id');
        const item = $btn.data('item');
        
        console.log('=== CLIQUE SALVAR PROBLEMA ===');
        console.log('TesteId extraído:', testeId);
        console.log('Item extraído:', item);
        
        if (!testeId || !item) {
            console.error('Dados faltando - TesteId:', testeId, 'Item:', item);
            mostrarNotificacao('❌ Erro: dados do item não encontrados', 'error');
            return;
        }
        
        $btn.prop('disabled', true).text('Salvando...');
        
        // Usar a função com debugging completo
        salvarProblemaCompleto(testeId, item, function() {
            // Callback de sucesso
            $btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Salvar Problema');
        });
    });
    
    // Botão Problema Resolvido
    $(document).on('click', '.resolver-problema-btn', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const testeId = $btn.data('teste-id');
        const equipamentoId = $btn.data('equipamento-id');
        const item = $btn.data('item');
        const $resolucao = $('#resolucao_' + item + '_' + testeId);
        
        console.log('Debug - Elementos encontrados:', {
            btn: $btn.length,
            testeId: testeId,
            equipamentoId: equipamentoId,
            item: item,
            resolucaoInput: $resolucao.length,
            resolucaoValue: $resolucao.val()
        });
        
        if (!$resolucao.val() || !$resolucao.val().trim()) {
            mostrarNotificacao('Por favor, descreva como o problema foi resolvido.', 'error');
            return;
        }
        
        $btn.prop('disabled', true).text('Resolvendo...');
        console.log('Iniciando resolução de problema:', { 
            testeId: testeId, 
            equipamentoId: equipamentoId, 
            item: item, 
            resolucao: $resolucao.val().trim() 
        });
        resolverProblemaCompleto(testeId, equipamentoId, item, $resolucao.val().trim());
        
        if (!resolucao.trim()) {
            mostrarNotificacao('Por favor, descreva como o problema foi resolvido.', 'error');
            return;
        }
        
        resolverProblema(testeId, item, resolucao);
    });
});

// Função para abrir modal de resolução de problemas
function abrirModalResolucao(testeId, equipamentoId, equipamentoNome, equipamentoTag) {
    testeAtual = {
        id: testeId,
        equipamento_id: equipamentoId
    };
    
    // Atualizar informações do equipamento
    document.getElementById('equipamentoResolucaoInfo').innerHTML = `
        <h6 class="mb-1">${equipamentoNome}</h6>
        <small class="text-muted"><i class="fas fa-tag"></i> ${equipamentoTag}</small>
    `;
    
    // Limpar formulário
    document.getElementById('resolucaoForm').reset();
    
    // Carregar o problema atual
    carregarProblemaAtual(testeId);
    
    // Mostrar modal
    new bootstrap.Modal(document.getElementById('resolucaoModal')).show();
}

// Função para carregar o problema atual
function carregarProblemaAtual(testeId) {
    fetch(`/testes/${testeId}`)
        .then(response => response.json())
        .then(data => {
            const teste = data.teste;
            document.getElementById('problemaAtual').textContent = teste.problema_descricao || 'Nenhuma descrição disponível';
        })
        .catch(error => {
            console.error('Erro ao carregar problema:', error);
            document.getElementById('problemaAtual').textContent = 'Erro ao carregar descrição do problema';
        });
}

// Submeter formulário de resolução
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('resolucaoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Preparar dados para resolução
        const resolucaoDescricao = formData.get('resolucao_descricao');
        const resolvidoPor = formData.get('resolvido_por');
        
        // Criar dados para atualização do teste
        const updateData = new FormData();
        updateData.append('status', 'ok');
        updateData.append('observacoes', `PROBLEMA RESOLVIDO: ${resolucaoDescricao}`);
        updateData.append('testado_por', resolvidoPor);
        updateData.append('_method', 'PATCH');
        
        // Garantir token CSRF válido
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            alert('Erro de segurança. Recarregue a página e tente novamente.');
            location.reload();
            return;
        }

        fetch(`/testes/${testeAtual.id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: updateData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Atualizar UI imediatamente
                atualizarStatusEquipamento(testeAtual.equipamento_id, data.teste);
                
                // Fechar modal
                bootstrap.Modal.getInstance(document.getElementById('resolucaoModal')).hide();
                
                // Mostrar mensagem de sucesso
                mostrarNotificacao('Problema resolvido com sucesso! Equipamento marcado como OK.', 'success');
                
                // Atualizar progresso em tempo real
                atualizarProgresso();
            } else {
                mostrarNotificacao('Erro ao resolver problema: ' + (data.message || 'Erro desconhecido'), 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarNotificacao('Erro de conexão ao resolver problema', 'error');
        });
    });
});

// Função para mostrar notificações
function mostrarNotificacao(message, type) {
    // Remover notificação anterior se existir
    const existente = document.querySelector('.toast-notification');
    if (existente) {
        existente.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} toast-notification`;
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.style.minWidth = '300px';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i>
        ${message}
        <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Remover automaticamente após 5 segundos para dar tempo de ler
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}


</script>
@endpush

<!-- FAB Mobile para Finalizar Parada - Posição otimizada -->
@if($parada->status == 'em_andamento')
<div class="fab-container d-sm-none" style="bottom: 20px; right: 20px; z-index: 1050;">
    <div class="fab-label" style="
        margin-bottom: 8px; 
        transform: translateX(-50%); 
        text-align: center;
        background: rgba(25, 135, 84, 0.95);
        color: white;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 12px;
        border-radius: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.2);
    ">
        Finalizar Parada
    </div>
    <button type="button" class="fab-mobile bg-success border-success" onclick="finalizarParada()" title="Finalizar Parada" data-bs-toggle="tooltip" data-bs-placement="left">
        <i class="fas fa-check text-white"></i>
    </button>
</div>

<!-- Aviso sobre o botão FAB em mobile -->
<div class="d-sm-none">
    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert" style="
        margin: 20px 15px;
        border-left: 4px solid #198754;
        background: linear-gradient(135deg, #d4edda 0%, #f8f9fa 100%);
        box-shadow: 0 2px 8px rgba(25, 135, 84, 0.1);
    ">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Dica:</strong> Use o botão verde flutuante no canto inferior direito para finalizar esta parada rapidamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
    <div style="height: 20px;"></div>
</div>
@endif

@endif {{-- Fechar o @else da verificação de equipamentos --}}

<style>
.historico-problemas {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-top: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    min-height: 100px;
}

.problema-historico {
    background: #fff;
    border: 1px solid #e3f2fd;
    border-left: 4px solid #28a745;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 1rem;
}

.problema-historico:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    border-color: #20c997;
}

.problema-info {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%) !important;
    border-left: 4px solid #dc3545 !important;
    padding: 1rem !important;
    border-radius: 0.5rem !important;
    margin-bottom: 0.75rem !important;
    border: 1px solid #f8d7da !important;
    display: block !important;
    visibility: visible !important;
}

.problema-info strong {
    color: #721c24;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.problema-info p {
    color: #856404 !important;
    margin: 0.5rem 0 0 0 !important;
    font-weight: 500 !important;
    line-height: 1.4 !important;
    font-size: 14px !important;
    display: block !important;
}

.resolucao-info {
    background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 100%) !important;
    border-left: 4px solid #28a745 !important;
    padding: 1rem !important;
    border-radius: 0.5rem !important;
    border: 1px solid #c3e6cb !important;
    display: block !important;
    visibility: visible !important;
}

.resolucao-info strong {
    color: #155724;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.resolucao-info p {
    color: #0f5132 !important;
    margin: 0.5rem 0 0 0 !important;
    font-weight: 500 !important;
    line-height: 1.4 !important;
    font-size: 14px !important;
    display: block !important;
}

.historico-problemas h6 {
    color: #495057;
    font-weight: 700;
    border-bottom: 3px solid #28a745;
    padding-bottom: 0.75rem;
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
}

.historico-problemas h6 i {
    font-size: 1.2rem;
    margin-right: 0.5rem;
    color: #28a745;
}

.badge.bg-success {
    background-color: #28a745 !important;
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
}

.problema-historico .d-flex .badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
}

.problema-historico .d-flex strong {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1rem;
}

.problema-historico .d-flex i:first-child {
    font-size: 1.1rem;
    color: #007bff;
}

.btn-outline-primary:hover {
    transform: scale(1.05);
    transition: all 0.2s ease;
}

/* Estilos para botões de status do checklist */
.status-buttons .btn.active {
    font-weight: bold !important;
    border-width: 2px !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1) !important;
}

.btn-success.active {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: white !important;
}

.btn-danger.active {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: white !important;
}

.btn-secondary.active {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
    color: white !important;
}

/* Cores de fundo para os containers dos itens */
.bg-success {
    background-color: #28a745 !important;
    color: white !important;
}

.bg-danger {
    background-color: #dc3545 !important;
    color: white !important;
}

.bg-secondary {
    background-color: #6c757d !important;
    color: white !important;
}

/* Garantir que o texto riscado seja visível */
.text-decoration-line-through {
    text-decoration: line-through !important;
    opacity: 0.7 !important;
}

/* Estilos para collapse do checklist */
.collapse-icon {
    transition: transform 0.3s ease !important;
}

[data-bs-toggle="collapse"][aria-expanded="true"] .collapse-icon {
    transform: rotate(180deg) !important;
}

[data-bs-toggle="collapse"]:hover {
    background-color: rgba(0, 0, 0, 0.05) !important;
    border-radius: 0.375rem !important;
    transition: background-color 0.2s ease !important;
}

.checklist-section {
    border: 2px solid #e9ecef !important;
    transition: border-color 0.3s ease !important;
}

.checklist-section:hover {
    border-color: #007bff !important;
}

/* Indicador visual de collapse */
.collapse-header {
    position: relative;
}

.collapse-header::after {
    content: "Expandir";
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.75rem;
    color: #6c757d;
    opacity: 0.7;
}

.collapse-header[aria-expanded="true"]::after {
    content: "Recolher";
}

/* Estilos para modal de fotos */
#fotoModal .modal-content {
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

#fotoModalImage {
    transition: transform 0.2s ease;
    cursor: grab;
    user-select: none;
}

#fotoModalImage:active {
    cursor: grabbing;
}

.photo-container {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Loading spinner para fotos */
.photo-loading {
    display: inline-block;
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Garantir que o histórico permaneça sempre visível */
.historico-content {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.historico-problemas .historico-content {
    max-height: none !important;
    overflow: visible !important;
}

/* Garantir que botões de foto sejam sempre visíveis */
.btn-photo {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Botões de foto melhorados */
.btn-photo {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-photo:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Estilos para o histórico colapsável */
.historico-problemas .d-flex[onclick] {
    transition: background-color 0.2s ease;
    border-radius: 4px;
    padding: 8px;
    margin: -8px;
    user-select: none;
}

.historico-problemas .d-flex[onclick]:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

.historico-toggle {
    transition: transform 0.3s ease;
    font-size: 0.9em;
}

.historico-toggle.fa-chevron-right {
    transform: rotate(-90deg);
}

.historico-content {
    overflow: hidden;
}

.problema-historico {
    animation: fadeInUp 0.4s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Estilos para o contador */
[id^="contador_historico_"] {
    font-size: 0.75em;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 12px;
    padding: 0.25em 0.75em;
}

.historico-problemas h6 {
    border-bottom: none !important;
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}
</style>

<!-- Modal para Visualização de Fotos -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="fotoModalLabel">
                    <i class="fas fa-camera me-2"></i>Visualização da Foto
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="photo-container">
                    <img id="fotoModalImage" src="" alt="Foto" class="img-fluid rounded shadow-lg" style="max-height: 70vh; max-width: 100%;">
                </div>
                <div class="mt-3">
                    <p id="fotoModalDescription" class="text-muted mb-3"></p>
                    <div class="btn-group" role="group">
                        <a id="fotoModalDownload" href="" download class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i>Baixar Foto
                        </a>
                        <button type="button" class="btn btn-outline-secondary" onclick="abrirFotoNovaAba()">
                            <i class="fas fa-external-link-alt me-1"></i>Abrir em Nova Aba
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="compartilharFoto()">
                            <i class="fas fa-share me-1"></i>Compartilhar
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Clique e arraste para mover a imagem. Use o scroll para dar zoom.
                </small>
            </div>
        </div>
    </div>
</div>

<script>
// Função auxiliar para normalizar paths de fotos
function normalizarCaminhoFoto(caminhoFoto) {
    if (!caminhoFoto) return '';
    if (caminhoFoto.startsWith('/')) return caminhoFoto;
    if (caminhoFoto.startsWith('http')) return caminhoFoto;
    return '/storage/' + caminhoFoto;
}

// Função auxiliar para gerar HTML de botão de foto
function gerarBotaoFoto(caminhoFoto, titulo, descricao, tipoBotao = 'danger') {
    console.log('*** gerarBotaoFoto chamada ***', { caminhoFoto, titulo, descricao, tipoBotao });
    if (!caminhoFoto) {
        console.log('*** Caminho foto vazio, retornando string vazia ***');
        return '';
    }
    
    const caminhoNormalizado = normalizarCaminhoFoto(caminhoFoto);
    console.log('*** Caminho normalizado ***:', caminhoNormalizado);
    const classeBotao = tipoBotao === 'success' ? 'btn-outline-success' : 'btn-outline-danger';
    
    return `
        <div class="mt-2">
            <button type="button" 
                    class="btn btn-sm ${classeBotao} btn-photo me-2"
                    onclick="abrirModalFoto('${caminhoNormalizado}', '${titulo}', '${descricao}')">
                <i class="fas fa-camera me-1"></i>${titulo}
            </button>
        </div>
    `;
}

// Função para abrir modal de foto
function abrirModalFoto(imagemSrc, titulo, descricao) {
    console.log('Abrindo modal de foto:', { imagemSrc, titulo, descricao });
    
    // Normalizar o caminho da imagem
    imagemSrc = normalizarCaminhoFoto(imagemSrc);
    
    const modal = new bootstrap.Modal(document.getElementById('fotoModal'));
    const imagem = document.getElementById('fotoModalImage');
    const tituloModal = document.getElementById('fotoModalLabel');
    const descricaoModal = document.getElementById('fotoModalDescription');
    const downloadLink = document.getElementById('fotoModalDownload');
    
    // Configurar conteúdo do modal
    imagem.src = imagemSrc;
    tituloModal.innerHTML = '<i class="fas fa-camera me-2"></i>' + titulo;
    descricaoModal.textContent = descricao || 'Foto do problema/resolução';
    downloadLink.href = imagemSrc;
    
    // Configurar nome do arquivo para download
    const nomeArquivo = titulo.toLowerCase().replace(/\s+/g, '_') + '_' + Date.now() + '.jpg';
    downloadLink.download = nomeArquivo;
    
    // Mostrar modal
    modal.show();
    
    // Adicionar funcionalidade de zoom e pan
    adicionarZoomPan(imagem);
}

// Função para adicionar funcionalidade de zoom e pan
function adicionarZoomPan(imagem) {
    let escala = 1;
    let posX = 0;
    let posY = 0;
    let arrastando = false;
    let ultimoX = 0;
    let ultimoY = 0;
    
    // Reset ao abrir
    imagem.style.transform = 'scale(1) translate(0px, 0px)';
    escala = 1;
    posX = 0;
    posY = 0;
    
    // Zoom com scroll
    imagem.addEventListener('wheel', function(e) {
        e.preventDefault();
        
        const deltaScale = e.deltaY > 0 ? 0.9 : 1.1;
        escala *= deltaScale;
        
        // Limitar zoom
        escala = Math.min(Math.max(0.5, escala), 3);
        
        atualizarTransform();
    });
    
    // Pan com mouse
    imagem.addEventListener('mousedown', function(e) {
        arrastando = true;
        ultimoX = e.clientX;
        ultimoY = e.clientY;
        imagem.style.cursor = 'grabbing';
    });
    
    document.addEventListener('mousemove', function(e) {
        if (arrastando) {
            const deltaX = e.clientX - ultimoX;
            const deltaY = e.clientY - ultimoY;
            
            posX += deltaX;
            posY += deltaY;
            
            ultimoX = e.clientX;
            ultimoY = e.clientY;
            
            atualizarTransform();
        }
    });
    
    document.addEventListener('mouseup', function() {
        arrastando = false;
        imagem.style.cursor = 'grab';
    });
    
    // Reset com duplo clique
    imagem.addEventListener('dblclick', function() {
        escala = 1;
        posX = 0;
        posY = 0;
        atualizarTransform();
    });
    
    function atualizarTransform() {
        imagem.style.transform = `scale(${escala}) translate(${posX}px, ${posY}px)`;
    }
    
    // Cursor inicial
    imagem.style.cursor = 'grab';
}

// Função para abrir foto em nova aba
function abrirFotoNovaAba() {
    const imagemSrc = document.getElementById('fotoModalImage').src;
    window.open(imagemSrc, '_blank');
}

// Função para compartilhar foto
function compartilharFoto() {
    const imagemSrc = document.getElementById('fotoModalImage').src;
    const titulo = document.getElementById('fotoModalLabel').textContent;
    
    if (navigator.share) {
        navigator.share({
            title: titulo,
            text: 'Foto do checklist de manutenção',
            url: imagemSrc
        }).catch(console.error);
    } else {
        // Fallback: copiar link para clipboard
        navigator.clipboard.writeText(imagemSrc).then(() => {
            mostrarNotificacao('Link da foto copiado para a área de transferência!', 'info');
        });
    }
}
</script>

@endsection
