#!/bin/bash

# Script de Migração do Banco - Sistema de Checklist
# Execute no servidor: bash migrate-database.sh

echo "=== MIGRAÇÃO DO BANCO DE DADOS ==="

# Configurar PATH
export PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:$PATH"

# Navegar para o diretório do projeto
PROJECT_DIR="/home/devaxis-checklist/htdocs/checklist.devaxis.com.br"
cd $PROJECT_DIR

echo "📍 Diretório atual: $(pwd)"
echo ""

# Verificar se existe o arquivo de banco
if [ ! -f "database/database.sqlite" ]; then
    echo "📁 Criando arquivo do banco SQLite..."
    touch database/database.sqlite
    chmod 664 database/database.sqlite
else
    echo "✅ Arquivo do banco SQLite já existe"
fi

echo ""
echo "📊 Status atual das migrações:"
php artisan migrate:status

echo ""
echo "🗄️ Executando migrações..."
php artisan migrate --force

echo ""
echo "🌱 Executando seeders (dados iniciais)..."
php artisan db:seed --force --class=DadosExemploSeeder

echo ""
echo "📋 Verificando tabelas criadas:"
php artisan tinker --execute="
\$tables = DB::select('SELECT name FROM sqlite_master WHERE type=\"table\"');
foreach(\$tables as \$table) {
    echo \"- \" . \$table->name . \"\\n\";
}
"

echo ""
echo "👥 Verificando usuários cadastrados:"
php artisan tinker --execute="
\$users = DB::table('users')->count();
echo \"Total de usuários: \" . \$users . \"\\n\";
if(\$users > 0) {
    \$user = DB::table('users')->first();
    echo \"Primeiro usuário: \" . \$user->name . \" (\" . \$user->email . \")\\n\";
}
"

echo ""
echo "🏭 Verificando áreas cadastradas:"
php artisan tinker --execute="
\$areas = DB::table('areas')->count();
echo \"Total de áreas: \" . \$areas . \"\\n\";
"

echo ""
echo "⚙️ Verificando equipamentos cadastrados:"
php artisan tinker --execute="
\$equipamentos = DB::table('equipamentos')->count();
echo \"Total de equipamentos: \" . \$equipamentos . \"\\n\";
"

echo ""
echo "✅ MIGRAÇÃO CONCLUÍDA!"
echo "🌐 Acesse: https://checklist.devaxis.com.br"