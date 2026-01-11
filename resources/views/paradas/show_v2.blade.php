@extends('layouts.app')

@section('title', 'Parada: ' . $parada->nome)

@section('styles')
<style>
    /* Layout base */
    .parada-shell { max-width: 1320px; }
    .parada-layout { display: grid; grid-template-columns: 320px 1fr; gap: 1.75rem; align-items: start; }
    .summary-panel { position: sticky; top: 90px; }
    .content-panel { min-width: 0; }

    /* Summary */
    .summary-card { border: 1px solid rgba(44, 90, 160, 0.12); background: #fff; box-shadow: 0 10px 24px rgba(18, 38, 63, 0.08); }
    .summary-header { display: flex; gap: 0.85rem; align-items: center; }
    .summary-icon { width: 46px; height: 46px; border-radius: 12px; background: rgba(23, 162, 184, 0.15); color: var(--primary-blue); display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .summary-meta { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.25rem; }
    .summary-pill { padding: 0.25rem 0.6rem; border-radius: 999px; font-size: 0.72rem; letter-spacing: 0.2px; background: rgba(44, 90, 160, 0.1); color: #2f3f55; font-weight: 600; text-transform: uppercase; }
    .summary-section { margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(44, 90, 160, 0.08); }
    .summary-label { font-size: 0.72rem; letter-spacing: 0.35px; text-transform: uppercase; color: #6b7c93; font-weight: 600; }
    .summary-value { font-size: 0.98rem; font-weight: 700; color: #1f2d3d; }
    .summary-value.small { font-size: 0.9rem; font-weight: 600; color: #3b4d63; }
    .summary-description { display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
    .summary-progress { background: #f6f9fc; border-radius: 10px; padding: 0.8rem 0.9rem; border: 1px solid rgba(44, 90, 160, 0.08); }
    .summary-progress .progress { height: 8px; }
    .summary-progress .progress-stats { font-size: 0.85rem; font-weight: 600; margin-top: 0.35rem; }
    .summary-actions { display: flex; flex-direction: column; gap: 0.6rem; }
    .summary-actions .btn { border-radius: 10px; font-weight: 600; }
    .summary-toolbar { display: flex; flex-direction: column; gap: 0.5rem; }

    /* Area card */
    .area-shell { border-radius: 16px; overflow: hidden; border: 1px solid rgba(30, 54, 86, 0.12); box-shadow: 0 12px 28px rgba(18, 38, 63, 0.08); background: #fff; }
    .area-header-v3 { background: #f0f6ff; border-bottom: 1px solid rgba(30, 54, 86, 0.08); color: #1f2d3d; }
    .area-header-v3 .area-title { display: flex; align-items: center; gap: 0.6rem; font-weight: 700; color: #1f2d3d; }
    .area-header-v3 .fa-map-marker-alt { color: #1e3d72; }
    .area-header-v3.area-header-progress { background: linear-gradient(135deg, #e2f4ff 0%, #f5fbff 100%) !important; }
    .area-header-v3.area-header-completed { background: linear-gradient(135deg, #e9f7ef 0%, #f5fbf7 100%) !important; }
    .area-header-v3.area-header-pending { background: linear-gradient(135deg, #eef1f6 0%, #f6f8fb 100%) !important; }
    .area-shell .card-header { padding: 0.9rem 1.2rem; }
    .area-shell .card-body { padding: 1.1rem 1.2rem 0.8rem; background: #f8fafc; }
    .area-shell .area-progress-badge { background: rgba(255, 255, 255, 0.92) !important; color: #1f2d3d !important; border-radius: 999px; padding: 0.35rem 0.75rem; font-weight: 700; box-shadow: 0 6px 14px rgba(15, 28, 51, 0.12); }

    /* Equipment card */
    .equipment-shell { border-radius: 14px; border: 1px solid rgba(30, 54, 86, 0.12); background: #fff; box-shadow: 0 8px 18px rgba(18, 38, 63, 0.06); margin-bottom: 0.9rem; overflow: hidden; position: relative; }
    .equipment-shell::before { content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: linear-gradient(180deg, #1e3d72 0%, #0dcaf0 100%); }
    .equipment-shell .equipment-header { background: linear-gradient(90deg, #f2f7ff 0%, #ffffff 70%); padding: 0.9rem 1.2rem 0.85rem; border-bottom: 1px solid rgba(30, 54, 86, 0.12); }
    .equipment-shell .equipment-title h6 { font-size: 1.15rem; font-weight: 800; color: #1e3d72; letter-spacing: 0.2px; }
    .equipment-shell .equipment-title small { font-size: 0.9rem; color: #5b6b82; }
    .equipment-shell .equipment-header .progress { min-width: 120px; height: 6px; border-radius: 999px; }
    .equipment-shell .badge { border-radius: 999px; padding: 0.3rem 0.65rem; font-weight: 700; }
    .equipment-shell .equipment-body { padding-top: 1.2rem; border-top: 1px solid rgba(30, 54, 86, 0.08); background: #fefefe; }

    /* Checklist line */
    .checklist-line { border: 1px solid rgba(30, 54, 86, 0.08); border-radius: 12px; padding: 0.9rem 1rem; margin-bottom: 0.75rem; background: #fff; transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .checklist-line:hover { transform: translateY(-2px); box-shadow: 0 10px 18px rgba(18, 38, 63, 0.08); }
    .checklist-content { display: grid; grid-template-columns: minmax(0, 1fr) minmax(340px, 420px); gap: 1rem; align-items: center; }
    .checklist-info { display: flex; align-items: center; gap: 0.6rem; font-size: 0.98rem; }
    .checklist-actions { display: flex; justify-content: flex-end; }
    .checklist-actions .status-badge { width: 100%; display: flex; align-items: center; justify-content: flex-end; gap: 0.6rem; flex-wrap: wrap; }
    .checklist-actions .badge { border-radius: 999px; padding: 0.35rem 0.75rem; font-weight: 700; }
    .action-buttons { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .action-buttons .btn { min-width: 120px; border-radius: 10px; font-weight: 600; box-shadow: 0 6px 12px rgba(15, 28, 51, 0.08); transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .action-buttons .btn:hover { transform: translateY(-1px); box-shadow: 0 8px 16px rgba(15, 28, 51, 0.12); }

    /* Toast */
    .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
    .toast { background-color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px; overflow: hidden; }
    .toast-header { background-color: rgba(255,255,255,0.95); border-bottom: 1px solid rgba(0,0,0,0.05); }
    .toast-success .toast-header { color: #198754; }
    .toast-error .toast-header { color: #dc3545; }
    .transition-colors { transition: background-color 0.5s ease, background 0.5s ease; }

    /* Mobile */
    @media (max-width: 991.98px) {
        .parada-layout { grid-template-columns: 1fr; }
        .summary-panel { position: static; }
        .checklist-content { grid-template-columns: 1fr; }
        .checklist-actions { justify-content: flex-start; }
        .action-buttons { flex-direction: column; }
    }

    @media (max-width: 767.98px) {
        .container.my-4 { padding-left: 12px; padding-right: 12px; }
        .action-buttons .btn-success { background: #28a745 !important; border-color: #28a745 !important; color: #fff !important; }
    }

    /* Mobile overrides to remove hover/transition animations that interfere with touch
       and to fix layout/overlap issues on small screens */
    @media (max-width: 991.98px) {
        /* Disable transitions and transforms on interactive elements (touch devices) */
        .checklist-line,
        .checklist-line:hover,
        .action-buttons .btn,
        .action-buttons .btn:hover,
        .mobile-user-card,
        .mobile-parada-card,
        .mobile-card-list .mobile-parada-card {
            transition: none !important;
            transform: none !important;
            box-shadow: none !important;
        }

        /* Improve tap responsiveness */
        .action-buttons .btn,
        .card .btn,
        .summary-card .btn {
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }

        /* Equipment header: avoid overlapping progress / badges */
        .equipment-shell .equipment-header { display: flex; flex-direction: column; gap: 0.35rem; align-items: stretch; }
        .equipment-shell .equipment-header .equipment-title { min-height: 3.2rem; }
        .equipment-shell .equipment-header .progress { min-width: 0 !important; width: 100% !important; }
        .equipment-shell .equipment-header .badge { position: relative; margin-left: 0; margin-top: 0.25rem; }

        /* Ensure long titles wrap and don't overlap */
        .equipment-shell .equipment-title h6 { white-space: normal; word-break: break-word; overflow-wrap: break-word; }

        /* Small fixes for status badges and action buttons in the checklist lines */
        .checklist-actions .status-badge { justify-content: flex-start; gap: 0.5rem; }
        .action-buttons { gap: 0.4rem; }
    }

    /* Transformar cards em lista horizontal para telas grandes */
    @media (min-width: 1200px) {
        /* Cards de equipamento transformados em lista */
        .equipment-shell { 
            margin-bottom: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border-radius: 8px;
            border: 1px solid #e3e8ef;
            transition: all 0.2s ease;
        }
        
        .equipment-shell:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-color: #17a2b8;
        }
        
        .equipment-shell .equipment-header { 
            padding: 0.85rem 1.25rem;
            background: linear-gradient(90deg, #f8fafc 0%, #fff 100%);
            align-items: center;
        }
        
        .equipment-shell .equipment-title h6 { 
            font-size: 1rem;
            margin-bottom: 0.15rem;
        }
        
        .equipment-shell .equipment-title small {
            font-size: 0.85rem;
        }
        
        .equipment-progress { max-width: 120px; }
        
        .equipment-body { 
            padding: 0.5rem 1.25rem 0.85rem;
        }
        
        /* Itens do checklist como linhas de lista */
        .checklist-line {
            padding: 0.75rem 0;
            margin-bottom: 0;
            border-radius: 0;
            border: none;
            border-bottom: 1px solid #f0f3f7;
            background: transparent;
        }
        
        .checklist-line:last-child {
            border-bottom: none;
        }
        
        .checklist-line:hover {
            background: #f8fafc;
            transform: none;
            box-shadow: none;
        }
        
        .checklist-content {
            display: grid;
            grid-template-columns: 1fr;
            grid-template-areas: "info" "actions";
            gap: 0.5rem;
            align-items: start;
        }
        
        .checklist-info { 
            grid-area: info;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .checklist-info i {
            font-size: 1rem;
            margin-right: 0.65rem;
        }
        
        .checklist-actions { 
            grid-area: actions;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            gap: 0.5rem;
        }
        
        .status-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-badge .badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }
        
        .action-buttons { 
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn { 
            min-width: 110px;
            padding: 0.4rem 0.85rem;
            font-size: 0.875rem;
            font-weight: 600;
        }
    }

</style>
@endsection

@section('content')
    <div class="container my-4 parada-shell">
    @php
        $statusLabel = ucfirst(str_replace('_', ' ', $parada->status));
        $statusClass = $parada->status === 'concluida' ? 'bg-success' : ($parada->status === 'em_andamento' ? 'bg-warning text-dark' : 'bg-secondary');
        $descricaoTexto = $parada->descricao ?? 'N/A';
        $paradaFinalizada = $parada->status === 'concluida';
    @endphp
    <div class="parada-layout">
        <aside class="summary-panel">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="summary-header">
                        <div class="summary-icon">
                            <i class="fas fa-industry"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">{{ $parada->nome }}</h5>
                            <div class="summary-meta">
                                <span class="summary-pill">Macro {{ $parada->macro }}</span>
                                <span class="summary-pill">Tipo {{ ucfirst($parada->tipo) }}</span>
                                <span class="summary-pill">{{ $statusLabel }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="summary-section summary-progress">
                        <div class="summary-label">Progresso geral</div>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-success" style="width: {{ $percentualGeral }}%"></div>
                        </div>
                        <div class="progress-stats">
                            <span id="parada_percentual_geral">{{ $percentualGeral }}%</span>
                            <span class="mx-1">•</span>
                            <span id="parada_progress_info">{{ $parada->testes_ok }} resolvido(s) • {{ $parada->testes_pendentes }} pendente(s)</span>
                        </div>
                    </div>

                    <div class="summary-section">
                        <div class="summary-label">Data de início</div>
                        <div class="summary-value">{{ \Carbon\Carbon::parse($parada->data_inicio)->format('d/m/Y') }}</div>
                    </div>
                    <div class="summary-section">
                        <div class="summary-label">Duração prevista</div>
                        <div class="summary-value">{{ $parada->duracao_prevista_horas ?? 'N/A' }} horas</div>
                    </div>
                    <div class="summary-section">
                        <div class="summary-label">Equipe responsável</div>
                        <div class="summary-value small">{{ $parada->equipe_responsavel ?? 'N/A' }}</div>
                    </div>
                    <div class="summary-section">
                        <div class="summary-label">Descrição</div>
                        <div class="summary-value small summary-description">{{ $descricaoTexto }}</div>
                    </div>

                    <div class="summary-section summary-actions">
                        @if($parada->status === 'concluida')
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>Parada Finalizada
                            </span>
                            @if(session('user.perfil') === 'admin')
                                <button type="button" class="btn btn-warning btn-sm" onclick="reabrirParada()" title="Reabrir esta parada (Apenas Administrador)">
                                    <i class="fas fa-unlock me-1"></i>Reabrir Parada
                                </button>
                            @endif
                        @else
                            @if($percentualGeral == 100)
                                <button type="button" class="btn btn-success btn-sm" onclick="finalizarParada()" title="Finalizar esta parada">
                                    <i class="fas fa-flag-checkered me-1"></i>Finalizar Parada
                                </button>
                            @endif
                            <span class="badge {{ $statusClass }} fs-6">
                                <i class="fas fa-circle me-1"></i>{{ $statusLabel }}
                            </span>
                        @endif
                    </div>

                    <div class="summary-section summary-toolbar">
                        <a href="{{ route('paradas.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Voltar para Paradas Ativas
                        </a>
                        <a href="{{ route('paradas.relatorio', $parada) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar me-1"></i>Ver Relatório Completo
                        </a>
                        <a href="{{ route('paradas.show', $parada) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-layer-group me-1"></i>Ver Layout Atual
                        </a>
                        @if($parada->status === 'concluida')
                            <a href="{{ route('paradas.historico') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-history me-1"></i>Ver Histórico
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </aside>

        <section class="content-panel">
        @if($paradaFinalizada)
            <!-- Aviso de Parada Finalizada -->
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="alert-heading mb-1">
                            <i class="fas fa-flag-checkered me-2"></i>Parada Finalizada
                        </h5>
                        <p class="mb-0">
                            Esta parada foi finalizada em <strong>{{ $parada->data_fim ? $parada->data_fim->format('d/m/Y \à\s H:i') : 'data não registrada' }}</strong>.
                            <br>Não é possível realizar alterações nos checklists. Esta página está em modo de visualização.
                            @if(session('user.perfil') === 'admin')
                                <br><small class="text-muted"><i class="fas fa-key me-1"></i>Como administrador, você pode reabrir esta parada usando o botão "Reabrir Parada" no resumo.</small>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($semEquipamentos) && $semEquipamentos)
        <!-- Mensagem quando não há equipamentos -->
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle me-2"></i>Nenhum Equipamento Selecionado</h5>
            <p>Esta parada ainda não possui equipamentos selecionados para checklist.</p>
            <a href="{{ route('paradas.select-equipment', $parada) }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Selecionar Equipamentos
            </a>
        </div>
    @else
        <!-- Toast Container para Notificações -->
        <div class="toast-container">
            <!-- Toast será injetado via JS -->
        </div>

        <!-- Lista de Áreas e Equipamentos -->
        @foreach($areas as $area)
        <!-- Card da Área contendo todos os equipamentos -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card area-card area-shell shadow-sm">
                    <!-- Header da Área -->
                    @php
                        $areaTotal = $area->equipamentos->count();
                        $areaConcluidos = $area->equipamentos->filter(function($equip) {
                            $teste = $equip->testes->first();
                            return $teste && intval($teste->checklist_progress) === 100;
                        })->count();
                        $percent = $percentualPorArea[$area->id] ?? 0;
                        
                        // Determinar classe de cor baseada no progresso
                        $headerClass = 'area-header-pending'; // Padrão cinza
                        if ($percent >= 100) {
                            $headerClass = 'area-header-completed'; // Verde
                        } elseif ($percent > 0) {
                            $headerClass = 'area-header-progress'; // Azul
                        }
                    @endphp
                    <div class="card-header {{ $headerClass }} area-header-v3 transition-colors" data-area-id="{{ $area->id }}">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 gap-md-0">
                            <div class="area-title">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="area-label">{{ $area->nome }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 gap-md-3 w-100 w-md-auto justify-content-between justify-content-md-end">
                                @php
                                    $areaTotal = $area->equipamentos->count();
                                    $areaConcluidos = $area->equipamentos->filter(function($equip) {
                                        $teste = $equip->testes->first();
                                        return $teste && intval($teste->checklist_progress) === 100;
                                    })->count();
                                @endphp
                                <span class="badge bg-light text-dark fs-7 fs-md-6 area-progress-badge" data-area-id="{{ $area->id }}">
                                    <span class="area-completed-count">{{ $areaConcluidos }}</span>/<span class="area-total-count">{{ $areaTotal }}</span> concluídos • <span class="area-percent">{{ $percentualPorArea[$area->id] ?? 0 }}%</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Container dos equipamentos dentro do card -->
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Equipamentos da Área (dentro do card) -->
                            @foreach($area->equipamentos as $equipamento)
                                @php $teste = $equipamento->testes->first() @endphp
                                @if($teste)
                                <div class="col-12 col-sm-6 col-md-6 col-lg-12">
                                    <div class="equipment-card equipment-shell" id="equipamento_{{ $equipamento->id }}_{{ $teste->id }}">
                                        <!-- Header do Equipamento -->
                                        <div class="equipment-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-cog me-2 text-primary"></i>
                                                    <div class="equipment-title">
                                                        <h6 class="mb-0 fw-bold">{{ $equipamento->nome }}</h6>
                                                        <small class="text-muted">({{ $equipamento->tag }})</small>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2 equipment-progress" style="height: 6px;">
                                                        <div class="progress-bar bg-{{ $teste->checklist_progress == 100 ? 'success' : ($teste->checklist_progress > 0 ? 'warning' : 'danger') }}" 
                                                             style="width: {{ $teste->checklist_progress }}%"></div>
                                                    </div>
                                                    <span class="badge bg-{{ $teste->checklist_progress == 100 ? 'success' : 'warning' }}">
                                                        {{ $teste->checklist_progress }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Checklist Items -->
                                        <div class="equipment-body">
                                            @foreach($teste->checklist_items as $key => $item)
                                                <div class="checklist-item checklist-line" id="item_{{ $key }}_{{ $teste->id }}">
                                                    <div class="checklist-content">
                                                        <div class="checklist-info">
                                                            <i class="{{ $item['icon'] }} me-2 text-primary"></i>
                                                            <span class="fw-bold">{{ $item['label'] }}</span>
                                                            @php
                                                                $statusAtual = $item['status'] ?? 'pendente';
                                                            @endphp
                                                            @if($statusAtual === 'ok')
                                                                <i class="fas fa-check-circle text-success ms-2"></i>
                                                            @elseif($statusAtual === 'problema')
                                                                <i class="fas fa-exclamation-triangle text-warning ms-2"></i>
                                                            @elseif($statusAtual === 'nao_aplica')
                                                                <i class="fas fa-ban text-info ms-2"></i>
                                                            @else
                                                                <i class="fas fa-clock text-warning ms-2"></i>
                                                            @endif
                                                        </div>
                                                        <div class="checklist-actions">
                                                            @php
                                                                $statusAtual = $item['status'] ?? 'pendente';
                                                            @endphp
                                                            
                                                            @if($statusAtual === 'pendente')
                                                                <!-- Status: Pendente - Mostrar botões de ação -->
                                                                @if($paradaFinalizada)
                                                                    <div class="status-badge">
                                                                        <span class="badge bg-warning text-dark">
                                                                            <i class="fas fa-clock me-1"></i>Pendente
                                                                        </span>
                                                                        <small class="text-muted">Parada finalizada</small>
                                                                    </div>
                                                                @else
                                                                    <div class="status-badge">
                                                                        <span class="badge bg-warning text-dark">
                                                                            <i class="fas fa-clock me-1"></i>Status: Pendente
                                                                        </span>
                                                                        <div class="action-buttons">
                                                                            <button class="btn btn-success" onclick="marcarOK('{{ $key }}', {{ $teste->id }}, event); return false;">
                                                                                <i class="fas fa-check me-1"></i>OK
                                                                            </button>
                                                                            <button class="btn btn-danger" onclick="reportarProblema('{{ $key }}', {{ $teste->id }}, event); return false;">
                                                                                <i class="fas fa-exclamation-triangle me-1"></i>Problema
                                                                            </button>
                                                                            <button class="btn btn-info" onclick="marcarNaoAplica('{{ $key }}', {{ $teste->id }}, event); return false;">
                                                                                <i class="fas fa-ban me-1"></i>N/A
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @elseif($statusAtual === 'problema')
                                                                <!-- Status: Problema - Mostrar botão resolver + editar -->
                                                                <div class="status-badge">
                                                                    <span class="badge bg-danger">
                                                                        <i class="fas fa-exclamation-triangle me-1"></i>Status: Com Problema
                                                                    </span>
                                                                    @if($paradaFinalizada)
                                                                        <small class="text-muted">Parada finalizada</small>
                                                                    @else
                                                                        <div class="action-buttons">
                                                                            @if(empty($item['resolucao']))
                                                                                <button class="btn btn-warning" onclick="mostrarResolucao('{{ $key }}', {{ $teste->id }})">
                                                                                    <i class="fas fa-wrench me-1"></i>Resolver
                                                                                </button>
                                                                            @else
                                                                                <button class="btn btn-success" onclick="mostrarResolucao('{{ $key }}', {{ $teste->id }})">
                                                                                    <i class="fas fa-edit me-1"></i>Editar
                                                                                </button>
                                                                            @endif
                                                                            <button class="btn btn-outline-secondary" onclick="editarStatus('{{ $key }}', {{ $teste->id }}, '{{ $statusAtual }}')" title="Alterar status">
                                                                                <i class="fas fa-edit me-1"></i>Alterar
                                                                            </button>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <!-- Status: OK ou N/A - Mostrar badge + botão editar -->
                                                                <div class="status-badge">
                                                                    @if($statusAtual === 'ok')
                                                                        <span class="badge bg-success">
                                                                            <i class="fas fa-check me-1"></i>Status: OK
                                                                        </span>
                                                                    @elseif($statusAtual === 'nao_aplica')
                                                                        <span class="badge bg-info">
                                                                            <i class="fas fa-ban me-1"></i>Status: Não se aplica
                                                                        </span>
                                                                    @endif
                                                                    @if(!$paradaFinalizada)
                                                                        <div class="action-buttons">
                                                                            <button class="btn btn-outline-secondary" onclick="editarStatus('{{ $key }}', {{ $teste->id }}, '{{ $statusAtual }}')" title="Alterar status">
                                                                                <i class="fas fa-edit me-1"></i>Alterar Status
                                                                            </button>
                                                                        </div>
                                                                    @else
                                                                        <small class="text-muted">Parada finalizada</small>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Formulário de Problema (inicialmente oculto) -->
                                                    <div class="problema-form mt-3" id="problema_form_{{ $key }}_{{ $teste->id }}" style="display: none;">
                                                        <div class="card bg-light">
                                                            <div class="card-body">
                                                                <h6 class="text-danger">
                                                                    <i class="fas fa-exclamation-triangle me-2"></i>Reportar Problema
                                                                </h6>
                                                                <form id="form_problema_{{ $key }}_{{ $teste->id }}">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Descrição do Problema:</label>
                                                                        <textarea class="form-control" name="problema" rows="3" required placeholder="Descreva o problema encontrado..."></textarea>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Foto (Opcional):</label>
                                                                        <input type="file" class="form-control" name="foto" accept="image/*">
                                                                    </div>
                                                                    <div class="d-flex gap-2">
                                                                        <button type="button" class="btn btn-primary" onclick="salvarProblema('{{ $key }}', {{ $teste->id }})">
                                                                            <i class="fas fa-save me-1"></i>Salvar Problema
                                                                        </button>
                                                                        <button type="button" class="btn btn-secondary" onclick="cancelarProblema('{{ $key }}', {{ $teste->id }})">
                                                                            <i class="fas fa-times me-1"></i>Cancelar
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Formulário de Resolução (inicialmente oculto) -->
                                                    <div class="resolucao-form mt-3" id="resolucao_form_{{ $key }}_{{ $teste->id }}" style="display: none;">
                                                        <div class="card bg-light">
                                                            <div class="card-body">
                                                                <h6 class="text-success">
                                                                    <i class="fas fa-wrench me-2"></i>Resolver Problema
                                                                </h6>
                                                                <form id="form_resolucao_{{ $key }}_{{ $teste->id }}">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Descrição da Resolução:</label>
                                                                        <textarea class="form-control" name="resolucao" rows="3" required placeholder="Descreva como o problema foi resolvido..."></textarea>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Foto da Resolução (Opcional):</label>
                                                                        <input type="file" class="form-control" name="foto" accept="image/*">
                                                                    </div>
                                                                    <div class="resolucao-error text-danger small mb-2" style="display:none"></div>
                                                                    <div class="d-flex gap-2">
                                                                        <button type="button" class="btn btn-success" onclick="salvarResolucao('{{ $key }}', {{ $teste->id }})">
                                                                            <i class="fas fa-check me-1"></i>Marcar como Resolvido
                                                                        </button>
                                                                        <button type="button" class="btn btn-secondary" onclick="cancelarResolucao('{{ $key }}', {{ $teste->id }})">
                                                                            <i class="fas fa-times me-1"></i>Cancelar
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                        </div>

                                        <!-- Histórico de Problemas -->
                                        @php
                                            $historico = collect($teste->checklist_items)->filter(function($item) {
                                                return !empty($item['problema']) || !empty($item['resolucao']);
                                            });
                                        @endphp

                                        @if($historico->count() > 0)
                                        <div class="historico-section mt-4">
                                            <h6 class="text-primary mb-3">
                                                <i class="fas fa-history me-2"></i>Histórico de Problemas
                                            </h6>
                                            @foreach($historico as $key => $item)
                                            <div class="historico-item">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="{{ $item['icon'] }} me-2 text-primary"></i>
                                                    <strong>{{ $item['label'] }}</strong>
                                                    @if(!empty($item['resolucao']))
                                                        <span class="badge bg-success ms-2">Resolvido</span>
                                                    @else
                                                        <span class="badge bg-warning ms-2">Pendente</span>
                                                    @endif
                                                </div>
                                                
                                                @if(!empty($item['problema']))
                                                <div class="problema-info mb-2">
                                                    <strong class="text-danger">Problema:</strong>
                                                    <p class="mb-1">{{ $item['problema'] }}</p>
                                                    @if(!empty($item['foto_problema_path']))
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="mostrarFoto('{{ Storage::url($item['foto_problema_path']) }}', 'Foto do Problema')">
                                                        <i class="fas fa-camera me-1"></i>Ver Foto do Problema
                                                    </button>
                                                    @endif
                                                </div>
                                                @endif
                                                
                                                @if(!empty($item['resolucao']))
                                                <div class="resolucao-info">
                                                    <strong class="text-success">Resolução:</strong>
                                                    <p class="mb-1">{{ $item['resolucao'] }}</p>
                                                    @if(!empty($item['foto_resolucao_path']))
                                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="mostrarFoto('{{ Storage::url($item['foto_resolucao_path']) }}', 'Foto da Resolução')">
                                                        <i class="fas fa-camera me-1"></i>Ver Foto da Resolução
                                                    </button>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
        </section>
    </div>
</div>

<!-- Modal para visualizar fotos -->
<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalTitle">Visualizar Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fotoModalImage" src="" class="img-fluid" alt="Foto">
            </div>
        </div>
    </div>
</div>

<script>
// Função para atualizar interface do item dinamicamente
function atualizarInterfaceItem(item, testeId, novoStatus, data) {
    const itemElement = document.getElementById(`item_${item}_${testeId}`);
    if (!itemElement) return;
    
    const checklistInfo = itemElement.querySelector('.checklist-info');
    const checklistActions = itemElement.querySelector('.checklist-actions');
    
    // Remover ícones de status existentes
    const existingIcons = checklistInfo.querySelectorAll('.fas.fa-check-circle, .fas.fa-exclamation-triangle, .fas.fa-ban');
    existingIcons.forEach(icon => icon.remove());
    
    // Adicionar novo ícone baseado no status
    let novoIcone = '';
    if (novoStatus === 'ok') {
        novoIcone = '<i class="fas fa-check-circle text-success ms-2"></i>';
    } else if (novoStatus === 'problema') {
        novoIcone = '<i class="fas fa-exclamation-triangle text-warning ms-2"></i>';
    } else if (novoStatus === 'nao_aplica') {
        novoIcone = '<i class="fas fa-ban text-info ms-2"></i>';
    }
    
    if (novoIcone) {
        checklistInfo.insertAdjacentHTML('beforeend', novoIcone);
    }
    
    // Atualizar botões de ação baseado no novo status
    let novosButtonsHtml = '';
    
    if (novoStatus === 'pendente') {
        novosButtonsHtml = `
            <div class="status-badge">
                <span class="badge bg-warning text-dark me-2">
                    <i class="fas fa-clock me-1"></i>Status: Pendente
                </span>
                <div class="action-buttons">
                    <button class="btn btn-success btn-sm" onclick="marcarOK('${item}', ${testeId}, event); return false;">
                        <i class="fas fa-check me-1"></i>OK
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="reportarProblema('${item}', ${testeId}, event); return false;">
                        <i class="fas fa-exclamation-triangle me-1"></i>Problema
                    </button>
                    <button class="btn btn-info btn-sm" onclick="marcarNaoAplica('${item}', ${testeId}, event); return false;">
                        <i class="fas fa-ban me-1"></i>N/A
                    </button>
                </div>
            </div>
        `;
    } else if (novoStatus === 'ok') {
        novosButtonsHtml = `
            <div class="status-badge">
                <span class="badge bg-success me-2">
                    <i class="fas fa-check me-1"></i>OK
                </span>
                <button class="btn btn-outline-secondary btn-sm" onclick="editarStatus('${item}', ${testeId}, '${novoStatus}')" title="Alterar status">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        `;
    } else if (novoStatus === 'nao_aplica') {
        novosButtonsHtml = `
            <div class="status-badge">
                <span class="badge bg-info me-2">
                    <i class="fas fa-ban me-1"></i>Não se aplica
                </span>
                <button class="btn btn-outline-secondary btn-sm" onclick="editarStatus('${item}', ${testeId}, '${novoStatus}')" title="Alterar status">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        `;
    } else if (novoStatus === 'problema') {
        novosButtonsHtml = `
            <div class="status-badge">
                <span class="badge bg-danger me-2">
                    <i class="fas fa-exclamation-triangle me-1"></i>Com Problema
                </span>
                <button class="btn btn-warning btn-sm" onclick="mostrarResolucao('${item}', ${testeId})">
                    <i class="fas fa-wrench me-1"></i>Resolver Problema
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="editarStatus('${item}', ${testeId}, '${novoStatus}')" title="Alterar status">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        `;
    }
    
    checklistActions.innerHTML = novosButtonsHtml;
    
    // Gerenciar histórico baseado no novo status
    if (data.equipamento_id) {
        gerenciarHistoricoStatus(item, testeId, novoStatus, data.equipamento_id);
    }
    
    // Destacar item alterado temporariamente
    itemElement.style.backgroundColor = '#d4edda';
    itemElement.style.border = '2px solid #28a745';
    
    setTimeout(() => {
        itemElement.style.backgroundColor = '';
        itemElement.style.border = '';
    }, 2000);
}

// Função para atualizar histórico dinamicamente
function atualizarHistorico(item, testeId, data) {
    const equipmentBody = document.querySelector(`#equipamento_${data.equipamento_id}_${testeId} .equipment-body`);
    if (!equipmentBody) return;
    
    // Verificar se já existe seção de histórico
    let historicoSection = equipmentBody.querySelector('.historico-section');
    
    // Se não existe, criar
    if (!historicoSection) {
        const historicoHtml = `
            <div class="historico-section mt-4">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-history me-2"></i>Histórico de Problemas
                </h6>
            </div>
        `;
        equipmentBody.insertAdjacentHTML('beforeend', historicoHtml);
        historicoSection = equipmentBody.querySelector('.historico-section');
    }
    
    // Procurar item existente no histórico
    let historicoItem = historicoSection.querySelector(`[data-item="${item}"]`);
    
    // Se não existe, criar
    if (!historicoItem) {
        const itemData = getItemData(item);
        const historicoItemHtml = `
            <div class="historico-item" data-item="${item}">
                <div class="d-flex align-items-center mb-2">
                    <i class="${itemData.icon} me-2 text-primary"></i>
                    <strong>${itemData.label}</strong>
                    <span class="badge bg-warning ms-2 status-badge">Pendente</span>
                </div>
            </div>
        `;
        historicoSection.insertAdjacentHTML('beforeend', historicoItemHtml);
        historicoItem = historicoSection.querySelector(`[data-item="${item}"]`);
    }
    
    // Atualizar conteúdo do problema
    if (data.problema) {
        // Remover problema existente se houver
        const problemaExistente = historicoItem.querySelector('.problema-info');
        if (problemaExistente) {
            problemaExistente.remove();
        }
        
        let problemaHtml = `
            <div class="problema-info mb-2">
                <strong class="text-danger">Problema:</strong>
                <p class="mb-1">${data.problema}</p>
        `;
        
        if (data.foto_problema_path) {
            // Garantir que a URL da foto comece com /storage/
            const fotoUrl = data.foto_problema_path.startsWith('/storage/') ? 
                data.foto_problema_path : 
                `/storage/${data.foto_problema_path.replace(/^\//, '')}`;
            
            problemaHtml += `
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="mostrarFoto('${fotoUrl}', 'Foto do Problema')">
                    <i class="fas fa-camera me-1"></i>Ver Foto do Problema
                </button>
            `;
        }
        
        problemaHtml += `</div>`;
        historicoItem.insertAdjacentHTML('beforeend', problemaHtml);
    }
    
    // Atualizar conteúdo da resolução
    if (data.resolucao) {
        // Remover resolução existente se houver
        const resolucaoExistente = historicoItem.querySelector('.resolucao-info');
        if (resolucaoExistente) {
            resolucaoExistente.remove();
        }
        
        let resolucaoHtml = `
            <div class="resolucao-info">
                <strong class="text-success">Resolução:</strong>
                <p class="mb-1">${data.resolucao}</p>
        `;
        
        if (data.foto_resolucao_path) {
            // Garantir que a URL da foto comece com /storage/
            const fotoUrl = data.foto_resolucao_path.startsWith('/storage/') ? 
                data.foto_resolucao_path : 
                `/storage/${data.foto_resolucao_path.replace(/^\//, '')}`;
            
            resolucaoHtml += `
                <button type="button" class="btn btn-sm btn-outline-success" onclick="mostrarFoto('${fotoUrl}', 'Foto da Resolução')">
                    <i class="fas fa-camera me-1"></i>Ver Foto da Resolução
                </button>
            `;
        }
        
        resolucaoHtml += `</div>`;
        historicoItem.insertAdjacentHTML('beforeend', resolucaoHtml);
        
        // Atualizar badge para resolvido
        const statusBadge = historicoItem.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.className = 'badge bg-success ms-2 status-badge';
            statusBadge.textContent = 'Resolvido';
        }
    } else if (data.problema && !data.resolucao) {
        // Apenas problema sem resolução - manter como pendente
        const statusBadge = historicoItem.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.className = 'badge bg-warning ms-2 status-badge';
            statusBadge.textContent = 'Pendente';
        }
    }
}

// Função para obter dados do item (ícone e label)
function getItemData(item) {
    const itemsData = {
        'ar_comprimido': { icon: 'fas fa-wind', label: 'Ar Comprimido OK' },
        'protecoes_eletricas': { icon: 'fas fa-bolt', label: 'Proteções Elétricas OK' },
        'protecoes_mecanicas': { icon: 'fas fa-cog', label: 'Proteções Mecânicas OK' },
        'chave_remoto': { icon: 'fas fa-key', label: 'Chave Remoto Testada' },
        'inspecionado': { icon: 'fas fa-search', label: 'Inspecionado' }
    };
    
    return itemsData[item] || { icon: 'fas fa-check', label: item };
}

// Função para gerenciar histórico baseado no status
function gerenciarHistoricoStatus(item, testeId, novoStatus, equipamentoId) {
    const equipmentBody = document.querySelector(`#equipamento_${equipamentoId}_${testeId} .equipment-body`);
    if (!equipmentBody) return;
    
    let historicoSection = equipmentBody.querySelector('.historico-section');
    let historicoItem = historicoSection?.querySelector(`[data-item="${item}"]`);
    
    if (novoStatus === 'pendente') {
        // Se mudou para pendente, remover completamente do histórico
        if (historicoItem) {
            historicoItem.remove();
            
            // Se não há mais itens no histórico, remover a seção inteira
            const remainingItems = historicoSection.querySelectorAll('[data-item]');
            if (remainingItems.length === 0) {
                historicoSection.remove();
            }
        }
    } else if (novoStatus === 'ok' || novoStatus === 'nao_aplica') {
        // Se mudou para OK ou N/A sem ter histórico de problema, não precisa fazer nada
        // O histórico só existe se teve problema antes
    }
    
    console.log(`Histórico gerenciado para ${item}: status ${novoStatus}`);
}

// Função para atualizar interface quando problema é salvo
function atualizarInterfaceProblema(item, testeId, data) {
    const itemElement = document.getElementById(`item_${item}_${testeId}`);
    if (!itemElement) return;
    
    const checklistInfo = itemElement.querySelector('.checklist-info');
    const checklistActions = itemElement.querySelector('.checklist-actions');
    
    // Remover ícones de status existentes
    const existingIcons = checklistInfo.querySelectorAll('.fas.fa-check-circle, .fas.fa-exclamation-triangle, .fas.fa-ban');
    existingIcons.forEach(icon => icon.remove());
    
    // Adicionar ícone de problema
    checklistInfo.insertAdjacentHTML('beforeend', '<i class="fas fa-exclamation-triangle text-warning ms-2"></i>');
    
    // Atualizar botões para mostrar que tem problema
    const novosButtonsHtml = `
        <div class="status-badge">
            <span class="badge bg-danger me-2">
                <i class="fas fa-exclamation-triangle me-1"></i>Com Problema
            </span>
            <button class="btn btn-warning btn-sm" onclick="mostrarResolucao('${item}', testeId)">
                <i class="fas fa-wrench me-1"></i>Resolver Problema
            </button>
            <button class="btn btn-outline-secondary btn-sm" onclick="editarStatus('${item}', testeId, 'problema')" title="Alterar status">
                <i class="fas fa-edit"></i>
            </button>
        </div>
    `;
    
    checklistActions.innerHTML = novosButtonsHtml;
    
    // Atualizar progresso do equipamento
    if (data.progress !== undefined && data.equipamento_id) {
        atualizarProgressoEquipamento(testeId, data.equipamento_id, data.progress);
    }
    
    // Atualizar histórico
    atualizarHistorico(item, testeId, data);
    
    // Destacar item
    itemElement.style.backgroundColor = '#fff3cd';
    itemElement.style.border = '2px solid #ffc107';
    
    setTimeout(() => {
        itemElement.style.backgroundColor = '';
        itemElement.style.border = '';
    }, 3000);
}

// Função para atualizar interface quando problema é resolvido
function atualizarInterfaceResolucao(item, testeId, data) {
    const itemElement = document.getElementById(`item_${item}_${testeId}`);
    if (!itemElement) return;
    
    const checklistInfo = itemElement.querySelector('.checklist-info');
    const checklistActions = itemElement.querySelector('.checklist-actions');
    
    // Remover ícones de status existentes
    const existingIcons = checklistInfo.querySelectorAll('.fas.fa-check-circle, .fas.fa-exclamation-triangle, .fas.fa-ban');
    existingIcons.forEach(icon => icon.remove());
    
    // Adicionar ícone de OK (problema foi resolvido = status OK)
    checklistInfo.insertAdjacentHTML('beforeend', '<i class="fas fa-check-circle text-success ms-2"></i>');
    
    // Atualizar botões para mostrar status OK com histórico de problema resolvido
    const novosButtonsHtml = `
        <div class="status-badge">
            <span class="badge bg-success me-2">
                <i class="fas fa-check me-1"></i>OK
            </span>
            <button class="btn btn-outline-secondary btn-sm" onclick="editarStatus('${item}', ${testeId}, 'ok')" title="Alterar status">
                <i class="fas fa-edit"></i>
            </button>
        </div>
    `;
    
    checklistActions.innerHTML = novosButtonsHtml;
    
    // Atualizar progresso do equipamento
    if (data.progress !== undefined && data.equipamento_id) {
        atualizarProgressoEquipamento(testeId, data.equipamento_id, data.progress);
    }
    
    // Atualizar histórico com resolução
    atualizarHistorico(item, testeId, data);
    
    // Destacar item com cor de sucesso
    itemElement.style.backgroundColor = '#d4edda';
    itemElement.style.border = '2px solid #28a745';
    
    setTimeout(() => {
        itemElement.style.backgroundColor = '';
        itemElement.style.border = '';
    }, 3000);
}

// Função para atualizar progresso do equipamento específico
function atualizarProgressoEquipamento(testeId, equipamentoId, novoProgresso) {
    // Primeiro tentar encontrar cabeçalho por atributo data-bs-target (compatibilidade)
    let equipamentoHeader = document.querySelector(`[data-bs-target="#equipamento_${equipamentoId}_${testeId}"]`);

    // Se não encontrar, tentar pelo id do card criado no template
    if (!equipamentoHeader) {
        equipamentoHeader = document.querySelector(`#equipamento_${equipamentoId}_${testeId} .equipment-header`);
    }

    // Por fim, tentar localizar o card inteiro e buscar a header internamente
    if (!equipamentoHeader) {
        const card = document.getElementById(`equipamento_${equipamentoId}_${testeId}`);
        equipamentoHeader = card ? card.querySelector('.equipment-header') : null;
    }

    if (equipamentoHeader) {
        const progressBar = equipamentoHeader.querySelector('.progress-bar');
        const badge = equipamentoHeader.querySelector('.badge');

        if (progressBar) {
            progressBar.style.width = `${novoProgresso}%`;
            progressBar.className = `progress-bar bg-${novoProgresso == 100 ? 'success' : (novoProgresso > 0 ? 'warning' : 'danger')}`;
        }

        if (badge) {
            badge.textContent = `${novoProgresso}%`;
            badge.className = `badge bg-${novoProgresso == 100 ? 'success' : 'warning'}`;
        }

        console.log(`Progresso do equipamento ${equipamentoId} atualizado para ${novoProgresso}%`);
    } else {
        console.warn(`Header do equipamento ${equipamentoId} não encontrado`);
    }
}

// Função para atualizar progresso geral da parada
function atualizarProgressoGeral() {
    fetch(`{{ route('paradas.progresso', $parada) }}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar percentual geral no header (mantém compatibilidade) e contagens
            const percentualGeral = document.getElementById('parada_percentual_geral');
            if (percentualGeral && data.percentual !== undefined) {
                percentualGeral.textContent = `${data.percentual}%`;
            }

            const heroProgressBar = document.querySelector('.summary-progress .progress-bar');
            if (heroProgressBar && data.percentual !== undefined) {
                heroProgressBar.style.width = `${data.percentual}%`;
            }

            // Atualizar texto com contagens (resolvidos / pendentes)
            const progressInfo = document.getElementById('parada_progress_info');
            if (progressInfo && data.total_testes !== undefined) {
                progressInfo.textContent = `${data.testes_ok} resolvido(s) • ${data.testes_pendentes} pendente(s)`;
            }
            
            // Atualizar percentuais das áreas
            if (data.areas && Array.isArray(data.areas)) {
                data.areas.forEach(area => {
                    // Atualizar badge percentual (se existir)
                    const areaBadgePercent = document.querySelector(`.area-progress-badge[data-area-id="${area.id}"] .area-percent`);
                    if (areaBadgePercent && area.percentual !== undefined) {
                        areaBadgePercent.textContent = `${area.percentual}%`;
                    }

                    // Atualizar badge de contagem (novo formato X/Y concluídos)
                    const areaBadgeCount = document.querySelector(`.area-progress-badge[data-area-id="${area.id}"]`);
                    if (areaBadgeCount) {
                        const completed = area.testes_ok !== undefined ? area.testes_ok : (area.testes_ok_count || 0);
                        const total = area.total_testes !== undefined ? area.total_testes : (area.total || 0);
                        const percentualArea = area.percentual !== undefined ? area.percentual : 0;
                        areaBadgeCount.innerHTML = `<span class="area-completed-count">${completed}</span>/<span class="area-total-count">${total}</span> concluídos • <span class="area-percent">${percentualArea}%</span>`;
                    }
                });
            }
            
            console.log('Progresso atualizado:', data.percentual + '%');
        }
    })
    .catch(error => {
        console.error('Erro ao atualizar progresso:', error);
    });
}

// Atualiza o progresso geral usando diretamente os dados já retornados pela chamada de alteração de status,
// evitando nova requisição quando possível (atualização imediata do DOM).
function atualizarProgressoGeralComData(data) {
    if (!data) return atualizarProgressoGeral();

    const percentualGeral = document.getElementById('parada_percentual_geral');
    if (percentualGeral && data.percentual !== undefined) {
        percentualGeral.textContent = `${data.percentual}%`;
    }

    const heroProgressBar = document.querySelector('.summary-progress .progress-bar');
    if (heroProgressBar && data.percentual !== undefined) {
        heroProgressBar.style.width = `${data.percentual}%`;
    }

    const progressInfo = document.getElementById('parada_progress_info');
    if (progressInfo && data.testes_ok !== undefined && data.testes_pendentes !== undefined) {
        progressInfo.textContent = `${data.testes_ok} resolvido(s) • ${data.testes_pendentes} pendente(s)`;
    }

    if (data.areas && Array.isArray(data.areas)) {
        data.areas.forEach(area => {
            const areaBadgePercent = document.querySelector(`.area-progress-badge[data-area-id="${area.id}"] .area-percent`);
            if (areaBadgePercent && area.percentual !== undefined) {
                areaBadgePercent.textContent = `${area.percentual}%`;
            }

            const areaBadgeCount = document.querySelector(`.area-progress-badge[data-area-id="${area.id}"]`);
            if (areaBadgeCount) {
                const completed = area.testes_ok !== undefined ? area.testes_ok : (area.testes_ok_count || 0);
                const total = area.total_testes !== undefined ? area.total_testes : (area.total || 0);
                const percentualArea = area.percentual !== undefined ? area.percentual : 0;
                areaBadgeCount.innerHTML = `<span class="area-completed-count">${completed}</span>/<span class="area-total-count">${total}</span> concluídos • <span class="area-percent">${percentualArea}%</span>`;
            }
        });
    }

    console.log('Progresso atualizado (via payload):', data.percentual !== undefined ? data.percentual + '%' : 'n/a');
}

// Variável global para verificar se parada está finalizada
const paradaFinalizada = @json($paradaFinalizada);

// Função para verificar se operações estão bloqueadas
function verificarOperacaoPermitida(operacao) {
    if (paradaFinalizada) {
        alert('Não é possível realizar esta operação. A parada já foi finalizada.');
        return false;
    }
    return true;
}

// Função para marcar item como OK
function marcarOK(item, testeId, event) {
    if (!verificarOperacaoPermitida('marcar como OK')) return;
    
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    fetch(`/testes/${testeId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            item: item,
            status: 'ok'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar progresso do equipamento
            if (data.progress !== undefined && data.equipamento_id) {
                atualizarProgressoEquipamento(testeId, data.equipamento_id, data.progress);
            }
            
            // Atualizar interface do item dinamicamente
            atualizarInterfaceItem(item, testeId, 'ok', data);
            
            // Atualizar progresso geral usando os dados retornados
            atualizarProgressoGeralComData(data);
        } else {
            alert('Erro ao atualizar status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
    });
    
    return false;
}

// Função para marcar item como "Não se aplica"
function marcarNaoAplica(item, testeId, event) {
    if (!verificarOperacaoPermitida('marcar como N/A')) return;
    
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    if (confirm('Tem certeza que deseja marcar este item como "Não se aplica"?')) {
        fetch(`/testes/${testeId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                item: item,
                status: 'nao_aplica'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Atualizar progresso do equipamento
                if (data.progress !== undefined && data.equipamento_id) {
                    atualizarProgressoEquipamento(testeId, data.equipamento_id, data.progress);
                }
                
                // Atualizar interface do item dinamicamente
                atualizarInterfaceItem(item, testeId, 'nao_aplica', data);
                
                // Atualizar progresso geral usando os dados retornados
                atualizarProgressoGeralComData(data);
            } else {
                alert('Erro ao atualizar status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao conectar com o servidor');
        });
    }
    
    return false;
}

// Função para mostrar formulário de problema
function reportarProblema(item, testeId, event) {
    if (!verificarOperacaoPermitida('reportar problema')) return;
    
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    // Primeiro atualizar o status para 'problema'
    fetch(`/testes/${testeId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            item: item,
            status: 'problema'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Após atualizar o status, mostrar o formulário
            document.getElementById(`problema_form_${item}_${testeId}`).style.display = 'block';
        } else {
            alert('Erro ao atualizar status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
    });
    
    return false;
}


// Função para editar status de um item
function editarStatus(item, testeId, statusAtual) {
    if (!verificarOperacaoPermitida('editar status')) return;
    
    // Verificar se o Bootstrap está carregado
    if (typeof bootstrap === 'undefined') {
        alert('Bootstrap não está carregado. Por favor, recarregue a página.');
        return;
    }
    
    const opcoes = {
        'pendente': 'Pendente',
        'ok': 'OK',
        'problema': 'Problema',
        'nao_aplica': 'Não se aplica'
    };
    
    let opcoesHtml = '';
    for (let [valor, label] of Object.entries(opcoes)) {
        const selected = valor === statusAtual ? 'selected' : '';
        opcoesHtml += `<option value="${valor}" ${selected}>${label}</option>`;
    }
    
    // Obter o nome do item
    const itemElement = document.querySelector('#item_' + item + '_' + testeId + ' .fw-bold');
    const itemNome = itemElement ? itemElement.textContent : 'Item não encontrado';
    
    const modal = '<div class="modal fade" id="modalEditarStatus" tabindex="-1">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<h5 class="modal-title">Alterar Status do Item</h5>' +
        '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>' +
        '</div>' +
        '<div class="modal-body">' +
        '<p><strong>Item:</strong> ' + itemNome + '</p>' +
        '<div class="mb-3">' +
        '<label class="form-label">Novo Status:</label>' +
        '<select class="form-select" id="novoStatus">' +
        opcoesHtml +
        '</select>' +
        '</div>' +
        '<div class="alert alert-warning">' +
        '<i class="fas fa-exclamation-triangle me-2"></i>' +
        '<strong>Atenção:</strong> Alterar o status pode afetar dados já salvos (problemas, resoluções, fotos).' +
        '</div>' +
        '</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>' +
        '<button type="button" class="btn btn-primary" onclick="confirmarAlteracaoStatus(\'' + item + '\', ' + testeId + ', \'' + statusAtual + '\')">' +
        'Alterar Status' +
        '</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
    
    // Remove modal existente se houver
    const modalExistente = document.getElementById('modalEditarStatus');
    if (modalExistente) {
        modalExistente.remove();
    }
    
    // Adiciona o modal ao body
    document.body.insertAdjacentHTML('beforeend', modal);
    
    // Mostra o modal
    try {
        const modalElement = document.getElementById('modalEditarStatus');
        console.log('Modal element:', modalElement);
        
        const modalBootstrap = new bootstrap.Modal(modalElement);
        console.log('Modal instance criada:', modalBootstrap);
        
        modalBootstrap.show();
        console.log('Modal.show() chamado');
    } catch (error) {
        console.error('Erro ao mostrar modal:', error);
        alert('Erro ao abrir modal: ' + error.message);
    }
    
    // Remove o modal do DOM após fechar
    document.getElementById('modalEditarStatus').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

// Função para confirmar alteração de status
function confirmarAlteracaoStatus(item, testeId, statusAnterior) {
    const novoStatus = document.getElementById('novoStatus').value;
    
    if (novoStatus === statusAnterior) {
        bootstrap.Modal.getInstance(document.getElementById('modalEditarStatus')).hide();
        return;
    }
    
    // Verificar se está mudando de 'problema' para outro status
    if (statusAnterior === 'problema' && novoStatus !== 'problema') {
        if (!confirm('Alterar o status de "Problema" pode remover dados salvos (descrição e fotos do problema). Deseja continuar?')) {
            return;
        }
    }
    
    // Se o novo status for 'problema', mostrar formulário
    if (novoStatus === 'problema') {
        bootstrap.Modal.getInstance(document.getElementById('modalEditarStatus')).hide();
        setTimeout(() => {
            reportarProblema(item, testeId);
        }, 500);
        return;
    }
    
    // Para outros status, fazer a alteração diretamente
    fetch(`/testes/${testeId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            item: item,
            status: novoStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalEditarStatus')).hide();
            
            // Atualizar interface dinamicamente
            setTimeout(() => {
                // Atualizar progresso do equipamento
                if (data.progress !== undefined && data.equipamento_id) {
                    atualizarProgressoEquipamento(testeId, data.equipamento_id, data.progress);
                }
                
                atualizarInterfaceItem(item, testeId, novoStatus, data);
                
                // Atualizar progresso geral usando os dados retornados
                atualizarProgressoGeralComData(data);
                
                // Mostrar feedback visual elegante
                showToast('Status atualizado com sucesso!', 'success');
            }, 300);
        } else {
            showToast('Erro ao alterar status: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao conectar com o servidor', 'error');
    });
}

// Função para cancelar problema
function cancelarProblema(item, testeId) {
    document.getElementById(`problema_form_${item}_${testeId}`).style.display = 'none';
}

// Função para salvar problema
function salvarProblema(item, testeId) {
    if (!verificarOperacaoPermitida('salvar problema')) return;
    
    const form = document.getElementById(`form_problema_${item}_${testeId}`);
    const formData = new FormData(form);
    formData.append('item', item);

    fetch(`/testes/${testeId}/problema`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Esconder formulário de problema
            document.getElementById(`problema_form_${item}_${testeId}`).style.display = 'none';
            
            // Atualizar interface para mostrar que tem problema
            atualizarInterfaceProblema(item, testeId, data);
            
            // Atualizar progresso geral usando os dados retornados
            atualizarProgressoGeralComData(data);
            
            alert('Problema salvo com sucesso!');
        } else {
            alert('Erro ao salvar problema: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
    });
}

// Função para mostrar formulário de resolução
function mostrarResolucao(item, testeId) {
    if (!verificarOperacaoPermitida('mostrar resolução')) return;
    
    document.getElementById(`resolucao_form_${item}_${testeId}`).style.display = 'block';
}

// Função para cancelar resolução
function cancelarResolucao(item, testeId) {
    document.getElementById(`resolucao_form_${item}_${testeId}`).style.display = 'none';
}

// Função para salvar resolução
function salvarResolucao(item, testeId) {
    if (!verificarOperacaoPermitida('salvar resolução')) return;
    
    const form = document.getElementById(`form_resolucao_${item}_${testeId}`);
    // Validação no cliente: tamanho máximo 2MB e tipos permitidos (mensagem inline)
    const fileInput = form.querySelector('input[type="file"]');
    const errorEl = form.querySelector('.resolucao-error');
    if (errorEl) { errorEl.style.display = 'none'; errorEl.textContent = ''; }
    if (fileInput && fileInput.files && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (file.size > maxSize) {
            if (errorEl) {
                errorEl.textContent = 'O arquivo é muito grande. Tamanho máximo permitido: 2MB.';
                errorEl.style.display = 'block';
            } else {
                alert('O arquivo é muito grande. Tamanho máximo permitido: 2MB.');
            }
            return;
        }
        if (!allowedTypes.includes(file.type)) {
            if (errorEl) {
                errorEl.textContent = 'Tipo de arquivo inválido. Apenas imagens (jpg, jpeg, png, gif) são permitidas.';
                errorEl.style.display = 'block';
            } else {
                alert('Tipo de arquivo inválido. Apenas imagens (jpg, jpeg, png, gif) são permitidas.');
            }
            return;
        }
    }

    const formData = new FormData(form);
    formData.append('item', item);

    fetch(`/testes/${testeId}/resolver-problema`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Esconder formulário de resolução
            document.getElementById(`resolucao_form_${item}_${testeId}`).style.display = 'none';
            
            // Atualizar interface para mostrar que foi resolvido
            atualizarInterfaceResolucao(item, testeId, data);
            
            // Atualizar progresso geral usando os dados retornados
            atualizarProgressoGeralComData(data);
            
            alert('Problema resolvido com sucesso!');
        } else {
            alert('Erro ao resolver problema: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
    });
}

// Função para mostrar foto no modal
function mostrarFoto(url, titulo) {
    document.getElementById('fotoModalTitle').textContent = titulo;
    document.getElementById('fotoModalImage').src = url;
    new bootstrap.Modal(document.getElementById('fotoModal')).show();
}

/* ===== FUNÇÃO DE TOAST (NOVA) ===== */
function showToast(message, type = 'success') {
    const toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) return;
    
    // Ícone baseado no tipo
    const icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-times-circle' : 'fa-info-circle');
    const colorClass = type === 'success' ? 'text-success' : (type === 'error' ? 'text-danger' : 'text-info');
    const title = type === 'success' ? 'Sucesso' : (type === 'error' ? 'Erro' : 'Informação');
    
    // Criar elemento do toast
    const toastHtml = `
        <div class="toast ${type === 'success' ? 'toast-success' : (type === 'error' ? 'toast-error' : '')}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas ${icon} ${colorClass} me-2"></i>
                <strong class="me-auto">${title}</strong>
                <small class="text-muted">agora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    // Inserir no container
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Inicializar e mostrar
    const toastEl = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
    toast.show();
    
    // Remover do DOM após fechar
    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
    });
}

// Função para atualizar histórico após salvar
function atualizarHistoricoAposSalvar(item, testeId, data) {
    console.log('Atualizando histórico após salvar:', { item, testeId, data });
    
    // Se existe foto_problema_path na resposta, adicionar botão imediatamente
    if (data.foto_problema_path) {
        // Buscar ou criar seção de histórico
        let historicoSection = document.querySelector('.historico-section');
        if (!historicoSection) {
            // Criar seção de histórico se não existir
            const equipmentCard = document.querySelector('.equipment-card');
            if (equipmentCard) {
                const historicoHtml = `
                    <div class="historico-section mt-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-history me-2"></i>Histórico de Problemas
                        </h6>
                        <div id="historico-container"></div>
                    </div>
                `;
                equipmentCard.insertAdjacentHTML('beforeend', historicoHtml);
                historicoSection = equipmentCard.querySelector('.historico-section');
            }
        }
        
        if (historicoSection) {
            const container = historicoSection.querySelector('#historico-container') || historicoSection;
            
            // Procurar item existente ou criar novo
            let itemHistorico = document.getElementById(`historico-${item}-${testeId}`);
            if (!itemHistorico) {
                const itemHtml = `
                    <div class="historico-item p-3 mb-3 border rounded bg-light" id="historico-${item}-${testeId}">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-wind me-2 text-primary"></i>
                            <strong>${item.replace('_', ' ')}</strong>
                            <span class="badge bg-warning ms-2">Pendente</span>
                        </div>
                        <div class="problema-info mb-2">
                            <strong class="text-danger">Problema:</strong>
                            <p class="mb-1" id="problema-text-${item}-${testeId}">Carregando...</p>
                            <div id="foto-container-${item}-${testeId}"></div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHtml);
                itemHistorico = document.getElementById(`historico-${item}-${testeId}`);
            }
            
            // Adicionar botão de foto imediatamente
            const fotoContainer = itemHistorico.querySelector(`#foto-container-${item}-${testeId}`);
            if (fotoContainer && data.foto_problema_path) {
                const fotoUrl = data.foto_problema_path.startsWith('/storage/') ? 
                data.foto_problema_path : 
                `/storage/${data.foto_problema_path.replace(/^\//, '')}`;
                fotoContainer.innerHTML = `
                    <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="mostrarFoto('${fotoUrl}', 'Foto do Problema')">
                        <i class="fas fa-camera me-1"></i>Ver Foto do Problema
                    </button>
                    <small class="text-success d-block mt-1">✓ Foto salva: ${data.foto_problema_path}</small>
                `;
                console.log('Botão de foto adicionado imediatamente:', fotoUrl);
            }
        }
    }
    
    // Similar para foto de resolução
    if (data.foto_resolucao_path) {
        console.log('Adicionando foto de resolução:', data.foto_resolucao_path);
        // Lógica similar para foto de resolução...
    }
}
</script>
    </div>
@endsection
