@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-3 mb-sm-0">
        <i class="fas fa-user-plus"></i> Novo Usuário
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>Informações do Usuário
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('usuarios.store') }}">
                    @csrf
                    
                    <!-- Informações Básicas -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome Completo *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username') }}" 
                                   placeholder="Ex: joao.silva">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Opcional. Se preenchido, deve ser único.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <!-- Espaço para manter layout -->
                        </div>
                    </div>
                    
                    <!-- Senha -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Senha *</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mínimo de 6 caracteres</div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmar Senha *</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                        </div>
                    </div>
                    
                    <!-- Perfil e Status -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="perfil" class="form-label">Perfil *</label>
                            <select class="form-select @error('perfil') is-invalid @enderror" 
                                    id="perfil" 
                                    name="perfil" 
                                    required>
                                <option value="">Selecione um perfil</option>
                                <option value="admin" {{ old('perfil') == 'admin' ? 'selected' : '' }}>
                                    Administrador
                                </option>
                                <option value="operador" {{ old('perfil') == 'operador' ? 'selected' : '' }}>
                                    Operador
                                </option>
                                <option value="manutencao" {{ old('perfil') == 'manutencao' ? 'selected' : '' }}>
                                    Manutenção
                                </option>
                            </select>
                            @error('perfil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="ativo" {{ old('status', 'ativo') == 'ativo' ? 'selected' : '' }}>
                                    Ativo
                                </option>
                                <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>
                                    Inativo
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Informações Complementares -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="departamento" class="form-label">Departamento</label>
                            <input type="text" 
                                   class="form-control @error('departamento') is-invalid @enderror" 
                                   id="departamento" 
                                   name="departamento" 
                                   value="{{ old('departamento') }}" 
                                   placeholder="Ex: Manutenção, Operação...">
                            @error('departamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" 
                                   class="form-control @error('telefone') is-invalid @enderror" 
                                   id="telefone" 
                                   name="telefone" 
                                   value="{{ old('telefone') }}" 
                                   placeholder="(11) 99999-9999">
                            @error('telefone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Descrição dos Perfis -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Descrição dos Perfis:</h6>
                        <ul class="mb-0">
                            <li><strong>Administrador:</strong> Acesso total ao sistema, pode gerenciar usuários e configurações</li>
                            <li><strong>Operador:</strong> Pode criar e gerenciar paradas, visualizar relatórios</li>
                            <li><strong>Manutenção:</strong> Pode executar testes e atualizar status das paradas</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Criar Usuário
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection