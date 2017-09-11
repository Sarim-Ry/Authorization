<?php

namespace App\Http\Controllers;

use app\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();
        return view('home')
        ->with('users', $users);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        if(empty($user))
        {
            return redirect()->route('home');
        }
        return view('auth.edit')
        ->with('user', $user);
    }

    public function updated($id, Request $request)
    {
        $telco_operator = "011";
        $this->validate($request, [
            'first_name' => 'required|string|max:35',
            'last_name' => 'required|string|max:35',
            'email' => 'required|email',
            'phone_number' => 'required|regex:/(0)[0-9]{8,9}+$/',
            'password' => 'confirmed',
        ]);

        $user = User::findOrFail($id);
        $newPassword = $request->get('password');
        if(empty($user))
        {
            return redirect()->route('home');
        }
        if(empty($newPassword)){
            $user->update($request->except('password'));
        }else{
            $user->password = bcrypt('password');
            $user->update($request->all());
        }
        return redirect()->route('home');
    }

    public function deleted($id, Request $request)
    {
        $user = User::findOrFail($id);
        if(empty($user))
        {
            return redirect()->route('home');
        }
        $user->delete($request);
        return redirect()->route('home');
    }
}
