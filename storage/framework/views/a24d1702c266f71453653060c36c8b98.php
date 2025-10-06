

<?php $__env->startSection('title', 'Detalhes da Área'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><i class="fas fa-map-marked-alt"></i> <?php echo e($area->nome); ?></h1>
        <?php if($area->descricao): ?>
            <p class="text-muted mb-0"><?php echo e($area->descricao); ?></p>
        <?php endif; ?>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('areas.edit', $area)); ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="<?php echo e(route('areas.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Equipamentos da Área -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-cogs"></i> Equipamentos (<?php echo e($area->equipamentosAtivos->count()); ?>)</h5>
                <a href="<?php echo e(route('equipamentos.create')); ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Novo Equipamento
                </a>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $area->equipamentosAtivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-bottom py-3 <?php if(!$loop->last): ?> mb-3 <?php endif; ?>">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1">
                                    <a href="<?php echo e(route('equipamentos.show', $equipamento)); ?>" class="text-decoration-none">
                                        <?php echo e($equipamento->nome); ?>

                                    </a>
                                </h6>
                                <p class="text-muted mb-1">
                                    <span class="badge bg-primary"><?php echo e($equipamento->tag); ?></span>
                                    <?php if($equipamento->descricao): ?>
                                        | <?php echo e(Str::limit($equipamento->descricao, 50)); ?>

                                    <?php endif; ?>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> Criado em <?php echo e($equipamento->created_at->format('d/m/Y')); ?>

                                </small>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <?php
                                    $ultimoTeste = $equipamento->testes()->latest()->first();
                                ?>
                                
                                <?php if($ultimoTeste): ?>
                                    <?php switch($ultimoTeste->status):
                                        case ('ok'): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Último teste: OK
                                            </span>
                                            <?php break; ?>
                                        <?php case ('problema'): ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times"></i> Último teste: Problema
                                            </span>
                                            <?php break; ?>
                                        <?php case ('resolvido'): ?>
                                            <span class="badge bg-info">
                                                <i class="fas fa-wrench"></i> Último teste: Resolvido
                                            </span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Último teste: Pendente
                                            </span>
                                    <?php endswitch; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-minus"></i> Sem testes
                                    </span>
                                <?php endif; ?>
                                
                                <div class="mt-2">
                                    <a href="<?php echo e(route('equipamentos.show', $equipamento)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="<?php echo e(route('equipamentos.edit', $equipamento)); ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Nenhum equipamento cadastrado</h6>
                        <p class="text-muted">Esta área ainda não possui equipamentos.</p>
                        <a href="<?php echo e(route('equipamentos.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Adicionar Primeiro Equipamento
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar com Informações -->
    <div class="col-md-4">
        <!-- Informações da Área -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informações da Área</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Nome:</strong><br>
                    <?php echo e($area->nome); ?>

                </p>
                
                <?php if($area->descricao): ?>
                    <p class="mb-2">
                        <strong>Descrição:</strong><br>
                        <?php echo e($area->descricao); ?>

                    </p>
                <?php endif; ?>
                
                <p class="mb-2">
                    <strong>Status:</strong><br>
                    <?php if($area->ativo): ?>
                        <span class="badge bg-success">Ativa</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inativa</span>
                    <?php endif; ?>
                </p>
                
                <p class="mb-2">
                    <strong>Equipamentos:</strong><br>
                    <?php echo e($area->equipamentosAtivos->count()); ?> ativos
                </p>
                
                <hr>
                
                <p class="mb-2">
                    <small class="text-muted">Criada em:</small><br>
                    <?php echo e($area->created_at->format('d/m/Y H:i')); ?>

                </p>
                
                <p class="mb-0">
                    <small class="text-muted">Última atualização:</small><br>
                    <?php echo e($area->updated_at->format('d/m/Y H:i')); ?>

                </p>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Estatísticas</h6>
            </div>
            <div class="card-body">
                <?php
                    $totalEquipamentos = $area->equipamentosAtivos->count();
                    $equipamentosComTeste = $area->equipamentosAtivos->filter(function($eq) {
                        return $eq->testes->count() > 0;
                    })->count();
                    
                    $totalTestes = $area->equipamentosAtivos->sum(function($eq) {
                        return $eq->testes->count();
                    });
                    
                    $testesOk = $area->equipamentosAtivos->sum(function($eq) {
                        return $eq->testes->where('status', 'ok')->count();
                    });
                ?>

                <div class="mb-3">
                    <small class="text-muted">Total de Equipamentos</small>
                    <h4 class="mb-0"><?php echo e($totalEquipamentos); ?></h4>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Equipamentos Testados</small>
                    <h4 class="mb-0"><?php echo e($equipamentosComTeste); ?></h4>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Total de Testes</small>
                    <h4 class="mb-0"><?php echo e($totalTestes); ?></h4>
                </div>

                <?php if($totalTestes > 0): ?>
                    <div class="mb-3">
                        <small class="text-muted">Testes Bem-sucedidos</small>
                        <h4 class="mb-0"><?php echo e($testesOk); ?></h4>
                    </div>

                    <?php
                        $sucessoRate = round(($testesOk / $totalTestes) * 100, 1);
                    ?>
                    
                    <div>
                        <small class="text-muted">Taxa de Sucesso</small>
                        <div class="progress mb-1" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: <?php echo e($sucessoRate); ?>%" 
                                 aria-valuenow="<?php echo e($sucessoRate); ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted"><?php echo e($sucessoRate); ?>%</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\areas\show.blade.php ENDPATH**/ ?>