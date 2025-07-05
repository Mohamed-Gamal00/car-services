<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DiscountCode;

//class DiscountCode extends Component
//{
//    public $discountCode;
//
//    public function checkDiscountCode()
//    {
//        // Validate the discount code
//        $this->validate([
//            'discountCode' => 'required|string',
//        ]);
//
//        // Check if the discount code exists
//        if (DiscountCode::where('code', $this->discountCode)->exists()) {
//            session()->flash('message', 'Discount code is valid.');
//        } else {
//            session()->flash('error', 'Invalid discount code.');
//        }
//    }
//
//    public function render()
//    {
//        return view('livewire.discount-code-checker');
//    }
//}
