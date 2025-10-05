

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-3 mb-sm-0">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </h1>
</div>

<!-- Cards de Estatísticas -->
<div class="row mb-4 g-3">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center p-3">
                <div class="stat-icon text-primary mb-2">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="stat-number mb-1"><?php echo e($totalParadas); ?></h3>
                <p class="stat-label mb-0 small">Total de Paradas</p>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center p-3">
                <div class="stat-icon text-warning mb-2">
                    <i class="fas fa-play-circle"></i>
                </div>
                <h3 class="stat-number mb-1"><?php echo e($paradasAtivas); ?></h3>
                <p class="stat-label mb-0 small">Paradas Ativas</p>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center p-3">
                <div class="stat-icon text-success mb-2">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="stat-number mb-1"><?php echo e($paradasConcluidas ?? 0); ?></h3>
                <p class="stat-label mb-0 small">Concluídas</p>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center p-3">
                <div class="stat-icon text-info mb-2">
                    <i class="fas fa-percentage"></i>
                </div>
                <h3 class="stat-number mb-1"><?php echo e($progressoGeral ?? 0); ?>%</h3>
                <p class="stat-label mb-0 small">Progresso Geral</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Paradas Recentes -->
    <div class="col-12 col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Paradas Recentes
                </h5>
            </div>
            <div class="card-body">
                <?php if(isset($paradasRecentes) && $paradasRecentes->count() > 0): ?>
                    <!-- Desktop Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Status</th>
                                    <th>Progresso</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $paradasRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <strong><?php echo e($parada->nome); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo e($parada->macro); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                <?php if($parada->status == 'concluida'): ?> bg-success
                                                <?php elseif($parada->status == 'em_andamento'): ?> bg-warning
                                                <?php else: ?> bg-secondary
                                                <?php endif; ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $parada->status))); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar" 
                                                     role="progressbar" 
                                                     style="width: <?php echo e($parada->progresso_percentual ?? 0); ?>%"
                                                     aria-valuenow="<?php echo e($parada->progresso_percentual ?? 0); ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted"><?php echo e($parada->progresso_percentual ?? 0); ?>%</small>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('paradas.show', $parada)); ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="mobile-card-list">
                        <?php $__currentLoopData = $paradasRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mobile-parada-card">
                                <div class="mobile-card-header">
                                    <div class="mobile-card-info">
                                        <div class="mobile-card-title"><?php echo e($parada->nome); ?></div>
                                        <div class="mobile-card-subtitle"><?php echo e($parada->macro); ?></div>
                                    </div>
                                    <div>
                                        <span class="badge 
                                            <?php if($parada->status == 'concluida'): ?> bg-success
                                            <?php elseif($parada->status == 'em_andamento'): ?> bg-warning text-dark
                                            <?php else: ?> bg-secondary
                                            <?php endif; ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $parada->status))); ?>

                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mobile-card-details">
                                    <div class="mobile-detail-item">
                                        <i class="fas fa-chart-line mobile-detail-icon"></i>
                                        <div class="flex-grow-1">
                                            <div class="progress mb-1" style="height: 6px;">
                                                <div class="progress-bar" 
                                                     role="progressbar" 
                                                     style="width: <?php echo e($parada->progresso_percentual ?? 0); ?>%"
                                                     aria-valuenow="<?php echo e($parada->progresso_percentual ?? 0); ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted"><?php echo e($parada->progresso_percentual ?? 0); ?>% concluído</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mobile-card-actions">
                                    <a href="<?php echo e(route('paradas.show', $parada)); ?>" class="btn btn-sm btn-primary flex-fill">
                                        <i class="fas fa-eye me-1"></i>Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <div class="mt-3">
                        <a href="<?php echo e(route('paradas.index')); ?>" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>Ver Todas as Paradas
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhuma parada encontrada.</p>
                        <a href="<?php echo e(route('paradas.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Criar Nova Parada
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Estatísticas e Ações Rápidas -->
    <div class="col-12 col-lg-4">
        <!-- Ações Rápidas -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-lightning-bolt me-2"></i>Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('paradas.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nova Parada
                    </a>
                    <a href="<?php echo e(route('areas.index')); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-map-marker-alt me-2"></i>Gerenciar Áreas
                    </a>
                    <a href="<?php echo e(route('equipamentos.index')); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-cogs me-2"></i>Gerenciar Equipamentos
                    </a>
                    <a href="<?php echo e(route('paradas.historico')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-history me-2"></i>Histórico de Paradas
                    </a>
                    
                    <!-- User Management for Admins -->
                    <?php if(session('user.perfil') === 'admin'): ?>
                        <hr>
                        <a href="<?php echo e(route('usuarios.index')); ?>" class="btn btn-outline-info">
                            <i class="fas fa-users me-2"></i>Gerenciar Usuários
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views/dashboard.blade.php ENDPATH**/ ?>