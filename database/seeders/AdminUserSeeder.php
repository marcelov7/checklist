<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = 'admin@local.test';
        $username = 'admin';

        $user = User::where('email', $email)->orWhere('username', $username)->first();

        if ($user) {
            $user->name = 'Administrador Local';
            $user->username = $username;
            $user->email = $email;
            $user->password = Hash::make('password');
            $user->perfil = 'admin';
            $user->departamento = 'TI';
            $user->telefone = null;
            $user->status = 'ativo';
            $user->save();
        } else {
            User::create([
                'name' => 'Administrador Local',
                'username' => $username,
                'email' => $email,
                'password' => Hash::make('password'),
                'perfil' => 'admin',
                'departamento' => 'TI',
                'telefone' => null,
                'status' => 'ativo',
            ]);
        }
    }
}
