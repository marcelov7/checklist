@extends('layouts.app')

@section('title', 'Visualizar Usuário')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-3 mb-sm-0">
        <i class="fas fa-user"></i> Visualizar Usuário
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <!-- Informações do Usuário -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                    {{ strtoupper(substr($usuario->name, 0, 1)) }}
                </div>
                <div class="flex-grow-1">
                    <h4 class="mb-1">{{ $usuario->name }}</h4>
                    <p class="text-muted mb-0">{{ $usuario->email }}</p>
                </div>
                <div>
                    <span class="badge {{ $usuario->status == 'ativo' ? 'bg-success' : 'bg-secondary' }} fs-6">
                        {{ $usuario->status_display }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Informações Básicas</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="120"><strong>Nome:</strong></td>
                                <td>{{ $usuario->name }}</td>
                            </tr>
                            @if($usuario->username)
                            <tr>
                                <td><strong>Username:</strong></td>
                                <td><code>{{ $usuario->username }}</code></td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $usuario->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Perfil:</strong></td>
                                <td>
                                    <span class="badge 
                                        @if($usuario->perfil == 'admin') bg-danger
                                        @elseif($usuario->perfil == 'operador') bg-primary  
                                        @else bg-info
                                        @endif">
                                        {{ $usuario->perfil_display }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge {{ $usuario->status == 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $usuario->status_display }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Informações Complementares</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="120"><strong>Departamento:</strong></td>
                                <td>{{ $usuario->departamento ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telefone:</strong></td>
                                <td>{{ $usuario->telefone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Criado em:</strong></td>
                                <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Atualizado em:</strong></td>
                                <td>{{ $usuario->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atividades Recentes (se necessário no futuro) -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Atividades Recentes
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Histórico de atividades será implementado em breve.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Editar Usuário
                    </a>
                    
                    @if($usuario->id !== session('user.id'))
                        <form method="POST" action="{{ route('usuarios.toggle-status', $usuario) }}" class="d-grid">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn {{ $usuario->status == 'ativo' ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                <i class="fas {{ $usuario->status == 'ativo' ? 'fa-user-slash' : 'fa-user-check' }} me-2"></i>
                                {{ $usuario->status == 'ativo' ? 'Desativar' : 'Ativar' }} Usuário
                            </button>
                        </form>
                        
                        <hr>
                        
                        <form method="POST" 
                              action="{{ route('usuarios.destroy', $usuario) }}" 
                              class="d-grid" 
                              onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i>Excluir Usuário
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Este é o seu perfil. Use a opção "Meu Perfil" para fazer alterações.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informações do Perfil -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user-cog me-2"></i>Permissões do Perfil
                </h6>
            </div>
            <div class="card-body">
                @if($usuario->perfil == 'admin')
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Acesso total ao sistema</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar usuários</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar relatórios</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Configurações do sistema</li>
                    </ul>
                @elseif($usuario->perfil == 'operador')
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Criar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar relatórios</li>
                        <li class="mb-0"><i class="fas fa-times text-muted me-2"></i>Gerenciar usuários</li>
                    </ul>
                @elseif($usuario->perfil == 'manutencao')
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Executar testes</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Atualizar status</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar paradas</li>
                        <li class="mb-0"><i class="fas fa-times text-muted me-2"></i>Gerenciar usuários</li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 60px;
    height: 60px;
    font-size: 24px;
    font-weight: 600;
}
</style>
@endsection