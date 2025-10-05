<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

echo "=== VERIFICANDO COLUNAS DA TABELA TESTES ===\n";

$columns = DB::select('PRAGMA table_info(testes)');

echo "Colunas que contêm 'foto':\n";
foreach($columns as $column) {
    if(strpos($column->name, 'foto') !== false) {
        echo "- " . $column->name . " (" . $column->type . ")\n";
    }
}

echo "\nVerificando especificamente as colunas esperadas:\n";
$expectedColumns = [
    'ar_comprimido_foto_problema',
    'protecoes_eletricas_foto_problema',
    'protecoes_mecanicas_foto_problema', 
    'chave_remoto_foto_problema',
    'inspecionado_foto_problema',
    'ar_comprimido_foto_resolucao',
    'protecoes_eletricas_foto_resolucao',
    'protecoes_mecanicas_foto_resolucao',
    'chave_remoto_foto_resolucao', 
    'inspecionado_foto_resolucao'
];

foreach($expectedColumns as $expected) {
    $exists = false;
    foreach($columns as $column) {
        if($column->name === $expected) {
            $exists = true;
            break;
        }
    }
    echo "- $expected: " . ($exists ? "EXISTE" : "NÃO EXISTE") . "\n";
}