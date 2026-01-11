# Design System - Sistema de Checklist

## 📋 Visão Geral

Este documento descreve o design system implementado para o Sistema de Checklist, baseado em **Tailwind CSS** e componentes React reutilizáveis. O sistema garante consistência visual, acessibilidade e facilita a manutenção do código.

## 🎨 Tokens de Design

### Cores

Nossa paleta de cores está centralizada no arquivo `src/styles/design-system.ts`:

#### Cores Primárias (Azul)
- **Primary 500**: `#3b82f6` - Cor principal do sistema
- **Primary 600**: `#2563eb` - Hover states
- **Primary 700**: `#1d4ed8` - Active states

#### Cores de Estado
- **Success**: Verde (`#22c55e`) - Ações bem-sucedidas
- **Warning**: Amarelo (`#f59e0b`) - Avisos e alertas
- **Danger**: Vermelho (`#ef4444`) - Erros e ações destrutivas
- **Secondary**: Cinza (`#6b7280`) - Elementos secundários

### Tipografia

```css
Font Family: Inter (sistema padrão)
Tamanhos: xs (12px), sm (14px), base (16px), lg (18px), xl (20px)
Pesos: normal (400), medium (500), semibold (600), bold (700)
```

### Espaçamentos

Baseado em múltiplos de 4px:
- **1**: 4px
- **2**: 8px  
- **3**: 12px
- **4**: 16px
- **6**: 24px
- **8**: 32px

### Breakpoints Responsivos

```css
xs: 475px    /* Smartphones pequenos */
sm: 640px    /* Smartphones */
md: 768px    /* Tablets */
lg: 1024px   /* Laptops */
xl: 1280px   /* Desktops */
2xl: 1536px  /* Telas grandes */
```

## 🧩 Componentes

### Button

Componente de botão com múltiplas variantes e tamanhos.

```jsx
import Button from './components/ui/Button';

// Variantes
<Button variant="primary">Primário</Button>
<Button variant="secondary">Secundário</Button>
<Button variant="success">Sucesso</Button>
<Button variant="warning">Aviso</Button>
<Button variant="danger">Perigo</Button>
<Button variant="outline">Contorno</Button>
<Button variant="ghost">Fantasma</Button>

// Tamanhos
<Button size="sm">Pequeno</Button>
<Button size="md">Médio</Button>
<Button size="lg">Grande</Button>

// Estados
<Button loading>Carregando...</Button>
<Button disabled>Desabilitado</Button>
<Button fullWidth>Largura Total</Button>

// Com ícones
<Button leftIcon={<Plus />}>Adicionar</Button>
<Button rightIcon={<ArrowRight />}>Continuar</Button>
```

### Card

Componente de cartão para agrupar conteúdo relacionado.

```jsx
import Card, { CardHeader, CardTitle, CardContent, CardFooter } from './components/ui/Card';

<Card variant="elevated" size="default">
  <CardHeader>
    <CardTitle>Título do Card</CardTitle>
  </CardHeader>
  <CardContent>
    Conteúdo do card aqui...
  </CardContent>
  <CardFooter>
    <Button>Ação</Button>
  </CardFooter>
</Card>

// Variantes
<Card variant="default">Padrão</Card>
<Card variant="primary">Primário</Card>
<Card variant="success">Sucesso</Card>
<Card variant="warning">Aviso</Card>
<Card variant="error">Erro</Card>

// Tamanhos
<Card size="compact">Compacto</Card>
<Card size="default">Padrão</Card>
<Card size="elevated">Elevado</Card>
```

### Input

Componente de entrada de dados com suporte a ícones e estados de erro.

```jsx
import Input from './components/ui/Input';

<Input
  label="Nome"
  placeholder="Digite seu nome"
  value={value}
  onChange={handleChange}
  size="md"
  variant="outline"
/>

// Com ícones
<Input
  label="Email"
  leftIcon={<Mail />}
  rightIcon={<Check />}
  type="email"
/>

// Estados de erro
<Input
  label="Senha"
  type="password"
  error="Senha deve ter pelo menos 8 caracteres"
  helperText="Use letras, números e símbolos"
/>

// Variantes
<Input variant="default">Padrão</Input>
<Input variant="filled">Preenchido</Input>
<Input variant="outline">Contorno</Input>
```

