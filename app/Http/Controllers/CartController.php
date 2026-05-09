<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart  = session('cart', []);
        $total = collect($cart)->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));

        return view('cart', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $qty  = max(1, (int) $request->input('quantity', 1));
        $cart = session('cart', []);
        $id   = $product->product_id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = [
                'id'       => $id,
                'title'    => $product->title,
                'artist'   => $product->artist?->name ?? '',
                'price'    => (float) $product->price,
                'image'    => $product->image,
                'quantity' => $qty,
            ];
        }

        session(['cart' => $cart]);

        return back();
    }

    public function update(Request $request, int $id)
    {
        $cart = session('cart', []);
        $qty  = (int) $request->input('quantity', 1);

        if (isset($cart[$id])) {
            if ($qty < 1) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $qty;
            }
        }

        session(['cart' => $cart]);

        return back();
    }

    public function remove(int $id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Cart cleared.');
    }
}
