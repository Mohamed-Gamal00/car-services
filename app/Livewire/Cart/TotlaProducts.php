<?php

namespace App\Livewire\Cart;

use App\Repositories\Cart\CartRepository;
use Livewire\Component;

class TotalProducts extends Component
{
    public $cart;

    public function updateTotalProducts(CartRepository $cart)
    {
        $this->cart = $cart->get()->sum('quantity');
    }

    public function render()
    {
        return view('livewire.cart.totla-products');
    }
}
