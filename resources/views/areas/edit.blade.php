@extends('layouts.app')

@section('title', 'Editar Área')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-2 mb-sm-0"><i class="fas fa-edit"></i> Editar Área</h1>
    <a href="{{ route('areas.index') }}" class="btn btn-secondary btn-lg">
        <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline">Voltar</span>
    </a>
</div>

<div class="row">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informações da Área</h5>
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

                <form action="{{ route('areas.update', $area) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Área *</label>
                        <input type="text" 
                               class="form-control @error('nome') is-invalid @enderror" 
                               id="nome" 
                               name="nome" 
                               value="{{ old('nome', $area->nome) }}" 
                               required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ativo" class="form-label">Status</label>
                        <select class="form-select @error('ativo') is-invalid @enderror" 
                                id="ativo" 
                                name="ativo">
                            <option value="1" {{ old('ativo', $area->ativo) == 1 ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ old('ativo', $area->ativo) == 0 ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('ativo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Áreas inativas não aparecem nos cadastros de equipamentos</div>
                    </div>

                    <div class="mb-4">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" 
                                  name="descricao" 
                                  rows="4">{{ old('descricao', $area->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                        <a href="{{ route('areas.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Área:</strong> {{ $area->nome }}
                </div>
                <div class="mb-3">
                    <strong>Status:</strong> 
                    <span class="badge {{ $area->ativo ? 'bg-success' : 'bg-secondary' }}">
                        {{ $area->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Equipamentos:</strong> {{ $area->equipamentos->count() }}
                </div>
                <div class="mb-3">
                    <strong>Criado em:</strong> {{ $area->created_at->format('d/m/Y H:i') }}
                </div>
                @if($area->updated_at != $area->created_at)
                <div class="mb-3">
                    <strong>Última alteração:</strong> {{ $area->updated_at->format('d/m/Y H:i') }}
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
                    <li>Áreas organizam os equipamentos no sistema</li>
                    <li>Áreas inativas não aparecem no cadastro de equipamentos</li>
                    <li>Equipamentos existentes não são afetados pelo status da área</li>
                    <li>Use nomes descritivos para facilitar a identificação</li>
                </ul>
            </div>
        </div>
        
        @if($area->equipamentos->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-tools"></i> Equipamentos nesta Área</h6>
                </div>
                <div class="card-body">
                    @foreach($area->equipamentos->take(5) as $equipamento)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $equipamento->nome }}</span>
                            <code class="small">{{ $equipamento->tag }}</code>
                        </div>
                    @endforeach
                    @if($area->equipamentos->count() > 5)
                        <div class="text-muted small">
                            ... e mais {{ $area->equipamentos->count() - 5 }} equipamento(s)
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection