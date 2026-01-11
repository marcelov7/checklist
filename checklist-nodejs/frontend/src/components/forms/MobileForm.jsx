import React from 'react';
import { useResponsive } from '../../hooks/useResponsive';

/**
 * Container de formulário otimizado para mobile
 * Aplica espaçamentos, padding e layout responsivos automaticamente
 */
export const MobileForm = ({ 
  children, 
  onSubmit, 
  className = '', 
  spacing = 'normal',
  ...props 
}) => {
  const { isMobile, getResponsiveSpacing } = useResponsive();

  const spacingMap = {
    tight: isMobile ? '0.75rem' : '1rem',
    normal: isMobile ? '1rem' : '1.5rem',
    loose: isMobile ? '1.5rem' : '2rem'
  };

  return (
    <form
      onSubmit={onSubmit}
      className={`mobile-form ${className}`}
      style={{
        display: 'flex',
        flexDirection: 'column',
        gap: spacingMap[spacing],
        padding: isMobile ? 'var(--mobile-padding)' : '1.5rem',
        ...props.style
      }}
      {...props}
    >
      {children}
    </form>
  );
};

/**
 * Grupo de campos de formulário com layout responsivo
 */
export const FormGroup = ({ 
  children, 
  columns = 1, 
  className = '', 
  spacing = 'normal',
  ...props 
}) => {
  const { isMobile, isTablet } = useResponsive();

  // Em mobile, sempre usar 1 coluna
  const responsiveColumns = isMobile ? 1 : (isTablet ? Math.min(columns, 2) : columns);

  const spacingMap = {
    tight: '0.75rem',
    normal: '1rem',
    loose: '1.5rem'
  };

  return (
    <div
      className={`form-group ${className}`}
      style={{
        display: 'grid',
        gridTemplateColumns: `repeat(${responsiveColumns}, 1fr)`,
        gap: spacingMap[spacing],
        width: '100%',
        ...props.style
      }}
      {...props}
    >
      {children}
    </div>
  );
};

/**
 * Campo de input otimizado para mobile
 */
export const MobileInput = ({ 
  label, 
  error, 
  required = false, 
  fullWidth = true,
  leftIcon,
  rightIcon,
  className = '',
  ...props 
}) => {
  const { isMobile, getResponsiveFontSize } = useResponsive();

  return (
    <div className={`mobile-input-container ${className}`} style={{ width: fullWidth ? '100%' : 'auto' }}>
      {label && (
        <label 
          className="mobile-input-label"
          style={{
            display: 'block',
            marginBottom: '0.5rem',
            fontSize: getResponsiveFontSize('sm'),
            fontWeight: '500',
            color: error ? 'var(--color-danger)' : 'var(--color-text)',
          }}
        >
          {label}
          {required && <span style={{ color: 'var(--color-danger)', marginLeft: '0.25rem' }}>*</span>}
        </label>
      )}
      
      <div className="mobile-input-wrapper" style={{ position: 'relative' }}>
        {leftIcon && (
          <div 
            className="mobile-input-icon-left"
            style={{
              position: 'absolute',
              left: '0.75rem',
              top: '50%',
              transform: 'translateY(-50%)',
              color: 'var(--color-text-muted)',
              zIndex: 1
            }}
          >
            {leftIcon}
          </div>
        )}
        
        <input
          className={`mobile-input ${error ? 'mobile-input-error' : ''}`}
          style={{
            width: '100%',
            minHeight: isMobile ? 'var(--touch-target-min)' : '2.5rem',
            padding: leftIcon 
              ? `0.75rem 0.75rem 0.75rem 2.5rem` 
              : rightIcon 
                ? `0.75rem 2.5rem 0.75rem 0.75rem`
                : '0.75rem',
            fontSize: getResponsiveFontSize('base'),
            border: `1px solid ${error ? 'var(--color-danger)' : 'var(--color-border)'}`,
            borderRadius: 'var(--border-radius)',
            backgroundColor: 'var(--color-background)',
            transition: 'all 0.2s ease',
            outline: 'none',
            WebkitAppearance: 'none', // Remove estilo padrão iOS
            ...props.style
          }}
          onFocus={(e) => {
            e.target.style.borderColor = 'var(--color-primary)';
            e.target.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
            props.onFocus?.(e);
          }}
          onBlur={(e) => {
            e.target.style.borderColor = error ? 'var(--color-danger)' : 'var(--color-border)';
            e.target.style.boxShadow = 'none';
            props.onBlur?.(e);
          }}
          {...props}
        />
        
        {rightIcon && (
          <div 
            className="mobile-input-icon-right"
            style={{
              position: 'absolute',
              right: '0.75rem',
              top: '50%',
              transform: 'translateY(-50%)',
              color: 'var(--color-text-muted)',
              cursor: 'pointer'
            }}
          >
            {rightIcon}
          </div>
        )}
      </div>
      
      {error && (
        <div 
          className="mobile-input-error-message"
          style={{
            marginTop: '0.25rem',
            fontSize: getResponsiveFontSize('sm'),
            color: 'var(--color-danger)'
          }}
        >
          {error}
        </div>
      )}
    </div>
  );
};

/**
 * Select otimizado para mobile
 */
export const MobileSelect = ({ 
  label, 
  options = [], 
  error, 
  required = false, 
  fullWidth = true,
  placeholder = 'Selecione uma opção',
  className = '',
  ...props 
}) => {
  const { isMobile, getResponsiveFontSize } = useResponsive();

  return (
    <div className={`mobile-select-container ${className}`} style={{ width: fullWidth ? '100%' : 'auto' }}>
      {label && (
        <label 
          className="mobile-select-label"
          style={{
            display: 'block',
            marginBottom: '0.5rem',
            fontSize: getResponsiveFontSize('sm'),
            fontWeight: '500',
            color: error ? 'var(--color-danger)' : 'var(--color-text)',
          }}
        >
          {label}
          {required && <span style={{ color: 'var(--color-danger)', marginLeft: '0.25rem' }}>*</span>}
        </label>
      )}
      
      <select
        className={`mobile-select ${error ? 'mobile-select-error' : ''}`}
        style={{
          width: '100%',
          minHeight: isMobile ? 'var(--touch-target-min)' : '2.5rem',
          padding: '0.75rem',
          fontSize: getResponsiveFontSize('base'),
          border: `1px solid ${error ? 'var(--color-danger)' : 'var(--color-border)'}`,
          borderRadius: 'var(--border-radius)',
          backgroundColor: 'var(--color-background)',
          transition: 'all 0.2s ease',
          outline: 'none',
          WebkitAppearance: 'none',
          backgroundImage: `url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e")`,
          backgroundPosition: 'right 0.5rem center',
          backgroundRepeat: 'no-repeat',
          backgroundSize: '1.5em 1.5em',
          paddingRight: '2.5rem',
          ...props.style
        }}
        onFocus={(e) => {
          e.target.style.borderColor = 'var(--color-primary)';
          e.target.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
          props.onFocus?.(e);
        }}
        onBlur={(e) => {
          e.target.style.borderColor = error ? 'var(--color-danger)' : 'var(--color-border)';
          e.target.style.boxShadow = 'none';
          props.onBlur?.(e);
        }}
        {...props}
      >
        <option value="">{placeholder}</option>
        {options.map((option, index) => (
          <option key={option.value || index} value={option.value}>
            {option.label}
          </option>
        ))}
      </select>
      
      {error && (
        <div 
          className="mobile-select-error-message"
          style={{
            marginTop: '0.25rem',
            fontSize: getResponsiveFontSize('sm'),
            color: 'var(--color-danger)'
          }}
        >
          {error}
        </div>
      )}
    </div>
  );
};

/**
 * Textarea otimizada para mobile
 */
