import React, { useState } from 'react';
import { Eye, EyeOff, LogIn, Mail, Lock } from 'lucide-react';
import { MobileForm, MobileInput, FormActions } from './MobileForm';
import ResponsiveButton from '../ResponsiveButton';
import { useResponsive } from '../../hooks/useResponsive';

export const LoginForm = ({ 
  onSubmit, 
  loading = false, 
  error = null,
  className = '',
  ...props 
}) => {
  const [showPassword, setShowPassword] = useState(false);
  const [formData, setFormData] = useState({
    identifier: '',
    password: ''
  });
  const [errors, setErrors] = useState({});
  
  const { isMobile, getResponsiveFontSize } = useResponsive();

  const validateForm = () => {
    const newErrors = {};
    
    if (!formData.identifier.trim()) {
      newErrors.identifier = 'Email ou username é obrigatório';
    }
    
    if (!formData.password) {
      newErrors.password = 'Senha é obrigatória';
    } else if (formData.password.length < 6) {
      newErrors.password = 'Senha deve ter pelo menos 6 caracteres';
    }
    
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }
    
    onSubmit?.(formData);
  };

  const handleInputChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
    
    // Limpar erro do campo quando o usuário começar a digitar
    if (errors[field]) {
      setErrors(prev => ({ ...prev, [field]: '' }));
    }
  };

  return (
    <div className={`login-form-container ${className}`} {...props}>
      {/* Header do formulário */}
      <div 
        className="login-header"
        style={{
          textAlign: 'center',
          marginBottom: isMobile ? '2rem' : '2.5rem'
        }}
      >
        <div 
          className="login-icon"
          style={{
            width: isMobile ? '4rem' : '5rem',
            height: isMobile ? '4rem' : '5rem',
            backgroundColor: 'var(--color-primary)',
            borderRadius: '50%',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            margin: '0 auto 1rem',
            color: 'white'
          }}
        >
          <LogIn size={isMobile ? 24 : 32} />
        </div>
        
        <h1 
          style={{
            fontSize: getResponsiveFontSize('2xl'),
            fontWeight: 'bold',
            color: 'var(--color-text)',
            margin: '0 0 0.5rem 0'
          }}
        >
          Checklist System
        </h1>
        
        <p 
          style={{
            fontSize: getResponsiveFontSize('base'),
            color: 'var(--color-text-muted)',
            margin: 0
          }}
        >
          Faça login em sua conta
        </p>
      </div>

      {/* Erro geral */}
      {error && (
        <div 
          className="login-error"
          style={{
            backgroundColor: 'var(--color-danger-light)',
            border: '1px solid var(--color-danger)',
            color: 'var(--color-danger-dark)',
            padding: '0.75rem 1rem',
            borderRadius: 'var(--border-radius)',
            marginBottom: '1.5rem',
            fontSize: getResponsiveFontSize('sm'),
            textAlign: 'center'
          }}
        >
          {error}
        </div>
      )}

      {/* Formulário */}
      <MobileForm onSubmit={handleSubmit} spacing="normal">
        <MobileInput
          label="Email ou Username"
          type="text"
          value={formData.identifier}
          onChange={(e) => handleInputChange('identifier', e.target.value)}
          error={errors.identifier}
          placeholder="seu@email.com ou seu_username"
          leftIcon={<Mail size={18} />}
          autoComplete="username"
          autoCapitalize="none"
          autoCorrect="off"
          required
        />

        <MobileInput
          label="Senha"
          type={showPassword ? 'text' : 'password'}
          value={formData.password}
          onChange={(e) => handleInputChange('password', e.target.value)}
          error={errors.password}
          placeholder="Digite sua senha"
          leftIcon={<Lock size={18} />}
          rightIcon={
            <button
              type="button"
              onClick={() => setShowPassword(!showPassword)}
              style={{
                background: 'none',
                border: 'none',
                cursor: 'pointer',
                padding: 0,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                color: 'var(--color-text-muted)'
              }}
              aria-label={showPassword ? 'Ocultar senha' : 'Mostrar senha'}
            >
              {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
            </button>
          }
          autoComplete="current-password"
          required
        />

        <FormActions align="stretch">
          <ResponsiveButton
            type="submit"
            variant="primary"
            size={isMobile ? 'lg' : 'md'}
            loading={loading}
            disabled={loading}
            fullWidth
          >
            {loading ? 'Entrando...' : 'Entrar'}
          </ResponsiveButton>
        </FormActions>
      </MobileForm>

      {/* Links adicionais */}
      <div 
        className="login-links"
        style={{
          textAlign: 'center',
          marginTop: '1.5rem',
          fontSize: getResponsiveFontSize('sm'),
          color: 'var(--color-text-muted)'
        }}
      >
        <a 
          href="#forgot-password"
          style={{
            color: 'var(--color-primary)',
            textDecoration: 'none',
            fontWeight: '500'
          }}
          onMouseEnter={(e) => e.target.style.textDecoration = 'underline'}
          onMouseLeave={(e) => e.target.style.textDecoration = 'none'}
        >
          Esqueceu sua senha?
        </a>
      </div>

      {/* Informações de ajuda para mobile */}
      {isMobile && (
        <div 
          className="mobile-help"
          style={{
            marginTop: '2rem',
            padding: '1rem',
            backgroundColor: 'var(--color-background-secondary)',
            borderRadius: 'var(--border-radius)',
            fontSize: getResponsiveFontSize('sm'),
            color: 'var(--color-text-muted)',
            textAlign: 'center'
          }}
        >
          <p style={{ margin: '0 0 0.5rem 0', fontWeight: '500' }}>
            💡 Dica para mobile:
          </p>
          <p style={{ margin: 0 }}>
            Use o teclado virtual para navegar entre os campos ou toque diretamente nos campos de entrada.
          </p>
        </div>
      )}
    </div>
  );
};