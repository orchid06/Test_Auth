<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function create(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:30',
            'cpassword' => 'required|min:5|max:30|same:password'
        ]);

        $user = new User();
        $user->name     = $request->input('name');
        $user->email    = $request->email;
        $user->password = ($request->password);
        $user->save();

        Auth::guard('web')->login($user);

        return redirect()->route('user.index')->with('success', 'You are now registered successfully');
    }

    public function check(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:5|max:30'
        ], [
            'email.exists' => 'This email is not exists on user table'
        ]);

        return   Auth::guard('web')->attempt($request->only('email', 'password'))
            ? redirect()->route('user.index')->with('success', 'you are logged in')
            : redirect()->route('user.login')->with('fail', 'Incorrect credentials');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect('/');
    }

    public function index(): View
    {
        $products = Product::with(['carts'])->withCount(['carts'])->latest()->get();

        $totalQty = $totalPrice = 0;

        $products = $products->map(function (Product $product) use (&$totalQty, &$totalPrice) {
            $cartQty     = $product->carts->sum('qty');
            $totalQty   += $product->qty + $cartQty;
            return $product;
        });

        $totalProduct = Product::count();

        return view('dashboard.user.home', compact('products', 'totalQty', 'totalProduct'));
    }

    public function toggleActive(Request $request, $id)
    {
        $user = User::findorfail($id);
        $user->update([
            'is_active' => $request->input('is_active')
        ]);

        return redirect()->back()->with('success', 'User active status updated');
    }

    // public function toggleActive(Request $request)
    // {
    //     $userId = $request->input('id');
    //     $user = User::find($userId);

    //     if (!$user) {
    //         return response()->json(['error' => 'User not found'], 404);
    //     }

    //     $user->update([
    //         'is_active' => $request->input('is_active')
    //     ]);

    //     return response()->json(['message' => 'User active status updated successfully'], 200);
    // }
}
