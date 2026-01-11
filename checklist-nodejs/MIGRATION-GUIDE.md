# 📊 Guia de Migração de Dados - Sistema Checklist

## 🎯 Resumo das Migrações

O sistema possui **3 migrações** prontas para produção:

### 1️⃣ `20251029135823_init`
**Primeira migração - Estrutura base**
- ✅ Tabela `users` (usuários do sistema)
- ✅ Tabela `areas` (áreas da empresa)
- ✅ Tabela `paradas` (paradas de manutenção)
- ✅ Tabela `checklists` (listas de verificação)
- ✅ Tabela `checklist_items` (itens dos checklists)
- ✅ Tabela `checklist_executions` (execuções dos checklists)
- ✅ Tabela `checklist_item_executions` (execução de itens individuais)

### 2️⃣ `20251029165053_add_equipamentos_table`
**Segunda migração - Módulo de Equipamentos**
- ✅ Tabela `equipamentos` com todos os campos necessários
- ✅ Relacionamento com `areas` (obrigatório inicialmente)
- ✅ Índice único em `numeracao`
- ✅ Campos: id, numeracao, nome, tipo, fabricante, modelo, numeroSerie, status, prioridade, observacoes

### 3️⃣ `20251029165129_make_area_id_optional`
**Terceira migração - Flexibilização**
- ✅ Campo `areaId` tornado opcional
- ✅ Permite equipamentos sem área definida
- ✅ Mantém integridade referencial

## 🔄 Processo de Deploy em Produção

### Passo 1: Backup do Banco Atual
```bash
# Para PostgreSQL
pg_dump -h localhost -U username -d database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# Para MySQL
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# Para SQLite (desenvolvimento)
cp dev.db backup_dev_$(date +%Y%m%d_%H%M%S).db
```

### Passo 2: Configurar Banco de Produção
```bash
# 1. Configurar DATABASE_URL no .env
DATABASE_URL="postgresql://user:pass@host:5432/checklist_prod"

# 2. Verificar conexão
npx prisma db pull --preview-feature
```

### Passo 3: Aplicar Migrações
```bash
# IMPORTANTE: Use migrate deploy em produção (não migrate dev)
npx prisma migrate deploy

# Verificar se todas foram aplicadas
npx prisma migrate status
```

### Passo 4: Gerar Cliente Prisma
```bash
npx prisma generate
```

## 📋 Checklist de Verificação Pós-Migração

### ✅ Verificações Obrigatórias

1. **Tabelas criadas:**
   ```sql
   -- Verificar se todas as tabelas existem
   SELECT table_name FROM information_schema.tables 
   WHERE table_schema = 'public';
   ```

2. **Dados preservados:**
   ```sql
   -- Contar registros em cada tabela
   SELECT COUNT(*) FROM users;
   SELECT COUNT(*) FROM areas;
   SELECT COUNT(*) FROM equipamentos;
   ```

3. **Relacionamentos funcionando:**
   ```sql
   -- Testar join entre equipamentos e areas
   SELECT e.nome, a.name as area_name 
   FROM equipamentos e 
   LEFT JOIN areas a ON e.areaId = a.id;
   ```

4. **Índices criados:**
   ```sql
   -- Verificar índices únicos
   SELECT indexname, tablename FROM pg_indexes 
   WHERE tablename IN ('users', 'areas', 'equipamentos');
   ```

## 🚨 Troubleshooting

### Erro: "Migration failed"
```bash
# Ver detalhes do erro
npx prisma migrate status

# Verificar diferenças
npx prisma db diff

# Se necessário, aplicar manualmente
npx prisma db push --preview-feature
```

### Erro: "Table already exists"
```bash
# Marcar migração como aplicada (cuidado!)
npx prisma migrate resolve --applied 20251029135823_init
```

### Erro: "Foreign key constraint"
```bash
# Verificar dados órfãos antes da migração
SELECT * FROM equipamentos WHERE areaId NOT IN (SELECT id FROM areas);
```

## 📊 Migração de Dados Existentes

Se você já tem dados em produção:

### 1. Equipamentos sem área
```sql
-- Encontrar equipamentos sem área válida
SELECT * FROM equipamentos 
WHERE areaId IS NOT NULL 
AND areaId NOT IN (SELECT id FROM areas);

-- Corrigir (definir como NULL)
UPDATE equipamentos 
SET areaId = NULL 
WHERE areaId NOT IN (SELECT id FROM areas);
```

### 2. Normalizar dados
```sql
-- Padronizar status
UPDATE equipamentos SET status = 'ATIVO' WHERE status IS NULL;

-- Padronizar prioridade
UPDATE equipamentos SET prioridade = 3 WHERE prioridade IS NULL;
```

## 🔐 Segurança

### Permissões do Banco
```sql
-- Criar usuário específico para a aplicação
CREATE USER checklist_app WITH PASSWORD 'senha_segura';

-- Dar permissões mínimas necessárias
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO checklist_app;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO checklist_app;
```

### Backup Automático
```bash
# Adicionar ao crontab para backup diário
0 2 * * * pg_dump -h localhost -U checklist_app checklist_prod > /backups/checklist_$(date +\%Y\%m\%d).sql
```

## 📈 Monitoramento Pós-Deploy

### Logs a Monitorar
- Conexões com banco de dados
- Queries lentas
- Erros de constraint
- Uso de memória do Prisma Client

### Métricas Importantes
- Tempo de resposta das APIs
- Número de conexões ativas
- Tamanho do banco de dados
- Performance das queries

---

**⚠️ IMPORTANTE:** Sempre teste as migrações em um ambiente de staging idêntico à produção antes do deploy final!