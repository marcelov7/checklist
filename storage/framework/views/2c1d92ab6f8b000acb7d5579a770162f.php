

<?php $__env->startSection('title', 'Editar Equipamento'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-2 mb-sm-0"><i class="fas fa-edit"></i> Editar Equipamento</h1>
    <a href="<?php echo e(route('equipamentos.index')); ?>" class="btn btn-secondary btn-lg">
        <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline">Voltar</span>
    </a>
</div>

<div class="row">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informações do Equipamento</h5>
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

                <form action="<?php echo e(route('equipamentos.update', $equipamento)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Equipamento *</label>
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
                               value="<?php echo e(old('nome', $equipamento->nome)); ?>" 
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
                        <label for="tag" class="form-label">TAG *</label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['tag'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="tag" 
                               name="tag" 
                               value="<?php echo e(old('tag', $equipamento->tag)); ?>" 
                               placeholder="Ex: BOMB-001, MOT-015, etc."
                               required>
                        <?php $__errorArgs = ['tag'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">TAG deve ser única no sistema</div>
                    </div>

                    <div class="mb-3">
                        <label for="area_id" class="form-label">Área *</label>
                        <select class="form-select <?php $__errorArgs = ['area_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="area_id" 
                                name="area_id" 
                                required>
                            <option value="">Selecione uma área</option>
                            <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($area->id); ?>" <?php echo e(old('area_id', $equipamento->area_id) == $area->id ? 'selected' : ''); ?>>
                                    <?php echo e($area->nome); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['area_id'];
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
                            <option value="1" <?php echo e(old('ativo', $equipamento->ativo) == 1 ? 'selected' : ''); ?>>Ativo</option>
                            <option value="0" <?php echo e(old('ativo', $equipamento->ativo) == 0 ? 'selected' : ''); ?>>Inativo</option>
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
                        <div class="form-text">Equipamentos inativos não aparecem nos checklists</div>
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
                                  rows="4"><?php echo e(old('descricao', $equipamento->descricao)); ?></textarea>
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
                        <a href="<?php echo e(route('equipamentos.index')); ?>" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Equipamento:</strong> <?php echo e($equipamento->nome); ?>

                </div>
                <div class="mb-3">
                    <strong>TAG Atual:</strong> <code><?php echo e($equipamento->tag); ?></code>
                </div>
                <div class="mb-3">
                    <strong>Área:</strong> <?php echo e($equipamento->area->nome ?? 'N/A'); ?>

                </div>
                <div class="mb-3">
                    <strong>Status:</strong> 
                    <span class="badge <?php echo e($equipamento->ativo ? 'bg-success' : 'bg-secondary'); ?>">
                        <?php echo e($equipamento->ativo ? 'Ativo' : 'Inativo'); ?>

                    </span>
                </div>
                <div class="mb-3">
                    <strong>Criado em:</strong> <?php echo e($equipamento->created_at->format('d/m/Y H:i')); ?>

                </div>
                <?php if($equipamento->updated_at != $equipamento->created_at): ?>
                <div class="mb-3">
                    <strong>Última alteração:</strong> <?php echo e($equipamento->updated_at->format('d/m/Y H:i')); ?>

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
                    <li>Altere a TAG com cuidado para não afetar paradas em andamento</li>
                    <li>Equipamentos inativos são removidos dos próximos checklists</li>
                    <li>A área define onde o equipamento aparece nos relatórios</li>
                    <li>Mantenha a descrição atualizada para facilitar identificação</li>
                </ul>
            </div>
        </div>
        
        <?php if(isset($equipamento->testes) && $equipamento->testes->count() > 0): ?>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i>
                        Este equipamento possui <strong><?php echo e($equipamento->testes->count()); ?></strong> teste(s) associado(s).
                        <br><br>
                        <small>Mudanças no status podem afetar paradas ativas.</small>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\equipamentos\edit.blade.php ENDPATH**/ ?>