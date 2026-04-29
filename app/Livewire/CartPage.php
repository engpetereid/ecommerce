<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\CartManagement;
use Livewire\Attributes\Title;

class CartPage extends Component
{
    public $cart_items = [];
    public $grand_total;

    #[Title('Shopping Cart')]
    public function mount()
    {
        $this->cart_items = CartManagement::getCartItemsFromCookie();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    //  ينادي عليها زر Remove
    public function removeItem($product_id, $variant_id = null)
    {
        $this->cart_items = CartManagement::removeCartItem($product_id, $variant_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);

        // تحديث العداد في الشريط العلوي
        $this->dispatch('cart_updated', count($this->cart_items));
    }

    //   ينادي عليها زر (+)
    public function increaseQty($product_id, $variant_id = null)
    {
        $this->cart_items = CartManagement::incrementQuantityToItem($product_id, $variant_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    // ينادي عليها زر (-)
    public function decreaseQty($product_id, $variant_id = null)
    {
        $this->cart_items = CartManagement::decrementQuantityToItem($product_id, $variant_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
