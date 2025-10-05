@extends('layouts.app')

@section('title', 'Editar Equipamento')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-2 mb-sm-0"><i class="fas fa-edit"></i> Editar Equipamento</h1>
    <a href="{{ route('equipamentos.index') }}" class="btn btn-secondary btn-lg">
        <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline">Voltar</span>
    </a>
</div>

<div class="row">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informações do Equipamento</h5>
            </div>
            <div class="card-body">
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

                <form action="{{ route('equipamentos.update', $equipamento) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Equipamento *</label>
                        <input type="text" 
                               class="form-control @error('nome') is-invalid @enderror" 
                               id="nome" 
                               name="nome" 
                               value="{{ old('nome', $equipamento->nome) }}" 
                               required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tag" class="form-label">TAG *</label>
                        <input type="text" 
                               class="form-control @error('tag') is-invalid @enderror" 
                               id="tag" 
                               name="tag" 
                               value="{{ old('tag', $equipamento->tag) }}" 
                               placeholder="Ex: BOMB-001, MOT-015, etc."
                               required>
                        @error('tag')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">TAG deve ser única no sistema</div>
                    </div>

                    <div class="mb-3">
                        <label for="area_id" class="form-label">Área *</label>
                        <select class="form-select @error('area_id') is-invalid @enderror" 
                                id="area_id" 
                                name="area_id" 
                                required>
                            <option value="">Selecione uma área</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id', $equipamento->area_id) == $area->id ? 'selected' : '' }}>
                                    {{ $area->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ativo" class="form-label">Status</label>
                        <select class="form-select @error('ativo') is-invalid @enderror" 
                                id="ativo" 
                                name="ativo">
                            <option value="1" {{ old('ativo', $equipamento->ativo) == 1 ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ old('ativo', $equipamento->ativo) == 0 ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('ativo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Equipamentos inativos não aparecem nos checklists</div>
                    </div>

                    <div class="mb-4">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" 
                                  name="descricao" 
                                  rows="4">{{ old('descricao', $equipamento->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                        <a href="{{ route('equipamentos.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Equipamento:</strong> {{ $equipamento->nome }}
                </div>
                <div class="mb-3">
                    <strong>TAG Atual:</strong> <code>{{ $equipamento->tag }}</code>
                </div>
                <div class="mb-3">
                    <strong>Área:</strong> {{ $equipamento->area->nome ?? 'N/A' }}
                </div>
                <div class="mb-3">
                    <strong>Status:</strong> 
                    <span class="badge {{ $equipamento->ativo ? 'bg-success' : 'bg-secondary' }}">
                        {{ $equipamento->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Criado em:</strong> {{ $equipamento->created_at->format('d/m/Y H:i') }}
                </div>
                @if($equipamento->updated_at != $equipamento->created_at)
                <div class="mb-3">
                    <strong>Última alteração:</strong> {{ $equipamento->updated_at->format('d/m/Y H:i') }}
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Dicas</h5>
            </div>
            <div class="card-body">
                <ul class="text-muted">
                    <li>Altere a TAG com cuidado para não afetar paradas em andamento</li>
                    <li>Equipamentos inativos são removidos dos próximos checklists</li>
                    <li>A área define onde o equipamento aparece nos relatórios</li>
                    <li>Mantenha a descrição atualizada para facilitar identificação</li>
                </ul>
            </div>
        </div>
        
        @if(isset($equipamento->testes) && $equipamento->testes->count() > 0)
            <div class="card mt-3">
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i>
                        Este equipamento possui <strong>{{ $equipamento->testes->count() }}</strong> teste(s) associado(s).
                        <br><br>
                        <small>Mudanças no status podem afetar paradas ativas.</small>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection