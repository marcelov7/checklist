import React, { useState, useEffect } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { useAuthStore } from '../stores/authStore';
import { useResponsive } from '../hooks/useResponsive';

/**
 * Componente de Navegação Mobile
 * Fornece uma sidebar responsiva para dispositivos móveis
 */
const MobileNavigation = ({ isOpen, onClose }) => {
  const { user, logout } = useAuthStore();
  const location = useLocation();
  const navigate = useNavigate();
  const { isMobile, shouldShowMobileSidebar } = useResponsive();

  // Itens de navegação baseados no papel do usuário
  const getNavigationItems = () => {
    const baseItems = [
      {
        path: '/',
        label: 'Dashboard',
        icon: (
          <svg className="nav-icon-mobile" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
          </svg>
        ),
      },
      {
        path: '/areas',
        label: 'Áreas',
        icon: (
          <svg className="nav-icon-mobile" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        ),
      },
      {
        path: '/equipments',
        label: 'Equipamentos',
        icon: (
          <svg className="nav-icon-mobile" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        ),
      },
      {
        path: '/paradas',
        label: 'Paradas',
        icon: (
          <svg className="nav-icon-mobile" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        ),
      },
    ];

    // Adicionar itens administrativos se o usuário for ADMIN
    if (user?.role === 'ADMIN') {
      baseItems.push(
        {
          path: '/users',
          label: 'Usuários',
          icon: (
            <svg className="nav-icon-mobile" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
          ),
        }
      );
    }

    return baseItems;
  };

  const navigationItems = getNavigationItems();

  // Função para verificar se o link está ativo
  const isActiveLink = (path) => {
    return location.pathname === path;
  };

  // Função para lidar com logout
  const handleLogout = async () => {
    try {
      await logout();
      navigate('/login');
      onClose();
    } catch (error) {
      console.error('Erro ao fazer logout:', error);
    }
  };

  // Função para lidar com clique em link
  const handleLinkClick = () => {
    if (isMobile) {
      onClose();
    }
  };

  // Fechar sidebar ao pressionar ESC
  useEffect(() => {
    const handleKeyDown = (event) => {
      if (event.key === 'Escape' && isOpen) {
        onClose();
      }
    };

    document.addEventListener('keydown', handleKeyDown);
    return () => document.removeEventListener('keydown', handleKeyDown);
  }, [isOpen, onClose]);

  // Prevenir scroll do body quando sidebar está aberta
  useEffect(() => {
    if (isOpen && isMobile) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'unset';
    }

    return () => {
      document.body.style.overflow = 'unset';
    };
  }, [isOpen, isMobile]);

  // Não renderizar se não for necessário mostrar sidebar mobile
  if (!shouldShowMobileSidebar()) {
    return null;
  }

  return (
    <>
      {/* Overlay */}
      {isOpen && (
        <div 
          className="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 transition-opacity duration-300"
          onClick={onClose}
          aria-hidden="true"
        />
      )}

      {/* Sidebar Mobile */}
      <aside 
        className={`fixed top-0 left-0 h-full w-80 bg-gradient-to-b from-blue-800 via-blue-900 to-slate-800 shadow-2xl z-50 transform transition-transform duration-300 ease-in-out flex flex-col ${
          isOpen ? 'translate-x-0' : '-translate-x-full'
        }`}
        role="navigation"
        aria-label="Navegação principal"
      >
        {/* Header da Sidebar */}
        <div className="flex items-center justify-between p-6 border-b border-white/10">
          <h2 className="text-xl font-bold text-white">Checklist</h2>
          <button
            className="p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors duration-200"
            onClick={onClose}
            aria-label="Fechar menu"
          >
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        {/* Informações do Usuário */}
        <div className="p-6 border-b border-white/10">
          <div className="flex items-center gap-3">
            <div className="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
              <span className="text-white font-semibold text-lg">
                {(user?.nome || user?.name || 'U').charAt(0).toUpperCase()}
              </span>
            </div>
            <div className="flex-1 min-w-0">
              <p className="text-white font-medium text-sm truncate">
                {user?.nome || user?.name || 'Usuário'}
              </p>
              <p className="text-white/70 text-xs truncate">
                {user?.email}
              </p>
              <span className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/20 text-white mt-1">
                {user?.role === 'ADMIN' ? 'Administrador' : 'Usuário'}
              </span>
            </div>
          </div>
        </div>

        {/* Navegação */}
        <nav className="flex-1 py-4" role="navigation">
          {navigationItems.map((item) => (
            <div key={item.path} className="px-3 mb-1">
              <Link
                to={item.path}
                className={`flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 no-underline ${
                  isActiveLink(item.path)
                    ? 'text-white bg-white/15 border border-white/20 backdrop-blur-sm font-semibold'
                    : 'text-white/80 hover:text-white hover:bg-white/10 hover:translate-x-1'
                }`}
                onClick={handleLinkClick}
                aria-current={isActiveLink(item.path) ? 'page' : undefined}
              >
                <div className="w-5 h-5">
                  {item.icon}
                </div>
                <span>{item.label}</span>
              </Link>
            </div>
          ))}
        </nav>

        {/* Botão de Logout */}
        <div className="mt-auto p-6 border-t border-white/10">
          <button
            onClick={handleLogout}
            className="flex items-center gap-3 w-full px-4 py-3 text-white/80 hover:text-white hover:bg-red-500/20 rounded-lg text-sm font-medium transition-all duration-200 text-left"
            aria-label="Sair do sistema"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span>Sair</span>
          </button>
        </div>
      </aside>
    </>
  );
};

/**
 * Componente do Header Mobile
 * Fornece um header fixo com botão de menu para dispositivos móveis
 */
export const MobileHeader = ({ onMenuClick, title = 'Checklist' }) => {
  const { shouldShowMobileSidebar } = useResponsive();

  if (!shouldShowMobileSidebar()) {
    return null;
  }

  return (
    <header className="lg:hidden bg-gradient-to-r from-slate-800 to-slate-900 border-b border-slate-700 shadow-lg">
      <div className="flex items-center justify-between px-4 py-3">
        <h1 className="text-xl font-bold text-white">{title}</h1>
        <button
          onClick={onMenuClick}
          className="p-2 text-white hover:bg-white/10 rounded-lg transition-colors duration-200"
          aria-label="Abrir menu"
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </header>
  );
};



export default MobileNavigation;