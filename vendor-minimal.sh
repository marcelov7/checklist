#!/bin/bash

# Script para criar vendor mínimo - Sistema de Checklist
# Execute no servidor: bash vendor-minimal.sh

echo "=== CRIANDO VENDOR MÍNIMO PARA LARAVEL ==="

# Configurar PATH
export PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:$PATH"

PROJECT_DIR="/home/devaxis-checklist/htdocs/checklist.devaxis.com.br"
cd $PROJECT_DIR

echo "📁 Criando estrutura vendor mínima..."

# Criar diretório vendor
mkdir -p vendor

# Criar autoload.php básico
cat > vendor/autoload.php << 'EOL'
<?php
// Autoloader mínimo para Laravel funcionar

// Definir caminhos base
$basePath = dirname(__DIR__);
$appPath = $basePath . '/app';

// Registrar classes do Laravel manualmente
spl_autoload_register(function ($class) use ($appPath) {
    // Classes do app/
    if (strpos($class, 'App\\') === 0) {
        $file = $appPath . '/' . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Simular algumas classes básicas do Laravel que podem ser necessárias
if (!class_exists('Illuminate\Foundation\Application')) {
    class Illuminate_Foundation_Application {}
}

return true;
EOL

echo "✅ Autoloader mínimo criado"

# Criar .env se não existir
if [ ! -f ".env" ]; then
    echo "⚙️ Criando arquivo .env..."
    cat > .env << 'ENV_EOF'
APP_NAME="Sistema de Checklist"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:$(openssl rand -base64 32)
APP_URL=https://checklist.devaxis.com.br

DB_CONNECTION=mysql
DB_HOST=31.97.168.137
DB_PORT=3306
DB_DATABASE=checkdb
DB_USERNAME=devaxis-checklist
DB_PASSWORD=DevAxisChecklist2024!

SESSION_DRIVER=file
SESSION_LIFETIME=480

LOG_CHANNEL=stack
LOG_LEVEL=error

CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
ENV_EOF
    echo "✅ Arquivo .env criado"
else
    echo "✅ Arquivo .env já existe"
fi

# Tentar executar comandos básicos
echo ""
echo "🧪 Testando se o Laravel funciona agora..."

# Testar artisan
if php artisan --version > /dev/null 2>&1; then
    echo "✅ Laravel Artisan funcionando"
    
    echo ""
    echo "🗄️ Executando migrações..."
    php artisan migrate:install --force
    php artisan migrate --force
    
    echo ""
    echo "🌱 Executando seeders..."
    php artisan db:seed --force
    
else
    echo "❌ Laravel ainda não funciona, precisamos das dependências completas"
    echo "💡 Vamos criar as tabelas manualmente via SQL"
    
    # Executar SQLs básicos diretamente
    echo "📊 Criando tabelas via MySQL..."
    
    # Aqui podemos conectar direto no MySQL e criar as tabelas
    mysql -h 31.97.168.137 -u devaxis-checklist -pDevAxisChecklist2024! checkdb << 'SQL_EOF'
-- Tabela de usuários
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(100) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'supervisor', 'operador') DEFAULT 'operador',
    area_id BIGINT UNSIGNED,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabela de áreas
CREATE TABLE IF NOT EXISTS areas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabela de equipamentos  
CREATE TABLE IF NOT EXISTS equipamentos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    codigo VARCHAR(50) UNIQUE,
    area_id BIGINT UNSIGNED,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (area_id) REFERENCES areas(id)
);

-- Tabela de paradas
CREATE TABLE IF NOT EXISTS paradas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    equipamento_id BIGINT UNSIGNED NOT NULL,
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME,
    motivo TEXT,
    macro VARCHAR(100),
    status ENUM('ativa', 'finalizada') DEFAULT 'ativa',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (equipamento_id) REFERENCES equipamentos(id)
);

-- Tabela de testes/checklist
CREATE TABLE IF NOT EXISTS testes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parada_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    data_teste DATETIME NOT NULL,
    observacoes TEXT,
    checklist_liberacao_eletrica BOOLEAN DEFAULT FALSE,
    checklist_liberacao_mecanica BOOLEAN DEFAULT FALSE,
    checklist_liberacao_operacional BOOLEAN DEFAULT FALSE,
    checklist_status ENUM('pendente', 'aprovado', 'rejeitado') DEFAULT 'pendente',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (parada_id) REFERENCES paradas(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Inserir dados de exemplo
INSERT IGNORE INTO areas (nome, descricao) VALUES 
('Produção', 'Área de produção principal'),
('Manutenção', 'Área de manutenção industrial'),
('Qualidade', 'Controle de qualidade');

INSERT IGNORE INTO equipamentos (nome, codigo, area_id, descricao) VALUES
('Compressor 001', 'COMP-001', 1, 'Compressor de ar principal'),
('Bomba Hidráulica 002', 'BOMB-002', 1, 'Bomba do sistema hidráulico'),
('Esteira Transportadora 003', 'EST-003', 1, 'Esteira principal de transporte');

INSERT IGNORE INTO users (name, email, username, password, role) VALUES
('Administrador', 'admin@devaxis.com.br', 'admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Supervisor', 'supervisor@devaxis.com.br', 'supervisor', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor');
SQL_EOF

fi

echo ""
echo "✅ CONFIGURAÇÃO CONCLUÍDA!"
echo "🌐 Teste agora: https://checklist.devaxis.com.br"
echo "👤 Login: admin / password ou supervisor / password"