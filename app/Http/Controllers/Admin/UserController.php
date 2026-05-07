<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * Gestión de usuarios desde el panel admin.
 *
 * Rutas asociadas (todas requieren auth + verified + admin):
 *   GET    /admin/users                     → index()
 *   GET    /admin/users/{user}              → show()
 *   PATCH  /admin/users/{user}/toggle-admin → toggleAdmin()
 *   DELETE /admin/users/{user}              → destroy()
 */
class UserController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'customers');

        $admins = User::where('is_admin', true)->orderBy('name')->get();
        $customers = User::where('is_admin', false)->orderBy('name')->paginate(20);

        return view('admin.users.index', compact('admins', 'customers', 'tab'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'name.max'          => 'El nombre no puede superar los 255 caracteres.',
            'email.required'    => 'El email es obligatorio.',
            'email.email'       => 'Introduce un email válido.',
            'email.unique'      => 'Este email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'is_admin'          => $request->boolean('is_admin'),
            'email_verified_at' => now(),
        ]);

        $tab = $user->is_admin ? 'admins' : 'customers';

        return redirect()->route('admin.users.index', ['tab' => $tab])
            ->with('success', "Usuario {$user->name} creado correctamente.");
    }

    public function show(User $user)
    {
        $user->loadCount(['products', 'likedProducts']);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'      => 'El nombre es obligatorio.',
            'name.max'           => 'El nombre no puede superar los 255 caracteres.',
            'email.required'     => 'El email es obligatorio.',
            'email.email'        => 'Introduce un email válido.',
            'email.unique'       => 'Este email ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'is_admin' => $request->boolean('is_admin'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes modificar tu propio rol de administrador.');
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        $msg = $user->is_admin
            ? "{$user->name} ahora es administrador."
            : "{$user->name} ya no es administrador.";

        return back()->with('success', $msg);
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta desde el panel.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Usuario {$user->name} eliminado correctamente.");
    }
}
