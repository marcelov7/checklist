#!/bin/bash

# Script de Deploy para Produção - Sistema Checklist
# Este script deve ser executado no servidor de produção

echo "🚀 Iniciando deploy do Sistema Checklist..."

# 1. Atualizar código do repositório
echo "📥 Atualizando código do repositório..."
git pull origin main

# 2. Instalar dependências do backend
echo "📦 Instalando dependências do backend..."
cd backend
npm install --production

# 3. Executar migrações do banco de dados
echo "🗄️ Executando migrações do banco de dados..."
npx prisma migrate deploy

# 4. Gerar cliente Prisma
echo "🔧 Gerando cliente Prisma..."
npx prisma generate

# 5. Instalar dependências do frontend
echo "📦 Instalando dependências do frontend..."
cd ../frontend
npm install

# 6. Build do frontend para produção
echo "🏗️ Fazendo build do frontend..."
npm run build

# 7. Reiniciar serviços (ajustar conforme seu ambiente)
echo "🔄 Reiniciando serviços..."
# pm2 restart checklist-backend
# pm2 restart checklist-frontend
# ou
# systemctl restart checklist-backend
# systemctl restart checklist-frontend

echo "✅ Deploy concluído com sucesso!"
echo "📋 Migrações aplicadas:"
echo "   - 20251029135823_init (tabelas iniciais)"
echo "   - 20251029165053_add_equipamentos_table (tabela equipamentos)"
echo "   - 20251029165129_make_area_id_optional (areaId opcional)"