@extends('layouts.app')

@section('title', 'Detalhes da Área')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><i class="fas fa-map-marked-alt"></i> {{ $area->nome }}</h1>
        @if($area->descricao)
            <p class="text-muted mb-0">{{ $area->descricao }}</p>
        @endif
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('areas.edit', $area) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{ route('areas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Equipamentos da Área -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-cogs"></i> Equipamentos ({{ $area->equipamentosAtivos->count() }})</h5>
                <a href="{{ route('equipamentos.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Novo Equipamento
                </a>
            </div>
            <div class="card-body">
                @forelse($area->equipamentosAtivos as $equipamento)
                    <div class="border-bottom py-3 @if(!$loop->last) mb-3 @endif">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1">
                                    <a href="{{ route('equipamentos.show', $equipamento) }}" class="text-decoration-none">
                                        {{ $equipamento->nome }}
                                    </a>
                                </h6>
                                <p class="text-muted mb-1">
                                    <span class="badge bg-primary">{{ $equipamento->tag }}</span>
                                    @if($equipamento->descricao)
                                        | {{ Str::limit($equipamento->descricao, 50) }}
                                    @endif
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> Criado em {{ $equipamento->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            <div class="col-md-4 text-md-end">
                                @php
                                    $ultimoTeste = $equipamento->testes()->latest()->first();
                                @endphp
                                
                                @if($ultimoTeste)
                                    @switch($ultimoTeste->status)
                                        @case('ok')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Último teste: OK
                                            </span>
                                            @break
                                        @case('problema')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times"></i> Último teste: Problema
                                            </span>
                                            @break
                                        @case('resolvido')
                                            <span class="badge bg-info">
                                                <i class="fas fa-wrench"></i> Último teste: Resolvido
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Último teste: Pendente
                                            </span>
                                    @endswitch
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-minus"></i> Sem testes
                                    </span>
                                @endif
                                
                                <div class="mt-2">
                                    <a href="{{ route('equipamentos.show', $equipamento) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('equipamentos.edit', $equipamento) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Nenhum equipamento cadastrado</h6>
                        <p class="text-muted">Esta área ainda não possui equipamentos.</p>
                        <a href="{{ route('equipamentos.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Adicionar Primeiro Equipamento
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar com Informações -->
    <div class="col-md-4">
        <!-- Informações da Área -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informações da Área</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Nome:</strong><br>
                    {{ $area->nome }}
                </p>
                
                @if($area->descricao)
                    <p class="mb-2">
                        <strong>Descrição:</strong><br>
                        {{ $area->descricao }}
                    </p>
                @endif
                
                <p class="mb-2">
                    <strong>Status:</strong><br>
                    @if($area->ativo)
                        <span class="badge bg-success">Ativa</span>
                    @else
                        <span class="badge bg-secondary">Inativa</span>
                    @endif
                </p>
                
                <p class="mb-2">
                    <strong>Equipamentos:</strong><br>
                    {{ $area->equipamentosAtivos->count() }} ativos
                </p>
                
                <hr>
                
                <p class="mb-2">
                    <small class="text-muted">Criada em:</small><br>
                    {{ $area->created_at->format('d/m/Y H:i') }}
                </p>
                
                <p class="mb-0">
                    <small class="text-muted">Última atualização:</small><br>
                    {{ $area->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Estatísticas</h6>
            </div>
            <div class="card-body">
                @php
                    $totalEquipamentos = $area->equipamentosAtivos->count();
                    $equipamentosComTeste = $area->equipamentosAtivos->filter(function($eq) {
                        return $eq->testes->count() > 0;
                    })->count();
                    
                    $totalTestes = $area->equipamentosAtivos->sum(function($eq) {
                        return $eq->testes->count();
                    });
                    
                    $testesOk = $area->equipamentosAtivos->sum(function($eq) {
                        return $eq->testes->where('status', 'ok')->count();
                    });
                @endphp

                <div class="mb-3">
                    <small class="text-muted">Total de Equipamentos</small>
                    <h4 class="mb-0">{{ $totalEquipamentos }}</h4>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Equipamentos Testados</small>
                    <h4 class="mb-0">{{ $equipamentosComTeste }}</h4>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Total de Testes</small>
                    <h4 class="mb-0">{{ $totalTestes }}</h4>
                </div>

                @if($totalTestes > 0)
                    <div class="mb-3">
                        <small class="text-muted">Testes Bem-sucedidos</small>
                        <h4 class="mb-0">{{ $testesOk }}</h4>
                    </div>

                    @php
                        $sucessoRate = round(($testesOk / $totalTestes) * 100, 1);
                    @endphp
                    
                    <div>
                        <small class="text-muted">Taxa de Sucesso</small>
                        <div class="progress mb-1" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $sucessoRate }}%" 
                                 aria-valuenow="{{ $sucessoRate }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted">{{ $sucessoRate }}%</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection