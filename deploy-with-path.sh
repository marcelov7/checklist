#!/bin/bash

# Script de Deploy com PATH Corrigido - Sistema de Checklist
# Execute no servidor: ./deploy-with-path.sh

echo "=== CONFIGURANDO PATH E EXECUTANDO DEPLOY ==="

# Configurar PATH correto para incluir diretórios padrão
export PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:$PATH"

echo "PATH configurado: $PATH"
echo ""

# Verificar se os comandos estão disponíveis agora
echo "🔍 Verificando comandos disponíveis..."
which git || echo "❌ Git não encontrado"
which php || echo "❌ PHP não encontrado" 
which chmod || echo "❌ chmod não encontrado"
echo ""

# Executar o script principal de deploy
echo "▶️  Executando script principal de deploy..."
chmod +x ./git-deploy.sh
./git-deploy.sh