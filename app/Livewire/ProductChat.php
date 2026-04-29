<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductChat extends Component
{
    public int $productId = 0;
    public int $threadUserId = 0;

    #[Validate('required|string|max:1000')]
    public string $body = '';

    public function mount(int $productId, int $threadUserId): void
    {
        $this->productId    = $productId;
        $this->threadUserId = $threadUserId;
        $this->markRead();
    }

    public function sendMessage(): void
    {
        $this->validate();

        // Permitir: admin, comprador (thread_user) o dueño del producto (vendedor)
        $product = Product::find($this->productId);
        $isBuyer  = Auth::id() === $this->threadUserId;
        $isSeller = $product && Auth::id() === $product->user_id;
        if (! Auth::user()->is_admin && ! $isBuyer && ! $isSeller) {
            abort(403);
        }

        Message::create([
            'product_id'     => $this->productId,
            'thread_user_id' => $this->threadUserId,
            'sender_id'      => Auth::id(),
            'body'           => trim($this->body),
            'created_at'     => now(),
        ]);

        $this->body = '';
        $this->markRead();
    }

    /** Marca como leídos los mensajes que NO son míos. */
    private function markRead(): void
    {
        Message::where('product_id', $this->productId)
            ->where('thread_user_id', $this->threadUserId)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        $messages    = Message::with('sender')
            ->where('product_id', $this->productId)
            ->where('thread_user_id', $this->threadUserId)
            ->orderBy('created_at')
            ->get();

        $product    = Product::with(['images', 'user'])->find($this->productId);
        $threadUser = User::find($this->threadUserId);

        return view('livewire.product-chat', compact('messages', 'product', 'threadUser'));
    }
}
