<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        abort_if(! auth()->user()->is_admin, 403);

        $products     = $user->products()->with('images')->latest()->get();
        $messageCount = Message::where('thread_user_id', $user->id)->distinct('product_id')->count('product_id');

        return view('store.user-profile', compact('user', 'products', 'messageCount'));
    }
}
