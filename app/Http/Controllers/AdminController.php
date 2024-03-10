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

    public function check(Request $request) : RedirectResponse
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

    public function logout() : RedirectResponse
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }

    public function index(): View
    {
        $users    = User::with(['carts'])->withCount(['carts'])->latest()->get();

        $totalQty = $totalPrice = 0;

        $cartQty = $users->map(function (User $users) {
            $cartQty     = $users->carts->sum('qty');
            return $cartQty;
        });

        return view('dashboard.admin.home', compact('users', 'cartQty'));
    }
}
