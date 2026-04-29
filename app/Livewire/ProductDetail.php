<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Helpers\CartManagement;
use Livewire\Attributes\Title;

class ProductDetail extends Component
{
    public $slug;
    public $product;
    public $selectedOptions = [];
    public $currentPrice;
    public $currentStock;
    public $quantity = 1;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->product = Product::with('variants')->where('slug', $slug)->firstOrFail();

        $this->currentPrice = $this->product->price;

        if ($this->product->has_variants && $this->product->variants->count() > 0) {
            $firstVariant = $this->product->variants->first();
            $this->selectedOptions = $firstVariant->options;
            $this->updateVariantStatus();
        } else {
            $this->currentStock = 100;
        }
    }

    public function updatedSelectedOptions()
    {
        $this->updateVariantStatus();
        $this->quantity = 1;
    }

    public function updateVariantStatus()
    {
        if (!$this->product->has_variants) return;

        $variant = $this->product->variants->first(function ($v) {
            return empty(array_diff_assoc($this->selectedOptions, $v->options));
        });

        if ($variant) {
            $this->currentPrice = $variant->price ?? $this->product->price;
            $this->currentStock = $variant->stock;
        } else {
            $this->currentStock = 0;
        }
    }

    //  زيادة الكمية
    public function incrementQty()
    {
        if ($this->quantity < $this->currentStock) {
            $this->quantity++;
        }
    }

    public function decrementQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        // التحقق من المخزون
        if ($this->product->has_variants && $this->currentStock <= 0) {
            return;
        }

        $variantId = null;
        if ($this->product->has_variants) {
            $variant = $this->product->variants->first(function ($v) {
                return empty(array_diff_assoc($this->selectedOptions, $v->options));
            });
            $variantId = $variant ? $variant->id : null;
        }

        // إضافة للسلة بالكمية المختارة
        $total_count = CartManagement::addItemToCart(
            $this->product->id,
            $variantId,
            $this->quantity,
            $this->selectedOptions
        );

        $this->dispatch('cart_updated', $total_count);

        $this->quantity = 1;

        session()->flash('success', 'Product added to cart successfully!');
    }

    #[Title('Product Details')]
    public function render()
    {
        $availableOptions = [];

        if ($this->product->has_variants) {
            foreach ($this->product->variants as $variant) {
                foreach ($variant->options as $key => $value) {
                    $availableOptions[$key][] = $value;
                }
            }
            foreach ($availableOptions as $key => $values) {
                $availableOptions[$key] = array_unique($values);
            }
        }

        return view('livewire.product-detail', [
            'availableOptions' => $availableOptions
        ]);
    }
}
