<?php $__env->startSection('title', 'Nova Parada'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-5">
    <div>
        <h1 class="mb-2 mb-sm-0 d-flex align-items-center">
            <i class="fas fa-plus text-primary fs-4 me-3"></i>
            Nova Parada
        </h1>
        <p class="text-muted mb-0">Criar nova parada de manutenção no sistema</p>
    </div>
    <a href="<?php echo e(route('paradas.index')); ?>" class="btn btn-outline-secondary btn-lg mt-3 mt-sm-0">
        <i class="fas fa-arrow-left me-2"></i>Voltar
    </a>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-4">
                <div class="d-flex align-items-center">
                    <div class="me-3 p-2 rounded-circle bg-info bg-opacity-10">
                        <i class="fas fa-clipboard-list text-info"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Informações da Parada</h5>
                        <small class="text-muted">Preencha os dados básicos da nova parada</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4 p-lg-5">
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
                
                <form action="<?php echo e(route('paradas.store')); ?>" method="POST" id="formParada">
                    <?php echo csrf_field(); ?>
                    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"}
                    
                    <!-- Seção 1: Identificação -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="border rounded-3 p-4 bg-light bg-opacity-50">
                                <h6 class="text-primary mb-4 d-flex align-items-center">
                                    <i class="fas fa-id-card me-2"></i>Identificação da Parada
                                </h6>
                                
                                <div class="row g-4">
                                    <div class="col-12 col-lg-6">
                                        <label for="macro" class="form-label fw-semibold">
                                            Código da Parada (Macro) *
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg <?php $__errorArgs = ['macro'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="macro" 
                                               name="macro" 
                                               value="<?php echo e(old('macro')); ?>" 
                                               placeholder="Ex: GP-2025.04"
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
                                        <div class="form-text">Código único para identificar esta parada</div>
                                    </div>
                                    
                                    <div class="col-12 col-lg-6">
                                        <label for="nome" class="form-label fw-semibold">
                                            Nome da Parada *
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="nome" 
                                               name="nome" 
                                               value="<?php echo e(old('nome')); ?>" 
                                               placeholder="Ex: Parada programada do forno"
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
                        </div>
                    </div>

                    <!-- Seção 2: Configuração -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="border rounded-3 p-4 bg-light bg-opacity-50">
                                <h6 class="text-primary mb-4 d-flex align-items-center">
                                    <i class="fas fa-cogs me-2"></i>Configuração da Parada
                                </h6>
                                
                                <!-- Mobile: Campos em coluna única, Desktop: Campos lado a lado -->
                                <div class="row g-3 g-lg-4">
                                    <div class="col-12 col-lg-6">
                                        <label for="tipo" class="form-label fw-semibold">
                                            Tipo de Parada *
                                        </label>
                                        <select class="form-select form-select-lg <?php $__errorArgs = ['tipo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="tipo" 
                                                name="tipo" 
                                                required>
                                            <option value="">Selecione o tipo de parada</option>
                                            <option value="programada" <?php echo e(old('tipo') == 'programada' ? 'selected' : ''); ?>>
                                                📅 Programada
                                            </option>
                                            <option value="preventiva" <?php echo e(old('tipo') == 'preventiva' ? 'selected' : ''); ?>>
                                                🔧 Preventiva
                                            </option>
                                            <option value="corretiva" <?php echo e(old('tipo') == 'corretiva' ? 'selected' : ''); ?>>
                                                🔨 Corretiva
                                            </option>
                                            <option value="emergencial" <?php echo e(old('tipo') == 'emergencial' ? 'selected' : ''); ?>>
                                                🚨 Emergencial
                                            </option>
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
                                    
                                    <div class="col-12 col-lg-6">
                                        <label for="duracao_prevista_horas" class="form-label fw-semibold">
                                            Duração Prevista (horas)
                                        </label>
                                        <div class="input-group" style="display: flex; align-items: stretch;">
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
                                                   value="<?php echo e(old('duracao_prevista_horas', '120')); ?>" 
                                                   min="1" max="720"
                                                   placeholder="120"
                                                   style="font-size: 16px; min-height: 44px; flex: 1 1 auto; border-radius: 0.375rem 0 0 0.375rem;">
                                            <span class="input-group-text" style="font-size: 14px; padding: 0.375rem 0.75rem; border-radius: 0 0.375rem 0.375rem 0; white-space: nowrap;">horas</span>
                                        </div>
                                        <?php $__errorArgs = ['duracao_prevista_horas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="form-text">Estimativa de duração total da parada</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seção 3: Responsabilidade e Detalhes -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="border rounded-3 p-4 bg-light bg-opacity-50">
                                <h6 class="text-primary mb-4 d-flex align-items-center">
                                    <i class="fas fa-users me-2"></i>Responsabilidade e Detalhes
                                </h6>
                                
                                <div class="row g-3 g-lg-4">
                                    <div class="col-12 col-lg-6">
                                        <label for="equipe_responsavel" class="form-label fw-semibold">
                                            Equipe Responsável
                                        </label>
                                        <textarea class="form-control <?php $__errorArgs = ['equipe_responsavel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                  id="equipe_responsavel" 
                                                  name="equipe_responsavel" 
                                                  rows="3"
                                                  placeholder="Ex: João Silva (Coordenador)&#10;Maria Santos (Técnica Elétrica)&#10;Pedro Costa (Mecânico)"><?php echo e(old('equipe_responsavel')); ?></textarea>
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
                                        <div class="form-text">Liste os responsáveis e suas funções</div>
                                    </div>
                                    
                                    <div class="col-12 col-lg-6">
                                        <label for="data_inicio" class="form-label fw-semibold">
                                            Data e Hora de Início *
                                        </label>
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
                                               value="<?php echo e(old('data_inicio', now()->format('Y-m-d\TH:i'))); ?>" 
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
                                        <div class="form-text">Data e hora planejadas para início da parada</div>
                                    </div>
                                </div>
                                
                                <div class="row g-3 mt-2">
                                    <div class="col-12">
                                        <label for="descricao" class="form-label fw-semibold">
                                            Descrição da Parada
                                        </label>
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
                                                  rows="5" 
                                                  placeholder="Descreva os principais procedimentos, objetivos e atividades planejadas para esta parada..."><?php echo e(old('descricao')); ?></textarea>
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
                                        <div class="form-text">Detalhe os procedimentos e objetivos desta parada para melhor planejamento</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-end pt-4 border-top">
                                <a href="<?php echo e(route('paradas.index')); ?>" class="btn btn-outline-secondary btn-lg order-2 order-sm-1">
                                    <i class="fas fa-arrow-left me-2"></i>Voltar à Lista
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5 order-1 order-sm-2">
                                    <i class="fas fa-save me-2"></i>Criar Parada
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <div class="position-sticky" style="top: 2rem;">
            <!-- Card de Informações -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-lightbulb me-2"></i>Informações Importantes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 bg-light">
                        <i class="fas fa-info-circle me-2"></i>
                        Ao criar uma nova parada, o sistema irá automaticamente gerar testes pendentes 
                        para todos os equipamentos ativos cadastrados.
                    </div>
                </div>
            </div>

            <!-- Card de Tipos de Parada -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-clipboard-list me-2"></i>Tipos de Parada
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <span class="badge rounded-pill me-2 mt-1" style="background: var(--primary-blue); color: white;">📅</span>
                                <div>
                                    <strong style="color: var(--primary-blue);">Programada</strong><br>
                                    <small class="text-muted">Parada planejada com antecedência</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <span class="badge rounded-pill me-2 mt-1" style="background: var(--accent-cyan); color: white;">🔧</span>
                                <div>
                                    <strong style="color: var(--accent-cyan);">Preventiva</strong><br>
                                    <small class="text-muted">Manutenção preventiva de rotina</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <span class="badge rounded-pill me-2 mt-1" style="background: var(--secondary-blue); color: white;">🔨</span>
                                <div>
                                    <strong style="color: var(--secondary-blue);">Corretiva</strong><br>
                                    <small class="text-muted">Correção de problemas identificados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <span class="badge rounded-pill me-2 mt-1" style="background: var(--dark-blue); color: white;">🚨</span>
                                <div>
                                    <strong style="color: var(--dark-blue);">Emergencial</strong><br>
                                    <small class="text-muted">Parada não planejada por emergência</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Dicas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-tips me-2"></i>Dicas e Boas Práticas
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <small>Use códigos únicos e descritivos no macro</small>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <small>Inclua ano/mês no código para organização</small>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <small>A duração prevista ajuda no planejamento</small>
                        </li>
                        <li class="mb-0 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <small>Liste toda a equipe responsável</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Atualizar CSRF token automaticamente
document.addEventListener('DOMContentLoaded', function() {
    // Configurar CSRF token para requisições AJAX
    let token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
    }
    
    // Interceptar submit do formulário para tratar erro 419
    document.getElementById('formParada').addEventListener('submit', function(e) {
        let csrfInput = this.querySelector('input[name="_token"]');
        if (!csrfInput || !csrfInput.value) {
            e.preventDefault();
            alert('Erro de segurança. A página será recarregada.');
            window.location.reload();
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\ProgramaçãoWeb\ProjetosWeb\checklist\resources\views/paradas/create.blade.php ENDPATH**/ ?>