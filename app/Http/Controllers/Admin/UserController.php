<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            'password' => ['required', 'confirmed', 'min:8', 'max:16', 'regex:/[^a-zA-Z0-9]/'],
        ], [
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max'       => 'La contraseña no puede superar los 16 caracteres.',
            'password.regex'     => 'La contraseña debe incluir al menos un símbolo especial (ej: !, @, #, $...).',
            'password.confirmed' => 'Las contraseñas no coinciden.',
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

        $buyerIds = Message::selectRaw('MAX(id) as id')
            ->where('thread_user_id', $user->id)
            ->groupBy('product_id', 'thread_user_id')
            ->pluck('id');

        $sellerIds = Message::selectRaw('MAX(id) as id')
            ->whereHas('product', fn ($q) => $q->where('user_id', $user->id))
            ->where('thread_user_id', '!=', $user->id)
            ->groupBy('product_id', 'thread_user_id')
            ->pluck('id');

        $threads = Message::with(['product.images', 'sender', 'threadUser'])
            ->whereIn('id', $buyerIds->merge($sellerIds)->unique())
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users.show', compact('user', 'threads'));
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
            'password' => ['nullable', 'confirmed', 'min:8', 'max:16', 'regex:/[^a-zA-Z0-9]/'],
        ], [
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max'       => 'La contraseña no puede superar los 16 caracteres.',
            'password.regex'     => 'La contraseña debe incluir al menos un símbolo especial (ej: !, @, #, $...).',
            'password.confirmed' => 'Las contraseñas no coinciden.',
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

    public function updateAvatar(Request $request, User $user)
    {
        $request->validate(['avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048']);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Avatar actualizado correctamente.');
    }

    public function deleteAvatar(User $user)
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Avatar eliminado correctamente.');
    }

    public function showConversation(User $user, Product $product)
    {
        $messages = Message::with('sender')
            ->where('product_id', $product->id)
            ->where('thread_user_id', $user->id)
            ->orderBy('created_at')
            ->get();

        $product->load('images', 'user');

        $fromUser = request()->query('from') ? User::find(request()->query('from')) : $user;

        return view('admin.users.conversation', compact('user', 'product', 'messages', 'fromUser'));
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
