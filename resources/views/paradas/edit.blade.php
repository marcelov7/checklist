@extends('layouts.app')

@section('title', 'Editar Parada - ' . $parada->macro)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Editar Parada</h1>
    <div>
        <a href="{{ route('paradas.show', $parada) }}" class="btn btn-outline-primary me-2">
            <i class="fas fa-eye"></i> Visualizar
        </a>
        <a href="{{ route('paradas.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('paradas.update', $parada) }}" method="POST" id="formEditarParada">
    @csrf
    @method('PUT')
    
    <!-- Etapa 1: Informações Básicas -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações Básicas da Parada</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="macro" class="form-label">Código da Parada (Macro) *</label>
                        <input type="text" 
                               class="form-control @error('macro') is-invalid @enderror" 
                               id="macro" 
                               name="macro" 
                               value="{{ old('macro', $parada->macro) }}" 
                               placeholder="Ex: PAR001-2025, PREV-OUT-25, etc."
                               required>
                        @error('macro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Código único para identificar esta parada</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Parada *</label>
                        <input type="text" 
                               class="form-control @error('nome') is-invalid @enderror" 
                               id="nome" 
                               name="nome" 
                               value="{{ old('nome', $parada->nome) }}" 
                               placeholder="Ex: Manutenção Preventiva Outubro"
                               required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" 
                                  name="descricao" 
                                  rows="3" 
                                  placeholder="Descrição detalhada da parada...">{{ old('descricao', $parada->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-12 col-lg-4">
                    <div class="mb-3">
                        <label for="data_inicio" class="form-label">Data/Hora de Início *</label>
                        <input type="datetime-local" 
                               class="form-control @error('data_inicio') is-invalid @enderror" 
                               id="data_inicio" 
                               name="data_inicio" 
                               value="{{ old('data_inicio', \Carbon\Carbon::parse($parada->data_inicio)->format('Y-m-d\TH:i')) }}"
                               style="font-size: 16px; min-height: 44px;"
                               required>
                        @error('data_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="mb-3">
                        <label for="duracao_prevista_horas" class="form-label">Duração Prevista (horas)</label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('duracao_prevista_horas') is-invalid @enderror" 
                                   id="duracao_prevista_horas" 
                                   name="duracao_prevista_horas" 
                                   value="{{ old('duracao_prevista_horas', $parada->duracao_prevista_horas) }}" 
                                   min="1" 
                                   max="720"
                                   style="font-size: 16px; min-height: 44px;"
                                   placeholder="Ex: 8">
                            <span class="input-group-text">h</span>
                            @error('duracao_prevista_horas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Parada *</label>
                        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required style="font-size: 16px; min-height: 44px;">
                            <option value="">Selecione o tipo de parada</option>
                            <option value="programada" {{ old('tipo', $parada->tipo) == 'programada' ? 'selected' : '' }}>📅 Programada</option>
                            <option value="preventiva" {{ old('tipo', $parada->tipo) == 'preventiva' ? 'selected' : '' }}>🔧 Preventiva</option>
                            <option value="corretiva" {{ old('tipo', $parada->tipo) == 'corretiva' ? 'selected' : '' }}>🔨 Corretiva</option>
                            <option value="emergencial" {{ old('tipo', $parada->tipo) == 'emergencial' ? 'selected' : '' }}>🚨 Emergencial</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="em_andamento" {{ old('status', $parada->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ old('status', $parada->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="cancelada" {{ old('status', $parada->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="equipe_responsavel" class="form-label">Equipe Responsável</label>
                        <input type="text" 
                               class="form-control @error('equipe_responsavel') is-invalid @enderror" 
                               id="equipe_responsavel" 
                               name="equipe_responsavel" 
                               value="{{ old('equipe_responsavel', $parada->equipe_responsavel) }}" 
                               placeholder="Ex: João Silva (Coordenador), Maria Santos (Técnica)">
                        @error('equipe_responsavel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Etapa 2: Resumo dos Equipamentos -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-cogs"></i> Equipamentos da Parada</h5>
            <a href="{{ route('paradas.select-equipment', $parada) }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i> Modificar Seleção
            </a>
        </div>
        <div class="card-body">
            @php
                $testes = $parada->testes()->with('equipamento.area')->get();
                $equipamentosPorArea = $testes->groupBy('equipamento.area.nome');
            @endphp
            
            @if($equipamentosPorArea->count() > 0)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-primary mb-1">{{ $equipamentosPorArea->count() }}</h4>
                            <small class="text-muted">Áreas envolvidas</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-success mb-1">{{ $testes->count() }}</h4>
                            <small class="text-muted">Equipamentos</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-info mb-1">{{ $testes->where('status', 'concluido')->count() }}/{{ $testes->count() }}</h4>
                            <small class="text-muted">Testes concluídos</small>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                @foreach($equipamentosPorArea as $nomeArea => $testesArea)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-map-marked-alt"></i> {{ $nomeArea }}
                            <span class="badge bg-secondary ms-2">{{ $testesArea->count() }} equipamentos</span>
                        </h6>
                        
                        <div class="row">
                            @foreach($testesArea as $teste)
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="card border-start border-3 {{ $teste->status == 'concluido' ? 'border-success' : ($teste->status == 'em_andamento' ? 'border-warning' : 'border-secondary') }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-cog text-secondary me-2"></i>
                                                <div class="flex-grow-1">
                                                    <strong>{{ $teste->equipamento->tag }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $teste->equipamento->nome }}</small>
                                                </div>
                                                <span class="badge bg-{{ $teste->status == 'concluido' ? 'success' : ($teste->status == 'em_andamento' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $teste->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-warning fa-2x mb-3"></i>
                    <h5>Nenhum equipamento selecionado</h5>
                    <p class="text-muted mb-3">Esta parada ainda não possui equipamentos associados.</p>
                    <a href="{{ route('paradas.select-equipment', $parada) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Selecionar Equipamentos
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="row">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success btn-lg me-3">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
            <a href="{{ route('paradas.show', $parada) }}" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validar formulário antes do envio
    document.getElementById('formEditarParada').addEventListener('submit', function(e) {
        const macro = document.getElementById('macro').value.trim();
        const nome = document.getElementById('nome').value.trim();
        const dataInicio = document.getElementById('data_inicio').value;
        const tipo = document.getElementById('tipo').value;
        const status = document.getElementById('status').value;
        
        if (!macro || !nome || !dataInicio || !tipo || !status) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
            return false;
        }
    });
    
    // Atualizar status baseado no progresso se necessário
    const statusSelect = document.getElementById('status');
    statusSelect.addEventListener('change', function() {
        if (this.value === 'concluida') {
            if (!confirm('Tem certeza que deseja marcar esta parada como concluída? Esta ação não pode ser desfeita facilmente.')) {
                this.value = '{{ $parada->status }}';
            }
        }
    });
});
</script>
@endsection