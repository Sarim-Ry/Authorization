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

        return view('home')->with('users', $users);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        if( empty($user) )
        {
            return redirect()->route('home');
        }

        return view('auth.edit')->with('user', $user);
    }

    public function updated($id, Request $request)
    {

        // santize data - remove all the characters & special characters from the string
        $user_phone = preg_replace('/[^0-9\-]/', '', $request->phone_number);

        // regerate constatnt array
        $telco_operator = array(
            '012', '085', '011', '093'
        );

        // split every 3 digits to array
        $phone_array = str_split( $user_phone, 3 );

        // validate if first 3digits start with 0 otherwise reject
        $prefix_phone = substr( $phone_array[0], 0, 1);

        // reject if not false
        if( $prefix_phone != '0' )
        {
            // what to do next if it isn't Zero prefix.
        }

        foreach( $telco_operator as $telco ) {

            // compare if the first index array match to the telco number
            if( strcmp($phone_array[0], $telco) == 0 ) {

                $correct_phone = $user_phone;

            }

        }

        $this->validate( $request, [
            'first_name' => 'required|string|max:35',
            'last_name' => 'required|string|max:35',
            'email' => 'required|email',
            'phone_number' => 'required|regex:/[0-9]{6,7}+$/',
            'password' => 'confirmed',
        ]);

        $user = User::findOrFail($id);

        $newPassword = $request->get('password');

        if(empty($user))
        {
            return redirect()->route('home');
        }

        if(empty($newPassword)) {

            $user->update($request->except('password'));

        } else {

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
