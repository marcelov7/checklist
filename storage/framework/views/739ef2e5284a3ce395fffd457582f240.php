<?php $__env->startSection('title', 'Relatório da Parada - ' . $parada->nome); ?>

<?php
    // Os dados já vêm atualizados do controller
?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-3">
    <!-- Cabeçalho da Página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 d-print-none">
        <div class="mb-3 mb-lg-0">
            <h1 class="h2 mb-2">
                <i class="fas fa-clipboard-list text-primary me-2"></i>
                Relatório da Parada
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('paradas.show', $parada)); ?>"><?php echo e($parada->nome); ?></a></li>
                    <li class="breadcrumb-item active">Relatório</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-lg-auto">
            <a href="<?php echo e(route('paradas.pendencias', $parada)); ?>" class="btn btn-danger flex-fill flex-sm-grow-0">
                <i class="fas fa-exclamation-triangle me-1"></i>
                <span class="d-none d-sm-inline">Relatório de </span>Pendências
            </a>
            <button onclick="window.open('<?php echo e(route('paradas.print', $parada)); ?>', '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes'); return false;" class="btn btn-primary flex-fill flex-sm-grow-0">
                <i class="fas fa-print me-1"></i>
                <span class="d-none d-sm-inline">Versão para </span>Impressão
            </button>
            <a href="<?php echo e(route('paradas.historico')); ?>" class="btn btn-outline-secondary flex-fill flex-sm-grow-0">
                <i class="fas fa-arrow-left me-1"></i>
                <span class="d-none d-sm-inline">Voltar ao </span>Histórico
            </a>
        </div>
    </div>

    <!-- Card Principal da Parada -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-info-circle me-2"></i><?php echo e($parada->nome); ?>

            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-medium text-muted" style="width: 40%;">Tipo:</td>
                            <td>
                                <span class="badge bg-info"><?php echo e(ucfirst($parada->tipo)); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium text-muted">Status:</td>
                            <td>
                                <?php if($parada->status == 'em_andamento'): ?>
                                    <span class="badge bg-warning">Em Andamento</span>
                                <?php elseif($parada->status == 'concluida'): ?>
                                    <span class="badge bg-success">Concluída</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst($parada->status)); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium text-muted">Data Início:</td>
                            <td><?php echo e($parada->data_inicio->format('d/m/Y H:i')); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-medium text-muted" style="width: 40%;">Duração:</td>
                            <td><?php echo e($parada->duracao_prevista_horas ? $parada->duracao_prevista_horas . ' horas' : 'Não definida'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-medium text-muted">Equipe:</td>
                            <td><?php echo e($parada->equipe_responsavel ?? 'Não informada'); ?></td>
                        </tr>
                        <?php if($parada->descricao): ?>
                        <tr>
                            <td class="fw-medium text-muted">Descrição:</td>
                            <td><?php echo e($parada->descricao); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <?php
                // Buscar todos os testes da parada - dados sempre atuais
                $todosOsTestes = $parada->testes()->with('equipamento')->get();
                $totalEquipamentos = $todosOsTestes->count();
                
                // Calcular status baseado no checklist de cada equipamento
                $equipamentosCompletos = 0;
                $equipamentosComProblema = 0;
                $equipamentosPendentes = 0;
                $equipamentosEmAndamento = 0;
                
                foreach($todosOsTestes as $teste) {
                    $checklistItems = ['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'];
                    $hasProblema = false;
                    $hasOk = false;
                    $itensComStatus = 0;
                    $itensResolvidosOuNA = 0; // Itens OK ou N/A
                    
                    foreach($checklistItems as $item) {
                        $status = $teste->{$item . '_status'};
                        if($status) {
                            $itensComStatus++;
                            if($status === 'problema') {
                                $hasProblema = true;
                            } elseif($status === 'ok') {
                                $hasOk = true;
                                $itensResolvidosOuNA++;
                            } elseif($status === 'nao_aplica') {
                                // N/A conta como resolvido
                                $itensResolvidosOuNA++;
                            }
                        }
                    }
                    
                    // Definir status do equipamento
                    if($hasProblema) {
                        $equipamentosComProblema++;
                    } elseif($itensComStatus > 0 && $itensResolvidosOuNA === $itensComStatus) {
                        // Se todos os itens com status estão OK ou N/A = COMPLETO
                        $equipamentosCompletos++;
                    } elseif($hasOk) {
                        $equipamentosEmAndamento++;
                    } else {
                        $equipamentosPendentes++;
                    }
                }
                
                // Calcular percentual de conclusão usando o mesmo método do histórico
                $percentualGeral = $parada->getPercentualCompleto();
                
                // Dados sempre atuais via controller
            ?>

            <!-- Resumo Estatístico -->
            <div class="row mt-3 pt-3 border-top">
                <div class="col-6 col-lg-3 text-center mb-3 mb-lg-0">
                    <div class="fs-3 fw-bold text-primary"><?php echo e($totalEquipamentos); ?></div>
                    <small class="text-muted">Total Equipamentos</small>
                </div>
                <div class="col-6 col-lg-3 text-center mb-3 mb-lg-0">
                    <div class="fs-3 fw-bold text-success"><?php echo e($equipamentosCompletos); ?></div>
                    <small class="text-muted">Completos</small>
                </div>
                <div class="col-6 col-lg-3 text-center mb-3 mb-lg-0">
                    <div class="fs-3 fw-bold text-danger"><?php echo e($equipamentosComProblema); ?></div>
                    <small class="text-muted">Com Problemas</small>
                </div>
                <div class="col-6 col-lg-3 text-center mb-3 mb-lg-0">
                    <div class="fs-3 fw-bold text-warning"><?php echo e($equipamentosEmAndamento); ?></div>
                    <small class="text-muted">Em Andamento</small>
                </div>
            </div>

            <!-- Detalhes Adicionais -->
            <?php if($equipamentosPendentes > 0): ?>
            <div class="row mt-2">
                <div class="col-12 text-center">
                    <small class="text-muted">
                        <strong><?php echo e($equipamentosPendentes); ?></strong> equipamentos ainda não iniciados
                    </small>
                </div>
            </div>
            <?php endif; ?>

            <!-- Barra de Progresso -->
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-medium">Progresso Geral:</span>
                    <span class="fw-bold"><?php echo e($percentualGeral); ?>%</span>
                </div>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar 
                        <?php if($percentualGeral >= 100): ?> bg-success
                        <?php elseif($percentualGeral >= 75): ?> bg-info
                        <?php elseif($percentualGeral >= 50): ?> bg-warning
                        <?php else: ?> bg-danger
                        <?php endif; ?>" 
                        role="progressbar" 
                        style="width: <?php echo e($percentualGeral); ?>%">
                        <?php echo e($percentualGeral); ?>%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        // Agrupar testes por área usando os dados já carregados
        $areasTestes = $parada->testes->groupBy('equipamento.area.nome');
    ?>

    <!-- Detalhamento por Área -->
    <?php $__currentLoopData = $areasTestes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nomeArea => $testesArea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-industry me-2 text-primary"></i><?php echo e($nomeArea); ?>

                    </h6>
                    <span class="badge bg-primary"><?php echo e($testesArea->count()); ?> equipamentos</span>
                </div>
            </div>
            
            <!-- Versão Desktop - Tabela -->
            <div class="card-body p-0 d-none d-lg-block">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 18%;">Equipamento</th>
                                <th style="width: 8%;">Tag</th>
                                <th style="width: 10%;">Status Geral</th>
                                <th style="width: 20%;">Itens do Checklist</th>
                                <th style="width: 15%;">Status dos Itens</th>
                                <th style="width: 12%;">Testado Por</th>
                                <th style="width: 9%;">Data/Hora</th>
                                <th style="width: 8%;">Obs/Problemas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $testesArea; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teste): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $equipamento = $teste->equipamento;
                                    // Calcular status geral baseado no checklist
                                    $checklistItems = ['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'];
                                    $hasProblema = false;
                                    $hasOk = false;
                                    $itensAplicaveis = 0;
                                    $itensOkOuNA = 0;
                                    
                                    foreach($checklistItems as $item) {
                                        $status = $teste->{$item . '_status'};
                                        if($status) {
                                            if($status === 'nao_aplica') {
                                                $itensOkOuNA++;
                                            } elseif($status === 'ok') {
                                                $itensAplicaveis++;
                                                $itensOkOuNA++;
                                                $hasOk = true;
                                            } elseif($status === 'problema') {
                                                $itensAplicaveis++;
                                                $hasProblema = true;
                                            } else {
                                                $itensAplicaveis++;
                                            }
                                        }
                                    }
                                    
                                    $totalItensComStatus = 0;
                                    foreach($checklistItems as $item) {
                                        if($teste->{$item . '_status'}) {
                                            $totalItensComStatus++;
                                        }
                                    }
                                    
                                    $statusGeral = 'PENDENTE';
                                    if($hasProblema) {
                                        $statusGeral = 'PROBLEMA';
                                    } elseif($totalItensComStatus > 0 && $itensOkOuNA === $totalItensComStatus) {
                                        $statusGeral = 'COMPLETO';
                                    } elseif($hasOk) {
                                        $statusGeral = 'EM ANDAMENTO';
                                    }

                                    $checklistLabels = [
                                        'ar_comprimido' => 'Ar Comprimido',
                                        'protecoes_eletricas' => 'Proteções Elétricas',
                                        'protecoes_mecanicas' => 'Proteções Mecânicas',
                                        'chave_remoto' => 'Chave Remoto',
                                        'inspecionado' => 'Inspeção Visual'
                                    ];
                                ?>
                                <tr>
                                    <td class="fw-medium"><?php echo e($equipamento->nome); ?></td>
                                    <td><code class="small"><?php echo e($equipamento->tag); ?></code></td>
                                    <td>
                                        <?php if($statusGeral === 'COMPLETO'): ?>
                                            <strong class="text-success">COMPLETO</strong>
                                        <?php elseif($statusGeral === 'PROBLEMA'): ?>
                                            <strong class="text-danger">PROBLEMA</strong>
                                        <?php elseif($statusGeral === 'EM ANDAMENTO'): ?>
                                            <strong class="text-warning">EM ANDAMENTO</strong>
                                        <?php else: ?>
                                            <strong class="text-secondary">PENDENTE</strong>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php $__currentLoopData = $checklistLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($teste->{$item . '_status'}): ?>
                                                <?php echo e($label); ?><br>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($teste->foto_verificada): ?>
                                            Foto Verificada<br>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php $__currentLoopData = $checklistLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $status = $teste->{$item . '_status'}; ?>
                                            <?php if($status): ?>
                                                <?php if($status === 'ok'): ?>
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle me-1"></i>OK
                                                    </span>
                                                <?php elseif($status === 'problema'): ?>
                                                    <span class="text-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>PROBLEMA
                                                    </span>
                                                <?php elseif($status === 'nao_aplica'): ?>
                                                    <span class="text-info">
                                                        <i class="fas fa-ban me-1"></i>N/A
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-warning">
                                                        <i class="fas fa-clock me-1"></i>PENDENTE
                                                    </span>
                                                <?php endif; ?>
                                                <br>
                                            <?php else: ?>
                                                <span class="text-warning">
                                                    <i class="fas fa-clock me-1"></i>PENDENTE
                                                </span>
                                                <br>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($teste->foto_verificada): ?>
                                            <span class="text-info">✓ OK</span><br>
                                        <?php endif; ?>
                                    </td>
                                    <td><small><?php echo e($teste->testado_por ?? '-'); ?></small></td>
                                    <td>
                                        <small>
                                            <?php if($teste && $teste->updated_at): ?>
                                                <?php echo e($teste->updated_at->format('d/m H:i')); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php
                                            $temProblemas = false;
                                            $problemas = [];
                                            foreach(['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'] as $item) {
                                                $problema = $teste->{$item . '_problema'};
                                                if($problema) {
                                                    $problemas[] = $problema;
                                                    $temProblemas = true;
                                                }
                                            }
                                        ?>
                                        
                                        <?php if($temProblemas): ?>
                                            <?php $__currentLoopData = $problemas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $problema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <small>• <?php echo e($problema); ?></small><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                        
                                        <?php if($teste->problema_descricao): ?>
                                            <small><strong>Geral:</strong> <?php echo e($teste->problema_descricao); ?></small><br>
                                        <?php endif; ?>

                                        <?php if($teste->observacoes): ?>
                                            <small><strong>Obs:</strong> <?php echo e($teste->observacoes); ?></small>
                                        <?php endif; ?>
                                        
                                        <?php if(!$temProblemas && !$teste->problema_descricao && !$teste->observacoes): ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Versão Mobile - Cards -->
            <div class="card-body p-2 d-lg-none">
                <?php $__currentLoopData = $testesArea; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teste): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><?php echo e($equipamento->nome); ?></h6>
                            <p class="card-subtitle mb-2 text-muted">
                                <code class="small"><?php echo e($equipamento->tag); ?></code>
                            </p>
                            
                            <?php
                                $equipamento = $teste->equipamento;
                                // Calcular status geral (mesmo código acima)
                                $checklistItems = ['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'];
                                $hasProblema = false;
                                $hasOk = false;
                                $itensAplicaveis = 0;
                                $itensOkOuNA = 0;
                                
                                foreach($checklistItems as $item) {
                                    $status = $teste->{$item . '_status'};
                                    if($status) {
                                        if($status === 'nao_aplica') {
                                            $itensOkOuNA++;
                                        } elseif($status === 'ok') {
                                            $itensAplicaveis++;
                                            $itensOkOuNA++;
                                            $hasOk = true;
                                        } elseif($status === 'problema') {
                                            $itensAplicaveis++;
                                            $hasProblema = true;
                                        } else {
                                            $itensAplicaveis++;
                                        }
                                    }
                                }
                            
                                // Status dos itens com ícones
                                foreach($checklistLabels as $item => $label) {
                                    $status = $teste->{$item . '_status'};
                                    echo '<div class="mb-1">';
                                    echo '<strong class="me-2">' . $label . ':</strong>';
                                    if($status) {
                                        if($status === 'ok') {
                                            echo '<span class="text-success"><i class="fas fa-check-circle me-1"></i>OK</span>';
                                        } elseif($status === 'problema') {
                                            echo '<span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>PROBLEMA</span>';
                                        } elseif($status === 'nao_aplica') {
                                            echo '<span class="text-info"><i class="fas fa-ban me-1"></i>N/A</span>';
                                        } else {
                                            echo '<span class="text-warning"><i class="fas fa-clock me-1"></i>PENDENTE</span>';
                                        }
                                    } else {
                                        echo '<span class="text-warning"><i class="fas fa-clock me-1"></i>PENDENTE</span>';
                                    }
                                    echo '</div>';
                                }
                            ?>
                        
                        <?php
                        $totalItensComStatus = 0;
                        foreach($checklistItems as $item) {
                            if($teste->{$item . '_status'}) {
                                $totalItensComStatus++;
                            }
                        }
                        
                        $statusGeral = 'PENDENTE';
                        if($hasProblema) {
                            $statusGeral = 'PROBLEMA';
                        } elseif($totalItensComStatus > 0 && $itensOkOuNA === $totalItensComStatus) {
                            $statusGeral = 'COMPLETO';
                        } elseif($hasOk) {
                            $statusGeral = 'EM ANDAMENTO';
                        }

                        $checklistLabels = [
                            'ar_comprimido' => 'Ar Comprimido',
                            'protecoes_eletricas' => 'Proteções Elétricas',
                            'protecoes_mecanicas' => 'Proteções Mecânicas',
                            'chave_remoto' => 'Chave Remoto',
                            'inspecionado' => 'Inspeção Visual'
                        ];
                        ?>
                    
                    <div class="card mb-2 border-start border-3 <?php if($statusGeral === 'COMPLETO'): ?> border-success <?php elseif($statusGeral === 'PROBLEMA'): ?> border-danger <?php elseif($statusGeral === 'EM ANDAMENTO'): ?> border-warning <?php else: ?> border-secondary <?php endif; ?>">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <h6 class="card-title mb-1 fw-bold"><?php echo e($equipamento->nome); ?></h6>
                                    <p class="card-text mb-2">
                                        <small class="text-muted">Tag: <code><?php echo e($equipamento->tag); ?></code></small>
                                    </p>
                                </div>
                                <div class="col-4 text-end">
                                    <?php if($statusGeral === 'COMPLETO'): ?>
                                        <span class="badge bg-success">COMPLETO</span>
                                    <?php elseif($statusGeral === 'PROBLEMA'): ?>
                                        <span class="badge bg-danger">PROBLEMA</span>
                                    <?php elseif($statusGeral === 'EM ANDAMENTO'): ?>
                                        <span class="badge bg-warning">EM ANDAMENTO</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">PENDENTE</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Checklist Items -->
                            <?php if($totalItensComStatus > 0): ?>
                                <div class="mt-3">
                                    <small class="fw-medium text-muted">ITENS VERIFICADOS:</small>
                                    <div class="mt-2">
                                        <?php $__currentLoopData = $checklistLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $status = $teste->{$item . '_status'}; ?>
                                            <?php if($status): ?>
                                                <div class="d-flex justify-content-between align-items-center py-1">
                                                    <small><?php echo e($label); ?></small>
                                                    <?php if($status === 'ok'): ?>
                                                        <span class="badge bg-success bg-opacity-25 text-success">OK</span>
                                                    <?php elseif($status === 'problema'): ?>
                                                        <span class="badge bg-danger bg-opacity-25 text-danger">PROBLEMA</span>
                                                    <?php elseif($status === 'nao_aplica'): ?>
                                                        <span class="badge bg-secondary bg-opacity-25 text-secondary">N/A</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Problemas -->
                            <?php
                                $temProblemas = false;
                                $problemas = [];
                                foreach(['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'] as $item) {
                                    $problema = $teste->{$item . '_problema'};
                                    if($problema) {
                                        $problemas[] = $problema;
                                        $temProblemas = true;
                                    }
                                }
                            ?>
                            
                            <?php if($temProblemas || $teste->problema_descricao): ?>
                                <div class="mt-3 p-2 bg-danger bg-opacity-10 rounded">
                                    <small class="fw-medium text-danger">PROBLEMAS IDENTIFICADOS:</small>
                                    <div class="mt-1">
                                        <?php $__currentLoopData = $problemas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $problema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <small class="d-block text-danger">• <?php echo e($problema); ?></small>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($teste->problema_descricao): ?>
                                            <small class="d-block text-danger"><strong>Geral:</strong> <?php echo e($teste->problema_descricao); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Informações Adicionais -->
                            <div class="mt-3 pt-2 border-top">
                                <div class="row text-muted">
                                    <div class="col-6">
                                        <small><strong>Testado por:</strong><br><?php echo e($teste->testado_por ?? 'Não informado'); ?></small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small><strong>Data/Hora:</strong><br>
                                            <?php if($teste && $teste->updated_at): ?>
                                                <?php echo e($teste->updated_at->format('d/m/Y H:i')); ?>

                                            <?php else: ?>
                                                Não testado
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                                
                                <?php if($teste->observacoes): ?>
                                    <div class="mt-2">
                                        <small><strong>Observações:</strong> <?php echo e($teste->observacoes); ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Resumo de Problemas -->
    <?php
        $problemasIdentificados = \App\Models\Teste::where('parada_id', $parada->id)
            ->where('status', 'problema')
            ->with('equipamento.area')
            ->get();
    ?>

    <?php if($problemasIdentificados->count() > 0): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Resumo de Problemas Identificados (<?php echo e($problemasIdentificados->count()); ?>)
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Área</th>
                            <th>Equipamento</th>
                            <th>Tag</th>
                            <th>Problema</th>
                            <th>Identificado Por</th>
                            <th>Data/Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $problemasIdentificados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $problema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($problema->equipamento->area->nome); ?></strong></td>
                            <td><?php echo e($problema->equipamento->nome); ?></td>
                            <td><code><?php echo e($problema->equipamento->tag); ?></code></td>
                            <td><?php echo e($problema->problema_descricao); ?></td>
                            <td><?php echo e($problema->testado_por ?? 'Não informado'); ?></td>
                            <td><?php echo e($problema->updated_at->format('d/m H:i')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php $__env->startPush('styles'); ?>
<style>
    /* Estilos Mobile Responsivos */
    @media (max-width: 991.98px) {
        .main-content {
            margin-top: 100px !important;
            padding: 15px 10px !important;
        }
        
        .container-fluid {
            padding: 0 10px !important;
        }
        
        /* Melhorar botões do cabeçalho em mobile */
        .d-flex.gap-2 {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .btn {
            font-size: 0.875rem !important;
            padding: 0.5rem 0.75rem !important;
        }
        
        /* Cards de área em mobile */
        .card {
            margin-bottom: 1rem !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
        
        .card-header {
            padding: 0.75rem !important;
        }
        
        .card-header h6 {
            font-size: 1rem !important;
        }
        
        /* Cards de equipamentos */
        .card-body .card {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        }
        
        .card-title {
            font-size: 1rem !important;
        }
        
        .badge {
            font-size: 0.75rem !important;
            padding: 0.375em 0.5em !important;
        }
    }
    
    @media (max-width: 767.98px) {
        .main-content {
            margin-top: 120px !important;
            padding: 10px 5px !important;
        }
        
        .container-fluid {
            padding: 0 5px !important;
        }
        
        /* Cabeçalho mais compacto */
        .d-flex.flex-column.flex-md-row {
            text-align: center;
        }
        
        .d-flex.gap-2 {
            margin-top: 1rem;
        }
        
        .btn {
            font-size: 0.8rem !important;
            padding: 0.4rem 0.6rem !important;
        }
        
        /* Cards ainda menores */
        .card {
            margin-bottom: 0.75rem !important;
        }
        
        .card-header {
            padding: 0.5rem !important;
        }
        
        .card-body {
            padding: 0.5rem !important;
        }
        
        .card-body .card .card-body {
            padding: 0.75rem !important;
        }
        
        /* Badges menores */
        .badge {
            font-size: 0.7rem !important;
            padding: 0.25em 0.4em !important;
        }
        
        /* Texto menor nos cards */
        .card-title {
            font-size: 0.95rem !important;
        }
        
        small {
            font-size: 0.75rem !important;
        }
    }
    
    @media (max-width: 575.98px) {
        .main-content {
            margin-top: 140px !important;
            padding: 5px !important;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
        
        /* Cards ultra compactos */
        .card {
            margin-bottom: 0.5rem !important;
        }
        
        .card-header {
            padding: 0.4rem !important;
        }
        
        .card-header h6 {
            font-size: 0.9rem !important;
        }
        
        .card-body .card .card-body {
            padding: 0.5rem !important;
        }
        
        /* Reorganizar informações em telas muito pequenas */
        .row {
            margin: 0 !important;
        }
        
        .col-8, .col-4, .col-6 {
            padding: 0 0.25rem !important;
        }
        
        .card-title {
            font-size: 0.85rem !important;
            margin-bottom: 0.25rem !important;
        }
        
        .badge {
            font-size: 0.65rem !important;
            padding: 0.2em 0.3em !important;
        }
        
        small {
            font-size: 0.7rem !important;
        }
        
        /* Espaçamento entre itens do checklist */
        .d-flex.justify-content-between {
            margin-bottom: 0.25rem !important;
        }
        
        /* Botões mais compactos */
        .btn {
            font-size: 0.75rem !important;
            padding: 0.3rem 0.5rem !important;
        }
    }

    /* Estilos para impressão */
    @media print {
        .d-print-none { display: none !important; }
        .d-lg-none { display: none !important; }
        .d-none.d-lg-block { display: block !important; }
        .card { page-break-inside: avoid; margin-bottom: 1rem; }
        .table { font-size: 11pt; }
        .table td, .table th { padding: 8pt 4pt; }
        .checklist-simple { font-size: 10pt; line-height: 1.3; }
        .checklist-simple strong { font-weight: bold; }
        .checklist-simple small { font-size: 9pt; }
        body { font-size: 11pt; color: #000; }
        .container-fluid { max-width: none; }
        .main-content { margin-top: 0 !important; }
    }

    /* Estilos customizados */
    .checklist-simple {
        font-size: 0.85rem;
        line-height: 1.4;
    }
    
    .checklist-simple strong {
        font-weight: 600;
    }
    
    .checklist-simple small {
        color: #666;
        line-height: 1.3;
    }
    
    code {
        background: #f8f9fa;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
        font-size: 0.875em;
    }

    /* Melhorias específicas para cards mobile */
    .border-start.border-3 {
        border-left-width: 4px !important;
    }
    
    .bg-opacity-10 {
        background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
    }
    
    .bg-opacity-25 {
        background-color: rgba(var(--bs-success-rgb), 0.25) !important;
    }

    /* Animações suaves */
    .card {
        transition: box-shadow 0.15s ease-in-out;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\ProgramaçãoWeb\ProjetosWeb\checklist\resources\views/paradas/relatorio.blade.php ENDPATH**/ ?>