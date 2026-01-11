<?php
/**
 * Script para criar link simbólico storage em ambientes sem acesso ao artisan
 * Upload este arquivo para public/ e execute via navegador
 */

// Segurança: remover após uso
$token = $_GET['token'] ?? '';
$expectedToken = 'create_link_2026'; // Token de segurança

if ($token !== $expectedToken) {
    die('Acesso negado. Token inválido.');
}

// Caminhos
$targetPath = realpath(__DIR__ . '/../storage/app/public');
$linkPath = __DIR__ . '/storage';

echo "<h2>Criação de Link Simbólico Storage</h2>";
echo "<pre>";
echo "Target: $targetPath\n";
echo "Link: $linkPath\n\n";

// Verificar se o diretório de destino existe
if (!is_dir($targetPath)) {
    die("ERRO: Diretório storage/app/public não existe!\n");
}

// Remover link/diretório existente
if (file_exists($linkPath)) {
    if (is_link($linkPath)) {
        echo "Link simbólico existente encontrado. Removendo...\n";
        unlink($linkPath);
    } elseif (is_dir($linkPath)) {
        echo "Diretório existente encontrado. Removendo...\n";
        rmdir($linkPath);
    } else {
        echo "Arquivo existente encontrado. Removendo...\n";
        unlink($linkPath);
    }
}

// Tentar criar link simbólico
if (symlink($targetPath, $linkPath)) {
    echo "✓ Link simbólico criado com sucesso!\n\n";
    
    // Verificar se funcionou
    if (is_link($linkPath)) {
        $linkTarget = readlink($linkPath);
        echo "✓ Verificação: Link aponta para $linkTarget\n";
        
        // Testar acesso
        if (is_dir($linkPath)) {
            echo "✓ Link está acessível\n";
            
            // Listar alguns arquivos como teste
            $files = scandir($linkPath);
            echo "\nArquivos em storage:\n";
            foreach (array_slice($files, 0, 10) as $file) {
                if ($file !== '.' && $file !== '..') {
                    echo "  - $file\n";
                }
            }
        } else {
            echo "✗ AVISO: Link criado mas não acessível\n";
        }
    } else {
        echo "✗ AVISO: Link criado mas verificação falhou\n";
    }
    
    echo "\n<strong style='color: green;'>CONCLUÍDO!</strong>\n";
    echo "\nAgora você pode acessar as imagens via:\n";
    echo "https://checklist.devaxis.com.br/storage/problemas/...\n\n";
    
} else {
    echo "✗ ERRO: Não foi possível criar link simbólico\n";
    echo "Motivo possível: servidor não permite symlink\n\n";
    
    echo "SOLUÇÃO ALTERNATIVA:\n";
    echo "1. Criar manualmente o diretório 'public/storage'\n";
    echo "2. Copiar conteúdo de 'storage/app/public' para 'public/storage'\n";
    echo "3. Ou configurar reescrita de URL no .htaccess\n";
}

echo "\n<strong>IMPORTANTE: Remova este arquivo após uso!</strong>\n";
echo "</pre>";

// Auto-remoção (comentado por segurança - descomente se desejar)
// unlink(__FILE__);
