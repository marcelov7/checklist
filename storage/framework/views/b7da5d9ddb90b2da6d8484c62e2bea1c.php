<?php $__env->startSection('title', 'Selecionar Equipamentos - ' . $parada->macro); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-check-square"></i> Selecionar Equipamentos</h1>
    <div>
        <a href="<?php echo e(route('paradas.edit', $parada)); ?>" class="btn btn-secondary me-2">
            <i class="fas fa-edit"></i> Editar Parada
        </a>
        <a href="<?php echo e(route('paradas.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<!-- Informações da Parada -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h5 class="mb-1"><?php echo e($parada->macro); ?></h5>
                        <p class="mb-0"><?php echo e($parada->nome); ?></p>
                    </div>
                    <div class="col-md-3">
                        <small><i class="fas fa-calendar"></i> <?php echo e(\Carbon\Carbon::parse($parada->data_inicio)->format('d/m/Y H:i')); ?></small><br>
                        <small><i class="fas fa-tag"></i> <?php echo e(ucfirst($parada->tipo)); ?></small>
                    </div>
                    <div class="col-md-3">
                        <small><i class="fas fa-clock"></i> <?php echo e($parada->duracao_prevista_horas ?? 'Não definida'); ?>h</small><br>
                        <small><i class="fas fa-users"></i> <?php echo e($parada->equipe_responsavel ?? 'Não definida'); ?></small>
                    </div>
                    <div class="col-md-3">
                        <?php if(isset($equipamentosSelecionados) && count($equipamentosSelecionados) > 0): ?>
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-edit"></i> Editando Seleção
                            </span>
                            <br>
                            <small><?php echo e(count($equipamentosSelecionados)); ?> equipamento(s) atual</small>
                        <?php else: ?>
                            <span class="badge bg-warning fs-6">
                                <i class="fas fa-exclamation-triangle"></i> Aguardando Equipamentos
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(isset($equipamentosSelecionados) && count($equipamentosSelecionados) > 0): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Editando seleção:</strong> 
                Esta parada já possui <?php echo e(count($equipamentosSelecionados)); ?> equipamento(s) selecionado(s). 
                Os equipamentos já selecionados estão destacados em verde. 
                Você pode adicionar ou remover equipamentos conforme necessário.
            </div>
        </div>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('paradas.store-equipment', $parada)); ?>" method="POST" id="formEquipamentos">
    <?php echo csrf_field(); ?>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><i class="fas fa-list-check"></i> Selecionar Áreas e Equipamentos</h5>
                        <?php if(isset($equipamentosSelecionados) && count($equipamentosSelecionados) > 0): ?>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Editando seleção - <?php echo e(count($equipamentosSelecionados)); ?> equipamento(s) já selecionado(s)
                            </small>
                        <?php endif; ?>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selecionarTodos()">
                            <i class="fas fa-check-double"></i> Selecionar Todos
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="desmarcarTodos()">
                            <i class="fas fa-times"></i> Desmarcar Todos
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <?php if($areas->count() > 0): ?>
                        <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-bottom">
                                <!-- Cabeçalho da Área -->
                                <div class="p-3 bg-light">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input area-checkbox" 
                                                       type="checkbox" 
                                                       id="area_<?php echo e($area->id); ?>" 
                                                       data-area="<?php echo e($area->id); ?>"
                                                       onchange="toggleAreaEquipamentos(<?php echo e($area->id); ?>)">
                                                <label class="form-check-label fw-bold" for="area_<?php echo e($area->id); ?>">
                                                    <i class="fas fa-map-marked-alt text-primary"></i> <?php echo e($area->nome); ?>

                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">
                                                <i class="fas fa-cogs"></i> <?php echo e($area->equipamentosAtivos->count()); ?> equipamento(s)
                                            </small>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#equipamentos_area_<?php echo e($area->id); ?>" 
                                                    aria-expanded="false">
                                                <i class="fas fa-chevron-down"></i> Ver Equipamentos
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <?php if($area->descricao): ?>
                                        <p class="text-muted small mb-0 mt-2"><?php echo e($area->descricao); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Lista de Equipamentos (Colapsável) -->
                                <div class="collapse" id="equipamentos_area_<?php echo e($area->id); ?>">
                                    <div class="p-3">
                                        <?php if($area->equipamentosAtivos->count() > 0): ?>
                                            <div class="row">
                                                <?php $__currentLoopData = $area->equipamentosAtivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <div class="card border <?php echo e(in_array($equipamento->id, $equipamentosSelecionados ?? []) ? 'border-success bg-light' : ''); ?>">
                                                            <div class="card-body p-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input equipamento-checkbox" 
                                                                           type="checkbox" 
                                                                           name="equipamentos[]" 
                                                                           value="<?php echo e($equipamento->id); ?>" 
                                                                           id="equipamento_<?php echo e($equipamento->id); ?>"
                                                                           data-area="<?php echo e($area->id); ?>"
                                                                           <?php echo e(in_array($equipamento->id, $equipamentosSelecionados ?? []) ? 'checked' : ''); ?>>
                                                                    <label class="form-check-label w-100" for="equipamento_<?php echo e($equipamento->id); ?>">
                                                                        <div class="d-flex align-items-center mb-2">
                                                                            <i class="fas fa-cog text-secondary me-2"></i>
                                                                            <strong><?php echo e($equipamento->tag); ?></strong>
                                                                        </div>
                                                                        <p class="mb-1 small"><?php echo e($equipamento->nome); ?></p>
                                                                        <?php if($equipamento->descricao): ?>
                                                                            <p class="text-muted small mb-0"><?php echo e(Str::limit($equipamento->descricao, 80)); ?></p>
                                                                        <?php endif; ?>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center py-3">
                                                <i class="fas fa-info-circle text-muted"></i>
                                                <p class="text-muted mb-0">Nenhum equipamento ativo nesta área</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="p-4 text-center">
                            <i class="fas fa-exclamation-triangle text-warning fa-2x mb-3"></i>
                            <h5>Nenhuma área cadastrada</h5>
                            <p class="text-muted">É necessário cadastrar áreas e equipamentos antes de criar uma parada.</p>
                            <a href="<?php echo e(route('areas.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Cadastrar Área
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Resumo da Seleção -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-clipboard-check"></i> Resumo da Seleção</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-primary mb-1" id="total-areas">0</h4>
                                <small class="text-muted">Áreas selecionadas</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-success mb-1" id="total-equipamentos">0</h4>
                                <small class="text-muted">Equipamentos selecionados</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-info mb-1" id="total-testes">0</h4>
                                <small class="text-muted">Testes que serão criados</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botões de Ação -->
    <div class="row">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success btn-lg me-3" id="btnSalvar" disabled>
                <i class="fas fa-save"></i> Salvar e Iniciar Parada
            </button>
            <a href="<?php echo e(route('paradas.index')); ?>" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Atualizar contadores quando checkboxes mudam
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('equipamento-checkbox') || e.target.classList.contains('area-checkbox')) {
            atualizarContadores();
        }
    });
    
    // Inicializar contadores e estado das áreas
    atualizarContadores();
    
    // Expandir automaticamente áreas que têm equipamentos selecionados
    <?php if(isset($equipamentosSelecionados) && count($equipamentosSelecionados) > 0): ?>
        document.querySelectorAll('.equipamento-checkbox:checked').forEach(function(checkbox) {
            const areaId = checkbox.dataset.area;
            const collapseElement = document.getElementById(`equipamentos_area_${areaId}`);
            if (collapseElement) {
                new bootstrap.Collapse(collapseElement, { show: true });
            }
        });
    <?php endif; ?>
});

