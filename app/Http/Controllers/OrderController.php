<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function shipping()
    {
        if (empty(session('cart', []))) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to continue.');
        }

        $user = Auth::user();

        return view('cart_shipping', compact('user'));
    }

    public function payment(Request $request)
    {
        if (empty(session('cart', []))) {
            return redirect()->route('cart');
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $shipping = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'email'       => 'required|email',
            'phone'       => 'nullable|string|max:30',
            'country'     => 'required|string',
            'city'        => 'required|string',
            'postal_code' => 'required|string|max:20',
            'address'     => 'required|string',
            'address2'    => 'nullable|string',
        ]);

        session(['shipping' => $shipping]);

        $cart = session('cart', []);
        $total = collect($cart)->sum(fn ($i) => $i['price'] * $i['quantity']);

        return view('cart_payment', compact('total'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cart = session('cart', []);
        $shipping = session('shipping', []);

        if (empty($cart) || empty($shipping)) {
            return redirect()->route('cart');
        }

        $request->validate([
            'payment_method' => 'required|in:card,paypal,bank',
        ]);

        $total = collect($cart)->sum(fn ($i) => $i['price'] * $i['quantity']);

        $order = Order::create([
            ...$shipping,
            'user_id'        => Auth::id(),
            'status'         => 'paid',
            'total'          => $total,
            'payment_method' => $request->payment_method,
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'artwork_id' => $item['id'],
                'title'      => $item['title'],
                'artist'     => $item['artist'],
                'price'      => $item['price'],
                'quantity'   => $item['quantity'],
            ]);
        }

        session()->forget(['cart', 'shipping']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully! Thank you for your purchase.');
    }

    public function show(Order $order)
    {
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items');

        return view('order_detail', compact('order'));
    }
}
