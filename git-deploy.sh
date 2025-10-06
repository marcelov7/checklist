#!/bin/bash

# Script de Deploy via Git - Sistema de Checklist Devaxis
# Execute no servidor: ./git-deploy.sh

echo "=== DEPLOY VIA GIT - SISTEMA DE CHECKLIST ==="
echo "Data: $(date)"
echo "Localização: /home/devaxis-checklist/htdocs/checklist.devaxis.com.br/"
echo "=============================================="

# Definir diretórios
PROJECT_DIR="/home/devaxis-checklist/htdocs/checklist.devaxis.com.br"
PUBLIC_DIR="$PROJECT_DIR/public"

# 1. Navegar para o diretório do projeto (pai do public)
echo "📁 Navegando para diretório do projeto..."
cd $PROJECT_DIR

# 2. Verificar se já existe um repositório Git
if [ ! -d ".git" ]; then
    echo "🔄 Clonando repositório pela primeira vez..."
    # Se não existe, fazer clone inicial
    git clone https://github.com/marcelov7/checklist.git .
else
    echo "🔄 Atualizando repositório existente..."
    # Se existe, fazer pull das últimas alterações
    git fetch origin
    git reset --hard origin/main
    git pull origin main
fi

# 3. Verificar se o composer está disponível
echo "📦 Verificando dependências..."
if command -v composer &> /dev/null; then
    echo "📦 Instalando dependências do Composer..."
    composer install --optimize-autoloader --no-dev --no-interaction
else
    echo "⚠️  Composer não encontrado, verificando se vendor/ existe..."
    if [ ! -d "vendor" ]; then
        echo "❌ ERRO: Dependências não encontradas e Composer não disponível"
        echo "   Você precisa fazer upload manual do vendor/ ou instalar Composer"
    fi
fi

# 4. Configurar arquivo .env para produção
echo "⚙️  Configurando ambiente de produção..."
if [ -f ".env.production" ]; then
    cp .env.production .env
    echo "✅ Arquivo .env configurado a partir de .env.production"
else
    echo "⚠️  Criando .env básico para produção..."
    cat > .env << 'EOL'
APP_NAME="Sistema de Checklist"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://checklist.devaxis.com.br

APP_LOCALE=pt
APP_FALLBACK_LOCALE=pt

DB_CONNECTION=sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=480
SESSION_DOMAIN=.devaxis.com.br

LOG_CHANNEL=stack
LOG_LEVEL=error

CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

MAIL_FROM_ADDRESS="noreply@devaxis.com.br"
MAIL_FROM_NAME="${APP_NAME}"
EOL
fi

# 5. Gerar chave da aplicação se não existir
echo "🔑 Configurando chave da aplicação..."
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
    echo "✅ Nova chave APP_KEY gerada"
else
    echo "✅ Chave APP_KEY já existe"
fi

# 6. Configurar permissões básicas
echo "🔒 Configurando permissões..."
chmod -R 755 $PROJECT_DIR
chmod -R 775 $PROJECT_DIR/storage
chmod -R 775 $PROJECT_DIR/bootstrap/cache

# 7. Criar diretórios necessários se não existirem
echo "📁 Criando diretórios necessários..."
mkdir -p storage/logs
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p bootstrap/cache

# 8. Executar migrações
echo "🗄️  Executando migrações do banco de dados..."
php artisan migrate --force

# 9. Executar seeders para dados iniciais
echo "🌱 Verificando seeders..."
php artisan db:seed --force --class=DadosExemploSeeder

# 10. Criar link simbólico do storage
echo "🔗 Criando link simbólico do storage..."
php artisan storage:link

# 11. Otimizar aplicação para produção
echo "⚡ Otimizando aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 12. Configurar permissões finais
echo "🔒 Configurando permissões finais..."
chown -R devaxis-checklist:devaxis-checklist $PROJECT_DIR/storage
chown -R devaxis-checklist:devaxis-checklist $PROJECT_DIR/bootstrap/cache
chmod -R 775 $PROJECT_DIR/storage
chmod -R 775 $PROJECT_DIR/bootstrap/cache

# 13. Verificar se a estrutura está correta
echo "🔍 Verificando estrutura final..."
if [ -f "public/index.php" ]; then
    echo "✅ Estrutura Laravel OK - public/index.php encontrado"
else
    echo "❌ ERRO: public/index.php não encontrado!"
fi

if [ -f "artisan" ]; then
    echo "✅ Artisan encontrado"
else
    echo "❌ ERRO: artisan não encontrado!"
fi

echo "=============================================="
echo "✅ DEPLOY CONCLUÍDO COM SUCESSO!"
echo "🌐 Sistema disponível em: https://checklist.devaxis.com.br"
echo "📁 Diretório: $PROJECT_DIR"
echo "📁 Document Root deve apontar para: $PROJECT_DIR/public"
echo "=============================================="

# 14. Mostrar informações do sistema
echo ""
echo "📊 INFORMAÇÕES DO SISTEMA:"
echo "PHP Version: $(php -v | head -n 1)"
echo "Laravel Version: $(php artisan --version 2>/dev/null || echo 'Não detectado')"
echo "Espaço em disco:"
df -h $PROJECT_DIR
echo "=============================================="