export const MobileTextarea = ({ 
  label, 
  error, 
  required = false, 
  fullWidth = true,
  rows = 4,
  className = '',
  ...props 
}) => {
  const { isMobile, getResponsiveFontSize } = useResponsive();

  return (
    <div className={`mobile-textarea-container ${className}`} style={{ width: fullWidth ? '100%' : 'auto' }}>
      {label && (
        <label 
          className="mobile-textarea-label"
          style={{
            display: 'block',
            marginBottom: '0.5rem',
            fontSize: getResponsiveFontSize('sm'),
            fontWeight: '500',
            color: error ? 'var(--color-danger)' : 'var(--color-text)',
          }}
        >
          {label}
          {required && <span style={{ color: 'var(--color-danger)', marginLeft: '0.25rem' }}>*</span>}
        </label>
      )}
      
      <textarea
        className={`mobile-textarea ${error ? 'mobile-textarea-error' : ''}`}
        rows={rows}
        style={{
          width: '100%',
          minHeight: isMobile ? `calc(${rows} * 1.5rem + 1.5rem)` : 'auto',
          padding: '0.75rem',
          fontSize: getResponsiveFontSize('base'),
          border: `1px solid ${error ? 'var(--color-danger)' : 'var(--color-border)'}`,
          borderRadius: 'var(--border-radius)',
          backgroundColor: 'var(--color-background)',
          transition: 'all 0.2s ease',
          outline: 'none',
          resize: 'vertical',
          fontFamily: 'inherit',
          ...props.style
        }}
        onFocus={(e) => {
          e.target.style.borderColor = 'var(--color-primary)';
          e.target.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
          props.onFocus?.(e);
        }}
        onBlur={(e) => {
          e.target.style.borderColor = error ? 'var(--color-danger)' : 'var(--color-border)';
          e.target.style.boxShadow = 'none';
          props.onBlur?.(e);
        }}
        {...props}
      />
      
      {error && (
        <div 
          className="mobile-textarea-error-message"
          style={{
            marginTop: '0.25rem',
            fontSize: getResponsiveFontSize('sm'),
            color: 'var(--color-danger)'
          }}
        >
          {error}
        </div>
      )}
    </div>
  );
};

/**
 * Checkbox otimizado para mobile
 */
export const MobileCheckbox = ({ 
  label, 
  error, 
  className = '',
  ...props 
}) => {
  const { isMobile, getResponsiveFontSize } = useResponsive();

  return (
    <div className={`mobile-checkbox-container ${className}`}>
      <label 
        className="mobile-checkbox-label"
        style={{
          display: 'flex',
          alignItems: 'center',
          gap: '0.75rem',
          cursor: 'pointer',
          fontSize: getResponsiveFontSize('base'),
          color: error ? 'var(--color-danger)' : 'var(--color-text)',
          minHeight: isMobile ? 'var(--touch-target-min)' : 'auto',
        }}
      >
        <input
          type="checkbox"
          className="mobile-checkbox"
          style={{
            width: isMobile ? '1.25rem' : '1rem',
            height: isMobile ? '1.25rem' : '1rem',
            accentColor: 'var(--color-primary)',
            cursor: 'pointer',
            ...props.style
          }}
          {...props}
        />
        {label}
      </label>
      
      {error && (
        <div 
          className="mobile-checkbox-error-message"
          style={{
            marginTop: '0.25rem',
            fontSize: getResponsiveFontSize('sm'),
            color: 'var(--color-danger)'
          }}
        >
          {error}
        </div>
      )}
    </div>
  );
};

/**
 * Botões de ação do formulário otimizados para mobile
 */
export const FormActions = ({ 
  children, 
  align = 'right', 
  stack = 'auto',
  className = '',
  ...props 
}) => {
  const { isMobile } = useResponsive();

  const shouldStack = stack === 'auto' ? isMobile : stack === 'always';
  
  const alignmentMap = {
    left: 'flex-start',
    center: 'center',
    right: 'flex-end',
    stretch: 'stretch'
  };

  return (
    <div
      className={`form-actions ${className}`}
      style={{
        display: 'flex',
        flexDirection: shouldStack ? 'column' : 'row',
        gap: '0.75rem',
        justifyContent: shouldStack ? 'stretch' : alignmentMap[align],
        alignItems: shouldStack ? 'stretch' : 'center',
        marginTop: '1.5rem',
        ...props.style
      }}
      {...props}
    >
      {children}
    </div>
  );
};