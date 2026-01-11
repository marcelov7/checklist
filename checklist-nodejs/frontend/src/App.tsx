import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Layout from './components/layout/Layout';
import { ProtectedRoute } from './components/auth/ProtectedRoute';
import { Login } from './pages/Login';
import { Dashboard } from './pages/Dashboard';
import Areas from './pages/Areas';
import Equipments from './pages/Equipments';
import Paradas from './pages/Paradas';
import Users from './pages/Users';
import ResponsiveTest from './components/ResponsiveTest';
import MobileOptimizationDemo from './components/MobileOptimizationDemo';
import AccessibilityDemo from './components/AccessibilityDemo';
import PerformanceDemo from './components/PerformanceDemo';
import { useAuthStore } from './stores/authStore';

// Componentes temporários para as outras páginas
const Settings = () => <div className="p-6"><h1 className="text-2xl font-bold">Configurações</h1><p>Página em desenvolvimento...</p></div>;
const NotFound = () => <div className="p-6"><h1 className="text-2xl font-bold">404 - Página não encontrada</h1></div>;
const Unauthorized = () => <div className="p-6"><h1 className="text-2xl font-bold">403 - Acesso negado</h1><p>Você não tem permissão para acessar esta página.</p></div>;

function App() {
  const { isAuthenticated } = useAuthStore();

  return (
    <Router>
      <div className="App">
        <Routes>
          {/* Rota de login */}
          <Route 
            path="/login" 
            element={
              isAuthenticated ? <Navigate to="/" replace /> : <Login />
            } 
          />
          
          {/* Rotas temporárias para testes */}
          <Route path="/test" element={<ResponsiveTest />} />
          <Route path="/mobile" element={<MobileOptimizationDemo />} />
          <Route path="/accessibility" element={<AccessibilityDemo />} />
          <Route path="/performance" element={<PerformanceDemo />} />
          
          {/* Rotas protegidas */}
          <Route 
            path="/" 
            element={
              <ProtectedRoute>
                <Layout />
              </ProtectedRoute>
            }
          >
            {/* Dashboard */}
            <Route index element={<Dashboard />} />
            
            {/* Áreas */}
            <Route path="areas" element={<Areas />} />
            
            {/* Equipamentos */}
            <Route path="equipments" element={<Equipments />} />
            
            {/* Paradas */}
            <Route path="paradas" element={<Paradas />} />
            
            {/* Rotas administrativas */}
            <Route 
              path="users" 
              element={
                <ProtectedRoute requiredRole="ADMIN">
                  <Users />
                </ProtectedRoute>
              } 
            />
            <Route 
              path="settings" 
              element={
                <ProtectedRoute requiredRole="ADMIN">
                  <Settings />
                </ProtectedRoute>
              } 
            />
          </Route>
          
          {/* Páginas de erro */}
          <Route path="/unauthorized" element={<Unauthorized />} />
          <Route path="*" element={<NotFound />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
