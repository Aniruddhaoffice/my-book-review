<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{

    
    //this file view register page
    public function register()
    {
        return view('account.register');
    }



    //this methode will register a user 
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:5',
            'name' => 'required|min:3',
            'password_confirmation' => 'required'

        ]);
        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'user';
            $user->save();

            return redirect()->route('account.login')->with('success', 'You have registed successfully');


        } else {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }
    }

    //this methode for show login page 
    public function login()
    {
        return view('account.login');
    }



      //this methode for authenticate user 

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'

        ]);
        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.profile')->with('Enter email or Password is incorrect');

            } else {
                return redirect()->route('account.login')->withInput()->with('error', 'Enter email or Password is incorrect');
            }
        } else {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }

    }

    //this methode for show profile page 

    public function profile()
    {
        return view('account.profile');
    }

    //this methode for logout user

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
     }


}
