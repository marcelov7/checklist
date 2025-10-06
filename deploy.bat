@echo off
echo ====================================
echo   DEPLOY SISTEMA CHECKLIST DEVAXIS
echo ====================================
echo.

echo 1. Preparando arquivos para producao...
cd /d "d:\XAMP\checklist"

echo 2. Limpando caches locais...
php artisan config:clear
php artisan cache:clear  
php artisan view:clear

echo 3. Fazendo backup do .env original...
copy .env .env.backup

echo 4. Usando configuracao de producao...
copy .env.production .env

echo 5. Otimizando para producao...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo 6. Iniciando upload via SSH...
echo ATENCAO: Sera solicitada a senha SSH

echo Fazendo upload dos arquivos principais...
scp -r app bootstrap config database public resources routes storage vendor composer.json composer.lock artisan .env deploy.sh devaxis-checklist@31.97.168.137:/home/devaxis-checklist/public_html/

if %errorlevel% equ 0 (
    echo 7. Upload concluido! Executando script de deploy no servidor...
    ssh devaxis-checklist@31.97.168.137 "cd /home/devaxis-checklist/public_html && chmod +x deploy.sh && ./deploy.sh"
) else (
    echo ERRO no upload! Verifique a conexao SSH.
)

echo 8. Restaurando .env local...
copy .env.backup .env
del .env.backup

echo 9. Limpando caches locais...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo ====================================
echo   DEPLOY FINALIZADO!
echo   Acesse: https://checklist.devaxis.com.br
echo ====================================
pause