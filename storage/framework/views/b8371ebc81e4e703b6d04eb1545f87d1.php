

<?php $__env->startSection('title', 'Meu Perfil'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-3 mb-sm-0">
        <i class="fas fa-user-circle"></i> Meu Perfil
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Editar Perfil
        </a>
    </div>
</div>

<div class="row">
    <!-- Informações do Perfil -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="avatar-xl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
                <div class="flex-grow-1">
                    <h3 class="mb-1"><?php echo e($user->name); ?></h3>
                    <p class="text-muted mb-0"><?php echo e($user->email); ?></p>
                    <span class="badge <?php echo e($user->status == 'ativo' ? 'bg-success' : 'bg-secondary'); ?> mt-1">
                        <?php echo e($user->status_display); ?>

                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informações Pessoais</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="140"><strong>Nome:</strong></td>
                                <td><?php echo e($user->name); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?php echo e($user->email); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Departamento:</strong></td>
                                <td><?php echo e($user->departamento ?? 'Não informado'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Telefone:</strong></td>
                                <td><?php echo e($user->telefone ?? 'Não informado'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informações do Sistema</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="140"><strong>Perfil:</strong></td>
                                <td>
                                    <span class="badge 
                                        <?php if($user->perfil == 'admin'): ?> bg-danger
                                        <?php elseif($user->perfil == 'operador'): ?> bg-primary  
                                        <?php else: ?> bg-info
                                        <?php endif; ?>">
                                        <?php echo e($user->perfil_display); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge <?php echo e($user->status == 'ativo' ? 'bg-success' : 'bg-secondary'); ?>">
                                        <?php echo e($user->status_display); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Conta criada:</strong></td>
                                <td><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Última atualização:</strong></td>
                                <td><?php echo e($user->updated_at->diffForHumans()); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações e Informações -->
    <div class="col-lg-4">
        <!-- Ações Rápidas -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Editar Perfil
                    </a>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-outline-info">
                        <i class="fas fa-tachometer-alt me-2"></i>Ir para Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Permissões do Perfil -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user-cog me-2"></i>Suas Permissões
                </h6>
            </div>
            <div class="card-body">
                <?php if($user->perfil == 'admin'): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-crown me-2"></i>
                        <strong>Administrador</strong><br>
                        Você tem acesso total ao sistema.
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Acesso total ao sistema</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar usuários</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar relatórios</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Configurações do sistema</li>
                    </ul>
                <?php elseif($user->perfil == 'operador'): ?>
                    <div class="alert alert-primary">
                        <i class="fas fa-user-tie me-2"></i>
                        <strong>Operador</strong><br>
                        Você pode gerenciar paradas e relatórios.
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Criar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar relatórios</li>
                        <li class="mb-0"><i class="fas fa-times text-muted me-2"></i>Gerenciar usuários</li>
                    </ul>
                <?php elseif($user->perfil == 'manutencao'): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-tools me-2"></i>
                        <strong>Manutenção</strong><br>
                        Você pode executar testes e atualizar paradas.
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Executar testes</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Atualizar status das paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar paradas</li>
                        <li class="mb-0"><i class="fas fa-times text-muted me-2"></i>Gerenciar usuários</li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Estatísticas Pessoais (Placeholder) -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Suas Estatísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center py-3">
                    <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Estatísticas pessoais<br>serão implementadas em breve.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-xl {
    width: 80px;
    height: 80px;
    font-size: 32px;
    font-weight: 600;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\profile\show.blade.php ENDPATH**/ ?>