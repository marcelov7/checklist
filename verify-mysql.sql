-- SQL para verificar e configurar o banco MySQL
-- Execute via: mysql -u checkuser -p checkdb < verify-mysql.sql

SELECT 'Verificação do Banco MySQL - Sistema Checklist' as info;
SELECT NOW() as timestamp;

-- Verificar tabelas existentes
SELECT 'TABELAS EXISTENTES:' as info;
SHOW TABLES;

-- Verificar estrutura das tabelas principais
SELECT 'ESTRUTURA DA TABELA USERS:' as info;
DESC users;

SELECT 'ESTRUTURA DA TABELA AREAS:' as info;
DESC areas;

SELECT 'ESTRUTURA DA TABELA EQUIPAMENTOS:' as info;
DESC equipamentos;

SELECT 'ESTRUTURA DA TABELA PARADAS:' as info;
DESC paradas;

SELECT 'ESTRUTURA DA TABELA TESTES:' as info;
DESC testes;

-- Verificar dados existentes
SELECT 'CONTAGEM DE REGISTROS:' as info;
SELECT 
    'users' as tabela, 
    COUNT(*) as total 
FROM users
UNION ALL
SELECT 
    'areas' as tabela, 
    COUNT(*) as total 
FROM areas
UNION ALL
SELECT 
    'equipamentos' as tabela, 
    COUNT(*) as total 
FROM equipamentos
UNION ALL
SELECT 
    'paradas' as tabela, 
    COUNT(*) as total 
FROM paradas
UNION ALL
SELECT 
    'testes' as tabela, 
    COUNT(*) as total 
FROM testes;

-- Mostrar dados de exemplo
SELECT 'USUÁRIOS:' as info;
SELECT id, name, email, created_at FROM users LIMIT 5;

SELECT 'ÁREAS:' as info;
SELECT id, nome, descricao, ativo FROM areas LIMIT 5;

SELECT 'EQUIPAMENTOS:' as info;
SELECT id, nome, tag, area_id, ativo FROM equipamentos LIMIT 5;

SELECT 'PARADAS:' as info;
SELECT id, macro, nome, status, tipo FROM paradas LIMIT 5;

SELECT 'TESTES:' as info;
SELECT id, parada_id, equipamento_id, status, testado_por FROM testes LIMIT 5;