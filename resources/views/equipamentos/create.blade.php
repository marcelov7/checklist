@extends('layouts.app')

@section('title', 'Novo Equipamento')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-2 mb-sm-0"><i class="fas fa-plus"></i> Novo Equipamento</h1>
    <a href="{{ route('equipamentos.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('equipamentos.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Equipamento *</label>
                        <input type="text" 
                               class="form-control @error('nome') is-invalid @enderror" 
                               id="nome" 
                               name="nome" 
                               value="{{ old('nome') }}" 
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
                               value="{{ old('tag') }}" 
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
                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" 
                                  name="descricao" 
                                  rows="4">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Equipamento
                        </button>
                        <a href="{{ route('equipamentos.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Dicas</h5>
            </div>
            <div class="card-body">
                <ul class="text-muted">
                    <li>Use TAGs descritivas e únicas</li>
                    <li>Organize por área para facilitar o checklist</li>
                    <li>A descrição ajuda na identificação durante os testes</li>
                    <li>Equipamentos inativos não aparecem nos checklists</li>
                </ul>
            </div>
        </div>
        
        @if($areas->count() == 0)
            <div class="card mt-3">
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Você precisa criar pelo menos uma área antes de cadastrar equipamentos.
                        <br><br>
                        <a href="{{ route('areas.create') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-plus"></i> Criar Área
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection