# Correção de Imagens 404 (Storage Link)

## Problema
Imagens anexadas retornam HTTP 404:
```
GET https://checklist.devaxis.com.br/storage/problemas/xxx.png [404]
```

## Causa
O link simbólico `public/storage` → `storage/app/public` não foi criado no servidor.

## Solução

### Opção 1: Script PHP (RECOMENDADO)

1. **Upload do arquivo:**
   - Enviar `public/create_storage_link.php` para o servidor

2. **Executar via navegador:**
   ```
   https://checklist.devaxis.com.br/create_storage_link.php?token=create_link_2026
   ```

3. **Verificar output:**
   - Deve mostrar "Link simbólico criado com sucesso!"
   - Listar arquivos em storage

4. **Remover arquivo:**
   - Deletar `public/create_storage_link.php` após uso

### Opção 2: Via artisan_http.php

Executar comando via navegador:
```
https://checklist.devaxis.com.br/artisan_http.php?command=storage:link&token=SEU_TOKEN
```

### Opção 3: .htaccess (se symlink não funcionar)

Se o servidor não permitir symlinks, adicionar ao final de `public/.htaccess`:

```apache
# Reescrever requisições para storage
RewriteCond %{REQUEST_URI} ^/storage/(.*)$
RewriteCond %{DOCUMENT_ROOT}/../storage/app/public/%1 -f
RewriteRule ^storage/(.*)$ ../storage/app/public/$1 [L]
```

### Opção 4: Copiar manualmente (última alternativa)

Via FTP/painel:
1. Criar diretório `public/storage/`
2. Copiar todo conteúdo de `storage/app/public/` para `public/storage/`
3. Repetir sempre que houver novos uploads

## Verificação

Após aplicar solução, testar URL:
```
https://checklist.devaxis.com.br/storage/problemas/1768148610_protecoes_eletricas_problema_60.png
```

Deve retornar HTTP 200 e exibir a imagem.

## Estrutura de Diretórios

```
projeto/
├── public/
│   ├── storage/          ← Link simbólico ou rewrite
│   ├── index.php
│   └── .htaccess
├── storage/
│   └── app/
│       └── public/       ← Imagens reais aqui
│           ├── problemas/
│           ├── resolucoes/
│           └── ...
```

## Arquivos Criados
- `public/create_storage_link.php` - Script para criar link
- `htaccess-storage-fix.txt` - Regras alternativas para .htaccess
