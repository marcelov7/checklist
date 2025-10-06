

<?php $__env->startSection('title', 'Detalhes do Equipamento'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <div class="mb-3 mb-sm-0">
        <h1><i class="fas fa-cog"></i> <?php echo e($equipamento->nome); ?></h1>
        <p class="text-muted mb-0"><?php echo e($equipamento->tag); ?></p>
    </div>
    <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-sm-auto">
        <a href="<?php echo e(route('equipamentos.edit', $equipamento)); ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Editar</span>
        </a>
        <a href="<?php echo e(route('equipamentos.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline">Voltar</span>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <!-- Informações Básicas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações Básicas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <p><strong>Nome:</strong><br><?php echo e($equipamento->nome); ?></p>
                    </div>
                    <div class="col-12 col-sm-6">
                        <p><strong>Tag/Código:</strong><br>
                            <span class="badge bg-primary"><?php echo e($equipamento->tag); ?></span>
                        </p>
                    </div>
                    <div class="col-12 col-sm-6">
                        <p><strong>Área:</strong><br><?php echo e($equipamento->area->nome); ?></p>
                    </div>
                    <div class="col-12 col-sm-6">
                        <p><strong>Status:</strong><br>
                            <?php if($equipamento->ativo): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inativo</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php if($equipamento->descricao): ?>
                    <div class="col-12">
                        <p><strong>Descrição:</strong><br><?php echo e($equipamento->descricao); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Histórico de Testes -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history"></i> Histórico de Testes</h5>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $equipamento->testes()->orderBy('created_at', 'desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teste): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-bottom py-3 <?php if(!$loop->last): ?> mb-3 <?php endif; ?>">
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <h6 class="mb-1">Parada: <?php echo e($teste->parada->nome); ?></h6>
                                <p class="text-muted mb-1">
                                    <small>
                                        <i class="fas fa-calendar"></i> <?php echo e($teste->created_at->format('d/m/Y H:i')); ?>

                                        <?php if($teste->testado_por): ?>
                                            | <i class="fas fa-user"></i> <?php echo e($teste->testado_por); ?>

                                        <?php endif; ?>
                                    </small>
                                </p>
                                <?php if($teste->observacoes): ?>
                                    <p class="mb-2 mb-md-0"><small><?php echo e($teste->observacoes); ?></small></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-4 text-start text-md-end">
                                <?php switch($teste->status):
                                    case ('ok'): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> OK
                                        </span>
                                        <?php break; ?>
                                    <?php case ('problema'): ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> Problema
                                        </span>
                                        <?php break; ?>
                                    <?php case ('resolvido'): ?>
                                        <span class="badge bg-info">
                                            <i class="fas fa-wrench"></i> Resolvido
                                        </span>
                                        <?php break; ?>
                                    <?php default: ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> Pendente
                                        </span>
                                <?php endswitch; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Nenhum teste realizado ainda</h6>
                        <p class="text-muted">Este equipamento ainda não participou de nenhuma parada de manutenção.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar com Estatísticas -->
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Estatísticas</h6>
            </div>
            <div class="card-body">
                <?php
                    $totalTestes = $equipamento->testes->count();
                    $testesOk = $equipamento->testes->where('status', 'ok')->count();
                    $testesProblema = $equipamento->testes->where('status', 'problema')->count();
                    $testesResolvido = $equipamento->testes->where('status', 'resolvido')->count();
                    $testesPendente = $equipamento->testes->where('status', 'pendente')->count();
                ?>

                <div class="mb-3">
                    <small class="text-muted">Total de Testes</small>
                    <h4 class="mb-0"><?php echo e($totalTestes); ?></h4>
                </div>

                <?php if($totalTestes > 0): ?>
                    <hr>
                    
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-check text-success"></i> OK</span>
                            <span><?php echo e($testesOk); ?></span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-times text-danger"></i> Problemas</span>
                            <span><?php echo e($testesProblema); ?></span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-wrench text-info"></i> Resolvidos</span>
                            <span><?php echo e($testesResolvido); ?></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-clock text-warning"></i> Pendentes</span>
                            <span><?php echo e($testesPendente); ?></span>
                        </div>
                    </div>

                    <!-- Taxa de Sucesso -->
                    <?php
                        $sucessoRate = $totalTestes > 0 ? round((($testesOk + $testesResolvido) / $totalTestes) * 100, 1) : 0;
                    ?>
                    
                    <hr>
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

        <!-- Informações Técnicas -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info"></i> Informações Técnicas</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <small class="text-muted">Criado em:</small><br>
                    <?php echo e($equipamento->created_at->format('d/m/Y H:i')); ?>

                </p>
                <p class="mb-0">
                    <small class="text-muted">Última atualização:</small><br>
                    <?php echo e($equipamento->updated_at->format('d/m/Y H:i')); ?>

                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\equipamentos\show.blade.php ENDPATH**/ ?>