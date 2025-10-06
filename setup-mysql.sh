#!/bin/bash

# Script para Configurar MySQL no Laravel
# Execute no servidor: bash setup-mysql.sh

echo "=== CONFIGURAÇÃO MYSQL PARA LARAVEL ==="

# Navegar para o diretório do projeto
PROJECT_DIR="/home/devaxis-checklist/htdocs/checklist.devaxis.com.br"
cd $PROJECT_DIR

echo "📍 Diretório atual: $(pwd)"
echo ""

# Dados de conexão MySQL
DB_HOST="127.0.0.1"
DB_PORT="3306"
DB_USER="checkuser"
DB_PASS="M@rcelo1809@3033"
DB_NAME="checkdb"

echo "🔧 Configurando arquivo .env para MySQL..."

# Backup do .env atual
if [ -f ".env" ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    echo "✅ Backup do .env criado"
fi

# Criar/atualizar .env
cat > .env << EOF
APP_NAME="Sistema de Checklist"
APP_ENV=production
APP_KEY=base64:$(php artisan key:generate --show 2>/dev/null || echo "SomeRandomKey123456789012345678901234567890=")
APP_DEBUG=false
APP_URL=https://checklist.devaxis.com.br

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Configuração MySQL
DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASS

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="\${APP_NAME}"
EOF

echo "✅ Arquivo .env configurado para MySQL"
echo ""

# Testar conexão
echo "🔌 Testando conexão com MySQL..."
php artisan tinker --execute="
try {
    DB::purge();
    \$connection = DB::connection();
    echo 'Conexão: ' . config('database.default') . PHP_EOL;
    echo 'Host: ' . config('database.connections.mysql.host') . PHP_EOL;
    echo 'Database: ' . config('database.connections.mysql.database') . PHP_EOL;
    \$pdo = \$connection->getPdo();
    echo '✅ Conexão MySQL estabelecida com sucesso!' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Erro na conexão MySQL: ' . \$e->getMessage() . PHP_EOL;
    exit(1);
}
"

if [ $? -ne 0 ]; then
    echo "❌ Falha na conexão. Verifique as credenciais do MySQL."
    exit 1
fi

echo ""

# Limpar cache de configuração
echo "🧹 Limpando cache do Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo ""

# Executar migrações
echo "🗄️ Executando migrações no MySQL..."
php artisan migrate:fresh --force

if [ $? -eq 0 ]; then
    echo "✅ Migrações executadas com sucesso!"
else
    echo "❌ Erro ao executar migrações!"
    exit 1
fi

echo ""

# Executar seeders
echo "🌱 Executando seeders..."
php artisan db:seed --force --class=DadosExemploSeeder

if [ $? -eq 0 ]; then
    echo "✅ Seeders executados com sucesso!"
else
    echo "❌ Erro ao executar seeders!"
fi

echo ""

# Verificar dados criados
echo "📊 Verificando dados criados:"

php artisan tinker --execute="
\$users = DB::table('users')->count();
\$areas = DB::table('areas')->count();
\$equipamentos = DB::table('equipamentos')->count();
\$paradas = DB::table('paradas')->count();
\$testes = DB::table('testes')->count();

echo 'Usuários: ' . \$users . PHP_EOL;
echo 'Áreas: ' . \$areas . PHP_EOL;
echo 'Equipamentos: ' . \$equipamentos . PHP_EOL;
echo 'Paradas: ' . \$paradas . PHP_EOL;
echo 'Testes: ' . \$testes . PHP_EOL;

if(\$users > 0) {
    \$user = DB::table('users')->first();
    echo 'Primeiro usuário: ' . \$user->name . ' (' . \$user->email . ')' . PHP_EOL;
}
"

echo ""
echo "✅ CONFIGURAÇÃO MYSQL CONCLUÍDA!"
echo "🌐 Sistema disponível em: https://checklist.devaxis.com.br"
echo ""
echo "📋 Dados de acesso padrão:"
echo "Email: test@example.com"
echo "Senha: password"
echo ""