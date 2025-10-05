<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'perfil',
        'departamento',
        'telefone',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the perfil display name
     */
    public function getPerfilDisplayAttribute()
    {
        return match($this->perfil) {
            'admin' => 'Administrador',
            'operador' => 'Operador',
            'manutencao' => 'Manutenção',
            default => 'Indefinido'
        };
    }

    /**
     * Get the status display name
     */
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'ativo' => 'Ativo',
            'inativo' => 'Inativo',
            default => 'Indefinido'
        };
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Scope for inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inativo');
    }

    /**
     * Scope by perfil
     */
    public function scopeByPerfil($query, $perfil)
    {
        return $query->where('perfil', $perfil);
    }
}
