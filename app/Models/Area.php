<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }

    public function equipamentosAtivos()
    {
        return $this->hasMany(Equipamento::class)->where('ativo', true);
    }
}
