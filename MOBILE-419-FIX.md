# Fix para Erro 419 em Mobile

## Problema
Navegadores mobile (principalmente iOS Safari e Chrome Mobile) estão retornando erro 419 (Page Expired) ao submeter formulários, enquanto desktop funciona normalmente.

## Causa
- Cookies CSRF não são enviados corretamente em mobile devido a políticas mais restritivas
- Session cookies podem ser bloqueados com `SameSite=lax` em alguns contextos

## Solução Implementada

### 1. JavaScript - Refresh CSRF antes de submit em mobile
Arquivo: `resources/views/layouts/app.blade.php`
- Detecta se é dispositivo mobile
- Antes de submeter formulário, busca token CSRF fresco via `/refresh-csrf`
- Atualiza token e então submete o formulário

### 2. Configuração de Produção

**Arquivo `.env` no servidor:**

```env
# Session Config para Mobile
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
SESSION_DOMAIN=
```

**IMPORTANTE:** 
- `SESSION_SAME_SITE=none` - Permite cookies cross-site (necessário para mobile)
- `SESSION_SECURE_COOKIE=true` - Obrigatório quando SameSite=none (requer HTTPS)
- `SESSION_DOMAIN=` - Deixar vazio ou definir como `.seudominio.com`

### 3. Headers de Resposta

Verificar se o servidor está enviando headers corretos:

```
Set-Cookie: laravel_session=...; path=/; secure; httponly; samesite=none
Set-Cookie: XSRF-TOKEN=...; path=/; samesite=none
```

### 4. Deploy

**Passos após upload:**

1. Fazer upload de:
   - `resources/views/layouts/app.blade.php`
   - Arquivos de view atualizados
   - `public/build/` (assets)

2. Atualizar `.env` de produção com configurações acima

3. Via `public/artisan_http.php?command=config:cache&token=SEU_TOKEN`:
   - Executar: `config:cache`
   - Executar: `view:cache`

4. Testar em mobile após limpar cache do navegador

### 5. Verificação

**Desktop (deve continuar funcionando):**
- Login ✓
- Submit de formulários ✓

**Mobile:**
- Login ✓
- Submit de formulários ✓
- Após inatividade/reabrir app ✓

### 6. Troubleshooting

Se continuar com 419:

1. **Limpar cache mobile:**
   - Safari iOS: Configurações > Safari > Limpar Histórico e Dados
   - Chrome Android: Configurações > Privacidade > Limpar dados

2. **Verificar logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Testar manualmente token:**
   - Abrir DevTools no mobile
   - Console: `document.cookie` - verificar se `laravel_session` e `XSRF-TOKEN` existem

4. **Fallback - remover PWA cache:**
   - Desregistrar service worker se existir
   - Limpar cache do PWA

### 7. Configuração Alternativa (se SameSite=none não funcionar)

```env
SESSION_SAME_SITE=lax
SESSION_SECURE_COOKIE=true
```

E adicionar no servidor (arquivo `.htaccess` ou config Nginx):
```
Header always edit Set-Cookie (.*) "$1; SameSite=None; Secure"
```

## Arquivos Modificados
- `resources/views/layouts/app.blade.php` - Lógica de refresh CSRF para mobile
