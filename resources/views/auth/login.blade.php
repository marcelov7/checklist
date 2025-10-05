<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Checklist de Paradas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #2c5aa0;
            --secondary-blue: #3d6bb3;
            --accent-cyan: #17a2b8;
            --success-green: #28a745;
            --warning-orange: #ffc107;
            --danger-red: #dc3545;
            --light-gray: #f8f9fa;
            --dark-gray: #6c757d;
            --accent-gray: #495057;
            --navbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Top Navbar */
        .top-navbar {
            background: rgba(44, 90, 160, 0.95);
            backdrop-filter: blur(10px);
            height: var(--navbar-height);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .navbar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: var(--accent-cyan);
        }

        .navbar-actions {
            display: flex;
            gap: 15px;
        }

        .navbar-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .navbar-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Main Container */
        .main-container {
            display: flex;
            min-height: 100vh;
            padding-top: var(--navbar-height);
        }

        /* Login Section */
        .login-section {
            flex: 1;
            background: rgba(30, 60, 114, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
        }

        .login-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.9) 0%, rgba(42, 82, 152, 0.8) 100%);
            z-index: 1;
        }

        .login-content {
            position: relative;
            z-index: 2;
            max-width: 500px;
            width: 100%;
        }

        .system-header {
            text-align: center;
            color: white;
            margin-bottom: 60px;
        }

        .system-icon {
            font-size: 4rem;
            color: var(--accent-cyan);
            margin-bottom: 20px;
            display: block;
        }

        .system-title {
            font-size: 2.2rem;
            font-weight: 300;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .system-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 300;
        }

        .login-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            color: var(--primary-blue);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-cyan) 100%);
            border: none;
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(44, 90, 160, 0.4);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        /* Ecosystem Section */
        .ecosystem-section {
            flex: 1;
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 40px;
            position: relative;
        }

        .ecosystem-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .ecosystem-title {
            color: white;
            font-size: 2rem;
            font-weight: 300;
            margin-bottom: 15px;
        }

        .ecosystem-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }

        .ecosystem-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .ecosystem-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 25px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .ecosystem-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .ecosystem-card:active {
            transform: translateY(-2px);
        }

        .card-icon {
            font-size: 2rem;
            color: var(--accent-cyan);
            margin-bottom: 15px;
        }

        .card-title {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .card-description {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .card-status {
            display: inline-block;
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 10px;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .card-status.development {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border-color: rgba(255, 193, 7, 0.3);
        }

        .system-footer {
            text-align: center;
            margin-top: 40px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 25px;
            padding: 15px 20px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
                padding-top: var(--navbar-height);
            }

            .ecosystem-section {
                order: -1;
                padding: 40px 20px 60px;
                min-height: 50vh;
            }

            .login-section {
                padding: 40px 20px 60px;
                min-height: 100vh;
            }

            .ecosystem-header {
                margin-bottom: 30px;
            }

            .ecosystem-title {
                font-size: 1.6rem;
            }

            .system-header {
                margin-bottom: 40px;
            }

            .system-title {
                font-size: 1.8rem;
            }

            .system-icon {
                font-size: 3rem;
            }

            .login-form {
                padding: 30px 25px;
            }

            .top-navbar {
                padding: 0 15px;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .navbar-actions {
                gap: 10px;
            }

            .navbar-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }

        /* Scroll Animation */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="navbar-brand">
            <i class="fas fa-clipboard-check me-2"></i>
            Sistema de Checklist
        </div>
        <div class="navbar-actions">
            <a href="#" class="navbar-btn">
                <i class="fas fa-sign-in-alt me-1"></i> Entrar
            </a>
            <a href="#" class="navbar-btn">
                <i class="fas fa-user-plus me-1"></i> Registrar
            </a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Login Section -->
        <div class="login-section">
            <div class="login-content fade-in-up">
                <div class="system-header">
                    <i class="fas fa-clipboard-check system-icon"></i>
                    <h1 class="system-title">Sistema de Checklist</h1>
                    <p class="system-subtitle">Paradas de Manutenção</p>
                </div>

                <div class="login-form">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if($errors->has('login'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first('login') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-circle me-2"></i>Email, Username ou Nome
                            </label>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   name="username" 
                                   placeholder="Digite seu email, username ou nome"
                                   value="{{ old('username') }}"
                                   required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock me-2"></i>Senha
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" 
                                   placeholder="Digite sua senha"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar no Sistema
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <details style="cursor: pointer;">
                            <summary style="color: var(--accent-cyan); font-size: 0.9rem; margin-bottom: 15px;">
                                <i class="fas fa-key me-1"></i> Credenciais de Teste
                            </summary>
                            <div style="background: rgba(23, 162, 184, 0.1); padding: 15px; border-radius: 8px; margin-top: 10px; text-align: left;">
                                <div style="margin-bottom: 8px; font-size: 0.85rem;">
                                    <strong>Admin:</strong> admin@checklist.com / 123456
                                </div>
                                <div style="margin-bottom: 8px; font-size: 0.85rem;">
                                    <strong>Operador:</strong> operador@checklist.com / 123456  
                                </div>
                                <div style="font-size: 0.85rem;">
                                    <strong>Manutenção:</strong> manutencao@checklist.com / 123456
                                </div>
                            </div>
                        </details>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ecosystem Section -->
        <div class="ecosystem-section">
            <div class="ecosystem-header fade-in-up">
                <h2 class="ecosystem-title">
                    <i class="fas fa-tools me-2"></i>
                    Ecossistema Industrial
                </h2>
                <p class="ecosystem-subtitle">Acesse nossas ferramentas de manutenção</p>
            </div>

            <div class="ecosystem-grid">
                <div class="ecosystem-card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="card-title">Sistema de Checklist</div>
                    <div class="card-description">Controle de paradas de manutenção</div>
                    <span class="card-status">● ATIVO</span>
                </div>

                <div class="ecosystem-card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="card-title">Sistema de Relatórios</div>
                    <div class="card-description">Análises e histórico de paradas</div>
                    <span class="card-status">● ATIVO</span>
                </div>

                <div class="ecosystem-card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="card-title">Gestão de Equipamentos</div>
                    <div class="card-description">Controle e monitoramento de ativos</div>
                    <span class="card-status development">⚡ EM DESENVOLVIMENTO</span>
                </div>
            </div>

            <div class="system-footer fade-in-up">
                <p><i class="fas fa-shield-alt me-1"></i> Sistemas certificados ISO 9001</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animações de scroll
        function animateOnScroll() {
            const elements = document.querySelectorAll('.fade-in-up');
            
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.classList.add('visible');
                }
            });
        }

        // Executar animações ao carregar e no scroll
        window.addEventListener('DOMContentLoaded', () => {
            // Animar elementos visíveis imediatamente
            setTimeout(() => {
                document.querySelectorAll('.fade-in-up').forEach((el, index) => {
                    setTimeout(() => {
                        el.classList.add('visible');
                    }, index * 200);
                });
            }, 300);
        });

        window.addEventListener('scroll', animateOnScroll);

        // Efeito parallax suave
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.ecosystem-section');
            
            if (parallax) {
                parallax.style.transform = `translateY(${scrolled * 0.1}px)`;
            }
        });

        // Hover effect nos cards do ecossistema
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.ecosystem-card');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-8px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0) scale(1)';
                });
            });
        });

        // Focus nos campos de entrada
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', () => {
                if (input.value === '') {
                    input.parentElement.classList.remove('focused');
                }
            });
        });
    </script>
</body>
</html>