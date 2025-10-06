#!/bin/bash

# Script de Deploy - Sistema de Checklist Devaxis
# Executa no servidor após o upload dos arquivos

echo "=== INICIANDO DEPLOY DO SISTEMA DE CHECKLIST ==="
echo "Data: $(date)"
echo "=============================================="

# 1. Configurar permissões básicas
echo "📁 Configurando permissões..."
chmod -R 755 /home/devaxis-checklist/public_html
chmod -R 775 /home/devaxis-checklist/public_html/storage
chmod -R 775 /home/devaxis-checklist/public_html/bootstrap/cache

# 2. Configurar arquivo .env para produção
echo "⚙️  Configurando ambiente de produção..."
cp /home/devaxis-checklist/public_html/.env.production /home/devaxis-checklist/public_html/.env

# 3. Instalar dependências (se composer estiver disponível)
echo "📦 Instalando dependências..."
cd /home/devaxis-checklist/public_html
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev
else
    echo "⚠️  Composer não encontrado, pulando instalação de dependências"
fi

# 4. Gerar chave da aplicação
echo "🔑 Configurando chave da aplicação..."
php artisan key:generate --force

# 5. Executar migrações
echo "🗄️  Executando migrações do banco de dados..."
php artisan migrate --force

# 6. Executar seeders (se necessário)
echo "🌱 Executando seeders..."
php artisan db:seed --force

# 7. Criar link simbólico do storage
echo "🔗 Criando link simbólico do storage..."
php artisan storage:link

# 8. Otimizar aplicação para produção
echo "⚡ Otimizando aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Limpar caches antigos
echo "🧹 Limpando caches desnecessários..."
php artisan cache:clear

# 10. Configurar permissões finais
echo "🔒 Configurando permissões finais..."
chmod -R 755 /home/devaxis-checklist/public_html
chmod -R 775 /home/devaxis-checklist/public_html/storage
chmod -R 775 /home/devaxis-checklist/public_html/bootstrap/cache
chown -R devaxis-checklist:devaxis-checklist /home/devaxis-checklist/public_html/storage
chown -R devaxis-checklist:devaxis-checklist /home/devaxis-checklist/public_html/bootstrap/cache

echo "✅ DEPLOY CONCLUÍDO COM SUCESSO!"
echo "🌐 Sistema disponível em: https://checklist.devaxis.com.br"
echo "=============================================="