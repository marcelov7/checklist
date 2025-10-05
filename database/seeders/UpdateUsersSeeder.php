<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUsersSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Atualizar usuários existentes ou criar novos
        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@checklist.com',
                'password' => Hash::make('123456'),
                'perfil' => 'admin',
                'departamento' => 'Administração',
                'telefone' => '(11) 99999-0001',
                'status' => 'ativo'
            ],
            [
                'name' => 'João Operador',
                'email' => 'operador@checklist.com',
                'password' => Hash::make('123456'),
                'perfil' => 'operador',
                'departamento' => 'Operação',
                'telefone' => '(11) 99999-0002',
                'status' => 'ativo'
            ],
            [
                'name' => 'Maria Manutenção',
                'email' => 'manutencao@checklist.com',
                'password' => Hash::make('123456'),
                'perfil' => 'manutencao',
                'departamento' => 'Manutenção',
                'telefone' => '(11) 99999-0003',
                'status' => 'ativo'
            ]
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']], // buscar por email
                $userData // dados para criar/atualizar
            );
        }

        $this->command->info('Usuários atualizados/criados com sucesso!');
    }
}