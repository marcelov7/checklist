# Sistema de Checklist de Paradas - Node.js + React

## 📋 Sobre o Projeto

Sistema de checklist para paradas industriais desenvolvido com Node.js no backend e React no frontend. Este é uma versão moderna e escalável do sistema original em Laravel.

## 🏗️ Arquitetura

```
checklist-nodejs/
├── frontend/          # Aplicação React (Vite)
├── backend/           # API Node.js (Express)
└── README.md         # Este arquivo
```

## 🚀 Tecnologias

### Backend
- **Node.js** - Runtime JavaScript
- **Express.js** - Framework web
- **Prisma** - ORM para banco de dados
- **PostgreSQL/MySQL** - Banco de dados
- **JWT** - Autenticação
- **bcrypt** - Hash de senhas
- **cors** - CORS middleware

### Frontend
- **React 18** - Biblioteca UI
- **Vite** - Build tool
- **TypeScript** - Tipagem estática
- **React Router** - Roteamento
- **Axios** - Cliente HTTP
- **React Query** - Gerenciamento de estado servidor
- **Tailwind CSS** - Framework CSS
- **React Hook Form** - Formulários

### PWA Features
- **Service Worker** - Cache e offline
- **Web App Manifest** - Instalação
- **Push Notifications** - Notificações
- **Background Sync** - Sincronização offline

## 📦 Instalação

### Pré-requisitos
- Node.js 18+
- npm ou yarn
- PostgreSQL ou MySQL

### Backend
```bash
cd backend
npm install
cp .env.example .env
# Configure as variáveis de ambiente
npm run dev
```

### Frontend
```bash
cd frontend
npm install
npm run dev
```

## 🔧 Configuração

### Variáveis de Ambiente (Backend)
```env
DATABASE_URL="postgresql://user:password@localhost:5432/checklist"
JWT_SECRET="your-secret-key"
PORT=3001
CORS_ORIGIN="http://localhost:5173"
```

### Variáveis de Ambiente (Frontend)
```env
VITE_API_URL="http://localhost:3001/api"
```

## 📊 Modelos de Dados

### User (Usuário)
- id, name, email, password
- role (admin, user)
- timestamps

### Area
- id, name, description
- timestamps

### Parada
- id, name, area_id
- status (ativa, concluida, cancelada)
- timestamps

### Teste
- id, name, parada_id
- status (pendente, em_andamento, concluido)
- progress (0-100)
- timestamps

## 🛠️ Funcionalidades

- ✅ Autenticação JWT
- ✅ CRUD de Áreas
- ✅ CRUD de Paradas
- ✅ CRUD de Testes
- ✅ Dashboard com estatísticas
- ✅ Interface responsiva
- ✅ PWA (Progressive Web App)
- ✅ Modo offline
- ✅ Sincronização de dados
- ✅ Notificações push

## 🚀 Deploy

### Backend (Railway/Heroku)
```bash
npm run build
npm start
```

### Frontend (Vercel/Netlify)
```bash
npm run build
# Deploy da pasta dist/
```

## 👨‍💻 Desenvolvimento

### Scripts Úteis

**Backend:**
```bash
npm run dev          # Desenvolvimento
npm run build        # Build para produção
npm run start        # Produção
npm run db:migrate   # Executar migrações
npm run db:seed      # Popular banco
```

**Frontend:**
```bash
npm run dev          # Desenvolvimento
npm run build        # Build para produção
npm run preview      # Preview da build
npm run lint         # Linter
npm run type-check   # Verificação de tipos
```

## 📱 PWA Features

- **Instalável** - Pode ser instalado como app nativo
- **Offline First** - Funciona sem internet
- **Background Sync** - Sincroniza quando volta online
- **Push Notifications** - Notificações em tempo real
- **Responsive** - Funciona em mobile e desktop

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.