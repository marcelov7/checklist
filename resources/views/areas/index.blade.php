@extends('layouts.app')

@section('title', 'Áreas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-map-marked-alt"></i> Áreas</h1>
    <div class="d-flex gap-2">
        <div class="btn-group flex-column flex-sm-row" role="group">
            <button type="button" class="btn btn-outline-success mb-1 mb-sm-0" onclick="exportarAreas()">
                <i class="fas fa-download"></i> <span class="d-none d-sm-inline">Exportar </span>CSV
            </button>
            <button type="button" class="btn btn-outline-info mb-1 mb-sm-0" onclick="document.getElementById('importFile').click()">
                <i class="fas fa-upload"></i> <span class="d-none d-sm-inline">Importar </span>CSV
            </button>
            <a href="{{ route('template.download') }}" class="btn btn-outline-warning">
                <i class="fas fa-file-csv"></i> <span class="d-none d-sm-inline">Template </span>CSV
            </a>
        </div>
        <a href="{{ route('areas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Área
        </a>
    </div>
</div>

<!-- Input file oculto para importação -->
<input type="file" id="importFile" accept=".csv,.txt" style="display: none;" onchange="importarAreas(this.files[0])">

<div class="row">
    @forelse($areas as $area)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ $area->nome }}</h5>
                </div>
                <div class="card-body">
                    @if($area->descricao)
                        <p class="text-muted">{{ Str::limit($area->descricao, 100) }}</p>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-cogs"></i> {{ $area->equipamentosAtivos->count() }} equipamentos
                        </small>
                        @if($area->ativo)
                            <span class="badge bg-success">Ativo</span>
                        @else
                            <span class="badge bg-secondary">Inativo</span>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="{{ route('areas.show', $area) }}" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('areas.edit', $area) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($area->equipamentosAtivos->count() == 0)
                            <form action="{{ route('areas.destroy', $area) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Tem certeza que deseja desativar esta área?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Nenhuma área encontrada</h4>
                <p class="text-muted">Comece criando sua primeira área para organizar os equipamentos.</p>
                <a href="{{ route('areas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Criar Primeira Área
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
function exportarAreas() {
    // Redirecionar diretamente para download do CSV
    window.location.href = '/areas/export-data';
    mostrarNotificacao('Download do arquivo CSV iniciado!', 'success');
}



function importarAreas(file) {
    if (!file) return;
    
    // Verificar se é um arquivo CSV
    if (!file.name.match(/\.(csv|txt)$/)) {
        mostrarNotificacao('Por favor, selecione um arquivo CSV (.csv ou .txt)', 'error');
        document.getElementById('importFile').value = '';
        return;
    }
    
    const formData = new FormData();
    formData.append('file', file);
    
    // Mostrar indicador de carregamento
    mostrarNotificacao('Importando áreas, aguarde...', 'info');
    
    $.ajax({
        url: '/areas/import-data',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                mostrarNotificacao(response.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                mostrarNotificacao(response.message, 'error');
            }
        },
        error: function(xhr) {
            const response = JSON.parse(xhr.responseText);
            mostrarNotificacao(response.message || 'Erro ao importar áreas', 'error');
        },
        complete: function() {
            // Reset do input
            document.getElementById('importFile').value = '';
        }
    });
}

function mostrarNotificacao(mensagem, tipo) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${tipo === 'success' ? 'success' : tipo === 'error' ? 'danger' : 'info'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${mensagem}
        <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}
</script>
@endpush