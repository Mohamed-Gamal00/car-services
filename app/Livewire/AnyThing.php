<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DiscountCode;

class AnyThing extends Component
{
    public $discountCode;

    public function checkDiscountCode()
    {
        // Your validation and processing logic here
        $discountCodeExists = DiscountCode::where('code', $this->discountCode)->exists();
        if ($discountCodeExists) {
            return $discountCodeExists;
        } else {
            dd('error');
        }
    }

    public function render()
    {
        return view('livewire.any-thing');
    }
}
