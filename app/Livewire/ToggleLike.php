<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ToggleLike extends Component
{
    public int $productId = 0;
    public bool $liked = false;
    public int $count = 0;

    public function mount(int $productId): void
    {
        $this->productId = $productId;

        if (Auth::check()) {
            $this->liked = Auth::user()->likedProducts()->where('product_id', $productId)->exists();
        }

        $this->count = Product::find($productId)?->likedByUsers()->count() ?? 0;
    }

    public function toggle(): void
    {
        if (! Auth::check()) {
            $this->redirectRoute('login');
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($this->liked) {
            $user->likedProducts()->detach($this->productId);
            $this->liked = false;
            $this->count--;
        } else {
            $user->likedProducts()->attach($this->productId);
            $this->liked = true;
            $this->count++;
        }
    }

    public function render()
    {
        return view('livewire.toggle-like');
    }
}
