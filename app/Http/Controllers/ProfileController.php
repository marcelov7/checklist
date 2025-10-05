<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $userId = session('user.id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para editar seu perfil.');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuário não encontrado. Faça login novamente.');
        }
        
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $userId = session('user.id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para atualizar seu perfil.');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuário não encontrado. Faça login novamente.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'departamento' => 'nullable|string|max:100',
            'telefone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        // Update session data
        session(['user.name' => $validated['name']]);
        session(['user.email' => $validated['email']]);

        return redirect()->route('profile.edit')
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $userId = session('user.id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para alterar sua senha.');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuário não encontrado. Faça login novamente.');
        }

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Show user profile.
     */
    public function show()
    {
        $userId = session('user.id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar seu perfil.');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuário não encontrado. Faça login novamente.');
        }
        
        return view('profile.show', compact('user'));
    }
}