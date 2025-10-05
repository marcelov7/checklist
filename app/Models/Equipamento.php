<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $fillable = [
        'nome',
        'tag',
        'descricao',
        'area_id',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function testes()
    {
        return $this->hasMany(Teste::class);
    }

    public function getStatusParadaAttribute($paradaId)
    {
        $teste = $this->testes()->where('parada_id', $paradaId)->first();
        return $teste ? $teste->status : 'pendente';
    }
}
