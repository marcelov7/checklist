

<?php $__env->startSection('title', 'Debug - Seleção de Equipamentos'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Debug - Equipamentos</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5>Informações Recebidas</h5>
        </div>
        <div class="card-body">
            <p><strong>Parada:</strong> <?php echo e($parada->macro); ?> - <?php echo e($parada->nome); ?></p>
            <p><strong>Equipamentos Selecionados:</strong> 
                <?php if(isset($equipamentosSelecionados)): ?>
                    <?php echo e(count($equipamentosSelecionados)); ?> - <?php echo e(implode(', ', $equipamentosSelecionados)); ?>

                <?php else: ?>
                    Nenhum
                <?php endif; ?>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Todas as Áreas e Equipamentos</h5>
        </div>
        <div class="card-body">
            <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <h6><?php echo e($area->nome); ?></h6>
                <ul>
                    <?php $__currentLoopData = $area->equipamentosAtivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <?php echo e($equipamento->tag); ?> - <?php echo e($equipamento->nome); ?>

                            (ID: <?php echo e($equipamento->id); ?>)
                            <?php if(isset($equipamentosSelecionados) && in_array($equipamento->id, $equipamentosSelecionados)): ?>
                                <span class="badge bg-success">SELECIONADO</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">NÃO SELECIONADO</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?php echo e(route('paradas.select-equipment', $parada)); ?>" class="btn btn-primary">Voltar à Seleção</a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\paradas\debug-equipment.blade.php ENDPATH**/ ?>