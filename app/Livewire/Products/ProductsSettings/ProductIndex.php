<?php

namespace App\Livewire\Products\ProductsSettings;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    public $search;


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with('parent')->withoutTrashed();

        if ($this->search !== null) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $products = $query->paginate(5);

        return view('livewire.products.products-settings.product-index', ['products' => $products]);
    }
}
