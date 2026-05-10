<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Auth::check() ? redirect('/') : view('login');
    }

    public function showRegister()
    {
        return Auth::check() ? redirect('/') : view('register');
    }

    private function mergeCartOnLogin(): void
    {
        $sessionCart = session('cart', []);
        $userId = Auth::id();

        // Loading items
        $dbItems = CartItem::where('user_id', $userId)->get();
        $merged = $sessionCart;

        foreach ($dbItems as $dbItem) {
            $id = $dbItem->product_id;

            // Grouping items
            if (isset($merged[$id])) {
                $merged[$id]['quantity'] += $dbItem->quantity;
            } else {
                $product = $dbItem->product;
                if (!$product) continue;

                $merged[$id] = [
                    'id' => $id,
                    'title' => $product->title,
                    'artist' => $product->artist?->name ?? '',
                    'price' => (float) $product->price,
                    'image' => $product->images->first()?->img_path ?? 'icons/img.svg',
                    'quantity' => $dbItem->quantity,
                ];
            }
        }

        session(['cart' => $merged]);

        // Updating DB
        CartItem::where('user_id', $userId)->delete();
        foreach ($merged as $id => $item) {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $id,
                'quantity' => $item['quantity'],
            ]);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $this->mergeCartOnLogin();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Wrong email or password.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
