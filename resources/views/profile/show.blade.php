@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-3 mb-sm-0">
        <i class="fas fa-user-circle"></i> Meu Perfil
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Editar Perfil
        </a>
    </div>
</div>

<div class="row">
    <!-- Informações do Perfil -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="avatar-xl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-grow-1">
                    <h3 class="mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    <span class="badge {{ $user->status == 'ativo' ? 'bg-success' : 'bg-secondary' }} mt-1">
                        {{ $user->status_display }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informações Pessoais</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="140"><strong>Nome:</strong></td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Departamento:</strong></td>
                                <td>{{ $user->departamento ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telefone:</strong></td>
                                <td>{{ $user->telefone ?? 'Não informado' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informações do Sistema</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="140"><strong>Perfil:</strong></td>
                                <td>
                                    <span class="badge 
                                        @if($user->perfil == 'admin') bg-danger
                                        @elseif($user->perfil == 'operador') bg-primary  
                                        @else bg-info
                                        @endif">
                                        {{ $user->perfil_display }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge {{ $user->status == 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $user->status_display }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Conta criada:</strong></td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Última atualização:</strong></td>
                                <td>{{ $user->updated_at->diffForHumans() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações e Informações -->
    <div class="col-lg-4">
        <!-- Ações Rápidas -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Editar Perfil
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-info">
                        <i class="fas fa-tachometer-alt me-2"></i>Ir para Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Permissões do Perfil -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user-cog me-2"></i>Suas Permissões
                </h6>
            </div>
            <div class="card-body">
                @if($user->perfil == 'admin')
                    <div class="alert alert-danger">
                        <i class="fas fa-crown me-2"></i>
                        <strong>Administrador</strong><br>
                        Você tem acesso total ao sistema.
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Acesso total ao sistema</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar usuários</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar relatórios</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Configurações do sistema</li>
                    </ul>
                @elseif($user->perfil == 'operador')
                    <div class="alert alert-primary">
                        <i class="fas fa-user-tie me-2"></i>
                        <strong>Operador</strong><br>
                        Você pode gerenciar paradas e relatórios.
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Criar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gerenciar paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar relatórios</li>
                        <li class="mb-0"><i class="fas fa-times text-muted me-2"></i>Gerenciar usuários</li>
                    </ul>
                @elseif($user->perfil == 'manutencao')
                    <div class="alert alert-info">
                        <i class="fas fa-tools me-2"></i>
                        <strong>Manutenção</strong><br>
                        Você pode executar testes e atualizar paradas.
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Executar testes</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Atualizar status das paradas</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar paradas</li>
                        <li class="mb-0"><i class="fas fa-times text-muted me-2"></i>Gerenciar usuários</li>
                    </ul>
                @endif
            </div>
        </div>

        <!-- Estatísticas Pessoais (Placeholder) -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Suas Estatísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center py-3">
                    <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Estatísticas pessoais<br>serão implementadas em breve.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-xl {
    width: 80px;
    height: 80px;
    font-size: 32px;
    font-weight: 600;
}
</style>
@endsection