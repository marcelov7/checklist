#!/bin/bash

# Script de Debug da Migração - Sistema de Checklist
# Execute no servidor: bash debug-migration.sh

echo "=== DEBUG DA MIGRAÇÃO DO BANCO DE DADOS ==="

# Configurar PATH
export PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:$PATH"

# Navegar para o diretório do projeto
PROJECT_DIR="/home/devaxis-checklist/htdocs/checklist.devaxis.com.br"
cd $PROJECT_DIR

echo "📍 Diretório atual: $(pwd)"
echo ""

# Verificar se o Laravel está funcionando
echo "🔍 Verificando instalação do Laravel..."
php artisan --version
echo ""

# Verificar arquivo .env
echo "📄 Verificando arquivo .env..."
if [ -f ".env" ]; then
    echo "✅ Arquivo .env existe"
    echo "🔧 Configurações do banco no .env:"
    grep -E "^DB_" .env || echo "❌ Nenhuma configuração DB_ encontrada no .env"
else
    echo "❌ Arquivo .env não encontrado!"
    echo "📋 Criando .env baseado no .env.example..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "✅ Arquivo .env criado"
    else
        echo "❌ Arquivo .env.example também não existe!"
    fi
fi

echo ""

# Verificar se existe o arquivo de banco
echo "🗄️ Verificando banco de dados..."
if [ -f "database/database.sqlite" ]; then
    echo "✅ Arquivo do banco SQLite existe"
    echo "📊 Tamanho do arquivo: $(du -h database/database.sqlite)"
    echo "🔐 Permissões: $(ls -la database/database.sqlite)"
else
    echo "❌ Arquivo do banco SQLite não existe!"
    echo "📁 Criando arquivo do banco SQLite..."
    touch database/database.sqlite
    chmod 664 database/database.sqlite
    echo "✅ Arquivo criado"
fi

echo ""

# Verificar configuração do banco no Laravel
echo "🔧 Testando conexão com o banco..."
php artisan tinker --execute="
try {
    \$connection = DB::connection();
    echo 'Conexão: ' . config('database.default') . PHP_EOL;
    echo 'Database: ' . config('database.connections.' . config('database.default') . '.database') . PHP_EOL;
    \$pdo = \$connection->getPdo();
    echo '✅ Conexão com banco estabelecida com sucesso!' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Erro na conexão: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""

# Verificar status das migrações
echo "📊 Status das migrações:"
php artisan migrate:status 2>&1

echo ""

# Tentar executar as migrações
echo "🔄 Tentando executar migrações..."
php artisan migrate --force --verbose 2>&1

echo ""

# Verificar se as tabelas foram criadas
echo "📋 Verificando tabelas no banco:"
php artisan tinker --execute="
try {
    \$tables = DB::select('SELECT name FROM sqlite_master WHERE type=\"table\" ORDER BY name');
    if (count(\$tables) > 0) {
        echo 'Tabelas encontradas:' . PHP_EOL;
        foreach(\$tables as \$table) {
            echo '- ' . \$table->name . PHP_EOL;
        }
    } else {
        echo '❌ Nenhuma tabela encontrada no banco!' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '❌ Erro ao consultar tabelas: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""

# Verificar se existem dados nas tabelas principais
echo "📊 Verificando dados nas tabelas:"

echo "👥 Usuários:"
php artisan tinker --execute="
try {
    \$users = DB::table('users')->count();
    echo 'Total de usuários: ' . \$users . PHP_EOL;
    if(\$users > 0) {
        \$user = DB::table('users')->first();
        echo 'Primeiro usuário: ' . \$user->name . ' (' . \$user->email . ')' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '❌ Erro ao verificar usuários: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🏭 Áreas:"
php artisan tinker --execute="
try {
    \$areas = DB::table('areas')->count();
    echo 'Total de áreas: ' . \$areas . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Erro ao verificar áreas: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "⚙️ Equipamentos:"
php artisan tinker --execute="
try {
    \$equipamentos = DB::table('equipamentos')->count();
    echo 'Total de equipamentos: ' . \$equipamentos . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Erro ao verificar equipamentos: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🔧 Paradas:"
php artisan tinker --execute="
try {
    \$paradas = DB::table('paradas')->count();
    echo 'Total de paradas: ' . \$paradas . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Erro ao verificar paradas: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""

# Tentar executar o seeder se não há dados
echo "🌱 Verificando se precisa executar seeders..."
php artisan tinker --execute="
try {
    \$users = DB::table('users')->count();
    \$areas = DB::table('areas')->count();
    if(\$users == 0 || \$areas == 0) {
        echo '📝 Banco vazio, executando seeders...' . PHP_EOL;
    } else {
        echo '✅ Banco já possui dados!' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '❌ Erro ao verificar dados: ' . \$e->getMessage() . PHP_EOL;
}
"

# Se necessário, executar seeders
php artisan db:seed --force --class=DadosExemploSeeder 2>&1

echo ""
echo "🔍 DEBUG CONCLUÍDO!"
echo ""
echo "📝 Se houver erros acima, verifique:"
echo "1. Permissões do arquivo database/database.sqlite"
echo "2. Configurações no arquivo .env"
echo "3. Se o PHP tem a extensão SQLite habilitada"
echo "4. Se há espaço em disco suficiente"
echo ""