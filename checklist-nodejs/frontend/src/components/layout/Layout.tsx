import React from 'react';
import { Outlet, Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuthStore } from '../../stores/authStore';
import { 
  Home, 
  MapPin, 
  CheckSquare, 
  Users, 
  Settings, 
  LogOut,
  Menu,
  X,
  User,
  Wrench
} from 'lucide-react';
import MobileNavigation, { MobileHeader } from '../MobileNavigation.jsx';
import { useMobileNavigation } from '../../hooks/useMobileNavigation';
import { useResponsive } from '../../hooks/useResponsive';

const Layout: React.FC = () => {
  const { user, logout } = useAuthStore();
  const navigate = useNavigate();
  const location = useLocation();
  const [sidebarOpen, setSidebarOpen] = React.useState(false);
  
  // Hooks de responsividade
  const { isMobile, isTablet, isDesktop, shouldShowMobileSidebar } = useResponsive();
  const { isOpen: mobileMenuOpen, openMenu, closeMenu } = useMobileNavigation();

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  const navigation = [
    { name: 'Dashboard', href: '/', icon: Home },
    { name: 'Áreas', href: '/areas', icon: MapPin },
    { name: 'Equipamentos', href: '/equipments', icon: Wrench },
    { name: 'Paradas', href: '/paradas', icon: CheckSquare },
    ...(user?.role === 'ADMIN' ? [{ name: 'Usuários', href: '/users', icon: Users }] : []),
  ];

  // Componente da seção do usuário no sidebar
  const UserSection = () => (
    <div className="p-6 border-t border-white/10 mt-auto">
      {/* Informações do usuário */}
      <div className="flex items-center gap-3 mb-4 p-3 bg-white/10 rounded-lg backdrop-blur-sm">
        <div className="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-base font-semibold shadow-lg shadow-blue-500/30">
          {(user?.nome || 'U').charAt(0).toUpperCase()}
        </div>
        <div className="flex-1 min-w-0">
          <div className="text-xs text-white/70 mb-0.5">
            Bem-vindo,
          </div>
          <div className="text-sm text-white font-semibold truncate">
            {user?.nome || 'Usuário'}
          </div>
        </div>
      </div>

      {/* Botão de logout */}
      <button
        onClick={handleLogout}
        className="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-500/90 hover:bg-red-600/95 text-white border-none rounded-lg text-sm font-medium cursor-pointer transition-all duration-200 shadow-lg shadow-red-500/30 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-red-500/40"
      >
        <LogOut className="w-4 h-4" />
        Sair
      </button>
    </div>
  );

  return (
    <div className="min-h-screen bg-slate-50">
      {/* Header Mobile */}
      <MobileHeader 
        onMenuClick={openMenu} 
        title={navigation.find(item => item.href === location.pathname)?.name || 'Dashboard'} 
      />
      
      {/* Navegação Mobile */}
      <MobileNavigation 
        isOpen={mobileMenuOpen} 
        onClose={closeMenu} 
      />
      
      {/* Sidebar para desktop */}
      <div 
        className={`sidebar ${isDesktop ? 'flex' : 'hidden'} flex-col bg-gradient-to-b from-blue-800 via-blue-900 to-slate-800 shadow-2xl border-r border-white/10`}
      >
        {/* Header do sidebar */}
        <div className="p-6 border-b border-white/10">
          <h1 className="text-xl font-bold text-white text-center drop-shadow-lg">
            Sistema Checklist
          </h1>
        </div>

        {/* Navegação */}
        <nav className="flex-1 py-4">
          {navigation.map((item) => {
            const Icon = item.icon;
            const isActive = location.pathname === item.href;
            return (
              <Link
                key={item.name}
                to={item.href}
                className={`
                  flex items-center gap-3 px-6 py-3.5 mx-3 my-1 rounded-lg text-sm font-medium transition-all duration-200 no-underline
                  ${isActive 
                    ? 'text-white bg-white/15 border border-white/20 backdrop-blur-sm font-semibold' 
                    : 'text-white/80 hover:text-white hover:bg-white/10 hover:translate-x-1'
                  }
                `}
              >
                <Icon className="w-5 h-5" />
                {item.name}
              </Link>
            );
          })}
        </nav>

        {/* Seção do usuário */}
        <UserSection />
      </div>



      {/* Conteúdo principal */}
      <div className={`main-content ${shouldShowMobileSidebar() ? 'pt-0' : 'pt-6'}`}>
        
        {/* Header simplificado - apenas desktop */}
        {isDesktop && (
          <header className="bg-white p-8 shadow-sm mb-8 rounded-xl border border-gray-200">
            <div>
              <h1 className="text-3xl font-bold text-gray-900 m-0 leading-tight">
                {navigation.find(item => item.href === location.pathname)?.name || 'Dashboard'}
              </h1>
              <p className="text-sm text-gray-600 mt-2">
                Bem-vindo de volta! Aqui está um resumo do sistema.
              </p>
            </div>
          </header>
        )}

        {/* Conteúdo da página */}
        <main className="flex-1">
          <div className={shouldShowMobileSidebar() ? 'p-[var(--mobile-padding)]' : 'p-0'}>
            <div className="container">
              <Outlet />
            </div>
          </div>
        </main>
      </div>
    </div>
  );
};

export default Layout;