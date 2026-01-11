@extends('layouts.app')

@section('title', 'Parada: ' . $parada->nome)

@section('styles')
<style>
    /* Estilos para PWA e Mobile */
    @media (max-width: 767.98px) {
        /* Ajustes gerais para mobile */
        body {
            background-color: #f5f6fa;
        }
        
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Melhorias no header */
        .card-header {
            padding: 1rem;
            border-radius: 12px 12px 0 0;
        }

        /* Cards mais modernos */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 1rem;
        }

        /* Equipamentos */
        .equipment-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .equipment-header {
            padding: 1rem;
            background: #f8f9fa;
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s ease;
        }

        .equipment-header:active {
            background: #e9ecef;
        }

        .equipment-body {
            padding: 1rem;
        }

        /* Progress bar mais moderna */
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        /* Badges mais modernos */
        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Ícones de status */
        .checklist-info .fas {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .checklist-info .fa-check-circle {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .checklist-info .fa-exclamation-triangle {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .checklist-info .fa-ban {
            background-color: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }

        .checklist-info .fa-clock {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        /* Botões de ação mais touch-friendly */
        .checklist-actions .btn {
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            border-radius: 50px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }
        
        .checklist-actions .btn:active {
            transform: scale(0.95);
        }
        
        /* Layout dos botões em mobile */
        .checklist-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
            margin-top: 0.5rem;
        }
        
        .status-badge {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            width: 100%;
            gap: 0.5rem;
        }
        
        /* Status badges mais visíveis */
        .status-badge .badge {
            padding: 0.5rem;
            font-size: 0.9rem;
            width: 100%;
            text-align: center;
            margin: 0;
        }
        
        /* Melhorias no layout do item */
        .checklist-item {
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .checklist-content {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .checklist-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }
        
        /* Formulários mais amigáveis para touch */
        .problema-form .form-control,
        .resolucao-form .form-control {
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 12px;
        }
        
        /* Botões de ação principais */
        .btn-success {
            background: #28a745;
            border-color: #28a745;
        }
        
        .btn-danger {
            background: #dc3545;
            border-color: #dc3545;
        }
        
        .btn-info {
            background: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }
        
        /* Efeito de toque */
        .btn:active {
            transform: scale(0.95);
        }
        
        /* Ícones mais visíveis */
        .fas {
            font-size: 1.1rem;
        }

        /* Botões de ação agrupados */
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 0.5rem;
            width: 100%;
        }

        /* Estilo para botões de edição */
        .btn-outline-secondary {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
        }

        /* Melhorias nos formulários */
        .problema-form,
        .resolucao-form {
            margin-top: 1rem;
        }

        .problema-form .card,
        .resolucao-form .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Botões de foto */
        .btn-outline-danger,
        .btn-outline-success {
            border-radius: 50px;
            padding: 0.5rem 1rem;
        }

        /* Histórico de problemas */
        .historico-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.5rem;
        }

        .historico-item {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        /* Animações suaves */
        .btn,
        .badge,
        .card {
            transition: all 0.2s ease-in-out;
        }

        /* Feedback visual ao tocar */
        .btn:active,
        .card:active {
            transform: scale(0.98);
        }
    }
}

/* ===== CARDS DE ÁREA ===== */
.area-card {
    border: 1px solid #dee2e6;
    border-radius: 0.75rem;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.area-card .card-header {
    background: linear-gradient(135deg, #17a2b8, #138496);
    border-bottom: 2px solid #117a8b;
    padding: 1.25rem 1.5rem;
}

/* ===== CARDS DE EQUIPAMENTOS ===== */
.equipment-card {
    background: #ffffff;
    border: 2px solid #e3f2fd;
    border-radius: 0.75rem;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.equipment-card:hover {
    border-color: #17a2b8;
    box-shadow: 0 0.5rem 1rem rgba(23, 162, 184, 0.15);
    transform: translateY(-2px);
}

.equipment-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #dee2e6;
    padding: 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    flex-shrink: 0;
    min-height: 80px;
    display: flex;
    align-items: center;
}

.equipment-header:hover {
    background: linear-gradient(135deg, #e9ecef, #dee2e6);
}

.equipment-body {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* ===== BOTOES DE NAVEGACAO ===== */
.parada-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    align-items: center;
}

.parada-actions .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    min-width: 200px;
}

/* ===== ITENS DO CHECKLIST ===== */
.checklist-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.checklist-item:hover {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    background: #ffffff;
}

.checklist-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
    min-height: 50px;
}

.checklist-info {
    display: flex;
    align-items: center;
    flex: 1;
    font-size: 1rem;
}

.checklist-info i {
    font-size: 1.1rem;
    margin-right: 0.75rem;
}

.checklist-info .fw-bold {
    font-size: 1rem;
    font-weight: 600;
}

.checklist-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    align-items: center;
}

.status-badge {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}

/* ===== HISTÓRICO ===== */
.historico-section {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #dee2e6;
}

.historico-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-left: 4px solid #0d6efd;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 0.75rem;
}

/* ===== RESPONSIVIDADE ===== */
@media (max-width: 767px) {
    .parada-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .parada-actions .btn {
        width: 100%;
        min-width: 0;
    }

    .equipment-card {
        margin-bottom: 1rem;
    }
    
    .equipment-header {
        padding: 1rem;
        min-height: 70px;
    }
    
    .equipment-header .d-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .equipment-header .progress {
        width: 120px;
        height: 6px;
    }
    
    .equipment-body {
        padding: 1rem;
    }
    
    .checklist-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .checklist-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .checklist-item {
        padding: 0.75rem;
    }
}

@media (min-width: 768px) {
    .equipment-card {
        margin-bottom: 1.5rem;
    }
    
    .equipment-header {
        padding: 1.25rem;
        min-height: 80px;
    }
    
    .equipment-body {
        padding: 1.5rem;
    }
}

@media (min-width: 1200px) {
    .equipment-card {
        margin-bottom: 2rem;
    }
    
    .equipment-header {
        padding: 1.5rem;
        min-height: 90px;
    }
    
    .equipment-body {
        padding: 2rem;
    }
    
    .checklist-item {
        padding: 1.25rem;
    }
    
    .checklist-info {
        font-size: 1.1rem;
    }
    
    .checklist-info i {
        font-size: 1.2rem;
    }
}

/* ===== ÍCONES E ANIMAÇÕES ===== */
.collapse-icon {
    transition: transform 0.3s ease;
    /* Quando não tem a classe collapsed, rotaciona 90° para apontar para baixo */
}

.collapse-icon:not(.collapsed) {
    transform: rotate(90deg);
}

.collapse-icon.collapsed {
    /* Quando colapsado, mantém apontando para a direita (sem rotação) */
    transform: rotate(0deg);
}

/* ===== BOTÕES E BADGES ===== */
.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838, #1e7e34);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    border: none;
    color: #000;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800, #e55a00);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
    color: #000;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* ===== PROGRESS BARS ===== */
.progress {
    height: 8px;
    border-radius: 4px;
}

.progress-bar {
    border-radius: 4px;
}

/* ===== GRID RESPONSIVO ===== */
.area-card .card-body {
    padding: 1.5rem;
    background: #f8f9fa;
}

.area-card .row {
    margin: 0;
}

.area-card .row > [class*="col-"] {
    padding: 0;
    margin-bottom: 0;
}

/* ===== ALERTAS ===== */
.alert-info {
    border-left: 5px solid #0dcaf0;
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    border-radius: 0.5rem;
}

/* ===== ESPAÇAMENTO ===== */
.container-fluid.px-0 {
    margin-bottom: 2rem;
}

