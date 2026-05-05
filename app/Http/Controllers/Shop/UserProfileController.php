<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $products = $user->products()->with('images')->latest()->get();

        return view('shop.user-profile', compact('user', 'products'));
    }
}
