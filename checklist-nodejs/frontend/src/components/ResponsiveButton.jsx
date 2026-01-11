import React from 'react';
import { useResponsive } from '../hooks/useResponsive';

/**
 * Componente de Botão Responsivo
 * Adapta automaticamente o tamanho e estilo baseado no dispositivo
 */
const ResponsiveButton = ({
  children,
  variant = 'primary',
  size = 'medium',
  fullWidth = false,
  disabled = false,
  loading = false,
  icon = null,
  iconPosition = 'left',
  onClick,
  type = 'button',
  className = '',
  mobileFullWidth = true,
  ...props
}) => {
  const { isMobile, getResponsiveClasses } = useResponsive();

  // Definir classes base do botão
  const baseClasses = 'btn focus-visible:focus-visible';

  // Definir classes de variante
  const variantClasses = {
    primary: 'btn-primary',
    secondary: 'btn-secondary',
    danger: 'btn-danger',
    success: 'bg-green-600 hover:bg-green-700 text-white',
    warning: 'bg-yellow-600 hover:bg-yellow-700 text-white',
    ghost: 'bg-transparent hover:bg-gray-100 text-gray-700 border border-gray-300',
    link: 'bg-transparent hover:bg-gray-50 text-blue-600 underline',
  };

  // Definir classes de tamanho
  const sizeClasses = {
    small: isMobile ? 'btn-sm' : 'px-3 py-1.5 text-sm',
    medium: isMobile ? 'btn' : 'px-4 py-2 text-sm',
    large: isMobile ? 'btn text-mobile-base' : 'px-6 py-3 text-base',
  };

  // Definir classes de largura
  const widthClasses = () => {
    if (fullWidth) return 'w-full';
    if (mobileFullWidth && isMobile) return 'w-full';
    return '';
  };

  // Definir classes de estado
  const stateClasses = () => {
    if (disabled || loading) return 'opacity-50 cursor-not-allowed';
    return '';
  };

  // Combinar todas as classes
  const buttonClasses = [
    baseClasses,
    variantClasses[variant] || variantClasses.primary,
    sizeClasses[size] || sizeClasses.medium,
    widthClasses(),
    stateClasses(),
    className,
  ].filter(Boolean).join(' ');

  // Função para lidar com clique
  const handleClick = (event) => {
    if (disabled || loading) {
      event.preventDefault();
      return;
    }
    onClick?.(event);
  };

  // Renderizar ícone de loading
  const LoadingIcon = () => (
    <svg className="loading-spinner" viewBox="0 0 24 24">
      <circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" opacity="0.25" />
      <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
    </svg>
  );

  // Renderizar conteúdo do botão
  const renderContent = () => {
    if (loading) {
      return (
        <>
          <LoadingIcon />
          <span>Carregando...</span>
        </>
      );
    }

    if (icon && iconPosition === 'left') {
      return (
        <>
          {icon}
          <span>{children}</span>
        </>
      );
    }

    if (icon && iconPosition === 'right') {
      return (
        <>
          <span>{children}</span>
          {icon}
        </>
      );
    }

    return children;
  };

  return (
    <button
      type={type}
      className={buttonClasses}
      onClick={handleClick}
      disabled={disabled || loading}
      aria-disabled={disabled || loading}
      {...props}
    >
      {renderContent()}
    </button>
  );
};

/**
 * Componente de Grupo de Botões Responsivo
 * Organiza botões de forma responsiva
 */
export const ResponsiveButtonGroup = ({
  children,
  orientation = 'horizontal',
  spacing = 'medium',
  mobileVertical = true,
  className = '',
}) => {
  const { isMobile } = useResponsive();

  // Definir orientação baseada no dispositivo
  const actualOrientation = (mobileVertical && isMobile) ? 'vertical' : orientation;

  // Definir classes de espaçamento
  const spacingClasses = {
    small: actualOrientation === 'vertical' ? 'gap-2' : 'gap-2',
    medium: actualOrientation === 'vertical' ? 'gap-3' : 'gap-3',
    large: actualOrientation === 'vertical' ? 'gap-4' : 'gap-4',
  };

  // Definir classes de direção
  const directionClasses = actualOrientation === 'vertical' ? 'flex-col' : 'flex-row';

  // Combinar classes
  const groupClasses = [
    'btn-group',
    'flex',
    directionClasses,
    spacingClasses[spacing] || spacingClasses.medium,
    className,
  ].filter(Boolean).join(' ');

  return (
    <div className={groupClasses}>
      {children}
    </div>
  );
};

/**
 * Componente de Botão Flutuante (FAB)
 * Botão de ação flutuante para ações principais em mobile
 */
export const FloatingActionButton = ({
  children,
  onClick,
  position = 'bottom-right',
  variant = 'primary',
  size = 'medium',
  className = '',
  ...props
}) => {
  const { isMobile } = useResponsive();

  // Só mostrar em mobile por padrão
  if (!isMobile) {
    return null;
  }

  // Definir classes de posição
  const positionClasses = {
    'bottom-right': 'bottom-4 right-4',
    'bottom-left': 'bottom-4 left-4',
    'bottom-center': 'bottom-4 left-1/2 transform -translate-x-1/2',
    'top-right': 'top-4 right-4',
    'top-left': 'top-4 left-4',
  };

  // Definir classes de tamanho
  const sizeClasses = {
    small: 'w-12 h-12',
    medium: 'w-14 h-14',
    large: 'w-16 h-16',
  };

  // Definir classes de variante
  const variantClasses = {
    primary: 'btn-fab',
    secondary: 'bg-gray-600 hover:bg-gray-700',
    success: 'bg-green-600 hover:bg-green-700',
    danger: 'bg-red-600 hover:bg-red-700',
  };

  // Combinar classes
  const fabClasses = [
    'fixed',
    'rounded-full',
    'shadow-lg',
    'flex',
    'items-center',
    'justify-center',
    'text-white',
    'transition-all',
    'duration-300',
    'z-50',
    'cursor-pointer',
    positionClasses[position] || positionClasses['bottom-right'],
    sizeClasses[size] || sizeClasses.medium,
    variantClasses[variant] || variantClasses.primary,
    className,
  ].filter(Boolean).join(' ');

  return (
    <button
      className={fabClasses}
      onClick={onClick}
      {...props}
    >
      {children}
    </button>
  );
};

/**
 * Componente de Botão de Ícone Responsivo
 * Botão apenas com ícone, adaptado para touch
 */
export const ResponsiveIconButton = ({
  icon,
  onClick,
  variant = 'secondary',
  size = 'medium',
  disabled = false,
  className = '',
  ariaLabel,
  ...props
}) => {
  const { isMobile } = useResponsive();

  // Definir classes de tamanho (maiores em mobile para touch)
  const sizeClasses = {
    small: isMobile ? 'w-10 h-10' : 'w-8 h-8',
    medium: isMobile ? 'w-12 h-12' : 'w-10 h-10',
    large: isMobile ? 'w-14 h-14' : 'w-12 h-12',
  };

  // Definir classes de variante
  const variantClasses = {
    primary: 'bg-blue-600 hover:bg-blue-700 text-white',
    secondary: 'bg-gray-200 hover:bg-gray-300 text-gray-700',
    ghost: 'bg-transparent hover:bg-gray-100 text-gray-600',
    danger: 'bg-red-600 hover:bg-red-700 text-white',
  };

  // Combinar classes
  const buttonClasses = [
    'inline-flex',
    'items-center',
    'justify-center',
    'rounded-lg',
    'border-none',
    'cursor-pointer',
    'transition-all',
    'duration-200',
    'focus-visible:focus-visible',
    sizeClasses[size] || sizeClasses.medium,
    variantClasses[variant] || variantClasses.secondary,
    disabled ? 'opacity-50 cursor-not-allowed' : '',
    className,
  ].filter(Boolean).join(' ');

  return (
    <button
      className={buttonClasses}
      onClick={disabled ? undefined : onClick}
      disabled={disabled}
      aria-label={ariaLabel}
      {...props}
    >
      {icon}
    </button>
  );
};

export default ResponsiveButton;