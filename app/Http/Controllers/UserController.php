<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create(Request $request){

        $request->validate([
            'name' => 'required',
            'email'=> 'required|email|unique:users,email',
            'password'=>'required|min:5|max:30',
            'cpassword'=>'required|min:5|max:30|same:password'
        ]);

        $user = new User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = \Hash::make($request->password);
        $save = $user->save();

        if($save){
            return redirect()->back()->with('success', 'You are now registered successfully');
        }
        else{
            return redirect()->back()->with('fail', 'something went wrong, failed to register');
        }

    }

    public function check(Request $request){
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' =>'required|min:5|max:30'
        ],[
            'email.exists' =>'This email is not exists on user table'
        ]);

        $creds = $request->only('email','password');
        if(Auth::guard('web')->attempt($creds)){
            return back();
        }
        else{
            return redirect()->route('login')->with('fail', 'Incorrect credentials');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
