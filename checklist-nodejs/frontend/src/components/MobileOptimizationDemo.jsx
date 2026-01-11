import React, { useState } from 'react';
import { Button, Card, CardHeader, CardTitle, CardContent, Input, Badge } from './ui';
import { useDeviceDetection, getMobileClasses, TOUCH_TARGET_SIZE } from '../utils/mobile';

/**
 * Componente de demonstração das otimizações mobile
 * 
 * Este componente mostra como os utilitários mobile podem ser usados
 * para criar interfaces otimizadas para dispositivos móveis e touch.
 */
const MobileOptimizationDemo = () => {
  const [inputValue, setInputValue] = useState('');
  const [showModal, setShowModal] = useState(false);
  const deviceInfo = useDeviceDetection();
  const mobileClasses = getMobileClasses(deviceInfo.isMobile);

  return (
    <div className="min-h-screen bg-gray-50 safe-area-all">
      {/* Header com informações do dispositivo */}
      <div className={`bg-white shadow-sm ${mobileClasses.padding}`}>
        <h1 className={`font-bold text-gray-900 ${mobileClasses.heading}`}>
          Demonstração Mobile
        </h1>
        <div className="mt-2 space-y-1">
          <Badge variant={deviceInfo.isMobile ? 'success' : 'secondary'}>
            Mobile: {deviceInfo.isMobile ? 'Sim' : 'Não'}
          </Badge>
          <Badge variant={deviceInfo.isTouch ? 'success' : 'secondary'}>
            Touch: {deviceInfo.isTouch ? 'Sim' : 'Não'}
          </Badge>
          {deviceInfo.isIOS && <Badge variant="primary">iOS</Badge>}
          {deviceInfo.isAndroid && <Badge variant="warning">Android</Badge>}
        </div>
      </div>

      {/* Conteúdo principal */}
      <div className={`${mobileClasses.padding} space-y-6`}>
        
        {/* Seção: Botões Touch-Friendly */}
        <Card className="card-mobile">
          <CardHeader>
            <CardTitle className={mobileClasses.subheading}>
              Botões Touch-Friendly
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <Button 
                variant="primary" 
                className="btn-touch touch-target"
                style={{ minHeight: `${TOUCH_TARGET_SIZE.comfortable}px` }}
              >
                Botão Primário
              </Button>
              <Button 
                variant="outline" 
                className="btn-touch touch-target"
                style={{ minHeight: `${TOUCH_TARGET_SIZE.comfortable}px` }}
              >
                Botão Secundário
              </Button>
            </div>
            
            {/* Botões de ação importantes com área de toque maior */}
            <Button 
              variant="success" 
              fullWidth
              className="btn-touch touch-target"
              style={{ minHeight: `${TOUCH_TARGET_SIZE.large}px` }}
            >
              Ação Importante (Área de Toque Maior)
            </Button>
          </CardContent>
        </Card>

        {/* Seção: Inputs Otimizados */}
        <Card className="card-mobile">
          <CardHeader>
            <CardTitle className={mobileClasses.subheading}>
              Inputs Otimizados
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <Input
              label="Campo de Texto"
              placeholder="Digite algo aqui..."
              value={inputValue}
              onChange={(e) => setInputValue(e.target.value)}
              className="input-touch"
              helperText="Fonte 16px previne zoom no iOS"
            />
            
            <Input
              label="Email"
              type="email"
              placeholder="seu@email.com"
              className="input-touch"
              helperText="Teclado otimizado para email"
            />
            
            <div className="space-y-2">
              <label className={`block font-medium text-gray-700 ${mobileClasses.body}`}>
                Área de Texto
              </label>
              <textarea
                className="textarea-touch input-touch w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                placeholder="Digite uma mensagem mais longa aqui..."
                rows={4}
              />
            </div>
          </CardContent>
        </Card>

        {/* Seção: Lista Touch-Friendly */}
        <Card className="card-mobile">
          <CardHeader>
            <CardTitle className={mobileClasses.subheading}>
              Lista Touch-Friendly
            </CardTitle>
          </CardHeader>
          <CardContent className="p-0">
            <ul className="list-mobile">
              {['Item 1', 'Item 2', 'Item 3', 'Item 4'].map((item, index) => (
                <li key={index} className="list-item touch-target cursor-pointer">
                  <div className="flex items-center justify-between w-full">
                    <span className={mobileClasses.body}>{item}</span>
                    <Badge variant="outline" size="sm">
                      {index + 1}
                    </Badge>
                  </div>
                </li>
              ))}
            </ul>
          </CardContent>
        </Card>

        {/* Seção: Modal Mobile */}
        <Card className="card-mobile">
          <CardHeader>
            <CardTitle className={mobileClasses.subheading}>
              Modal Mobile
            </CardTitle>
          </CardHeader>
          <CardContent>
            <Button 
              variant="primary" 
              onClick={() => setShowModal(true)}
              className="btn-touch touch-target w-full"
            >
              Abrir Modal Mobile
            </Button>
          </CardContent>
        </Card>

        {/* Seção: Informações Técnicas */}
        <Card className="card-mobile">
          <CardHeader>
            <CardTitle className={mobileClasses.subheading}>
              Informações Técnicas
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-3">
            <div className="grid grid-cols-2 gap-4 text-sm">
              <div>
                <span className="font-medium text-gray-600">Largura da Tela:</span>
                <p className="text-gray-900">{typeof window !== 'undefined' ? window.innerWidth : 'N/A'}px</p>
              </div>
              <div>
                <span className="font-medium text-gray-600">Altura da Tela:</span>
                <p className="text-gray-900">{typeof window !== 'undefined' ? window.innerHeight : 'N/A'}px</p>
              </div>
              <div>
                <span className="font-medium text-gray-600">User Agent:</span>
                <p className="text-gray-900 text-xs break-all">
                  {typeof window !== 'undefined' ? navigator.userAgent.substring(0, 50) + '...' : 'N/A'}
                </p>
              </div>
              <div>
                <span className="font-medium text-gray-600">Touch Points:</span>
                <p className="text-gray-900">
                  {typeof window !== 'undefined' ? navigator.maxTouchPoints : 'N/A'}
                </p>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Espaçamento para safe area bottom */}
        <div className="h-16 safe-area-bottom"></div>
      </div>

      {/* Modal Mobile */}
      {showModal && (
        <div className="modal-mobile">
          <div className="fixed inset-0 bg-black bg-opacity-50" onClick={() => setShowModal(false)} />
          <div className="modal-content relative">
            <div className="flex items-center justify-between mb-4">
              <h3 className={`font-semibold text-gray-900 ${mobileClasses.subheading}`}>
                Modal Mobile
              </h3>
              <Button
                variant="ghost"
                size="sm"
                onClick={() => setShowModal(false)}
                className="touch-target"
              >
                ✕
              </Button>
            </div>
            
            <div className="space-y-4">
              <p className={`text-gray-600 ${mobileClasses.body}`}>
                Este é um exemplo de modal otimizado para dispositivos móveis. 
                Ele ocupa a parte inferior da tela e tem bordas arredondadas no topo.
              </p>
              
              <div className="space-y-3">
                <Input
                  label="Campo no Modal"
                  placeholder="Digite algo..."
                  className="input-touch"
                />
                
                <div className="flex gap-3">
                  <Button
                    variant="outline"
                    onClick={() => setShowModal(false)}
                    className="btn-touch touch-target flex-1"
                  >
                    Cancelar
                  </Button>
                  <Button
                    variant="primary"
                    onClick={() => setShowModal(false)}
                    className="btn-touch touch-target flex-1"
                  >
                    Confirmar
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Menu Mobile Fixo (apenas em mobile) */}
      {deviceInfo.isMobile && (
        <div className="mobile-menu mobile-only">
          <div className="flex justify-around">
            {['Home', 'Lista', 'Config', 'Perfil'].map((item, index) => (
              <a
                key={index}
                href="#"
                className="mobile-menu-item touch-target"
                onClick={(e) => e.preventDefault()}
              >
                <div className="w-6 h-6 bg-gray-400 rounded mb-1"></div>
                <span className="text-xs">{item}</span>
              </a>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

export default MobileOptimizationDemo;