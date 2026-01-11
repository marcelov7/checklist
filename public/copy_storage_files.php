<?php
/**
 * Script para copiar arquivos de storage quando symlink não é permitido
 * Upload este arquivo para public/ e execute via navegador
 */

// Segurança
$token = $_GET['token'] ?? '';
$expectedToken = 'copy_storage_2026';

if ($token !== $expectedToken) {
    die('Acesso negado. Token inválido.');
}

// Caminhos
$sourcePath = realpath(__DIR__ . '/../storage/app/public');
$destPath = __DIR__ . '/storage';

echo "<h2>Cópia de Arquivos Storage</h2>";
echo "<pre>";
echo "Origem: $sourcePath\n";
echo "Destino: $destPath\n\n";

// Verificar se origem existe
if (!is_dir($sourcePath)) {
    die("ERRO: Diretório storage/app/public não existe!\n");
}

// Função recursiva para copiar diretórios
function copyDirectory($source, $dest) {
    $copied = 0;
    $errors = 0;
    
    if (!is_dir($dest)) {
        if (!mkdir($dest, 0755, true)) {
            echo "✗ Erro ao criar diretório: $dest\n";
            return ['copied' => 0, 'errors' => 1];
        }
        echo "✓ Criado: $dest\n";
    }
    
    $dir = opendir($source);
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            $srcFile = $source . '/' . $file;
            $dstFile = $dest . '/' . $file;
            
            if (is_dir($srcFile)) {
                $result = copyDirectory($srcFile, $dstFile);
                $copied += $result['copied'];
                $errors += $result['errors'];
            } else {
                if (copy($srcFile, $dstFile)) {
                    $copied++;
                    if ($copied % 10 == 0) {
                        echo "Copiados: $copied arquivos...\n";
                        flush();
                    }
                } else {
                    echo "✗ Erro ao copiar: $srcFile\n";
                    $errors++;
                }
            }
        }
    }
    closedir($dir);
    
    return ['copied' => $copied, 'errors' => $errors];
}

// Remover destino se existir
if (file_exists($destPath)) {
    echo "Removendo destino existente...\n";
    if (is_link($destPath)) {
        unlink($destPath);
    } elseif (is_dir($destPath)) {
        // Não remove para não perder arquivos - apenas sobrescreve
        echo "Diretório já existe. Sincronizando arquivos...\n\n";
    }
}

// Executar cópia
echo "Iniciando cópia de arquivos...\n";
flush();

$result = copyDirectory($sourcePath, $destPath);

echo "\n";
echo "=====================================\n";
echo "RESULTADO:\n";
echo "✓ Arquivos copiados: {$result['copied']}\n";
echo "✗ Erros: {$result['errors']}\n";
echo "=====================================\n\n";

if ($result['errors'] == 0) {
    echo "<strong style='color: green;'>CONCLUÍDO COM SUCESSO!</strong>\n\n";
    echo "Agora as imagens devem estar acessíveis em:\n";
    echo "https://checklist.devaxis.com.br/storage/problemas/...\n\n";
    
    echo "<strong>IMPORTANTE:</strong>\n";
    echo "- Este método copia arquivos ao invés de link simbólico\n";
    echo "- Novos uploads precisarão ser copiados novamente\n";
    echo "- Considere usar o .htaccess rewrite como solução permanente\n";
} else {
    echo "<strong style='color: orange;'>Concluído com erros</strong>\n";
    echo "Verifique as permissões dos diretórios\n";
}

echo "\n<strong>Remova este arquivo após uso!</strong>\n";
echo "</pre>";
