<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\CartManagement;
use Livewire\Attributes\On;

class Navbar extends Component
{
    public $total_count = 0;

    public function mount()
    {
        $this->total_count = count(CartManagement::getCartItemsFromCookie());
    }


    #[On('cart_updated')]
    public function updateCartCount()
    {
        $this->total_count = count(CartManagement::getCartItemsFromCookie());
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}
