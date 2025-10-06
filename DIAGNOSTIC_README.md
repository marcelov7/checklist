# DIAGNÓSTICO E SOLUÇÃO - MIGRAÇÃO DO BANCO DE DADOS

## Problema Identificado
A migração dos dados não está funcionando no servidor. Baseado na análise do código, identifiquei os possíveis problemas:

## Arquivos Criados para Diagnóstico

### 1. debug-migration.sh
**Propósito**: Diagnosticar problemas com SQLite (configuração atual)
**Como usar**: 
```bash
cd /home/devaxis-checklist/htdocs/checklist.devaxis.com.br
bash debug-migration.sh
```

### 2. check-mysql.sh  
**Propósito**: Verificar se o MySQL está funcionando com as credenciais fornecidas
**Como usar**:
```bash
cd /home/devaxis-checklist/htdocs/checklist.devaxis.com.br
bash check-mysql.sh
```

### 3. setup-mysql.sh
**Propósito**: Configurar o Laravel para usar MySQL ao invés de SQLite
**Como usar**:
```bash
cd /home/devaxis-checklist/htdocs/checklist.devaxis.com.br
bash setup-mysql.sh
```

### 4. verify-mysql.sql
**Propósito**: Verificar dados diretamente no MySQL
**Como usar**:
```bash
mysql -u checkuser -p'M@rcelo1809@3033' checkdb < verify-mysql.sql
```

## Diagnóstico Passo a Passo

### Passo 1: Conectar no servidor
```bash
# Via SSH (se funcionar)
ssh devaxis-checklist@200.98.132.57

# Ou via painel de controle/terminal do servidor
```

### Passo 2: Executar diagnóstico
```bash
cd /home/devaxis-checklist/htdocs/checklist.devaxis.com.br
bash debug-migration.sh > debug-log.txt 2>&1
cat debug-log.txt
```

### Passo 3: Verificar MySQL
```bash
bash check-mysql.sh
```

### Passo 4: Se MySQL estiver funcionando, configurar Laravel
```bash
bash setup-mysql.sh
```

## Possíveis Problemas e Soluções

### Problema 1: Arquivo .env não existe ou está mal configurado
**Sintomas**: Erro ao conectar no banco
**Solução**: O script setup-mysql.sh cria um .env completo

### Problema 2: SQLite sem permissões
**Sintomas**: Erro de permissão ao criar database/database.sqlite
**Solução**: 
```bash
chmod 755 database/
chmod 664 database/database.sqlite
```

### Problema 3: MySQL não configurado
**Sintomas**: Sistema usando SQLite mas deveria usar MySQL
**Solução**: Executar setup-mysql.sh

### Problema 4: Migrações não executadas
**Sintomas**: Tabelas não existem
**Solução**:
```bash
php artisan migrate:fresh --force
php artisan db:seed --force --class=DadosExemploSeeder
```

### Problema 5: Cache do Laravel
**Sintomas**: Mudanças no .env não surtem efeito
**Solução**:
```bash
php artisan config:clear
php artisan cache:clear
```

## Configuração Recomendada (.env)

Para MySQL:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=checkdb
DB_USERNAME=checkuser
DB_PASSWORD=M@rcelo1809@3033
```

Para SQLite (alternativo):
```bash
DB_CONNECTION=sqlite
# DB_DATABASE será database/database.sqlite automaticamente
```

## Comandos de Verificação Rápida

```bash
# Verificar se Laravel funciona
php artisan --version

# Testar conexão com banco
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Conectado!';"

# Ver status das migrações
php artisan migrate:status

# Contar registros
php artisan tinker --execute="echo 'Users: '.DB::table('users')->count();"
```

## Logs Importantes

Verificar logs em:
- `storage/logs/laravel.log`
- `/var/log/apache2/error.log` (ou nginx)
- `/var/log/mysql/error.log`

## Próximos Passos

1. Execute `debug-migration.sh` primeiro
2. Se MySQL estiver disponível, execute `setup-mysql.sh`
3. Se tudo estiver funcionando, acesse: https://checklist.devaxis.com.br
4. Login padrão: test@example.com / password

## Notas Importantes

- O sistema atual está configurado para SQLite por padrão
- As credenciais MySQL fornecidas: checkuser/M@rcelo1809@3033/checkdb
- O script setup-mysql.sh faz a migração completa para MySQL
- Todos os dados de exemplo serão criados automaticamente