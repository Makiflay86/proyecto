<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:8', 'max:16', 'regex:/[^a-zA-Z0-9]/'],
        ], [
            'name.required'              => 'El nombre es obligatorio.',
            'email.required'             => 'El correo electrónico es obligatorio.',
            'email.email'                => 'El correo electrónico no es válido.',
            'email.unique'               => 'Este correo electrónico ya está registrado.',
            'password.required'          => 'La contraseña es obligatoria.',
            'password.min'               => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max'               => 'La contraseña no puede superar los 16 caracteres.',
            'password.regex'             => 'La contraseña debe incluir al menos un símbolo especial (ej: !, @, #, $...).',
            'password.confirmed'         => 'Las contraseñas no coinciden.',
            'password_confirmation.required' => 'Debes confirmar la contraseña.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('store.index', absolute: false));
    }
}
