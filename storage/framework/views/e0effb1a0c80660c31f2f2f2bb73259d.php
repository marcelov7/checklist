

<?php $__env->startSection('title', 'Visualizar Usuário'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-3 mb-sm-0">
        <i class="fas fa-user"></i> Visualizar Usuário
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?php echo e(route('usuarios.edit', $usuario)); ?>" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
        <a href="<?php echo e(route('usuarios.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <!-- Informações do Usuário -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                    <?php echo e(strtoupper(substr($usuario->name, 0, 1))); ?>

                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-1"><?php echo e($usuario->name); ?></h4>
                    <p class="text-muted mb-0"><?php echo e($usuario->email); ?></p>
                </div>
                <div>
                    <span class="badge <?php echo e($usuario->status == 'ativo' ? 'bg-success' : 'bg-secondary'); ?> fs-6">
                        <?php echo e($usuario->status_display); ?>

                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Informações Básicas</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="120"><strong>Nome:</strong></td>
                                <td><?php echo e($usuario->name); ?></td>
                            </tr>
                            <?php if($usuario->username): ?>
                            <tr>
                                <td><strong>Username:</strong></td>
                                <td><code><?php echo e($usuario->username); ?></code></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?php echo e($usuario->email); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Perfil:</strong></td>
                                <td>
                                    <span class="badge 
                                        <?php if($usuario->perfil == 'admin'): ?> bg-danger
                                        <?php elseif($usuario->perfil == 'operador'): ?> bg-primary  
                                        <?php else: ?> bg-info
                                        <?php endif; ?>">
                                        <?php echo e($usuario->perfil_display); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge <?php echo e($usuario->status == 'ativo' ? 'bg-success' : 'bg-secondary'); ?>">
                                        <?php echo e($usuario->status_display); ?>

                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Informações Complementares</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="120"><strong>Departamento:</strong></td>
                                <td><?php echo e($usuario->departamento ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Telefone:</strong></td>
                                <td><?php echo e($usuario->telefone ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Criado em:</strong></td>
                                <td><?php echo e($usuario->created_at->format('d/m/Y H:i')); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Atualizado em:</strong></td>
                                <td><?php echo e($usuario->updated_at->format('d/m/Y H:i')); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atividades Recentes (se necessário no futuro) -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Atividades Recentes
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Histórico de atividades será implementado em breve.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('usuarios.edit', $usuario)); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Editar Usuário
                    </a>
                    
                    <?php if($usuario->id !== session('user.id')): ?>
                        <form method="POST" action="<?php echo e(route('usuarios.toggle-status', $usuario)); ?>" class="d-grid">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" 
                                    class="btn <?php echo e($usuario->status == 'ativo' ? 'btn-outline-warning' : 'btn-outline-success'); ?>">
                                <i class="fas <?php echo e($usuario->status == 'ativo' ? 'fa-user-slash' : 'fa-user-check'); ?> me-2"></i>
                                <?php echo e($usuario->status == 'ativo' ? 'Desativar' : 'Ativar'); ?> Usuário
                            </button>
                        </form>
                        
                        <hr>
                        
                        <form method="POST" 
                              action="<?php echo e(route('usuarios.destroy', $usuario)); ?>" 
                              class="d-grid" 
                              onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i>Excluir Usuário
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Este é o seu perfil. Use a opção "Meu Perfil" para fazer alterações.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Informações do Perfil -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user-cog me-2"></i>Permissões do Perfil
                </h6>
            </div>
            <div class="card-body">
                <?php if($usuario->perfil == 'admin'): ?>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Acesso total ao sistema</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar usuários</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar relatórios</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Configurações do sistema</li>
                    </ul>
                <?php elseif($usuario->perfil == 'operador'): ?>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Criar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar relatórios</li>
                        <li class="mb-0"><i class="fas fa-times text-muted me-2"></i>Gerenciar usuários</li>
                    </ul>
                <?php elseif($usuario->perfil == 'manutencao'): ?>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Executar testes</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Atualizar status</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar paradas</li>
                        <li class="mb-0"><i class="fas fa-times text-muted me-2"></i>Gerenciar usuários</li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 60px;
    height: 60px;
    font-size: 24px;
    font-weight: 600;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\usuarios\show.blade.php ENDPATH**/ ?>