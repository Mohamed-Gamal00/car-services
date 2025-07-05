<?php

namespace App\Livewire\Products;

use Livewire\WithPagination;

use App\Models\Product;
use Livewire\Component;


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

        if(empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $products = Product::latest()->with('parent')
            ->where('name', 'like', '%' . $this->search . '%')
            ->withoutTrashed()->paginate(5);

        return view('livewire.products.product-index', compact('products'));
    }
}
