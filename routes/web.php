<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\ParadaController;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (rota protegida)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth.session');

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('/test', function () {
    return 'Sistema funcionando!';
});

// Rota de teste para upload de fotos
Route::get('/teste-upload', function () {
    $testes = \App\Models\Teste::with('equipamento')->get();
    return view('teste-upload', compact('testes'));
})->middleware('auth.session');

// Rotas protegidas por autenticação
Route::middleware(['auth.session'])->group(function () {
    // Rotas para Áreas
    Route::get('areas/export-data', [AreaController::class, 'exportData'])->name('areas.export-data');
    Route::post('areas/import-data', [AreaController::class, 'importData'])->name('areas.import-data');
    Route::get('template-importacao', [AreaController::class, 'downloadTemplate'])->name('template.download');
    Route::resource('areas', AreaController::class);

    // Rotas para Equipamentos
    Route::get('equipamentos/export-data', [EquipamentoController::class, 'exportData'])->name('equipamentos.export-data');
    Route::post('equipamentos/import-data', [EquipamentoController::class, 'importData'])->name('equipamentos.import-data');
    Route::get('template-equipamentos', [EquipamentoController::class, 'downloadTemplate'])->name('equipamentos.template');
    Route::resource('equipamentos', EquipamentoController::class);

    // Rotas para Paradas
    Route::resource('paradas', ParadaController::class);
    Route::get('/paradas/{parada}/select-equipment', [ParadaController::class, 'selectEquipment'])->name('paradas.select-equipment');
    Route::post('/paradas/{parada}/store-equipment', [ParadaController::class, 'storeEquipment'])->name('paradas.store-equipment');
    Route::get('/paradas/{parada}/debug-equipment', [ParadaController::class, 'debugEquipment'])->name('paradas.debug-equipment');
    Route::post('paradas/{parada}/finalizar', [ParadaController::class, 'finalizar'])->name('paradas.finalizar');
    Route::get('paradas/{parada}/progresso', [ParadaController::class, 'progresso'])->name('paradas.progresso');
    Route::get('paradas/{parada}/debug', [ParadaController::class, 'debug'])->name('paradas.debug');
    Route::get('paradas-historico', [ParadaController::class, 'historico'])->name('paradas.historico');
    Route::get('paradas/{parada}/relatorio', [ParadaController::class, 'relatorio'])->name('paradas.relatorio');
    Route::get('paradas/{parada}/print', [ParadaController::class, 'print'])->name('paradas.print');
    Route::get('paradas/{parada}/pendencias', [ParadaController::class, 'pendencias'])->name('paradas.pendencias');
    Route::get('paradas/{parada}/pendencias-print', [ParadaController::class, 'pendenciasPrint'])->name('paradas.pendencias-print');

    // Rotas para Testes
    Route::post('testes', [TesteController::class, 'store'])->name('testes.store');
    Route::get('testes/{teste}', [TesteController::class, 'show'])->name('testes.show');
    Route::patch('testes/{teste}', [TesteController::class, 'update'])->name('testes.update');
    Route::patch('testes/{teste}/checklist-status', [TesteController::class, 'atualizarChecklistStatus'])->name('testes.atualizarChecklistStatus');
    Route::post('testes/{teste}/status', [TesteController::class, 'atualizarStatus'])->name('testes.atualizarStatus');
    Route::match(['PATCH', 'POST'], 'testes/{teste}/problema', [TesteController::class, 'salvarProblema'])->name('testes.salvarProblema');
    Route::match(['PATCH', 'POST'], 'testes/{teste}/resolver-problema', [TesteController::class, 'resolverProblema'])->name('testes.resolverProblema');
    Route::get('testes/{teste}/debug', [TesteController::class, 'debug'])->name('testes.debug');
    Route::patch('testes/update-multiple', [TesteController::class, 'updateMultiple'])->name('testes.update-multiple');

    // Rotas para Gerenciamento de Usuários (apenas admin)
    Route::middleware(['admin'])->group(function () {
        Route::resource('usuarios', UserController::class)->parameters(['usuarios' => 'usuario']);
        Route::patch('usuarios/{usuario}/toggle-status', [UserController::class, 'toggleStatus'])->name('usuarios.toggle-status');
        
        // Rota para reabrir parada (apenas administradores)
        Route::post('paradas/{parada}/reabrir', [ParadaController::class, 'reabrir'])->name('paradas.reabrir');
    });

    // Rotas para Perfil do Usuário
    Route::middleware(['auth.session'])->group(function () {
        Route::get('perfil', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('perfil', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('perfil/senha', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});
