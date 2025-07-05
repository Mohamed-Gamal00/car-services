<div>
    <form wire:submit.prevent="checkDiscountCode">
        @csrf
        <input wire:model="discountCode" type="text" placeholder="Enter discount code">
        <button type="submit">Check</button>
    </form>
</div>
