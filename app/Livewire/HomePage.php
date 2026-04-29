<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Helpers\CartManagement;
use Livewire\Attributes\Title;

class HomePage extends Component
{
    #[Title('Home Page - Best Deals')]
    public function render()
    {
        $products = Product::where('is_active', true)
            ->latest()
            ->take(12)
            ->get();

        return view('livewire.home-page', [
            'products' => $products
        ]);
    }

    // لإضافة المنتج للسلة مباشرة من الصفحة الرئيسية
    public function addToCart($productId)
    {
        $product = Product::find($productId);

        // لو المنتج له متغيرات (لون/مقاس)، لازم يروح صفحة التفاصيل يختار الأول
        if ($product->has_variants) {
            return redirect()->route('products.show', $product->slug);
        }

        // لو منتج بسيط، اضافه للسلة
        $count = CartManagement::addItemToCart($product->id);

        $this->dispatch('cart_updated', $count);
        $this->dispatch('notify', message: 'Added to cart successfully');
    }
}
