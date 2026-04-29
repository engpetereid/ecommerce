<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{
    static public function addItemToCart($product_id, $variant_id = null, $qty = 1, $options = [])
    {
        $cart_items = self::getCartItemsFromCookie();

        $product = Product::find($product_id);

        if (!$product) {
            return count($cart_items);
        }

        $price = $product->price;
        $image = $product->image;

        if ($variant_id) {
            $variant = $product->variants()->find($variant_id);
            if ($variant) {
                $price = $variant->price ?? $product->price;
            }
        }

        $item_key = $product_id . '-' . ($variant_id ?? '0');

        if (isset($cart_items[$item_key])) {
            $cart_items[$item_key]['quantity'] += $qty;
            $cart_items[$item_key]['total_price'] = $cart_items[$item_key]['quantity'] * $cart_items[$item_key]['unit_price'];
        } else {

            $cart_items[$item_key] = [
                'product_id' => $product_id,
                'variant_id' => $variant_id,
                'name' => $product->name,
                'image' => $image,
                'quantity' => $qty,
                'unit_price' => $price,
                'total_price' => $price * $qty,
                'options' => $options,
            ];
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    static public function removeCartItem($product_id, $variant_id = null)
    {
        $cart_items = self::getCartItemsFromCookie();
        // مفتاح العنصر هو دمج رقم المنتج مع رقم المتغير
        $item_key = $product_id . '-' . ($variant_id ?? '0');

        if (isset($cart_items[$item_key])) {
            unset($cart_items[$item_key]);
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    static public function incrementQuantityToItem($product_id, $variant_id = null)
    {
        $cart_items = self::getCartItemsFromCookie();
        $item_key = $product_id . '-' . ($variant_id ?? '0');

        if (isset($cart_items[$item_key])) {
            $cart_items[$item_key]['quantity']++;
            // إعادة حساب السعر الإجمالي لهذا المنتج
            $cart_items[$item_key]['total_price'] = $cart_items[$item_key]['quantity'] * $cart_items[$item_key]['unit_price'];
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    static public function decrementQuantityToItem($product_id, $variant_id = null)
    {
        $cart_items = self::getCartItemsFromCookie();
        $item_key = $product_id . '-' . ($variant_id ?? '0');

        if (isset($cart_items[$item_key])) {
            // ننقص فقط إذا كانت الكمية أكبر من 1
            if ($cart_items[$item_key]['quantity'] > 1) {
                $cart_items[$item_key]['quantity']--;
                $cart_items[$item_key]['total_price'] = $cart_items[$item_key]['quantity'] * $cart_items[$item_key]['unit_price'];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    static public function getCartItemsFromCookie()
    {
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if (!$cart_items) {
            $cart_items = [];
        }
        return $cart_items;
    }

    static public function addCartItemsToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }

    static public function calculateGrandTotal($items)
    {
        return array_sum(array_column($items, 'total_price'));
    }


    static public function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }


}
