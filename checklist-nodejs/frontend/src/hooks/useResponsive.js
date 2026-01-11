import { useState, useEffect } from 'react';

/**
 * Hook personalizado para gerenciar responsividade
 * Detecta o tamanho da tela e fornece breakpoints úteis
 */
export const useResponsive = () => {
  const [screenSize, setScreenSize] = useState({
    width: typeof window !== 'undefined' ? window.innerWidth : 0,
    height: typeof window !== 'undefined' ? window.innerHeight : 0,
  });

  const [breakpoint, setBreakpoint] = useState('desktop');

  // Breakpoints definidos
  const breakpoints = {
    mobile: 767,
    tablet: 1023,
    desktop: 1024,
  };

  // Função para determinar o breakpoint atual
  const getBreakpoint = (width) => {
    if (width <= breakpoints.mobile) return 'mobile';
    if (width <= breakpoints.tablet) return 'tablet';
    return 'desktop';
  };

  // Função para detectar se é dispositivo touch
  const isTouchDevice = () => {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
  };

  // Função para detectar orientação
  const getOrientation = () => {
    if (typeof window === 'undefined') return 'portrait';
    return window.innerHeight > window.innerWidth ? 'portrait' : 'landscape';
  };

  // Effect para monitorar mudanças no tamanho da tela
  useEffect(() => {
    const handleResize = () => {
      const newWidth = window.innerWidth;
      const newHeight = window.innerHeight;
      
      setScreenSize({
        width: newWidth,
        height: newHeight,
      });
      
      setBreakpoint(getBreakpoint(newWidth));
    };

    // Listener para resize
    window.addEventListener('resize', handleResize);
    
    // Listener para orientação (mobile)
    window.addEventListener('orientationchange', () => {
      // Delay para aguardar a mudança completa da orientação
      setTimeout(handleResize, 100);
    });

    // Configuração inicial
    handleResize();

    // Cleanup
    return () => {
      window.removeEventListener('resize', handleResize);
      window.removeEventListener('orientationchange', handleResize);
    };
  }, []);

  // Funções de conveniência para verificar breakpoints
  const isMobile = breakpoint === 'mobile';
  const isTablet = breakpoint === 'tablet';
  const isDesktop = breakpoint === 'desktop';
  const isMobileOrTablet = isMobile || isTablet;
  const isTabletOrDesktop = isTablet || isDesktop;

  // Função para verificar se a tela é pequena (mobile)
  const isSmallScreen = screenSize.width <= breakpoints.mobile;
  
  // Função para verificar se a tela é média (tablet)
  const isMediumScreen = screenSize.width > breakpoints.mobile && screenSize.width <= breakpoints.tablet;
  
  // Função para verificar se a tela é grande (desktop)
  const isLargeScreen = screenSize.width > breakpoints.tablet;

  // Função para obter classes CSS responsivas
  const getResponsiveClasses = (mobileClass = '', tabletClass = '', desktopClass = '') => {
    if (isMobile && mobileClass) return mobileClass;
    if (isTablet && tabletClass) return tabletClass;
    if (isDesktop && desktopClass) return desktopClass;
    return '';
  };

  // Função para obter estilos responsivos
  const getResponsiveStyles = (mobileStyles = {}, tabletStyles = {}, desktopStyles = {}) => {
    if (isMobile) return { ...mobileStyles };
    if (isTablet) return { ...tabletStyles };
    if (isDesktop) return { ...desktopStyles };
    return {};
  };

  // Função para verificar se deve mostrar sidebar mobile
  const shouldShowMobileSidebar = () => {
    return isMobile || (isTablet && getOrientation() === 'portrait');
  };

  // Função para obter número de colunas do grid baseado no breakpoint
  const getGridColumns = (mobileColumns = 1, tabletColumns = 2, desktopColumns = 3) => {
    if (isMobile) return mobileColumns;
    if (isTablet) return tabletColumns;
    return desktopColumns;
  };

  // Função para verificar se deve usar layout compacto
  const shouldUseCompactLayout = () => {
    return isMobile || (isTablet && getOrientation() === 'portrait');
  };

  // Função para obter tamanho de fonte responsivo
  const getResponsiveFontSize = (mobileFontSize = '14px', tabletFontSize = '16px', desktopFontSize = '16px') => {
    if (isMobile) return mobileFontSize;
    if (isTablet) return tabletFontSize;
    return desktopFontSize;
  };

  // Função para obter espaçamento responsivo
  const getResponsiveSpacing = (mobileSpacing = '8px', tabletSpacing = '12px', desktopSpacing = '16px') => {
    if (isMobile) return mobileSpacing;
    if (isTablet) return tabletSpacing;
    return desktopSpacing;
  };

  return {
    // Estado da tela
    screenSize,
    breakpoint,
    orientation: getOrientation(),
    isTouch: isTouchDevice(),

    // Verificações de breakpoint
    isMobile,
    isTablet,
    isDesktop,
    isMobileOrTablet,
    isTabletOrDesktop,
    isSmallScreen,
    isMediumScreen,
    isLargeScreen,

    // Funções utilitárias
    getResponsiveClasses,
    getResponsiveStyles,
    shouldShowMobileSidebar,
    getGridColumns,
    shouldUseCompactLayout,
    getResponsiveFontSize,
    getResponsiveSpacing,

    // Breakpoints para uso direto
    breakpoints,
  };
};

/**
 * Hook para detectar se o usuário está em um dispositivo móvel
 * Versão simplificada do useResponsive
 */
export const useIsMobile = () => {
  const [isMobile, setIsMobile] = useState(false);

  useEffect(() => {
    const checkIsMobile = () => {
      setIsMobile(window.innerWidth <= 767);
    };

    checkIsMobile();
    window.addEventListener('resize', checkIsMobile);

    return () => window.removeEventListener('resize', checkIsMobile);
  }, []);

  return isMobile;
};

/**
 * Hook para detectar mudanças de orientação
 */
export const useOrientation = () => {
  const [orientation, setOrientation] = useState('portrait');

  useEffect(() => {
    const handleOrientationChange = () => {
      const newOrientation = window.innerHeight > window.innerWidth ? 'portrait' : 'landscape';
      setOrientation(newOrientation);
    };

    handleOrientationChange();
    window.addEventListener('resize', handleOrientationChange);
    window.addEventListener('orientationchange', handleOrientationChange);

    return () => {
      window.removeEventListener('resize', handleOrientationChange);
      window.removeEventListener('orientationchange', handleOrientationChange);
    };
  }, []);

  return orientation;
};

/**
 * Hook para detectar se o dispositivo suporta hover
 */
export const useHoverSupport = () => {
  const [supportsHover, setSupportsHover] = useState(true);

  useEffect(() => {
    const mediaQuery = window.matchMedia('(hover: hover)');
    setSupportsHover(mediaQuery.matches);

    const handleChange = (e) => {
      setSupportsHover(e.matches);
    };

    mediaQuery.addEventListener('change', handleChange);
    return () => mediaQuery.removeEventListener('change', handleChange);
  }, []);

  return supportsHover;
};

export default useResponsive;