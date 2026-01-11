import React, { useState } from 'react';
import { Button, Card, CardHeader, CardTitle, CardContent, Input, Badge } from './ui';
import { 
  useFocusManagement, 
  useModalAccessibility, 
  useStatusAnnouncement,
  a11yClasses,
  KEYBOARD_KEYS,
  calculateContrastRatio,
  isContrastCompliant
} from '../utils/accessibility';

/**
 * Componente de demonstração das melhorias de acessibilidade
 * 
 * Este componente demonstra as funcionalidades de acessibilidade implementadas,
 * incluindo navegação por teclado, ARIA labels, anúncios para leitores de tela
 * e validação de contraste.
 */
const AccessibilityDemo = () => {
  const [showModal, setShowModal] = useState(false);
  const [selectedItem, setSelectedItem] = useState(0);
  const [inputValue, setInputValue] = useState('');
  const [showSkipLink, setShowSkipLink] = useState(false);
  
  const { announce } = useStatusAnnouncement();
  const { modalRef, handleKeyDown } = useModalAccessibility(showModal, () => setShowModal(false));
  const { focusedIndex, setItemRef, handleKeyNavigation } = useFocusManagement();

  // Lista de itens para demonstrar navegação por teclado
  const menuItems = [
    { id: 1, label: 'Dashboard', icon: '🏠' },
    { id: 2, label: 'Relatórios', icon: '📊' },
    { id: 3, label: 'Configurações', icon: '⚙️' },
    { id: 4, label: 'Ajuda', icon: '❓' },
  ];

  // Cores para teste de contraste
  const colorTests = [
    { bg: '#ffffff', text: '#000000', name: 'Preto no Branco' },
    { bg: '#3b82f6', text: '#ffffff', name: 'Branco no Azul' },
    { bg: '#ef4444', text: '#ffffff', name: 'Branco no Vermelho' },
    { bg: '#f3f4f6', text: '#6b7280', name: 'Cinza no Cinza Claro' },
  ];

  const handleMenuItemClick = (index, item) => {
    setSelectedItem(index);
    announce(`${item.label} selecionado`, 'polite');
  };

  const handleFormSubmit = (e) => {
    e.preventDefault();
    if (inputValue.trim()) {
      announce('Formulário enviado com sucesso', 'assertive');
      setInputValue('');
    } else {
      announce('Por favor, preencha o campo obrigatório', 'assertive');
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Skip Link */}
      <a 
        href="#main-content" 
        className={a11yClasses.skipLink}
        onFocus={() => setShowSkipLink(true)}
        onBlur={() => setShowSkipLink(false)}
      >
        Pular para o conteúdo principal
      </a>

      {/* Header */}
      <header className="bg-white shadow-sm p-6" role="banner">
        <h1 className="text-3xl font-bold text-gray-900">
          Demonstração de Acessibilidade
        </h1>
        <p className="mt-2 text-gray-600">
          Teste as funcionalidades de acessibilidade implementadas
        </p>
      </header>

      {/* Conteúdo Principal */}
      <main id="main-content" className="p-6 space-y-8" role="main">
        
        {/* Seção: Navegação por Teclado */}
        <section aria-labelledby="keyboard-nav-title">
          <Card>
            <CardHeader>
              <CardTitle id="keyboard-nav-title">
                Navegação por Teclado
              </CardTitle>
              <p className="text-sm text-gray-600">
                Use as setas ↑↓ para navegar, Enter para selecionar
              </p>
            </CardHeader>
            <CardContent>
              <nav role="navigation" aria-label="Menu principal">
                <ul 
                  className="space-y-2"
                  role="listbox"
                  aria-label="Itens do menu"
                  onKeyDown={(e) => handleKeyNavigation(e, {
                    onSelect: (index) => handleMenuItemClick(index, menuItems[index])
                  })}
                >
                  {menuItems.map((item, index) => (
                    <li key={item.id} role="option">
                      <button
                        ref={setItemRef(index)}
                        className={`w-full text-left p-3 rounded-lg border transition-colors ${
                          selectedItem === index 
                            ? 'bg-blue-50 border-blue-300 text-blue-900' 
                            : 'bg-white border-gray-200 hover:bg-gray-50'
                        } ${a11yClasses.focusVisible}`}
                        onClick={() => handleMenuItemClick(index, item)}
                        aria-selected={selectedItem === index}
                        aria-describedby={`menu-item-${item.id}-desc`}
                      >
                        <div className="flex items-center gap-3">
                          <span aria-hidden="true">{item.icon}</span>
                          <span>{item.label}</span>
                        </div>
                        <div id={`menu-item-${item.id}-desc`} className={a11yClasses.srOnly}>
                          Item {index + 1} de {menuItems.length}
                        </div>
                      </button>
                    </li>
                  ))}
                </ul>
              </nav>
            </CardContent>
          </Card>
        </section>

        {/* Seção: Botões Acessíveis */}
        <section aria-labelledby="buttons-title">
          <Card>
            <CardHeader>
              <CardTitle id="buttons-title">
                Botões com Acessibilidade
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Button
                  variant="primary"
                  ariaLabel="Salvar documento atual"
                  ariaDescribedBy="save-help"
                  onClick={() => announce('Documento salvo', 'assertive')}
                >
                  Salvar
                </Button>
                <div id="save-help" className="text-sm text-gray-600">
                  Salva o documento atual no servidor
                </div>

                <Button
                  variant="danger"
                  ariaLabel="Excluir item selecionado"
                  onClick={() => announce('Item excluído', 'assertive')}
                >
                  Excluir
                </Button>

                <Button
                  variant="outline"
                  ariaExpanded={showModal}
                  ariaControls="demo-modal"
                  onClick={() => setShowModal(true)}
                >
                  Abrir Modal
                </Button>
              </div>

              <div className="space-y-2">
                <Button
                  variant="secondary"
                  loading={false}
                  loadingText="Processando dados..."
                  fullWidth
                >
                  Botão Normal
                </Button>
                
                <Button
                  variant="secondary"
                  loading={true}
                  loadingText="Processando dados..."
                  fullWidth
                  ariaLabel="Processando dados, aguarde"
                >
                  Botão Carregando
                </Button>
              </div>
            </CardContent>
          </Card>
        </section>

        {/* Seção: Formulário Acessível */}
        <section aria-labelledby="form-title">
          <Card>
            <CardHeader>
              <CardTitle id="form-title">
                Formulário Acessível
              </CardTitle>
            </CardHeader>
            <CardContent>
              <form onSubmit={handleFormSubmit} noValidate>
                <div className="space-y-4">
                  <Input
                    label="Nome Completo"
                    value={inputValue}
                    onChange={(e) => setInputValue(e.target.value)}
                    placeholder="Digite seu nome completo"
                    required
                    aria-describedby="name-help name-error"
                    helperText="Campo obrigatório"
                    error={inputValue.length > 0 && inputValue.length < 2 ? 'Nome deve ter pelo menos 2 caracteres' : ''}
                    id="name-input"
                    name="fullName"
                    type="text"
                    autoComplete="name"
                  />
                  <div id="name-help" className="text-sm text-gray-600">
                    Informe seu nome completo para identificação
                  </div>
                  
                  <Button
                    type="submit"
                    variant="primary"
                    ariaDescribedBy="submit-help"
                  >
                    Enviar Formulário
                  </Button>
                  <div id="submit-help" className="text-sm text-gray-600">
                    Clique para enviar o formulário
                  </div>
                </div>
              </form>
            </CardContent>
          </Card>
        </section>

        {/* Seção: Teste de Contraste */}
        <section aria-labelledby="contrast-title">
          <Card>
            <CardHeader>
              <CardTitle id="contrast-title">
                Teste de Contraste de Cores
              </CardTitle>
              <p className="text-sm text-gray-600">
                Validação automática de contraste WCAG
              </p>
            </CardHeader>
            <CardContent>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                {colorTests.map((test, index) => {
                  const ratio = calculateContrastRatio(test.text, test.bg);
                  const isCompliant = isContrastCompliant(test.text, test.bg);
                  
                  return (
                    <div
                      key={index}
                      className="p-4 rounded-lg border"
                      style={{ backgroundColor: test.bg, color: test.text }}
                    >
                      <h4 className="font-semibold">{test.name}</h4>
                      <p className="text-sm mt-1">
                        Razão: {ratio.toFixed(2)}:1
                      </p>
                      <Badge 
                        variant={isCompliant ? 'success' : 'danger'}
                        size="sm"
                        className="mt-2"
                      >
                        {isCompliant ? 'WCAG AA ✓' : 'WCAG AA ✗'}
                      </Badge>
                    </div>
                  );
                })}
              </div>
            </CardContent>
          </Card>
        </section>

        {/* Seção: Anúncios para Leitores de Tela */}
        <section aria-labelledby="announcements-title">
          <Card>
            <CardHeader>
              <CardTitle id="announcements-title">
                Anúncios para Leitores de Tela
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Button
                  variant="outline"
                  onClick={() => announce('Esta é uma mensagem educada', 'polite')}
                >
                  Anúncio Educado
                </Button>
                
                <Button
                  variant="warning"
                  onClick={() => announce('Esta é uma mensagem assertiva', 'assertive')}
                >
                  Anúncio Assertivo
                </Button>
                
                <Button
                  variant="success"
                  onClick={() => announce('Operação concluída com sucesso', 'polite')}
                >
                  Sucesso
                </Button>
              </div>
              
              <div className="text-sm text-gray-600 bg-blue-50 p-3 rounded-lg">
                <strong>Dica:</strong> Use um leitor de tela (como NVDA, JAWS ou VoiceOver) 
                para ouvir os anúncios quando clicar nos botões acima.
              </div>
            </CardContent>
          </Card>
        </section>

        {/* Região de status ao vivo */}
        <div 
          aria-live="polite" 
          aria-atomic="true" 
          className={a11yClasses.srOnly}
          id="status-region"
        >
          {/* Anúncios aparecerão aqui */}
        </div>
      </main>

      {/* Modal Acessível */}
      {showModal && (
        <div 
          className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
          role="dialog"
          aria-modal="true"
          aria-labelledby="modal-title"
          aria-describedby="modal-description"
        >
          <div
            ref={modalRef}
            className="bg-white rounded-lg shadow-xl max-w-md w-full p-6"
            onKeyDown={handleKeyDown}
            id="demo-modal"
          >
            <div className="flex items-center justify-between mb-4">
              <h3 id="modal-title" className="text-lg font-semibold text-gray-900">
                Modal Acessível
              </h3>
              <Button
                variant="ghost"
                size="sm"
                onClick={() => setShowModal(false)}
                ariaLabel="Fechar modal"
              >
                ✕
              </Button>
            </div>
            
            <div id="modal-description" className="space-y-4">
              <p className="text-gray-600">
                Este modal demonstra as melhores práticas de acessibilidade:
              </p>
              <ul className="list-disc list-inside text-sm text-gray-600 space-y-1">
                <li>Foco capturado dentro do modal</li>
                <li>Escape para fechar</li>
                <li>ARIA labels apropriados</li>
                <li>Foco retorna ao elemento que abriu</li>
              </ul>
              
              <div className="flex gap-3 pt-4">
                <Button
                  variant="outline"
                  onClick={() => setShowModal(false)}
                >
                  Cancelar
                </Button>
                <Button
                  variant="primary"
                  onClick={() => {
                    announce('Ação confirmada', 'assertive');
                    setShowModal(false);
                  }}
                >
                  Confirmar
                </Button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default AccessibilityDemo;