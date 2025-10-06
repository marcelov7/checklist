

<?php $__env->startSection('title', 'Histórico de Paradas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-history me-2"></i>Histórico de Paradas
                    </h4>
                    <a href="<?php echo e(route('paradas.create')); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>Nova Parada
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-info mb-2">
                        <i class="fas fa-chart-bar fa-2x"></i>
                    </div>
                    <h3 class="text-info mb-1"><?php echo e($estatisticas['total']); ?></h3>
                    <p class="mb-0 small text-muted">Total de Paradas</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-primary mb-2">
                        <i class="fas fa-play-circle fa-2x"></i>
                    </div>
                    <h3 class="text-primary mb-1"><?php echo e($estatisticas['em_andamento']); ?></h3>
                    <p class="mb-0 small text-muted">Em Andamento</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h3 class="text-success mb-1"><?php echo e($estatisticas['concluidas']); ?></h3>
                    <p class="mb-0 small text-muted">Finalizadas</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-warning mb-2">
                        <i class="fas fa-tools fa-2x"></i>
                    </div>
                    <h3 class="text-warning mb-1"><?php echo e($estatisticas['preventivas']); ?></h3>
                    <p class="mb-0 small text-muted">Preventivas</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-danger mb-2">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <h3 class="text-danger mb-1"><?php echo e($estatisticas['corretivas'] ?? 0); ?></h3>
                    <p class="mb-0 small text-muted">Corretivas</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-dark mb-2">
                        <i class="fas fa-bolt fa-2x"></i>
                    </div>
                    <h3 class="text-dark mb-1"><?php echo e($estatisticas['emergenciais'] ?? 0); ?></h3>
                    <p class="mb-0 small text-muted">Emergenciais</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-filter me-2"></i>Filtros Rápidos
                    </h6>
                </div>
                <div class="card-body">
                    <div class="btn-group flex-wrap" role="group">
                        <a href="<?php echo e(route('paradas.historico')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Todas
                        </a>
                        <a href="<?php echo e(route('paradas.historico')); ?>?status=em_andamento" class="btn btn-outline-primary">
                            <i class="fas fa-play"></i> Em Andamento
                        </a>
                        <a href="<?php echo e(route('paradas.historico')); ?>?status=concluida" class="btn btn-outline-success">
                            <i class="fas fa-check"></i> Concluídas
                        </a>
                        <a href="<?php echo e(route('paradas.historico')); ?>?tipo=preventiva" class="btn btn-outline-warning">
                            <i class="fas fa-calendar-alt"></i> Preventivas
                        </a>
                        <a href="<?php echo e(route('paradas.historico')); ?>?tipo=corretiva" class="btn btn-outline-danger">
                            <i class="fas fa-wrench"></i> Corretivas
                        </a>
                        <a href="<?php echo e(route('paradas.historico')); ?>?tipo=emergencial" class="btn btn-outline-dark">
                            <i class="fas fa-exclamation-triangle"></i> Emergenciais
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Paradas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Todas as Paradas
                        <span class="badge bg-secondary ms-2"><?php echo e($paradas->total()); ?> <?php echo e($paradas->total() == 1 ? 'parada' : 'paradas'); ?></span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php $__empty_1 = true; $__currentLoopData = $paradas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $percentualGeral = $parada->getPercentualCompleto();
                            $percentualPorArea = $parada->getPercentualPorArea();
                        ?>
                        <div class="parada-item <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>" style="touch-action: pan-y; position: relative;">
                            <div class="p-4" style="pointer-events: none;">
                                <!-- Cabeçalho da Parada -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <span class="badge bg-dark fs-6"><?php echo e($parada->macro); ?></span>
                                                <br>
                                                <?php if($parada->status === 'concluida'): ?>
                                                    <span class="badge bg-success mt-1">
                                                        <i class="fas fa-check-circle me-1"></i>Finalizada
                                                    </span>
                                                <?php elseif($parada->status === 'em_andamento'): ?> 
                                                    <span class="badge bg-primary mt-1">
                                                        <i class="fas fa-play me-1"></i>Em Andamento
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary mt-1">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', $parada->status))); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <h5 class="mb-1"><?php echo e($parada->nome); ?></h5>
                                                <div class="text-muted small">
                                                    <i class="fas fa-tag me-1"></i><?php echo e(ucfirst($parada->tipo)); ?>

                                                    <?php if($parada->equipe_responsavel): ?>
                                                        | <i class="fas fa-users me-1"></i><?php echo e($parada->equipe_responsavel); ?>

                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-muted small mt-1">
                                                    <i class="fas fa-calendar me-1"></i><?php echo e($parada->data_inicio->format('d/m/Y H:i')); ?>

                                                    <?php if($parada->data_fim): ?>
                                                        | <i class="fas fa-calendar-check me-1"></i><?php echo e($parada->data_fim->format('d/m/Y H:i')); ?>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 text-end">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <!-- Progresso Geral -->
                                            <div class="me-3">
                                                <div class="text-center">
                                                    <div class="progress" style="width: 120px; height: 8px;">
                                                        <div class="progress-bar bg-<?php echo e($percentualGeral == 100 ? 'success' : 'primary'); ?>" 
                                                             style="width: <?php echo e($percentualGeral); ?>%">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted"><?php echo e($percentualGeral); ?>% Completo</small>
                                                </div>
                                            </div>
                                            
                                            <!-- Duração -->
                                            <div class="me-3 text-end">
                                                <?php if($parada->duracao_prevista_horas): ?>
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-clock me-1"></i><?php echo e(number_format($parada->duracao_prevista_horas, 1)); ?>h prevista
                                                    </small>
                                                <?php endif; ?>
                                                <?php if($parada->duracao_real): ?>
                                                    <small class="text-success d-block">
                                                        <i class="fas fa-stopwatch me-1"></i><?php echo e($parada->duracao_real); ?>h real
                                                    </small>
                                                <?php elseif($parada->status == 'em_andamento'): ?>
                                                    <small class="text-primary d-block">
                                                        <i class="fas fa-play me-1"></i><?php echo e($parada->duracao_atual); ?>h decorridas
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Botões de Ação -->
                                            <div class="btn-group btn-group-sm" style="touch-action: manipulation; position: relative; z-index: 10; pointer-events: auto;">
                                                <a href="<?php echo e(route('paradas.show', $parada)); ?>" class="btn btn-outline-primary" title="Visualizar" style="touch-action: manipulation; pointer-events: auto;">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if($parada->status === 'concluida' && session('user.perfil') === 'admin'): ?>
                                                    <button class="btn btn-outline-warning" onclick="reabrirParada(<?php echo e($parada->id); ?>)" title="Reabrir Parada (Admin)" style="touch-action: manipulation; pointer-events: auto;">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <div class="dropdown" style="pointer-events: auto;">
                                                    <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" style="touch-action: manipulation; position: relative; z-index: 15; pointer-events: auto;">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="<?php echo e(route('paradas.show', $parada)); ?>">
                                                            <i class="fas fa-eye me-2"></i>Visualizar
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="<?php echo e(route('paradas.relatorio', $parada)); ?>">
                                                            <i class="fas fa-file-alt me-2"></i>Relatório
                                                        </a></li>
                                                        <?php if($parada->status == 'em_andamento'): ?>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item" href="<?php echo e(route('paradas.edit', $parada)); ?>">
                                                                <i class="fas fa-edit me-2"></i>Editar
                                                            </a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Progresso por Área -->
                                <?php if($percentualPorArea->isNotEmpty()): ?>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <small class="text-muted d-block mb-2">Progresso por Área:</small>
                                            <div class="row g-2">
                                                <?php $__currentLoopData = $percentualPorArea; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-md-3">
                                                        <div class="d-flex align-items-center">
                                                            <small class="me-2"><?php echo e(Str::limit($area->nome, 15)); ?>:</small>
                                                            <div class="progress flex-grow-1" style="height: 4px;">
                                                                <div class="progress-bar bg-info" style="width: <?php echo e($area->percentual); ?>%"></div>
                                                            </div>
                                                            <small class="ms-2 text-muted"><?php echo e($area->percentual); ?>%</small>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Indicadores de Problemas -->
                                <?php if($parada->testes_problema > 0): ?>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i><?php echo e($parada->testes_problema); ?> problema<?php echo e($parada->testes_problema > 1 ? 's' : ''); ?> identificado<?php echo e($parada->testes_problema > 1 ? 's' : ''); ?>

                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-5">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Nenhuma parada encontrada</h4>
                <p class="text-muted">Comece criando sua primeira parada de manutenção.</p>
                <a href="<?php echo e(route('paradas.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Criar Primeira Parada
                </a>
            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($paradas->hasPages()): ?>
                        <div class="card-footer bg-light">
                            <?php echo e($paradas->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros Rápidos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros Rápidos</h6>
                </div>
                <div class="card-body">
                    <div class="btn-group flex-wrap" role="group">
                        <a href="<?php echo e(route('paradas.historico')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-1"></i>Todas
                        </a>
                        <a href="<?php echo e(route('paradas.historico')); ?>?status=em_andamento" class="btn btn-outline-primary">
                            <i class="fas fa-play me-1"></i>Em Andamento
                        </a>
                        <a href="<?php echo e(route('paradas.historico')); ?>?status=concluida" class="btn btn-outline-success">
                            <i class="fas fa-check me-1"></i>Finalizadas
                        </a>
                        <a href="<?php echo e(route('paradas.historico')); ?>?tipo=preventiva" class="btn btn-outline-warning">
                            <i class="fas fa-calendar-alt me-1"></i>Preventivas
                        </a>
                        <a href="<?php echo e(route('paradas.historico')); ?>?tipo=corretiva" class="btn btn-outline-danger">
                            <i class="fas fa-exclamation-triangle me-1"></i>Corretivas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Função para reabrir parada (apenas admin)
function reabrirParada(paradaId) {
    if (confirm('Tem certeza que deseja reabrir esta parada?\n\nApós reaberta, será possível fazer alterações nos checklists novamente.')) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch(`/paradas/${paradaId}/reabrir`, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Parada reaberta com sucesso!');
                location.reload();
            } else {
                alert('Erro ao reabrir parada: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao reabrir parada.');
        });
    }
}

// Otimização TOTAL para mobile: cards estáticos e botões funcionais
document.addEventListener('DOMContentLoaded', function() {
    // Detectar se é mobile
    const isMobile = window.innerWidth <= 991;
    
    if (isMobile) {
        // CRÍTICO: Estabilizar todos os cards completamente
        const paradaItems = document.querySelectorAll('.parada-item, .card');
        paradaItems.forEach(item => {
            // Forçar remoção de todas as transições e transformações
            item.style.transition = 'none';
            item.style.transform = 'none';
            item.style.willChange = 'auto';
            
            // Prevenir qualquer animação CSS que possa interferir
            item.addEventListener('touchstart', function(e) {
                // NÃO fazer nada no card - manter completamente estático
                e.stopPropagation();
            });
            
            item.addEventListener('touchmove', function(e) {
                // Evitar qualquer mudança visual durante scroll
                this.style.transform = 'none';
                this.style.transition = 'none';
            });
        });
        
        // Otimizar TODOS os botões para melhor resposta ao toque
        const allButtons = document.querySelectorAll('.btn, .dropdown-toggle, .btn-group .btn');
        allButtons.forEach(button => {
            // Remover animações que podem causar lag
            button.style.transition = 'none';
            button.style.willChange = 'auto';
            
            // Aumentar z-index para garantir que ficam "por cima" do card
            button.style.position = 'relative';
            button.style.zIndex = '10';
            
            // Feedback visual direto no botão (não no card pai)
            button.addEventListener('touchstart', function(e) {
                e.stopPropagation(); // CRÍTICO: evitar propagação para o card
                this.style.opacity = '0.8';
            });
            
            button.addEventListener('touchend', function(e) {
                e.stopPropagation();
                setTimeout(() => {
                    this.style.opacity = '1';
                }, 100);
            });
            
            // Garantir que cliques funcionem sem interferência
            button.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
        
        // Otimizar dropdowns especificamente
        const dropdownButtons = document.querySelectorAll('.dropdown-toggle');
        dropdownButtons.forEach(button => {
            button.style.zIndex = '15'; // Ainda maior que outros botões
            
            // Remover completamente animações do Bootstrap
            button.addEventListener('shown.bs.dropdown', function() {
                const menu = this.nextElementSibling;
                if (menu) {
                    menu.style.transition = 'none';
                    menu.style.animation = 'none';
                    menu.style.transform = 'none';
                }
            });
        });
        
        // Otimizar menus dropdown
        const dropdownMenus = document.querySelectorAll('.dropdown-menu');
        dropdownMenus.forEach(menu => {
            menu.style.transition = 'none';
            menu.style.animation = 'none';
            menu.style.transform = 'none';
            menu.style.zIndex = '1000';
        });
        
        // Melhorar fechamento de dropdowns
        document.addEventListener('touchstart', function(e) {
            // Fechar dropdowns quando tocar fora, mas não interferir com botões
            if (!e.target.closest('.dropdown')) {
                const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                openDropdowns.forEach(menu => {
                    const dropdown = bootstrap.Dropdown.getInstance(menu.previousElementSibling);
                    if (dropdown) dropdown.hide();
                });
            }
        });
        
        console.log('Mobile optimizations applied: cards stabilized, buttons enhanced');
    }
});
</script>

<style>
.parada-item {
    position: relative;
    margin-bottom: 1.5rem;
    background: white;
    border-radius: 0;
}

/* Desktop: animações completas */
@media (min-width: 992px) {
    .parada-item {
        transition: all 0.2s ease;
    }
    
    .parada-item:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
}

/* Mobile: sem animações, estabilidade total dos cards */
@media (max-width: 991.98px) {
    .parada-item {
        transition: none !important;
        transform: none !important;
    }
    
    /* IMPORTANTE: Remover TODOS os efeitos visuais que causam movimento no card */
    .parada-item:hover,
    .parada-item:active,
    .parada-item:focus,
    .parada-item:focus-within {
        background-color: white !important;
        transform: none !important;
        box-shadow: none !important;
        transition: none !important;
    }
    
    /* Cards devem permanecer completamente estáticos */
    .card {
        transition: none !important;
        transform: none !important;
    }
    
    .card:hover,
    .card:active,
    .card:focus {
        transform: none !important;
        box-shadow: inherit !important;
        background-color: inherit !important;
    }
    
    /* Otimização dos botões para mobile - área de toque maior */
    .btn-group .btn {
        transition: none !important;
        min-width: 44px;
        min-height: 44px;
        padding: 0.75rem;
        position: relative;
        z-index: 10;
    }
    
    /* Botões devem ter feedback visual próprio, não do card pai */
    .btn:active {
        background-color: var(--bs-btn-active-bg) !important;
        border-color: var(--bs-btn-active-border-color) !important;
        transform: none !important;
    }
    
    .dropdown-toggle {
        transition: none !important;
        position: relative;
        z-index: 15;
    }
    
    .dropdown-menu {
        transition: none !important;
        animation: none !important;
        transform: none !important;
        position: absolute !important;
        z-index: 1000 !important;
    }
    
    /* Evitar propagação de eventos do card para os botões */
    .btn-group {
        position: relative;
        z-index: 10;
        pointer-events: auto;
    }
    
    /* FORÇAR estabilidade total - override de qualquer CSS externo */
    * {
        -webkit-transform: none !important;
        -moz-transform: none !important;
        -ms-transform: none !important;
        -o-transform: none !important;
        transform: none !important;
    }
    
    /* Remover animações do Bootstrap que podem interferir */
    .card,
    .parada-item,
    .card-body,
    .row > div {
        -webkit-transition: none !important;
        -moz-transition: none !important;
        -ms-transition: none !important;
        -o-transition: none !important;
        transition: none !important;
        -webkit-animation: none !important;
        -moz-animation: none !important;
        -ms-animation: none !important;
        -o-animation: none !important;
        animation: none !important;
    }
    
    /* Garantir que elementos filhos não herdem animações problemáticas */
    .parada-item * {
        transform: none !important;
        transition: none !important;
    }
    
    /* Exceção apenas para os botões que precisam de feedback visual */
    .btn,
    .dropdown-toggle,
    .btn-group,
    .dropdown {
        pointer-events: auto !important;
        transition: opacity 0.1s ease !important;
    }
    
    /* Garantir que links e botões dentro dos cards funcionem */
    .parada-item a,
    .parada-item button,
    .parada-item .btn-group,
    .parada-item .dropdown {
        pointer-events: auto !important;
        position: relative;
        z-index: 10;
    }
    
    /* Container do card: permitir apenas scroll, não cliques */
    .parada-item {
        pointer-events: none !important;
        touch-action: pan-y !important;
    }
    
    /* Re-habilitar eventos apenas nos elementos interativos */
    .parada-item .btn,
    .parada-item .dropdown-toggle,
    .parada-item .dropdown-item,
    .parada-item a {
        pointer-events: auto !important;
    }
}

.parada-item.border-bottom {
    border-top: 4px solid #0d6efd !important;
    border-bottom: 1px solid #e9ecef !important;
    margin-bottom: 2rem;
    padding-top: 0.5rem;
    padding-bottom: 1rem;
}

.parada-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0;
}

/* Separador visual simples entre paradas */
.parada-item + .parada-item {
    position: relative;
    margin-top: 1rem;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\checklist\resources\views\paradas\historico.blade.php ENDPATH**/ ?>