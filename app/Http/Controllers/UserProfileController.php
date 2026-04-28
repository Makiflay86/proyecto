<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $products = $user->products()->with('images')->latest()->get();

        return view('store.user-profile', compact('user', 'products'));
    }
}
