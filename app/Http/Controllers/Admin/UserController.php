<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function show(User $user)
    {
        $user->loadCount(['products', 'likedProducts']);

        return view('admin.users.show', compact('user'));
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
