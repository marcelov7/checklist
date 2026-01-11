<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token">
    <title>@yield('title', 'Sistema de Checklist de Paradas')</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Sistema de checklist para paradas industriais com funcionamento offline">
    <meta name="theme-color" content="#007bff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Checklist">
    <meta name="msapplication-TileColor" content="#007bff">
    <meta name="msapplication-config" content="/browserconfig.xml">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Icons -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.ico">
    
    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Esquema de Cores Azul/Ciano - Controle de Forcing Style */
        :root {
            --primary-blue: #2c5aa0;
            --secondary-blue: #1e3d72;
            --accent-cyan: #17a2b8;
            --light-blue: #e3f2fd;
            --success-cyan: #00bcd4;
            --warning-amber: #ff9800;
            --danger-red: #e53e3e;
            --dark-blue: #1a365d;
        }
        
        /* Layout Base - Mobile First */
        .navbar {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)) !important;
            border: none;
        }
        
        .navbar-brand {
            background: transparent !important;
            border: none;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--light-blue), #f0f8ff);
            font-weight: 600;
            border-bottom: 2px solid var(--accent-cyan);
            color: var(--secondary-blue);
        }
        
        /* Corrigir cores específicas para card-headers com classes de cor */
        .card-header.bg-primary {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)) !important;
            color: white !important;
        }
        
        .card-header.bg-secondary {
            background: linear-gradient(135deg, #6c757d, #495057) !important;
            color: white !important;
        }
        
        .card-header.bg-success {
            background: linear-gradient(135deg, #198754, #146c43) !important;
            color: white !important;
        }
        
        .progress-card {
            border-left: 4px solid var(--accent-cyan);
            box-shadow: 0 2px 10px rgba(44, 90, 160, 0.1);
        }
        
        .status-badge {
            font-size: 0.8rem;
        }
        
        .equipamento-row {
            transition: all 0.3s ease;
        }
        .equipamento-row:hover {
            background: linear-gradient(135deg, var(--light-blue), #f8fafc);
            transform: translateY(-1px);
        }
        
        .teste-form {
            display: none;
        }
        
        .area-card {
            border-left: 4px solid var(--success-cyan);
            background: linear-gradient(135deg, #ffffff, #f0fdff);
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--secondary-blue), var(--dark-blue));
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            padding: 14px 24px;
            display: block;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin: 2px 8px;
            border-radius: 6px;
        }
        
        .sidebar a:hover {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.2), rgba(44, 90, 160, 0.2));
            color: #ffffff;
            border-left: 3px solid var(--accent-cyan);
            transform: translateX(2px);
        }
        
        .sidebar .active {
            background: linear-gradient(135deg, var(--accent-cyan), var(--success-cyan));
            border-left: 3px solid #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        /* Botões com tema azul */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-cyan));
            border: none;
            box-shadow: 0 4px 15px rgba(44, 90, 160, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-blue), var(--primary-blue));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 90, 160, 0.4);
        }
        
        .btn-outline-primary {
            border-color: var(--accent-cyan);
            color: var(--primary-blue);
        }
        
        .btn-outline-primary:hover {
            background: var(--accent-cyan);
            border-color: var(--accent-cyan);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-cyan), #00acc1);
            border: none;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-amber), #fb8c00);
            border: none;
        }
        
        /* Cards com tema */
        .card {
            border: 1px solid rgba(44, 90, 160, 0.1);
            box-shadow: 0 4px 20px rgba(44, 90, 160, 0.08);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(44, 90, 160, 0.15);
        }
        
        /* Badges com cores do tema */
        .badge.bg-primary {
            background: var(--primary-blue) !important;
        }
        
        .badge.bg-info {
            background: var(--accent-cyan) !important;
        }
        
        .badge.bg-success {
            background: var(--success-cyan) !important;
        }
        
        /* Badge especial para status implementado */
        .badge-implementado {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
            color: white;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        /* Badge especial para departamentos */
        .badge-departamento {
            background: linear-gradient(135deg, var(--accent-cyan), #00bcd4) !important;
            color: white;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
        }
        
        /* Progress bars */
        .progress-bar {
            background: linear-gradient(135deg, var(--accent-cyan), var(--success-cyan));
        }
        
        /* User Menu Navbar Styles */
        .navbar .nav-link:hover {
            background: rgba(255,255,255,0.2) !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Input Group Fix - Substituir estilos conflitantes */
        .input-group {
            position: relative;
            display: flex;
            flex-wrap: nowrap;
            align-items: stretch;
            width: 100%;
        }
        
        .input-group > .form-control {
            position: relative;
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
            margin-bottom: 0;
        }
        
        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #6c757d;
            text-align: center;
            white-space: nowrap;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }

        /* Mobile: disable animations/transforms and improve touch behavior to avoid
           screen movement when tapping elements */
        @media (max-width: 991.98px) {
            *, *::before, *::after {
                transition: none !important;
                animation: none !important;
                transform: none !important;
            }

            html, body {
                -webkit-tap-highlight-color: transparent;
                overscroll-behavior-y: contain;
                touch-action: manipulation;
            }

            a, button, input, label, .btn, .card, .equipment-shell, .equipment-card, .checklist-item {
                -webkit-tap-highlight-color: transparent;
                touch-action: manipulation;
                will-change: auto !important;
            }

            /* Remove scale/translate on :active to avoid visual jump */
            a:active, button:active, .btn:active, .card:active {
                transform: none !important;
                outline: none !important;
            }
        }
        
        /* Navbar superior */
        .mobile-navbar {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            box-shadow: 0 2px 10px rgba(44, 90, 160, 0.3);
        }

        /* ===== MOBILE OPTIMIZATIONS ===== */
        
        /* Mobile Layout (≤767px) */
        @media (max-width: 767px) {
            /* Reset body padding for mobile */
            body {
                padding-top: 0;
                font-size: 14px;
            }
            
            /* Navbar mobile */
            .navbar {
                padding: 0.5rem 1rem;
                min-height: 56px;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                z-index: 1100 !important;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            .navbar-brand {
                font-size: 1rem;
                font-weight: 700;
            }
            
            /* Sidebar mobile como overlay */
            .sidebar {
                position: fixed !important;
                top: 0 !important;
                left: -300px !important;
                width: 300px !important;
                height: 100vh !important;
                z-index: 1200 !important;
                transition: left 0.3s ease !important;
                padding-top: 76px !important; /* altura da navbar */
                background: linear-gradient(180deg, var(--secondary-blue), var(--dark-blue)) !important;
                box-shadow: 2px 0 10px rgba(0,0,0,0.3) !important;
            }
            
            .sidebar.show {
                left: 0 !important;
            }
            
            /* Conteúdo principal mobile */
            .main-content {
                margin-left: 0 !important;
                padding: 15px !important;
                margin-top: 76px !important; /* espaço para navbar mobile - ajustado para altura correta */
                width: 100% !important;
                min-height: calc(100vh - 76px) !important;
            }
            
            /* Links da sidebar mais espaçados */
            .sidebar a {
                padding: 15px 20px;
                font-size: 1rem;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            
            .sidebar a:hover {
                transform: none; /* Remove animação em mobile */
                padding-left: 25px;
            }
            
            /* Títulos mobile */
            h1 {
                font-size: 1.4rem;
                font-weight: 600;
                line-height: 1.3;
            }
            
            h2 {
                font-size: 1.3rem;
            }
            
            h3 {
                font-size: 1.2rem;
            }
            
            h4, h5, h6 {
                font-size: 1rem;
            }
            
            /* Botões mobile */
            .btn {
                padding: 12px 16px;
                font-size: 14px;
                min-height: 44px; /* Touch target */
                border-radius: 8px;
            }
            
            .btn-lg {
                padding: 14px 20px;
                font-size: 16px;
                min-height: 48px;
            }
            
            .btn-sm {
                padding: 8px 12px;
                font-size: 13px;
                min-height: 36px;
            }
            
            /* Cards mobile */
            .card {
                border-radius: 12px;
                margin-bottom: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .card-header {
                padding: 0.75rem 1rem;
            }
            
            /* Table responsive improvements */
            .table-responsive {
                border-radius: 8px;
            }
            
            .table td, .table th {
                padding: 0.5rem;
                font-size: 13px;
                vertical-align: middle;
            }
            
            /* Form improvements */
            .form-control, .form-select {
                min-height: 44px;
                font-size: 16px; /* Evita zoom iOS */
                border-radius: 8px;
            }
            
            .form-label {
                font-weight: 600;
                margin-bottom: 0.5rem;
            }
            
            /* Badge improvements */
            .badge {
                font-size: 12px;
                padding: 4px 8px;
            }
            
            /* Progress bar mobile */
            .progress {
                height: 8px;
                border-radius: 4px;
            }
        }
        
        /* ===== MOBILE TABLE CARDS ===== */
        @media (max-width: 767px) {
            /* Ocultar tabelas e mostrar cards em mobile */
            .table-responsive .table {
                display: none;
            }
            
            .mobile-card-list {
                display: block;
            }
            
            .mobile-user-card, .mobile-parada-card {
                border: 1px solid rgba(44, 90, 160, 0.1);
                border-radius: 12px;
                background: #ffffff;
                box-shadow: 0 2px 10px rgba(44, 90, 160, 0.08);
                margin-bottom: 1rem;
                padding: 1rem;
                transition: all 0.3s ease;
            }
            
            .mobile-user-card:hover, .mobile-parada-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(44, 90, 160, 0.15);
            }
            
            .mobile-card-header {
                display: flex;
                align-items: center;
                margin-bottom: 0.75rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid rgba(0,0,0,0.1);
            }
            
            .mobile-card-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: var(--primary-blue);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                margin-right: 0.75rem;
                font-size: 16px;
            }
            
            .mobile-card-info {
                flex: 1;
            }
            
            .mobile-card-title {
                font-weight: 600;
                margin-bottom: 2px;
                color: var(--secondary-blue);
            }
            
            .mobile-card-subtitle {
                font-size: 13px;
                color: #6c757d;
                margin: 0;
            }
            
            .mobile-card-details {
                margin: 0.75rem 0;
            }
            
            .mobile-detail-item {
                display: flex;
                align-items: center;
                margin-bottom: 0.5rem;
                font-size: 13px;
            }
            
            .mobile-detail-item:last-child {
                margin-bottom: 0;
            }
            
            .mobile-detail-icon {
                width: 16px;
                margin-right: 8px;
                color: var(--accent-cyan);
            }
            
            .mobile-card-actions {
                display: flex;
                gap: 0.5rem;
                margin-top: 1rem;
                flex-wrap: wrap;
            }
            
            .mobile-card-actions .btn {
                flex: 1;
                min-width: 80px;
            }
        }
        
        /* Desktop: ocultar mobile cards */
        @media (min-width: 768px) {
            .mobile-card-list {
                display: none;
            }
        }

        /* ===== DASHBOARD STATISTICS CARDS ===== */
        .stat-card {
            border: none;
            border-radius: 15px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 4px 20px rgba(44, 90, 160, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--success-cyan));
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(44, 90, 160, 0.2);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        /* ===== FLOATING ACTION BUTTON ===== */
        .fab-mobile {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-cyan));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(44, 90, 160, 0.3);
            z-index: 1000;
            transition: all 0.3s ease;
            font-size: 20px;
        }
        
        .fab-mobile:hover, .fab-mobile:focus {
            transform: scale(1.1);
            color: white;
            box-shadow: 0 6px 25px rgba(44, 90, 160, 0.6);
        }
        
        .fab-mobile:active {
            transform: scale(0.95);
        }
        
        /* ===== MOBILE FILTERS ===== */
        @media (max-width: 767px) {
            .mobile-filter-toggle {
                background: var(--light-blue);
                border: 1px solid var(--accent-cyan);
                border-radius: 8px;
                padding: 8px 12px;
                margin-bottom: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .mobile-filter-content {
                display: none;
                margin-top: 1rem;
            }
            
            .mobile-filter-content.show {
                display: block;
                animation: slideDown 0.3s ease;
            }
            
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            /* Sidebar overlay */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1150;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            /* Mobile improvements for text and spacing */
            .text-truncate-mobile {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                max-width: 150px;
            }
            
            /* Navbar responsiva melhorada */
            @media (max-width: 767.98px) {
                .navbar {
                    padding: 0.5rem 1rem;
                    min-height: 60px !important;
                }
                
                .navbar-brand {
                    font-size: 0.9rem !important;
                    margin-right: 0.5rem;
                }
                
                .navbar-nav {
                    flex-direction: row;
                    align-items: center;
                }
                
                .nav-link {
                    padding: 0.25rem 0.5rem !important;
                    font-size: 0.85rem;
                }
                
                .dropdown-toggle::after {
                    font-size: 0.7rem;
                }
                
                /* Ajustar main-content para altura reduzida da navbar */
                .main-content {
                    margin-top: 60px !important;
                    min-height: calc(100vh - 60px) !important;
                    padding-top: 20px;
                }
            }
            
            @media (max-width: 575.98px) {
                .navbar {
                    padding: 0.25rem 0.5rem;
                    min-height: 56px !important;
                }
                
                .navbar-brand {
                    font-size: 0.8rem !important;
                    margin-right: 0.25rem;
                }
                
                .navbar-toggler {
                    padding: 0.25rem;
                    font-size: 0.8rem;
                }
                
                .nav-link {
                    padding: 0.2rem 0.4rem !important;
                    font-size: 0.8rem;
                }
                
                .text-truncate {
                    max-width: 80px !important;
                }
                
                /* Ajustar main-content para navbar ainda menor */
                .main-content {
                    margin-top: 56px !important;
                    min-height: calc(100vh - 56px) !important;
                    padding-top: 15px;
                }
            }
            
            /* Evitar overflow horizontal */
            .navbar .container-fluid {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            .navbar-nav .nav-item {
                flex-shrink: 0;
            }
            
            .badge {
                font-size: 11px;
                padding: 3px 6px;
                border-radius: 6px;
            }
            
            .small {
                font-size: 12px;
            }
            
            /* Better touch targets */
            .btn-group .btn {
                min-width: 44px;
                min-height: 44px;
            }
            
            /* Alert improvements */
            .alert {
                border-radius: 8px;
                padding: 12px 16px;
                margin-bottom: 16px;
            }
            
            /* Pagination mobile */
            .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .page-link {
                min-width: 44px;
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            /* Input Group Mobile Fixes */
            .input-group {
                flex-wrap: nowrap;
            }
            
            .input-group .form-control {
                flex: 1;
                min-width: 0;
                font-size: 16px; /* Evita zoom no iOS */
            }
            
            .input-group-text {
                padding: 0.375rem 0.75rem;
                font-size: 14px;
                white-space: nowrap;
                border-radius: 0 8px 8px 0;
            }
            
            .input-group .form-control:first-child {
                border-radius: 8px 0 0 8px;
            }
            
            /* DateTime Input Mobile Fixes */
            input[type="datetime-local"] {
                font-size: 16px !important;
                padding: 12px !important;
                min-height: 44px;
                background: white;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }
            
            /* Fix para campos date/time em iOS */
            input[type="datetime-local"]::-webkit-datetime-edit {
                font-size: 16px;
                padding: 0;
            }
            
            input[type="datetime-local"]::-webkit-calendar-picker-indicator {
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTYgMlY2TTEwIDJWNk0xNCAyVjZNMyA4SDE3TTMgMTBIMTdNMyAxMkgxN000IDE0SDE2TTE2IDRINEMzLjQ0NzcyIDQgMyA0LjQ0NzcyIDMgNVYxNUMzIDE1LjU1MjMgMy40NDc3MiAxNiA0IDE2SDE2QzE2LjU1MjMgMTYgMTcgMTUuNTUyMyAxNyAxNVY1QzE3IDQuNDQ3NzIgMTYuNTUyMyA0IDE2IDRaIiBzdHJva2U9IiM2NzY3NjciIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPHN2Zz4K');
                background-size: 18px;
                background-repeat: no-repeat;
                background-position: center;
                width: 30px;
                height: 30px;
                opacity: 0.7;
                cursor: pointer;
            }
            
            /* Number input mobile fixes */
            input[type="number"] {
                font-size: 16px !important;
                -webkit-appearance: none;
                -moz-appearance: textfield;
                appearance: none;
            }
            
            input[type="number"]::-webkit-outer-spin-button,
            input[type="number"]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            
            /* Textarea mobile improvements */
            textarea {
                font-size: 16px !important;
                resize: vertical;
                min-height: 44px;
            }
            
            /* Select mobile improvements */
            select.form-select {
                font-size: 16px !important;
                background-position: right 12px center;
                background-size: 16px;
            }
            
            /* Mobile form section improvements */
            .border.rounded-3 {
                border-radius: 12px !important;
                margin-bottom: 1.5rem;
            }
            
            .border.rounded-3 .row {
                margin: 0;
            }
            
            .border.rounded-3 .col-12 {
                padding-left: 0;
                padding-right: 0;
            }
            
            /* Melhorias para cards de formulário mobile */
            .card-body {
                padding: 1rem;
            }
            
            .card-header {
                padding: 0.75rem 1rem;
                font-size: 14px;
            }
            
            /* Form group spacing mobile */
            .mb-3, .mb-4, .mb-5 {
                margin-bottom: 1rem !important;
            }
            
            /* Button improvements for forms */
            .btn {
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
            }
            
            /* Input focus improvements */
            .form-control:focus, .form-select:focus {
                border-color: var(--accent-cyan);
                box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
            }
            
            /* Sidebar card improvements for better readability */
            .card-body .badge[style*="background: var"] {
                font-weight: 600;
                padding: 6px 10px;
                font-size: 12px;
            }
            
            .card-body strong[style*="color: var"] {
                font-weight: 700;
                font-size: 0.95rem;
            }
            
            /* Ensure consistent text readability */
            .text-muted {
                color: #6c757d !important;
            }
            
            /* Garantir visibilidade de input groups em mobile */
            .input-group {
                display: flex !important;
                width: 100% !important;
            }
            
            .input-group .form-control {
                flex: 1 !important;
                min-width: 0 !important;
                width: auto !important;
            }
            
            .input-group-text {
                flex-shrink: 0 !important;
                white-space: nowrap !important;
                max-width: 80px;
                font-size: 14px;
                padding: 0.375rem 0.5rem;
            }
            
            /* Corrigir problemas específicos com col-md-6 em mobile */
            .col-12.col-md-6 {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            /* Assegurar que campos não sejam cortados */
            .border.rounded-3 {
                overflow: visible !important;
            }
            
            .border.rounded-3 .row {
                overflow: visible !important;
            }
        }
        
        /* Mobile adjustments for stats */
        @media (max-width: 576px) {
            .stat-icon {
                font-size: 2rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            /* Input groups mobile - estilos específicos apenas quando necessário */
            @media (max-width: 576px) {
                .input-group {
                    min-height: 44px;
                }
                
                .input-group .form-control {
                    font-size: 16px; /* Evita zoom no iOS */
                }
                
                .input-group-text {
                    font-size: 14px;
                    padding: 0.375rem 0.5rem;
                }
            }
            
            /* Força visibilidade de campos em containers pequenos */
            .col-12.col-md-6,
            .col-12.col-lg-6 {
                margin-bottom: 1rem;
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
            
            /* Garante espaço adequado para labels */
            .form-label {
                margin-bottom: 0.75rem !important;
                display: block !important;
                font-weight: 600 !important;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
        }

        /* Mobile Cards e Componentes */
        @media (max-width: 576px) {
            /* Cards mobile otimizados */
            .card {
                margin-bottom: 1rem;
                border-radius: 0.75rem;
                box-shadow: 0 4px 12px rgba(44, 90, 160, 0.1);
                border: 1px solid rgba(44, 90, 160, 0.08);
            }
            
            .card-header {
                padding: 1rem;
                border-radius: 0.75rem 0.75rem 0 0;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .card-footer {
                padding: 0.75rem 1rem;
                background: rgba(248, 250, 252, 0.5);
                border-radius: 0 0 0.75rem 0.75rem;
            }
            
            /* Botões mobile touch-friendly */
            .btn {
                padding: 0.75rem 1.25rem;
                min-height: 48px;
                font-weight: 600;
                border-radius: 0.5rem;
                font-size: 0.9rem;
            }
            
            .btn-sm {
                min-height: 40px;
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
            
            .btn-group {
                flex-direction: column;
                width: 100%;
                gap: 0.5rem;
            }
            
            .btn-group .btn {
                border-radius: 0.5rem !important;
                margin-bottom: 0;
            }
            
            /* Formulários mobile */
            .form-control {
                min-height: 48px;
                padding: 0.75rem 1rem;
                font-size: 1rem; /* Evita zoom no iOS */
                border-radius: 0.5rem;
                border: 2px solid rgba(44, 90, 160, 0.1);
            }
            
            .form-control:focus {
                border-color: var(--accent-cyan);
                box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
            }
            
            .form-label {
                font-weight: 600;
                color: var(--primary-blue);
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }
            
            /* Badges mobile */
            .badge {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
                border-radius: 0.375rem;
                font-weight: 600;
            }
            
            /* Spacing mobile */
            .mb-4 {
                margin-bottom: 1.5rem !important;
            }
            
            /* Progress bars mobile */
            .progress {
                height: 8px;
                border-radius: 4px;
                background-color: rgba(44, 90, 160, 0.1);
            }
        }

        /* Mobile Tables */
        @media (max-width: 768px) {
            .table-responsive-mobile {
                display: block;
                width: 100%;
                overflow-x: auto;
                white-space: nowrap;
            }
            .mobile-card-view {
                display: block !important;
            }
            .desktop-table-view {
                display: none !important;
            }
        }
        @media (min-width: 769px) {
            .mobile-card-view {
                display: none !important;
            }
            .desktop-table-view {
                display: block !important;
            }
        }

        /* Mobile Typography */
        @media (max-width: 576px) {
            h1 { font-size: 1.5rem; }
            h2 { font-size: 1.3rem; }
            h3 { font-size: 1.2rem; }
            h4 { font-size: 1.1rem; }
            h5 { font-size: 1rem; }
            .badge { font-size: 0.75rem; }
        }

        /* Mobile Spacing */
        @media (max-width: 576px) {
            .container-fluid { padding: 0.5rem; }
            .mb-4 { margin-bottom: 1.5rem !important; }
            .p-3 { padding: 1rem !important; }
        }

        /* Touch-friendly buttons */
        @media (max-width: 768px) {
            .btn {
                min-height: 44px;
                min-width: 44px;
            }
            .btn-sm {
                min-height: 36px;
                min-width: 36px;
            }
        }

        /* Mobile Progress Bars */
        @media (max-width: 576px) {
            .progress {
                height: 1.2rem;
            }
            .progress-bar {
                font-size: 0.8rem;
                line-height: 1.2rem;
            }
        }

        /* Modal Adjustments for Mobile */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
            .modal-header {
                padding: 0.75rem;
            }
            .modal-body {
                padding: 1rem 0.75rem;
            }
            .modal-footer {
                padding: 0.75rem;
                flex-direction: column;
            }
            .modal-footer .btn {
                width: 100%;
                margin: 0.25rem 0;
            }
        }

        /* Mobile Form Improvements */
        @media (max-width: 576px) {
            .form-control, .form-select {
                font-size: 16px; /* Prevents zoom on iOS */
                min-height: 44px;
            }
            .input-group {
                flex-direction: column;
            }
            .input-group .btn {
                border-radius: 0.375rem !important;
                margin-top: 0.5rem;
            }
        }

        /* Mobile Navigation Bar */
        .mobile-navbar {
            display: none;
            background: #343a40;
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        @media (max-width: 768px) {
            .mobile-navbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }
        .mobile-menu-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .mobile-title {
            color: white;
            font-weight: 600;
            margin: 0;
        }

        /* Sticky action buttons for mobile */
        @media (max-width: 768px) {
            .mobile-actions {
                position: fixed;
                bottom: 1rem;
                right: 1rem;
                z-index: 500;
            }
            .mobile-actions .btn {
                border-radius: 50px;
                width: 56px;
                height: 56px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
        /* ===== LAYOUT PRINCIPAL CORRIGIDO ===== */
        
        /* Base Layout - Mobile First */
        .main-content {
            padding: 20px;
            margin: 0;
            width: 100%;
            min-height: calc(100vh - 76px);
        }
        
        /* Sidebar base styling */
        .sidebar {
            background: linear-gradient(180deg, var(--secondary-blue), var(--dark-blue));
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        /* Desktop Layout (≥768px) */
        @media (min-width: 768px) {
            /* Container principal sem padding lateral */
            .container-fluid {
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
            
            /* Remover margin das rows dentro do container */
            .container-fluid > .row {
                margin: 0;
            }
            
            /* Sidebar fixa no desktop */
            .sidebar {
                position: fixed;
                top: 76px; /* altura da navbar */
                left: 0;
                width: 280px;
                height: calc(100vh - 76px);
                z-index: 1000;
                overflow-y: auto;
                background: linear-gradient(180deg, var(--secondary-blue), var(--dark-blue));
            }
            
            /* Conteúdo principal com margin para sidebar */
            .main-content {
                margin-left: 280px;
                padding: 30px;
                width: calc(100% - 280px);
                min-height: calc(100vh - 76px);
                margin-top: 0; /* body já tem padding-top */
                box-sizing: border-box;
            }
            
            /* Navbar fixa no topo */
            .navbar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1100;
                height: 76px;
            }
            
            /* Cards do dashboard com espaçamento correto */
            .main-content .container-fluid {
                padding: 0;
            }
            
            .main-content .row {
                margin-left: -15px;
                margin-right: -15px;
            }
            
            .main-content [class*="col-"] {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            /* Garantir que cards não vazem da área */
            .main-content .card {
                width: 100%;
                box-sizing: border-box;
            }
        }
        
        /* Large Desktop Layout (≥1200px) */
        @media (min-width: 1200px) {
            .main-content {
                padding: 40px 50px;
                max-width: calc(100vw - 280px);
            }
            
            /* Otimizar cards em telas grandes */
            .stat-card .card-body {
                padding: 2rem;
            }
            
            .stat-number {
                font-size: 3rem;
            }
            
            .stat-icon {
                font-size: 3rem;
            }
        }
        
        /* Extra Large Desktop Layout (≥1400px) */
        @media (min-width: 1400px) {
            .main-content {
                max-width: 1400px;
                margin-left: calc(280px + ((100vw - 1680px) / 2));
            }
        }
        
        /* Tablet Portrait (768px - 991px) */
        @media (min-width: 768px) and (max-width: 991px) {
            .sidebar {
                width: 250px;
            }
            
            .main-content {
                margin-left: 250px;
                width: calc(100% - 250px);
                padding: 20px;
            }
        }
        
        /* Body base styling */
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Desktop body adjustments */
        @media (min-width: 768px) {
            body {
                padding-top: 76px; /* espaço para navbar fixa */
            }
        }
        
        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1150;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        @media (min-width: 768px) {
            .sidebar-overlay {
                display: none !important;
            }
            
            .sidebar {
                display: block !important;
            }
        }
        
        @media (max-width: 767px) {
            .sidebar {
                display: block;
            }
            
            /* Menu hamburger mobile visível */
            .navbar-toggler {
                display: block !important;
                border: none;
                padding: 4px 8px;
            }
            
            .navbar-toggler:focus {
                text-decoration: none;
                outline: 0;
                box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
            }
        }
        
        /* Assegurar que o botão hamburger funcione */
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* ===== CORREÇÕES FINAIS DE LAYOUT ===== */
        
        /* Garantir que não há scroll horizontal */
        html, body {
            overflow-x: hidden;
        }
        
        /* Cards do dashboard sempre responsivos */
        .main-content .row > [class*="col-"] {
            margin-bottom: 1rem;
        }
        
        .main-content .card {
            height: 100%;
        }
        
        /* Melhorar botões em mobile */
        @media (max-width: 576px) {
            .btn {
                padding: 0.75rem 1rem;
                font-size: 1rem;
            }
            
            .btn-sm {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
        }
        
        /* Progress Ring Responsivo */
        .progress-ring {
            width: 60px;
            height: 60px;
        }
        .progress-ring circle {
            transition: stroke-dasharray 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
        
        /* Modal Responsivo */
        .modal-lg {
            max-width: 800px;
        }
        
        /* Mobile - Componentes Específicos */
        @media (max-width: 576px) {
            .main-content {
                padding: 15px 10px;
            }
            
            .progress-ring {
                width: 40px;
                height: 40px;
            }
            
            /* Modais mobile full-screen */
            .modal-lg {
                max-width: 95vw;
                margin: 10px auto;
            }
            
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-content {
                border-radius: 0.75rem;
                border: none;
                box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            }
            
            /* Navegação breadcrumb mobile */
            .breadcrumb {
                background: none;
                padding: 0.5rem 0;
                margin-bottom: 1rem;
            }
            
            .breadcrumb-item {
                font-size: 0.85rem;
            }
            
            /* Área de ação sticky mobile */
            .mobile-actions {
                position: sticky;
                bottom: 0;
                background: white;
                padding: 1rem;
                border-top: 1px solid rgba(44, 90, 160, 0.1);
                margin: 1rem -10px 0;
                border-radius: 1rem 1rem 0 0;
                box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
            }
            
            /* Enhanced Button Styling */
            .btn {
                border-radius: 10px;
                font-weight: 500;
                padding: 12px 24px;
                font-size: 16px;
                min-height: 48px;
                transition: all 0.3s ease;
                border: none;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                position: relative;
                overflow: hidden;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }

            /* Primary Button */
            .btn-primary {
                background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
                color: white;
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #1e4080 0%, #2847a0 100%);
                color: white;
            }

            .btn-primary::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .btn-primary:hover::before {
                left: 100%;
            }

            /* Secondary Button */
            .btn-secondary {
                background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
                color: white;
            }

            .btn-secondary:hover {
                background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
                color: white;
            }

            /* Outline Secondary Button */
            .btn-outline-secondary {
                background: transparent;
                border: 2px solid var(--accent-gray);
                color: var(--accent-gray);
            }

            .btn-outline-secondary:hover {
                background: var(--accent-gray);
                color: white;
                border-color: var(--accent-gray);
            }

            /* Success Button */
            .btn-success {
                background: linear-gradient(135deg, var(--success-green) 0%, #1e7e34 100%);
                color: white;
            }

            .btn-success:hover {
                background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
                color: white;
            }

            /* Warning Button */
            .btn-warning {
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
                color: #212529;
            }

            .btn-warning:hover {
                background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
                color: #212529;
            }

            /* Danger Button */
            .btn-danger {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                color: white;
            }

            .btn-danger:hover {
                background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
                color: white;
            }

            /* Info Button */
            .btn-info {
                background: linear-gradient(135deg, var(--accent-cyan) 0%, #138496 100%);
                color: white;
            }

            .btn-info:hover {
                background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
                color: white;
            }

            /* Large Button Variant */
            .btn-lg {
                padding: 16px 32px;
                font-size: 18px;
                min-height: 56px;
            }

            /* Small Button Variant for Mobile */
            .btn-sm {
                padding: 8px 16px;
                font-size: 14px;
                min-height: 40px;
            }

            /* Button with Icon Spacing */
            .btn i {
                margin-right: 8px;
            }

            .btn i:last-child {
                margin-right: 0;
                margin-left: 8px;
            }

            /* Modal Button Styling */
            .modal-footer .btn {
                min-width: 120px;
                margin: 0 4px;
            }

            /* Responsive Button Adjustments */
            @media (max-width: 768px) {
                .btn {
                    width: 100%;
                    margin-bottom: 10px;
                    padding: 14px 20px;
                    font-size: 16px;
                }
                
                .d-flex.gap-2 {
                    flex-direction: column;
                    gap: 0 !important;
                }
                
                .d-flex.gap-2 .btn {
                    margin-bottom: 12px;
                }
                
                .d-flex.gap-2 .btn:last-child {
                    margin-bottom: 0;
                }

                /* Modal buttons em mobile */
                .modal-footer {
                    flex-direction: column;
                }

                .modal-footer .btn {
                    width: 100%;
                    margin: 4px 0;
                }
            }

            /* Gestos swipe para sidebar */
            .sidebar {
                touch-action: pan-y;
            }
            
            /* Feedback visual para toque */
            .btn:active,
            .card:active,
            .sidebar a:active {
                transform: scale(0.98);
                transition: transform 0.1s ease;
            }
            
            /* Loading states mobile */
            .loading-mobile {
                min-height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            /* FAB (Floating Action Button) - Melhorado para mobile */
            .fab-mobile {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1000;
                width: 56px;
                height: 56px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-cyan) 100%);
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 8px 24px rgba(44, 90, 160, 0.4), 0 4px 12px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                transition: all 0.3s ease;
                text-decoration: none;
                position: relative;
                overflow: hidden;
            }

            .fab-mobile::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
                transition: left 0.6s;
            }
            
            .fab-mobile:hover {
                transform: translateY(-3px) scale(1.05);
                box-shadow: 0 12px 32px rgba(44, 90, 160, 0.5), 0 6px 16px rgba(0, 0, 0, 0.2);
                color: white;
                text-decoration: none;
            }
            
            /* Animação de pulso sutil para chamar atenção */
            .fab-mobile::after {
                content: '';
                position: absolute;
                width: 100%;
                height: 100%;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: fab-pulse 2s infinite;
            }
            
            @keyframes fab-pulse {
                0% {
                    transform: scale(0);
                    opacity: 1;
                }
                70% {
                    transform: scale(1.4);
                    opacity: 0;
                }
                100% {
                    transform: scale(1.4);
                    opacity: 0;
                }
            }
            
            /* Container do FAB com label */
            .fab-container {
                position: fixed;
                bottom: 24px;
                right: 24px;
                z-index: 1000;
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                pointer-events: none; /* Permite cliques apenas no botão */
            }
            
            .fab-container .fab-mobile {
                pointer-events: auto; /* Habilita cliques no botão */
            }
            
            .fab-container .fab-mobile {
                position: relative;
                margin-bottom: 0;
            }
            
            .fab-label {
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
                white-space: nowrap;
                margin-bottom: 8px;
                transform: translateX(-8px);
                opacity: 0;
                animation: fab-label-show 3s ease-in-out 1s infinite;
            }
            
            @keyframes fab-label-show {
                0%, 85% {
                    opacity: 0;
                    transform: translateX(-8px) translateY(5px);
                }
                15%, 70% {
                    opacity: 1;
                    transform: translateX(-8px) translateY(0);
                }
            }

            .fab-mobile:hover::before {
                left: 100%;
            }

            .fab-mobile:active {
                transform: translateY(-1px) scale(1.02);
                box-shadow: 0 6px 20px rgba(44, 90, 160, 0.4);
            }
        }
        
        /* Tablet */
        @media (min-width: 577px) and (max-width: 768px) {
            .main-content {
                padding: 18px 15px;
            }
            
            .progress-ring {
                width: 50px;
                height: 50px;
            }
            
            .modal-lg {
                max-width: 90vw;
            }
            
            /* Cards tablet */
            .card {
                transition: all 0.3s ease;
            }
            
            .card:hover {
                transform: translateY(-2px);
            }
            
            /* Botões tablet */
            .btn {
                min-height: 44px;
                padding: 0.625rem 1.125rem;
            }
            
            /* Grid responsivo tablet */
            .col-sm-6:nth-child(odd) {
                padding-right: 0.75rem;
            }
            
            .col-sm-6:nth-child(even) {
                padding-left: 0.75rem;
            }
        }
        
        /* Melhorias gerais de UX */
        .table-responsive {
            border-radius: 0.375rem;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            .table td, .table th {
                padding: 0.5rem 0.25rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)); box-shadow: 0 2px 10px rgba(44, 90, 160, 0.3); min-height: 76px; z-index: 1100;">
        <div class="container-fluid">
            <button class="navbar-toggler d-md-none me-2" type="button" onclick="toggleMobileMenu()" style="border: none; min-width: 44px;">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <a class="navbar-brand fw-bold flex-grow-1 flex-md-grow-0" href="{{ route('dashboard') }}" style="font-size: 1rem;">
                <i class="fas fa-clipboard-list me-2"></i>
                <span class="d-none d-sm-inline">Sistema de Checklist de Paradas</span>
                <span class="d-inline d-sm-none">Checklist</span>
            </a>
            
            <!-- Desktop User Menu -->
            <div class="navbar-nav ms-auto d-none d-md-flex align-items-center">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-semibold d-flex align-items-center px-3 py-2 rounded-3" href="#" role="button" data-bs-toggle="dropdown" style="background: rgba(255,255,255,0.1); transition: all 0.3s ease; white-space: nowrap;">
                        <i class="fas fa-user-circle me-2 fs-5"></i>
                        <span class="text-truncate" style="max-width: 200px;">{{ session('user.name', 'Usuário') }}</span>
                        <i class="fas fa-chevron-down ms-2 small"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Mobile User Info -->
            <div class="navbar-nav d-md-none d-flex align-items-center ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link text-white d-flex align-items-center px-2 py-1 rounded-2" href="#" role="button" data-bs-toggle="dropdown" style="background: rgba(255,255,255,0.1); min-width: fit-content;">
                        <i class="fas fa-user-circle me-1"></i>
                        <span class="text-truncate fw-medium d-none d-sm-inline" style="max-width: 120px;">{{ session('user.name', 'Usuário') }}</span>
                        <i class="fas fa-chevron-down ms-1 small"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" onclick="closeMobileMenu()"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="sidebar p-0" id="sidebar">
                <div class="px-4 py-4 text-white border-bottom border-secondary" style="margin: 0 8px;">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Menu Principal
                    </h6>
                </div>
                <nav class="py-2">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('paradas.index') }}" class="{{ request()->routeIs('paradas.index') || request()->routeIs('paradas.show') || request()->routeIs('paradas.create') || request()->routeIs('paradas.edit') ? 'active' : '' }}">
                        <i class="fas fa-play-circle me-2"></i> Paradas Ativas
                    </a>
                    <a href="{{ route('paradas.historico') }}" class="{{ request()->routeIs('paradas.historico') || request()->routeIs('paradas.relatorio') ? 'active' : '' }}">
                        <i class="fas fa-history me-2"></i> Histórico
                    </a>
                    <a href="{{ route('areas.index') }}" class="{{ request()->routeIs('areas.*') ? 'active' : '' }}">
                        <i class="fas fa-building me-2"></i> Áreas
                    </a>
                    <a href="{{ route('equipamentos.index') }}" class="{{ request()->routeIs('equipamentos.*') ? 'active' : '' }}">
                        <i class="fas fa-tools me-2"></i> Equipamentos
                    </a>
                    
                    <!-- Divider -->
                    <div style="height: 1px; background: rgba(255,255,255,0.2); margin: 12px 24px;"></div>
                    
                    <!-- Profile Management -->
                    <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle me-2"></i> Meu Perfil
                    </a>
                    
                    <!-- User Management (Admin Only) -->
                    @if(session('user.perfil') === 'admin')
                        <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                            <i class="fas fa-users me-2"></i> Gerenciar Usuários
                        </a>
                    @endif
                    
                    <!-- Mobile Logout Link -->
                    <a href="{{ route('logout') }}" class="d-md-none" style="border-top: 1px solid rgba(255,255,255,0.2); margin-top: 12px; padding-top: 12px;">
                        <i class="fas fa-sign-out-alt me-2"></i> Sair
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/mobile-fixes.js') }}"></script>
    <script>
        $(document).ready(function() {
            // CSRF Token para requisições AJAX
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var csrfParam = $('meta[name="csrf-param"]').attr('content');
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            // Adicionar token CSRF a todos os formulários
            $('form').each(function() {
                var token = $('<input>', {
                    type: 'hidden',
                    name: csrfParam,
                    value: csrfToken
                });
                $(this).append(token);
            });
            
            // Função para atualizar CSRF token
            function refreshCSRFToken() {
                $.get('/refresh-csrf')
                    .done(function(response) {
                        if (response.token) {
                            // Atualizar variáveis
                            csrfToken = response.token;
                            
                            // Atualizar meta tag
                            $('meta[name="csrf-token"]').attr('content', csrfToken);
                            
                            // Atualizar todos os inputs hidden do token
                            $('input[name="' + csrfParam + '"]').val(csrfToken);
                            
                            // Atualizar o header do $.ajaxSetup
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });
                            
                            console.log('Token CSRF atualizado com sucesso');
                        }
                    })
                    .fail(function(error) {
                        console.error('Erro ao atualizar token CSRF:', error);
                        if (error.status === 419) {
                            // Recarregar a página apenas se o token estiver realmente inválido
                            location.reload();
                        }
                    });
            }
            
            // Interceptar erros de CSRF em requisições AJAX
            $(document).ajaxError(function(event, jqXHR, settings, error) {
                if (jqXHR.status === 419) { // Token CSRF expirado
                    refreshCSRFToken();
                }
            });
            
            // Detectar se está em mobile
            var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            // Interceptar envio de formulários para verificar token
            $('form').on('submit', function(e) {
                let $form = $(this);
                let tokenInput = $form.find('input[name="' + csrfParam + '"]');
                
                // Se não houver input de token, criar um
                if (tokenInput.length === 0) {
                    tokenInput = $('<input>', {
                        type: 'hidden',
                        name: csrfParam,
                        value: csrfToken
                    });
                    $form.append(tokenInput);
                }
                
                // Em mobile, sempre buscar token fresco antes de submeter
                if (isMobile && !$form.data('csrf-refreshed')) {
                    e.preventDefault();
                    
                    $.get('/refresh-csrf')
                        .done(function(response) {
                            if (response.token) {
                                csrfToken = response.token;
                                $('meta[name="csrf-token"]').attr('content', csrfToken);
                                tokenInput.val(csrfToken);
                                $form.data('csrf-refreshed', true);
                                $form.submit();
                            }
                        })
                        .fail(function() {
                            // Se falhar, tentar com token atual
                            tokenInput.val(csrfToken);
                            $form.data('csrf-refreshed', true);
                            $form.submit();
                        });
                    return false;
                }
                
                // Verificar se o token está válido
                let token = tokenInput.val();
                if (!token || token.length === 0 || token !== csrfToken) {
                    tokenInput.val(csrfToken);
                }
                
                // Limpar flag após submit
                $form.data('csrf-refreshed', false);
            });
            
            // Atualizar token periodicamente (a cada 30 minutos)
            setInterval(refreshCSRFToken, 30 * 60 * 1000);
            
            // Atualizar token ao retomar foco na página
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    refreshCSRFToken();
                }
            });
        });

        // Mobile Menu Functions
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (sidebar && overlay) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                
                // Prevent body scroll when menu is open
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : 'auto';
                
                // Força a atualização do layout
                sidebar.style.display = 'block';
                setTimeout(() => {
                    sidebar.style.removeProperty('display');
                }, 10);

                // Adiciona evento de clique no overlay para fechar o menu
                if (sidebar.classList.contains('show')) {
                    overlay.addEventListener('click', closeMobileMenu);
                } else {
                    overlay.removeEventListener('click', closeMobileMenu);
                }
            }
        }

        function closeMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (sidebar && overlay) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = 'auto';
                overlay.removeEventListener('click', closeMobileMenu);
            }
        }

        // Close mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileMenu();
            }
        });

        // Garantir que o menu mobile funcione em todas as páginas
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (sidebar && overlay) {
                // Forçar a inicialização correta do menu
                sidebar.style.left = '-300px';
                overlay.style.display = 'none';
                
                // Garantir que o menu seja visível em desktop
                if (window.innerWidth > 768) {
                    sidebar.style.left = '0';
                }

                // Adiciona evento de clique no overlay
                overlay.addEventListener('click', closeMobileMenu);
            }
        });

        // Enhanced touch events for mobile menu
        let touchStartX = 0;
        let touchEndX = 0;
        let touchStartY = 0;
        let touchEndY = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }, { passive: true });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        });

        function handleSwipe() {
            const diffX = touchEndX - touchStartX;
            const diffY = touchEndY - touchStartY;
            const threshold = 80;
            
            // Only process horizontal swipes (ignore vertical scrolling)
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > threshold) {
                if (window.innerWidth <= 768) {
                    if (diffX < -threshold) {
                        // Swipe left - close menu
                        closeMobileMenu();
                    } else if (diffX > threshold && touchStartX < 50) {
                        // Swipe right from left edge - open menu
                        if (!document.getElementById('sidebar').classList.contains('show')) {
                            toggleMobileMenu();
                        }
                    }
                }
            }
        }
        
        // Add haptic feedback for supported devices
        function vibrate() {
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        }

        // Mobile form field improvements
        document.addEventListener('DOMContentLoaded', function() {
            // Fix for iOS datetime inputs
            if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
                const dateTimeInputs = document.querySelectorAll('input[type="datetime-local"]');
                dateTimeInputs.forEach(function(input) {
                    input.style.webkitAppearance = 'none';
                    input.style.appearance = 'none';
                    
                    // Add focus enhancement for better UX
                    input.addEventListener('focus', function() {
                        this.style.borderColor = '#007bff';
                        this.style.boxShadow = '0 0 0 0.2rem rgba(0, 123, 255, 0.25)';
                    });
                    
                    input.addEventListener('blur', function() {
                        this.style.borderColor = '#ced4da';
                        this.style.boxShadow = 'none';
                    });
                });
            }
            
            // Fix for number inputs on mobile
            const numberInputs = document.querySelectorAll('input[type="number"]');
            numberInputs.forEach(function(input) {
                // Prevent zoom on iOS by ensuring font-size is at least 16px
                if (window.innerWidth < 768) {
                    input.style.fontSize = '16px';
                }
                
                // Add mobile-friendly increment/decrement
                input.addEventListener('focus', function() {
                    this.select();
                });
            });
            
            // Improve form validation messages for mobile
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const invalidInputs = form.querySelectorAll(':invalid');
                    if (invalidInputs.length > 0 && window.innerWidth < 768) {
                        e.preventDefault();
                        invalidInputs[0].scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                        invalidInputs[0].focus();
                        return false;
                    }
                });
            });
            
            // Fix input group visibility on mobile
            function fixInputGroupsOnMobile() {
                if (window.innerWidth < 768) {
                    const inputGroups = document.querySelectorAll('.input-group');
                    inputGroups.forEach(function(group) {
                        // Garantir que o input group seja visível
                        group.style.display = 'flex';
                        group.style.width = '100%';
                        group.style.maxWidth = '100%';
                        
                        const input = group.querySelector('.form-control');
                        const text = group.querySelector('.input-group-text');
                        
                        if (input) {
                            input.style.flex = '1';
                            input.style.minWidth = '0';
                            input.style.fontSize = '16px';
                        }
                        
                        if (text) {
                            text.style.flexShrink = '0';
                            text.style.fontSize = '14px';
                            text.style.padding = '0.375rem 0.5rem';
                        }
                    });
                }
            }
            
            // Executar fix na carga da página
            fixInputGroupsOnMobile();
            
            // Executar fix quando a tela for redimensionada
            window.addEventListener('resize', fixInputGroupsOnMobile);
        });
    </script>
    
    <!-- Offline Storage -->
    <script src="/js/offline-storage.js"></script>
    
    <!-- PWA Manager -->
    <script src="/js/pwa-manager.js"></script>
    
    @stack('scripts')
</body>
</html>
