

<?php $__env->startSection('title', 'Editar Parada - ' . $parada->macro); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Editar Parada</h1>
    <div>
        <a href="<?php echo e(route('paradas.show', $parada)); ?>" class="btn btn-outline-primary me-2">
            <i class="fas fa-eye"></i> Visualizar
        </a>
        <a href="<?php echo e(route('paradas.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

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

<form action="<?php echo e(route('paradas.update', $parada)); ?>" method="POST" id="formEditarParada">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    
    <!-- Etapa 1: Informações Básicas -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações Básicas da Parada</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="macro" class="form-label">Código da Parada (Macro) *</label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['macro'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="macro" 
                               name="macro" 
                               value="<?php echo e(old('macro', $parada->macro)); ?>" 
                               placeholder="Ex: PAR001-2025, PREV-OUT-25, etc."
                               required>
                        <?php $__errorArgs = ['macro'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text text-muted">Código único para identificar esta parada</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Parada *</label>
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
                               value="<?php echo e(old('nome', $parada->nome)); ?>" 
                               placeholder="Ex: Manutenção Preventiva Outubro"
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
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
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
                                  rows="3" 
                                  placeholder="Descrição detalhada da parada..."><?php echo e(old('descricao', $parada->descricao)); ?></textarea>
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
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-12 col-lg-4">
                    <div class="mb-3">
                        <label for="data_inicio" class="form-label">Data/Hora de Início *</label>
                        <input type="datetime-local" 
                               class="form-control <?php $__errorArgs = ['data_inicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="data_inicio" 
                               name="data_inicio" 
                               value="<?php echo e(old('data_inicio', \Carbon\Carbon::parse($parada->data_inicio)->format('Y-m-d\TH:i'))); ?>"
                               style="font-size: 16px; min-height: 44px;"
                               required>
                        <?php $__errorArgs = ['data_inicio'];
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
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="mb-3">
                        <label for="duracao_prevista_horas" class="form-label">Duração Prevista (horas)</label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['duracao_prevista_horas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="duracao_prevista_horas" 
                                   name="duracao_prevista_horas" 
                                   value="<?php echo e(old('duracao_prevista_horas', $parada->duracao_prevista_horas)); ?>" 
                                   min="1" 
                                   max="720"
                                   style="font-size: 16px; min-height: 44px;"
                                   placeholder="Ex: 8">
                            <span class="input-group-text">h</span>
                            <?php $__errorArgs = ['duracao_prevista_horas'];
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
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Parada *</label>
                        <select class="form-select <?php $__errorArgs = ['tipo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tipo" name="tipo" required style="font-size: 16px; min-height: 44px;">
                            <option value="">Selecione o tipo de parada</option>
                            <option value="programada" <?php echo e(old('tipo', $parada->tipo) == 'programada' ? 'selected' : ''); ?>>📅 Programada</option>
                            <option value="preventiva" <?php echo e(old('tipo', $parada->tipo) == 'preventiva' ? 'selected' : ''); ?>>🔧 Preventiva</option>
                            <option value="corretiva" <?php echo e(old('tipo', $parada->tipo) == 'corretiva' ? 'selected' : ''); ?>>🔨 Corretiva</option>
                            <option value="emergencial" <?php echo e(old('tipo', $parada->tipo) == 'emergencial' ? 'selected' : ''); ?>>🚨 Emergencial</option>
                        </select>
                        <?php $__errorArgs = ['tipo'];
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
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status" required>
                            <option value="em_andamento" <?php echo e(old('status', $parada->status) == 'em_andamento' ? 'selected' : ''); ?>>Em Andamento</option>
                            <option value="concluida" <?php echo e(old('status', $parada->status) == 'concluida' ? 'selected' : ''); ?>>Concluída</option>
                            <option value="cancelada" <?php echo e(old('status', $parada->status) == 'cancelada' ? 'selected' : ''); ?>>Cancelada</option>
                        </select>
                        <?php $__errorArgs = ['status'];
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
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="equipe_responsavel" class="form-label">Equipe Responsável</label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['equipe_responsavel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="equipe_responsavel" 
                               name="equipe_responsavel" 
                               value="<?php echo e(old('equipe_responsavel', $parada->equipe_responsavel)); ?>" 
                               placeholder="Ex: João Silva (Coordenador), Maria Santos (Técnica)">
                        <?php $__errorArgs = ['equipe_responsavel'];
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
                </div>
            </div>
        </div>
    </div>

    <!-- Etapa 2: Resumo dos Equipamentos -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-cogs"></i> Equipamentos da Parada</h5>
            <a href="<?php echo e(route('paradas.select-equipment', $parada)); ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i> Modificar Seleção
            </a>
        </div>
        <div class="card-body">
            <?php
                $testes = $parada->testes()->with('equipamento.area')->get();
                $equipamentosPorArea = $testes->groupBy('equipamento.area.nome');
            ?>
            
            <?php if($equipamentosPorArea->count() > 0): ?>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-primary mb-1"><?php echo e($equipamentosPorArea->count()); ?></h4>
                            <small class="text-muted">Áreas envolvidas</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-success mb-1"><?php echo e($testes->count()); ?></h4>
                            <small class="text-muted">Equipamentos</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-info mb-1"><?php echo e($testes->where('status', 'concluido')->count()); ?>/<?php echo e($testes->count()); ?></h4>
                            <small class="text-muted">Testes concluídos</small>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <?php $__currentLoopData = $equipamentosPorArea; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nomeArea => $testesArea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-map-marked-alt"></i> <?php echo e($nomeArea); ?>

                            <span class="badge bg-secondary ms-2"><?php echo e($testesArea->count()); ?> equipamentos</span>
                        </h6>
                        
                        <div class="row">
                            <?php $__currentLoopData = $testesArea; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teste): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="card border-start border-3 <?php echo e($teste->status == 'concluido' ? 'border-success' : ($teste->status == 'em_andamento' ? 'border-warning' : 'border-secondary')); ?>">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-cog text-secondary me-2"></i>
                                                <div class="flex-grow-1">
                                                    <strong><?php echo e($teste->equipamento->tag); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo e($teste->equipamento->nome); ?></small>
                                                </div>
                                                <span class="badge bg-<?php echo e($teste->status == 'concluido' ? 'success' : ($teste->status == 'em_andamento' ? 'warning' : 'secondary')); ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $teste->status))); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-warning fa-2x mb-3"></i>
                    <h5>Nenhum equipamento selecionado</h5>
                    <p class="text-muted mb-3">Esta parada ainda não possui equipamentos associados.</p>
                    <a href="<?php echo e(route('paradas.select-equipment', $parada)); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Selecionar Equipamentos
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="row">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success btn-lg me-3">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
            <a href="<?php echo e(route('paradas.show', $parada)); ?>" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validar formulário antes do envio
    document.getElementById('formEditarParada').addEventListener('submit', function(e) {
        const macro = document.getElementById('macro').value.trim();
        const nome = document.getElementById('nome').value.trim();
        const dataInicio = document.getElementById('data_inicio').value;
        const tipo = document.getElementById('tipo').value;
        const status = document.getElementById('status').value;
        
        if (!macro || !nome || !dataInicio || !tipo || !status) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
            return false;
        }
    });
    
    // Atualizar status baseado no progresso se necessário
    const statusSelect = document.getElementById('status');
    statusSelect.addEventListener('change', function() {
        if (this.value === 'concluida') {
            if (!confirm('Tem certeza que deseja marcar esta parada como concluída? Esta ação não pode ser desfeita facilmente.')) {
                this.value = '<?php echo e($parada->status); ?>';
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\paradas\edit.blade.php ENDPATH**/ ?>