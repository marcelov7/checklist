<?php $__env->startSection('title', 'Paradas Ativas'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-stop-circle"></i> Paradas Ativas</h1>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('paradas.historico')); ?>" class="btn btn-outline-primary d-none d-sm-inline-flex">
            <i class="fas fa-history"></i> <span class="d-none d-lg-inline">Ver </span>Histórico
        </a>
        <a href="<?php echo e(route('paradas.create')); ?>" class="btn btn-primary d-none d-sm-inline-flex">
            <i class="fas fa-plus-circle"></i> <span class="d-none d-lg-inline">Nova </span>Parada
        </a>
    </div>
</div>

<!-- FAB Mobile com label -->
<div class="fab-container d-sm-none">
    <a href="<?php echo e(route('paradas.create')); ?>" class="fab-mobile" title="Criar Nova Parada" data-bs-toggle="tooltip" data-bs-placement="left">
        <i class="fas fa-plus"></i>
    </a>
    <div class="fab-label">
        Nova Parada
    </div>
</div>

<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $paradas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-2">
                        <span class="badge badge-departamento fs-6 mb-1 mb-sm-0"><?php echo e($parada->macro); ?></span>
                        <span class="badge 
                            <?php if($parada->status == 'concluida'): ?> badge-implementado
                            <?php elseif($parada->status == 'em_andamento'): ?> bg-warning
                            <?php else: ?> bg-secondary
                            <?php endif; ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $parada->status))); ?>

                        </span>
                    </div>
                    <h5 class="mb-0"><?php echo e($parada->nome); ?></h5>
                    <small class="text-muted">
                        <i class="fas fa-tag"></i> <?php echo e($parada->tipo_label); ?>

                    </small>
                </div>
                <div class="card-body">
                    <?php if($parada->descricao): ?>
                        <p class="text-muted small"><?php echo e(Str::limit($parada->descricao, 100)); ?></p>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> Início: <?php echo e($parada->data_inicio->format('d/m/Y H:i')); ?>

                        </small>
                        <?php if($parada->data_fim): ?>
                            <br>
                            <small class="text-success">
                                <i class="fas fa-calendar-check"></i> Fim: <?php echo e($parada->data_fim->format('d/m/Y H:i')); ?>

                            </small>
                        <?php endif; ?>
                        
                        <?php if($parada->duracao_prevista_horas): ?>
                            <br>
                            <small class="text-info">
                                <i class="fas fa-clock"></i> Duração prevista: <?php echo e(number_format($parada->duracao_prevista_horas, 2, ',', '.')); ?>h
                            </small>
                        <?php endif; ?>
                        
                        <?php if($parada->status == 'em_andamento'): ?>
                            <br>
                            <small class="text-primary">
                                <i class="fas fa-play"></i> <?php echo e($parada->duracao_atual); ?>h em andamento
                            </small>
                        <?php endif; ?>
                        
                        <?php if($parada->equipe_responsavel): ?>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-users"></i> <?php echo e(Str::limit($parada->equipe_responsavel, 50)); ?>

                            </small>
                        <?php endif; ?>
                    </div>

                    <?php
                        $percentual = $parada->getPercentualCompleto();
                    ?>
                    
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped <?php echo e($percentual == 100 ? 'bg-success' : 'bg-primary'); ?>" 
                             role="progressbar" 
                             style="width: <?php echo e($percentual); ?>%"
                             aria-valuenow="<?php echo e($percentual); ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?php echo e($percentual); ?>%
                        </div>
                    </div>
                    
                    <small class="text-muted">Progresso geral</small>
                </div>
                <div class="card-footer">
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="<?php echo e(route('paradas.show', $parada)); ?>" class="btn btn-primary flex-fill">
                            <i class="fas fa-eye me-1"></i> <span class="d-none d-sm-inline">Visualizar</span><span class="d-sm-none">Ver Detalhes</span>
                        </a>
                        <?php if($parada->status == 'em_andamento'): ?>
                            <a href="<?php echo e(route('paradas.edit', $parada)); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i><span class="d-none d-md-inline"> Editar</span><span class="d-md-none">Editar</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-clipboard-list" style="font-size: 4rem; color: var(--accent-cyan); opacity: 0.6;"></i>
                </div>
                <h4 class="text-muted mb-3">Nenhuma parada encontrada</h4>
                <p class="text-muted mb-4">Comece criando sua primeira parada de manutenção.</p>
                <a href="<?php echo e(route('paradas.create')); ?>" class="btn btn-primary btn-lg d-none d-sm-inline-flex">
                    <i class="fas fa-plus-circle me-2"></i> Criar Primeira Parada
                </a>
                <!-- Mobile: FAB será usado automaticamente -->
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if($paradas->count() > 0): ?>
    <div class="mt-4">
        <h5>Estatísticas Gerais</h5>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary"><?php echo e($paradas->where('status', 'em_andamento')->count()); ?></h3>
                        <p class="mb-0">Em Andamento</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success"><?php echo e($paradas->where('status', 'concluida')->count()); ?></h3>
                        <p class="mb-0">Concluídas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-secondary"><?php echo e($paradas->where('status', 'cancelada')->count()); ?></h3>
                        <p class="mb-0">Canceladas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info"><?php echo e($paradas->count()); ?></h3>
                        <p class="mb-0">Total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\ProgramaçãoWeb\ProjetosWeb\checklist\resources\views/paradas/index.blade.php ENDPATH**/ ?>