<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    public function shipping()
    {
        if (empty(session('cart', []))) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        // Guests are allowed
        $user = Auth::user();

        return view('cart_shipping', compact('user'));
    }

    public function processShipping(Request $request)
    {
        $shipping = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|max:30',
            'country' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:12',
            'address' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
        ],
        [   // Error messages
            'phone.regex' => 'Invalid phone number',
        ]);

        session(['shipping' => $shipping]);

        return redirect()->route('orders.payment');
    }

    public function payment()
    {
        if (empty(session('cart')) || empty(session('shipping'))) {
            return redirect()->route('cart.shipping');
        }

        $cart = session('cart', []);
        $total = collect($cart)->sum(fn ($i) => $i['price'] * $i['quantity']);

        return view('cart_payment', compact('total'));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);
        $shipping = session('shipping', []);

        if (empty($cart) || empty($shipping)) {
            return redirect()->route('cart');
        }

        $request->validate([
            'payment_method' => 'required|in:card,paypal,bank',
            'card_name' => 'required|string|max:255',
            'card_number' => [
                'required',
                'regex:/^[0-9\s]{13,19}$/' ], // Only numbers
            'mmyy' => [
                'required',
                'regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/'], // MM/YY
            'cvv' => 'required|numeric|digits:3',
        ],
        [   // Error messages
            'card_number.regex' => 'Invalid card number.',
            'mmyy.regex' => 'Invalid MM/YY',
            'cvv.digits' => 'Invalid CVV',
        ]);

        $total = collect($cart)->sum(fn ($i) => $i['price'] * $i['quantity']);

        $order = Order::create([
            ...$shipping,
            'user_id' => Auth::id(), // null for guests
            'status' => 'paid',
            'total' => $total,
            'payment_method' => $request->payment_method,
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'title' => $item['title'],
                'artist' => $item['artist'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        session()->forget(['cart', 'shipping']);

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        }

        // Store guest order token
        if (!Auth::check()) {
            session(['guest_order_id' => $order->order_id]);
        }

        return redirect()->route('orders.show', $order)->with('success', true);
    }

    public function show(Order $order)
    {
        // Authenticated user only
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        // Guest can only see an order
        if (!Auth::check() && $order->user_id !== null) {
            abort(403);
        }

        if (!Auth::check() && session('guest_order_id') !== $order->order_id) {
            abort(403);
        }

        $order->load('items.product.images');

        // Check guest account-creation attempt
        $guestEmail = $order->email;
        $canRegister = !Auth::check() && !User::where('email', $guestEmail)->exists();

        return view('cart_order', compact('order', 'canRegister'));
    }

    // Allow guest to register and claim their order
    public function claimOrder(Request $request, Order $order)
    {
        // Only for guest orders in the current session
        if (Auth::check() || session('guest_order_id') !== $order->order_id) {
            abort(403);
        }

        if ($order->user_id !== null) {
            return redirect()->route('orders.show', $order)->with('error', 'This order is already linked to an account.');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        // Create account using the shipping email/name
        $user = User::create([
            'name' => $order->first_name . ' ' . $order->last_name,
            'email' => $order->email,
            'password' => Hash::make($request->password),
        ]);

        // Link order to the new account
        $order->update(['user_id' => $user->id]);

        Auth::login($user);
        $request->session()->regenerate();
        session()->forget('guest_order_id');

        return redirect()->route('orders.show', $order)->with('success', 'Account created and order linked!');
    }
}
