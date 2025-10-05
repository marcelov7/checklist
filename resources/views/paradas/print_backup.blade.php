<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Parada - {{ $parada->nome }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos específicos para impressão */
        @page {
            margin: 15mm 10mm;
            size: A4;
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
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px 4px;
            text-align: left;
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
            height: 15px;
            background: #e9ecef;
            border: 1px solid #dee2e6;
        }

        .progress-bar {
            background: #28a745;
            color: #000;
            text-align: center;
            line-height: 15px;
            font-size: 10pt;
            font-weight: bold;
        }

        code {
            background: #f8f9fa;
            padding: 2px 4px;
            border: 1px solid #e9ecef;
            font-size: 9pt;
        }
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 15px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .info-table .label {
            font-weight: bold;
            width: 150px;
        }

        .summary-metrics {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }

        .metric-box {
            text-align: center;
            border: 2px solid #000;
            padding: 15px;
            min-width: 100px;
            background: #f8f9fa;
        }

        .metric-number {
            font-size: 24pt;
            font-weight: bold;
            display: block;
        }

        .metric-label {
            font-size: 9pt;
            margin-top: 5px;
        }

        .progress-section {
            margin: 20px 0;
        }

        .progress-bar-print {
            width: 100%;
            height: 20px;
            border: 2px solid #000;
            position: relative;
            background: #f8f9fa;
        }

        .progress-fill {
            height: 100%;
            background: #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .equipment-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .equipment-table th,
        .equipment-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .equipment-table th {
            background: #e9ecef;
            font-weight: bold;
            font-size: 10pt;
        }

        .equipment-table td {
            font-size: 9pt;
        }

        .status-ok {
            background: #d4edda;
            padding: 3px 6px;
            border: 1px solid #c3e6cb;
            font-weight: bold;
        }

        .status-problema {
            background: #f8d7da;
            padding: 3px 6px;
            border: 1px solid #f5c6cb;
            font-weight: bold;
        }

        .status-pendente {
            background: #fff3cd;
            padding: 3px 6px;
            border: 1px solid #ffeaa7;
            font-weight: bold;
        }

        .area-header {
            background: #e3f2fd;
            padding: 12px;
            border: 1px solid #1976d2;
            font-weight: bold;
            font-size: 12pt;
            margin-top: 25px;
        }

        .area-progress {
            margin: 10px 0;
        }

        .problems-section {
            page-break-before: always;
        }

        .problems-header {
            background: #ffebee;
            border: 2px solid #f44336;
            color: #d32f2f;
            padding: 12px;
            font-weight: bold;
            font-size: 14pt;
            text-align: center;
        }

        .signatures {
            page-break-before: always;
            margin-top: 40px;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            margin: 40px 0;
        }

        .signature-box {
            width: 45%;
        }

        .signature-label {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .signature-line {
            border-bottom: 2px solid #000;
            height: 40px;
            margin: 30px 0 10px 0;
        }

        .signature-text {
            font-size: 9pt;
            text-align: center;
        }

        .observations {
            margin-top: 30px;
        }

        .obs-box {
            border: 2px solid #000;
            height: 100px;
            padding: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }

        /* Botão de impressão apenas na tela */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        @media print {
            .print-button {
                display: none !important;
            }
            
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }

        @media screen {
            body {
                padding: 20px;
                background: #f5f5f5;
            }
            
            .print-container {
                background: white;
                max-width: 210mm;
                margin: 0 auto;
                padding: 20mm;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                border-radius: 8px;
            }
        }

        @media print {
            .print-container {
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <!-- Botão de impressão (apenas na tela) -->
    <div class="print-button">
        <button onclick="window.print()" class="btn btn-primary btn-lg">
            <i class="fas fa-print"></i> Imprimir Relatório
        </button>
        <a href="{{ route('paradas.show', $parada) }}" class="btn btn-secondary btn-lg ms-2">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="print-container">
        <!-- Cabeçalho -->
        <div class="print-header">
            <h1>RELATÓRIO DE PARADA DE MANUTENÇÃO</h1>
            <div class="company-info">
                <strong>{{ config('app.name', 'Sistema de Checklist') }}</strong><br>
                Relatório gerado em {{ now()->format('d/m/Y H:i:s') }}
            </div>
        </div>

        <!-- Informações Gerais -->
        <div class="section">
            <div class="section-header">Informações Gerais da Parada</div>
            
            <table class="info-table">
                <tr>
                    <td class="label">Macro:</td>
                    <td><strong>{{ $parada->macro }}</strong></td>
                    <td class="label">Data Início:</td>
                    <td>{{ $parada->data_inicio->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="label">Nome:</td>
                    <td>{{ $parada->nome }}</td>
                    <td class="label">Data Fim:</td>
                    <td>{{ $parada->data_fim ? $parada->data_fim->format('d/m/Y H:i') : 'Em andamento' }}</td>
                </tr>
                <tr>
                    <td class="label">Tipo:</td>
                    <td>{{ ucfirst($parada->tipo) }}</td>
                    <td class="label">Duração Prevista:</td>
                    <td>{{ $parada->duracao_prevista_horas ?? 'Não definida' }} horas</td>
                </tr>
                <tr>
                    <td class="label">Status:</td>
                    <td>
                        @switch($parada->status)
                            @case('em_andamento') Em Andamento @break
                            @case('concluida') Concluída @break
                            @case('cancelada') Cancelada @break
                        @endswitch
                    </td>
                    <td class="label">Equipe Responsável:</td>
                    <td>{{ $parada->equipe_responsavel ?? 'Não informada' }}</td>
                </tr>
            </table>
            
            @if($parada->descricao)
            <div style="margin-top: 15px;">
                <strong>Descrição:</strong><br>
                {{ $parada->descricao }}
            </div>
            @endif
        </div>

        <!-- Resumo Executivo -->
        <div class="section">
            <div class="section-header">Resumo Executivo</div>
            
            <div class="summary-metrics">
                <div class="metric-box">
                    <span class="metric-number">{{ $resumo['total_equipamentos'] ?? 0 }}</span>
                    <div class="metric-label">Total Equipamentos</div>
                </div>
                <div class="metric-box">
                    <span class="metric-number">{{ $resumo['testes_ok'] ?? 0 }}</span>
                    <div class="metric-label">Testes OK</div>
                </div>
                <div class="metric-box">
                    <span class="metric-number">{{ $resumo['testes_problema'] ?? 0 }}</span>
                    <div class="metric-label">Problemas</div>
                </div>
                <div class="metric-box">
                    <span class="metric-number">{{ $resumo['testes_pendentes'] ?? 0 }}</span>
                    <div class="metric-label">Pendentes</div>
                </div>
            </div>

            <div class="progress-section">
                <strong>Progresso Geral: {{ $resumo['percentual_conclusao'] }}%</strong>
                <div class="progress-bar-print">
                    <div class="progress-fill" style="width: {{ $resumo['percentual_conclusao'] }}%;">
                        {{ $resumo['percentual_conclusao'] }}%
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalhamento por Área -->
        @php
            $areas = \App\Models\Area::whereHas('equipamentos.testes', function($query) use ($parada) {
                $query->where('parada_id', $parada->id);
            })->with(['equipamentos' => function($query) use ($parada) {
                $query->whereHas('testes', function($testQuery) use ($parada) {
                    $testQuery->where('parada_id', $parada->id);
                })->with(['testes' => function($testQuery) use ($parada) {
                    $testQuery->where('parada_id', $parada->id);
                }]);
            }])->get();
            
            $percentualPorArea = $parada->getPercentualPorArea();
        @endphp

        <div class="section">
            <div class="section-header">Detalhamento por Área</div>
            
            @foreach($areas as $area)
                @php
                    $areaPercentual = $percentualPorArea->where('id', $area->id)->first();
                @endphp
                
                <div class="area-header">
                    {{ $area->nome }} - {{ $areaPercentual ? $areaPercentual->percentual : 0 }}% Concluído
                </div>
                
                <div class="area-progress">
                    <div class="progress-bar-print" style="height: 15px;">
                        <div class="progress-fill" style="width: {{ $areaPercentual ? $areaPercentual->percentual : 0 }}%;">
                            {{ $areaPercentual ? $areaPercentual->percentual : 0 }}%
                        </div>
                    </div>
                </div>

                <table class="equipment-table no-break">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Equipamento</th>
                            <th style="width: 12%;">Tag</th>
                            <th style="width: 12%;">Status</th>
                            <th style="width: 15%;">Testado Por</th>
                            <th style="width: 12%;">Data/Hora</th>
                            <th style="width: 24%;">Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($area->equipamentos as $equipamento)
                            @php
                                $teste = $equipamento->testes->first();
                            @endphp
                            <tr>
                                <td><strong>{{ $equipamento->nome }}</strong></td>
                                <td style="font-family: monospace;">{{ $equipamento->tag }}</td>
                                <td>
                                    @if($teste)
                                        @switch($teste->status)
                                            @case('ok')
                                                <span class="status-ok">OK</span>
                                                @break
                                            @case('problema')
                                                <span class="status-problema">PROBLEMA</span>
                                                @break
                                            @case('pendente')
                                                <span class="status-pendente">PENDENTE</span>
                                                @break
                                        @endswitch
                                    @else
                                        <span class="status-pendente">NÃO TESTADO</span>
                                    @endif
                                </td>
                                <td>{{ $teste->testado_por ?? '-' }}</td>
                                <td>
                                    @if($teste && $teste->updated_at)
                                        {{ $teste->updated_at->format('d/m H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($teste)
                                        @if($teste->problema_descricao)
                                            <strong>Problema:</strong> {{ Str::limit($teste->problema_descricao, 80) }}
                                            @if($teste->observacoes)<br>@endif
                                        @endif
                                        @if($teste->observacoes)
                                            <em>Obs:</em> {{ Str::limit($teste->observacoes, 80) }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>

        <!-- Problemas Identificados -->
        @php
            $problemasIdentificados = \App\Models\Teste::where('parada_id', $parada->id)
                ->where('status', 'problema')
                ->with('equipamento.area')
                ->get();
        @endphp

        @if($problemasIdentificados->count() > 0)
        <div class="section problems-section">
            <div class="problems-header">
                ⚠️ PROBLEMAS IDENTIFICADOS ({{ $problemasIdentificados->count() }})
            </div>
            
            <table class="equipment-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Área</th>
                        <th style="width: 20%;">Equipamento</th>
                        <th style="width: 10%;">Tag</th>
                        <th style="width: 35%;">Descrição do Problema</th>
                        <th style="width: 12%;">Identificado Por</th>
                        <th style="width: 8%;">Data/Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($problemasIdentificados as $problema)
                    <tr>
                        <td><strong>{{ $problema->equipamento->area->nome }}</strong></td>
                        <td>{{ $problema->equipamento->nome }}</td>
                        <td style="font-family: monospace;">{{ $problema->equipamento->tag }}</td>
                        <td>{{ $problema->problema_descricao }}</td>
                        <td>{{ $problema->testado_por ?? 'Não informado' }}</td>
                        <td>{{ $problema->updated_at->format('d/m H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Seção de Assinaturas -->
        <div class="signatures page-break">
            <div class="section-header">Validações e Assinaturas</div>
            
            <div class="signature-row">
                <div class="signature-box">
                    <div class="signature-label">Responsável pela Execução:</div>
                    <div style="margin: 10px 0;">{{ $parada->equipe_responsavel ?? 'Não informado' }}</div>
                    <div class="signature-line"></div>
                    <div class="signature-text">Assinatura e Data</div>
                </div>
                <div class="signature-box">
                    <div class="signature-label">Supervisor Responsável:</div>
                    <div style="margin: 10px 0;">_________________________</div>
                    <div class="signature-line"></div>
                    <div class="signature-text">Assinatura e Data</div>
                </div>
            </div>
            
            <div class="signature-row">
                <div class="signature-box">
                    <div class="signature-label">Engenheiro de Manutenção:</div>
                    <div style="margin: 10px 0;">_________________________</div>
                    <div class="signature-line"></div>
                    <div class="signature-text">Assinatura e Data</div>
                </div>
                <div class="signature-box">
                    <div class="signature-label">Gerente de Produção:</div>
                    <div style="margin: 10px 0;">_________________________</div>
                    <div class="signature-line"></div>
                    <div class="signature-text">Assinatura e Data</div>
                </div>
            </div>
            
            <div class="observations">
                <div class="signature-label">Observações Gerais:</div>
                <div class="obs-box">
                    <small style="color: #666;">Espaço para observações adicionais, comentários ou ressalvas sobre a execução da parada de manutenção.</small>
                </div>
            </div>
        </div>

        <!-- Rodapé -->
        <div style="text-align: center; margin-top: 40px; font-size: 10pt; border-top: 1px solid #000; padding-top: 10px;">
            Relatório de Parada {{ $parada->macro }} - Sistema de Checklist - {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        // Auto-focus na impressão se vier de um parâmetro
        if (window.location.search.includes('print=1')) {
            window.print();
        }
    </script>
</body>
</html>