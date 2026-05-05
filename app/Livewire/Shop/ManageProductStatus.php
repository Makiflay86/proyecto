<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManageProductStatus extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function toggleReserved()
    {
        abort_if($this->product->user_id !== Auth::id(), 403);

        if ($this->product->isReserved()) {
            $this->product->update(['estado' => 'activo']);
            session()->flash('success', '¡Reserva cancelada!');
        } else {
            $this->product->update(['estado' => 'reservado']);
            session()->flash('success', '¡Producto reservado!');
        }
    }

    public function markAsSold()
    {
        abort_if($this->product->user_id !== Auth::id(), 403);

        $this->product->update(['estado' => 'vendido']);
        session()->flash('success', '¡Producto vendido!');
    }

    public function reactivate()
    {
        abort_if($this->product->user_id !== Auth::id(), 403);

        $this->product->update(['estado' => 'activo']);
        session()->flash('success', '¡Producto reactivado!');
    }

    public function render()
    {
        return view('livewire.shop.manage-product-status');
    }
}
