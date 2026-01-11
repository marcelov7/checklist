import { useState, useEffect } from 'react';
import { useResponsive } from './useResponsive';

/**
 * Hook para gerenciar o estado da navegação mobile
 */
export const useMobileNavigation = () => {
  const [isOpen, setIsOpen] = useState(false);
  const { isMobile } = useResponsive();

  const openMenu = () => setIsOpen(true);
  const closeMenu = () => setIsOpen(false);
  const toggleMenu = () => setIsOpen(!isOpen);

  // Fechar menu automaticamente quando sair do mobile
  useEffect(() => {
    if (!isMobile && isOpen) {
      setIsOpen(false);
    }
  }, [isMobile, isOpen]);

  return {
    isOpen,
    openMenu,
    closeMenu,
    toggleMenu,
  };
};