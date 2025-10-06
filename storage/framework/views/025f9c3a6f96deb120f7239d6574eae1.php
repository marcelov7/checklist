

<?php $__env->startSection('title', 'Gerenciar Usuários'); ?>

<?php $__env->startSection('content'); ?>
<!-- Debug temporário -->
<?php if(config('app.debug')): ?>
<div class="alert alert-info">
    <strong>Debug:</strong> Total de usuários encontrados: <?php echo e($users->total()); ?> | Na página atual: <?php echo e($users->count()); ?>

</div>
<?php endif; ?>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-3 mb-sm-0">
        <i class="fas fa-users"></i> Gerenciar Usuários
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?php echo e(route('usuarios.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo Usuário
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <!-- Mobile Filter Toggle -->
    <div class="d-md-none mobile-filter-toggle" onclick="toggleMobileFilters()">
        <div class="d-flex align-items-center justify-content-between">
            <span><i class="fas fa-filter me-2"></i>Filtros</span>
            <i class="fas fa-chevron-down" id="filterToggleIcon"></i>
        </div>
    </div>
    
    <div class="card-body mobile-filter-content" id="filterContent">
        <form method="GET" action="<?php echo e(route('usuarios.index')); ?>" class="row g-3">
            <div class="col-12 col-md-3">
                <label for="perfil" class="form-label">Perfil</label>
                <select class="form-select" name="perfil" id="perfil">
                    <option value="">Todos os perfis</option>
                    <option value="admin" <?php echo e(request('perfil') === 'admin' ? 'selected' : ''); ?>>Administrador</option>
                    <option value="operador" <?php echo e(request('perfil') === 'operador' ? 'selected' : ''); ?>>Operador</option>
                    <option value="manutencao" <?php echo e(request('perfil') === 'manutencao' ? 'selected' : ''); ?>>Manutenção</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" name="status" id="status">
                    <option value="">Todos os status</option>
                    <option value="ativo" <?php echo e(request('status') === 'ativo' ? 'selected' : ''); ?>>Ativo</option>
                    <option value="inativo" <?php echo e(request('status') === 'inativo' ? 'selected' : ''); ?>>Inativo</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" name="search" id="search" 
                       placeholder="Nome ou email..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label d-block">&nbsp;</label>
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i> <span class="d-none d-sm-inline">Filtrar</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Usuários -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Lista de Usuários
            <span class="badge bg-secondary ms-2"><?php echo e($users->total()); ?></span>
        </h5>
    </div>
    <div class="card-body">
        <?php if($users->count() > 0): ?>
            <!-- Desktop Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Departamento</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($users->count() > 0): ?>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <strong><?php echo e($user->name); ?></strong>
                                            <?php if($user->telefone): ?>
                                                <br><small class="text-muted"><?php echo e($user->telefone); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e($user->email); ?></td>
                                <td>
                                    <span class="badge 
                                        <?php if($user->perfil == 'admin'): ?> bg-danger
                                        <?php elseif($user->perfil == 'operador'): ?> bg-primary  
                                        <?php else: ?> bg-info
                                        <?php endif; ?>">
                                        <?php echo e($user->perfil_display); ?>

                                    </span>
                                </td>
                                <td><?php echo e($user->departamento ?? '-'); ?></td>
                                <td>
                                    <span class="badge <?php echo e($user->status == 'ativo' ? 'bg-success' : 'bg-secondary'); ?>">
                                        <?php echo e($user->status_display); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php echo e($user->created_at->format('d/m/Y')); ?>

                                    <br><small class="text-muted"><?php echo e($user->created_at->format('H:i')); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('usuarios.show', $user)); ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('usuarios.edit', $user)); ?>" 
                                           class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <?php if($user->id !== session('user.id')): ?>
                                            <form method="POST" action="<?php echo e(route('usuarios.toggle-status', $user)); ?>" 
                                                  class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" 
                                                        class="btn btn-sm <?php echo e($user->status == 'ativo' ? 'btn-outline-secondary' : 'btn-outline-success'); ?>" 
                                                        title="<?php echo e($user->status == 'ativo' ? 'Desativar' : 'Ativar'); ?>">
                                                    <i class="fas <?php echo e($user->status == 'ativo' ? 'fa-user-slash' : 'fa-user-check'); ?>"></i>
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="<?php echo e(route('usuarios.destroy', $user)); ?>" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="badge bg-info">Você</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-2x mb-3"></i>
                                        <h5>Nenhum usuário encontrado</h5>
                                        <p>Não há usuários cadastrados ou que correspondam aos filtros aplicados.</p>
                                        <a href="<?php echo e(route('usuarios.create')); ?>" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Criar Primeiro Usuário
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="mobile-card-list">
                <?php if($users->count() > 0): ?>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mobile-user-card">
                        <div class="mobile-card-header">
                            <div class="mobile-card-avatar">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                            <div class="mobile-card-info">
                                <div class="mobile-card-title"><?php echo e($user->name); ?></div>
                                <div class="mobile-card-subtitle"><?php echo e($user->email); ?></div>
                            </div>
                            <div>
                                <span class="badge <?php echo e($user->status == 'ativo' ? 'bg-success' : 'bg-secondary'); ?>">
                                    <?php echo e($user->status_display); ?>

                                </span>
                            </div>
                        </div>
                        
                        <div class="mobile-card-details">
                            <div class="mobile-detail-item">
                                <i class="fas fa-user-tag mobile-detail-icon"></i>
                                <span class="badge 
                                    <?php if($user->perfil == 'admin'): ?> bg-danger
                                    <?php elseif($user->perfil == 'operador'): ?> bg-primary  
                                    <?php else: ?> bg-info
                                    <?php endif; ?>">
                                    <?php echo e($user->perfil_display); ?>

                                </span>
                            </div>
                            
                            <?php if($user->departamento): ?>
                                <div class="mobile-detail-item">
                                    <i class="fas fa-building mobile-detail-icon"></i>
                                    <span><?php echo e($user->departamento); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($user->telefone): ?>
                                <div class="mobile-detail-item">
                                    <i class="fas fa-phone mobile-detail-icon"></i>
                                    <span><?php echo e($user->telefone); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mobile-detail-item">
                                <i class="fas fa-calendar mobile-detail-icon"></i>
                                <span>Criado em <?php echo e($user->created_at->format('d/m/Y H:i')); ?></span>
                            </div>
                        </div>
                        
                        <div class="mobile-card-actions">
                            <a href="<?php echo e(route('usuarios.show', $user)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Ver
                            </a>
                            <a href="<?php echo e(route('usuarios.edit', $user)); ?>" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit me-1"></i>Editar
                            </a>
                            
                            <?php if($user->id !== session('user.id')): ?>
                                <form method="POST" action="<?php echo e(route('usuarios.toggle-status', $user)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-sm <?php echo e($user->status == 'ativo' ? 'btn-outline-secondary' : 'btn-outline-success'); ?>">
                                        <i class="fas <?php echo e($user->status == 'ativo' ? 'fa-user-slash' : 'fa-user-check'); ?> me-1"></i>
                                        <?php echo e($user->status == 'ativo' ? 'Desativar' : 'Ativar'); ?>

                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="badge bg-info">Você</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h5>Nenhum usuário encontrado</h5>
                            <p>Não há usuários cadastrados ou que correspondam aos filtros aplicados.</p>
                            <a href="<?php echo e(route('usuarios.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Criar Primeiro Usuário
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando <?php echo e($users->firstItem() ?? 0); ?> a <?php echo e($users->lastItem() ?? 0); ?> 
                    de <?php echo e($users->total()); ?> usuários
                </div>
                <?php echo e($users->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">Nenhum usuário encontrado.</p>
                <a href="<?php echo e(route('usuarios.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Criar Primeiro Usuário
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
    font-weight: 600;
}

/* Mobile filter improvements */
@media (min-width: 768px) {
    .mobile-filter-toggle {
        display: none;
    }
    .mobile-filter-content {
        display: block !important;
    }
}
</style>

<script>
function toggleMobileFilters() {
    const content = document.getElementById('filterContent');
    const icon = document.getElementById('filterToggleIcon');
    
    content.classList.toggle('show');
    
    if (content.classList.contains('show')) {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}

// Auto-open filters if there are active filters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const hasActiveFilters = urlParams.has('perfil') || urlParams.has('status') || urlParams.has('search');
    
    if (hasActiveFilters && window.innerWidth < 768) {
        toggleMobileFilters();
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\usuarios\index.blade.php ENDPATH**/ ?>