### Badge

Componente para exibir status, categorias ou informações destacadas.

```jsx
import Badge from './components/ui/Badge';

// Variantes
<Badge variant="default">Padrão</Badge>
<Badge variant="success">Ativo</Badge>
<Badge variant="warning">Pendente</Badge>
<Badge variant="danger">Erro</Badge>
<Badge variant="secondary">Inativo</Badge>
<Badge variant="outline">Contorno</Badge>

// Tamanhos
<Badge size="sm">Pequeno</Badge>
<Badge size="md">Médio</Badge>
<Badge size="lg">Grande</Badge>
```

## 📱 Responsividade

### Estratégia Mobile-First

Todos os componentes seguem a estratégia mobile-first:

```jsx
// Classes responsivas
<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
  {/* Conteúdo */}
</div>

// Espaçamentos responsivos
<div className="p-4 md:p-6 lg:p-8">
  {/* Conteúdo */}
</div>

// Tipografia responsiva
<h1 className="text-2xl md:text-3xl lg:text-4xl">
  Título Responsivo
</h1>
```

### Breakpoints Customizados

```css
/* Dispositivos específicos */
.mobile:max-767px     /* Apenas mobile */
.tablet:768px-1023px  /* Apenas tablet */
.desktop:min-1024px   /* Desktop e acima */

/* Interação */
.touch:hover-none     /* Dispositivos touch */
.no-touch:hover-hover /* Dispositivos com mouse */
```

## 🎯 Boas Práticas

### 1. Uso de Classes Utilitárias

```jsx
// ✅ Bom - Classes semânticas e consistentes
<div className="bg-primary-500 text-white p-4 rounded-lg shadow-md">

// ❌ Evitar - Valores arbitrários
<div className="bg-[#3b82f6] text-[#ffffff] p-[16px]">
```

### 2. Composição de Componentes

```jsx
// ✅ Bom - Componentes compostos
<Card>
  <CardHeader>
    <CardTitle>Título</CardTitle>
  </CardHeader>
  <CardContent>
    <Input label="Campo" />
    <Button variant="primary">Salvar</Button>
  </CardContent>
</Card>
```

### 3. Estados Consistentes

```jsx
// ✅ Bom - Estados padronizados
<Button loading={isLoading} disabled={!isValid}>
  {isLoading ? 'Salvando...' : 'Salvar'}
</Button>
```

### 4. Acessibilidade

```jsx
// ✅ Bom - ARIA labels e semântica
<Button 
  aria-label="Adicionar novo item"
  aria-describedby="help-text"
>
  <Plus aria-hidden="true" />
  Adicionar
</Button>
```

## 🔧 Configuração

### Tailwind CSS

O arquivo `tailwind.config.js` está configurado com:

- **Cores customizadas** do design system
- **Breakpoints responsivos** otimizados
- **Animações** e transições suaves
- **Sombras** e efeitos visuais

### Importações

```jsx
// Componentes individuais
import Button from './components/ui/Button';
import Card from './components/ui/Card';

// Ou via index (recomendado)
import { Button, Card, Input, Badge } from './components/ui';
```

## 🧪 Teste de Responsividade

Acesse `/test` para visualizar todos os componentes em diferentes tamanhos de tela e testar a responsividade do sistema.

## 📚 Recursos Adicionais

- **Design Tokens**: `src/styles/design-system.ts`
- **Componentes**: `src/components/ui/`
- **Configuração Tailwind**: `tailwind.config.js`
- **Teste Visual**: `src/components/ResponsiveTest.jsx`

---

## 🚀 Próximos Passos

1. **Tema Escuro**: Implementar suporte a dark mode
2. **Mais Componentes**: Modal, Dropdown, Tooltip
3. **Animações**: Micro-interações e transições
4. **Testes**: Testes automatizados dos componentes
5. **Storybook**: Documentação interativa dos componentes

---

*Este design system foi criado para garantir consistência, acessibilidade e facilidade de manutenção em todo o Sistema de Checklist.*