function toggleAreaEquipamentos(areaId) {
    const areaCheckbox = document.getElementById(`area_${areaId}`);
    const equipamentosCheckboxes = document.querySelectorAll(`input[data-area="${areaId}"]`);
    
    equipamentosCheckboxes.forEach(checkbox => {
        if (checkbox.name === 'equipamentos[]') {
            checkbox.checked = areaCheckbox.checked;
        }
    });
    
    atualizarContadores();
}

function selecionarTodos() {
    document.querySelectorAll('.area-checkbox, .equipamento-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    atualizarContadores();
}

function desmarcarTodos() {
    document.querySelectorAll('.area-checkbox, .equipamento-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    atualizarContadores();
}

function atualizarContadores() {
    const areasCheckboxes = document.querySelectorAll('.area-checkbox:checked');
    const equipamentosCheckboxes = document.querySelectorAll('.equipamento-checkbox:checked');
    
    document.getElementById('total-areas').textContent = areasCheckboxes.length;
    document.getElementById('total-equipamentos').textContent = equipamentosCheckboxes.length;
    document.getElementById('total-testes').textContent = equipamentosCheckboxes.length;
    
    // Habilitar/desabilitar botão salvar
    const btnSalvar = document.getElementById('btnSalvar');
    btnSalvar.disabled = equipamentosCheckboxes.length === 0;
    
    // Atualizar checkboxes de área baseado nos equipamentos
    document.querySelectorAll('.area-checkbox').forEach(areaCheckbox => {
        const areaId = areaCheckbox.dataset.area;
        const equipamentosArea = document.querySelectorAll(`input[data-area="${areaId}"][name="equipamentos[]"]`);
        const equipamentosAreaSelecionados = document.querySelectorAll(`input[data-area="${areaId}"][name="equipamentos[]"]:checked`);
        
        if (equipamentosAreaSelecionados.length === equipamentosArea.length && equipamentosArea.length > 0) {
            areaCheckbox.checked = true;
            areaCheckbox.indeterminate = false;
        } else if (equipamentosAreaSelecionados.length > 0) {
            areaCheckbox.checked = false;
            areaCheckbox.indeterminate = true;
        } else {
            areaCheckbox.checked = false;
            areaCheckbox.indeterminate = false;
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\ProgramaçãoWeb\ProjetosWeb\checklist\resources\views/paradas/select-equipment.blade.php ENDPATH**/ ?>