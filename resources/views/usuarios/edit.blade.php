@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-3 mb-sm-0">
        <i class="fas fa-user-edit"></i> Editar Usuário
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-outline-info">
            <i class="fas fa-eye me-2"></i>Visualizar
        </a>
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                    {{ strtoupper(substr($usuario->name, 0, 1)) }}
                </div>
                <div>
                    <h5 class="mb-0">{{ $usuario->name }}</h5>
                    <small class="text-muted">{{ $usuario->email }}</small>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informações Básicas -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome Completo *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $usuario->name) }}" 
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
                                   value="{{ old('username', $usuario->username) }}" 
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
                                   value="{{ old('email', $usuario->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <!-- Espaço para manter layout -->
                        </div>
                    </div>
                    
                    <!-- Nova Senha (Opcional) -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="change_password" onchange="togglePasswordFields()">
                                <label class="form-check-label" for="change_password">
                                    Alterar senha
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="password_fields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Nova Senha</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Mínimo de 6 caracteres</div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                            </div>
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
                                <option value="admin" {{ old('perfil', $usuario->perfil) == 'admin' ? 'selected' : '' }}>
                                    Administrador
                                </option>
                                <option value="operador" {{ old('perfil', $usuario->perfil) == 'operador' ? 'selected' : '' }}>
                                    Operador
                                </option>
                                <option value="manutencao" {{ old('perfil', $usuario->perfil) == 'manutencao' ? 'selected' : '' }}>
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
                                <option value="ativo" {{ old('status', $usuario->status) == 'ativo' ? 'selected' : '' }}>
                                    Ativo
                                </option>
                                <option value="inativo" {{ old('status', $usuario->status) == 'inativo' ? 'selected' : '' }}>
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
                                   value="{{ old('departamento', $usuario->departamento) }}" 
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
                                   value="{{ old('telefone', $usuario->telefone) }}" 
                                   placeholder="(11) 99999-9999">
                            @error('telefone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Informações do Sistema -->
                    <div class="alert alert-light">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-plus me-1"></i>
                                    Criado em: {{ $usuario->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-check me-1"></i>
                                    Última atualização: {{ $usuario->updated_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordFields() {
    const checkbox = document.getElementById('change_password');
    const passwordFields = document.getElementById('password_fields');
    const passwordInput = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    if (checkbox.checked) {
        passwordFields.style.display = 'block';
        passwordInput.required = true;
        passwordConfirmation.required = true;
    } else {
        passwordFields.style.display = 'none';
        passwordInput.required = false;
        passwordConfirmation.required = false;
        passwordInput.value = '';
        passwordConfirmation.value = '';
    }
}
</script>

<style>
.avatar-md {
    width: 50px;
    height: 50px;
    font-size: 20px;
    font-weight: 600;
}
</style>
@endsection