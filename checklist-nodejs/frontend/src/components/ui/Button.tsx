import React from 'react';
import { getButtonAriaProps, useStatusAnnouncement, a11yClasses } from '../../utils/accessibility';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger' | 'success';
  size?: 'sm' | 'md' | 'lg';
  loading?: boolean;
  children: React.ReactNode;
  leftIcon?: React.ReactNode;
  rightIcon?: React.ReactNode;
  fullWidth?: boolean;
  // Propriedades de acessibilidade
  ariaLabel?: string;
  ariaDescribedBy?: string;
  ariaPressed?: boolean;
  ariaExpanded?: boolean;
  ariaControls?: string;
  loadingText?: string;
}

const Button: React.FC<ButtonProps> = ({
  variant = 'primary',
  size = 'md',
  loading = false,
  className = '',
  children,
  disabled,
  leftIcon,
  rightIcon,
  fullWidth = false,
  ariaLabel,
  ariaDescribedBy,
  ariaPressed,
  ariaExpanded,
  ariaControls,
  loadingText = 'Carregando...',
  onClick,
  ...props
}) => {
  const { announce } = useStatusAnnouncement();
  const getVariantClasses = () => {
    const baseClasses = `font-medium transition-all duration-200 ${a11yClasses.focusVisible} ${a11yClasses.reducedMotion} ${a11yClasses.highContrast}`;
    
    switch (variant) {
      case 'primary':
        return `${baseClasses} bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white shadow-lg hover:shadow-xl focus:ring-blue-500 border border-transparent`;
      case 'secondary':
        return `${baseClasses} bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-900 shadow-md hover:shadow-lg focus:ring-gray-500 border border-gray-300`;
      case 'outline':
        return `${baseClasses} bg-transparent hover:bg-gray-50 text-gray-700 border border-gray-300 hover:border-gray-400 focus:ring-gray-500`;
      case 'ghost':
        return `${baseClasses} bg-transparent hover:bg-gray-100 text-gray-700 border border-transparent focus:ring-gray-500`;
      case 'danger':
        return `${baseClasses} bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white shadow-lg hover:shadow-xl focus:ring-red-500 border border-transparent`;
      case 'success':
        return `${baseClasses} bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white shadow-lg hover:shadow-xl focus:ring-green-500 border border-transparent`;
      default:
        return `${baseClasses} bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white shadow-lg hover:shadow-xl focus:ring-blue-500 border border-transparent`;
    }
  };

  const getSizeClasses = () => {
    switch (size) {
      case 'sm':
        return 'px-3 py-1.5 text-sm rounded-md';
      case 'lg':
        return 'px-6 py-3 text-lg rounded-lg';
      default:
        return 'px-4 py-2 text-base rounded-lg';
    }
  };

  const getDisabledClasses = () => {
    if (disabled || loading) {
      return 'opacity-50 cursor-not-allowed';
    }
    return '';
  };

  // Manipulador de clique com anúncio de status
  const handleClick = (event: React.MouseEvent<HTMLButtonElement>) => {
    if (loading || disabled) return;
    
    onClick?.(event);
    
    // Anuncia ação para leitores de tela se necessário
    if (ariaLabel && variant === 'danger') {
      announce(`${ariaLabel} acionado`, 'assertive');
    }
  };

  // Props ARIA
  const ariaProps = getButtonAriaProps({
    label: ariaLabel,
    describedBy: ariaDescribedBy,
    pressed: ariaPressed,
    expanded: ariaExpanded,
    disabled: disabled || loading,
    controls: ariaControls,
  });

  const buttonClasses = [
    'inline-flex items-center justify-center gap-2',
    fullWidth ? 'w-full' : '',
    getVariantClasses(),
    getSizeClasses(),
    getDisabledClasses(),
    className
  ].filter(Boolean).join(' ');

  return (
    <button
      className={buttonClasses}
      disabled={disabled || loading}
      onClick={handleClick}
      {...ariaProps}
      {...props}
    >
      {loading && (
        <>
          <div 
            className="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin" 
            aria-hidden="true"
          />
          <span className={a11yClasses.srOnly}>
            {loadingText}
          </span>
        </>
      )}
      {leftIcon && !loading && (
        <div className="w-4 h-4" aria-hidden="true">
          {leftIcon}
        </div>
      )}
      <span>{children}</span>
      {rightIcon && !loading && (
        <div className="w-4 h-4" aria-hidden="true">
          {rightIcon}
        </div>
      )}
    </button>
  );
};

export default Button;