

<?php $__env->startSection('title', 'Editar Área'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-2 mb-sm-0"><i class="fas fa-edit"></i> Editar Área</h1>
    <a href="<?php echo e(route('areas.index')); ?>" class="btn btn-secondary btn-lg">
        <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline">Voltar</span>
    </a>
</div>

<div class="row">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informações da Área</h5>
            </div>
            <div class="card-body">
                <?php if(session('error')): ?>
                    <div class="alert alert-danger">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>
                
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('areas.update', $area)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Área *</label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="nome" 
                               name="nome" 
                               value="<?php echo e(old('nome', $area->nome)); ?>" 
                               required>
                        <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="ativo" class="form-label">Status</label>
                        <select class="form-select <?php $__errorArgs = ['ativo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="ativo" 
                                name="ativo">
                            <option value="1" <?php echo e(old('ativo', $area->ativo) == 1 ? 'selected' : ''); ?>>Ativo</option>
                            <option value="0" <?php echo e(old('ativo', $area->ativo) == 0 ? 'selected' : ''); ?>>Inativo</option>
                        </select>
                        <?php $__errorArgs = ['ativo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Áreas inativas não aparecem nos cadastros de equipamentos</div>
                    </div>

                    <div class="mb-4">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="descricao" 
                                  name="descricao" 
                                  rows="4"><?php echo e(old('descricao', $area->descricao)); ?></textarea>
                        <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                        <a href="<?php echo e(route('areas.index')); ?>" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Área:</strong> <?php echo e($area->nome); ?>

                </div>
                <div class="mb-3">
                    <strong>Status:</strong> 
                    <span class="badge <?php echo e($area->ativo ? 'bg-success' : 'bg-secondary'); ?>">
                        <?php echo e($area->ativo ? 'Ativo' : 'Inativo'); ?>

                    </span>
                </div>
                <div class="mb-3">
                    <strong>Equipamentos:</strong> <?php echo e($area->equipamentos->count()); ?>

                </div>
                <div class="mb-3">
                    <strong>Criado em:</strong> <?php echo e($area->created_at->format('d/m/Y H:i')); ?>

                </div>
                <?php if($area->updated_at != $area->created_at): ?>
                <div class="mb-3">
                    <strong>Última alteração:</strong> <?php echo e($area->updated_at->format('d/m/Y H:i')); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Dicas</h5>
            </div>
            <div class="card-body">
                <ul class="text-muted">
                    <li>Áreas organizam os equipamentos no sistema</li>
                    <li>Áreas inativas não aparecem no cadastro de equipamentos</li>
                    <li>Equipamentos existentes não são afetados pelo status da área</li>
                    <li>Use nomes descritivos para facilitar a identificação</li>
                </ul>
            </div>
        </div>
        
        <?php if($area->equipamentos->count() > 0): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-tools"></i> Equipamentos nesta Área</h6>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $area->equipamentos->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><?php echo e($equipamento->nome); ?></span>
                            <code class="small"><?php echo e($equipamento->tag); ?></code>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($area->equipamentos->count() > 5): ?>
                        <div class="text-muted small">
                            ... e mais <?php echo e($area->equipamentos->count() - 5); ?> equipamento(s)
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\areas\edit.blade.php ENDPATH**/ ?>