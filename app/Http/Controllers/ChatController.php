<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /** Lista de conversaciones del usuario autenticado (o todas si es admin). */
    public function index()
    {
        $user = Auth::user();

        if ($user->is_admin) {
            // Admin ve todos los hilos
            $lastIds = Message::selectRaw('MAX(id) as id')
                ->groupBy('product_id', 'thread_user_id')
                ->pluck('id');
        } else {
            // Como comprador: hilos donde el usuario es el thread_user
            $buyerIds = Message::selectRaw('MAX(id) as id')
                ->where('thread_user_id', $user->id)
                ->groupBy('product_id', 'thread_user_id')
                ->pluck('id');

            // Como vendedor: hilos de sus productos donde el comprador es otro usuario
            $sellerIds = Message::selectRaw('MAX(id) as id')
                ->whereHas('product', fn ($q) => $q->where('user_id', $user->id))
                ->where('thread_user_id', '!=', $user->id)
                ->groupBy('product_id', 'thread_user_id')
                ->pluck('id');

            $lastIds = $buyerIds->merge($sellerIds)->unique();
        }

        $threads = Message::with(['product.images', 'sender', 'threadUser'])
            ->whereIn('id', $lastIds)
            ->orderByDesc('created_at')
            ->get();

        return view('store.messages', compact('threads'));
    }

    /** Vista del chat para el comprador (su propio hilo sobre un producto). */
    public function show(Product $product)
    {
        return view('store.chat', [
            'product'      => $product,
            'threadUserId' => Auth::id(),
        ]);
    }

    /** Vista del chat para el vendedor viendo el hilo de un comprador concreto. */
    public function showAsSeller(Product $product, User $buyer)
    {
        abort_if(Auth::id() !== $product->user_id && ! Auth::user()->is_admin, 403);

        return view('store.chat', [
            'product'      => $product,
            'threadUserId' => $buyer->id,
        ]);
    }

    /** Vista del chat para el admin viendo el hilo de un usuario concreto. */
    public function showThread(Product $product, User $user)
    {
        abort_if(! Auth::user()->is_admin, 403);

        return view('store.chat', [
            'product'      => $product,
            'threadUserId' => $user->id,
        ]);
    }
}
