/**
 * Componente de Teste de Responsividade
 * 
 * Este componente demonstra como nossos componentes se comportam
 * em diferentes tamanhos de tela e dispositivos.
 */

import React, { useState } from 'react';
import Button from './ui/Button';
import Card, { CardHeader, CardTitle, CardContent } from './ui/Card';
import Input from './ui/Input';
import Badge from './ui/Badge';

const ResponsiveTest = () => {
  const [inputValue, setInputValue] = useState('');
  const [showMobileView, setShowMobileView] = useState(false);

  return (
    <div className="min-h-screen bg-gradient-to-br from-primary-50 to-secondary-100 p-4">
      {/* Header de Teste */}
      <div className="max-w-7xl mx-auto mb-8">
        <div className="text-center mb-6">
          <h1 className="text-3xl md:text-4xl font-bold text-secondary-900 mb-2">
            Teste de Responsividade
          </h1>
          <p className="text-secondary-600 text-lg">
            Demonstração dos componentes em diferentes tamanhos de tela
          </p>
        </div>

        {/* Indicadores de Breakpoint */}
        <div className="flex flex-wrap justify-center gap-2 mb-6">
          <Badge variant="primary" className="xs:block sm:hidden">
            XS (até 475px)
          </Badge>
          <Badge variant="secondary" className="hidden sm:block md:hidden">
            SM (475px - 640px)
          </Badge>
          <Badge variant="success" className="hidden md:block lg:hidden">
            MD (640px - 768px)
          </Badge>
          <Badge variant="warning" className="hidden lg:block xl:hidden">
            LG (768px - 1024px)
          </Badge>
          <Badge variant="danger" className="hidden xl:block 2xl:hidden">
            XL (1024px - 1280px)
          </Badge>
          <Badge variant="primary" className="hidden 2xl:block">
            2XL (1280px+)
          </Badge>
        </div>
      </div>

      {/* Grid Responsivo de Componentes */}
      <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        
        {/* Card de Botões */}
        <Card variant="elevated" className="h-fit">
          <CardHeader>
            <CardTitle>Botões Responsivos</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-3">
              <Button variant="primary" size="sm" fullWidth className="md:w-auto">
                Pequeno
              </Button>
              <Button variant="secondary" size="md" fullWidth className="md:w-auto">
                Médio
              </Button>
              <Button variant="success" size="lg" fullWidth className="md:w-auto">
                Grande
              </Button>
            </div>
            
            <div className="flex flex-col sm:flex-row gap-2">
              <Button variant="warning" className="flex-1">
                Flexível
              </Button>
              <Button variant="danger" className="flex-1">
                Responsivo
              </Button>
            </div>
          </CardContent>
        </Card>

        {/* Card de Inputs */}
        <Card variant="primary" className="h-fit">
          <CardHeader>
            <CardTitle>Inputs Responsivos</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <Input
              label="Nome Completo"
              placeholder="Digite seu nome"
              value={inputValue}
              onChange={(e) => setInputValue(e.target.value)}
              size="md"
            />
            
            <Input
              label="Email"
              type="email"
              placeholder="seu@email.com"
              size="lg"
              helperText="Será usado para notificações"
            />
            
            <Input
              label="Senha"
              type="password"
              placeholder="••••••••"
              size="sm"
              error="Senha deve ter pelo menos 8 caracteres"
            />
          </CardContent>
        </Card>

        {/* Card de Badges */}
        <Card variant="secondary" className="h-fit md:col-span-2 xl:col-span-1">
          <CardHeader>
            <CardTitle>Badges e Estados</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="flex flex-wrap gap-2 mb-4">
              <Badge variant="primary" size="sm">Ativo</Badge>
              <Badge variant="success" size="md">Concluído</Badge>
              <Badge variant="warning" size="lg">Pendente</Badge>
              <Badge variant="danger">Erro</Badge>
            </div>
            
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-2">
              <Badge variant="primary">Status 1</Badge>
              <Badge variant="secondary">Status 2</Badge>
              <Badge variant="success">Status 3</Badge>
              <Badge variant="warning">Status 4</Badge>
              <Badge variant="danger">Status 5</Badge>
              <Badge variant="primary">Status 6</Badge>
            </div>
          </CardContent>
        </Card>

        {/* Card de Layout Responsivo */}
        <Card variant="elevated" className="md:col-span-2">
          <CardHeader>
            <CardTitle>Layout Responsivo</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
              {[1, 2, 3, 4].map((item) => (
                <div
                  key={item}
                  className="bg-gradient-to-br from-primary-100 to-primary-200 
                           rounded-lg p-4 text-center border border-primary-300
                           hover:shadow-md transition-all duration-200"
                >
                  <div className="text-2xl font-bold text-primary-700 mb-2">
                    {item}
                  </div>
                  <div className="text-sm text-primary-600">
                    Item {item}
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>

        {/* Card de Navegação Mobile */}
        <Card variant="primary" className="h-fit">
          <CardHeader>
            <CardTitle>Navegação Mobile</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <Button
              variant="secondary"
              fullWidth
              onClick={() => setShowMobileView(!showMobileView)}
            >
              {showMobileView ? 'Ocultar' : 'Mostrar'} Menu Mobile
            </Button>
            
            {showMobileView && (
              <div className="bg-secondary-50 rounded-lg p-4 border border-secondary-200">
                <div className="space-y-2">
                  <div className="flex items-center p-2 rounded hover:bg-secondary-100 cursor-pointer">
                    <span className="text-secondary-700">📊 Dashboard</span>
                  </div>
                  <div className="flex items-center p-2 rounded hover:bg-secondary-100 cursor-pointer">
                    <span className="text-secondary-700">📍 Paradas</span>
                  </div>
                  <div className="flex items-center p-2 rounded hover:bg-secondary-100 cursor-pointer">
                    <span className="text-secondary-700">👥 Usuários</span>
                  </div>
                  <div className="flex items-center p-2 rounded hover:bg-secondary-100 cursor-pointer">
                    <span className="text-secondary-700">🏢 Áreas</span>
                  </div>
                </div>
              </div>
            )}
          </CardContent>
        </Card>
      </div>

      {/* Footer com Informações Técnicas */}
      <div className="max-w-7xl mx-auto mt-12 p-6 bg-white rounded-xl shadow-soft">
        <h3 className="text-lg font-semibold text-secondary-900 mb-4">
          Informações Técnicas
        </h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm text-secondary-600">
          <div>
            <strong>Breakpoints:</strong>
            <ul className="mt-1 space-y-1">
              <li>XS: até 475px</li>
              <li>SM: 475px - 640px</li>
              <li>MD: 640px - 768px</li>
            </ul>
          </div>
          <div>
            <strong>Breakpoints (cont.):</strong>
            <ul className="mt-1 space-y-1">
              <li>LG: 768px - 1024px</li>
              <li>XL: 1024px - 1280px</li>
              <li>2XL: 1280px+</li>
            </ul>
          </div>
          <div>
            <strong>Recursos:</strong>
            <ul className="mt-1 space-y-1">
              <li>Design System</li>
              <li>Tailwind CSS</li>
              <li>Componentes Reutilizáveis</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ResponsiveTest;