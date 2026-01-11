/**
 * Utilitários para otimização mobile e dispositivos touch
 * 
 * Este arquivo contém funções e constantes para melhorar a experiência
 * em dispositivos móveis, incluindo detecção de dispositivos, otimizações
 * de performance e interações touch-friendly.
 */

import React from 'react';

// Detecção de dispositivos
export const isMobile = (): boolean => {
  if (typeof window === 'undefined') return false;
  return window.innerWidth <= 768;
};

export const isTablet = (): boolean => {
  if (typeof window === 'undefined') return false;
  return window.innerWidth > 768 && window.innerWidth <= 1024;
};

export const isTouchDevice = (): boolean => {
  if (typeof window === 'undefined') return false;
  return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
};

export const isIOS = (): boolean => {
  if (typeof window === 'undefined') return false;
  return /iPad|iPhone|iPod/.test(navigator.userAgent);
};

export const isAndroid = (): boolean => {
  if (typeof window === 'undefined') return false;
  return /Android/.test(navigator.userAgent);
};

// Constantes para otimização mobile
export const MOBILE_BREAKPOINTS = {
  xs: 475,
  sm: 640,
  md: 768,
  lg: 1024,
  xl: 1280,
} as const;

export const TOUCH_TARGET_SIZE = {
  minimum: 44, // Tamanho mínimo recomendado (44px)
  comfortable: 48, // Tamanho confortável (48px)
  large: 56, // Tamanho grande para ações importantes (56px)
} as const;

// Classes CSS otimizadas para mobile
export const getMobileClasses = (isMobileDevice: boolean) => ({
  // Espaçamentos otimizados
  padding: isMobileDevice ? 'p-4' : 'p-6',
  margin: isMobileDevice ? 'm-2' : 'm-4',
  gap: isMobileDevice ? 'gap-3' : 'gap-4',
  
  // Tipografia responsiva
  heading: isMobileDevice ? 'text-xl' : 'text-2xl',
  subheading: isMobileDevice ? 'text-lg' : 'text-xl',
  body: isMobileDevice ? 'text-sm' : 'text-base',
  caption: isMobileDevice ? 'text-xs' : 'text-sm',
  
  // Botões touch-friendly
  button: isMobileDevice ? 'min-h-[44px] px-4 py-3' : 'min-h-[40px] px-6 py-2',
  iconButton: isMobileDevice ? 'w-12 h-12' : 'w-10 h-10',
  
  // Inputs otimizados
  input: isMobileDevice ? 'min-h-[44px] text-base' : 'min-h-[40px] text-sm',
  
  // Grid responsivo
  grid: isMobileDevice ? 'grid-cols-1' : 'grid-cols-2 lg:grid-cols-3',
});

// Hook personalizado para detecção de dispositivo
export const useDeviceDetection = () => {
  const [deviceInfo, setDeviceInfo] = React.useState({
    isMobile: false,
    isTablet: false,
    isTouch: false,
    isIOS: false,
    isAndroid: false,
  });

  React.useEffect(() => {
    const updateDeviceInfo = () => {
      setDeviceInfo({
        isMobile: isMobile(),
        isTablet: isTablet(),
        isTouch: isTouchDevice(),
        isIOS: isIOS(),
        isAndroid: isAndroid(),
      });
    };

    updateDeviceInfo();
    window.addEventListener('resize', updateDeviceInfo);
    
    return () => window.removeEventListener('resize', updateDeviceInfo);
  }, []);

  return deviceInfo;
};

// Utilitários para performance mobile
export const mobileOptimizations = {
  // Lazy loading para imagens
  lazyImageProps: {
    loading: 'lazy' as const,
    decoding: 'async' as const,
  },
  
  // Configurações de scroll otimizadas
  scrollConfig: {
    behavior: 'smooth' as const,
    block: 'nearest' as const,
  },
  
  // Debounce para inputs em mobile
  debounceDelay: 300,
  
  // Configurações de touch
  touchConfig: {
    passive: true,
    capture: false,
  },
};

// Função para otimizar eventos touch
export const optimizeTouchEvents = (element: HTMLElement) => {
  // Previne zoom duplo toque em iOS
  element.addEventListener('touchend', (e) => {
    e.preventDefault();
    e.target?.dispatchEvent(new MouseEvent('click', {
      bubbles: true,
      cancelable: true,
    }));
  }, { passive: false });
  
  // Adiciona feedback visual para touch
  element.addEventListener('touchstart', () => {
    element.classList.add('touch-active');
  }, { passive: true });
  
  element.addEventListener('touchend', () => {
    setTimeout(() => {
      element.classList.remove('touch-active');
    }, 150);
  }, { passive: true });
};

// Função para calcular viewport seguro (safe area)
export const getSafeAreaInsets = () => {
  if (typeof window === 'undefined') return { top: 0, bottom: 0, left: 0, right: 0 };
  
  const style = getComputedStyle(document.documentElement);
  
  return {
    top: parseInt(style.getPropertyValue('--safe-area-inset-top') || '0'),
    bottom: parseInt(style.getPropertyValue('--safe-area-inset-bottom') || '0'),
    left: parseInt(style.getPropertyValue('--safe-area-inset-left') || '0'),
    right: parseInt(style.getPropertyValue('--safe-area-inset-right') || '0'),
  };
};

// Classes CSS para safe area
export const safeAreaClasses = {
  paddingTop: 'pt-[env(safe-area-inset-top)]',
  paddingBottom: 'pb-[env(safe-area-inset-bottom)]',
  paddingLeft: 'pl-[env(safe-area-inset-left)]',
  paddingRight: 'pr-[env(safe-area-inset-right)]',
  marginTop: 'mt-[env(safe-area-inset-top)]',
  marginBottom: 'mb-[env(safe-area-inset-bottom)]',
};

// Função para melhorar acessibilidade em mobile
export const enhanceMobileAccessibility = (element: HTMLElement) => {
  // Aumenta área de toque para elementos pequenos
  const rect = element.getBoundingClientRect();
  if (rect.width < TOUCH_TARGET_SIZE.minimum || rect.height < TOUCH_TARGET_SIZE.minimum) {
    element.style.minWidth = `${TOUCH_TARGET_SIZE.minimum}px`;
    element.style.minHeight = `${TOUCH_TARGET_SIZE.minimum}px`;
  }
  
  // Adiciona role e aria-labels se necessário
  if (!element.getAttribute('role') && element.tagName === 'DIV') {
    element.setAttribute('role', 'button');
  }
  
  // Melhora contraste para telas pequenas
  if (isMobile()) {
    element.style.filter = 'contrast(1.1)';
  }
};

export default {
  isMobile,
  isTablet,
  isTouchDevice,
  isIOS,
  isAndroid,
  getMobileClasses,
  useDeviceDetection,
  mobileOptimizations,
  optimizeTouchEvents,
  getSafeAreaInsets,
  safeAreaClasses,
  enhanceMobileAccessibility,
  MOBILE_BREAKPOINTS,
  TOUCH_TARGET_SIZE,
};