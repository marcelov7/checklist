@extends('layouts.app')

@section('title', 'Histórico de Paradas')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-history me-2"></i>Histórico de Paradas
                    </h4>
                    <a href="{{ route('paradas.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>Nova Parada
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-info mb-2">
                        <i class="fas fa-chart-bar fa-2x"></i>
                    </div>
                    <h3 class="text-info mb-1">{{ $estatisticas['total'] }}</h3>
                    <p class="mb-0 small text-muted">Total de Paradas</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-primary mb-2">
                        <i class="fas fa-play-circle fa-2x"></i>
                    </div>
                    <h3 class="text-primary mb-1">{{ $estatisticas['em_andamento'] }}</h3>
                    <p class="mb-0 small text-muted">Em Andamento</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h3 class="text-success mb-1">{{ $estatisticas['concluidas'] }}</h3>
                    <p class="mb-0 small text-muted">Finalizadas</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-warning mb-2">
                        <i class="fas fa-tools fa-2x"></i>
                    </div>
                    <h3 class="text-warning mb-1">{{ $estatisticas['preventivas'] }}</h3>
                    <p class="mb-0 small text-muted">Preventivas</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-danger mb-2">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <h3 class="text-danger mb-1">{{ $estatisticas['corretivas'] ?? 0 }}</h3>
                    <p class="mb-0 small text-muted">Corretivas</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="text-dark mb-2">
                        <i class="fas fa-bolt fa-2x"></i>
                    </div>
                    <h3 class="text-dark mb-1">{{ $estatisticas['emergenciais'] ?? 0 }}</h3>
                    <p class="mb-0 small text-muted">Emergenciais</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-filter me-2"></i>Filtros Rápidos
                    </h6>
                </div>
                <div class="card-body">
                    <div class="btn-group flex-wrap" role="group">
                        <a href="{{ route('paradas.historico') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Todas
                        </a>
                        <a href="{{ route('paradas.historico') }}?status=em_andamento" class="btn btn-outline-primary">
                            <i class="fas fa-play"></i> Em Andamento
                        </a>
                        <a href="{{ route('paradas.historico') }}?status=concluida" class="btn btn-outline-success">
                            <i class="fas fa-check"></i> Concluídas
                        </a>
                        <a href="{{ route('paradas.historico') }}?tipo=preventiva" class="btn btn-outline-warning">
                            <i class="fas fa-calendar-alt"></i> Preventivas
                        </a>
                        <a href="{{ route('paradas.historico') }}?tipo=corretiva" class="btn btn-outline-danger">
                            <i class="fas fa-wrench"></i> Corretivas
                        </a>
                        <a href="{{ route('paradas.historico') }}?tipo=emergencial" class="btn btn-outline-dark">
                            <i class="fas fa-exclamation-triangle"></i> Emergenciais
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Paradas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Todas as Paradas
                        <span class="badge bg-secondary ms-2">{{ $paradas->total() }} {{ $paradas->total() == 1 ? 'parada' : 'paradas' }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @forelse($paradas as $parada)
            @php
                $percentualGeral = $parada->getPercentualCompleto();
                $percentualPorArea = $parada->getPercentualPorArea();
            @endphp
                        <div class="parada-item {{ !$loop->last ? 'border-bottom' : '' }}" style="touch-action: pan-y; position: relative;">
                            <div class="p-4" style="pointer-events: none;">
                                <!-- Cabeçalho da Parada -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <span class="badge bg-dark fs-6">{{ $parada->macro }}</span>
                                                <br>
                                                @if($parada->status === 'concluida')
                                                    <span class="badge bg-success mt-1">
                                                        <i class="fas fa-check-circle me-1"></i>Finalizada
                                                    </span>
                                                @elseif($parada->status === 'em_andamento') 
                                                    <span class="badge bg-primary mt-1">
                                                        <i class="fas fa-play me-1"></i>Em Andamento
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary mt-1">
                                                        {{ ucfirst(str_replace('_', ' ', $parada->status)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $parada->nome }}</h5>
                                                <div class="text-muted small">
                                                    <i class="fas fa-tag me-1"></i>{{ ucfirst($parada->tipo) }}
                                                    @if($parada->equipe_responsavel)
                                                        | <i class="fas fa-users me-1"></i>{{ $parada->equipe_responsavel }}
                                                    @endif
                                                </div>
                                                <div class="text-muted small mt-1">
                                                    <i class="fas fa-calendar me-1"></i>{{ $parada->data_inicio->format('d/m/Y H:i') }}
                                                    @if($parada->data_fim)
                                                        | <i class="fas fa-calendar-check me-1"></i>{{ $parada->data_fim->format('d/m/Y H:i') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 text-end">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <!-- Progresso Geral -->
                                            <div class="me-3">
                                                <div class="text-center">
                                                    <div class="progress" style="width: 120px; height: 8px;">
                                                        <div class="progress-bar bg-{{ $percentualGeral == 100 ? 'success' : 'primary' }}" 
                                                             style="width: {{ $percentualGeral }}%">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $percentualGeral }}% Completo</small>
                                                </div>
                                            </div>
                                            
                                            <!-- Duração -->
                                            <div class="me-3 text-end">
                                                @if($parada->duracao_prevista_horas)
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-clock me-1"></i>{{ number_format($parada->duracao_prevista_horas, 1) }}h prevista
                                                    </small>
                                                @endif
                                                @if($parada->duracao_real)
                                                    <small class="text-success d-block">
                                                        <i class="fas fa-stopwatch me-1"></i>{{ $parada->duracao_real }}h real
                                                    </small>
                                                @elseif($parada->status == 'em_andamento')
                                                    <small class="text-primary d-block">
                                                        <i class="fas fa-play me-1"></i>{{ $parada->duracao_atual }}h decorridas
                                                    </small>
                                                @endif
                                            </div>
                                            
                                            <!-- Botões de Ação -->
                                            <div class="btn-group btn-group-sm" style="touch-action: manipulation; position: relative; z-index: 10; pointer-events: auto;">
                                                <a href="{{ route('paradas.show', $parada) }}" class="btn btn-outline-primary" title="Visualizar" style="touch-action: manipulation; pointer-events: auto;">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($parada->status === 'concluida' && session('user.perfil') === 'admin')
                                                    <button class="btn btn-outline-warning" onclick="reabrirParada({{ $parada->id }})" title="Reabrir Parada (Admin)" style="touch-action: manipulation; pointer-events: auto;">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                @endif
                                                <div class="dropdown" style="pointer-events: auto;">
                                                    <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" style="touch-action: manipulation; position: relative; z-index: 15; pointer-events: auto;">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="{{ route('paradas.show', $parada) }}">
                                                            <i class="fas fa-eye me-2"></i>Visualizar
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="{{ route('paradas.relatorio', $parada) }}">
                                                            <i class="fas fa-file-alt me-2"></i>Relatório
                                                        </a></li>
                                                        @if($parada->status == 'em_andamento')
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item" href="{{ route('paradas.edit', $parada) }}">
                                                                <i class="fas fa-edit me-2"></i>Editar
                                                            </a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Progresso por Área -->
                                @if($percentualPorArea->isNotEmpty())
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <small class="text-muted d-block mb-2">Progresso por Área:</small>
                                            <div class="row g-2">
                                                @foreach($percentualPorArea as $area)
                                                    <div class="col-md-3">
                                                        <div class="d-flex align-items-center">
                                                            <small class="me-2">{{ Str::limit($area->nome, 15) }}:</small>
                                                            <div class="progress flex-grow-1" style="height: 4px;">
                                                                <div class="progress-bar bg-info" style="width: {{ $area->percentual }}%"></div>
                                                            </div>
                                                            <small class="ms-2 text-muted">{{ $area->percentual }}%</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Indicadores de Problemas -->
                                @if($parada->testes_problema > 0)
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $parada->testes_problema }} problema{{ $parada->testes_problema > 1 ? 's' : '' }} identificado{{ $parada->testes_problema > 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Nenhuma parada encontrada</h4>
                <p class="text-muted">Comece criando sua primeira parada de manutenção.</p>
                <a href="{{ route('paradas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Criar Primeira Parada
                </a>
            </div>
                        @endforelse
                    </div>
                    
                    @if($paradas->hasPages())
                        <div class="card-footer bg-light">
                            {{ $paradas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


</div>

<script>
// Função para reabrir parada (apenas admin)
function reabrirParada(paradaId) {
    if (confirm('Tem certeza que deseja reabrir esta parada?\n\nApós reaberta, será possível fazer alterações nos checklists novamente.')) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch(`/paradas/${paradaId}/reabrir`, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Parada reaberta com sucesso!');
                location.reload();
            } else {
                alert('Erro ao reabrir parada: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao reabrir parada.');
        });
    }
}

// Função para reabrir parada (apenas admin)
document.addEventListener('DOMContentLoaded', function() {
    // Garantir que os botões funcionem corretamente em dispositivos móveis
    const buttons = document.querySelectorAll('.btn-group .btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
});
</script>

<style>
.parada-item {
    position: relative;
    margin-bottom: 1.5rem;
    background: white;
    border-radius: 0;
}

/* Ajustes para dispositivos móveis */
@media (max-width: 991.98px) {
    .btn-group .btn {
        min-width: 44px;
        padding: 8px;
    }
    
    .dropdown-menu {
        min-width: 200px;
    }
    
    .progress {
        min-width: 80px;
    }
}

}

.parada-item.border-bottom {
    border-top: 4px solid #0d6efd !important;
    border-bottom: 1px solid #e9ecef !important;
    margin-bottom: 2rem;
    padding-top: 0.5rem;
    padding-bottom: 1rem;
}

.parada-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0;
}

/* Separador visual simples entre paradas */
.parada-item + .parada-item {
    position: relative;
    margin-top: 1rem;
}
</style>
@endsection