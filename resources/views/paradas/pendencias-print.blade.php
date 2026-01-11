<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Pendências - {{ $parada->nome }}</title>
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

        /* Estilos específicos para imagens na impressão */
        .img-print-container {
            page-break-inside: avoid;
            margin-bottom: 15px;
        }

        .img-print-container img {
            display: block;
            margin: 0 auto;
            max-width: 100% !important;
            max-height: 180px !important;
            border: 2px solid #dee2e6;
            padding: 5px;
            background: white;
        }

        .border-danger {
            border-color: #dc3545 !important;
        }

        .border-success {
            border-color: #198754 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-success {
            color: #198754 !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        .text-primary {
            color: #0d6efd !important;
        }

        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        .bg-danger {
            background-color: #dc3545 !important;
            color: white !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: black !important;
        }

        .bg-success {
            background-color: #198754 !important;
            color: white !important;
        }

        .bg-info {
            background-color: #0dcaf0 !important;
            color: black !important;
        }

        .bg-primary {
            background-color: #0d6efd !important;
            color: white !important;
        }

        .bg-secondary {
            background-color: #6c757d !important;
            color: white !important;
        }

        .photos-container {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            justify-content: center;
        }

        .photo-box {
            flex: 1;
            max-width: 45%;
            text-align: center;
        }

        .photo-box img {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            border: 2px solid #dee2e6;
            padding: 5px;
            background: white;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho do Relatório -->
    <div class="print-header">
        <h1>Relatório de Pendências - {{ $parada->nome }}</h1>
        <div class="company-info">
            <strong>Tipo:</strong> {{ ucfirst($parada->tipo) }} |
            <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $parada->status)) }} |
            <strong>Duração:</strong> {{ $parada->duracao }} horas |
            <strong>Equipe:</strong> {{ $parada->equipe }}
        </div>
        <div class="company-info">
            <strong>Data Início:</strong> {{ $parada->data_inicio ? \Carbon\Carbon::parse($parada->data_inicio)->format('d/m/Y H:i') : 'Não informada' }} |
            <strong>Descrição:</strong> {{ $parada->descricao }}
        </div>
    </div>

    <!-- Contadores -->
    @php
        $totalEquipamentosPendentes = $testesPendentes->count();
        $equipamentosComProblema = $testesPendentes->filter(function($teste) {
            return in_array('problema', [
                $teste->ar_comprimido_status,
                $teste->protecoes_eletricas_status, 
                $teste->protecoes_mecanicas_status,
                $teste->chave_remoto_status,
                $teste->inspecionado_status
            ]);
        })->count();
        $equipamentosSemTeste = $totalEquipamentosPendentes - $equipamentosComProblema;
    @endphp

    <div class="summary-stats no-break">
        <div class="stat-item">
            <span class="stat-number text-danger">{{ $equipamentosComProblema }}</span>
            <div class="stat-label">Com Problemas</div>
        </div>
        <div class="stat-item">
            <span class="stat-number text-warning">{{ $equipamentosSemTeste }}</span>
            <div class="stat-label">Sem Teste/Pendente</div>
        </div>
        <div class="stat-item">
            <span class="stat-number text-primary">{{ $totalEquipamentosPendentes }}</span>
            <div class="stat-label">Total de Pendências</div>
        </div>
    </div>

    @if($testesPendentes->isEmpty())
        <!-- Quando não há pendências -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <h3 class="text-success">Parabéns! Nenhuma Pendência Encontrada</h3>
                <p class="text-muted">Todos os equipamentos desta parada estão com status COMPLETO.</p>
            </div>
        </div>
    @else
        <!-- Equipamentos com pendências agrupados por área -->
        @foreach($testesPendentes->groupBy('equipamento.area.nome') as $nomeArea => $testesArea)
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-industry me-2"></i>
                        {{ $nomeArea }} 
                        <span class="badge bg-danger ms-2">{{ $testesArea->count() }} equipamento(s) pendente(s)</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Equipamento</th>
                                    <th>TAG</th>
                                    <th>Status</th>
                                    <th>Itens</th>
                                    <th>Status dos Itens</th>
                                    <th class="text-center">Última Atualização</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($testesArea as $teste)
                                    @php
                                        $checklistItems = ['ar_comprimido', 'protecoes_eletricas', 'protecoes_mecanicas', 'chave_remoto', 'inspecionado'];
                                        
                                        $hasProblema = false;
                                        $hasOk = false;
                                        $itensOkOuNA = 0;
                                        
                                        foreach($checklistItems as $item) {
                                            $status = $teste->{$item . '_status'};
                                            if($status === 'ok') {
                                                $hasOk = true;
                                                $itensOkOuNA++;
                                            } elseif($status === 'nao_aplica') {
                                                $itensOkOuNA++;
                                            } elseif($status === 'problema') {
                                                $hasProblema = true;
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
                                        
                                        $statusClass = $statusGeral === 'COMPLETO' ? 'success' : 
                                                      ($statusGeral === 'PROBLEMA' ? 'danger' : 
                                                      ($statusGeral === 'EM ANDAMENTO' ? 'warning' : 'secondary'));
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $teste->equipamento->nome ?? 'N/A' }}</strong></td>
                                        <td><code>{{ $teste->equipamento->tag ?? 'N/A' }}</code></td>
                                        <td><span class="badge bg-{{ $statusClass }}">{{ $statusGeral }}</span></td>
                                        <td>
                                            <div class="checklist-simple">
                                                @foreach($checklistItems as $item)
                                                    @php
                                                        $status = $teste->{$item . '_status'};
                                                        if(!$status || $status === 'ok' || $status === 'nao_aplica') continue;
                                                        
                                                        $nomeItem = match($item) {
                                                            'ar_comprimido' => 'Ar Comprimido',
                                                            'protecoes_eletricas' => 'Proteções Elétricas',
                                                            'protecoes_mecanicas' => 'Proteções Mecânicas',
                                                            'chave_remoto' => 'Chave Remoto',
                                                            'inspecionado' => 'Inspeção Visual',
                                                            default => ucwords(str_replace('_', ' ', $item))
                                                        };
                                                    @endphp
                                                    <div>{{ $nomeItem }}</div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div class="checklist-simple">
                                                @foreach($checklistItems as $item)
                                                    @php 
                                                        $status = $teste->{$item . '_status'};
                                                        if(!$status || $status === 'ok' || $status === 'nao_aplica') continue;
                                                    @endphp
                                                    <div>
                                                        @if($status === 'problema')
                                                            <span class="text-danger"><strong>✗ PROBLEMA</strong></span>
                                                        @else
                                                            <span class="text-warning"><strong>⏳ PENDENTE</strong></span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ $teste->updated_at ? $teste->updated_at->format('d/m H:i') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Problemas Detalhados da Área -->
            @php
                $problemasArea = [];
                foreach($testesArea as $teste) {
                    foreach($checklistItems as $item) {
                        $status = $teste->{$item . '_status'};
                        if($status === 'problema') {
                            $problema = $teste->{$item . '_problema'};
                            $fotoProblema = $teste->{$item . '_foto_problema'};
                            $fotoResolucao = $teste->{$item . '_foto_resolucao'};
                            
                            if($problema) {
                                $problemasArea[] = [
                                    'equipamento' => $teste->equipamento->nome,
                                    'tag' => $teste->equipamento->tag,
                                    'item' => match($item) {
                                        'ar_comprimido' => 'Ar Comprimido',
                                        'protecoes_eletricas' => 'Proteções Elétricas',
                                        'protecoes_mecanicas' => 'Proteções Mecânicas',
                                        'chave_remoto' => 'Chave Remoto',
                                        'inspecionado' => 'Inspeção Visual',
                                        default => ucwords(str_replace('_', ' ', $item))
                                    },
                                    'problema' => $problema,
                                    'foto_problema' => $fotoProblema,
                                    'foto_resolucao' => $fotoResolucao,
                                    'testado_por' => $teste->testado_por,
                                    'data' => $teste->updated_at
                                ];
                            }
                        }
                    }
                }
            @endphp

            @if(count($problemasArea) > 0)
                <div class="card no-break">
                    <div class="card-header text-danger">
                        <strong>⚠️ Problemas Detalhados - {{ $nomeArea }}</strong>
                    </div>
                    <div class="card-body">
                        @foreach($problemasArea as $problema)
                            <div class="border-bottom pb-2 mb-2 no-break">
                                <strong>{{ $problema['equipamento'] }} ({{ $problema['tag'] }}) - {{ $problema['item'] }}</strong><br>
                                <small class="text-muted">
                                    Testado por: {{ $problema['testado_por'] ?? 'N/A' }} em {{ $problema['data'] ? $problema['data']->format('d/m/Y H:i') : 'N/A' }}
                                </small><br>
                                <span class="text-danger">{{ $problema['problema'] }}</span>

                                @if($problema['foto_problema'] || $problema['foto_resolucao'])
                                    <div class="photos-container">
                                        @if($problema['foto_problema'])
                                            <div class="photo-box">
                                                <img src="{{ Storage::url($problema['foto_problema']) }}" 
                                                     alt="Problema: {{ $problema['item'] }}">
                                                <small class="text-muted d-block mt-1">Foto do Problema</small>
                                            </div>
                                        @endif
                                        @if($problema['foto_resolucao'])
                                            <div class="photo-box">
                                                <img src="{{ Storage::url($problema['foto_resolucao']) }}" 
                                                     alt="Resolução: {{ $problema['item'] }}">
                                                <small class="text-muted d-block mt-1">Foto da Resolução</small>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <!-- Assinatura -->
        <div class="card no-break">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <p><strong>Responsável pelo Relatório:</strong></p>
                        <div style="margin-top: 40px; border-top: 1px solid #000; padding-top: 5px;">
                            Nome: ______________________________<br>
                            Assinatura: _________________________<br>
                            Data: {{ now()->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="col-6">
                        <p><strong>Supervisor/Coordenador:</strong></p>
                        <div style="margin-top: 40px; border-top: 1px solid #000; padding-top: 5px;">
                            Nome: ______________________________<br>
                            Assinatura: _________________________<br>
                            Data: ______________________________
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</body>
</html>