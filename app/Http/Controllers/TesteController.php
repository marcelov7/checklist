<?php

namespace App\Http\Controllers;

use App\Models\Teste;
use App\Models\Parada;
use App\Models\Equipamento;
use Illuminate\Http\Request;

class TesteController extends Controller
{
    /**
     * Criar novo teste
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parada_id' => 'required|exists:paradas,id',
            'equipamento_id' => 'required|exists:equipamentos,id',
            'status' => 'required|in:pendente,ok,problema',
            'observacoes' => 'nullable|string',
            'problema_descricao' => 'required_if:status,problema|string|nullable',
            'testado_por' => 'nullable|string|max:255',
        ]);

        $validated['data_teste'] = now();

        $teste = Teste::create($validated);

        $testeCompleto = $teste->fresh()->load('equipamento');
        
        return response()->json([
            'success' => true,
            'message' => 'Teste criado com sucesso!',
            'teste' => [
                'id' => $testeCompleto->id,
                'status' => $testeCompleto->status,
                'status_label' => $testeCompleto->status_label,
                'status_color' => $testeCompleto->status_color,
                'observacoes' => $testeCompleto->observacoes,
                'problema_descricao' => $testeCompleto->problema_descricao,
                'testado_por' => $testeCompleto->testado_por,
            ]
        ]);
    }

    /**
     * Atualizar status do teste
     */
    public function update(Request $request, Teste $teste)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendente,ok,problema',
            'observacoes' => 'nullable|string',
            'problema_descricao' => 'required_if:status,problema|string|nullable',
            'testado_por' => 'nullable|string|max:255',
        ]);

        $validated['data_teste'] = now();

        $teste->update($validated);
        
        $testeCompleto = $teste->fresh()->load('equipamento');

        return response()->json([
            'success' => true,
            'message' => 'Teste atualizado com sucesso!',
            'teste' => [
                'id' => $testeCompleto->id,
                'status' => $testeCompleto->status,
                'status_label' => $testeCompleto->status_label,
                'status_color' => $testeCompleto->status_color,
                'observacoes' => $testeCompleto->observacoes,
                'problema_descricao' => $testeCompleto->problema_descricao,
                'testado_por' => $testeCompleto->testado_por,
            ]
        ]);
    }

    /**
     * Buscar teste específico para AJAX
     */
    public function show(Teste $teste)
    {
        return response()->json([
            'teste' => $teste->load(['equipamento', 'parada'])
        ]);
    }

    /**
     * Atualizar múltiplos testes via AJAX
     */
    public function updateMultiple(Request $request)
    {
        $validated = $request->validate([
            'testes' => 'required|array',
            'testes.*.id' => 'required|exists:testes,id',
            'testes.*.status' => 'required|in:pendente,ok,problema',
            'testes.*.observacoes' => 'nullable|string',
            'testes.*.problema_descricao' => 'nullable|string',
            'testado_por' => 'nullable|string|max:255',
        ]);

        $testesAtualizados = [];

        foreach ($validated['testes'] as $testeData) {
            $teste = Teste::findOrFail($testeData['id']);
            
            $updateData = [
                'status' => $testeData['status'],
                'observacoes' => $testeData['observacoes'] ?? null,
                'problema_descricao' => $testeData['problema_descricao'] ?? null,
                'data_teste' => now(),
                'testado_por' => $validated['testado_por'] ?? null,
            ];

            $teste->update($updateData);
            $testesAtualizados[] = $teste->fresh()->load('equipamento');
        }

        return response()->json([
            'success' => true,
            'message' => 'Testes atualizados com sucesso!',
            'testes' => $testesAtualizados
        ]);
    }

    /**
     * Atualizar status de item específico do checklist
     */
    public function atualizarChecklistStatus(Request $request, Teste $teste)
    {
        $validated = $request->validate([
            'item' => 'required|in:ar_comprimido,protecoes_eletricas,protecoes_mecanicas,chave_remoto,inspecionado',
            'status' => 'required|in:ok,problema,nao_aplica'
        ]);

        $statusField = $validated['item'] . '_status';
        
        $teste->update([
            $statusField => $validated['status']
        ]);

        // Recarregar o teste para obter o progresso atualizado
        $teste->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Status do checklist atualizado com sucesso!',
            'equipamento_id' => $teste->equipamento_id,
            'progress' => $teste->checklist_progress
        ]);
    }

    /**
     * Salvar descrição de problema do checklist
     */
    public function salvarProblema(Request $request, Teste $teste)
    {
        // Verificar se a parada está finalizada
        if ($teste->parada->status === 'concluida') {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível salvar problema. A parada já foi finalizada.'
            ], 403);
        }
        
        // Debug detalhado
        \Illuminate\Support\Facades\Log::info('=== SALVAR PROBLEMA ===');
        \Illuminate\Support\Facades\Log::info('Request method:', [$request->method()]);
        \Illuminate\Support\Facades\Log::info('Request all data:', $request->all());
        \Illuminate\Support\Facades\Log::info('Files:', $request->allFiles());
        \Illuminate\Support\Facades\Log::info('Teste ID:', [$teste->id]);

        try {
            $validated = $request->validate([
                'item' => 'required|in:ar_comprimido,protecoes_eletricas,protecoes_mecanicas,chave_remoto,inspecionado',
                'problema' => 'required|string|min:3',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240' // Máximo 10MB para a foto
            ]);

            \Illuminate\Support\Facades\Log::info('Dados validados:', $validated);

            $problemaField = $validated['item'] . '_problema';
            $updateData = [$problemaField => $validated['problema']];
            
            \Illuminate\Support\Facades\Log::info('Campo problema:', [$problemaField]);
            \Illuminate\Support\Facades\Log::info('Update data inicial:', $updateData);
            
            // Se foi enviada uma foto
            if ($request->hasFile('foto')) {
                \Illuminate\Support\Facades\Log::info('Foto encontrada, processando...');
                $foto = $request->file('foto');
                $nomeArquivo = time() . '_' . $validated['item'] . '_problema_' . $teste->id . '.' . $foto->getClientOriginalExtension();
                $caminhoFoto = $foto->storeAs('problemas', $nomeArquivo, 'public');
                
                // Salvar foto no campo específico do item
                $fotoField = $validated['item'] . '_foto_problema';
                $updateData[$fotoField] = $caminhoFoto;
                \Illuminate\Support\Facades\Log::info('Foto salva em:', [$caminhoFoto]);
                \Illuminate\Support\Facades\Log::info('Campo foto:', [$fotoField]);
            } else {
                \Illuminate\Support\Facades\Log::info('Nenhuma foto enviada');
            }
            
            \Illuminate\Support\Facades\Log::info('Update data final:', $updateData);
            
            // Debug: Verificar estado antes do update
            \Illuminate\Support\Facades\Log::info('Teste ANTES do update - campo foto:', [$teste->{$validated['item'] . '_foto_problema'}]);
            
            // Usar query builder direto para garantir que o update funcione
            $updateResult = \Illuminate\Support\Facades\DB::table('testes')
                ->where('id', $teste->id)
                ->update($updateData);
            \Illuminate\Support\Facades\Log::info('Resultado do update (query builder):', [$updateResult]);
            
            // Recarregar o modelo após update direto
            $teste->refresh();
            
            // Debug: Verificar estado imediatamente após update
            \Illuminate\Support\Facades\Log::info('Teste APÓS update - campo foto:', [$teste->{$validated['item'] . '_foto_problema'}]);
            
            // Forçar nova consulta no banco para garantir que os dados estejam atualizados
            $testeAtualizado = \App\Models\Teste::find($teste->id);
            \Illuminate\Support\Facades\Log::info('Teste atualizado com sucesso');

            // Obter o caminho da foto do campo correto do banco
            $fotoField = $validated['item'] . '_foto_problema';
            $fotoPath = $testeAtualizado->{$fotoField};
            
            \Illuminate\Support\Facades\Log::info('Foto field:', [$fotoField]);
            \Illuminate\Support\Facades\Log::info('Foto path do banco:', [$fotoPath]);
            \Illuminate\Support\Facades\Log::info('Dados completos do teste:', [$testeAtualizado->toArray()]);

            // Obter dados atualizados da parada para retornar progresso completo
            $parada = $testeAtualizado->parada;
            $parada->refresh();
            
            $percentualGeral = $parada->getPercentualCompleto();
            $percentualPorArea = $parada->getPercentualPorArea();
            
            $totalTestes = $parada->total_testes;
            $testesOk = $parada->testes_ok;
            $testesPendentes = max(0, $totalTestes - $testesOk);

            return response()->json([
                'success' => true,
                'message' => 'Problema salvo com sucesso!',
                'equipamento_id' => $testeAtualizado->equipamento_id,
                'progress' => $testeAtualizado->checklist_progress,
                'problema' => $validated['problema'],
                'foto_problema_path' => $fotoPath,
                'teste_atualizado' => $testeAtualizado->toArray(),
                'percentual' => $percentualGeral,
                'areas' => $percentualPorArea,
                'total_testes' => $totalTestes,
                'testes_ok' => $testesOk,
                'testes_pendentes' => $testesPendentes,
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Erro de validação:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao salvar problema:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resolver problema do checklist
     */
    public function resolverProblema(Request $request, Teste $teste)
    {
        // Verificar se a parada está finalizada
        if ($teste->parada->status === 'concluida') {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível resolver problema. A parada já foi finalizada.'
            ], 403);
        }
        
        // Debug detalhado
        \Illuminate\Support\Facades\Log::info('=== RESOLVER PROBLEMA ===');
        \Illuminate\Support\Facades\Log::info('Request method:', [$request->method()]);
        \Illuminate\Support\Facades\Log::info('Request all:', $request->all());
        \Illuminate\Support\Facades\Log::info('Request input item:', [$request->input('item')]);
        \Illuminate\Support\Facades\Log::info('Request input resolucao:', [$request->input('resolucao')]);
        \Illuminate\Support\Facades\Log::info('Has file foto:', [$request->hasFile('foto')]);
        
        try {
            // Se um arquivo foi enviado, validar se há erro no upload (ex: excedeu upload_max_filesize)
            if ($request->hasFile('foto')) {
                $uploaded = $request->file('foto');
                if (!$uploaded->isValid()) {
                    $code = $uploaded->getError();
                    switch ($code) {
                        case UPLOAD_ERR_INI_SIZE:
                            $msg = 'Arquivo maior do que o permitido pelo servidor (upload_max_filesize).';
                            break;
                        case UPLOAD_ERR_FORM_SIZE:
                            $msg = 'Arquivo maior do que o permitido pelo formulário (MAX_FILE_SIZE).';
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $msg = 'Upload incompleto. Tente novamente.';
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $msg = 'Nenhum arquivo foi enviado.';
                            break;
                        case UPLOAD_ERR_NO_TMP_DIR:
                            $msg = 'Diretório temporário ausente no servidor.';
                            break;
                        case UPLOAD_ERR_CANT_WRITE:
                            $msg = 'Falha ao gravar o arquivo no disco.';
                            break;
                        case UPLOAD_ERR_EXTENSION:
                            $msg = 'Upload interrompido por uma extensão do PHP.';
                            break;
                        default:
                            $msg = 'Erro desconhecido no upload. Código: ' . $code;
                    }

                    \Illuminate\Support\Facades\Log::error('Erro no upload da foto de resolução: ' . $msg, ['code' => $code]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao enviar a foto: ' . $msg
                    ], 422);
                }
            }

            // Validação normal (aceita apenas imagens e tipos comuns)
            $validated = $request->validate([
                'item' => 'required|in:ar_comprimido,protecoes_eletricas,protecoes_mecanicas,chave_remoto,inspecionado',
                'resolucao' => 'required|string|min:1',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
            ]);

            \Illuminate\Support\Facades\Log::info('Validação passou:', $validated);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Mapear mensagens genéricas de upload para textos amigáveis
            $errors = $e->validator->errors()->all();
            $userMessages = array_map(function ($m) {
                if (strpos($m, 'validation.uploaded') !== false || strpos($m, 'uploaded') !== false) {
                    return 'Falha no upload do arquivo. Verifique o tamanho (máx 10MB) e o tipo da imagem.';
                }
                return $m;
            }, $errors);

            \Illuminate\Support\Facades\Log::error('Erro de validação na resolução:', [
                'errors' => $errors,
                'mapped' => $userMessages,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos: ' . implode(', ', $userMessages),
                'errors' => $errors
            ], 422);
        }

        $resolucaoField = $validated['item'] . '_resolucao';
        $statusField = $validated['item'] . '_status';
        $fotoResolucaoField = $validated['item'] . '_foto_resolucao';
        
        $updateData = [
            $resolucaoField => $validated['resolucao'],
            $statusField => 'ok' // Muda o status para OK quando resolvido
        ];
        
        // Se foi enviada uma foto da resolução
        if ($request->hasFile('foto')) {
            \Illuminate\Support\Facades\Log::info('Foto de resolução encontrada, processando...');
            $foto = $request->file('foto');
            $nomeArquivo = time() . '_resolucao_' . $validated['item'] . '_' . $teste->id . '.' . $foto->getClientOriginalExtension();
            $caminhoFoto = $foto->storeAs('resolucoes', $nomeArquivo, 'public');
            $updateData[$fotoResolucaoField] = $caminhoFoto;
            \Illuminate\Support\Facades\Log::info('Foto de resolução salva em:', [$caminhoFoto]);
        } else {
            \Illuminate\Support\Facades\Log::info('Nenhuma foto de resolução enviada');
        }
        
        try {
            // Usar query builder direto para garantir que o update funcione
            $updateResult = \Illuminate\Support\Facades\DB::table('testes')
                ->where('id', $teste->id)
                ->update($updateData);
            \Illuminate\Support\Facades\Log::info('Resultado do update resolução (query builder):', [$updateResult]);
            
            // Recarregar o modelo após update direto
            $teste->refresh();
            
            // Forçar nova consulta no banco para garantir que os dados estejam atualizados
            $testeAtualizado = \App\Models\Teste::find($teste->id);
            \Illuminate\Support\Facades\Log::info('Update data final resolução:', $updateData);
            \Illuminate\Support\Facades\Log::info('Foto resolução path final:', [$testeAtualizado->{$fotoResolucaoField}]);

            // Obter dados atualizados da parada para retornar progresso completo
            $parada = $testeAtualizado->parada;
            $parada->refresh();
            
            $percentualGeral = $parada->getPercentualCompleto();
            $percentualPorArea = $parada->getPercentualPorArea();
            
            $totalTestes = $parada->total_testes;
            $testesOk = $parada->testes_ok;
            $testesPendentes = max(0, $totalTestes - $testesOk);

            return response()->json([
                'success' => true,
                'message' => 'Problema resolvido com sucesso!',
                'equipamento_id' => $testeAtualizado->equipamento_id,
                'progress' => $testeAtualizado->checklist_progress,
                'resolucao' => $validated['resolucao'],
                'foto_resolucao_path' => $testeAtualizado->{$fotoResolucaoField},
                'teste_atualizado' => $testeAtualizado->toArray(),
                'percentual' => $percentualGeral,
                'areas' => $percentualPorArea,
                'total_testes' => $totalTestes,
                'testes_ok' => $testesOk,
                'testes_pendentes' => $testesPendentes,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao resolver problema: ' . $e->getMessage(), [
                'teste_id' => $teste->id,
                'item' => $validated['item'],
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor ao resolver problema.'
            ], 500);
        }
    }

    /**
     * Debug do teste
     */
    public function debug(Teste $teste)
    {
        return response()->json([
            'teste' => $teste,
            'checklist_items' => $teste->checklist_items,
            'progress' => $teste->checklist_progress,
            'raw_data' => [
                'ar_comprimido_foto_problema' => $teste->ar_comprimido_foto_problema,
                'ar_comprimido_foto_resolucao' => $teste->ar_comprimido_foto_resolucao,
                'protecoes_eletricas_foto_problema' => $teste->protecoes_eletricas_foto_problema,
                'protecoes_eletricas_foto_resolucao' => $teste->protecoes_eletricas_foto_resolucao,
                'protecoes_mecanicas_foto_problema' => $teste->protecoes_mecanicas_foto_problema,
                'protecoes_mecanicas_foto_resolucao' => $teste->protecoes_mecanicas_foto_resolucao,
                'chave_remoto_foto_problema' => $teste->chave_remoto_foto_problema,
                'chave_remoto_foto_resolucao' => $teste->chave_remoto_foto_resolucao,
                'inspecionado_foto_problema' => $teste->inspecionado_foto_problema,
                'inspecionado_foto_resolucao' => $teste->inspecionado_foto_resolucao,
            ]
        ]);
    }

    /**
     * Atualizar status de um item do checklist
     */
    public function atualizarStatus(Request $request, Teste $teste)
    {
        // Verificar se a parada está finalizada
        if ($teste->parada->status === 'concluida') {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível alterar status. A parada já foi finalizada.'
            ], 403);
        }
        
        try {
            $validated = $request->validate([
                'item' => 'required|in:ar_comprimido,protecoes_eletricas,protecoes_mecanicas,chave_remoto,inspecionado',
                'status' => 'required|in:pendente,ok,problema,nao_aplica'
            ]);

            $item = $validated['item'];
            $novoStatus = $validated['status'];
            
            // Campos a serem atualizados
            $updateData = [
                $item . '_status' => $novoStatus
            ];
            
            // Se mudou de 'problema' para outro status, limpar dados do problema
            if ($novoStatus !== 'problema') {
                $updateData[$item . '_problema'] = null;
                $updateData[$item . '_foto_problema'] = null;
            }
            
            // Se mudou para status diferente de 'problema', limpar resolução também
            if ($novoStatus !== 'problema') {
                $updateData[$item . '_resolucao'] = null;
                $updateData[$item . '_foto_resolucao'] = null;
            }
            
            // Log da operação
            \Illuminate\Support\Facades\Log::info("Alterando status do item {$item} para {$novoStatus}", [
                'teste_id' => $teste->id,
                'update_data' => $updateData
            ]);
            
            // Usar query builder direto para garantir que o update funcione
            \Illuminate\Support\Facades\DB::table('testes')
                ->where('id', $teste->id)
                ->update($updateData);

            // Recarregar o modelo
            $teste->refresh();

            // Obter dados atualizados da parada para retornar progresso completo
            $parada = $teste->parada;
            $parada->refresh();
            
            $percentualGeral = $parada->getPercentualCompleto();
            $percentualPorArea = $parada->getPercentualPorArea();
            
            $totalTestes = $parada->total_testes;
            $testesOk = $parada->testes_ok;
            $testesPendentes = max(0, $totalTestes - $testesOk);

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso!',
                'equipamento_id' => $teste->equipamento_id,
                'progress' => $teste->checklist_progress,
                'percentual' => $percentualGeral,
                'areas' => $percentualPorArea,
                'total_testes' => $totalTestes,
                'testes_ok' => $testesOk,
                'testes_pendentes' => $testesPendentes,
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao atualizar status:', [
                'error' => $e->getMessage(),
                'teste_id' => $teste->id ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }
}
