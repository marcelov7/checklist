

<?php $__env->startSection('title', 'Equipamentos'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-3 mb-sm-0"><i class="fas fa-cogs"></i> Equipamentos</h1>
    <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-sm-auto">
        <div class="btn-group flex-column flex-sm-row" role="group">
            <button type="button" class="btn btn-outline-success mb-1 mb-sm-0" onclick="exportarEquipamentos()">
                <i class="fas fa-download"></i> <span class="d-none d-sm-inline">Exportar </span>CSV
            </button>
            <button type="button" class="btn btn-outline-info mb-1 mb-sm-0" onclick="document.getElementById('importFileEquip').click()">
                <i class="fas fa-upload"></i> <span class="d-none d-sm-inline">Importar </span>CSV
            </button>
            <a href="<?php echo e(route('equipamentos.template')); ?>" class="btn btn-outline-warning mb-1 mb-sm-0">
                <i class="fas fa-file-csv"></i> <span class="d-none d-sm-inline">Template </span>CSV
            </a>
        </div>
        <a href="<?php echo e(route('equipamentos.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Novo </span>Equipamento
        </a>
    </div>
</div>

<!-- Input file oculto para importação -->
<input type="file" id="importFileEquip" accept=".csv,.txt" style="display: none;" onchange="importarEquipamentos(this.files[0])">

<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $equipamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                    <h6 class="mb-1 mb-sm-0"><?php echo e($equipamento->nome); ?></h6>
                    <span class="badge bg-primary"><?php echo e($equipamento->tag); ?></span>
                </div>
                <div class="card-body">
                    <?php if($equipamento->descricao): ?>
                        <p class="text-muted small"><?php echo e(Str::limit($equipamento->descricao, 100)); ?></p>
                    <?php endif; ?>
                    
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-map-marked-alt"></i> <?php echo e($equipamento->area->nome); ?>

                        </small>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <?php if($equipamento->ativo): ?>
                            <span class="badge bg-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inativo</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="<?php echo e(route('equipamentos.show', $equipamento)); ?>" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="fas fa-eye"></i> <span class="d-none d-sm-inline">Ver</span>
                        </a>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('equipamentos.edit', $equipamento)); ?>" class="btn btn-sm btn-outline-secondary flex-fill">
                                <i class="fas fa-edit"></i><span class="d-none d-md-inline"> Editar</span>
                            </a>
                            <form action="<?php echo e(route('equipamentos.destroy', $equipamento)); ?>" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Tem certeza que deseja desativar este equipamento?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i><span class="d-none d-lg-inline"> Excluir</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Nenhum equipamento encontrado</h4>
                <p class="text-muted">Comece criando seu primeiro equipamento.</p>
                <a href="<?php echo e(route('equipamentos.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Criar Primeiro Equipamento
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function exportarEquipamentos() {
    // Redirecionar diretamente para download do CSV
    window.location.href = '/equipamentos/export-data';
    mostrarNotificacao('Download do arquivo CSV iniciado!', 'success');
}



function importarEquipamentos(file) {
    if (!file) return;
    
    // Verificar se é um arquivo CSV
    if (!file.name.match(/\.(csv|txt)$/)) {
        mostrarNotificacao('Por favor, selecione um arquivo CSV (.csv ou .txt)', 'error');
        document.getElementById('importFileEquip').value = '';
        return;
    }
    
    const formData = new FormData();
    formData.append('file', file);
    
    // Mostrar indicador de carregamento
    mostrarNotificacao('Importando equipamentos, aguarde...', 'info');
    
    $.ajax({
        url: '/equipamentos/import-data',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                mostrarNotificacao(response.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                mostrarNotificacao(response.message, 'error');
            }
        },
        error: function(xhr) {
            const response = JSON.parse(xhr.responseText);
            mostrarNotificacao(response.message || 'Erro ao importar equipamentos', 'error');
        },
        complete: function() {
            // Reset do input
            document.getElementById('importFileEquip').value = '';
        }
    });
}

function mostrarNotificacao(mensagem, tipo) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${tipo === 'success' ? 'success' : tipo === 'error' ? 'danger' : 'info'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${mensagem}
        <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\equipamentos\index.blade.php ENDPATH**/ ?>