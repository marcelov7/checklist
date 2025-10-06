

<?php $__env->startSection('title', 'Relatório Detalhado - ' . $parada->nome); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Estilos para impressão */
@media print {
    .sidebar { display: none !important; }
    .col-md-10.main-content { width: 100% !important; max-width: 100% !important; flex: 0 0 100% !important; padding: 0 !important; margin: 0 !important; }
    .row { margin: 0 !important; }
    .d-print-none { display: none !important; }
    .card { page-break-inside: avoid; }
    .equipment-item { page-break-inside: avoid; }
    .checklist-item { page-break-inside: avoid; }
}

/* ===== RESPONSIVIDADE MOBILE ===== */
@media (max-width: 767.98px) {
    .card-header h4 { font-size: 1.1rem !important; line-height: 1.3; }
    .card-header small { font-size: 0.8rem; opacity: 0.9; }
    .badge { font-size: 0.7rem !important; padding: 0.25rem 0.5rem; }
    .fs-7 { font-size: 0.7rem !important; }
    .btn-sm { padding: 0.375rem 0.5rem; font-size: 0.8rem; }
    .container-fluid { padding-left: 0.5rem; padding-right: 0.5rem; }
    .card { margin-bottom: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .card-header h5 { font-size: 1rem !important; line-height: 1.2; }
    .equipment-item { padding: 0.75rem !important; }
    .checklist-item { padding: 0.5rem !important; margin-bottom: 0.5rem; }
    .table { font-size: 0.8rem; }
    .table th, .table td { padding: 0.5rem 0.25rem; vertical-align: middle; }
    .progress { height: 6px; margin-bottom: 0.5rem; }
    .text-muted { font-size: 0.8rem; }
    .breadcrumb { font-size: 0.75rem; margin-bottom: 0; padding: 0.5rem 0; }
    .row { margin-left: -0.25rem; margin-right: -0.25rem; }
    .row > [class*="col-"] { padding-left: 0.25rem; padding-right: 0.25rem; }
    .gap-3 { gap: 0.5rem !important; }
    .gap-2 { gap: 0.375rem !important; }
}

@media (max-width: 575.98px) {
    .container-fluid { padding-left: 0.25rem; padding-right: 0.25rem; }
    .card { margin-bottom: 0.75rem; }
    .card-header { padding: 0.75rem; }
    .card-body { padding: 0.75rem; }
    .card-header h4 { font-size: 1rem !important; }
    .card-header h5 { font-size: 0.9rem !important; }
    .badge { font-size: 0.65rem !important; padding: 0.2rem 0.4rem; }
    .btn-sm { padding: 0.25rem 0.4rem; font-size: 0.75rem; }
    .table { font-size: 0.75rem; }
    .table th, .table td { padding: 0.375rem 0.2rem; }
    .equipment-item { padding: 0.5rem !important; }
    .checklist-item { padding: 0.375rem !important; }
    small, .small { font-size: 0.7rem; }
}

/* ===== ESTILOS CUSTOMIZADOS ===== */
.equipment-item {
    transition: all 0.2s ease;
    border-left: 4px solid transparent;
}
.equipment-item:hover {
    background-color: #f8f9fa;
    border-left-color: #007bff;
}
.equipment-item.completed { border-left-color: #28a745; }
.equipment-item.with-problems { border-left-color: #dc3545; }
.equipment-item.pending { border-left-color: #ffc107; }

.checklist-item {
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.2s ease;
}
.checklist-item:hover { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.checklist-item.status-ok { border-left: 4px solid #28a745; }
.checklist-item.status-problema { border-left: 4px solid #dc3545; }
.checklist-item.status-nao-aplica { border-left: 4px solid #6c757d; }
.checklist-item.status-pendente { border-left: 4px solid #ffc107; }

.area-progress {
    height: 8px;
    border-radius: 4px;
    background-color: #e9ecef;
}
.area-progress .progress-bar {
    border-radius: 4px;
}

.status-badge.bg-success { background-color: #28a745 !important; }
.status-badge.bg-danger { background-color: #dc3545 !important; }
.status-badge.bg-warning { background-color: #ffc107 !important; color: #000; }
.status-badge.bg-secondary { background-color: #6c757d !important; }

.teste-details { font-size: 0.9rem; line-height: 1.4; }
.teste-meta { font-size: 0.8rem; color: #6c757d; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php
        // Buscar todas as áreas com seus equipamentos e testes para esta parada
        $areas = \App\Models\Area::whereHas('equipamentos.testes', function($query) use ($parada) {
            $query->where('parada_id', $parada->id);
        })->with(['equipamentos' => function($query) use ($parada) {
            $query->whereHas('testes', function($testQuery) use ($parada) {
                $testQuery->where('parada_id', $parada->id);
            })->with(['testes' => function($testQuery) use ($parada) {
                $testQuery->where('parada_id', $parada->id)->orderBy('updated_at', 'desc');
            }]);
        }])->get();
        
        $percentualPorArea = $parada->getPercentualPorArea();
        $problemasIdentificados = $parada->testes()->where('status', 'problema')->with(['equipamento.area'])->get();
    ?>

    <!-- Cabeçalho do Relatório -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="w-100 w-md-auto">
                        <h4 class="mb-0 fs-5 fs-md-4">
                            <i class="fas fa-clipboard-check me-2"></i>Relatório Detalhado de Checklist
                        </h4>
                        <small class="d-block d-md-inline"><?php echo e($parada->nome); ?> <span class="d-none d-md-inline">|</span><br class="d-md-none"> Macro: <?php echo e($parada->macro); ?></small>
                    </div>
                    <div class="d-flex flex-column flex-md-row gap-2 gap-md-3 w-100 w-md-auto d-print-none">
                        <button onclick="window.print()" class="btn btn-success btn-sm">
                            <i class="fas fa-print me-1"></i><span class="d-none d-sm-inline">Imprimir Relatório</span><span class="d-inline d-sm-none">Imprimir</span>
                        </button>
                        <a href="<?php echo e(route('paradas.show', $parada)); ?>" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Voltar ao Checklist</span><span class="d-inline d-sm-none">Voltar</span>
                        </a>
                    </div>
                </div>
                <div class="card-body p-2 p-md-3 d-print-none">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('paradas.historico')); ?>">Paradas</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('paradas.show', $parada)); ?>"><?php echo e(Str::limit($parada->nome, 20)); ?></a></li>
                            <li class="breadcrumb-item active">Relatório Detalhado</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Cabeçalho para impressão -->
    <div class="d-none d-print-block text-center mb-4">
        <h2>RELATÓRIO DETALHADO DE CHECKLIST DE PARADA</h2>
        <h4><?php echo e($parada->nome); ?> - <?php echo e($parada->macro); ?></h4>
        <p class="mb-0">Relatório gerado em: <?php echo e(now()->format('d/m/Y H:i:s')); ?></p>
        <hr>
    </div>

    <!-- Informações Gerais da Parada -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informações da Parada</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th class="text-muted" style="width: 30%;">Macro:</th>
                                    <td><strong><?php echo e($parada->macro); ?></strong></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Nome:</th>
                                    <td><?php echo e($parada->nome); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tipo:</th>
                                    <td>
                                        <span class="badge 
                                            <?php if($parada->tipo === 'preventiva'): ?> bg-warning text-dark
                                            <?php elseif($parada->tipo === 'corretiva'): ?> bg-danger
                                            <?php else: ?> bg-dark
                                            <?php endif; ?>
                                        "><?php echo e(ucfirst($parada->tipo)); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Status:</th>
                                    <td>
                                        <?php switch($parada->status):
                                            case ('em_andamento'): ?>
                                                <span class="badge bg-primary">Em Andamento</span>
                                                <?php break; ?>
                                            <?php case ('concluida'): ?>
                                                <span class="badge bg-success">Concluída</span>
                                                <?php break; ?>
                                            <?php case ('cancelada'): ?>
                                                <span class="badge bg-danger">Cancelada</span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge bg-secondary"><?php echo e(ucfirst(str_replace('_', ' ', $parada->status))); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th class="text-muted" style="width: 35%;">Data Início:</th>
                                    <td><?php echo e($parada->data_inicio->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <?php if($parada->data_fim): ?>
                                <tr>
                                    <th class="text-muted">Data Fim:</th>
                                    <td><?php echo e($parada->data_fim->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th class="text-muted">Progresso Geral:</th>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 20px;">
                                                <div class="progress-bar 
                                                    <?php if($resumo['percentual_conclusao'] >= 100): ?> bg-success
                                                    <?php elseif($resumo['percentual_conclusao'] >= 75): ?> bg-info
                                                    <?php elseif($resumo['percentual_conclusao'] >= 50): ?> bg-warning
                                                    <?php else: ?> bg-danger
                                                    <?php endif; ?>
                                                " style="width: <?php echo e($resumo['percentual_conclusao']); ?>%">
                                                    <?php echo e($resumo['percentual_conclusao']); ?>%
                                                </div>
                                            </div>
                                            <span class="badge bg-primary"><?php echo e($resumo['percentual_conclusao']); ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Equipe Responsável:</th>
                                    <td><?php echo e($parada->equipe_responsavel ?? 'Não informada'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php if($parada->descricao): ?>
                    <div class="mt-3 pt-3 border-top">
                        <h6 class="text-muted">Descrição:</h6>
                        <p class="mb-0"><?php echo e($parada->descricao); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumo Estatístico -->
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-primary mb-2">
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                    <h3 class="text-primary mb-1"><?php echo e($resumo['total_equipamentos'] ?? 0); ?></h3>
                    <p class="mb-0 small text-muted">Total de Equipamentos</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h3 class="text-success mb-1"><?php echo e($resumo['testes_ok'] ?? 0); ?></h3>
                    <p class="mb-0 small text-muted">Testes OK</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-danger mb-2">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <h3 class="text-danger mb-1"><?php echo e($resumo['testes_problema'] ?? 0); ?></h3>
                    <p class="mb-0 small text-muted">Com Problemas</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-warning mb-2">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h3 class="text-warning mb-1"><?php echo e($resumo['testes_pendentes'] ?? 0); ?></h3>
                    <p class="mb-0 small text-muted">Pendentes</p>
                </div>
            </div>
        </div>
    </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Macro:</th>
                            <td><strong><?php echo e($parada->macro); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Nome:</th>
                            <td><?php echo e($parada->nome); ?></td>
                        </tr>
                        <tr>
                            <th>Tipo:</th>
                            <td>
                                <span class="badge bg-info"><?php echo e(ucfirst($parada->tipo)); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <?php switch($parada->status):
                                    case ('em_andamento'): ?>
                                        <span class="badge bg-warning">Em Andamento</span>
                                        <?php break; ?>
                                    <?php case ('concluida'): ?>
                                        <span class="badge bg-success">Concluída</span>
                                        <?php break; ?>
                                    <?php case ('cancelada'): ?>
                                        <span class="badge bg-danger">Cancelada</span>
                                        <?php break; ?>
                                <?php endswitch; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Data Início:</th>
                            <td><?php echo e($parada->data_inicio->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <?php if($parada->data_fim): ?>
                        <tr>
                            <th>Data Fim:</th>
                            <td><?php echo e($parada->data_fim->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Duração Prevista:</th>
                            <td><?php echo e($parada->duracao_prevista_horas ? number_format($parada->duracao_prevista_horas, 2, ',', '.') : 'Não definida'); ?> horas</td>
                        </tr>
                        <?php if(isset($resumo['duracao_real'])): ?>
                        <tr>
                            <th>Duração Real:</th>
                            <td><?php echo e(number_format($resumo['duracao_real'], 2, ',', '.')); ?> horas</td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Equipe Responsável:</th>
                            <td><?php echo e($parada->equipe_responsavel ?? 'Não informada'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php if($parada->descricao): ?>
            <div class="mt-3">
                <h6>Descrição:</h6>
                <p class="text-muted"><?php echo e($parada->descricao); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Detalhamento Completo dos Testes por Área -->
    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $areaPercentual = $percentualPorArea->where('id', $area->id)->first();
            $areaEquipamentos = $area->equipamentos->where('testes.count', '>', 0);
        ?>
        
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i><?php echo e($area->nome); ?>

                            </h5>
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <small>Progresso:</small>
                                    <div class="progress bg-light" style="width: 120px; height: 20px;">
                                        <div class="progress-bar 
                                            <?php if(($areaPercentual->percentual ?? 0) >= 100): ?> bg-success
                                            <?php elseif(($areaPercentual->percentual ?? 0) >= 75): ?> bg-info
                                            <?php elseif(($areaPercentual->percentual ?? 0) >= 50): ?> bg-warning
                                            <?php else: ?> bg-danger
                                            <?php endif; ?>
                                        " style="width: <?php echo e($areaPercentual ? $areaPercentual->percentual : 0); ?>%">
                                            <small><?php echo e($areaPercentual ? $areaPercentual->percentual : 0); ?>%</small>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark fs-6">
                                    <?php echo e($areaEquipamentos->count()); ?> equipamento<?php echo e($areaEquipamentos->count() != 1 ? 's' : ''); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <?php $__currentLoopData = $area->equipamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $teste = $equipamento->testes->first();
                                if (!$teste) continue;
                                
                                $checklistData = $teste->checklist ? json_decode($teste->checklist, true) : [];
                                
                                // Calcular status do equipamento
                                $equipamentoStatus = 'completed';
                                if ($teste->status === 'problema') {
                                    $equipamentoStatus = 'with-problems';
                                } elseif ($teste->status === 'pendente') {
                                    $equipamentoStatus = 'pending';
                                }
                            ?>
                            
                            <div class="equipment-item <?php echo e($equipamentoStatus); ?> border-bottom p-4">
                                <!-- Header do Equipamento -->
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                                    <div class="equipment-info">
                                        <h6 class="mb-1 fw-bold">
                                            <i class="fas fa-cog me-2 text-primary"></i><?php echo e($equipamento->nome); ?>

                                        </h6>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <code class="bg-light px-2 py-1 rounded small"><?php echo e($equipamento->tag); ?></code>
                                            <?php switch($teste->status):
                                                case ('ok'): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Aprovado
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('problema'): ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Com Problema
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('nao_aplica'): ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-ban me-1"></i>Não se Aplica
                                                    </span>
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i>Pendente
                                                    </span>
                                            <?php endswitch; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="teste-meta text-end">
                                        <?php if($teste->testado_por): ?>
                                            <div class="mb-1">
                                                <i class="fas fa-user me-1 text-muted"></i>
                                                <small class="text-muted">Testado por: <strong><?php echo e($teste->testado_por); ?></strong></small>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <i class="fas fa-clock me-1 text-muted"></i>
                                            <small class="text-muted"><?php echo e($teste->updated_at->format('d/m/Y H:i')); ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Detalhes do Checklist -->
                                <?php if($checklistData && count($checklistData) > 0): ?>
                                    <div class="checklist-details">
                                        <h6 class="text-muted mb-3">
                                            <i class="fas fa-list-check me-2"></i>Detalhes do Checklist:
                                        </h6>
                                        
                                        <div class="row g-2">
                                            <?php $__currentLoopData = $checklistData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $statusClass = '';
                                                    $statusIcon = '';
                                                    $statusText = '';
                                                    
                                                    switch($item['status']) {
                                                        case 'ok':
                                                            $statusClass = 'status-ok';
                                                            $statusIcon = 'fas fa-check text-success';
                                                            $statusText = 'OK';
                                                            break;
                                                        case 'problema':
                                                            $statusClass = 'status-problema';
                                                            $statusIcon = 'fas fa-exclamation-triangle text-danger';
                                                            $statusText = 'Problema';
                                                            break;
                                                        case 'nao_aplica':
                                                            $statusClass = 'status-nao-aplica';
                                                            $statusIcon = 'fas fa-ban text-secondary';
                                                            $statusText = 'N/A';
                                                            break;
                                                        default:
                                                            $statusClass = 'status-pendente';
                                                            $statusIcon = 'fas fa-clock text-warning';
                                                            $statusText = 'Pendente';
                                                    }
                                                ?>
                                                
                                                <div class="col-12 col-lg-6">
                                                    <div class="checklist-item <?php echo e($statusClass); ?> p-3 h-100">
                                                        <div class="d-flex align-items-start gap-3">
                                                            <div class="flex-shrink-0">
                                                                <i class="<?php echo e($statusIcon); ?> fs-5"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                    <strong class="checklist-title"><?php echo e($key); ?></strong>
                                                                    <span class="badge status-badge 
                                                                        <?php if($item['status'] === 'ok'): ?> bg-success
                                                                        <?php elseif($item['status'] === 'problema'): ?> bg-danger
                                                                        <?php elseif($item['status'] === 'nao_aplica'): ?> bg-secondary
                                                                        <?php else: ?> bg-warning text-dark
                                                                        <?php endif; ?>
                                                                    "><?php echo e($statusText); ?></span>
                                                                </div>
                                                                
                                                                <?php if(isset($item['observacoes']) && $item['observacoes']): ?>
                                                                    <div class="teste-details">
                                                                        <strong class="text-muted">Observações:</strong>
                                                                        <p class="mb-0"><?php echo e($item['observacoes']); ?></p>
                                                                    </div>
                                                                <?php endif; ?>
                                                                
                                                                <?php if(isset($item['problema_descricao']) && $item['problema_descricao']): ?>
                                                                    <div class="teste-details mt-2">
                                                                        <strong class="text-danger">Problema Identificado:</strong>
                                                                        <p class="mb-1 text-danger"><?php echo e($item['problema_descricao']); ?></p>
                                                                        
                                                                        <?php if(isset($item['problema_resolucao']) && $item['problema_resolucao']): ?>
                                                                            <strong class="text-success">Resolução:</strong>
                                                                            <p class="mb-0 text-success"><?php echo e($item['problema_resolucao']); ?></p>
                                                                        <?php else: ?>
                                                                            <small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Aguardando resolução</small>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                
                                                                <?php if(isset($item['foto_problema']) && $item['foto_problema']): ?>
                                                                    <div class="mt-2">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-camera me-1"></i>Evidência fotográfica anexada
                                                                        </small>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Observações Gerais do Teste -->
                                <?php if($teste->observacoes): ?>
                                    <div class="mt-4 p-3 bg-light rounded">
                                        <h6 class="text-muted mb-2">
                                            <i class="fas fa-sticky-note me-2"></i>Observações Gerais:
                                        </h6>
                                        <p class="mb-0"><?php echo e($teste->observacoes); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Resumo de Problemas Identificados -->
    <?php if($problemasIdentificados->count() > 0): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Resumo de Problemas Identificados
                            <span class="badge bg-light text-danger ms-2"><?php echo e($problemasIdentificados->count()); ?></span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Área</th>
                                        <th>Equipamento</th>
                                        <th class="d-none d-md-table-cell">Tag</th>
                                        <th>Problema</th>
                                        <th class="d-none d-lg-table-cell">Testado Por</th>
                                        <th class="d-none d-lg-table-cell">Data/Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $problemasIdentificados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $problema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="fw-bold text-danger"><?php echo e($problema->equipamento->area->nome); ?></td>
                                            <td><?php echo e($problema->equipamento->nome); ?></td>
                                            <td class="d-none d-md-table-cell">
                                                <code class="small"><?php echo e($problema->equipamento->tag); ?></code>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;" title="<?php echo e($problema->problema_descricao); ?>">
                                                    <?php echo e($problema->problema_descricao); ?>

                                                </div>
                                            </td>
                                            <td class="d-none d-lg-table-cell small text-muted"><?php echo e($problema->testado_por ?? 'Não informado'); ?></td>
                                            <td class="d-none d-lg-table-cell small text-muted"><?php echo e($problema->updated_at->format('d/m H:i')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Resumo Executivo -->
    <div class="row mb-4 d-print-none">
        <div class="col-12">
            <div class="card shadow-sm no-break">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Resumo Executivo</h5>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cards de Estatísticas -->
    <div class="row mb-4 g-3 d-print-none">
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-primary mb-2">
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                    <h3 class="text-primary mb-1"><?php echo e($resumo['total_equipamentos'] ?? 0); ?></h3>
                    <p class="mb-0 small text-muted">Total de Equipamentos</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h3 class="text-success mb-1"><?php echo e($resumo['testes_ok'] ?? 0); ?></h3>
                    <p class="mb-0 small text-muted">Testes OK</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-danger mb-2">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <h3 class="text-danger mb-1"><?php echo e($resumo['testes_problema'] ?? 0); ?></h3>
                    <p class="mb-0 small text-muted">Problemas</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-warning mb-2">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h3 class="text-warning mb-1"><?php echo e($resumo['testes_pendentes'] ?? 0); ?></h3>
                    <p class="mb-0 small text-muted">Pendentes</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Progress Geral -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
            
            <!-- Layout otimizado para impressão -->
            <div class="print-summary d-none d-print-flex">
                <div class="metric">
                    <h3><?php echo e($resumo['total_equipamentos'] ?? 0); ?></h3>
                    <small>Total de Equipamentos</small>
                </div>
                <div class="metric">
                    <h3><?php echo e($resumo['testes_ok'] ?? 0); ?></h3>
                    <small>Testes OK</small>
                </div>
                <div class="metric">
                    <h3><?php echo e($resumo['testes_problema'] ?? 0); ?></h3>
                    <small>Problemas</small>
                </div>
                <div class="metric">
                    <h3><?php echo e($resumo['testes_pendentes'] ?? 0); ?></h3>
                    <small>Pendentes</small>
                </div>
            </div>
            
            <div class="mt-4">
                <h6><strong>Progresso Geral: <?php echo e($resumo['percentual_conclusao']); ?>%</strong></h6>
                <div class="progress mb-2" style="height: 25px;">
                    <div class="progress-bar 
                        <?php if($resumo['percentual_conclusao'] >= 100): ?> bg-success
                        <?php elseif($resumo['percentual_conclusao'] >= 75): ?> bg-info
                        <?php elseif($resumo['percentual_conclusao'] >= 50): ?> bg-warning
                        <?php else: ?> bg-danger
                        <?php endif; ?>" 
                        role="progressbar" 
                        style="width: <?php echo e($resumo['percentual_conclusao']); ?>%"
                        aria-valuenow="<?php echo e($resumo['percentual_conclusao']); ?>" 
                        aria-valuemin="0" 
                        aria-valuemax="100">
                        <?php echo e($resumo['percentual_conclusao']); ?>%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalhes por Área -->
    <?php
        $areas = \App\Models\Area::whereHas('equipamentos.testes', function($query) use ($parada) {
            $query->where('parada_id', $parada->id);
        })->with(['equipamentos' => function($query) use ($parada) {
            $query->whereHas('testes', function($testQuery) use ($parada) {
                $testQuery->where('parada_id', $parada->id);
            })->with(['testes' => function($testQuery) use ($parada) {
                $testQuery->where('parada_id', $parada->id);
            }]);
        }])->get();
        
        $percentualPorArea = $parada->getPercentualPorArea();
    ?>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-industry me-2"></i>Detalhamento por Área</h5>
                </div>
                <div class="card-body p-0">
            <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $areaPercentual = $percentualPorArea->where('id', $area->id)->first();
                ?>
                
                    <div class="area-item border-top border-3 border-info">
                        <div class="p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                                <h6 class="mb-0 fs-6 fs-md-5">
                                    <i class="fas fa-map-marker-alt me-2 text-info"></i><?php echo e($area->nome); ?>

                                </h6>
                                <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                                    <span class="badge bg-info fs-7 fs-md-6">
                                        <?php echo e($areaPercentual ? $areaPercentual->percentual : 0); ?>% Concluído
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Progress por Área -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Progresso da Área</small>
                                    <small class="fw-bold"><?php echo e($areaPercentual ? $areaPercentual->percentual : 0); ?>%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar 
                                        <?php if(($areaPercentual->percentual ?? 0) >= 100): ?> bg-success
                                        <?php elseif(($areaPercentual->percentual ?? 0) >= 75): ?> bg-info
                                        <?php elseif(($areaPercentual->percentual ?? 0) >= 50): ?> bg-warning
                                        <?php else: ?> bg-danger
                                        <?php endif; ?>" 
                                        role="progressbar" 
                                        style="width: <?php echo e($areaPercentual ? $areaPercentual->percentual : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                            <!-- Equipamentos da Área -->
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="d-none d-md-table-cell" style="width: 25%;">Equipamento</th>
                                            <th class="d-table-cell d-md-none">Equip./Tag</th>
                                            <th class="d-none d-md-table-cell" style="width: 10%;">Tag</th>
                                            <th style="width: 10%;">Status</th>
                                            <th class="d-none d-lg-table-cell" style="width: 15%;">Testado Por</th>
                                            <th class="d-none d-lg-table-cell" style="width: 15%;">Data/Hora</th>
                                            <th class="d-none d-md-table-cell" style="width: 25%;">Observações</th>
                                        </tr>
                                    </thead>
                            <tbody>
                                <?php $__currentLoopData = $area->equipamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $teste = $equipamento->testes->first();
                                    ?>
                                        <tr class="no-break">
                                            <td class="d-none d-md-table-cell"><strong><?php echo e($equipamento->nome); ?></strong></td>
                                            <td class="d-table-cell d-md-none">
                                                <div class="fw-bold"><?php echo e(Str::limit($equipamento->nome, 20)); ?></div>
                                                <small class="text-muted"><?php echo e($equipamento->tag); ?></small>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <code class="small"><?php echo e($equipamento->tag); ?></code>
                                            </td>
                                            <td>
                                            <?php if($teste): ?>
                                                <?php switch($teste->status):
                                                    case ('ok'): ?>
                                                        <span class="badge bg-success">OK</span>
                                                        <?php break; ?>
                                                    <?php case ('problema'): ?>
                                                        <span class="badge bg-danger">PROBLEMA</span>
                                                        <?php break; ?>
                                                    <?php case ('pendente'): ?>
                                                        <span class="badge bg-warning">PENDENTE</span>
                                                        <?php break; ?>
                                                <?php endswitch; ?>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">NÃO TESTADO</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-lg-table-cell" style="font-size: 8pt;">
                                            <?php echo e($teste->testado_por ?? '-'); ?>

                                        </td>
                                        <td class="d-none d-lg-table-cell" style="font-size: 8pt;">
                                            <?php if($teste && $teste->updated_at): ?>
                                                <?php echo e($teste->updated_at->format('d/m H:i')); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-md-table-cell" style="font-size: 8pt;">
                                            <?php if($teste): ?>
                                                <?php if($teste->problema_descricao): ?>
                                                    <strong>Problema:</strong> <?php echo e(Str::limit($teste->problema_descricao, 60)); ?>

                                                    <?php if($teste->observacoes): ?><br><?php endif; ?>
                                                <?php endif; ?>
                                                <?php if($teste->observacoes): ?>
                                                    <em>Obs:</em> <?php echo e(Str::limit($teste->observacoes, 60)); ?>

                                                <?php endif; ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Problemas Identificados -->
    <?php
        $problemasIdentificados = \App\Models\Teste::where('parada_id', $parada->id)
            ->where('status', 'problema')
            ->with('equipamento.area')
            ->get();
    ?>

    <?php if($problemasIdentificados->count() > 0): ?>
    <div class="card mb-4 problems-section">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-exclamation-triangle d-print-none"></i> Problemas Identificados (<?php echo e($problemasIdentificados->count()); ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Área</th>
                            <th style="width: 20%;">Equipamento</th>
                            <th style="width: 10%;">Tag</th>
                            <th style="width: 35%;">Descrição do Problema</th>
                            <th style="width: 12%;">Identificado Por</th>
                            <th style="width: 8%;">Data/Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $problemasIdentificados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $problema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="no-break">
                            <td><strong><?php echo e($problema->equipamento->area->nome); ?></strong></td>
                            <td><?php echo e($problema->equipamento->nome); ?></td>
                            <td><code style="font-size: 8pt;"><?php echo e($problema->equipamento->tag); ?></code></td>
                            <td style="font-size: 8pt;"><?php echo e($problema->problema_descricao); ?></td>
                            <td style="font-size: 8pt;"><?php echo e($problema->testado_por ?? 'Não informado'); ?></td>
                            <td style="font-size: 8pt;"><?php echo e($problema->updated_at->format('d/m H:i')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Seção de Assinaturas (apenas na impressão) -->
    <div class="d-none d-print-block page-break">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Validações e Assinaturas</h5>
            </div>
            <div class="card-body">
                <div class="row mt-5">
                    <div class="col-6">
                        <div style="border-bottom: 1pt solid #000; padding-bottom: 2pt; margin-bottom: 8pt;">
                            <strong>Responsável pela Execução:</strong>
                        </div>
                        <p style="font-size: 9pt;"><?php echo e($parada->equipe_responsavel ?? 'Não informado'); ?></p>
                        <br><br>
                        <div style="border-bottom: 1pt solid #000; width: 200pt; margin-bottom: 5pt;"></div>
                        <small>Assinatura e Data</small>
                    </div>
                    <div class="col-6">
                        <div style="border-bottom: 1pt solid #000; padding-bottom: 2pt; margin-bottom: 8pt;">
                            <strong>Supervisor Responsável:</strong>
                        </div>
                        <p style="font-size: 9pt;">_________________________</p>
                        <br><br>
                        <div style="border-bottom: 1pt solid #000; width: 200pt; margin-bottom: 5pt;"></div>
                        <small>Assinatura e Data</small>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-6">
                        <div style="border-bottom: 1pt solid #000; padding-bottom: 2pt; margin-bottom: 8pt;">
                            <strong>Engenheiro de Manutenção:</strong>
                        </div>
                        <p style="font-size: 9pt;">_________________________</p>
                        <br><br>
                        <div style="border-bottom: 1pt solid #000; width: 200pt; margin-bottom: 5pt;"></div>
                        <small>Assinatura e Data</small>
                    </div>
                    <div class="col-6">
                        <div style="border-bottom: 1pt solid #000; padding-bottom: 2pt; margin-bottom: 8pt;">
                            <strong>Gerente de Produção:</strong>
                        </div>
                        <p style="font-size: 9pt;">_________________________</p>
                        <br><br>
                        <div style="border-bottom: 1pt solid #000; width: 200pt; margin-bottom: 5pt;"></div>
                        <small>Assinatura e Data</small>
                    </div>
                </div>
                
                <div class="mt-4" style="border-top: 1pt solid #000; padding-top: 10pt;">
                    <h6>Observações Gerais:</h6>
                    <div style="border: 1pt solid #000; height: 80pt; padding: 8pt;">
                        <small style="color: #666;">Espaço para observações adicionais, comentários ou ressalvas sobre a execução da parada de manutenção.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações e Navegação -->
    <div class="card d-print-none mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row gap-2 justify-content-between align-items-center">
                <div class="d-flex flex-column flex-md-row gap-2">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print me-1"></i>Imprimir Relatório
                    </button>
                    <a href="<?php echo e(route('paradas.show', $parada)); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i>Voltar ao Checklist
                    </a>
                </div>
                <div class="text-center text-md-end">
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>Gerado em <?php echo e(now()->format('d/m/Y H:i:s')); ?>

                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé do Sistema -->
    <div class="card d-print-none">
        <div class="card-body text-center py-2">
            <small class="text-muted">
                Sistema de Checklist de Paradas - <?php echo e(config('app.name', 'Laravel')); ?>

            </small>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    /* Estilos para tela */
    .table th {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .print-header {
        display: none;
    }
    
    .page-break {
        page-break-before: always;
    }
    
    .no-break {
        page-break-inside: avoid;
    }

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
            border-radius: 8px !important;
        }
        
        .card-header {
            padding: 0.75rem !important;
            font-size: 0.95rem !important;
        }
        
        .card-body {
            padding: 0.75rem !important;
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
            padding: 0.2em 0.35em !important;
        }
        
        .progress {
            height: 1.25rem !important;
        }
        
        .btn {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
            margin-bottom: 0.5rem !important;
        }
        
        h1 {
            font-size: 1.5rem !important;
        }
        
        h2 {
            font-size: 1.3rem !important;
        }
        
        h5 {
            font-size: 1rem !important;
        }
        
        .d-flex.flex-column.flex-md-row {
            flex-direction: column !important;
        }
        
        .d-flex.flex-column.flex-md-row > * {
            margin-bottom: 0.5rem !important;
        }
        
        .text-end {
            text-align: left !important;
        }
        
        .ms-auto {
            margin-left: 0 !important;
        }
    }
    
    @media (max-width: 575.98px) {
        .main-content {
            margin-top: 140px !important;
            padding: 10px 5px !important;
        }
        
        .card-header h5 {
            font-size: 0.9rem !important;
        }
        
        .table th,
        .table td {
            padding: 0.375rem 0.125rem !important;
            font-size: 0.7rem !important;
        }
        
        .badge {
            font-size: 0.6rem !important;
        }
        
        .btn {
            font-size: 0.8rem !important;
            padding: 0.4rem 0.6rem !important;
        }
    }

    /* Estilos específicos para impressão */
    @media print {
        /* Configurações básicas de impressão */
        @page {
            margin: 15mm 10mm;
            size: A4;
        }
        
        body {
            font-size: 11pt;
            line-height: 1.3;
            color: #000 !important;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
        
        /* Estrutura específica do layout - ocultar na impressão */
        body > .container-fluid > .row {
            display: block !important;
        }
        
        body > .container-fluid > .row > .col-md-2.sidebar {
            display: none !important;
        }
        
        body > .container-fluid > .row > .col-md-10.main-content {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            padding: 0 !important;
        }
        
        /* Ocultar TODOS os elementos desnecessários na impressão */
        .d-print-none,
        .breadcrumb,
        nav,
        .sidebar,
        .navbar,
        .nav,
        .menu,
        .header:not(.print-header),
        .footer,
        .btn,
        button,
        .offcanvas,
        .collapse,
        .dropdown,
        .modal,
        .alert,
        .pagination,
        .nav-tabs,
        .nav-pills,
        .main-sidebar,
        .control-sidebar,
        .navbar-nav,
        .main-header {
            display: none !important;
        }
        
        /* Garantir que apenas o container do relatório seja mostrado */
        body {
            background: white !important;
        }
        
        /* Resetar estrutura do Bootstrap para impressão */
        .container-fluid {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .row {
            margin: 0 !important;
        }
        
        .col-md-10 {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
        }
        
        /* Manter o layout da tela para impressão */
        .container-fluid {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* Cabeçalho para impressão */
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20pt;
            border-bottom: 2pt solid #000;
            padding-bottom: 10pt;
        }
        
        .print-header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
        }
        
        .print-header .company-info {
            font-size: 10pt;
            margin-top: 5pt;
        }
        
        /* Cards e containers - manter layout da tela */
        .card {
            border: 1px solid #dee2e6 !important;
            margin-bottom: 1.5rem !important;
            page-break-inside: avoid;
            background: white !important;
            box-shadow: none !important;
        }
        
        .card-header {
            background: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
            padding: 0.75rem 1.25rem !important;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .card-body {
            padding: 1.25rem !important;
        }
        
        /* Tabelas - manter aparência da tela */
        .table {
            font-size: 0.875rem !important;
            border-collapse: collapse !important;
            width: 100% !important;
            margin-bottom: 1rem !important;
        }
        
        .table th {
            background: #e9ecef !important;
            border: 1px solid #dee2e6 !important;
            padding: 0.5rem !important;
            font-weight: bold !important;
            text-align: left !important;
            font-size: 0.875rem !important;
        }
        
        .table td {
            border: 1px solid #dee2e6 !important;
            padding: 0.5rem !important;
            vertical-align: top !important;
            font-size: 0.875rem !important;
        }
        
        .table-borderless th,
        .table-borderless td {
            border: none !important;
            padding: 0.25rem 0.5rem !important;
        }
        
        /* Barras de progresso - manter aparência da tela */
        .progress {
            height: 1.5rem !important;
            background: #e9ecef !important;
            border: 1px solid #dee2e6 !important;
            overflow: visible !important;
        }
        
        .progress-bar {
            background: #28a745 !important;
            color: #000 !important;
            text-align: center !important;
            line-height: 1.5rem !important;
            font-size: 0.75rem !important;
            font-weight: bold !important;
        }
        
        .progress-bar.bg-danger {
            background: #dc3545 !important;
        }
        
        .progress-bar.bg-warning {
            background: #ffc107 !important;
        }
        
        .progress-bar.bg-info {
            background: #17a2b8 !important;
        }
        
        /* Badges - manter aparência da tela */
        .badge {
            border: 1px solid transparent !important;
            padding: 0.25em 0.4em !important;
            font-size: 0.75em !important;
            font-weight: bold !important;
            color: #000 !important;
        }
        
        .badge.bg-success {
            background: #d4edda !important;
        }
        
        .badge.bg-danger {
            background: #f8d7da !important;
        }
        
        .badge.bg-warning {
            background: #fff3cd !important;
        }
        
        .badge.bg-info, .badge.bg-primary {
            background: #d1ecf1 !important;
        }
        
        .badge.bg-secondary {
            background: #e2e3e5 !important;
        }
        
        /* Resumo executivo - manter layout da tela */
        .print-summary {
            display: flex;
            justify-content: space-around;
            text-align: center;
            margin: 1rem 0;
        }
        
        .print-summary .metric {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
            min-width: 120px;
            background: #f8f9fa;
        }
        
        .print-summary .metric h3 {
            font-size: 1.5rem;
            margin: 0;
            font-weight: bold;
        }
        
        .print-summary .metric small {
            font-size: 0.75rem;
            display: block;
            margin-top: 0.25rem;
        }
        
        /* Quebras de página estratégicas */
        .page-break {
            page-break-before: always !important;
        }
        
        .no-break {
            page-break-inside: avoid !important;
        }
        
        /* Seções específicas */
        .area-section {
            page-break-inside: avoid;
            margin-bottom: 20pt;
        }
        
        .area-section h6 {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 8pt;
            border-bottom: 1pt solid #000;
            padding-bottom: 3pt;
        }
        
        /* Problemas identificados */
        .problems-section {
            page-break-before: always;
        }
        
        .problems-section .card-header {
            background: #f8d7da !important;
        }
        
        /* Rodapé */
        .print-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            border-top: 1pt solid #000;
            padding-top: 5pt;
            background: white;
        }
        
        /* Ajustes de layout para melhor aproveitamento */
        .row {
            display: flex !important;
            flex-wrap: wrap !important;
        }
        
        .col-md-6 {
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        
        .col-md-3 {
            flex: 0 0 25% !important;
            max-width: 25% !important;
        }
        
        /* Evitar quebras desnecessárias */
        h1, h2, h3, h4, h5, h6 {
            page-break-after: avoid !important;
        }
        
        /* Espaçamentos - manter da tela */
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }
        
        .mt-4 {
            margin-top: 1.5rem !important;
        }
        
        .mb-3 {
            margin-bottom: 1rem !important;
        }
        
        .mt-3 {
            margin-top: 1rem !important;
        }
        
        /* Melhorias específicas para impressão */
        .d-print-flex {
            display: flex !important;
        }
        
        .d-print-block {
            display: block !important;
        }
        
        .text-justify {
            text-align: justify;
        }
        
        /* Manter layout da tela na impressão */
        .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
        
        /* Cabeçalho da empresa */
        .print-header h1 {
            letter-spacing: 0.05em;
        }
        
        /* Códigos - manter aparência da tela */
        code {
            background: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            padding: 0.125rem 0.25rem !important;
            font-weight: bold !important;
        }
        
        /* Destaque para informações importantes */
        .card-header h5 {
            letter-spacing: 0.025em;
        }
        
        /* Assinatura */
        .signature-section {
            margin-top: 2rem;
        }
        
        /* Remover rodapé fixo para não interferir no layout */
        .print-footer {
            position: static !important;
            border: none !important;
            text-align: center;
            font-size: 0.75rem;
            margin-top: 2rem;
            background: transparent !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\paradas\relatorio_backup.blade.php ENDPATH**/ ?>