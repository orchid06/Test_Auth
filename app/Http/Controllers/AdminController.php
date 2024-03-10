<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;


class AdminController extends Controller
{

    public function check(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email|exists:admins,email',
            'password' => 'required|min:5|max:30'
        ], [
            'email.exists' => 'This email is not exists on admin table'
        ]);


        return   Auth::guard('admin')->attempt($request->only('email', 'password'))
            ? back()
            : redirect()->route('admin.login')->with('fail', 'Incorrect credentials');
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }

    public function index(): View
    {
        $users    = User::withcount('carts')->get();

        return view('dashboard.admin.home', compact('users'));
    }

    public function productView(): View
    {
        $products = Product::with(['carts'])->withCount(['carts'])->latest()->get();

        $totalQty = $totalPrice = 0;

        $products = $products->map(function (Product $product) use (&$totalQty, &$totalPrice) {
            $cartQty     = $product->carts->sum('qty');
            $totalQty   += $product->qty + $cartQty;
            return $product;
        });

        $totalProduct = Product::count();

        return view('dashboard.admin.product', compact('products', 'totalQty', 'totalProduct'));
    }

    public function viewCart($id): View
    {
        $user  = auth('web')->user();
        $user = User::findorfail($id);
        $user_name = $user->name;

        $cartProducts = $user->carts;

        $totalCartProduct = $cartProducts->count();
        $totalCartQty     = $cartProducts->sum('qty');
        $totalCartPrice   = $cartProducts->sum(function ($cartProduct) {
            return $cartProduct->price * $cartProduct->qty;
        });

        return view('dashboard.user.cart', compact('cartProducts', 'totalCartProduct', 'totalCartQty', 'totalCartPrice', 'user_name'));
    }

    public function userCreate(Request $request): RedirectResponse
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

        return back()->with('success', 'User registered successfully');
    }

    public function userUpdate(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|max:30',
            'cpassword' => 'required|min:5|max:30|same:password'
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        return back()->with('success', 'User Updated');
    }

    public function userDelete($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
