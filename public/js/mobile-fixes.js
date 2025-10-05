/**
 * Mobile Fixes for Form Fields
 * Garante que campos de formulário sejam visíveis em dispositivos móveis
 */

(function() {
    'use strict';
    
    // Função para garantir visibilidade de input groups
    function ensureInputGroupVisibility() {
        if (window.innerWidth < 768) {
            const inputGroups = document.querySelectorAll('.input-group');
            
            inputGroups.forEach(function(group) {
                // Verificar se o grupo está visível
                const rect = group.getBoundingClientRect();
                const isVisible = rect.width > 0 && rect.height > 0;
                
                if (!isVisible) {
                    // Forçar visibilidade
                    group.style.display = 'flex';
                    group.style.width = '100%';
                    group.style.maxWidth = '100%';
                    group.style.minHeight = '44px';
                }
                
                // Ajustar input interno
                const input = group.querySelector('.form-control');
                if (input) {
                    input.style.flex = '1';
                    input.style.minWidth = '0';
                    input.style.fontSize = '16px';
                    input.style.minHeight = '44px';
                }
                
                // Ajustar texto do grupo
                const text = group.querySelector('.input-group-text');
                if (text) {
                    text.style.flexShrink = '0';
                    text.style.fontSize = '14px';
                    text.style.padding = '0.375rem 0.5rem';
                    text.style.maxWidth = '80px';
                    text.style.textAlign = 'center';
                }
            });
        }
    }
    
    // Função para verificar campos cortados
    function checkHiddenFields() {
        if (window.innerWidth < 768) {
            const formControls = document.querySelectorAll('.form-control, .form-select');
            
            formControls.forEach(function(field) {
                const rect = field.getBoundingClientRect();
                const container = field.closest('.col-12, .col-md-6, .col-lg-6');
                
                if (container) {
                    const containerRect = container.getBoundingClientRect();
                    
                    // Se o campo está sendo cortado
                    if (rect.right > containerRect.right - 10) {
                        field.style.width = '100%';
                        field.style.maxWidth = '100%';
                        
                        // Se está em um input group
                        const inputGroup = field.closest('.input-group');
                        if (inputGroup) {
                            inputGroup.style.width = '100%';
                            inputGroup.style.maxWidth = '100%';
                        }
                    }
                }
            });
        }
    }
    
    // Função para observar mudanças no DOM
    function observeFormChanges() {
        const observer = new MutationObserver(function(mutations) {
            let shouldFix = false;
            
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'attributes') {
                    shouldFix = true;
                }
            });
            
            if (shouldFix) {
                setTimeout(function() {
                    ensureInputGroupVisibility();
                    checkHiddenFields();
                }, 100);
            }
        });
        
        // Observar mudanças em formulários
        const forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            observer.observe(form, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['class', 'style']
            });
        });
    }
    
    // Executar quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            ensureInputGroupVisibility();
            checkHiddenFields();
            observeFormChanges();
        });
    } else {
        ensureInputGroupVisibility();
        checkHiddenFields();
        observeFormChanges();
    }
    
    // Executar quando a tela for redimensionada
    window.addEventListener('resize', function() {
        setTimeout(function() {
            ensureInputGroupVisibility();
            checkHiddenFields();
        }, 100);
    });
    
    // Executar quando a orientação da tela mudar
    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            ensureInputGroupVisibility();
            checkHiddenFields();
        }, 300);
    });
    
})();