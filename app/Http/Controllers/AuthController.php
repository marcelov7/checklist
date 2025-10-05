<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

        /**
     * Processa o login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Buscar usuário por email, username ou nome
        $user = User::where('email', $username)
                    ->orWhere('username', $username)
                    ->orWhere('name', $username)
                    ->first();

        // Verificar se o usuário existe, está ativo e a senha está correta
        if ($user && $user->status === 'ativo' && Hash::check($password, $user->password)) {
            // Login bem-sucedido
            Session::put('user', [
                'id' => $user->id,
                'username' => $user->email,
                'name' => $user->name,
                'email' => $user->email,
                'perfil' => $user->perfil,
                'departamento' => $user->departamento,
                'telefone' => $user->telefone,
                'status' => $user->status,
                'logged_in' => true
            ]);

            return redirect()->intended(route('dashboard'));
        }

        // Login falhou
        return back()->withErrors([
            'username' => 'Email/usuário ou senha incorretos, ou conta inativa.',
        ])->withInput();
    }

    /**
     * Realiza o logout
     */
    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Logout realizado com sucesso!');
    }
}
