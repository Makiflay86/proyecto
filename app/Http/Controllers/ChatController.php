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

        // Obtenemos el ID del último mensaje de cada hilo para luego cargarlo con relaciones
        $lastIds = Message::selectRaw('MAX(id) as id')
            ->when(! $user->is_admin, fn ($q) => $q->where('thread_user_id', $user->id))
            ->groupBy('product_id', 'thread_user_id')
            ->pluck('id');

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
