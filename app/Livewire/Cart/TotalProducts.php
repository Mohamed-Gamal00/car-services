<?php

namespace App\Livewire\Cart;

use App\Repositories\Cart\CartRepository;
use Livewire\Component;

class TotalProducts extends Component
{
    public $cart;

    public function render(CartRepository $cart)
    {
        $this->cart = $this->getTotalProducts($cart); // Injected into the method
        return view('livewire.cart.total-products');
    }

    public function getTotalProducts(CartRepository $cart)
    {
        return $cart->get()->sum('quantity');
    }

    public function mount(CartRepository $cart)
    {
        $this->cart = $this->getTotalProducts($cart); // Initial value
        $this->pollForTotalProducts($cart); // Pass CartRepository instance to the method
    }

    public function pollForTotalProducts(CartRepository $cart)
    {

        $this->cart = $this->getTotalProducts($cart);

    }
}
