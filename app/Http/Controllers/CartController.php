<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getCart(): array
    {
        return session('cart', []);
    }

    private function syncToDb(array $cart): void
    {
        if (!Auth::check()) return;

        $userId = Auth::id();
        CartItem::where('user_id', $userId)->delete();

        foreach ($cart as $id => $item) {
            if (!$id || (int)$id <= 0) continue;

            CartItem::create([
                'user_id' => $userId,
                'product_id' => (int)$id,
                'quantity' => $item['quantity'],
            ]);
        }
    }

    private function loadFromDb(): array
    {
        $cart = [];
        $items = CartItem::where('user_id', Auth::id())->with('product.images')->get();

        foreach ($items as $item) {
            $product = $item->product;
            if (!$product) continue;

            $firstImage = $product->images->first()?->img_path ?? 'icons/img.svg';
            $id = $product->product_id;

            $cart[$id] = [
                'id' => $id,
                'title' => $product->title,
                'artist' => $product->artist?->name ?? '',
                'price' => (float) $product->price,
                'image' => $firstImage,
                'quantity' => $item->quantity,
            ];
        }

        return $cart;
    }

    public function index()
    {
        // Load from DB
        if (Auth::check() && empty(session('cart', []))) {
            $cart = $this->loadFromDb();
            session(['cart' => $cart]);
        }

        $cart = $this->getCart();
        $total = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 0));

        return view('cart', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('quantity', 1));
        $cart = $this->getCart();
        $id = $product->product_id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $firstImage = $product->images->first()?->img_path ?? 'icons/img.svg';
            $cart[$id]  = [
                'id' => $id,
                'title' => $product->title,
                'artist' => $product->artist?->name ?? '',
                'price' => (float) $product->price,
                'image' => $firstImage,
                'quantity' => $qty,
            ];
        }

        session(['cart' => $cart]);
        $this->syncToDb($cart);

        return back();
    }

    public function update(Request $request, int $id)
    {
        $cart = $this->getCart();
        $qty = (int) $request->input('quantity', 1);

        if (isset($cart[$id])) {
            if ($qty < 1) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $qty;
            }
        }

        session(['cart' => $cart]);
        $this->syncToDb($cart);

        return back();
    }

    public function remove(int $id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);

        session(['cart' => $cart]);
        $this->syncToDb($cart);

        return back()->with('success');
    }

    public function clear()
    {
        session()->forget('cart');

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        }

        return back()->with('success');
    }
}
