/**
 * Utilitários para acessibilidade (a11y)
 * 
 * Este arquivo contém funções e constantes para melhorar a acessibilidade
 * da aplicação, incluindo navegação por teclado, ARIA labels, contraste
 * de cores e outras funcionalidades de acessibilidade.
 */

import React from 'react';

// ===== CONSTANTES DE ACESSIBILIDADE =====

export const ARIA_ROLES = {
  button: 'button',
  link: 'link',
  menu: 'menu',
  menuitem: 'menuitem',
  dialog: 'dialog',
  alert: 'alert',
  status: 'status',
  progressbar: 'progressbar',
  tab: 'tab',
  tabpanel: 'tabpanel',
  tablist: 'tablist',
  listbox: 'listbox',
  option: 'option',
  combobox: 'combobox',
  grid: 'grid',
  gridcell: 'gridcell',
  row: 'row',
  columnheader: 'columnheader',
  rowheader: 'rowheader',
} as const;

export const KEYBOARD_KEYS = {
  ENTER: 'Enter',
  SPACE: ' ',
  ESCAPE: 'Escape',
  TAB: 'Tab',
  ARROW_UP: 'ArrowUp',
  ARROW_DOWN: 'ArrowDown',
  ARROW_LEFT: 'ArrowLeft',
  ARROW_RIGHT: 'ArrowRight',
  HOME: 'Home',
  END: 'End',
  PAGE_UP: 'PageUp',
  PAGE_DOWN: 'PageDown',
} as const;

export const ARIA_STATES = {
  expanded: 'aria-expanded',
  selected: 'aria-selected',
  checked: 'aria-checked',
  disabled: 'aria-disabled',
  hidden: 'aria-hidden',
  pressed: 'aria-pressed',
  current: 'aria-current',
  live: 'aria-live',
  atomic: 'aria-atomic',
  busy: 'aria-busy',
} as const;

// ===== FUNÇÕES DE NAVEGAÇÃO POR TECLADO =====

/**
 * Manipula navegação por teclado em listas
 */
export const handleListNavigation = (
  event: React.KeyboardEvent,
  items: HTMLElement[],
  currentIndex: number,
  onIndexChange: (index: number) => void,
  options: {
    loop?: boolean;
    orientation?: 'horizontal' | 'vertical';
    onSelect?: (index: number) => void;
  } = {}
) => {
  const { loop = true, orientation = 'vertical', onSelect } = options;
  
  let newIndex = currentIndex;
  
  switch (event.key) {
    case KEYBOARD_KEYS.ARROW_DOWN:
      if (orientation === 'vertical') {
        event.preventDefault();
        newIndex = currentIndex + 1;
        if (newIndex >= items.length) {
          newIndex = loop ? 0 : items.length - 1;
        }
      }
      break;
      
    case KEYBOARD_KEYS.ARROW_UP:
      if (orientation === 'vertical') {
        event.preventDefault();
        newIndex = currentIndex - 1;
        if (newIndex < 0) {
          newIndex = loop ? items.length - 1 : 0;
        }
      }
      break;
      
    case KEYBOARD_KEYS.ARROW_RIGHT:
      if (orientation === 'horizontal') {
        event.preventDefault();
        newIndex = currentIndex + 1;
        if (newIndex >= items.length) {
          newIndex = loop ? 0 : items.length - 1;
        }
      }
      break;
      
    case KEYBOARD_KEYS.ARROW_LEFT:
      if (orientation === 'horizontal') {
        event.preventDefault();
        newIndex = currentIndex - 1;
        if (newIndex < 0) {
          newIndex = loop ? items.length - 1 : 0;
        }
      }
      break;
      
    case KEYBOARD_KEYS.HOME:
      event.preventDefault();
      newIndex = 0;
      break;
      
    case KEYBOARD_KEYS.END:
      event.preventDefault();
      newIndex = items.length - 1;
      break;
      
    case KEYBOARD_KEYS.ENTER:
    case KEYBOARD_KEYS.SPACE:
      event.preventDefault();
      onSelect?.(currentIndex);
      return;
  }
  
  if (newIndex !== currentIndex) {
    onIndexChange(newIndex);
    items[newIndex]?.focus();
  }
};

/**
 * Manipula navegação por teclado em modais
 */
export const handleModalNavigation = (
  event: React.KeyboardEvent,
  onClose: () => void
) => {
  if (event.key === KEYBOARD_KEYS.ESCAPE) {
    event.preventDefault();
    onClose();
  }
};

/**
 * Captura foco dentro de um elemento (trap focus)
 */
export const trapFocus = (container: HTMLElement) => {
  const focusableElements = container.querySelectorAll(
    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
  ) as NodeListOf<HTMLElement>;
  
  const firstElement = focusableElements[0];
  const lastElement = focusableElements[focusableElements.length - 1];
  
  const handleTabKey = (event: KeyboardEvent) => {
    if (event.key !== KEYBOARD_KEYS.TAB) return;
    
    if (event.shiftKey) {
      if (document.activeElement === firstElement) {
        event.preventDefault();
        lastElement.focus();
      }
    } else {
      if (document.activeElement === lastElement) {
        event.preventDefault();
        firstElement.focus();
      }
    }
  };
  
  container.addEventListener('keydown', handleTabKey);
  
  // Foca no primeiro elemento
  firstElement?.focus();
  
  // Retorna função para limpar o event listener
  return () => {
    container.removeEventListener('keydown', handleTabKey);
  };
};

// ===== FUNÇÕES DE ARIA LABELS =====

/**
 * Gera IDs únicos para elementos
 */
export const generateId = (prefix: string = 'element'): string => {
  return `${prefix}-${Math.random().toString(36).substr(2, 9)}`;
};

/**
 * Cria props ARIA para botões
 */
export const getButtonAriaProps = (options: {
  label?: string;
  describedBy?: string;
  pressed?: boolean;
  expanded?: boolean;
  disabled?: boolean;
  controls?: string;
}) => {
  const props: Record<string, any> = {
    role: ARIA_ROLES.button,
  };
  
  if (options.label) props['aria-label'] = options.label;
  if (options.describedBy) props['aria-describedby'] = options.describedBy;
  if (options.pressed !== undefined) props['aria-pressed'] = options.pressed;
  if (options.expanded !== undefined) props['aria-expanded'] = options.expanded;
  if (options.disabled) props['aria-disabled'] = true;
  if (options.controls) props['aria-controls'] = options.controls;
  
  return props;
};

/**
 * Cria props ARIA para inputs
 */
export const getInputAriaProps = (options: {
  label?: string;
  describedBy?: string;
  invalid?: boolean;
  required?: boolean;
  placeholder?: string;
}) => {
  const props: Record<string, any> = {};
  
  if (options.label) props['aria-label'] = options.label;
  if (options.describedBy) props['aria-describedby'] = options.describedBy;
  if (options.invalid) props['aria-invalid'] = true;
  if (options.required) props['aria-required'] = true;
  if (options.placeholder) props['placeholder'] = options.placeholder;
  
  return props;
};

/**
 * Cria props ARIA para listas
 */
export const getListAriaProps = (options: {
  label?: string;
  multiselectable?: boolean;
  orientation?: 'horizontal' | 'vertical';
}) => {
  const props: Record<string, any> = {
    role: ARIA_ROLES.listbox,
  };
  
  if (options.label) props['aria-label'] = options.label;
  if (options.multiselectable) props['aria-multiselectable'] = true;
  if (options.orientation) props['aria-orientation'] = options.orientation;
  
  return props;
};

/**
 * Cria props ARIA para itens de lista
 */
export const getListItemAriaProps = (options: {
  selected?: boolean;
  disabled?: boolean;
  index?: number;
  setSize?: number;
}) => {
  const props: Record<string, any> = {
    role: ARIA_ROLES.option,
  };
  
  if (options.selected !== undefined) props['aria-selected'] = options.selected;
  if (options.disabled) props['aria-disabled'] = true;
  if (options.index !== undefined) props['aria-posinset'] = options.index + 1;
  if (options.setSize !== undefined) props['aria-setsize'] = options.setSize;
  
  return props;
};

// ===== FUNÇÕES DE ANÚNCIO PARA LEITORES DE TELA =====

/**
 * Anuncia mensagens para leitores de tela
 */
export const announceToScreenReader = (
  message: string,
  priority: 'polite' | 'assertive' = 'polite'
) => {
  const announcement = document.createElement('div');
  announcement.setAttribute('aria-live', priority);
  announcement.setAttribute('aria-atomic', 'true');
  announcement.className = 'sr-only';
  announcement.textContent = message;
  
  document.body.appendChild(announcement);
  
  // Remove o elemento após um tempo
  setTimeout(() => {
    document.body.removeChild(announcement);
  }, 1000);
};

// ===== HOOKS DE ACESSIBILIDADE =====

/**
 * Hook para gerenciar foco
 */
export const useFocusManagement = () => {
  const [focusedIndex, setFocusedIndex] = React.useState(0);
  const itemsRef = React.useRef<HTMLElement[]>([]);
  
  const setItemRef = React.useCallback((index: number) => (el: HTMLElement | null) => {
    if (el) {
      itemsRef.current[index] = el;
    }
  }, []);
  
  const focusItem = React.useCallback((index: number) => {
    const item = itemsRef.current[index];
    if (item) {
      item.focus();
      setFocusedIndex(index);
    }
  }, []);
  
  const handleKeyNavigation = React.useCallback((
    event: React.KeyboardEvent,
    options?: Parameters<typeof handleListNavigation>[3]
  ) => {
    handleListNavigation(
      event,
      itemsRef.current,
      focusedIndex,
      setFocusedIndex,
      options
    );
  }, [focusedIndex]);
  
  return {
    focusedIndex,
    setFocusedIndex,
    setItemRef,
    focusItem,
    handleKeyNavigation,
  };
};

/**
 * Hook para gerenciar estado de modal
 */
export const useModalAccessibility = (isOpen: boolean, onClose: () => void) => {
  const modalRef = React.useRef<HTMLDivElement>(null);
  const previousFocusRef = React.useRef<HTMLElement | null>(null);
  
  React.useEffect(() => {
    if (isOpen) {
      // Salva o elemento focado anteriormente
      previousFocusRef.current = document.activeElement as HTMLElement;
      
      // Configura trap focus
      const modal = modalRef.current;
      if (modal) {
        const cleanup = trapFocus(modal);
        return cleanup;
      }
    } else {
      // Restaura o foco anterior
      if (previousFocusRef.current) {
        previousFocusRef.current.focus();
      }
    }
  }, [isOpen]);
  
  const handleKeyDown = React.useCallback((event: React.KeyboardEvent) => {
    handleModalNavigation(event, onClose);
  }, [onClose]);
  
  return {
    modalRef,
    handleKeyDown,
  };
};

/**
 * Hook para anúncios de status
 */
export const useStatusAnnouncement = () => {
  const announce = React.useCallback((
    message: string,
    priority: 'polite' | 'assertive' = 'polite'
  ) => {
    announceToScreenReader(message, priority);
  }, []);
  
  return { announce };
};

// ===== VALIDAÇÃO DE CONTRASTE =====

/**
 * Calcula a razão de contraste entre duas cores
 */
export const calculateContrastRatio = (color1: string, color2: string): number => {
  // Função auxiliar para converter hex para RGB
  const hexToRgb = (hex: string) => {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
      r: parseInt(result[1], 16),
      g: parseInt(result[2], 16),
      b: parseInt(result[3], 16)
    } : null;
  };
  
  // Função auxiliar para calcular luminância
  const getLuminance = (r: number, g: number, b: number) => {
    const [rs, gs, bs] = [r, g, b].map(c => {
      c = c / 255;
      return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
    });
    return 0.2126 * rs + 0.7152 * gs + 0.0722 * bs;
  };
  
  const rgb1 = hexToRgb(color1);
  const rgb2 = hexToRgb(color2);
  
  if (!rgb1 || !rgb2) return 0;
  
  const lum1 = getLuminance(rgb1.r, rgb1.g, rgb1.b);
  const lum2 = getLuminance(rgb2.r, rgb2.g, rgb2.b);
  
  const brightest = Math.max(lum1, lum2);
  const darkest = Math.min(lum1, lum2);
  
  return (brightest + 0.05) / (darkest + 0.05);
};

/**
 * Verifica se o contraste atende aos padrões WCAG
 */
export const isContrastCompliant = (
  color1: string,
  color2: string,
  level: 'AA' | 'AAA' = 'AA',
  size: 'normal' | 'large' = 'normal'
): boolean => {
  const ratio = calculateContrastRatio(color1, color2);
  
  if (level === 'AAA') {
    return size === 'large' ? ratio >= 4.5 : ratio >= 7;
  } else {
    return size === 'large' ? ratio >= 3 : ratio >= 4.5;
  }
};

// ===== CLASSES CSS PARA ACESSIBILIDADE =====

export const a11yClasses = {
  // Screen reader only
  srOnly: 'sr-only',
  
  // Focus visible
  focusVisible: 'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2',
  
  // Skip links
  skipLink: 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white focus:rounded',
  
  // High contrast mode
  highContrast: 'contrast-more:border-black contrast-more:text-black',
  
  // Reduced motion
  reducedMotion: 'motion-reduce:transition-none motion-reduce:animate-none',
};

export default {
  ARIA_ROLES,
  KEYBOARD_KEYS,
  ARIA_STATES,
  handleListNavigation,
  handleModalNavigation,
  trapFocus,
  generateId,
  getButtonAriaProps,
  getInputAriaProps,
  getListAriaProps,
  getListItemAriaProps,
  announceToScreenReader,
  useFocusManagement,
  useModalAccessibility,
  useStatusAnnouncement,
  calculateContrastRatio,
  isContrastCompliant,
  a11yClasses,
};