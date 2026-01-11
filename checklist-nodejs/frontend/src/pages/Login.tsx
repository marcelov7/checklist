import React, { useState } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import { LoginForm } from '../components/forms';
import { useAuthStore } from '../stores/authStore';
import { authService } from '../services/authService';
import { useResponsive } from '../hooks/useResponsive';

export const Login: React.FC = () => {
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();
  const location = useLocation();
  const { login, setError, error } = useAuthStore();
  const { isMobile } = useResponsive();

  const from = location.state?.from?.pathname || '/';

  const handleSubmit = async (data: { identifier: string; password: string }) => {
    setIsLoading(true);
    setError(null);

    try {
      // Determina se o identificador é email ou username
      const isEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.identifier);
      
      const loginData = {
        ...(isEmail ? { email: data.identifier } : { username: data.identifier }),
        password: data.password
      };

      const response = await authService.login(loginData);
      login(response.user, response.token);
      navigate(from, { replace: true });
    } catch (err: any) {
      setError(err.message || 'Erro ao fazer login');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div 
      className="login-page"
      style={{
        minHeight: '100vh',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: 'var(--color-background)',
        padding: isMobile ? 'var(--mobile-padding)' : '3rem 1.5rem'
      }}
    >
      <div 
        style={{
          width: '100%',
          maxWidth: isMobile ? '100%' : '28rem',
          backgroundColor: 'white',
          borderRadius: 'var(--border-radius-lg)',
          boxShadow: isMobile ? 'none' : '0 10px 25px rgba(0, 0, 0, 0.1)',
          padding: isMobile ? '1.5rem' : '2.5rem',
          border: isMobile ? 'none' : '1px solid var(--color-border)'
        }}
      >
        <LoginForm
          onSubmit={handleSubmit}
          loading={isLoading}
          error={error}
        />
      </div>
    </div>
  );
};