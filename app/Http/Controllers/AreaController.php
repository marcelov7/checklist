<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = Area::with('equipamentosAtivos')->where('ativo', true)->get();
        return view('areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('areas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        Area::create($validated);

        return redirect()->route('areas.index')->with('success', 'Área criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        $area->load('equipamentosAtivos');
        return view('areas.show', compact('area'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $area->update($validated);

        return redirect()->route('areas.index')->with('success', 'Área atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $area->update(['ativo' => false]);
        
        return redirect()->route('areas.index')->with('success', 'Área desativada com sucesso!');
    }

    /**
     * Export data as CSV
     */
    public function exportData()
    {
        $areas = Area::with('equipamentosAtivos')->where('ativo', true)->get();
        
        $filename = 'areas_' . date('Y-m-d_H-i-s') . '.csv';
        
        return new StreamedResponse(function() use ($areas) {
            $handle = fopen('php://output', 'w');
            
            // BOM for UTF-8
            fwrite($handle, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($handle, [
                'ID',
                'Nome da Área',
                'Descrição', 
                'Status',
                'Qtd. Equipamentos',
                'Data de Criação'
            ], ';');
            
            foreach ($areas as $area) {
                fputcsv($handle, [
                    $area->id,
                    $area->nome,
                    $area->descricao ?? '',
                    $area->ativo ? 'Ativo' : 'Inativo',
                    $area->equipamentosAtivos->count(),
                    $area->created_at->format('d/m/Y H:i')
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
                if (count($row) >= 2 && !empty(trim($row[1]))) {
                    Area::create([
                        'nome' => trim($row[1]),
                        'descricao' => isset($row[2]) ? trim($row[2]) : '',
                        'ativo' => true
                    ]);
                    $imported++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "{$imported} áreas importadas com sucesso!"
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
        $filename = 'template_areas.csv';
        
        return new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            
            // BOM for UTF-8
            fwrite($handle, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($handle, [
                'ID',
                'Nome da Área',
                'Descrição'
            ], ';');
            
            // Example data
            fputcsv($handle, ['', 'Área de Produção', 'Área responsável pela produção principal'], ';');
            fputcsv($handle, ['', 'Área de Manutenção', 'Área responsável pela manutenção dos equipamentos'], ';');
            fputcsv($handle, ['', 'Área de Utilidades', 'Área de sistemas auxiliares (ar comprimido, vapor, etc.)'], ';');
            
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
