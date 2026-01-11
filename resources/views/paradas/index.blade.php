@extends('layouts.app')

@section('title', 'Paradas Ativas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-stop-circle"></i> Paradas Ativas</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('paradas.historico') }}" class="btn btn-outline-primary d-none d-sm-inline-flex">
            <i class="fas fa-history"></i> <span class="d-none d-lg-inline">Ver </span>Histórico
        </a>
        <a href="{{ route('paradas.create') }}" class="btn btn-primary d-none d-sm-inline-flex">
            <i class="fas fa-plus-circle"></i> <span class="d-none d-lg-inline">Nova </span>Parada
        </a>
    </div>
</div>

<!-- FAB Mobile com label -->
<div class="fab-container d-sm-none">
    <a href="{{ route('paradas.create') }}" class="fab-mobile" title="Criar Nova Parada" data-bs-toggle="tooltip" data-bs-placement="left">
        <i class="fas fa-plus"></i>
    </a>
    <div class="fab-label">
        Nova Parada
    </div>
</div>

<div class="row">
    @forelse($paradas as $parada)
            <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <div class="card h-100" data-parada-id="{{ $parada->id }}">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-2">
                        <span class="badge badge-departamento fs-6 mb-1 mb-sm-0">{{ $parada->macro }}</span>
                        <span class="badge 
                            @if($parada->status == 'concluida') badge-implementado
                            @elseif($parada->status == 'em_andamento') bg-warning
                            @else bg-secondary
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $parada->status)) }}
                        </span>
                    </div>
                    <h5 class="mb-0">{{ $parada->nome }}</h5>
                    <small class="text-muted">
                        <i class="fas fa-tag"></i> {{ $parada->tipo_label }}
                    </small>
                </div>
                <div class="card-body">
                    @if($parada->descricao)
                        <p class="text-muted small">{{ Str::limit($parada->descricao, 100) }}</p>
                    @endif
                    
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> Início: {{ $parada->data_inicio->format('d/m/Y H:i') }}
                        </small>
                        @if($parada->data_fim)
                            <br>
                            <small class="text-success">
                                <i class="fas fa-calendar-check"></i> Fim: {{ $parada->data_fim->format('d/m/Y H:i') }}
                            </small>
                        @endif
                        
                        @if($parada->duracao_prevista_horas)
                            <br>
                            <small class="text-info">
                                <i class="fas fa-clock"></i> Duração prevista: {{ number_format($parada->duracao_prevista_horas, 2, ',', '.') }}h
                            </small>
                        @endif
                        
                        @if($parada->status == 'em_andamento')
                            <br>
                            <small class="text-primary">
                                <i class="fas fa-play"></i> {{ $parada->duracao_atual }}h em andamento
                            </small>
                        @endif
                        
                        @if($parada->equipe_responsavel)
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-users"></i> {{ Str::limit($parada->equipe_responsavel, 50) }}
                            </small>
                        @endif
                    </div>

                    @php
                        $percentual = $parada->getPercentualCompleto();
                        $percentualDisplay = number_format($percentual, 1, '.', '');
                    @endphp
                    
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped {{ $percentual >= 100 ? 'bg-success' : 'bg-primary' }}" 
                             role="progressbar" 
                             style="width: {{ $percentualDisplay }}%"
                             aria-valuenow="{{ $percentualDisplay }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ $percentualDisplay }}%
                        </div>
                    </div>
                    
                    <small class="text-muted">Progresso geral</small>
                </div>
                <div class="card-footer">
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('paradas.show', $parada) }}" class="btn btn-primary flex-fill">
                            <i class="fas fa-eye me-1"></i> <span class="d-none d-sm-inline">Visualizar</span><span class="d-sm-none">Ver Detalhes</span>
                        </a>
                        @if($parada->status == 'em_andamento')
                            <a href="{{ route('paradas.edit', $parada) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i><span class="d-none d-md-inline"> Editar</span><span class="d-md-none">Editar</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-clipboard-list" style="font-size: 4rem; color: var(--accent-cyan); opacity: 0.6;"></i>
                </div>
                <h4 class="text-muted mb-3">Nenhuma parada encontrada</h4>
                <p class="text-muted mb-4">Comece criando sua primeira parada de manutenção.</p>
                <a href="{{ route('paradas.create') }}" class="btn btn-primary btn-lg d-none d-sm-inline-flex">
                    <i class="fas fa-plus-circle me-2"></i> Criar Primeira Parada
                </a>
                <!-- Mobile: FAB será usado automaticamente -->
            </div>
        </div>
    @endforelse
</div>

@if($paradas->count() > 0)
    <div class="mt-4">
        <h5>Estatísticas Gerais</h5>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $paradas->where('status', 'em_andamento')->count() }}</h3>
                        <p class="mb-0">Em Andamento</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">{{ $paradas->where('status', 'concluida')->count() }}</h3>
                        <p class="mb-0">Concluídas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-secondary">{{ $paradas->where('status', 'cancelada')->count() }}</h3>
                        <p class="mb-0">Canceladas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">{{ $paradas->count() }}</h3>
                        <p class="mb-0">Total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<script>
    (function(){
        // Atualiza progresso das paradas ativas periodicamente
        const intervalo = 8000; // ms

        function updateCard(paradaId, data) {
            try {
                const card = document.querySelector('[data-parada-id="' + paradaId + '"]');
                if (!card) return;
                const progressBar = card.querySelector('.progress-bar');
                if (progressBar && data.percentual !== undefined) {
                    const percentualRaw = parseFloat(data.percentual);
                    const percentual = Number.isFinite(percentualRaw) ? Math.round(percentualRaw * 10) / 10 : 0;
                    progressBar.style.width = percentual + '%';
                    progressBar.setAttribute('aria-valuenow', percentual);
                    progressBar.textContent = percentual + '%';
                    // ajustar classes
                    if (percentual >= 100) {
                        progressBar.classList.remove('bg-primary');
                        progressBar.classList.add('bg-success');
                    } else {
                        progressBar.classList.remove('bg-success');
                        progressBar.classList.add('bg-primary');
                    }
                }
            } catch(e) { console.error('Erro updateCard', e); }
        }

        function fetchAndUpdate(paradaId) {
            // cache-busting: add timestamp and request no-store to avoid stale cached responses
            const url = '/paradas/' + paradaId + '/progresso?_=' + Date.now();
            fetch(url, { cache: 'no-store', headers: { 'Cache-Control': 'no-cache' } })
                .then(r => r.json())
                .then(json => {
                    if (json && json.success) updateCard(paradaId, json);
                })
                .catch(err => console.error('Erro fetch progresso', err));
        }

        function initPolling() {
            const cards = Array.from(document.querySelectorAll('[data-parada-id]'));
            if (!cards.length) return;
            // inicial fetch
            cards.forEach(c => fetchAndUpdate(c.dataset.paradaId));
            // intervalo
            setInterval(() => {
                cards.forEach(c => fetchAndUpdate(c.dataset.paradaId));
            }, intervalo);
        }

        document.addEventListener('DOMContentLoaded', initPolling);
    })();
</script>
@endsection
