#!/bin/bash

# Script de Conexão e Verificação MySQL
# Execute no servidor: bash check-mysql.sh

echo "=== VERIFICAÇÃO DO BANCO MYSQL ==="

# Dados de conexão
DB_HOST="127.0.0.1"
DB_PORT="3306"
DB_USER="checkuser"
DB_PASS="M@rcelo1809@3033"
DB_NAME="checkdb"

echo "🔌 Testando conexão com MySQL..."
echo "Host: $DB_HOST:$DB_PORT"
echo "Usuário: $DB_USER"
echo "Banco: $DB_NAME"
echo ""

# Testar conexão
mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" -e "SELECT 'Conexão estabelecida com sucesso!' as status;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Conexão MySQL funcionando!"
    echo ""
    
    # Verificar se o banco existe
    echo "🗄️ Verificando banco de dados '$DB_NAME'..."
    mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" -e "USE $DB_NAME; SELECT 'Banco existe!' as status;" 2>/dev/null
    
    if [ $? -eq 0 ]; then
        echo "✅ Banco '$DB_NAME' existe e está acessível!"
        
        # Listar tabelas
        echo ""
        echo "📋 Tabelas no banco:"
        mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null
        
        # Verificar se há dados
        echo ""
        echo "📊 Verificando dados nas tabelas principais:"
        
        # Usuários
        echo -n "👥 Usuários: "
        mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SELECT COUNT(*) FROM users;" 2>/dev/null | tail -n 1
        
        # Áreas
        echo -n "🏭 Áreas: "
        mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SELECT COUNT(*) FROM areas;" 2>/dev/null | tail -n 1
        
        # Equipamentos
        echo -n "⚙️ Equipamentos: "
        mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SELECT COUNT(*) FROM equipamentos;" 2>/dev/null | tail -n 1
        
        # Paradas
        echo -n "🔧 Paradas: "
        mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SELECT COUNT(*) FROM paradas;" 2>/dev/null | tail -n 1
        
    else
        echo "❌ Banco '$DB_NAME' não existe ou não está acessível!"
        echo ""
        echo "📋 Bancos disponíveis:"
        mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" -e "SHOW DATABASES;" 2>/dev/null
    fi
    
else
    echo "❌ Erro na conexão MySQL!"
    echo ""
    echo "🔍 Possíveis problemas:"
    echo "1. Usuário/senha incorretos"
    echo "2. MySQL não está rodando"
    echo "3. Firewall bloqueando conexão"
    echo "4. Usuário não tem permissões"
fi

echo ""
echo "🔧 Para configurar Laravel para usar MySQL, atualize o .env:"
echo "DB_CONNECTION=mysql"
echo "DB_HOST=$DB_HOST"
echo "DB_PORT=$DB_PORT"
echo "DB_DATABASE=$DB_NAME"
echo "DB_USERNAME=$DB_USER"
echo "DB_PASSWORD=$DB_PASS"
echo ""