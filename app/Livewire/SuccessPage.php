<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\Title;

class SuccessPage extends Component
{
    #[Title('Order Placed')]
    public function render()
    {
        return view('livewire.success-page');
    }
}
