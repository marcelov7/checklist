<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->filled('perfil')) {
            $query->where('perfil', $request->perfil);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();
        
        return view('usuarios.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(6)],
            'perfil' => 'required|in:admin,operador,manutencao',
            'departamento' => 'nullable|string|max:100',
            'telefone' => 'nullable|string|max:20',
            'status' => 'required|in:ativo,inativo'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Converter string vazia para null no username
        if (empty($validated['username'])) {
            $validated['username'] = null;
        }

        User::create($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username,' . $usuario->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'perfil' => 'required|in:admin,operador,manutencao',
            'departamento' => 'nullable|string|max:100',
            'telefone' => 'nullable|string|max:20',
            'status' => 'required|in:ativo,inativo'
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(6)]
            ]);
            $validated['password'] = Hash::make($request->password);
        }
        
        // Converter string vazia para null no username
        if (empty($validated['username'])) {
            $validated['username'] = null;
        }

        $usuario->update($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $usuario)
    {
        // Prevent deleting own account
        if ($usuario->id === session('user.id')) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Você não pode excluir sua própria conta!');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $usuario)
    {
        $usuario->update([
            'status' => $usuario->status === 'ativo' ? 'inativo' : 'ativo'
        ]);

        $status = $usuario->status === 'ativo' ? 'ativado' : 'desativado';

        return redirect()->back()
            ->with('success', "Usuário {$status} com sucesso!");
    }
}