/* ===== RESPONSIVIDADE MOBILE ===== */
@media (max-width: 767.98px) {
    /* Header Principal */
    .card-header h4 {
        font-size: 1.1rem !important;
        line-height: 1.3;
    }
    
    .card-header small {
        font-size: 0.8rem;
        opacity: 0.9;
    }
    
    /* Badges menores em mobile */
    .badge {
        font-size: 0.7rem !important;
        padding: 0.25rem 0.5rem;
    }
    
    .fs-7 {
        font-size: 0.7rem !important;
    }
    
    /* Botões otimizados para mobile */
    .btn-sm {
        padding: 0.375rem 0.5rem;
        font-size: 0.8rem;
    }
    
    /* Container e cards */
    .container-fluid {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .card {
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Header das áreas */
    .card-header h5 {
        font-size: 1rem !important;
        line-height: 1.2;
    }
    
    /* Equipment cards */
    .equipment-card {
        margin-bottom: 0.75rem;
    }
    
    .equipment-header {
        padding: 0.75rem;
    }
    
    .equipment-header h6 {
        font-size: 0.9rem;
        line-height: 1.3;
    }
    
    /* Checklist items mobile */
    .checklist-item {
        padding: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    /* LAYOUT VERTICAL COMPLETO PARA BOTÕES - APENAS DENTRO DO CHECKLIST */
    
    /* Aplicar apenas em botões dentro de itens de checklist */
    .collapse .card-body .btn,
    .checklist-item .btn {
        display: block !important;
        width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        margin-bottom: 0.5rem !important;
        float: none !important;
    }
    
    /* Containers de checklist - forçar layout vertical */
    .checklist-item,
    .collapse .card-body .status-badge {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.5rem !important;
        width: 100% !important;
        align-items: stretch !important;
    }
    
    /* Preservar display classes do Bootstrap */
    .d-none,
    .d-md-none {
        display: none !important;
    }
    
    .d-flex.d-md-none {
        display: none !important;
    }
    
    .d-none.d-md-flex {
        display: none !important;
    }
    
    @media (min-width: 768px) {
        .d-none.d-md-flex {
            display: flex !important;
        }
        
        .d-flex.d-md-none {
            display: none !important;
        }
    }
    
    /* Botões específicos APENAS dentro do checklist */
    .collapse .card-body .btn-success,
    .collapse .card-body .btn-danger, 
    .collapse .card-body .btn-info,
    .collapse .card-body .btn-warning,
    .checklist-item .btn-success,
    .checklist-item .btn-danger, 
    .checklist-item .btn-info,
    .checklist-item .btn-warning {
        display: block !important;
        width: 100% !important;
        margin-bottom: 0.5rem !important;
        border-radius: 0.375rem !important;
        font-size: 0.8rem !important;
        padding: 0.75rem !important;
        text-align: center !important;
        box-sizing: border-box !important;
    }
    
    /* Clearfix apenas para botões de checklist */
    .collapse .card-body .btn::after,
    .checklist-item .btn::after {
        content: "";
        display: block;
        clear: both;
    }
    
    /* Espaçamento entre elementos do checklist */
    .collapse .card-body .badge + .btn,
    .collapse .card-body .btn + .badge,
    .collapse .card-body .btn + .btn,
    .checklist-item .badge + .btn,
    .checklist-item .btn + .badge,
    .checklist-item .btn + .btn {
        margin-top: 0.5rem !important;
    }
    
    /* Container específico para área de botões do checklist */
    .collapse .card-body > *:not(.row):not(.col-*):not(.d-flex):not(.btn-group),
    .checklist-item > *:not(.row):not(.col-*):not(.d-flex):not(.btn-group) {
        width: 100% !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* Progress bars em mobile */
    .progress {
        height: 6px;
        margin-bottom: 0.5rem;
    }
    
    /* Accordion melhorado para mobile */
    .collapse .card-body {
        padding: 0.75rem;
    }
    
    /* Texto de status */
    .text-muted {
        font-size: 0.8rem;
    }
    
    /* Melhor aproveitamento horizontal */
    .row {
        margin-left: -0.25rem;
        margin-right: -0.25rem;
    }
    
    .row > [class*="col-"] {
        padding-left: 0.25rem;
        padding-right: 0.25rem;
    }
    
    /* Espaçamento entre elementos */
    .gap-3 {
        gap: 0.5rem !important;
    }
    
    .gap-2 {
        gap: 0.375rem !important;
    }
}

/* ===== MELHORIAS PARA TELAS MUITO PEQUENAS ===== */
@media (max-width: 575.98px) {
    .container-fluid {
        padding-left: 0.25rem;
        padding-right: 0.25rem;
    }
    
    .card {
        margin-bottom: 0.75rem;
    }
    
    .card-header {
        padding: 0.75rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    /* Headers ainda menores */
    .card-header h4 {
        font-size: 1rem !important;
    }
    
    .card-header h5 {
        font-size: 0.9rem !important;
    }
    
    /* Badges e botões compactos */
    .badge {
        font-size: 0.65rem !important;
        padding: 0.2rem 0.4rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.4rem;
        font-size: 0.75rem;
    }
    
    /* Texto ainda mais compacto */
    .equipment-header h6 {
        font-size: 0.85rem;
    }
    
    small, .small {
        font-size: 0.75rem;
    }
}

/* ===== CORES DINÂMICAS DE STATUS POR AREA ===== */
.area-header-pending {
    background-color: #6c757d !important; /* Cinza para áreas não iniciadas */
    background: linear-gradient(135deg, #6c757d, #495057) !important;
}

.area-header-progress {
    background-color: #0dcaf0 !important; /* Azul para em andamento */
    background: linear-gradient(135deg, #0dcaf0, #0aa2c0) !important;
}

.area-header-completed {
    background-color: #198754 !important; /* Verde para concluído */
    background: linear-gradient(135deg, #198754, #146c43) !important;
}

/* ===== TOAST NOTIFICATIONS ===== */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.toast {
    background-color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-radius: 8px;
    overflow: hidden;
}

.toast-header {
    background-color: rgba(255,255,255,0.95);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.toast-success .toast-header {
    color: #198754;
}

.toast-error .toast-header {
    color: #dc3545;
}

/* ===== TRANSIÇÕES ===== */
.transition-colors {
    transition: background-color 0.5s ease, background 0.5s ease;
}

/* ===== DONUT CHARTS (CSS PURO) ===== */
.donut-chart {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #e9ecef; /* Cor de fundo (incompleto) */
    transition: all 0.5s ease;
}

/* O "furo" do donut */
.donut-chart::after {
    content: "";
    width: 20px;
    height: 20px;
    background: white; /* Cor do centro do donut */
    border-radius: 50%;
    position: absolute;
}

/* O texto dentro do furo (opcional, aqui usado apenas para mostrar % se for maior) */
.donut-chart span {
    display: none; 
}

/* Modificador para fundo escuro em headers coloridos */
.card-header .donut-chart::after {
    background: transparent; /* Transparente para herdar a cor do header, criando efeito de anel simples */
}

/* Criar um fundo branco atrás do donut para contraste em headers coloridos */
.donut-bg-wrapper {
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    padding: 2px;
}

/* ===== LAYOUT MODERNO E RESPONSIVO PARA A VIEW DE PARADA ===== */
.container.my-4 {
    max-width: 1100px;
}

.area-card {
    border: none;
    background: linear-gradient(180deg, #ffffff, #f8fdff);
}

.area-card .card-header {
    padding: 1rem 1.25rem !important;
}

.area-card .card-header h5 {
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.3px;
    color: #ffffff !important;
}

.area-card .card-header .fa-map-marker-alt {
    color: rgba(255,255,255,0.95) !important;
    font-size: 1.15rem !important;
}

.area-progress-badge {
    background: rgba(255,255,255,0.95) !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    border-radius: 18px;
    padding: 0.4rem 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,0.3);
}

/* Cor e estilo para o rótulo da área (local) no header */
.card-header .area-label {
    color: #ffffff !important; /* branco para contraste com fundo colorido */
    font-weight: 700 !important;
    text-shadow: 0 1px 3px rgba(0,0,0,0.3) !important; /* sombra para legibilidade */
}

/* Garantir que o h5 dentro do card-header também seja branco */
.card-header.area-header-pending h5,
.card-header.area-header-progress h5,
.card-header.area-header-completed h5 {
    color: #ffffff !important;
}

.card-header.area-header-pending .fa-map-marker-alt,
.card-header.area-header-progress .fa-map-marker-alt,
.card-header.area-header-completed .fa-map-marker-alt {
    color: rgba(255,255,255,0.95) !important;
}

/* Separador entre nome do equipamento e checklist */
.equipment-header {
    position: relative;
    padding-bottom: 0.9rem; /* garantir espaço interno */
}
.equipment-header::after {
    content: "";
    position: absolute;
    left: 1rem;
    right: 1rem;
    bottom: 0.35rem;
    height: 1px;
    background: linear-gradient(90deg, rgba(0,0,0,0.04), rgba(0,0,0,0.02));
}

.checklist-content {
    margin-top: 0.9rem; /* separar visualmente do header */
}

/* Porcentagem no header (visual mais consistente) */
.area-percent-text {
    background: var(--accent-cyan, #17a2b8);
    color: #fff;
    padding: 0.25rem 0.6rem;
    border-radius: 12px;
    font-size: 0.85rem;
    margin-left: 0.5rem;
}

/* Desktop: melhore organização dos equipamentos em grid e dos cards */
@media (min-width: 992px) {
    /* Tornar equipamentos visualmente agrupados; duas colunas com gap */
    .area-card .card-body .row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        align-items: start;
    }

    .equipment-card {
        min-height: auto;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        margin-bottom: 0;
        border: 1px solid rgba(23, 162, 184, 0.15);
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        background: #ffffff;
    }
    
    .equipment-card:hover {
        border-color: rgba(23, 162, 184, 0.35);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.12);
        transform: translateY(-1px);
    }

    .equipment-header {
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-bottom: 2px solid rgba(23, 162, 184, 0.1);
        border-radius: 10px 10px 0 0;
    }

    .equipment-body {
        padding: 1rem 1.25rem;
    }
    
    .area-card .card-body {
        padding: 1.75rem 1.5rem;
    }
    
    .equipment-header h6 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #2c3e50;
    }
}

/* Em mobile, reduzir o espaçamento para ajustar melhor sem perder leitura */
@media (max-width: 767.98px) {
    .equipment-header::after { left: 0.75rem; right: 0.75rem; }
    .area-label { font-size: 0.95rem; }
}

.donut-chart {
    width: 34px;
    height: 34px;
}

@media (max-width: 767.98px) {
    .donut-chart { width: 28px; height: 28px; }
    .container.my-4 { padding-left: 12px; padding-right: 12px; }
    .card { margin-bottom: 1rem; }
}

/* Tornar equipamentos mais compactos visualmente */
.equipment-card { padding: 0; }
.equipment-header { padding: 0.75rem 1rem; }
.equipment-body { padding: 0.85rem 1rem; }

/* Botões mais discretos e com sombra sutil */
.btn-outline-light { box-shadow: none; }

</style>
@endsection

@section('content')
    <div class="container my-4">
    <!-- Header da Parada -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="w-100 w-md-auto">
                        <h4 class="mb-0 fs-5 fs-md-4">
                            <i class="fas fa-industry me-2"></i>{{ $parada->nome }}
                        </h4>
                        <small class="d-block d-md-inline">Macro: {{ $parada->macro }} <span class="d-none d-md-inline">|</span><br class="d-md-none"> Tipo: {{ ucfirst($parada->tipo) }}</small>
                    </div>
                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2 gap-md-3 w-100 w-md-auto">
                        <div class="btn-group btn-group-sm d-none d-md-flex">
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="expandirTodos()" title="Expandir todos os equipamentos">
                                <i class="fas fa-expand-arrows-alt me-1"></i>Expandir Todos
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="colapsarTodos()" title="Colapsar todos os equipamentos">
                                <i class="fas fa-compress-arrows-alt me-1"></i>Colapsar Todos
                            </button>
                        </div>
                        <!-- Versão mobile dos botões -->
                        <div class="d-flex d-md-none gap-1 w-100">
                            <button type="button" class="btn btn-outline-light btn-sm flex-fill" onclick="expandirTodos()" title="Expandir todos">
                                <i class="fas fa-expand-arrows-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm flex-fill" onclick="colapsarTodos()" title="Colapsar todos">
                                <i class="fas fa-compress-arrows-alt"></i>
                            </button>
                        </div>
                        
                        @if($parada->status === 'concluida')
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Parada Finalizada
                                </span>
                                @if(session('user.perfil') === 'admin')
                                    <button type="button" class="btn btn-warning btn-sm" onclick="reabrirParada()" title="Reabrir esta parada (Apenas Administrador)">
                                        <i class="fas fa-unlock me-1"></i>Reabrir Parada
                                    </button>
                                @endif
                            </div>
                        @else
                            @if($percentualGeral == 100)
                                <button type="button" class="btn btn-success btn-sm" onclick="finalizarParada()" title="Finalizar esta parada">
                                    <i class="fas fa-flag-checkered me-1"></i>Finalizar Parada
                                </button>
                            @endif
                        @endif
                        
                        <span id="parada_progress_info" class="badge bg-light text-dark fs-6">{{ $parada->testes_ok }} resolvido(s) • {{ $parada->testes_pendentes }} pendente(s)</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Data de Início:</strong> {{ \Carbon\Carbon::parse($parada->data_inicio)->format('d/m/Y') }}</p>
                            <p><strong>Duração Prevista:</strong> {{ $parada->duracao_prevista_horas ?? 'N/A' }} horas</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Equipe Responsável:</strong> {{ $parada->equipe_responsavel ?? 'N/A' }}</p>
                            <p><strong>Descrição:</strong> {{ $parada->descricao ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botões de Navegação -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="parada-actions">
                <a href="{{ route('paradas.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar para Paradas Ativas
                </a>
                <a href="{{ route('paradas.relatorio', $parada) }}" class="btn btn-info">
                    <i class="fas fa-chart-bar me-1"></i>Ver Relatório Completo
                </a>
                @if($parada->status === 'concluida')
                    <a href="{{ route('paradas.historico') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-history me-1"></i>Ver Histórico
                    </a>
                @endif
            </div>
        </div>
    </div>

    @php
        $paradaFinalizada = $parada->status === 'concluida';
    @endphp

    @if($paradaFinalizada)
        <!-- Aviso de Parada Finalizada -->
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <h5 class="alert-heading mb-1">
                        <i class="fas fa-flag-checkered me-2"></i>Parada Finalizada
                    </h5>
                    <p class="mb-0">
                        Esta parada foi finalizada em <strong>{{ $parada->data_fim ? $parada->data_fim->format('d/m/Y \à\s H:i') : 'data não registrada' }}</strong>.
                        <br>Não é possível realizar alterações nos checklists. Esta página está em modo de visualização.
                        @if(session('user.perfil') === 'admin')
                            <br><small class="text-muted"><i class="fas fa-key me-1"></i>Como administrador, você pode reabrir esta parada usando o botão "Reabrir Parada" no cabeçalho.</small>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(isset($semEquipamentos) && $semEquipamentos)
        <!-- Mensagem quando não há equipamentos -->
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle me-2"></i>Nenhum Equipamento Selecionado</h5>
            <p>Esta parada ainda não possui equipamentos selecionados para checklist.</p>
            <a href="{{ route('paradas.select-equipment', $parada) }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Selecionar Equipamentos
            </a>
        </div>
    @else
        <!-- Toast Container para Notificações -->
        <div class="toast-container">
            <!-- Toast será injetado via JS -->
        </div>

        <!-- Lista de Áreas e Equipamentos -->
        @foreach($areas as $area)
        <!-- Card da Área contendo todos os equipamentos -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card area-card shadow-sm">
                    <!-- Header da Área -->
                    @php
                        $areaTotal = $area->equipamentos->count();
                        $areaConcluidos = $area->equipamentos->filter(function($equip) {
                            $teste = $equip->testes->first();
                            return $teste && intval($teste->checklist_progress) === 100;
                        })->count();
                        $percent = $percentualPorArea[$area->id] ?? 0;
                        
                        // Determinar classe de cor baseada no progresso
                        $headerClass = 'area-header-pending'; // Padrão cinza
                        if ($percent >= 100) {
                            $headerClass = 'area-header-completed'; // Verde
                        } elseif ($percent > 0) {
                            $headerClass = 'area-header-progress'; // Azul
                        }
                    @endphp
                    <div class="card-header text-white {{ $headerClass }} transition-colors" data-area-id="{{ $area->id }}">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 gap-md-0">
                            <h5 class="mb-0 fs-6 fs-md-5">
                                <i class="fas fa-map-marker-alt me-2"></i><span class="area-label">{{ $area->nome }}</span>
                            </h5>
                            <div class="d-flex align-items-center gap-2 gap-md-3 w-100 w-md-auto justify-content-between justify-content-md-end">
                                <div class="btn-group btn-group-sm d-none d-md-flex">
                                    <button type="button" class="btn btn-outline-light btn-sm" onclick="expandirTodosArea('{{ $area->id }}')" title="Expandir todos os equipamentos desta área">
                                        <i class="fas fa-expand-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-light btn-sm" onclick="colapsarTodosArea('{{ $area->id }}')" title="Colapsar todos os equipamentos desta área">
                                        <i class="fas fa-compress-alt"></i>
                                    </button>
                                </div>
                                <!-- Botões mobile para área -->
                                <div class="btn-group btn-group-sm d-flex d-md-none">
                                    <button type="button" class="btn btn-outline-light btn-sm" onclick="expandirTodosArea('{{ $area->id }}')" title="Expandir">
                                        <i class="fas fa-expand-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-light btn-sm" onclick="colapsarTodosArea('{{ $area->id }}')" title="Colapsar">
                                        <i class="fas fa-compress-alt"></i>
                                    </button>
                                </div>
                                @php
                                    $areaTotal = $area->equipamentos->count();
                                    $areaConcluidos = $area->equipamentos->filter(function($equip) {
                                        $teste = $equip->testes->first();
                                        return $teste && intval($teste->checklist_progress) === 100;
                                    })->count();
                                @endphp
                                <span class="badge bg-light text-dark fs-7 fs-md-6 area-progress-badge" data-area-id="{{ $area->id }}">
                                    <span class="area-completed-count">{{ $areaConcluidos }}</span>/<span class="area-total-count">{{ $areaTotal }}</span> concluídos • <span class="area-percent">{{ $percentualPorArea[$area->id] ?? 0 }}%</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Container dos equipamentos dentro do card -->
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Equipamentos da Área (dentro do card) -->
                            @foreach($area->equipamentos as $equipamento)
                                @php $teste = $equipamento->testes->first() @endphp
                                @if($teste)
                                <div class="col-12 col-lg-6">
                                    <div class="equipment-card h-100">
                                        <!-- Header do Equipamento (sempre visível) -->
                                        <div class="equipment-header" 
                                             data-bs-toggle="collapse" 
                                             data-bs-target="#equipamento_{{ $equipamento->id }}_{{ $teste->id }}" 
                                             aria-expanded="false" 
                                             aria-controls="equipamento_{{ $equipamento->id }}_{{ $teste->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-chevron-right me-2 collapse-icon collapsed"></i>
                                                    <i class="fas fa-cog me-2 text-primary"></i>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $equipamento->nome }}</h6>
                                                        <small class="text-muted">({{ $equipamento->tag }})</small>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 80px; height: 6px;">
                                                        <div class="progress-bar bg-{{ $teste->checklist_progress == 100 ? 'success' : ($teste->checklist_progress > 0 ? 'warning' : 'danger') }}" 
                                                             style="width: {{ $teste->checklist_progress }}%"></div>
                                                    </div>
                                                    <span class="badge bg-{{ $teste->checklist_progress == 100 ? 'success' : 'warning' }}">
                                                        {{ $teste->checklist_progress }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Checklist Items (colapsável) -->
                                        <div class="collapse" id="equipamento_{{ $equipamento->id }}_{{ $teste->id }}">
                                            <div class="equipment-body">
                                                @foreach($teste->checklist_items as $key => $item)
                                                <div class="checklist-item" id="item_{{ $key }}_{{ $teste->id }}">
                                                    <div class="checklist-content">
                                                        <div class="checklist-info">
                                                            <i class="{{ $item['icon'] }} me-2 text-primary"></i>
                                                            <span class="fw-bold">{{ $item['label'] }}</span>
                                                            @php
                                                                $statusAtual = $item['status'] ?? 'pendente';
                                                            @endphp
                                                            @if($statusAtual === 'ok')
                                                                <i class="fas fa-check-circle text-success ms-2"></i>
                                                            @elseif($statusAtual === 'problema')
                                                                <i class="fas fa-exclamation-triangle text-warning ms-2"></i>
                                                            @elseif($statusAtual === 'nao_aplica')
                                                                <i class="fas fa-ban text-info ms-2"></i>
                                                            @else
                                                                <i class="fas fa-clock text-warning ms-2"></i>
                                                            @endif
                                                        </div>
                                                        <div class="checklist-actions">
                                                            @php
                                                                $statusAtual = $item['status'] ?? 'pendente';
                                                            @endphp
                                                            
                                                            @if($statusAtual === 'pendente')
                                                                <!-- Status: Pendente - Mostrar botões de ação -->
                                                                @if($paradaFinalizada)
                                                                    <div class="status-badge">
                                                                        <span class="badge bg-warning text-dark">
                                                                            <i class="fas fa-clock me-1"></i>Pendente
                                                                        </span>
                                                                        <small class="text-muted">Parada finalizada</small>
                                                                    </div>
                                                                @else
                                                                    <div class="status-badge">
                                                                        <span class="badge bg-warning text-dark">
                                                                            <i class="fas fa-clock me-1"></i>Status: Pendente
                                                                        </span>
                                                                        <div class="action-buttons">
                                                                            <button class="btn btn-success" onclick="marcarOK('{{ $key }}', {{ $teste->id }}, event); return false;">
                                                                                <i class="fas fa-check me-1"></i>OK
                                                                            </button>
                                                                            <button class="btn btn-danger" onclick="reportarProblema('{{ $key }}', {{ $teste->id }}, event); return false;">
                                                                                <i class="fas fa-exclamation-triangle me-1"></i>Problema
                                                                            </button>
                                                                            <button class="btn btn-info" onclick="marcarNaoAplica('{{ $key }}', {{ $teste->id }}, event); return false;">
                                                                                <i class="fas fa-ban me-1"></i>N/A
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @elseif($statusAtual === 'problema')
                                                                <!-- Status: Problema - Mostrar botão resolver + editar -->
                                                                <div class="status-badge">
                                                                    <span class="badge bg-danger">
                                                                        <i class="fas fa-exclamation-triangle me-1"></i>Status: Com Problema
                                                                    </span>
                                                                    @if($paradaFinalizada)
                                                                        <small class="text-muted">Parada finalizada</small>
                                                                    @else
                                                                        <div class="action-buttons">
                                                                            @if(empty($item['resolucao']))
                                                                                <button class="btn btn-warning" onclick="mostrarResolucao('{{ $key }}', {{ $teste->id }})">
                                                                                    <i class="fas fa-wrench me-1"></i>Resolver
                                                                                </button>
                                                                            @else
                                                                                <button class="btn btn-success" onclick="mostrarResolucao('{{ $key }}', {{ $teste->id }})">
                                                                                    <i class="fas fa-edit me-1"></i>Editar
                                                                                </button>
                                                                            @endif
                                                                            <button class="btn btn-outline-secondary" onclick="editarStatus('{{ $key }}', {{ $teste->id }}, '{{ $statusAtual }}')" title="Alterar status">
                                                                                <i class="fas fa-edit me-1"></i>Alterar
                                                                            </button>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <!-- Status: OK ou N/A - Mostrar badge + botão editar -->
                                                                <div class="status-badge">
                                                                    @if($statusAtual === 'ok')
                                                                        <span class="badge bg-success">
                                                                            <i class="fas fa-check me-1"></i>Status: OK
                                                                        </span>
                                                                    @elseif($statusAtual === 'nao_aplica')
                                                                        <span class="badge bg-info">
                                                                            <i class="fas fa-ban me-1"></i>Status: Não se aplica
                                                                        </span>
                                                                    @endif
                                                                    @if(!$paradaFinalizada)
                                                                        <div class="action-buttons">
                                                                            <button class="btn btn-outline-secondary" onclick="editarStatus('{{ $key }}', {{ $teste->id }}, '{{ $statusAtual }}')" title="Alterar status">
                                                                                <i class="fas fa-edit me-1"></i>Alterar Status
                                                                            </button>
                                                                        </div>
                                                                    @else
                                                                        <small class="text-muted">Parada finalizada</small>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Formulário de Problema (inicialmente oculto) -->
                                                    <div class="problema-form mt-3" id="problema_form_{{ $key }}_{{ $teste->id }}" style="display: none;">
                                                        <div class="card bg-light">
                                                            <div class="card-body">
                                                                <h6 class="text-danger">
                                                                    <i class="fas fa-exclamation-triangle me-2"></i>Reportar Problema
                                                                </h6>
                                                                <form id="form_problema_{{ $key }}_{{ $teste->id }}">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Descrição do Problema:</label>
                                                                        <textarea class="form-control" name="problema" rows="3" required placeholder="Descreva o problema encontrado..."></textarea>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Foto (Opcional):</label>
                                                                        <input type="file" class="form-control" name="foto" accept="image/*">
                                                                    </div>
                                                                    <div class="d-flex gap-2">
                                                                        <button type="button" class="btn btn-primary" onclick="salvarProblema('{{ $key }}', {{ $teste->id }})">
                                                                            <i class="fas fa-save me-1"></i>Salvar Problema
                                                                        </button>
                                                                        <button type="button" class="btn btn-secondary" onclick="cancelarProblema('{{ $key }}', {{ $teste->id }})">
                                                                            <i class="fas fa-times me-1"></i>Cancelar
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Formulário de Resolução (inicialmente oculto) -->
                                                    <div class="resolucao-form mt-3" id="resolucao_form_{{ $key }}_{{ $teste->id }}" style="display: none;">
                                                        <div class="card bg-light">
                                                            <div class="card-body">
                                                                <h6 class="text-success">
                                                                    <i class="fas fa-wrench me-2"></i>Resolver Problema
                                                                </h6>
                                                                <form id="form_resolucao_{{ $key }}_{{ $teste->id }}">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Descrição da Resolução:</label>
                                                                        <textarea class="form-control" name="resolucao" rows="3" required placeholder="Descreva como o problema foi resolvido..."></textarea>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Foto da Resolução (Opcional):</label>
                                                                        <input type="file" class="form-control" name="foto" accept="image/*">
                                                                    </div>
                                                                    <div class="resolucao-error text-danger small mb-2" style="display:none"></div>
                                                                    <div class="d-flex gap-2">
                                                                        <button type="button" class="btn btn-success" onclick="salvarResolucao('{{ $key }}', {{ $teste->id }})">
                                                                            <i class="fas fa-check me-1"></i>Marcar como Resolvido
                                                                        </button>
                                                                        <button type="button" class="btn btn-secondary" onclick="cancelarResolucao('{{ $key }}', {{ $teste->id }})">
                                                                            <i class="fas fa-times me-1"></i>Cancelar
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>

                                            <!-- Histórico de Problemas -->
                                            @php
                                                $historico = collect($teste->checklist_items)->filter(function($item) {
                                                    return !empty($item['problema']) || !empty($item['resolucao']);
                                                });
                                            @endphp

                                            @if($historico->count() > 0)
                                            <div class="historico-section mt-4">
                                                <h6 class="text-primary mb-3">
                                                    <i class="fas fa-history me-2"></i>Histórico de Problemas
                                                </h6>
                                                @foreach($historico as $key => $item)
                                                <div class="historico-item">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="{{ $item['icon'] }} me-2 text-primary"></i>
                                                        <strong>{{ $item['label'] }}</strong>
                                                        @if(!empty($item['resolucao']))
                                                            <span class="badge bg-success ms-2">Resolvido</span>
                                                        @else
                                                            <span class="badge bg-warning ms-2">Pendente</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if(!empty($item['problema']))
                                                    <div class="problema-info mb-2">
                                                        <strong class="text-danger">Problema:</strong>
                                                        <p class="mb-1">{{ $item['problema'] }}</p>
                                                        @if(!empty($item['foto_problema_path']))
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="mostrarFoto('{{ Storage::url($item['foto_problema_path']) }}', 'Foto do Problema')">
                                                            <i class="fas fa-camera me-1"></i>Ver Foto do Problema
                                                        </button>
                                                        @endif
                                                    </div>
                                                    @endif
                                                    
                                                    @if(!empty($item['resolucao']))
                                                    <div class="resolucao-info">
                                                        <strong class="text-success">Resolução:</strong>
                                                        <p class="mb-1">{{ $item['resolucao'] }}</p>
                                                        @if(!empty($item['foto_resolucao_path']))
                                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="mostrarFoto('{{ Storage::url($item['foto_resolucao_path']) }}', 'Foto da Resolução')">
                                                            <i class="fas fa-camera me-1"></i>Ver Foto da Resolução
                                                        </button>
                                                        @endif
                                                    </div>
                                                    @endif
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

<!-- Modal para visualizar fotos -->
<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalTitle">Visualizar Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fotoModalImage" src="" class="img-fluid" alt="Foto">
            </div>
        </div>
    </div>
</div>

<script>
// Funções para expandir/colapsar todos os equipamentos de uma área
function expandirTodosArea(areaId) {
    const areaCard = document.querySelector(`[data-area-id="${areaId}"]`).closest('.area-card');
    const collapseElements = areaCard.querySelectorAll('.collapse');
    
    collapseElements.forEach(function(collapseEl) {
        const bsCollapse = new bootstrap.Collapse(collapseEl, {show: true});
        bsCollapse.show();
    });
    
    // Salvar estado após expansão
    setTimeout(saveCollapseState, 500);
}

function colapsarTodosArea(areaId) {
    const areaCard = document.querySelector(`[data-area-id="${areaId}"]`).closest('.area-card');
    const collapseElements = areaCard.querySelectorAll('.collapse');
    
    collapseElements.forEach(function(collapseEl) {
        const bsCollapse = new bootstrap.Collapse(collapseEl, {show: false});
        bsCollapse.hide();
    });
    
    // Salvar estado após colapso
    setTimeout(saveCollapseState, 500);
}

// Funções globais para expandir/colapsar todos os equipamentos
function expandirTodos() {
    const collapseElements = document.querySelectorAll('.collapse');
    
    collapseElements.forEach(function(collapseEl) {
        if (!collapseEl.classList.contains('show')) {
            const bsCollapse = new bootstrap.Collapse(collapseEl, {show: true});
            bsCollapse.show();
        }
    });
    
    // Salvar estado após expansão
    setTimeout(saveCollapseState, 800);
}

function colapsarTodos() {
    const collapseElements = document.querySelectorAll('.collapse');
    
    collapseElements.forEach(function(collapseEl) {
        if (collapseEl.classList.contains('show')) {
            const bsCollapse = new bootstrap.Collapse(collapseEl, {show: false});
            bsCollapse.hide();
        }
    });
    
    // Salvar estado após colapso
    setTimeout(saveCollapseState, 800);
}

// Função para atualizar interface do item dinamicamente
function atualizarInterfaceItem(item, testeId, novoStatus, data) {
    const itemElement = document.getElementById(`item_${item}_${testeId}`);
    if (!itemElement) return;
    
    const checklistInfo = itemElement.querySelector('.checklist-info');
    const checklistActions = itemElement.querySelector('.checklist-actions');
    
    // Remover ícones de status existentes
    const existingIcons = checklistInfo.querySelectorAll('.fas.fa-check-circle, .fas.fa-exclamation-triangle, .fas.fa-ban');
    existingIcons.forEach(icon => icon.remove());
    
    // Adicionar novo ícone baseado no status
    let novoIcone = '';
    if (novoStatus === 'ok') {
        novoIcone = '<i class="fas fa-check-circle text-success ms-2"></i>';
    } else if (novoStatus === 'problema') {
        novoIcone = '<i class="fas fa-exclamation-triangle text-warning ms-2"></i>';
    } else if (novoStatus === 'nao_aplica') {
        novoIcone = '<i class="fas fa-ban text-info ms-2"></i>';
    }
    
    if (novoIcone) {
        checklistInfo.insertAdjacentHTML('beforeend', novoIcone);
    }
    
    // Atualizar botões de ação baseado no novo status
    let novosButtonsHtml = '';
    
    if (novoStatus === 'pendente') {
        novosButtonsHtml = `
            <div class="status-badge">
                <span class="badge bg-warning text-dark me-2">
                    <i class="fas fa-clock me-1"></i>Status: Pendente
                </span>
                <div class="action-buttons">
                    <button class="btn btn-success btn-sm" onclick="marcarOK('${item}', ${testeId}, event); return false;">
                        <i class="fas fa-check me-1"></i>OK
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="reportarProblema('${item}', ${testeId}, event); return false;">
                        <i class="fas fa-exclamation-triangle me-1"></i>Problema
                    </button>
                    <button class="btn btn-info btn-sm" onclick="marcarNaoAplica('${item}', ${testeId}, event); return false;">
                        <i class="fas fa-ban me-1"></i>N/A
                    </button>
                </div>
            </div>
        `;
    } else if (novoStatus === 'ok') {
        novosButtonsHtml = `
            <div class="status-badge">
                <span class="badge bg-success me-2">
                    <i class="fas fa-check me-1"></i>OK
                </span>
                <button class="btn btn-outline-secondary btn-sm" onclick="editarStatus('${item}', ${testeId}, '${novoStatus}')" title="Alterar status">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        `;
    } else if (novoStatus === 'nao_aplica') {
        novosButtonsHtml = `
            <div class="status-badge">
                <span class="badge bg-info me-2">
                    <i class="fas fa-ban me-1"></i>Não se aplica
                </span>
                <button class="btn btn-outline-secondary btn-sm" onclick="editarStatus('${item}', ${testeId}, '${novoStatus}')" title="Alterar status">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        `;
    } else if (novoStatus === 'problema') {
        novosButtonsHtml = `
            <div class="status-badge">
                <span class="badge bg-danger me-2">
                    <i class="fas fa-exclamation-triangle me-1"></i>Com Problema
                </span>
                <button class="btn btn-warning btn-sm" onclick="mostrarResolucao('${item}', ${testeId})">
                    <i class="fas fa-wrench me-1"></i>Resolver Problema
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="editarStatus('${item}', ${testeId}, '${novoStatus}')" title="Alterar status">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        `;
    }
    
    checklistActions.innerHTML = novosButtonsHtml;
    
    // Gerenciar histórico baseado no novo status
    if (data.equipamento_id) {
        gerenciarHistoricoStatus(item, testeId, novoStatus, data.equipamento_id);
    }
    
    // Destacar item alterado temporariamente
    itemElement.style.backgroundColor = '#d4edda';
    itemElement.style.border = '2px solid #28a745';
    
    setTimeout(() => {
        itemElement.style.backgroundColor = '';
        itemElement.style.border = '';
    }, 2000);
}

// Função para atualizar histórico dinamicamente
function atualizarHistorico(item, testeId, data) {
    const equipmentBody = document.querySelector(`#equipamento_${data.equipamento_id}_${testeId} .equipment-body`);
    if (!equipmentBody) return;
    
    // Verificar se já existe seção de histórico
    let historicoSection = equipmentBody.querySelector('.historico-section');
    
    // Se não existe, criar
    if (!historicoSection) {
        const historicoHtml = `
            <div class="historico-section mt-4">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-history me-2"></i>Histórico de Problemas
                </h6>
            </div>
        `;
        equipmentBody.insertAdjacentHTML('beforeend', historicoHtml);
        historicoSection = equipmentBody.querySelector('.historico-section');
    }
    
    // Procurar item existente no histórico
    let historicoItem = historicoSection.querySelector(`[data-item="${item}"]`);
    
    // Se não existe, criar
    if (!historicoItem) {
        const itemData = getItemData(item);
        const historicoItemHtml = `
            <div class="historico-item" data-item="${item}">
                <div class="d-flex align-items-center mb-2">
                    <i class="${itemData.icon} me-2 text-primary"></i>
                    <strong>${itemData.label}</strong>
                    <span class="badge bg-warning ms-2 status-badge">Pendente</span>
                </div>
            </div>
        `;
        historicoSection.insertAdjacentHTML('beforeend', historicoItemHtml);
        historicoItem = historicoSection.querySelector(`[data-item="${item}"]`);
    }
    
    // Atualizar conteúdo do problema
    if (data.problema) {
        // Remover problema existente se houver
        const problemaExistente = historicoItem.querySelector('.problema-info');
        if (problemaExistente) {
            problemaExistente.remove();
        }
        
        let problemaHtml = `
            <div class="problema-info mb-2">
                <strong class="text-danger">Problema:</strong>
                <p class="mb-1">${data.problema}</p>
        `;
        
        if (data.foto_problema_path) {
            // Garantir que a URL da foto comece com /storage/
            const fotoUrl = data.foto_problema_path.startsWith('/storage/') ? 
                data.foto_problema_path : 
                `/storage/${data.foto_problema_path.replace(/^\//, '')}`;
            
            problemaHtml += `
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="mostrarFoto('${fotoUrl}', 'Foto do Problema')">
                    <i class="fas fa-camera me-1"></i>Ver Foto do Problema
                </button>
            `;
        }
        
        problemaHtml += `</div>`;
        historicoItem.insertAdjacentHTML('beforeend', problemaHtml);
    }
    
    // Atualizar conteúdo da resolução
    if (data.resolucao) {
        // Remover resolução existente se houver
        const resolucaoExistente = historicoItem.querySelector('.resolucao-info');
        if (resolucaoExistente) {
            resolucaoExistente.remove();
        }
        
        let resolucaoHtml = `
            <div class="resolucao-info">
                <strong class="text-success">Resolução:</strong>
                <p class="mb-1">${data.resolucao}</p>
        `;
        
        if (data.foto_resolucao_path) {
            // Garantir que a URL da foto comece com /storage/
            const fotoUrl = data.foto_resolucao_path.startsWith('/storage/') ? 
                data.foto_resolucao_path : 
                `/storage/${data.foto_resolucao_path.replace(/^\//, '')}`;
            
            resolucaoHtml += `
                <button type="button" class="btn btn-sm btn-outline-success" onclick="mostrarFoto('${fotoUrl}', 'Foto da Resolução')">
                    <i class="fas fa-camera me-1"></i>Ver Foto da Resolução
                </button>
            `;
        }
        
        resolucaoHtml += `</div>`;
        historicoItem.insertAdjacentHTML('beforeend', resolucaoHtml);
        
        // Atualizar badge para resolvido
        const statusBadge = historicoItem.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.className = 'badge bg-success ms-2 status-badge';
            statusBadge.textContent = 'Resolvido';
        }
    } else if (data.problema && !data.resolucao) {
        // Apenas problema sem resolução - manter como pendente
        const statusBadge = historicoItem.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.className = 'badge bg-warning ms-2 status-badge';
            statusBadge.textContent = 'Pendente';
        }
    }
}

// Função para obter dados do item (ícone e label)
function getItemData(item) {
    const itemsData = {
        'ar_comprimido': { icon: 'fas fa-wind', label: 'Ar Comprimido OK' },
        'protecoes_eletricas': { icon: 'fas fa-bolt', label: 'Proteções Elétricas OK' },
        'protecoes_mecanicas': { icon: 'fas fa-cog', label: 'Proteções Mecânicas OK' },
        'chave_remoto': { icon: 'fas fa-key', label: 'Chave Remoto Testada' },
        'inspecionado': { icon: 'fas fa-search', label: 'Inspecionado' }
    };
    
    return itemsData[item] || { icon: 'fas fa-check', label: item };
}

// Função para gerenciar histórico baseado no status
function gerenciarHistoricoStatus(item, testeId, novoStatus, equipamentoId) {
    const equipmentBody = document.querySelector(`#equipamento_${equipamentoId}_${testeId} .equipment-body`);
    if (!equipmentBody) return;
    
    let historicoSection = equipmentBody.querySelector('.historico-section');
    let historicoItem = historicoSection?.querySelector(`[data-item="${item}"]`);
    
    if (novoStatus === 'pendente') {
        // Se mudou para pendente, remover completamente do histórico
        if (historicoItem) {
            historicoItem.remove();
            
            // Se não há mais itens no histórico, remover a seção inteira
            const remainingItems = historicoSection.querySelectorAll('[data-item]');
            if (remainingItems.length === 0) {
                historicoSection.remove();
            }
        }
    } else if (novoStatus === 'ok' || novoStatus === 'nao_aplica') {
        // Se mudou para OK ou N/A sem ter histórico de problema, não precisa fazer nada
        // O histórico só existe se teve problema antes
    }
    
    console.log(`Histórico gerenciado para ${item}: status ${novoStatus}`);
}

// Função para atualizar interface quando problema é salvo
function atualizarInterfaceProblema(item, testeId, data) {
    const itemElement = document.getElementById(`item_${item}_${testeId}`);
    if (!itemElement) return;
    
    const checklistInfo = itemElement.querySelector('.checklist-info');
    const checklistActions = itemElement.querySelector('.checklist-actions');
    
    // Remover ícones de status existentes
    const existingIcons = checklistInfo.querySelectorAll('.fas.fa-check-circle, .fas.fa-exclamation-triangle, .fas.fa-ban');
    existingIcons.forEach(icon => icon.remove());
    
    // Adicionar ícone de problema
    checklistInfo.insertAdjacentHTML('beforeend', '<i class="fas fa-exclamation-triangle text-warning ms-2"></i>');
    
    // Atualizar botões para mostrar que tem problema
    const novosButtonsHtml = `
        <div class="status-badge">
            <span class="badge bg-danger me-2">
                <i class="fas fa-exclamation-triangle me-1"></i>Com Problema
            </span>
            <button class="btn btn-warning btn-sm" onclick="mostrarResolucao('${item}', testeId)">
                <i class="fas fa-wrench me-1"></i>Resolver Problema
            </button>
            <button class="btn btn-outline-secondary btn-sm" onclick="editarStatus('${item}', testeId, 'problema')" title="Alterar status">
                <i class="fas fa-edit"></i>
            </button>
        </div>
    `;
    
    checklistActions.innerHTML = novosButtonsHtml;
    
    // Atualizar progresso do equipamento
    if (data.progress !== undefined && data.equipamento_id) {
        atualizarProgressoEquipamento(testeId, data.equipamento_id, data.progress);
    }
    
    // Atualizar histórico
    atualizarHistorico(item, testeId, data);
    
    // Destacar item
    itemElement.style.backgroundColor = '#fff3cd';
    itemElement.style.border = '2px solid #ffc107';
    
    setTimeout(() => {
        itemElement.style.backgroundColor = '';
        itemElement.style.border = '';
    }, 3000);
}

// Função para atualizar interface quando problema é resolvido
function atualizarInterfaceResolucao(item, testeId, data) {
    const itemElement = document.getElementById(`item_${item}_${testeId}`);
    if (!itemElement) return;
    
    const checklistInfo = itemElement.querySelector('.checklist-info');
    const checklistActions = itemElement.querySelector('.checklist-actions');
    
    // Remover ícones de status existentes
    const existingIcons = checklistInfo.querySelectorAll('.fas.fa-check-circle, .fas.fa-exclamation-triangle, .fas.fa-ban');
    existingIcons.forEach(icon => icon.remove());
    
    // Adicionar ícone de OK (problema foi resolvido = status OK)
    checklistInfo.insertAdjacentHTML('beforeend', '<i class="fas fa-check-circle text-success ms-2"></i>');
    
    // Atualizar botões para mostrar status OK com histórico de problema resolvido
    const novosButtonsHtml = `
        <div class="status-badge">
            <span class="badge bg-success me-2">
                <i class="fas fa-check me-1"></i>OK
            </span>
            <button class="btn btn-outline-secondary btn-sm" onclick="editarStatus('${item}', ${testeId}, 'ok')" title="Alterar status">
                <i class="fas fa-edit"></i>
            </button>
        </div>
    `;
    
    checklistActions.innerHTML = novosButtonsHtml;
    
    // Atualizar progresso do equipamento
    if (data.progress !== undefined && data.equipamento_id) {
        atualizarProgressoEquipamento(testeId, data.equipamento_id, data.progress);
    }
    
    // Atualizar histórico com resolução
    atualizarHistorico(item, testeId, data);
    
    // Destacar item com cor de sucesso
    itemElement.style.backgroundColor = '#d4edda';
    itemElement.style.border = '2px solid #28a745';
    
    setTimeout(() => {
        itemElement.style.backgroundColor = '';
        itemElement.style.border = '';
    }, 3000);
}

// Função para atualizar progresso do equipamento específico
function atualizarProgressoEquipamento(testeId, equipamentoId, novoProgresso) {
    // Encontrar o header do equipamento usando o padrão do data-bs-target
    const equipamentoHeader = document.querySelector(`[data-bs-target="#equipamento_${equipamentoId}_${testeId}"]`);
    
    if (equipamentoHeader) {
        const progressBar = equipamentoHeader.querySelector('.progress-bar');
        const badge = equipamentoHeader.querySelector('.badge');
        
        if (progressBar) {
            progressBar.style.width = `${novoProgresso}%`;
            progressBar.className = `progress-bar bg-${novoProgresso == 100 ? 'success' : (novoProgresso > 0 ? 'warning' : 'danger')}`;
        }
        
        if (badge) {
            badge.textContent = `${novoProgresso}%`;
            badge.className = `badge bg-${novoProgresso == 100 ? 'success' : 'warning'}`;
        }
        
        console.log(`Progresso do equipamento ${equipamentoId} atualizado para ${novoProgresso}%`);
    } else {
        console.warn(`Header do equipamento ${equipamentoId} não encontrado`);
    }
}

// Função para atualizar progresso geral da parada
function atualizarProgressoGeral() {
    fetch(`{{ route('paradas.progresso', $parada) }}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar percentual geral no header (mantém compatibilidade) e contagens
            const percentualGeral = document.querySelector('.card-header .badge');
            if (percentualGeral) {
                percentualGeral.textContent = `${data.percentual}% Completo`;
            }

            // Atualizar texto com contagens (resolvidos / pendentes)
            const progressInfo = document.getElementById('parada_progress_info');
            if (progressInfo && data.total_testes !== undefined) {
                progressInfo.textContent = `${data.testes_ok} resolvido(s) • ${data.testes_pendentes} pendente(s)`;
            }
            
            // Atualizar percentuais das áreas
            if (data.areas && Array.isArray(data.areas)) {
                data.areas.forEach(area => {
                    // Atualizar badge percentual (se existir)
                    const areaBadgePercent = document.querySelector(`.card-header[data-area-id="${area.id}"] .badge`);
                    if (areaBadgePercent && area.percentual !== undefined) {
                        areaBadgePercent.textContent = `${area.percentual}% Completo`;
                    }

                    // Atualizar badge de contagem (novo formato X/Y concluídos)
                    const areaBadgeCount = document.querySelector(`.area-progress-badge[data-area-id="${area.id}"]`);
                    if (areaBadgeCount) {
                        const completed = area.testes_ok !== undefined ? area.testes_ok : (area.testes_ok_count || 0);
                        const total = area.total_testes !== undefined ? area.total_testes : (area.total || 0);
                        const percentualArea = area.percentual !== undefined ? area.percentual : 0;
                        areaBadgeCount.innerHTML = `<span class="area-completed-count">${completed}</span>/<span class="area-total-count">${total}</span> concluídos • <span class="area-percent">${percentualArea}%</span>`;
                    }
                });
            }
            
            console.log('Progresso atualizado:', data.percentual + '%');
        }
    })
    .catch(error => {
        console.error('Erro ao atualizar progresso:', error);
    });
}

// Atualiza o progresso geral usando diretamente os dados já retornados pela chamada de alteração de status,
// evitando nova requisição quando possível (atualização imediata do DOM).
function atualizarProgressoGeralComData(data) {
    if (!data) return atualizarProgressoGeral();

    // Atualizar percentual geral no header (mantém compatibilidade)
    const percentualGeral = document.querySelector('.card-header .badge');
    if (percentualGeral && data.percentual !== undefined) {
        percentualGeral.textContent = `${data.percentual}% Completo`;
    }

    // Atualizar texto com contagens (resolvidos / pendentes)
    const progressInfo = document.getElementById('parada_progress_info');
    if (progressInfo && data.testes_ok !== undefined && data.testes_pendentes !== undefined) {
        progressInfo.textContent = `${data.testes_ok} resolvido(s) • ${data.testes_pendentes} pendente(s)`;
    }

    // Atualizar percentuais e contagens das áreas se fornecidas
    if (data.areas && Array.isArray(data.areas)) {
        data.areas.forEach(area => {
            const areaBadgePercent = document.querySelector(`.card-header[data-area-id="${area.id}"] .badge`);
            if (areaBadgePercent && area.percentual !== undefined) {
                areaBadgePercent.textContent = `${area.percentual}% Completo`;
            }

            const areaBadgeCount = document.querySelector(`.area-progress-badge[data-area-id="${area.id}"]`);
            if (areaBadgeCount) {
                const completed = area.testes_ok !== undefined ? area.testes_ok : (area.testes_ok_count || 0);
                const total = area.total_testes !== undefined ? area.total_testes : (area.total || 0);
                const percentualArea = area.percentual !== undefined ? area.percentual : 0;
                areaBadgeCount.innerHTML = `<span class="area-completed-count">${completed}</span>/<span class="area-total-count">${total}</span> concluídos • <span class="area-percent">${percentualArea}%</span>`;
            }
        });
    }

    console.log('Progresso atualizado (via payload):', data.percentual !== undefined ? data.percentual + '%' : 'n/a');
}

// Variável global para verificar se parada está finalizada
const paradaFinalizada = @json($paradaFinalizada);

// Função para verificar se operações estão bloqueadas
function verificarOperacaoPermitida(operacao) {
    if (paradaFinalizada) {
        alert('Não é possível realizar esta operação. A parada já foi finalizada.');
        return false;
    }
    return true;
}

// Função para marcar item como OK
function marcarOK(item, testeId, event) {
    if (!verificarOperacaoPermitida('marcar como OK')) return;
    
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    fetch(`/testes/${testeId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            item: item,
            status: 'ok'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar progresso do equipamento
            if (data.progress !== undefined && data.equipamento_id) {
                atualizarProgressoEquipamento(testeId, data.equipamento_id, data.progress);
            }
            
            // Atualizar interface do item dinamicamente
            atualizarInterfaceItem(item, testeId, 'ok', data);
            
            // Atualizar progresso geral usando os dados retornados
            atualizarProgressoGeralComData(data);
        } else {
            alert('Erro ao atualizar status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
    });
    
    return false;
}

// Função para marcar item como "Não se aplica"
function marcarNaoAplica(item, testeId, event) {
    if (!verificarOperacaoPermitida('marcar como N/A')) return;
    
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    if (confirm('Tem certeza que deseja marcar este item como "Não se aplica"?')) {
        fetch(`/testes/${testeId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                item: item,
                status: 'nao_aplica'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Atualizar progresso do equipamento
                if (data.progress !== undefined && data.equipamento_id) {
                    atualizarProgressoEquipamento(testeId, data.equipamento_id, data.progress);
                }
                
                // Atualizar interface do item dinamicamente
                atualizarInterfaceItem(item, testeId, 'nao_aplica', data);
                
                // Atualizar progresso geral usando os dados retornados
                atualizarProgressoGeralComData(data);
            } else {
                alert('Erro ao atualizar status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao conectar com o servidor');
        });
    }
    
    return false;
}

// Função para mostrar formulário de problema
function reportarProblema(item, testeId, event) {
    if (!verificarOperacaoPermitida('reportar problema')) return;
    
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    // Primeiro atualizar o status para 'problema'
    fetch(`/testes/${testeId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            item: item,
            status: 'problema'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Após atualizar o status, mostrar o formulário
            document.getElementById(`problema_form_${item}_${testeId}`).style.display = 'block';
        } else {
            alert('Erro ao atualizar status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
    });
    
    return false;
}


// Função para editar status de um item
function editarStatus(item, testeId, statusAtual) {
    if (!verificarOperacaoPermitida('editar status')) return;
    
    // Verificar se o Bootstrap está carregado
    if (typeof bootstrap === 'undefined') {
        alert('Bootstrap não está carregado. Por favor, recarregue a página.');
        return;
    }
    
    const opcoes = {
        'pendente': 'Pendente',
        'ok': 'OK',
        'problema': 'Problema',
        'nao_aplica': 'Não se aplica'
    };
    
    let opcoesHtml = '';
    for (let [valor, label] of Object.entries(opcoes)) {
        const selected = valor === statusAtual ? 'selected' : '';
        opcoesHtml += `<option value="${valor}" ${selected}>${label}</option>`;
    }
    
    // Obter o nome do item
    const itemElement = document.querySelector('#item_' + item + '_' + testeId + ' .fw-bold');
    const itemNome = itemElement ? itemElement.textContent : 'Item não encontrado';
    
    const modal = '<div class="modal fade" id="modalEditarStatus" tabindex="-1">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<h5 class="modal-title">Alterar Status do Item</h5>' +
        '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>' +
        '</div>' +
        '<div class="modal-body">' +
        '<p><strong>Item:</strong> ' + itemNome + '</p>' +
        '<div class="mb-3">' +
        '<label class="form-label">Novo Status:</label>' +
        '<select class="form-select" id="novoStatus">' +
        opcoesHtml +
        '</select>' +
        '</div>' +
        '<div class="alert alert-warning">' +
        '<i class="fas fa-exclamation-triangle me-2"></i>' +
        '<strong>Atenção:</strong> Alterar o status pode afetar dados já salvos (problemas, resoluções, fotos).' +
        '</div>' +
        '</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>' +
        '<button type="button" class="btn btn-primary" onclick="confirmarAlteracaoStatus(\'' + item + '\', ' + testeId + ', \'' + statusAtual + '\')">' +
        'Alterar Status' +
        '</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
    
    // Remove modal existente se houver
    const modalExistente = document.getElementById('modalEditarStatus');
    if (modalExistente) {
        modalExistente.remove();
    }
    
    // Adiciona o modal ao body
    document.body.insertAdjacentHTML('beforeend', modal);
    
    // Mostra o modal
    try {
        const modalElement = document.getElementById('modalEditarStatus');
        console.log('Modal element:', modalElement);
        
        const modalBootstrap = new bootstrap.Modal(modalElement);
        console.log('Modal instance criada:', modalBootstrap);
        
        modalBootstrap.show();
        console.log('Modal.show() chamado');
    } catch (error) {
        console.error('Erro ao mostrar modal:', error);
        alert('Erro ao abrir modal: ' + error.message);
    }
    
    // Remove o modal do DOM após fechar
    document.getElementById('modalEditarStatus').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

// Função para confirmar alteração de status
function confirmarAlteracaoStatus(item, testeId, statusAnterior) {
    const novoStatus = document.getElementById('novoStatus').value;
    
    if (novoStatus === statusAnterior) {
        bootstrap.Modal.getInstance(document.getElementById('modalEditarStatus')).hide();
        return;
    }
    
    // Verificar se está mudando de 'problema' para outro status
    if (statusAnterior === 'problema' && novoStatus !== 'problema') {
        if (!confirm('Alterar o status de "Problema" pode remover dados salvos (descrição e fotos do problema). Deseja continuar?')) {
            return;
        }
    }
    
    // Se o novo status for 'problema', mostrar formulário
    if (novoStatus === 'problema') {
        bootstrap.Modal.getInstance(document.getElementById('modalEditarStatus')).hide();
        setTimeout(() => {
            reportarProblema(item, testeId);
        }, 500);
        return;
    }
    
    // Para outros status, fazer a alteração diretamente
    fetch(`/testes/${testeId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            item: item,
            status: novoStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalEditarStatus')).hide();
            
            // Atualizar interface dinamicamente
            setTimeout(() => {
                // Atualizar progresso do equipamento
                if (data.progress !== undefined && data.equipamento_id) {
                    atualizarProgressoEquipamento(testeId, data.equipamento_id, data.progress);
                }
                
                atualizarInterfaceItem(item, testeId, novoStatus, data);
                
                // Atualizar progresso geral usando os dados retornados
                atualizarProgressoGeralComData(data);
                
                // Mostrar feedback visual elegante
                showToast('Status atualizado com sucesso!', 'success');
            }, 300);
        } else {
            showToast('Erro ao alterar status: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao conectar com o servidor', 'error');
    });
}

// Função para cancelar problema
function cancelarProblema(item, testeId) {
    document.getElementById(`problema_form_${item}_${testeId}`).style.display = 'none';
}

// Função para salvar problema
function salvarProblema(item, testeId) {
    if (!verificarOperacaoPermitida('salvar problema')) return;
    
    const form = document.getElementById(`form_problema_${item}_${testeId}`);
    const formData = new FormData(form);
    formData.append('item', item);

    fetch(`/testes/${testeId}/problema`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Esconder formulário de problema
            document.getElementById(`problema_form_${item}_${testeId}`).style.display = 'none';
            
            // Atualizar interface para mostrar que tem problema
            atualizarInterfaceProblema(item, testeId, data);
            
            // Atualizar progresso geral usando os dados retornados
            atualizarProgressoGeralComData(data);
            
            alert('Problema salvo com sucesso!');
        } else {
            alert('Erro ao salvar problema: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
    });
}

// Função para mostrar formulário de resolução
function mostrarResolucao(item, testeId) {
    if (!verificarOperacaoPermitida('mostrar resolução')) return;
    
    document.getElementById(`resolucao_form_${item}_${testeId}`).style.display = 'block';
}

// Função para cancelar resolução
function cancelarResolucao(item, testeId) {
    document.getElementById(`resolucao_form_${item}_${testeId}`).style.display = 'none';
}

// Função para salvar resolução
function salvarResolucao(item, testeId) {
    if (!verificarOperacaoPermitida('salvar resolução')) return;
    
    const form = document.getElementById(`form_resolucao_${item}_${testeId}`);
    // Validação no cliente: tamanho máximo 2MB e tipos permitidos (mensagem inline)
    const fileInput = form.querySelector('input[type="file"]');
    const errorEl = form.querySelector('.resolucao-error');
    if (errorEl) { errorEl.style.display = 'none'; errorEl.textContent = ''; }
    if (fileInput && fileInput.files && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (file.size > maxSize) {
            if (errorEl) {
                errorEl.textContent = 'O arquivo é muito grande. Tamanho máximo permitido: 2MB.';
                errorEl.style.display = 'block';
            } else {
                alert('O arquivo é muito grande. Tamanho máximo permitido: 2MB.');
            }
            return;
        }
        if (!allowedTypes.includes(file.type)) {
            if (errorEl) {
                errorEl.textContent = 'Tipo de arquivo inválido. Apenas imagens (jpg, jpeg, png, gif) são permitidas.';
                errorEl.style.display = 'block';
            } else {
                alert('Tipo de arquivo inválido. Apenas imagens (jpg, jpeg, png, gif) são permitidas.');
            }
            return;
        }
    }

    const formData = new FormData(form);
    formData.append('item', item);

    fetch(`/testes/${testeId}/resolver-problema`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Esconder formulário de resolução
            document.getElementById(`resolucao_form_${item}_${testeId}`).style.display = 'none';
            
            // Atualizar interface para mostrar que foi resolvido
            atualizarInterfaceResolucao(item, testeId, data);
            
            // Atualizar progresso geral usando os dados retornados
            atualizarProgressoGeralComData(data);
            
            alert('Problema resolvido com sucesso!');
        } else {
            alert('Erro ao resolver problema: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
    });
}

// Função para mostrar foto no modal
function mostrarFoto(url, titulo) {
    document.getElementById('fotoModalTitle').textContent = titulo;
    document.getElementById('fotoModalImage').src = url;
    new bootstrap.Modal(document.getElementById('fotoModal')).show();
}

/* ===== FUNÇÃO DE TOAST (NOVA) ===== */
function showToast(message, type = 'success') {
    const toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) return;
    
    // Ícone baseado no tipo
    const icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-times-circle' : 'fa-info-circle');
    const colorClass = type === 'success' ? 'text-success' : (type === 'error' ? 'text-danger' : 'text-info');
    const title = type === 'success' ? 'Sucesso' : (type === 'error' ? 'Erro' : 'Informação');
    
    // Criar elemento do toast
    const toastHtml = `
        <div class="toast ${type === 'success' ? 'toast-success' : (type === 'error' ? 'toast-error' : '')}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas ${icon} ${colorClass} me-2"></i>
                <strong class="me-auto">${title}</strong>
                <small class="text-muted">agora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    // Inserir no container
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Inicializar e mostrar
    const toastEl = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
    toast.show();
    
    // Remover do DOM após fechar
    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
    });
}

// Função para atualizar histórico após salvar
function atualizarHistoricoAposSalvar(item, testeId, data) {
    console.log('Atualizando histórico após salvar:', { item, testeId, data });
    
    // Se existe foto_problema_path na resposta, adicionar botão imediatamente
    if (data.foto_problema_path) {
        // Buscar ou criar seção de histórico
        let historicoSection = document.querySelector('.historico-section');
        if (!historicoSection) {
            // Criar seção de histórico se não existir
            const equipmentCard = document.querySelector('.equipment-card');
            if (equipmentCard) {
                const historicoHtml = `
                    <div class="historico-section mt-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-history me-2"></i>Histórico de Problemas
                        </h6>
                        <div id="historico-container"></div>
                    </div>
                `;
                equipmentCard.insertAdjacentHTML('beforeend', historicoHtml);
                historicoSection = equipmentCard.querySelector('.historico-section');
            }
        }
        
        if (historicoSection) {
            const container = historicoSection.querySelector('#historico-container') || historicoSection;
            
            // Procurar item existente ou criar novo
            let itemHistorico = document.getElementById(`historico-${item}-${testeId}`);
            if (!itemHistorico) {
                const itemHtml = `
                    <div class="historico-item p-3 mb-3 border rounded bg-light" id="historico-${item}-${testeId}">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-wind me-2 text-primary"></i>
                            <strong>${item.replace('_', ' ')}</strong>
                            <span class="badge bg-warning ms-2">Pendente</span>
                        </div>
                        <div class="problema-info mb-2">
                            <strong class="text-danger">Problema:</strong>
                            <p class="mb-1" id="problema-text-${item}-${testeId}">Carregando...</p>
                            <div id="foto-container-${item}-${testeId}"></div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHtml);
                itemHistorico = document.getElementById(`historico-${item}-${testeId}`);
            }
            
            // Adicionar botão de foto imediatamente
            const fotoContainer = itemHistorico.querySelector(`#foto-container-${item}-${testeId}`);
            if (fotoContainer && data.foto_problema_path) {
                const fotoUrl = data.foto_problema_path.startsWith('/storage/') ? 
                data.foto_problema_path : 
                `/storage/${data.foto_problema_path.replace(/^\//, '')}`;
                fotoContainer.innerHTML = `
                    <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="mostrarFoto('${fotoUrl}', 'Foto do Problema')">
                        <i class="fas fa-camera me-1"></i>Ver Foto do Problema
                    </button>
                    <small class="text-success d-block mt-1">✓ Foto salva: ${data.foto_problema_path}</small>
                `;
                console.log('Botão de foto adicionado imediatamente:', fotoUrl);
            }
        }
    }
    
    // Similar para foto de resolução
    if (data.foto_resolucao_path) {
        console.log('Adicionando foto de resolução:', data.foto_resolucao_path);
        // Lógica similar para foto de resolução...
    }
}
</script>
    </div>
@endsection
