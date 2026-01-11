# 🚀 Guia de Deploy - Sistema Checklist

## 📋 Pré-requisitos

- Node.js 18+ instalado
- Banco de dados configurado (SQLite para desenvolvimento, PostgreSQL/MySQL para produção)
- Git configurado
- PM2 ou similar para gerenciamento de processos (opcional)

## 🗄️ Migrações do Banco de Dados

O sistema possui as seguintes migrações que serão aplicadas automaticamente:

### 1. `20251029135823_init`
- Cria as tabelas iniciais do sistema
- Tabelas: `users`, `areas`, `checklists`, `checklistItems`, `checklistExecution`

### 2. `20251029165053_add_equipamentos_table`
- Adiciona a tabela `equipamentos`
- Campos: id, numeracao, nome, tipo, fabricante, modelo, numeroSerie, status, prioridade, observacoes
- Relacionamento com `areas`

### 3. `20251029165129_make_area_id_optional`
- Torna o campo `areaId` opcional na tabela `equipamentos`
- Permite equipamentos sem área definida

## 🔧 Deploy Manual

### 1. Preparar o ambiente
```bash
# Clonar o repositório
git clone <url-do-repositorio>
cd checklist-nodejs

# Configurar variáveis de ambiente
cp backend/.env.example backend/.env
# Editar backend/.env com as configurações de produção
```

### 2. Backend
```bash
cd backend

# Instalar dependências
npm install --production

# Executar migrações (IMPORTANTE!)
npx prisma migrate deploy

# Gerar cliente Prisma
npx prisma generate

# Iniciar servidor
npm start
```

### 3. Frontend
```bash
cd frontend

# Instalar dependências
npm install

# Build para produção
npm run build

# Servir arquivos estáticos (com nginx, apache, etc.)
```

## 🤖 Deploy Automatizado

Execute o script de deploy:

```bash
chmod +x deploy-production.sh
./deploy-production.sh
```

## ⚠️ Importante para Produção

### Banco de Dados
- **NUNCA** use `prisma migrate reset` em produção
- Use sempre `prisma migrate deploy` para aplicar migrações
- Faça backup do banco antes de aplicar migrações

### Variáveis de Ambiente
Certifique-se de configurar no arquivo `.env`:

```env
# Banco de dados
DATABASE_URL="postgresql://user:password@localhost:5432/checklist"

# JWT
JWT_SECRET="sua-chave-secreta-super-segura"

# Ambiente
NODE_ENV="production"

# Porta
PORT=3000
```

### Verificação Pós-Deploy

1. **Verificar migrações aplicadas:**
```bash
npx prisma migrate status
```

2. **Verificar tabelas criadas:**
```bash
npx prisma studio
```

3. **Testar endpoints:**
```bash
curl http://localhost:3000/api/health
```

## 🔍 Troubleshooting

### Erro de Migração
Se houver erro nas migrações:

```bash
# Verificar status
npx prisma migrate status

# Ver diferenças
npx prisma db diff

# Aplicar migrações pendentes
npx prisma migrate deploy
```

### Erro de Permissão (Windows)
Se encontrar erros EPERM:
- Execute como administrador
- Feche o Prisma Studio se estiver aberto
- Pare o servidor backend temporariamente

## 📊 Monitoramento

Após o deploy, monitore:
- Logs do servidor backend
- Conexões com o banco de dados
- Performance das consultas
- Uso de memória e CPU

## 🔄 Atualizações Futuras

Para atualizações que incluam novas migrações:

1. Fazer backup do banco
2. Executar `git pull`
3. Executar `npx prisma migrate deploy`
4. Reiniciar serviços

---

**Nota:** Este sistema foi desenvolvido e testado localmente. Certifique-se de testar em ambiente de staging antes do deploy em produção.