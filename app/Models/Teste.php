<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teste extends Model
{
    protected $fillable = [
        'parada_id',
        'equipamento_id',
        'status',
        'observacoes',
        'problema_descricao',
        'data_teste',
        'testado_por',
        'foto_verificada',
        'ar_comprimido_ok',
        'protecoes_eletricas_ok',
        'protecoes_mecanicas_ok',
        'chave_remoto_testada',
        'inspecionado',
        'foto_path',
        // Novos campos de status
        'foto_status',
        'ar_comprimido_status',
        'protecoes_eletricas_status',
        'protecoes_mecanicas_status',
        'chave_remoto_status',
        'inspecionado_status',
        // Campos de problemas específicos
        'foto_problema',
        'ar_comprimido_problema',
        'protecoes_eletricas_problema',
        'protecoes_mecanicas_problema',
        'chave_remoto_problema',
        'inspecionado_problema',
        // Campos de fotos dos problemas
        'ar_comprimido_foto_problema',
        'protecoes_eletricas_foto_problema',
        'protecoes_mecanicas_foto_problema',
        'chave_remoto_foto_problema',
        'inspecionado_foto_problema',
        // Campos de resolução de problemas
        'ar_comprimido_resolucao',
        'ar_comprimido_foto_resolucao',
        'protecoes_eletricas_resolucao',
        'protecoes_eletricas_foto_resolucao',
        'protecoes_mecanicas_resolucao',
        'protecoes_mecanicas_foto_resolucao',
        'chave_remoto_resolucao',
        'chave_remoto_foto_resolucao',
        'inspecionado_resolucao',
        'inspecionado_foto_resolucao'
    ];

    protected $casts = [
        'data_teste' => 'datetime',
        'foto_verificada' => 'boolean',
        'ar_comprimido_ok' => 'boolean',
        'protecoes_eletricas_ok' => 'boolean',
        'protecoes_mecanicas_ok' => 'boolean',
        'chave_remoto_testada' => 'boolean',
        'inspecionado' => 'boolean',
    ];

    public function parada()
    {
        return $this->belongsTo(Parada::class);
    }

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pendente' => 'Pendente',
            'ok' => 'OK',
            'problema' => 'Com Problema',
            'nao_aplica' => 'Não se aplica',
            default => 'Desconhecido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pendente' => 'warning',
            'ok' => 'success',
            'problema' => 'danger',
            'nao_aplica' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Calcula o percentual de conclusão do checklist baseado nos status
     */
    public function getChecklistProgressAttribute()
    {
        $statusFields = [
            'ar_comprimido_status',
            'protecoes_eletricas_status',
            'protecoes_mecanicas_status',
            'chave_remoto_status',
            'inspecionado_status'
        ];

        $concluidos = 0;
        foreach ($statusFields as $campo) {
            // Considera concluído se o status for 'ok', 'problema', 'nao_ok' ou 'nao_aplica' (não pendente)
            if (in_array($this->$campo, ['ok', 'problema', 'nao_ok', 'nao_aplica'])) {
                $concluidos++;
            }
        }

        return round(($concluidos / count($statusFields)) * 100);
    }

    /**
     * Verifica se o checklist está completo
     */
    public function getChecklistCompletoAttribute()
    {
        $statusFields = [
            'ar_comprimido_status',
            'protecoes_eletricas_status',
            'protecoes_mecanicas_status',
            'chave_remoto_status',
            'inspecionado_status'
        ];

        foreach ($statusFields as $campo) {
            if ($this->$campo === 'pendente') {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Retorna a configuração dos itens do checklist
     */
    public function getChecklistItemsAttribute()
    {
        return [
            'ar_comprimido' => [
                'label' => 'Ar Comprimido OK',
                'icon' => 'fas fa-wind',
                'status' => $this->ar_comprimido_status,
                'problema' => $this->ar_comprimido_problema,
                'resolucao' => $this->ar_comprimido_resolucao,
                'foto_problema_path' => $this->ar_comprimido_foto_problema,
                'foto_resolucao_path' => $this->ar_comprimido_foto_resolucao
            ],
            'protecoes_eletricas' => [
                'label' => 'Proteções Elétricas OK',
                'icon' => 'fas fa-bolt',
                'status' => $this->protecoes_eletricas_status,
                'problema' => $this->protecoes_eletricas_problema,
                'resolucao' => $this->protecoes_eletricas_resolucao,
                'foto_problema_path' => $this->protecoes_eletricas_foto_problema,
                'foto_resolucao_path' => $this->protecoes_eletricas_foto_resolucao
            ],
            'protecoes_mecanicas' => [
                'label' => 'Proteções Mecânicas OK',
                'icon' => 'fas fa-cog',
                'status' => $this->protecoes_mecanicas_status,
                'problema' => $this->protecoes_mecanicas_problema,
                'resolucao' => $this->protecoes_mecanicas_resolucao,
                'foto_problema_path' => $this->protecoes_mecanicas_foto_problema,
                'foto_resolucao_path' => $this->protecoes_mecanicas_foto_resolucao
            ],
            'chave_remoto' => [
                'label' => 'Chave Remoto Testada',
                'icon' => 'fas fa-key',
                'status' => $this->chave_remoto_status,
                'problema' => $this->chave_remoto_problema,
                'resolucao' => $this->chave_remoto_resolucao,
                'foto_problema_path' => $this->chave_remoto_foto_problema,
                'foto_resolucao_path' => $this->chave_remoto_foto_resolucao
            ],
            'inspecionado' => [
                'label' => 'Inspecionado',
                'icon' => 'fas fa-search',
                'status' => $this->inspecionado_status,
                'problema' => $this->inspecionado_problema,
                'resolucao' => $this->inspecionado_resolucao,
                'foto_problema_path' => $this->inspecionado_foto_problema,
                'foto_resolucao_path' => $this->inspecionado_foto_resolucao
            ]
        ];
    }
}
