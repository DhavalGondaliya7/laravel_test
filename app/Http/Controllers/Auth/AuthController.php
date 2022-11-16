<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('product')
                ->withSuccess('You have Successfully loggedin');
        }

        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }

    public function postRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('product')
                ->withSuccess('You have Successfully loggedin');
        }

        return redirect("registration")->withError('something went wrong');
    }

    public function forgot_password(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email',
            ]);
            $email  = $request->email;
            $user   = User::where('email', $email)->first();

            if (!empty($user)) {
                $access_token = uniqid();

                User::where('email', $email)->update(['remember_token' => $access_token]);

                $link = URL('reset-password/' . $access_token);
                $data['link'] = $link;
                return view('auth.success', $data);
            }
            return redirect()->back()->withError('Email Not Register.');
        } else {
            return view('auth.forgot_password');
        }
    }

    public function reset_password(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'password'          => 'required|min:6|max:13',
                'confirm_password'  => 'required|same:password',
            ]);

            $user = User::where('remember_token', $request->access_token)->first();
            if (!empty($user)) {
                User::where('remember_token', $request->access_token)->update(['password' => Hash::make($request->password), 'remember_token' => '']);
                Session::flash('success', 'Password change successfully');
                return redirect('login');
            } else {
                return redirect()->back()->withError('User not found');
            }
        } else {
            $data['token'] = $request->token;
            return view('auth.reset_password', $data);
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
