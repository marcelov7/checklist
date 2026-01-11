// PWA Manager - Gerenciamento de Atualizações e Instalação
class PWAManager {
    constructor() {
        this.swRegistration = null;
        this.updateAvailable = false;
        this.deferredPrompt = null;
        
        this.init();
    }
    
    // ===== INICIALIZAÇÃO =====
    async init() {
        // Registrar Service Worker
        if ('serviceWorker' in navigator) {
            try {
                this.swRegistration = await navigator.serviceWorker.register('/sw.js');
                console.log('[PWA] Service Worker registrado:', this.swRegistration);
                
                // Escutar atualizações
                this.setupUpdateListener();
                
                // Verificar se há atualização disponível
                this.checkForUpdates();
                
            } catch (error) {
                console.error('[PWA] Erro ao registrar Service Worker:', error);
            }
        }
        
        // Configurar prompt de instalação
        this.setupInstallPrompt();
        
        // Configurar detecção de conexão
        this.setupConnectionDetection();
        
        // Criar interface de notificação
        this.createUpdateNotificationUI();
    }
    
    // ===== SISTEMA DE ATUALIZAÇÃO =====
    
    setupUpdateListener() {
        // Escutar mensagens do Service Worker
        navigator.serviceWorker.addEventListener('message', event => {
            const { type, version, message } = event.data;
            
            switch (type) {
                case 'UPDATE_AVAILABLE':
                    this.showUpdateNotification(version, message);
                    break;
                    
                case 'VERSION_INFO':
                    console.log('[PWA] Versão atual:', version);
                    break;
            }
        });
        
        // Detectar novo Service Worker instalado
        if (this.swRegistration) {
            this.swRegistration.addEventListener('updatefound', () => {
                const newWorker = this.swRegistration.installing;
                
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        // Nova versão disponível
                        this.updateAvailable = true;
                        this.showUpdateNotification();
                    }
                });
            });
        }
    }
    
    checkForUpdates() {
        if (this.swRegistration) {
            this.swRegistration.update();
        }
    }
    
    // ===== INTERFACE DE NOTIFICAÇÃO =====
    
    createUpdateNotificationUI() {
        // Criar container de notificação
        const notificationHTML = `
            <div id="pwa-update-notification" class="pwa-notification" style="display: none;">
                <div class="pwa-notification-content">
                    <div class="pwa-notification-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="pwa-notification-text">
                        <strong>Atualização Disponível!</strong>
                        <p>Uma nova versão do sistema está disponível.</p>
                    </div>
                    <div class="pwa-notification-actions">
                        <button id="pwa-update-later" class="btn-secondary">Depois</button>
                        <button id="pwa-update-now" class="btn-primary">Atualizar Agora</button>
                    </div>
                </div>
            </div>
            
            <!-- Notificação de Instalação -->
            <div id="pwa-install-notification" class="pwa-notification" style="display: none;">
                <div class="pwa-notification-content">
                    <div class="pwa-notification-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 9H15L13 7H9L7 9H3C1.9 9 1 9.9 1 11V19C1 20.1 1.9 21 3 21H19C20.1 21 21 20.1 21 19V11C21 9.9 20.1 9 19 9Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="pwa-notification-text">
                        <strong>Instalar App</strong>
                        <p>Instale o app para acesso rápido e uso offline.</p>
                    </div>
                    <div class="pwa-notification-actions">
                        <button id="pwa-install-dismiss" class="btn-secondary">Não, obrigado</button>
                        <button id="pwa-install-now" class="btn-primary">Instalar</button>
                    </div>
                </div>
            </div>
        `;
        
        // Adicionar ao body
        document.body.insertAdjacentHTML('beforeend', notificationHTML);
        
        // Adicionar estilos
        this.addNotificationStyles();
        
        // Configurar eventos
        this.setupNotificationEvents();
    }
    
    addNotificationStyles() {
        const styles = `
            <style id="pwa-notification-styles">
                .pwa-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
                    border: 1px solid #e1e5e9;
                    z-index: 10000;
                    max-width: 400px;
                    animation: slideInRight 0.3s ease-out;
                }
                
                .pwa-notification-content {
                    padding: 20px;
                    display: flex;
                    align-items: flex-start;
                    gap: 15px;
                }
                
                .pwa-notification-icon {
                    color: #007bff;
                    flex-shrink: 0;
                    margin-top: 2px;
                }
                
                .pwa-notification-text {
                    flex: 1;
                }
                
                .pwa-notification-text strong {
                    display: block;
                    margin-bottom: 5px;
                    color: #2c3e50;
                    font-size: 16px;
                }
                
                .pwa-notification-text p {
                    margin: 0;
                    color: #6c757d;
                    font-size: 14px;
                    line-height: 1.4;
                }
                
                .pwa-notification-actions {
                    display: flex;
                    gap: 10px;
                    margin-top: 15px;
                }
                
                .pwa-notification .btn-primary,
                .pwa-notification .btn-secondary {
                    padding: 8px 16px;
                    border-radius: 6px;
                    border: none;
                    font-size: 14px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }
                
                .pwa-notification .btn-primary {
                    background: #007bff;
                    color: white;
                }
                
                .pwa-notification .btn-primary:hover {
                    background: #0056b3;
                }
                
                .pwa-notification .btn-secondary {
                    background: #f8f9fa;
                    color: #6c757d;
                    border: 1px solid #dee2e6;
                }
                
                .pwa-notification .btn-secondary:hover {
                    background: #e9ecef;
                }
                
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
                
                .pwa-notification.hiding {
                    animation: slideOutRight 0.3s ease-in forwards;
                }
                
                /* Responsivo */
                @media (max-width: 480px) {
                    .pwa-notification {
                        top: 10px;
                        right: 10px;
                        left: 10px;
                        max-width: none;
                    }
                    
                    .pwa-notification-content {
                        padding: 15px;
                    }
                    
                    .pwa-notification-actions {
                        flex-direction: column;
                    }
                }
            </style>
        `;
        
        document.head.insertAdjacentHTML('beforeend', styles);
    }
    
    setupNotificationEvents() {
        // Botão "Atualizar Agora"
        document.getElementById('pwa-update-now')?.addEventListener('click', () => {
            this.applyUpdate();
        });
        
        // Botão "Depois"
        document.getElementById('pwa-update-later')?.addEventListener('click', () => {
            this.hideUpdateNotification();
        });
        
        // Botão "Instalar"
        document.getElementById('pwa-install-now')?.addEventListener('click', () => {
            this.installApp();
        });
        
        // Botão "Não, obrigado"
        document.getElementById('pwa-install-dismiss')?.addEventListener('click', () => {
            this.hideInstallNotification();
        });
    }
    
    showUpdateNotification(version = '', message = '') {
        const notification = document.getElementById('pwa-update-notification');
        if (notification) {
            // Atualizar texto se fornecido
            if (version) {
                const textElement = notification.querySelector('.pwa-notification-text p');
                textElement.textContent = `Versão ${version} disponível.`;
            }
            
            notification.style.display = 'block';
            
            // Auto-hide após 10 segundos
            setTimeout(() => {
                if (notification.style.display !== 'none') {
                    this.hideUpdateNotification();
                }
            }, 10000);
        }
    }
    
    hideUpdateNotification() {
        const notification = document.getElementById('pwa-update-notification');
        if (notification) {
            notification.classList.add('hiding');
            setTimeout(() => {
                notification.style.display = 'none';
                notification.classList.remove('hiding');
            }, 300);
        }
    }
    
    async applyUpdate() {
        if (this.swRegistration && this.swRegistration.waiting) {
            // Enviar mensagem para o Service Worker ativar
            this.swRegistration.waiting.postMessage({ type: 'SKIP_WAITING' });
            
            // Aguardar a ativação e recarregar
            navigator.serviceWorker.addEventListener('controllerchange', () => {
                window.location.reload();
            });
        }
    }
    
    // ===== INSTALAÇÃO DO APP =====
    
    setupInstallPrompt() {
        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevenir o prompt automático
            e.preventDefault();
            this.deferredPrompt = e;
            
            // Mostrar notificação de instalação após 5 segundos
            setTimeout(() => {
                this.showInstallNotification();
            }, 5000);
        });
        
        // Detectar quando o app foi instalado
        window.addEventListener('appinstalled', () => {
            console.log('[PWA] App instalado com sucesso!');
            this.hideInstallNotification();
            this.deferredPrompt = null;
        });
    }
    
    showInstallNotification() {
        if (this.deferredPrompt) {
            const notification = document.getElementById('pwa-install-notification');
            if (notification) {
                notification.style.display = 'block';
            }
        }
    }
    
    hideInstallNotification() {
        const notification = document.getElementById('pwa-install-notification');
        if (notification) {
            notification.classList.add('hiding');
            setTimeout(() => {
                notification.style.display = 'none';
                notification.classList.remove('hiding');
            }, 300);
        }
    }
    
    async installApp() {
        if (this.deferredPrompt) {
            // Mostrar prompt de instalação
            this.deferredPrompt.prompt();
            
            // Aguardar resposta do usuário
            const { outcome } = await this.deferredPrompt.userChoice;
            
            if (outcome === 'accepted') {
                console.log('[PWA] Usuário aceitou instalar o app');
            } else {
                console.log('[PWA] Usuário recusou instalar o app');
            }
            
            this.deferredPrompt = null;
            this.hideInstallNotification();
        }
    }
    
    // ===== DETECÇÃO DE CONEXÃO =====
    
    setupConnectionDetection() {
        // Status inicial
        this.updateConnectionStatus();
        
        // Escutar mudanças de conexão
        window.addEventListener('online', () => {
            console.log('[PWA] Conexão restaurada');
            this.updateConnectionStatus();
            this.syncOfflineData();
        });
        
        window.addEventListener('offline', () => {
            console.log('[PWA] Conexão perdida - Modo offline ativado');
            this.updateConnectionStatus();
        });
    }
    
    updateConnectionStatus() {
        const isOnline = navigator.onLine;
        document.body.classList.toggle('pwa-offline', !isOnline);
        
        // Adicionar indicador visual se necessário
        this.showConnectionStatus(isOnline);
    }
    
    showConnectionStatus(isOnline) {
        // Remover indicador anterior com animação
        const existingIndicator = document.getElementById('pwa-connection-indicator');
        if (existingIndicator) {
            const indicatorDiv = existingIndicator.querySelector('.pwa-offline-indicator');
            if (indicatorDiv) {
                indicatorDiv.classList.add('hiding');
                setTimeout(() => {
                    existingIndicator.remove();
                }, 300); // Tempo da animação
            } else {
                existingIndicator.remove();
            }
        }
        
        if (!isOnline) {
            const indicator = document.createElement('div');
            indicator.id = 'pwa-connection-indicator';
            indicator.innerHTML = `
                <div class="pwa-offline-indicator">
                    📱 Modo Offline - Seus dados serão sincronizados quando a conexão retornar
                </div>
            `;
            document.body.appendChild(indicator);
            
            // Adicionar estilos específicos para o indicador offline
            this.addOfflineIndicatorStyles();
            
            // Banner como único indicador offline
        }
    }
    
    addOfflineIndicatorStyles() {
        // Verificar se os estilos já foram adicionados
        if (document.getElementById('pwa-offline-indicator-styles')) {
            return;
        }
        
        const styles = `
            <style id="pwa-offline-indicator-styles">
                /* Estilos base do indicador offline */
                .pwa-offline-indicator {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    background: #dc3545;
                    color: white;
                    text-align: center;
                    padding: 10px 16px;
                    font-size: 14px;
                    font-weight: 500;
                    z-index: 10001;
                    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
                    animation: slideDownOffline 0.3s ease-out;
                    display: block;
                    width: 100%;
                }
                
                /* Animações */
                @keyframes slideDownOffline {
                    from {
                        transform: translateY(-100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }
                
                @keyframes slideUpOffline {
                    from {
                        transform: translateY(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateY(-100%);
                        opacity: 0;
                    }
                }
                
                /* Transição suave quando o indicador desaparece */
                .pwa-offline-indicator.hiding {
                    animation: slideUpOffline 0.3s ease-in forwards;
                }
                
                /* Responsivo para mobile (até 767px) */
                @media (max-width: 767.98px) {
                    .pwa-offline-indicator {
                        top: 60px !important; /* Abaixo da navbar mobile */
                        padding: 8px 12px !important;
                        font-size: 12px !important;
                        z-index: 10001 !important;
                    }
                    
                    /* Ajustar o conteúdo principal quando offline em mobile */
                    body.pwa-offline .main-content {
                        padding-top: calc(76px + 44px) !important; /* navbar + indicador */
                    }
                }
                
                /* Desktop e tablets (768px e acima) */
                @media (min-width: 768px) {
                    .pwa-offline-indicator {
                        top: 76px !important; /* Exatamente abaixo da navbar */
                        padding: 8px 16px !important;
                        font-size: 13px !important;
                        z-index: 10001 !important;
                        display: block !important;
                        border-radius: 0 0 8px 8px; /* Bordas arredondadas na parte inferior */
                        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2); /* Sombra mais suave */
                    }
                    
                    /* Ajustar o conteúdo principal quando offline em desktop */
                    body.pwa-offline .main-content {
                        padding-top: calc(76px + 40px) !important; /* navbar + indicador */
                    }
                    
                    /* Para telas muito grandes */
                    body.pwa-offline .sidebar {
                        padding-top: calc(76px + 40px) !important;
                    }
                }
                
                /* Garantir visibilidade em todas as resoluções */
                @media (min-width: 1200px) {
                    .pwa-offline-indicator {
                        display: block !important;
                        visibility: visible !important;
                        opacity: 1 !important;
                    }
                }
                
                /* Animação para o ícone do sidebar */
                @keyframes pulse {
                    0% { opacity: 1; }
                    50% { opacity: 0.5; }
                    100% { opacity: 1; }
                }
            </style>
        `;
        
        document.head.insertAdjacentHTML('beforeend', styles);
    }
    
    // ===== SINCRONIZAÇÃO =====
    
    async syncOfflineData() {
        if ('serviceWorker' in navigator && 'sync' in window.ServiceWorkerRegistration.prototype) {
            try {
                const registration = await navigator.serviceWorker.ready;
                await registration.sync.register('background-sync');
                console.log('[PWA] Sincronização em background registrada');
            } catch (error) {
                console.error('[PWA] Erro ao registrar sincronização:', error);
            }
        }
    }
    
    // ===== MÉTODOS PÚBLICOS =====
    
    // Verificar se há atualizações manualmente (método duplicado removido)
    // checkForUpdates() já existe acima
    
    // Obter versão atual
    async getCurrentVersion() {
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            const messageChannel = new MessageChannel();
            
            return new Promise((resolve) => {
                messageChannel.port1.onmessage = (event) => {
                    if (event.data.type === 'VERSION_INFO') {
                        resolve(event.data.version);
                    }
                };
                
                navigator.serviceWorker.controller.postMessage(
                    { type: 'GET_VERSION' },
                    [messageChannel.port2]
                );
            });
        }
        return null;
    }
    
    // Limpar cache manualmente
    async clearCache() {
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            const messageChannel = new MessageChannel();
            
            return new Promise((resolve) => {
                messageChannel.port1.onmessage = (event) => {
                    if (event.data.type === 'CACHE_CLEARED') {
                        resolve(event.data.success);
                    }
                };
                
                navigator.serviceWorker.controller.postMessage(
                    { type: 'CLEAR_CACHE' },
                    [messageChannel.port2]
                );
            });
        }
        return false;
    }
}

// ===== INICIALIZAÇÃO AUTOMÁTICA =====
document.addEventListener('DOMContentLoaded', () => {
    window.pwaManager = new PWAManager();
});

// Exportar para uso global
window.PWAManager = PWAManager;