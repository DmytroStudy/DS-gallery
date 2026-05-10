<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $orders = $user->orders()->with('items')->latest()->paginate(5);

        return view('account', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate(['name' => 'required|string|max:255', 'email' => 'required|email|unique:users,email,' . $user->id,]);

        $user->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],]);

        Auth::user()->update(['password' => Hash::make($request->password),]);

        return back()->with('success', 'Password changed successfully.');
    }
}
