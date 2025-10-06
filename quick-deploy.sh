#!/bin/bash

# Script de Deploy Rápido - Sistema de Checklist
echo "=== DEPLOY E MIGRAÇÃO DO SISTEMA DE CHECKLIST ==="

# Definir diretório do projeto
PROJECT_DIR="/home/devaxis-checklist/htdocs/checklist.devaxis.com.br"
cd "$PROJECT_DIR"

echo "📍 Diretório: $(pwd)"
echo ""

# Copiar .env de produção
echo "🔧 Configurando arquivo .env..."
if [ -f ".env.production" ]; then
    cp .env.production .env
    echo "✅ Arquivo .env configurado para produção"
elif [ -f ".env.example" ]; then
    cp .env.example .env
    
    # Ajustar configurações no .env
    sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
    sed -i 's/APP_ENV=local/APP_ENV=production/' .env
    sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
    sed -i 's|APP_URL=http://localhost|APP_URL=https://checklist.devaxis.com.br|' .env
    
    # Adicionar configurações MySQL se não existirem
    if ! grep -q "DB_HOST=" .env; then
        echo "" >> .env
        echo "DB_HOST=127.0.0.1" >> .env
        echo "DB_PORT=3306" >> .env
        echo "DB_DATABASE=checkdb" >> .env
        echo "DB_USERNAME=checkuser" >> .env
        echo "DB_PASSWORD=M@rcelo1809@3033" >> .env
    fi
    
    echo "✅ Arquivo .env criado e configurado"
else
    echo "❌ Arquivo .env.example não encontrado!"
    exit 1
fi

echo ""

# Gerar chave da aplicação se necessário
echo "🔑 Verificando chave da aplicação..."
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
    echo "✅ Chave da aplicação gerada"
else
    echo "✅ Chave da aplicação já existe"
fi

echo ""

# Limpar cache
echo "🧹 Limpando cache do Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo ""

# Testar conexão com MySQL
echo "🔌 Testando conexão MySQL..."
php artisan tinker --execute="
try {
    \$pdo = DB::connection()->getPdo();
    echo '✅ Conexão MySQL estabelecida!' . PHP_EOL;
    echo 'Servidor: ' . \$pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Erro MySQL: ' . \$e->getMessage() . PHP_EOL;
    exit(1);
}
"

if [ $? -ne 0 ]; then
    echo "❌ Falha na conexão MySQL. Verifique as credenciais."
    exit 1
fi

echo ""

# Executar migrações
echo "🗄️ Executando migrações..."
php artisan migrate:fresh --force

if [ $? -eq 0 ]; then
    echo "✅ Migrações executadas com sucesso!"
else
    echo "❌ Erro nas migrações!"
    exit 1
fi

echo ""

# Executar seeders
echo "🌱 Populando banco com dados iniciais..."
php artisan db:seed --force --class=DadosExemploSeeder

if [ $? -eq 0 ]; then
    echo "✅ Dados iniciais criados!"
else
    echo "⚠️ Aviso: Erro ao criar dados iniciais (pode já existir)"
fi

echo ""

# Verificar dados criados
echo "📊 Verificando dados no banco:"
php artisan tinker --execute="
try {
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
        echo PHP_EOL . 'Login padrão:' . PHP_EOL;
        echo 'Email: ' . \$user->email . PHP_EOL;
        echo 'Senha: password' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '❌ Erro: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""

# Ajustar permissões
echo "🔐 Ajustando permissões..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo ""
echo "🎉 DEPLOY CONCLUÍDO COM SUCESSO!"
echo "🌐 Sistema disponível em: https://checklist.devaxis.com.br"
echo ""
echo "📋 Para acessar o sistema:"
echo "1. Abra: https://checklist.devaxis.com.br"
echo "2. Use o login exibido acima"
echo ""