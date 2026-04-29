<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\CartManagement;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;

class CheckoutPage extends Component
{
    // بيانات النموذج
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $zip_code;
    public $payment_method = 'cod';

    public $cart_items = [];
    public $grand_total;

    public function mount()
    {
        $this->cart_items = CartManagement::getCartItemsFromCookie();
        if (count($this->cart_items) == 0) {
            return redirect()->route('home');
        }
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    public function placeOrder()
    {
        // 1. التحقق من البيانات
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'payment_method' => 'required',
        ]);

        // 2. عمل الطلب
        $order = Order::create([
            'user_id' => auth()->id(),
            'number' => 'ORD-' . Str::random(10),
            'total_price' => $this->grand_total,
            'status' => 'pending',
            'payment_method' => $this->payment_method,
            'address_info' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'street_address' => $this->street_address,
                'city' => $this->city,
                'zip_code' => $this->zip_code,
            ],
        ]);

        // 3. حفظ تفاصيل المنتجات (Order Items)
        foreach ($this->cart_items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['variant_id'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);
        }

        // 4. تفريغ السلة وتحديث العداد
        CartManagement::clearCartItems();
        $this->dispatch('cart_updated');

        // 5. توجيه لصفحة النجاح
        return redirect()->route('success');
    }

    #[Title('Checkout')]
    public function render()
    {
        return view('livewire.checkout-page');
    }
}
