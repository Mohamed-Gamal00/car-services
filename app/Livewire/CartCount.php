<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Cart;

class CartCount extends Component
{
    public $count;

    protected $listeners = ['cartUpdated' => 'updateCount'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        $this->count = Cart::count();
    }

    public function render()
    {
        return view('livewire.cart-count');
    }
}
