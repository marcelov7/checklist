<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Parada - <?php echo e($parada->nome); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos específicos para impressão */
        @page {
            margin: 15mm 10mm;
            size: A4 portrait;
        }

        /* Ocultar elementos de menu e navegação */
        .sidebar, .navbar, .menu, nav, .navigation, 
        .breadcrumb, .btn, .dropdown, .collapse,
        .offcanvas, .modal, .toast, .alert-dismissible {
            display: none !important;
        }

        /* Garantir que o conteúdo ocupe toda a largura */
        .container-fluid, .container {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }

        /* Remover elementos interativos */
        button, input, select, textarea {
            display: none !important;
        }

        /* Foco apenas no conteúdo da impressão */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .no-print {
                display: none !important;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            background: white;
        }

        .print-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .print-header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
        }

        .company-info {
            font-size: 10pt;
            margin-top: 5px;
        }

        .card {
            border: 1px solid #dee2e6;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 15px;
            font-weight: bold;
        }

        .card-body {
            padding: 15px;
        }

        .table {
            font-size: 10pt;
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 0;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 6px 4px;
            text-align: left;
            vertical-align: top;
        }

        .table th {
            background: #f8f9fa;
            font-weight: bold;
        }

        .checklist-simple {
            font-size: 9pt;
            line-height: 1.2;
        }

        .checklist-simple strong {
            font-weight: 600;
        }

        .text-success { color: #28a745 !important; }
        .text-danger { color: #dc3545 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-muted { color: #6c757d !important; }
        .text-info { color: #17a2b8 !important; }

        .progress {
            height: 12px;
            background: #e9ecef;
            border: 1px solid #dee2e6;
        }

        .progress-bar {
            background: #28a745;
            color: #000;
            text-align: center;
            line-height: 12px;
            font-size: 9pt;
            font-weight: bold;
        }

        code {
            background: #f8f9fa;
            padding: 2px 4px;
            border: 1px solid #e9ecef;
            font-size: 9pt;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin: 15px 0;
            text-align: center;
        }

        .stat-item {
            border: 1px solid #000;
            padding: 10px;
            background: #f8f9fa;
            min-width: 80px;
        }

        .stat-number {
            font-size: 16pt;
            font-weight: bold;
            display: block;
        }

        .stat-label {
            font-size: 8pt;
            margin-top: 2px;
        }

        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

    <!-- Cabeçalho para impressão -->
    <div class="print-header">
        <h1>RELATÓRIO DE CHECKLIST DE PARADA</h1>
        <div class="company-info">
            <strong><?php echo e($parada->nome); ?></strong><br>
            Relatório gerado em: <?php echo e(now()->format('d/m/Y H:i:s')); ?>

        </div>
    </div>

    <?php
        // Usar a mesma lógica do relatório atual
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
        
        // Calcular percentual de conclusão (apenas equipamentos realmente completos)
        $percentualConclusao = $totalEquipamentos > 0 ? round(($equipamentosCompletos / $totalEquipamentos) * 100, 1) : 0;
        
        // Agrupar testes por área usando os dados já carregados
        $areasTestes = $parada->testes->groupBy('equipamento.area.nome');
    ?>

    <!-- Informações da Parada -->
    <div class="card">
        <div class="card-header">
            Informações da Parada
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-medium" style="width: 40%;">Tipo:</td>
                            <td><?php echo e(ucfirst($parada->tipo)); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Status:</td>
                            <td>
                                <?php if($parada->status == 'em_andamento'): ?>
                                    Em Andamento
                                <?php elseif($parada->status == 'concluida'): ?>
                                    Concluída
                                <?php else: ?>
                                    <?php echo e(ucfirst($parada->status)); ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Data Início:</td>
                            <td><?php echo e($parada->data_inicio->format('d/m/Y H:i')); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-medium" style="width: 40%;">Duração:</td>
                            <td><?php echo e($parada->duracao_prevista_horas ? $parada->duracao_prevista_horas . ' horas' : 'Não definida'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Equipe:</td>
                            <td><?php echo e($parada->equipe_responsavel ?? 'Não informada'); ?></td>
                        </tr>
                        <?php if($parada->descricao): ?>
                        <tr>
                            <td class="fw-medium">Descrição:</td>
                            <td><?php echo e($parada->descricao); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Resumo Estatístico -->
            <div class="summary-stats mt-3 pt-3" style="border-top: 1px solid #dee2e6;">
                <div class="stat-item">
                    <span class="stat-number"><?php echo e($totalEquipamentos); ?></span>
                    <div class="stat-label">Total Equipamentos</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo e($equipamentosCompletos); ?></span>
                    <div class="stat-label">Completos</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo e($equipamentosComProblema); ?></span>
                    <div class="stat-label">Com Problemas</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo e($equipamentosEmAndamento); ?></span>
                    <div class="stat-label">Em Andamento</div>
                </div>
            </div>

            <!-- Barra de Progresso -->
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Progresso Geral:</strong>
                    <strong><?php echo e($percentualConclusao); ?>%</strong>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: <?php echo e($percentualConclusao); ?>%">
                        <?php echo e($percentualConclusao); ?>%
                    </div>
                </div>
            </div>

            <?php if($equipamentosPendentes > 0): ?>
            <div class="mt-2 text-center">
                <small><strong><?php echo e($equipamentosPendentes); ?></strong> equipamentos ainda não iniciados</small>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Detalhamento por Área -->
    <?php $__currentLoopData = $areasTestes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nomeArea => $testesArea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card no-break">
            <div class="card-header">
                <?php echo e($nomeArea); ?> (<?php echo e($testesArea->count()); ?> equipamentos)
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 18%;">Equipamento</th>
                            <th style="width: 8%;">Tag</th>
                            <th style="width: 12%;">Status Geral</th>
                            <th style="width: 25%;">Itens do Checklist</th>
                            <th style="width: 20%;">Status dos Itens</th>
                            <th style="width: 17%;">Obs/Problemas</th>
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
                                $itensAplicaveis = 0; // Itens que se aplicam (não são N/A)
                                $itensOkOuNA = 0; // Itens OK ou N/A
                                
                                foreach($checklistItems as $item) {
                                    $status = $teste->{$item . '_status'};
                                    if($status) {
                                        if($status === 'nao_aplica') {
                                            // N/A conta como "resolvido" mas não aplicável
                                            $itensOkOuNA++;
                                        } elseif($status === 'ok') {
                                            $itensAplicaveis++;
                                            $itensOkOuNA++;
                                            $hasOk = true;
                                        } elseif($status === 'problema') {
                                            $itensAplicaveis++;
                                            $hasProblema = true;
                                        } else {
                                            $itensAplicaveis++; // Pendente conta como aplicável
                                        }
                                    }
                                }
                                
                                // Contar total de itens que têm algum status definido
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
                                    // Se todos os itens com status estão OK ou N/A = COMPLETO
                                    $statusGeral = 'COMPLETO';
                                } elseif($hasOk) {
                                    $statusGeral = 'EM ANDAMENTO';
                                }
                            ?>
                            <tr class="no-break">
                                <td><strong><?php echo e($equipamento->nome); ?></strong></td>
                                <td>
                                    <code><?php echo e($equipamento->tag); ?></code>
                                </td>
                                <td>
                                    <?php if($statusGeral === 'COMPLETO'): ?>
                                        <strong class="text-success">COMPLETO</strong>
                                    <?php elseif($statusGeral === 'PROBLEMA'): ?>
                                        <strong class="text-danger">PROBLEMA</strong>
                                    <?php elseif($statusGeral === 'EM ANDAMENTO'): ?>
                                        <strong class="text-warning">EM ANDAMENTO</strong>
                                    <?php else: ?>
                                        <strong class="text-muted">PENDENTE</strong>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $checklistLabels = [
                                            'ar_comprimido' => 'Ar Comprimido',
                                            'protecoes_eletricas' => 'Proteções Elétricas',
                                            'protecoes_mecanicas' => 'Proteções Mecânicas',
                                            'chave_remoto' => 'Chave Remoto',
                                            'inspecionado' => 'Inspeção Visual'
                                        ];
                                    ?>
                                    
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
                                                <span class="text-success">OK</span>
                                            <?php elseif($status === 'problema'): ?>
                                                <span class="text-danger">PROBLEMA</span>
                                            <?php elseif($status === 'nao_aplica'): ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                            <br>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php if($teste->foto_verificada): ?>
                                        <span class="text-info">OK</span><br>
                                    <?php endif; ?>
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
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Resumo de Problemas -->
    <?php
        $problemasIdentificados = \App\Models\Teste::where('parada_id', $parada->id)
            ->where('status', 'problema')
            ->with('equipamento.area')
            ->get();
    ?>

    <?php if($problemasIdentificados->count() > 0): ?>
    <div class="card no-break">
        <div class="card-header">
            Resumo de Problemas Identificados (<?php echo e($problemasIdentificados->count()); ?>)
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
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
                    <tr class="no-break">
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
    <?php endif; ?>

    <!-- Assinaturas para impressão -->
    <div class="card no-break" style="margin-top: 30px;">
        <div class="card-header">
            Validações e Assinaturas
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div style="border-bottom: 1pt solid #000; padding-bottom: 2pt; margin-bottom: 8pt;">
                        <strong>Responsável pela Execução:</strong>
                    </div>
                    <p><?php echo e($parada->equipe_responsavel ?? 'Não informado'); ?></p>
                    <br><br>
                    <div style="border-bottom: 1pt solid #000; width: 200pt; margin-bottom: 5pt;"></div>
                    <small>Assinatura e Data</small>
                </div>
                <div class="col-6">
                    <div style="border-bottom: 1pt solid #000; padding-bottom: 2pt; margin-bottom: 8pt;">
                        <strong>Supervisor Responsável:</strong>
                    </div>
                    <p>_________________________</p>
                    <br><br>
                    <div style="border-bottom: 1pt solid #000; width: 200pt; margin-bottom: 5pt;"></div>
                    <small>Assinatura e Data</small>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <small class="text-muted">
                        Relatório gerado automaticamente pelo Sistema de Checklist de Paradas em <?php echo e(now()->format('d/m/Y H:i:s')); ?>

                    </small>
                </div>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH D:\XAMP\checklist\resources\views\paradas\print.blade.php ENDPATH**/ ?>