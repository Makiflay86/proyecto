<?php

namespace App\Livewire\Shop;

use App\Mail\MessageReceivedMail;
use App\Models\Message;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductChat extends Component
{
    public int $productId = 0;
    public int $threadUserId = 0;
    public bool $showRatingModal = false;
    public int $ratedUserId = 0;
    public int $selectedStars = 0;

    #[Validate('required|string|max:1000')]
    public string $body = '';

    public function mount(int $productId, int $threadUserId): void
    {
        $this->productId    = $productId;
        $this->threadUserId = $threadUserId;
        $this->markRead();
        $this->checkPendingRating();
    }

    private function checkPendingRating(): void
    {
        $product = Product::find($this->productId);
        if (! $product || ! $product->isSold()) {
            return;
        }

        $alreadyRated = Rating::where('product_id', $this->productId)
            ->where('rater_id', Auth::id())
            ->exists();

        if ($alreadyRated) {
            return;
        }

        $isSeller = Auth::id() === $product->user_id;
        $this->ratedUserId      = $isSeller ? $this->threadUserId : $product->user_id;
        $this->showRatingModal  = true;
    }

    public function sendMessage(): void
    {
        $this->validate();

        $product    = Product::with('user')->find($this->productId);
        $threadUser = User::find($this->threadUserId);

        $isBuyer  = Auth::id() === $this->threadUserId;
        $isSeller = $product && Auth::id() === $product->user_id;

        if (! Auth::user()->is_admin && ! $isBuyer && ! $isSeller) {
            abort(403);
        }

        $message = Message::create([
            'product_id'     => $this->productId,
            'thread_user_id' => $this->threadUserId,
            'sender_id'      => Auth::id(),
            'body'           => trim($this->body),
            'created_at'     => now(),
        ]);

        $recipient = (Auth::id() === $product->user_id) ? $threadUser : $product->user;

        if ($recipient && ! $recipient->isOnline()) {
            Mail::to($recipient->email)->send(new MessageReceivedMail($message, $recipient, Auth::user()));
        }

        $this->body = '';
        $this->markRead();
    }

    private function markRead(): void
    {
        Message::where('product_id', $this->productId)
            ->where('thread_user_id', $this->threadUserId)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        Auth::user()->update(['last_seen_at' => now()]);
    }

    public function toggleReserved(): void
    {
        $product = Product::find($this->productId);
        abort_if(! $product || $product->user_id !== Auth::id(), 403);

        $product->update(['estado' => $product->isReserved() ? 'activo' : 'reservado']);
    }

    public function markAsSold(): void
    {
        $product = Product::find($this->productId);
        abort_if(! $product || $product->user_id !== Auth::id(), 403);

        $product->update(['estado' => 'vendido']);

        // Mostrar modal de valoración al vendedor inmediatamente
        $alreadyRated = Rating::where('product_id', $this->productId)
            ->where('rater_id', Auth::id())
            ->exists();

        if (! $alreadyRated) {
            $this->ratedUserId     = $this->threadUserId;
            $this->showRatingModal = true;
        }
    }

    public function submitRating(int $stars): void
    {
        if ($stars < 1 || $stars > 5 || ! $this->ratedUserId) {
            return;
        }

        $product = Product::find($this->productId);
        if (! $product) {
            return;
        }

        $isSeller = Auth::id() === $product->user_id;
        $isBuyer  = Auth::id() === $this->threadUserId;

        if (! $isSeller && ! $isBuyer) {
            return;
        }

        Rating::firstOrCreate(
            ['product_id' => $this->productId, 'rater_id' => Auth::id()],
            ['rated_user_id' => $this->ratedUserId, 'stars' => $stars]
        );

        $this->showRatingModal = false;
        $this->selectedStars   = 0;
    }

    public function skipRating(): void
    {
        $this->showRatingModal = false;
        $this->selectedStars   = 0;
    }

    public function refreshMessages(): void
    {
        $this->markRead();
    }

    public function render()
    {
        $messages   = Message::with('sender')
            ->where('product_id', $this->productId)
            ->where('thread_user_id', $this->threadUserId)
            ->orderBy('created_at')
            ->get();

        $product    = Product::with(['images', 'user'])->find($this->productId);
        $threadUser = User::find($this->threadUserId);

        return view('livewire.shop.product-chat', compact('messages', 'product', 'threadUser'));
    }
}
