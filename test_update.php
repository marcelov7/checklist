<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

echo "=== TESTE DIRETO NO BANCO ===\n";

// Verificar valor atual
$teste = DB::table('testes')->where('id', 71)->first();
echo "Valor atual ar_comprimido_foto_problema: " . ($teste->ar_comprimido_foto_problema ?? 'NULL') . "\n";

// Tentar fazer update
try {
    $result = DB::table('testes')->where('id', 71)->update(['ar_comprimido_foto_problema' => 'teste123']);
    echo "Resultado do update: $result\n";
    
    // Verificar novamente
    $teste = DB::table('testes')->where('id', 71)->first();
    echo "Valor após update: " . ($teste->ar_comprimido_foto_problema ?? 'NULL') . "\n";
    
} catch(Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}