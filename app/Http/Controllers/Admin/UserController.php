<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function home() 
    {
        $roles = Role::pluck('name');
        $users = User::all();
        return view('users.index', [
            'roles' => $roles,
            'users' => $users,
        ]);
    }

    public function create()
    {
        $roles = Role::pluck('name');

        $users = auth()->user()->hasRole('Administrator') 
            ? User::with('roles')->paginate(15) 
            : collect();

        return view('users.create', [
            'roles' => $roles,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->assignRole($validated['role']);
        
        return redirect()->route('show.empresas.home')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $usuario = User::find($id);
        $roles = Role::pluck('name', 'id');

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return view('users.edit', [
            'roles' => $roles,
            'usuario' => $usuario
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($usuario->id)],
            'password' => ['nullable', 'confirmed', 'min:6'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $usuario->name = $validated['name'];
        $usuario->email = $validated['email'];

        if (!empty($validated['password'])) {
            $usuario->password = bcrypt($validated['password']);
        }

        $usuario->save();

        $usuario->assignRole($validated['role']);

        return redirect()
            ->route('admin.show.usuarios.home')
            ->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            return redirect()
                ->route('admin.show.usuarios.home')
                ->with('error', 'Usuario no encontrado.');
        }

        if ($usuario->hasRole('Administrator')) {
            return redirect()
                ->route('admin.show.usuarios.home')
                ->with('error', 'No puedes eliminar un administrador.');
        }

        // Optional: avoid deleting yourself
        if (auth()->id() === $usuario->id) {
            return redirect()
                ->route('admin.show.usuarios.home')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Remove roles first (Spatie requirement)
        $usuario->syncRoles([]);

        // Soft delete or permanent delete
        $usuario->delete();

        return redirect()
            ->route('admin.show.usuarios.home')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
