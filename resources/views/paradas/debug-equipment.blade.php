@extends('layouts.app')

@section('title', 'Debug - Seleção de Equipamentos')

@section('content')
<div class="container">
    <h1>Debug - Equipamentos</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5>Informações Recebidas</h5>
        </div>
        <div class="card-body">
            <p><strong>Parada:</strong> {{ $parada->macro }} - {{ $parada->nome }}</p>
            <p><strong>Equipamentos Selecionados:</strong> 
                @if(isset($equipamentosSelecionados))
                    {{ count($equipamentosSelecionados) }} - {{ implode(', ', $equipamentosSelecionados) }}
                @else
                    Nenhum
                @endif
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Todas as Áreas e Equipamentos</h5>
        </div>
        <div class="card-body">
            @foreach($areas as $area)
                <h6>{{ $area->nome }}</h6>
                <ul>
                    @foreach($area->equipamentosAtivos as $equipamento)
                        <li>
                            {{ $equipamento->tag }} - {{ $equipamento->nome }}
                            (ID: {{ $equipamento->id }})
                            @if(isset($equipamentosSelecionados) && in_array($equipamento->id, $equipamentosSelecionados))
                                <span class="badge bg-success">SELECIONADO</span>
                            @else
                                <span class="badge bg-secondary">NÃO SELECIONADO</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('paradas.select-equipment', $parada) }}" class="btn btn-primary">Voltar à Seleção</a>
    </div>
</div>
@endsection