<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\Area;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EquipamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipamentos = Equipamento::with('area')->where('ativo', true)->get();
        return view('equipamentos.index', compact('equipamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = Area::where('ativo', true)->get();
        return view('equipamentos.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tag' => 'required|string|max:50|unique:equipamentos,tag',
            'descricao' => 'nullable|string',
            'area_id' => 'required|exists:areas,id',
        ]);

        Equipamento::create($validated);

        return redirect()->route('equipamentos.index')->with('success', 'Equipamento criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipamento $equipamento)
    {
        $equipamento->load('area', 'testes');
        return view('equipamentos.show', compact('equipamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipamento $equipamento)
    {
        $areas = Area::where('ativo', true)->get();
        return view('equipamentos.edit', compact('equipamento', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipamento $equipamento)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tag' => 'required|string|max:50|unique:equipamentos,tag,' . $equipamento->id,
            'descricao' => 'nullable|string',
            'area_id' => 'required|exists:areas,id',
        ]);

        $equipamento->update($validated);

        return redirect()->route('equipamentos.index')->with('success', 'Equipamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipamento $equipamento)
    {
        $equipamento->update(['ativo' => false]);
        
        return redirect()->route('equipamentos.index')->with('success', 'Equipamento desativado com sucesso!');
    }

    /**
     * Export data as CSV
     */
    public function exportData()
    {
        $equipamentos = Equipamento::with('area')->where('ativo', true)->get();
        
        $filename = 'equipamentos_' . date('Y-m-d_H-i-s') . '.csv';
        
        return new StreamedResponse(function() use ($equipamentos) {
            $handle = fopen('php://output', 'w');
            
            // BOM for UTF-8
            fwrite($handle, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($handle, [
                'ID',
                'Nome do Equipamento',
                'Tag/Código',
                'Descrição',
                'Área',
                'Status',
                'Data de Criação'
            ], ';');
            
            foreach ($equipamentos as $equipamento) {
                fputcsv($handle, [
                    $equipamento->id,
                    $equipamento->nome,
                    $equipamento->tag,
                    $equipamento->descricao ?? '',
                    $equipamento->area->nome,
                    $equipamento->ativo ? 'Ativo' : 'Inativo',
                    $equipamento->created_at->format('d/m/Y H:i')
                ], ';');
            }
            
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Import data from CSV  
     */
    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path), array_fill(0, count(file($path)), ';'));
            
            // Remove header
            array_shift($data);
            
            $imported = 0;
            foreach ($data as $row) {
                if (count($row) >= 5 && !empty(trim($row[1])) && !empty(trim($row[2])) && !empty(trim($row[4]))) {
                    // Buscar área
                    $area = Area::where('nome', trim($row[4]))->where('ativo', true)->first();
                    
                    if ($area) {
                        Equipamento::create([
                            'nome' => trim($row[1]),
                            'tag' => trim($row[2]),
                            'descricao' => isset($row[3]) ? trim($row[3]) : '',
                            'area_id' => $area->id,
                            'ativo' => true
                        ]);
                        $imported++;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "{$imported} equipamentos importados com sucesso!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao importar: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Download CSV template for import
     */
    public function downloadTemplate()
    {
        $areas = Area::where('ativo', true)->get();
        $filename = 'template_equipamentos.csv';
        
        return new StreamedResponse(function() use ($areas) {
            $handle = fopen('php://output', 'w');
            
            // BOM for UTF-8  
            fwrite($handle, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($handle, [
                'ID',
                'Nome do Equipamento',
                'Tag/Código',
                'Descrição',
                'Área'
            ], ';');
            
            // Example data with existing areas
            if ($areas->count() > 0) {
                fputcsv($handle, ['', 'Bomba Principal 01', 'BP-001', 'Bomba centrífuga para água de resfriamento', $areas->first()->nome], ';');
                fputcsv($handle, ['', 'Compressor de Ar', 'CA-001', 'Compressor de parafuso para ar de processo', $areas->first()->nome], ';');
                fputcsv($handle, ['', 'Motor Elétrico 01', 'ME-001', 'Motor de 50CV para acionamento da bomba', $areas->first()->nome], ';');
            }
            
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
