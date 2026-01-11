@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('content')
<!-- Debug temporário -->
@if(config('app.debug'))
<div class="alert alert-info">
    <strong>Debug:</strong> Total de usuários encontrados: {{ $users->total() }} | Na página atual: {{ $users->count() }}
</div>
@endif

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
    <h1 class="mb-3 mb-sm-0">
        <i class="fas fa-users"></i> Gerenciar Usuários
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo Usuário
        </a>
    </div>
</div>

<!-- Lista de Usuários -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Lista de Usuários
            <span class="badge bg-secondary ms-2">{{ $users->total() }}</span>
        </h5>
    </div>

    <!-- Filtros -->
    <div class="border-bottom">
        <!-- Mobile Filter Toggle -->
        <div class="d-md-none d-flex align-items-center p-2" onclick="toggleMobileFilters()">
            <div class="d-flex align-items-center flex-grow-1">
                <i class="fas fa-filter me-2"></i>
                <span>Filtros</span>
                @if(request()->anyFilled(['perfil', 'status', 'search']))
                    <span class="badge bg-primary ms-2">Filtros ativos</span>
                @endif
            </div>
            <i class="fas fa-chevron-down" id="filterToggleIcon"></i>
        </div>
        
        <div class="p-1" id="filterContent">
            <form method="GET" action="{{ route('usuarios.index') }}" class="row g-1">
                <div class="col-12 col-md-3">
                    <label for="perfil" class="form-label small mb-0">Perfil</label>
                    <select class="form-select form-select-sm py-0" name="perfil" id="perfil">
                        <option value="">Todos os perfis</option>
                        @foreach($perfis as $value => $label)
                            <option value="{{ $value }}" {{ request('perfil') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="status" class="form-label small mb-0">Status</label>
                    <select class="form-select form-select-sm py-0" name="status" id="status">
                        <option value="">Todos os status</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label for="search" class="form-label small mb-0">Buscar</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control py-0" name="search" id="search" 
                               placeholder="Nome, email ou departamento..." value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('usuarios.index', array_merge(request()->except('search'), ['page' => 1])) }}" 
                               class="btn btn-outline-secondary py-0" title="Limpar busca">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small mb-0 d-block">&nbsp;</label>
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm py-0 flex-grow-1">
                            <i class="fas fa-search"></i> <span class="d-none d-sm-inline">Filtrar</span>
                        </button>
                        @if(request()->anyFilled(['perfil', 'status', 'search']))
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary btn-sm py-0" title="Limpar filtros">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card-body">
        @if($users->count() > 0)
            <!-- Desktop Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Departamento</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users->count() > 0)
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->telefone)
                                                <br><small class="text-muted">{{ $user->telefone }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge 
                                        @if($user->perfil == 'admin') bg-danger
                                        @elseif($user->perfil == 'operador') bg-primary  
                                        @else bg-info
                                        @endif">
                                        {{ $user->perfil_display }}
                                    </span>
                                </td>
                                <td>{{ $user->departamento ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $user->status == 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $user->status_display }}
                                    </span>
                                </td>
                                <td>
                                    {{ $user->created_at->format('d/m/Y') }}
                                    <br><small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('usuarios.show', $user) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('usuarios.edit', $user) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if($user->id !== session('user.id'))
                                            <form method="POST" action="{{ route('usuarios.toggle-status', $user) }}" 
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm {{ $user->status == 'ativo' ? 'btn-outline-secondary' : 'btn-outline-success' }}" 
                                                        title="{{ $user->status == 'ativo' ? 'Desativar' : 'Ativar' }}">
                                                    <i class="fas {{ $user->status == 'ativo' ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('usuarios.destroy', $user) }}" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-info">Você</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-2x mb-3"></i>
                                        <h5>Nenhum usuário encontrado</h5>
                                        <p>Não há usuários cadastrados ou que correspondam aos filtros aplicados.</p>
                                        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Criar Primeiro Usuário
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="mobile-card-list">
                @if($users->count() > 0)
                    @foreach($users as $user)
                    <div class="mobile-user-card">
                        <div class="mobile-card-header">
                            <div class="mobile-card-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="mobile-card-info">
                                <div class="mobile-card-title">{{ $user->name }}</div>
                                <div class="mobile-card-subtitle">{{ $user->email }}</div>
                            </div>
                            <div>
                                <span class="badge {{ $user->status == 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->status_display }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mobile-card-details">
                            <div class="mobile-detail-item">
                                <i class="fas fa-user-tag mobile-detail-icon"></i>
                                <span class="badge 
                                    @if($user->perfil == 'admin') bg-danger
                                    @elseif($user->perfil == 'operador') bg-primary  
                                    @else bg-info
                                    @endif">
                                    {{ $user->perfil_display }}
                                </span>
                            </div>
                            
                            @if($user->departamento)
                                <div class="mobile-detail-item">
                                    <i class="fas fa-building mobile-detail-icon"></i>
                                    <span>{{ $user->departamento }}</span>
                                </div>
                            @endif
                            
                            @if($user->telefone)
                                <div class="mobile-detail-item">
                                    <i class="fas fa-phone mobile-detail-icon"></i>
                                    <span>{{ $user->telefone }}</span>
                                </div>
                            @endif
                            
                            <div class="mobile-detail-item">
                                <i class="fas fa-calendar mobile-detail-icon"></i>
                                <span>Criado em {{ $user->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        
                        <div class="mobile-card-actions">
                            <a href="{{ route('usuarios.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Ver
                            </a>
                            <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit me-1"></i>Editar
                            </a>
                            
                            @if($user->id !== session('user.id'))
                                <form method="POST" action="{{ route('usuarios.toggle-status', $user) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $user->status == 'ativo' ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                        <i class="fas {{ $user->status == 'ativo' ? 'fa-user-slash' : 'fa-user-check' }} me-1"></i>
                                        {{ $user->status == 'ativo' ? 'Desativar' : 'Ativar' }}
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-info">Você</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h5>Nenhum usuário encontrado</h5>
                            <p>Não há usuários cadastrados ou que correspondam aos filtros aplicados.</p>
                            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Criar Primeiro Usuário
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} 
                    de {{ $users->total() }} usuários
                </div>
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">Nenhum usuário encontrado.</p>
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Criar Primeiro Usuário
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
    font-weight: 600;
}

/* Mobile filter improvements */
@media (min-width: 768px) {
    #filterContent {
        display: block !important;
    }
}

@media (max-width: 767px) {
    .mobile-filter-content {
        transition: all 0.3s ease;
    }
    #filterContent.show {
        display: block !important;
    }
}
</style>

<script>
function toggleMobileFilters() {
    const content = document.getElementById('filterContent');
    const icon = document.getElementById('filterToggleIcon');
    
    if (content.style.display === 'none' || !content.classList.contains('show')) {
        content.style.display = 'block';
        content.classList.add('show');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        content.style.display = 'none';
        content.classList.remove('show');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}

// Auto-open filters if there are active filters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const hasActiveFilters = urlParams.has('perfil') || urlParams.has('status') || urlParams.has('search');
    
    if (hasActiveFilters && window.innerWidth < 768) {
        toggleMobileFilters();
    }
});
</script>
@endsection