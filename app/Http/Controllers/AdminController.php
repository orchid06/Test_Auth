<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;


class AdminController extends Controller
{

    public function check(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:admins,email',
            'password' => 'required|min:5|max:30'
        ], [
            'email.exists' => 'This email is not exists on admin table'
        ]);

        $creds = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($creds)) {
            return back();  
        } else {
            return redirect()->route('admin.login')->with('fail', 'Incorrect credentials');
        }
    }

    public function logout()
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

        return view('dashboard.admin.home', compact('products', 'totalQty', 'totalProduct'));
    }
}
